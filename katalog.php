<?php
require_once 'backend/koneksi.php';

// Fallback Data if DB is not connected
$umkm_list = [
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

// Fetch from DB if available
if ($pdo) {
    $stmt = $pdo->query("SELECT id, nama_produk, tagline, gambar_umkm FROM umkm ORDER BY id ASC");
    $db_umkm = $stmt->fetchAll();
    if ($db_umkm) {
        $umkm_list = $db_umkm;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A3626">
    <meta name="description" content="Jenggik Utara - Katalog lengkap produk unggulan UMKM Desa Jenggik Utara. Temukan berbagai produk karya asli masyarakat desa mulai dari Gula Aren hingga Kerajinan Bambu.">
    <meta name="keywords" content="Jenggik Utara, Katalog UMKM Jenggik Utara, Belanja Produk Desa Jenggik Utara, Gula Semut Jenggik Utara, Jajanan Tradisional Lombok, Kerajinan Anyaman Bambu">
    <meta name="author" content="Pemdes Jenggik Utara">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://umkmjenggikutara.com/katalog.php">
    <meta property="og:title" content="Gerai UMKM - Desa Jenggik Utara">
    <meta property="og:description" content="Katalog produk unggulan UMKM Desa Jenggik Utara. Temukan berbagai karya asli masyarakat desa kami.">
    <meta property="og:image" content="https://umkmjenggikutara.com/assets/beranda/beranda2.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://umkmjenggikutara.com/katalog.php">
    <meta property="twitter:title" content="Gerai UMKM - Desa Jenggik Utara">
    <meta property="twitter:description" content="Katalog produk unggulan UMKM Desa Jenggik Utara. Temukan berbagai karya asli masyarakat desa kami.">
    <meta property="twitter:image" content="https://umkmjenggikutara.com/assets/beranda/beranda2.jpg">

    <title>Gerai UMKM - Desa Jenggik Utara</title>
    <link rel="canonical" href="https://umkmjenggikutara.com/katalog.php">
    
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
      "name": "Gerai UMKM Jenggik Utara",
      "url": "https://umkmjenggikutara.com/katalog.php"
    }
    </script>
    <style>
        .page-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: white;
            text-align: center;
            padding: 8rem 20px 6rem;
            position: relative;
        }
        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>

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

    <div class="page-header">
        <h1>Katalog Produk UMKM Desa</h1>
        <p>Berbagai produk unggulan karya asli masyarakat desa kami.</p>
        
        <!-- Torn Edge Divider -->
        <div style="position: absolute; bottom: 0; left: 0; width: 100%; overflow: hidden; line-height: 0; z-index: 5;">
            <svg viewBox="0 0 1200 50" preserveAspectRatio="none" style="display: block; width: 100%; height: 50px;">
                <polygon points="0,50 1200,50 1200,20 1170,35 1130,10 1090,35 1050,15 1000,40 960,10 920,40 880,15 840,40 800,10 760,35 720,15 680,45 640,15 600,40 560,10 520,35 480,15 440,40 400,10 360,35 320,15 280,40 240,10 200,35 160,15 120,45 80,15 40,35 0,20" fill="var(--bg-color)"/>
            </svg>
        </div>
    </div>

    <!-- Semua UMKM -->
    <section id="semua-umkm" style="padding: 3rem 2% 6rem; max-width: 100%;">
        <?php if($db_error): ?>
        <div style="background:#ffebee; color:#c62828; padding:15px; text-align:center; margin-bottom: 2rem; border-radius:10px;">
            <strong>Perhatian:</strong> Database belum terhubung. Menampilkan data dummy.
        </div>
        <?php endif; ?>

        <div class="product-grid">
            <?php foreach($umkm_list as $umkm): ?>
            <div class="product-card">
                <div class="product-img">
                    <img src="<?= htmlspecialchars($umkm['gambar_umkm']) ?>" alt="<?= htmlspecialchars($umkm['nama_produk']) ?>">
                </div>
                <div class="product-info">
                    <h3 class="product-title"><?= htmlspecialchars($umkm['nama_produk']) ?></h3>
                    <p class="product-tagline"><?= htmlspecialchars($umkm['tagline']) ?></p>
                    <div class="product-action">
                        <a href="detail.php?id=<?= $umkm['id'] ?>" class="btn">Lihat Detail Produk</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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
