-- phpMyAdmin SQL Dump
-- Database: `website_kkn`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `profile_desa`
--

CREATE TABLE `profile_desa` (
  `id` int(11) NOT NULL,
  `potensi_desa` text NOT NULL,
  `informasi_desa` text NOT NULL,
  `gambar_desa` varchar(255) NOT NULL,
  `judul_profil` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profile_desa`
--

INSERT INTO `profile_desa` (`id`, `potensi_desa`, `informasi_desa`, `gambar_desa`, `judul_profil`) VALUES
(1, 'Pertanian (Alpukat, Manggis, Durian) dan Ekonomi Kreatif (Anyaman Bambu, Gula Aren, Jajanan Tradisional)', 'Jenggik Utara adalah desa yang terletak di dataran tinggi dengan ketinggian sekitar 400 mdpl, terdiri dari 11 dusun yang membentang dari utara ke selatan. Udara di sini sejuk dan tanahnya subur, menjadikan pertanian sebagai salah satu tulang punggung kehidupan warganya, dengan komoditas unggulan seperti alpukat, manggis, dan durian. Selain bertani, sebagian besar warga juga berprofesi sebagai buruh lepas, Pekerja Migran Indonesia (PMI), dan peternak.\r\n\r\nSebagai Desa Berdaya, Jenggik Utara juga punya potensi ekonomi kreatif yang menjanjikan, terutama dari kerajinan anyaman bambu hasil tangan terampil warga lokal. Melalui program digitalisasi UMKM, kami hadir untuk membantu mengangkat produk-produk unggulan desa mulai dari anyaman bambu, VCO, gula aren, jajanan tradisional hingga hasil olahan lokal lainnya agar lebih dikenal luas dan bernilai jual tinggi.\r\n\r\nDi ketinggian 400 mdpl, tempat udara sejuk berpadu dengan tanah yang subur, berdirilah Jenggik Utara desa dengan 11 dusun yang menyimpan kekayaan alam berupa alpukat, manggis, dan durian. Di balik kesahajaan warganya yang bertani, beternak, dan merantau sebagai Pekerja Migran Indonesia, tersimpan kearifan tangan-tangan terampil pengrajin anyaman bambu yang terus dijaga turun-temurun.\r\n\r\nKini, sebagai Desa Berdaya, Jenggik Utara melangkah maju merangkul dunia digital menghadirkan anyaman bambu, VCO, gula aren, dan jajanan tradisional beserta hasil bumi lokal lainnya ke hadapan dunia yang lebih luas.', 'assets/beranda/kantor desa.jpg', 'Profil Desa Jenggik Utara');

-- --------------------------------------------------------

--
-- Table structure for table `umkm`
--

CREATE TABLE `umkm` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `tagline` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `latar_belakang_usaha` text DEFAULT NULL,
  `informasi_tambahan` text NOT NULL,
  `gambar_umkm` varchar(255) NOT NULL,
  `logo_umkm` varchar(255) DEFAULT NULL,
  `proses_umkm` varchar(255) DEFAULT NULL,
  `alur_proses` text DEFAULT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `umkm`
--

INSERT INTO `umkm` (`id`, `nama_produk`, `tagline`, `deskripsi`, `informasi_tambahan`, `gambar_umkm`, `slug`, `latar_belakang_usaha`, `alur_proses`) VALUES
(1, 'Jajanan Tradisional Lombok', 'Apapun yang dipesan, kami usahakan selalu bisa menerima pesanan.', 'Jenis pesanan yang dibuat biasanya tidak jauh-jauh dari jenis jajanan tradisional yang ada di Lombok, seperti kue basah yang sering diperuntukkan untuk acara begawe (hajatan). Inak Khaeril Alpaeni menceritakan, \"Yang saya buat adalah jajanan tradisional atau apa yang orang-orang pesan, itu yang saya buat.\" Beliau selalu mengusahakan untuk bisa menerima semua pesanan demi memenuhi kebutuhan ekonomi keluarga.', '\"Saya kesusahan pada bagian keuangan, modal yang saya miliki sangat terbatas untuk membeli bahan-bahan. Jika tidak ada uang maka usaha ini tidak mungkin bisa berjalan,\" ungkapnya. Oleh karena itu, pembeli biasanya datang untuk membayar uang muka (DP). Jika pesanan sepi, Inak Khaeril Alpaeni juga mencoba membuat keranjang demi membantu perekonomian keluarga. Pemesanan dilakukan melalui ponsel, biasanya dari rekomendasi pelanggan sebelumnya.', 'assets/umkm/paket_jajanan.jpg', 'jajanan-tradisional', '\"Usaha ini dimulai dari diri saya sendiri karena saya tidak mendapatkan pendidikan khusus baik dari sekolah maupun orang tua,\" kata Inak Khaeril Alpaeni. Usaha ini sudah berjalan kurang lebih belasan tahun, sejak anaknya berumur 10 bulan hingga sekarang sudah SMA. \"Dulu pesanan dimulai dari harga 30rb, kemudian pembeli merasakan rasanya. Setelah itu datang lagi pembeli melakukan pre order skala lebih besar yaitu 100rb.\"', '[{\"judul\":\"Pemesanan & Uang Muka (DP)\",\"deskripsi\":\"Pemesan datang ke rumah untuk memesan dan menjelaskan apa yang mereka inginkan, serta membayar uang muka (DP). \\\"Karena saya benar-benar tidak memiliki modal sama sekali, saya meminta uang muka pada pembeli untuk memproduksi jajanan,\\\" jelas beliau.\"},{\"judul\":\"Proses Produksi\",\"deskripsi\":\"Setelah menerima DP, produksi sesuai pesanan dimulai. \\\"Terkadang saya mengerjakan pesanan ini hingga jam 11 malam bersama anak saya supaya ada uang untuk berbelanja kebutuhan sehari-hari,\\\" tambahnya.\"},{\"judul\":\"Pengambilan Pesanan\",\"deskripsi\":\"Setelah jajanan disiapkan dengan telaten, pembeli akan datang untuk mengambil jajan yang telah siap diambil.\"}]'),
(2, 'Gula Aren & Anyaman Bambu', 'Produk Tradisional dari Alam, 100% Asli Desa Jenggik Utara.', 'Usaha ini mencakup dua produk unggulan yaitu Gula Aren dan Anyaman Bambu. Produk gula aren dari desa ini menggunakan 100% air nira tanpa campuran apapun. Semua prosesnya masih sangat tradisional menggunakan tungku, kayu bakar, serta cetakan tempurung kelapa. Selain itu, juga diproduksi kerajinan anyaman bambu seperti keranjang dan keraro (randang).', 'Untuk gula aren, dijual dengan harga Rp 20.000 per cetakan (1 bilaq) atau sering dijual per 6 pcs seharga Rp 100.000 kepada para pengepul. Sedangkan harga jual keranjang biasanya Rp 25.000 untuk satu keranjang, dan randang dijual di kisaran harga Rp 120.000 hingga Rp 150.000 tergantung ukurannya.', 'assets/umkm/gula aren.jpg', 'gula-aren-anyaman', 'Usaha-usaha ini ditekuni oleh Bapak Satar. Latar belakang dari dimulainya usaha ini adalah karena kesulitan ekonomi keluarga Bapak Satar yang membuatnya terdorong untuk melakukan usaha produksi berskala rumahan. Beliau mempelajari cara membuat keranjang secara otodidak dengan melihat dan mengamati pola keranjang yang telah dibuat oleh tetangga sekitarnya dahulu. Sedangkan untuk produksi gula aren, beliau telah belajar secara turun temurun dari nenek moyang yang memanfaatkan potensi yang disediakan oleh alam.', '[{\"judul\":\"Pembuatan Gula Aren: Persiapan & Pemasakan\",\"deskripsi\":\"Pertama, air nira diambil dari pohon nira. Kedua, masak air nira menggunakan kuwali di atas tungku, kemudian tunggu hingga mendidih.\"},{\"judul\":\"Pembuatan Gula Aren: Pengentalan & Pencetakan\",\"deskripsi\":\"Setelah mendidih, air nira diaduk terus menerus hingga mengental. Untuk memastikan nira siap, sedikit gula dicelupkan ke air; jika mengeras maka siap dicetak ke dalam tempurung kelapa dan didiamkan hingga mengeras.\"},{\"judul\":\"Pembuatan Anyaman Bambu\",\"deskripsi\":\"Pembuatan dimulai dengan menyiapkan bambu. Kemudian bambu dipotong menjadi bilah-bilah sebelum dianyam secara teliti menjadi keranjang, keraro, maupun dandang.\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
--

CREATE TABLE `testimoni` (
  `id` int(11) NOT NULL,
  `nama_pengirim` varchar(255) NOT NULL,
  `isi_testimoni` text NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `jabatan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `testimoni`
--

INSERT INTO `testimoni` (`id`, `nama_pengirim`, `isi_testimoni`, `rating`, `jabatan`) VALUES
(1, 'Budi Santoso', 'Gula arennya sangat terasa alami, wanginya beda dengan yang ada di pasaran.', 5, 'Wisatawan'),
(2, 'Siti Aminah', 'Kue basahnya enak sekali, pesan untuk begawe kemarin semuanya puas.', 5, 'Pelanggan'),
(3, 'Andi Wijaya', 'Keranjang anyaman karya Bapak Satar rapi dan kuat. Sudah beli 3 untuk wadah hasil bumi.', 5, 'Pelanggan Setia');

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id` int(11) NOT NULL,
  `umkm_id` int(11) NOT NULL,
  `no_wa` varchar(20) NOT NULL,
  `pesan_wa_default` text NOT NULL,
  `link_facebook` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kontak`
--

INSERT INTO `kontak` (`id`, `umkm_id`, `no_wa`, `pesan_wa_default`, `link_facebook`) VALUES
(1, 1, '6281234567890', 'Halo Inak Khaeril Alpaeni, saya tertarik memesan Jajanan Tradisional Lombok untuk acara saya. Boleh diskusi pesanannya?', NULL),
(2, 2, '6281234567891', 'Halo Bapak Satar, saya tertarik memesan produk Gula Aren atau Anyaman Bambu. Boleh diskusi?', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `profile_desa`
--
ALTER TABLE `profile_desa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `umkm`
--
ALTER TABLE `umkm`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `umkm_id` (`umkm_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `profile_desa`
--
ALTER TABLE `profile_desa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `umkm`
--
ALTER TABLE `umkm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kontak`
--
ALTER TABLE `kontak`
  ADD CONSTRAINT `kontak_ibfk_1` FOREIGN KEY (`umkm_id`) REFERENCES `umkm` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
