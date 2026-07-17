<?php
require_once 'koneksi.php';

if (!$pdo) {
    die("Koneksi gagal: " . $db_error);
}

try {
    // 1. Buat tabel admin
    $sql_admin = "
    CREATE TABLE IF NOT EXISTS `admin` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `username` varchar(50) NOT NULL,
        `password_hash` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql_admin);
    echo "Tabel admin berhasil dibuat atau sudah ada.\n";

    // 2. Insert admin default jika kosong (admin / admin123)
    $stmt = $pdo->query("SELECT COUNT(*) FROM `admin`");
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO `admin` (`username`, `password_hash`) VALUES (?, ?)")->execute(['admin', $hash]);
        echo "Admin default (admin / admin123) berhasil ditambahkan.\n";
    }

    // 3. Alter tabel umkm menambahkan logo_umkm dan proses_umkm
    // Cek dulu apakah kolom sudah ada
    $stmt = $pdo->query("SHOW COLUMNS FROM `umkm` LIKE 'logo_umkm'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `umkm` ADD COLUMN `logo_umkm` varchar(255) DEFAULT NULL AFTER `gambar_umkm`");
        echo "Kolom logo_umkm berhasil ditambahkan.\n";
    }

    $stmt = $pdo->query("SHOW COLUMNS FROM `umkm` LIKE 'proses_umkm'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE `umkm` ADD COLUMN `proses_umkm` varchar(255) DEFAULT NULL AFTER `logo_umkm`");
        echo "Kolom proses_umkm berhasil ditambahkan.\n";
    }

    echo "Migrasi database selesai dengan sukses!\n";
} catch (PDOException $e) {
    echo "Terjadi kesalahan migrasi: " . $e->getMessage() . "\n";
}
