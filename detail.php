<?php
require_once 'backend/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
if ($id === 0 && empty($slug)) {
    $id = 1; // Default fallback
}
$product = null;
$kontak = null;

// Fallback Dummy Data Array based on the Markdown specs
$dummy_products = [
    1 => [
        'nama_produk' => 'Jajanan Tradisional Lombok',
        'tagline' => 'Apapun yang dipesan, kami usahakan selalu bisa menerima pesanan.',
        'deskripsi' => 'Jenis pesanan yang dibuat biasanya tidak jauh-jauh dari jenis jajanan tradisional yang ada di Lombok, seperti kue basah yang sering diperuntukkan untuk acara begawe (hajatan). Inak Khaeril Alpaeni menceritakan, "Yang saya buat adalah jajanan tradisional atau apa yang orang-orang pesan, itu yang saya buat." Beliau selalu mengusahakan untuk bisa menerima semua pesanan demi memenuhi kebutuhan ekonomi keluarga.',
        'informasi_tambahan' => '"Saya kesusahan pada bagian keuangan, modal yang saya miliki sangat terbatas untuk membeli bahan-bahan. Jika tidak ada uang maka usaha ini tidak mungkin bisa berjalan," ungkapnya. Oleh karena itu, pembeli biasanya datang untuk membayar uang muka (DP). Jika pesanan sepi, Inak Khaeril Alpaeni juga mencoba membuat keranjang demi membantu perekonomian keluarga. Pemesanan dilakukan melalui ponsel, biasanya dari rekomendasi pelanggan sebelumnya.',
        'latar_belakang_usaha' => '"Usaha ini dimulai dari diri saya sendiri karena saya tidak mendapatkan pendidikan khusus baik dari sekolah maupun orang tua," kata Inak Khaeril Alpaeni. Usaha ini sudah berjalan kurang lebih belasan tahun, sejak anaknya berumur 10 bulan hingga sekarang sudah SMA. "Dulu pesanan dimulai dari harga 30rb, kemudian pembeli merasakan rasanya. Setelah itu datang lagi pembeli melakukan pre order skala lebih besar yaitu 100rb."',
        'gambar_umkm' => 'assets/umkm/paket_jajanan.jpg',
        'no_wa' => '6281234567890',
        'pesan_wa' => 'Halo Inak Khaeril Alpaeni, saya tertarik memesan Jajanan Tradisional Lombok untuk acara saya. Boleh diskusi pesanannya?',
        'alur_proses' => [
            ['judul' => 'Pemesanan & Uang Muka (DP)', 'deskripsi' => 'Pemesan datang ke rumah untuk memesan dan menjelaskan apa yang mereka inginkan, serta membayar uang muka (DP). "Karena saya benar-benar tidak memiliki modal sama sekali, saya meminta uang muka pada pembeli untuk memproduksi jajanan," jelas beliau.'],
            ['judul' => 'Proses Produksi', 'deskripsi' => 'Setelah menerima DP, produksi sesuai pesanan dimulai. "Terkadang saya mengerjakan pesanan ini hingga jam 11 malam bersama anak saya supaya ada uang untuk berbelanja kebutuhan sehari-hari," tambahnya.'],
            ['judul' => 'Pengambilan Pesanan', 'deskripsi' => 'Setelah jajanan disiapkan dengan telaten, pembeli akan datang untuk mengambil jajan yang telah siap diambil.']
        ]
    ],
    2 => [
        'nama_produk' => 'Gula Aren & Anyaman Bambu',
        'tagline' => 'Produk Tradisional dari Alam, 100% Asli Desa Jenggik Utara.',
        'deskripsi' => 'Usaha ini mencakup dua produk unggulan yaitu Gula Aren dan Anyaman Bambu. Produk gula aren dari desa ini menggunakan 100% air nira tanpa campuran apapun. Semua prosesnya masih sangat tradisional menggunakan tungku, kayu bakar, serta cetakan tempurung kelapa. Selain itu, juga diproduksi kerajinan anyaman bambu seperti keranjang dan keraro (randang).',
        'informasi_tambahan' => 'Untuk gula aren, dijual dengan harga Rp 20.000 per cetakan (1 bilaq) atau sering dijual per 6 pcs seharga Rp 100.000 kepada para pengepul. Sedangkan harga jual keranjang biasanya Rp 25.000 untuk satu keranjang, dan randang dijual di kisaran harga Rp 120.000 hingga Rp 150.000 tergantung ukurannya.',
        'latar_belakang_usaha' => 'Usaha-usaha ini ditekuni oleh Bapak Satar. Latar belakang dari dimulainya usaha ini adalah karena kesulitan ekonomi keluarga Bapak Satar yang membuatnya terdorong untuk melakukan usaha produksi berskala rumahan. Beliau mempelajari cara membuat keranjang secara otodidak dengan melihat dan mengamati pola keranjang yang telah dibuat oleh tetangga sekitarnya dahulu. Sedangkan untuk produksi gula aren, beliau telah belajar secara turun temurun dari nenek moyang yang memanfaatkan potensi yang disediakan oleh alam.',
        'gambar_umkm' => 'assets/umkm/gula aren.jpg',
        'no_wa' => '6281234567891',
        'pesan_wa' => 'Halo Bapak Satar, saya tertarik memesan produk Gula Aren atau Anyaman Bambu. Boleh diskusi?',
        'alur_proses' => [
            ['judul' => 'Pembuatan Gula Aren: Persiapan & Pemasakan', 'deskripsi' => 'Pertama, air nira diambil dari pohon nira. Kedua, masak air nira menggunakan kuwali di atas tungku, kemudian tunggu hingga mendidih.'],
            ['judul' => 'Pembuatan Gula Aren: Pengentalan & Pencetakan', 'deskripsi' => 'Setelah mendidih, air nira diaduk terus menerus hingga mengental. Untuk memastikan nira siap, sedikit gula dicelupkan ke air; jika mengeras maka siap dicetak ke dalam tempurung kelapa dan didiamkan hingga mengeras.'],
            ['judul' => 'Pembuatan Anyaman Bambu', 'deskripsi' => 'Pembuatan dimulai dengan menyiapkan bambu. Kemudian bambu dipotong menjadi bilah-bilah sebelum dianyam secara teliti menjadi keranjang, keraro, maupun dandang.']
        ]
    ]
];

if ($pdo) {
    // Fetch from DB
    if (!empty($slug)) {
        $stmt = $pdo->prepare("SELECT * FROM umkm WHERE slug = ?");
        $stmt->execute([$slug]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM umkm WHERE id = ?");
        $stmt->execute([$id]);
    }
    $db_prod = $stmt->fetch();
    
    if ($db_prod) {
        $id = $db_prod['id']; // Update ID untuk kontak
        $product = $db_prod;
        
        $stmt_kontak = $pdo->prepare("SELECT * FROM kontak WHERE umkm_id = ?");
        $stmt_kontak->execute([$id]);
        $kontak = $stmt_kontak->fetch();
        
        $product['no_wa'] = $kontak ? $kontak['no_wa'] : '628000000000';
        $product['pesan_wa'] = $kontak ? $kontak['pesan_wa_default'] : 'Halo, saya tertarik dengan produk ini.';
        $product['link_facebook'] = $kontak ? $kontak['link_facebook'] : '';
    }
} 

if (!$product) {
    // Fallback to Dummy if DB not connected or ID not found
    $product = isset($dummy_products[$id]) ? $dummy_products[$id] : $dummy_products[1];
}

// Generate WA Link
$wa_url = "https://wa.me/" . $product['no_wa'] . "?text=" . urlencode($product['pesan_wa']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A3626">
    <meta name="description" content="<?= htmlspecialchars(substr(strip_tags($product['deskripsi']), 0, 155)) ?>...">
    <meta name="keywords" content="Jenggik Utara, <?= htmlspecialchars($product['nama_produk']) ?>, UMKM Desa Jenggik Utara, Produk Lokal Jenggik Utara, Gula Aren Jenggik Utara, Karsaloka">
    <meta name="author" content="Pemdes Jenggik Utara">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="product">
    <meta property="og:url" content="https://umkmjenggikutara.com/detail.php?id=<?= $id ?>">
    <meta property="og:title" content="<?= htmlspecialchars($product['nama_produk']) ?> - UMKM Desa Jenggik Utara">
    <meta property="og:description" content="<?= htmlspecialchars(substr(strip_tags($product['deskripsi']), 0, 155)) ?>...">
    <meta property="og:image" content="https://umkmjenggikutara.com/<?= htmlspecialchars($product['gambar_umkm']) ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://umkmjenggikutara.com/detail.php?id=<?= $id ?>">
    <meta property="twitter:title" content="<?= htmlspecialchars($product['nama_produk']) ?> - UMKM Desa Jenggik Utara">
    <meta property="twitter:description" content="<?= htmlspecialchars(substr(strip_tags($product['deskripsi']), 0, 155)) ?>...">
    <meta property="twitter:image" content="https://umkmjenggikutara.com/<?= htmlspecialchars($product['gambar_umkm']) ?>">

    <title><?= htmlspecialchars($product['nama_produk']) ?> - UMKM Desa Jenggik Utara</title>
    <link rel="canonical" href="https://umkmjenggikutara.com/detail.php?id=<?= $id ?>">
    
    <!-- Favicon / App Icons -->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="KARSALOKA">
    <link rel="manifest" href="/site.webmanifest">

    <link rel="stylesheet" href="assets/css/style.css?v=<?= time() ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Structured Data (Schema.org) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "<?= htmlspecialchars($product['nama_produk']) ?>",
      "image": "https://umkmjenggikutara.com/<?= htmlspecialchars($product['gambar_umkm']) ?>",
      "description": "<?= htmlspecialchars(strip_tags($product['deskripsi'])) ?>",
      "brand": {
        "@type": "Brand",
        "name": "UMKM Desa Jenggik Utara"
      }
    }
    </script>
</head>
<body style="background: var(--bg-color);">

    <header>
        <nav>
            <div class="logo" style="display: flex; align-items: center;">KARSALOKA</div>
            <button class="menu-toggle" id="mobile-menu-btn" aria-label="Toggle menu">☰</button>
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="index.php#profil">Profil Desa</a></li>
                <li><a href="katalog.php">Gerai UMKM</a></li>
                <li><a href="index.php#testimoni">Testimoni</a></li>
            </ul>
        </nav>
    </header>

    <div class="detail-container" style="padding-top: 120px;">


        <div class="product-detail-layout">
            <div class="detail-gallery">
                <h4 style="margin-bottom: 10px; color:var(--primary-color);">Foto Produk</h4>
                <img class="detail-main-img" src="<?= htmlspecialchars($product['gambar_umkm']) ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" style="margin-bottom: 20px;">
                
                <?php if(!empty($product['logo_umkm']) || !empty($product['proses_umkm'])): ?>
                <div style="display: flex; gap: 15px; margin-top: 15px;">
                    <?php if(!empty($product['logo_umkm'])): ?>
                    <div>
                        <h4 style="margin-bottom: 10px; font-size: 0.9rem; color:var(--primary-dark);">Logo UMKM</h4>
                        <img src="<?= htmlspecialchars($product['logo_umkm']) ?>" alt="Logo UMKM" style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    </div>
                    <?php endif; ?>
                    <?php if(!empty($product['proses_umkm'])): ?>
                    <div>
                        <h4 style="margin-bottom: 10px; font-size: 0.9rem; color:var(--primary-dark);">Proses Produksi</h4>
                        <img src="<?= htmlspecialchars($product['proses_umkm']) ?>" alt="Proses Produksi" style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>
            <div class="detail-info">
                <h1><?= htmlspecialchars($product['nama_produk']) ?></h1>
                <p class="detail-tagline">"<?= htmlspecialchars($product['tagline']) ?>"</p>
                
                <h3 style="margin-bottom:10px; color:var(--primary-color);">Keunggulan Produk</h3>
                <p class="detail-desc"><?= nl2br(htmlspecialchars($product['deskripsi'])) ?></p>
                
            </div>

            <!-- Row 2 for Grid Layout -->
            <div class="detail-latar-belakang" style="height: 100%; display: flex; flex-direction: column;">
                <div style="height: 100%; padding: 15px; background: var(--bg-color); border-radius: 12px; border-left: 4px solid var(--secondary-color);">
                    <h4 style="margin-bottom: 5px; color: var(--primary-dark); font-size: 1rem;">Latar Belakang Usaha</h4>
                    <p style="font-size: 0.9rem; color: #555;"><?= nl2br(htmlspecialchars(!empty($product['latar_belakang_usaha']) ? $product['latar_belakang_usaha'] : 'Usaha ini berawal dari inisiatif warga desa untuk mengangkat potensi sumber daya alam lokal dan melestarikan tradisi turun-temurun.')) ?></p>
                </div>
            </div>

            <div class="detail-tambahan-dan-tombol" style="height: 100%; display: flex; flex-direction: column;">
                <div style="flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <?php if(!empty($product['informasi_tambahan'])): ?>
                    <div class="detail-extra">
                        <strong>Informasi Tambahan:</strong><br>
                        <?= nl2br(htmlspecialchars($product['informasi_tambahan'])) ?>
                    </div>
                    <?php endif; ?>
                    
                    <div style="margin-top: 15px;">
                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <a href="<?= $wa_url ?>" target="_blank" class="btn btn-wa" style="flex: 1; min-width: 200px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                </svg>
                                WhatsApp
                            </a>
                            <?php if(!empty($product['link_facebook'])): ?>
                            <a href="<?= htmlspecialchars($product['link_facebook']) ?>" target="_blank" class="btn btn-fb" style="flex: 1; min-width: 200px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                                </svg>
                                Facebook
                            </a>
                            <?php endif; ?>
                        </div>
                        <p style="text-align: center; font-size: 0.85rem; color: #888; margin-top: 10px;">
                            Anda akan diarahkan langsung ke WhatsApp pengrajin UMKM ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Resolve alur proses for rendering
    $alur_proses_data = [];
    if (!empty($product['alur_proses'])) {
        if (is_string($product['alur_proses'])) {
            $alur_proses_data = json_decode($product['alur_proses'], true);
        } else {
            $alur_proses_data = $product['alur_proses'];
        }
    }
    ?>
    <div class="timeline-section" style="max-width: 1200px; margin: 4rem auto 6rem; padding: 0 5%;">
        <h2 class="section-title" style="margin-bottom: 3rem; text-align: left; font-size: 2.2rem; margin-left: 0;">Alur Proses Kerja</h2>
        <div class="timeline-container">
            <?php if (!empty($alur_proses_data) && is_array($alur_proses_data)): ?>
                <?php $step = 1; foreach($alur_proses_data as $proses): ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h3><?= $step ?>. <?= htmlspecialchars($proses['judul']) ?></h3>
                        <p><?= htmlspecialchars($proses['deskripsi']) ?></p>
                    </div>
                </div>
                <?php $step++; endforeach; ?>
            <?php else: ?>
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h3>Informasi Proses Belum Tersedia</h3>
                        <p>Alur proses kerja untuk produk ini sedang dalam tahap pembaruan data.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="footer-content footer-grid">
            <!-- Kolom 1: Tentang -->
            <div class="footer-col">
                <div class="footer-logo">UMKM DESA JENGGIK UTARA</div>
                <p class="footer-desc">Koleksi produk unggulan UMKM dari Desa Jenggik Utara, Kabupaten Lombok Timur. Berkomitmen memajukan ekonomi lokal melalui karya autentik masyarakat desa.</p>
                
                <h4 class="footer-heading-small">IKUTI KAMI</h4>
                <div class="social-links">
                    <a href="#" class="social-icon fb-icon" title="Facebook">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.312h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/></svg>
                    </a>
                    <a href="#" class="social-icon ig-icon" title="Instagram">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="social-icon wa-icon" title="WhatsApp">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>

                <div class="footer-badge">
                    <strong>100% Produk Lokal</strong>
                    <p>Setiap produk diproduksi secara etis dan otentik oleh warga desa.</p>
                </div>
            </div>

            <!-- Kolom 2: Tautan Cepat -->
            <div class="footer-col">
                <h3 class="footer-heading">TAUTAN CEPAT</h3>
                <ul class="footer-links">
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="index.php#profil">Profil Desa</a></li>
                    <li><a href="katalog.php">Gerai UMKM</a></li>
                    <li><a href="index.php#testimoni">Testimoni</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Kontak -->
            <div class="footer-col">
                <h3 class="footer-heading">KONTAK</h3>
                <div class="contact-item">
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </span>
                    <div>
                        <strong>Lokasi Kami</strong>
                        <p>Desa Jenggik Utara, Kec. Montong Gading<br>Kab. Lombok Timur, NTB 83347</p>
                    </div>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                    </span>
                    <div>
                        <strong>Telepon</strong>
                        <p>0878-6142-4102</p>
                    </div>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    </span>
                    <div>
                        <strong>Email</strong>
                        <p>pemdesjenggikutara2@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="copyright">
            &copy; KKN PMD UNRAM 2026. All rights reserved.
        </div>
    </footer>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if(window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', () => {
            const menuBtn = document.getElementById('mobile-menu-btn');
            const navLinks = document.getElementById('nav-links');
            if (menuBtn && navLinks) {
                menuBtn.addEventListener('click', () => {
                    navLinks.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
