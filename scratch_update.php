<?php
require_once 'd:/xampp/htdocs/Website KKN/backend/koneksi.php';

if ($pdo) {
    $info_desa = "Jenggik Utara adalah desa yang terletak di dataran tinggi dengan ketinggian sekitar 400 mdpl, terdiri dari 11 dusun yang membentang dari utara ke selatan. Udara di sini sejuk dan tanahnya subur, menjadikan pertanian sebagai salah satu tulang punggung kehidupan warganya, dengan komoditas unggulan seperti alpukat, manggis, dan durian. Selain bertani, sebagian besar warga juga berprofesi sebagai buruh lepas, Pekerja Migran Indonesia (PMI), dan peternak.\n\nSebagai Desa Berdaya, Jenggik Utara juga punya potensi ekonomi kreatif yang menjanjikan, terutama dari kerajinan anyaman bambu hasil tangan terampil warga lokal. Melalui program digitalisasi UMKM, kami hadir untuk membantu mengangkat produk-produk unggulan desa mulai dari anyaman bambu, VCO, gula aren, jajanan tradisional hingga hasil olahan lokal lainnya agar lebih dikenal luas dan bernilai jual tinggi.";

    $stmt = $pdo->prepare("UPDATE profile_desa SET informasi_desa = ? WHERE id = 1");
    if ($stmt->execute([$info_desa])) {
        echo "Database updated successfully.";
    } else {
        echo "Failed to update database.";
    }
} else {
    echo "Database connection failed: " . $db_error;
}
?>
