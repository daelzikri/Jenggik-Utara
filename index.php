<?php
require_once 'backend/koneksi.php';

// Fallback Data if DB is not connected
$profil_desa = [
    'judul_profil' => 'Profil Desa Jenggik Utara',
    'potensi_desa' => 'Pusat UMKM Lokal: VCO (Minyak Kelapa), Klepon, Anyaman Bambu, Gula Aren, dan Aneka Jajanan Tradisional',
    'informasi_desa' => "Jenggik Utara adalah desa yang terletak di dataran tinggi dengan ketinggian sekitar 400 mdpl, terdiri dari 11 dusun yang membentang dari utara ke selatan. Udara di sini sejuk dan tanahnya subur, menjadikan desa ini kaya akan potensi alam dan ekonomi kreatif masyarakatnya.\n\nSebagai Desa Berdaya, Jenggik Utara memiliki ragam potensi UMKM yang menjadi kebanggaan lokal. Kami sangat mengutamakan 5 produk unggulan desa: VCO (Minyak Kelapa) murni, Klepon legendaris, Kerajinan Anyaman Bambu hasil tangan terampil warga, Gula Aren asli dari nira pilihan, serta Aneka Jajanan Tradisional lainnya yang terus dilestarikan.\n\nMelalui program digitalisasi UMKM ini, kami hadir untuk membantu mengangkat produk-produk unggulan tersebut agar lebih dikenal luas dan bernilai jual tinggi. Setiap pembelian Anda adalah bentuk dukungan langsung bagi perekonomian warga Desa Jenggik Utara.",
    'gambar_desa' => 'assets/beranda/kantor desa.jpg'
];

$umkm_sorotan = [
    [
        'id' => 1,
        'nama_produk' => 'Jajanan Tradisional Lombok',
        'tagline' => 'Apapun yang dipesan, kami usahakan selalu bisa menerima pesanan.',
        'gambar_umkm' => 'assets/umkm/paket_jajanan.jpg'
    ],
    [
        'id' => 2,
        'nama_produk' => 'Gula Aren & Anyaman Bambu',
        'tagline' => 'Produk Tradisional dari Alam, 100% Asli Desa Jenggik Utara.',
        'gambar_umkm' => 'assets/umkm/gula aren.jpg'
    ]
];

$testimoni_list = [
    ['nama_pengirim' => 'Budi Santoso', 'jabatan' => 'Wisatawan', 'isi_testimoni' => 'Gula arennya sangat terasa alami, wanginya beda dengan yang ada di pasaran.', 'rating' => 5],
    ['nama_pengirim' => 'Siti Aminah', 'jabatan' => 'Pelanggan', 'isi_testimoni' => 'Kue basahnya enak sekali, pesan untuk begawe kemarin semuanya puas.', 'rating' => 5],
    ['nama_pengirim' => 'Andi Wijaya', 'jabatan' => 'Pelanggan Setia', 'isi_testimoni' => 'Keranjang anyaman karya Bapak Satar rapi dan kuat. Sudah beli 3 untuk wadah hasil bumi.', 'rating' => 5]
];

// Fetch from DB if available
if ($pdo) {
    // Fetch Profil
    $stmt = $pdo->query("SELECT * FROM profile_desa LIMIT 1");
    if ($row = $stmt->fetch()) {
        $profil_desa = $row;
    }

    // Fetch UMKM Sorotan
    $stmt = $pdo->query("SELECT id, slug, nama_produk, tagline, gambar_umkm FROM umkm ORDER BY id ASC LIMIT 3");
    $db_umkm = $stmt->fetchAll();
    if ($db_umkm) {
        $umkm_sorotan = $db_umkm;
    }

    // Fetch Testimoni
    try {
        $stmt = $pdo->query("SELECT * FROM testimoni WHERE is_visible = 1 ORDER BY id DESC LIMIT 5");
    } catch (PDOException $e) {
        // Fallback for when column does not exist yet
        $stmt = $pdo->query("SELECT * FROM testimoni ORDER BY id DESC LIMIT 5");
    }
    $db_testi = $stmt->fetchAll();
    if ($db_testi) {
        $testimoni_list = $db_testi;
    }
}

// Handle new testimoni submission from user
$submit_message = '';
if (isset($_POST['submit_testimoni']) && $pdo) {
    $nama = $_POST['nama_pengirim'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    $isi = $_POST['isi_testimoni'] ?? '';
    $rating = (int)($_POST['rating'] ?? 5);
    
    if (!empty($nama) && !empty($isi)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO testimoni (nama_pengirim, jabatan, isi_testimoni, rating, is_visible) VALUES (?, ?, ?, ?, 0)");
            $stmt->execute([$nama, $jabatan, $isi, $rating]);
            $submit_message = '<div style="background:#e8f5e9; color:#2e7d32; padding:15px; text-align:center; margin-bottom: 20px; border-radius: 5px; font-weight:bold;">Terima kasih! Testimoni Anda telah dikirim dan menunggu persetujuan admin.</div>';
        } catch (PDOException $e) {
            // Attempt auto create column if error
            if (strpos($e->getMessage(), "Unknown column 'is_visible'") !== false) {
                 try {
                     $pdo->exec("ALTER TABLE testimoni ADD COLUMN is_visible TINYINT(1) DEFAULT 1 AFTER rating");
                     $stmt = $pdo->prepare("INSERT INTO testimoni (nama_pengirim, jabatan, isi_testimoni, rating, is_visible) VALUES (?, ?, ?, ?, 0)");
                     $stmt->execute([$nama, $jabatan, $isi, $rating]);
                     $submit_message = '<div style="background:#e8f5e9; color:#2e7d32; padding:15px; text-align:center; margin-bottom: 20px; border-radius: 5px; font-weight:bold;">Terima kasih! Testimoni Anda telah dikirim dan menunggu persetujuan admin.</div>';
                 } catch(PDOException $ex) {
                     $submit_message = '<div style="background:#ffebee; color:#c62828; padding:15px; text-align:center; margin-bottom: 20px; border-radius: 5px;">Gagal mengirim testimoni. Silakan coba lagi.</div>';
                 }
            } else {
                 $submit_message = '<div style="background:#ffebee; color:#c62828; padding:15px; text-align:center; margin-bottom: 20px; border-radius: 5px;">Gagal mengirim testimoni. Silakan coba lagi.</div>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A3626">
    <meta name="description" content="Jenggik Utara - Website resmi Desa Jenggik Utara. Jelajahi keindahan alam, potensi desa, dan katalog lengkap produk unggulan UMKM lokal kami seperti VCO, Klepon, Anyaman Bambu, Gula Aren, dan Jajanan Tradisional.">
    <meta name="keywords" content="Jenggik Utara, Desa Jenggik Utara, KKN PMD UNRAM, UMKM Desa Jenggik Utara, VCO Jenggik Utara, Klepon, Anyaman Bambu, Gula Aren, Jajanan Tradisional Lombok, Karsaloka">
    <meta name="author" content="Pemdes Jenggik Utara">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://umkmjenggikutara.com/">
    <meta property="og:title" content="Desa Jenggik Utara - Portal Komunitas & Gerai UMKM Terpadu">
    <meta property="og:description" content="Jelajahi keindahan alam dan potensi UMKM lokal Desa Jenggik Utara. Temukan produk autentik langsung dari pengrajin.">
    <meta property="og:image" content="https://umkmjenggikutara.com/assets/beranda/beranda1.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://umkmjenggikutara.com/">
    <meta property="twitter:title" content="Desa Jenggik Utara - Portal Komunitas & Gerai UMKM Terpadu">
    <meta property="twitter:description" content="Jelajahi keindahan alam dan potensi UMKM lokal Desa Jenggik Utara. Temukan produk autentik langsung dari pengrajin.">
    <meta property="twitter:image" content="https://umkmjenggikutara.com/assets/beranda/beranda1.jpg">

    <title>Desa Jenggik Utara - Portal Komunitas & Gerai UMKM Terpadu</title>
    <link rel="canonical" href="https://umkmjenggikutara.com/">
    
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
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Karsaloka Desa Jenggik Utara",
      "url": "https://umkmjenggikutara.com/"
    }
    </script>
</head>
<body>

    <header>
        <nav>
            <div class="logo" style="display: flex; align-items: center;">KARSALOKA</div>
            <button class="menu-toggle" id="mobile-menu-btn" aria-label="Toggle menu">☰</button>
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="#profil">Profil Desa</a></li>
                <li><a href="katalog.php">Gerai UMKM</a></li>
                <li><a href="#testimoni">Testimoni</a></li>
            </ul>
        </nav>
    </header>

    <?php if($db_error): ?>
    <div style="background:#ffebee; color:#c62828; padding:15px; text-align:center;">
        <strong>Perhatian:</strong> Database belum terhubung. Menampilkan data dummy. <br>
        Error: <?= htmlspecialchars($db_error) ?>
    </div>
    <?php endif; ?>

    <!-- Hero Section (Slider) -->
    <section class="hero" style="padding:0; max-width:100%;">
        <!-- Carousel Images -->
        <div class="carousel">
            <div class="carousel-slide active" style="background-image: url('assets/beranda/beranda1.jpg');"></div>
            <div class="carousel-slide" style="background-image: url('assets/beranda/beranda2.jpg');"></div>
            <div class="carousel-slide" style="background-image: url('assets/beranda/beranda3.jpg');"></div>
        </div>
        <div class="carousel-overlay"></div>
        
        <div class="hero-content">
            <h1 style="color: #ffffff;">Temukan Keindahan Alam<br> & Potensi Desa</h1>
            <p style="color: #eaeaea;">Jelajahi keindahan alam kami dan temukan berbagai produk lokal unggulan kualitas terbaik langsung dari para pengrajin desa.</p>
            <a href="katalog.php" class="btn">Mulai Eksplorasi</a>
        </div>
        <!-- Floating Info Bar -->
        <div class="floating-info-bar" style="position: absolute; bottom: -40px; left: 50%; transform: translateX(-50%); width: 85%; max-width: 900px; background: var(--card-bg); padding: 1.2rem 2.5rem; border-radius: 50px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-md); z-index: 100;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 45px; height: 45px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">🌲</div>
                <div>
                    <div style="font-size: 0.8rem; color: #666; font-weight: 600; text-transform: uppercase;">Lokasi</div>
                    <div style="font-weight: 800; color: var(--primary-dark); font-size: 1.1rem;">Desa Jenggik Utara</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 45px; height: 45px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">🧺</div>
                <div>
                    <div style="font-size: 0.8rem; color: #666; font-weight: 600; text-transform: uppercase;">Produk</div>
                    <div style="font-weight: 800; color: var(--primary-dark); font-size: 1.1rem;">UMKM Lokal</div>
                </div>
            </div>
            <a href="#umkm" class="btn" style="padding: 0.8rem 2rem; background: var(--primary-dark); color: white;">Lihat Detail</a>
        </div>
        <!-- Elegant Gradient Divider -->
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 6px; background: linear-gradient(90deg, transparent, rgba(217,119,70,0.8), transparent); z-index: 5;"></div>
    </section>

    <!-- Profil Singkat & Potensi -->
    <section id="profil" style="margin-top: 60px;">
        <h2 class="section-title">Profil Desa</h2>
        <div class="profile-content">
            <div class="profile-image">
                <img src="<?= htmlspecialchars($profil_desa['gambar_desa']) ?>" alt="Profil Desa">
            </div>
            <div class="profile-text">
                <h3 style="font-size: 2.2rem; color: var(--primary-dark); font-weight: 800; line-height: 1.2; margin-bottom: 1rem;"><?= htmlspecialchars($profil_desa['judul_profil']) ?></h3>
                
                <?php if(!empty($profil_desa['potensi_desa'])): ?>
                <div style="background: rgba(217, 119, 70, 0.1); border-left: 4px solid var(--secondary-color); padding: 15px; margin-bottom: 20px; border-radius: 0 8px 8px 0;">
                    <strong style="color: var(--secondary-color); display: block; margin-bottom: 5px;">Potensi Unggulan:</strong>
                    <span style="color: #444; font-weight: 600; line-height: 1.5;"><?= htmlspecialchars($profil_desa['potensi_desa']) ?></span>
                </div>
                <?php endif; ?>

                <p style="margin-bottom: 20px; font-size: 1.1rem; color: #555; text-align: justify;"><?= nl2br(htmlspecialchars($profil_desa['informasi_desa'])) ?></p>
                <div style="display: flex; gap: 2rem; margin-top: 2rem;">
                    <div>
                        <h4 style="font-size: 1.8rem; color: var(--secondary-color); font-weight: 800; margin-bottom: 5px;">100%</h4>
                        <span style="font-weight: 600; color: #666; font-size: 0.9rem; text-transform: uppercase;">Natural</span>
                    </div>
                    <div>
                        <h4 style="font-size: 1.8rem; color: var(--secondary-color); font-weight: 800; margin-bottom: 5px;">Handmade</h4>
                        <span style="font-weight: 600; color: #666; font-size: 0.9rem; text-transform: uppercase;">Lokal</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- UMKM Unggulan -->
    <!-- Gradient Divider Top for UMKM section -->
    <div style="width: 100%; height: 6px; background: linear-gradient(90deg, transparent, rgba(26,54,38,0.2), transparent); margin-top: 2rem;"></div>
    <section id="umkm" style="position: relative; padding: 5rem 5% 6rem; background: var(--primary-light); max-width: 100%;">
        <div style="max-width: 1400px; margin: 0 auto;">
            <h2 class="section-title" style="color: var(--primary-dark);">Produk Unggulan Desa</h2>
            <div class="product-grid">
            <?php foreach($umkm_sorotan as $umkm): ?>
            <div class="product-card">
                <div class="product-img">
                    <img src="<?= htmlspecialchars($umkm['gambar_umkm']) ?>" alt="<?= htmlspecialchars($umkm['nama_produk']) ?>">
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?= htmlspecialchars($umkm['nama_produk']) ?></h3>
                    <p class="product-tagline"><?= htmlspecialchars($umkm['tagline']) ?></p>
                    <div class="product-action">
                        <a href="detail.php?<?= !empty($umkm['slug']) ? 'slug=' . urlencode($umkm['slug']) : 'id=' . $umkm['id'] ?>" class="btn">Lihat Detail Produk</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 4rem;">
                <a href="katalog.php" class="btn" style="background: var(--primary-dark);">Lihat Semua Produk</a>
            </div>
        </div>
    </section>
    
    <!-- Gradient Divider Bottom for UMKM section -->
    <div style="width: 100%; height: 6px; background: linear-gradient(90deg, transparent, rgba(26,54,38,0.2), transparent);"></div>

    <!-- Testimoni -->
    <section id="testimoni">
        <div class="testimonials" style="position: relative;">
            <h2 class="section-title">Apa Kata Mereka</h2>
            <div style="text-align: center; margin-bottom: 30px;">
                <button id="btnTambahTesti" class="btn" style="background: var(--primary-dark); padding: 10px 25px; cursor: pointer;">Tambahkan Testimoni Anda</button>
            </div>
            
            <?= $submit_message ?>

            <div class="testi-slider" id="testiSlider">
                <?php foreach($testimoni_list as $index => $testi): ?>
                <div class="testi-slide <?= $index === 0 ? 'active' : '' ?>">
                    <div class="stars">
                        <?php for($i=0; $i<$testi['rating']; $i++) echo "★"; ?>
                    </div>
                    <p class="testi-content">"<?= htmlspecialchars($testi['isi_testimoni']) ?>"</p>
                    <p class="testi-author"><?= htmlspecialchars($testi['nama_pengirim']) ?></p>
                    <p class="testi-role"><?= htmlspecialchars($testi['jabatan']) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

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

    <!-- Testimoni Modal -->
    <div id="testiModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); position: relative;">
            <button id="closeTestiModal" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #333;">&times;</button>
            <h3 style="margin-top: 0; color: var(--primary-dark); margin-bottom: 20px; text-align:center;">Tambahkan Testimoni Anda</h3>
            <form method="POST" action="index.php#testimoni">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Nama Anda</label>
                    <input type="text" name="nama_pengirim" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-family: inherit;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Profesi / Peran (Opsional)</label>
                    <input type="text" name="jabatan" placeholder="Misal: Wisatawan, Pelanggan" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-family: inherit;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Rating</label>
                    <select name="rating" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-family: inherit;">
                        <option value="5">5 Bintang - Sangat Bagus</option>
                        <option value="4">4 Bintang - Bagus</option>
                        <option value="3">3 Bintang - Cukup</option>
                        <option value="2">2 Bintang - Kurang</option>
                        <option value="1">1 Bintang - Sangat Kurang</option>
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600;">Pesan Anda</label>
                    <textarea name="isi_testimoni" required rows="4" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-family: inherit;"></textarea>
                </div>
                <button type="submit" name="submit_testimoni" class="btn" style="width: 100%; padding: 12px; background: var(--secondary-color); color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; text-align: center;">Kirim Testimoni</button>
            </form>
        </div>
    </div>

    <script>
        // Hero Carousel Script
        const slides = document.querySelectorAll('.carousel-slide');
        let currentSlide = 0;

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        setInterval(nextSlide, 3000); // Change image every 3 seconds

        // Testimonial Slider Script
        const testiSlides = document.querySelectorAll('.testi-slide');
        let currentTesti = 0;

        function nextTesti() {
            if(testiSlides.length === 0) return;
            testiSlides[currentTesti].classList.remove('active');
            currentTesti = (currentTesti + 1) % testiSlides.length;
            testiSlides[currentTesti].classList.add('active');
        }

        setInterval(nextTesti, 4000); // Change testimonial every 4 seconds

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

            // Testimoni Modal Logic
            const btnTambahTesti = document.getElementById('btnTambahTesti');
            const testiModal = document.getElementById('testiModal');
            const closeTestiModal = document.getElementById('closeTestiModal');

            if (btnTambahTesti && testiModal && closeTestiModal) {
                btnTambahTesti.addEventListener('click', () => {
                    testiModal.style.display = 'flex';
                });
                closeTestiModal.addEventListener('click', () => {
                    testiModal.style.display = 'none';
                });
                window.addEventListener('click', (e) => {
                    if (e.target === testiModal) {
                        testiModal.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>
</html>
