<?php
require_once 'header.php';

$message = '';
$error = '';

$upload_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul_profil'] ?? '';
    $potensi = $_POST['potensi_desa'] ?? '';
    $informasi = $_POST['informasi_desa'] ?? '';
    
    // Handle image upload
    $gambar = $_POST['old_gambar_desa'] ?? '';
    if (isset($_FILES['gambar_desa']) && $_FILES['gambar_desa']['error'] === UPLOAD_ERR_OK) {
        if ($_FILES['gambar_desa']['size'] > 3145728) {
            $upload_error = "Ukuran file melebihi batas maksimal 3 MB.";
        } else {
            $targetDir = '../assets/beranda/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            
            $originalName = basename($_FILES['gambar_desa']["name"]);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $baseName = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', pathinfo($originalName, PATHINFO_FILENAME));
            $targetPathWebp = $targetDir . $baseName . '.webp';
            
            $sourcePath = $_FILES['gambar_desa']["tmp_name"];
            $info = @getimagesize($sourcePath);
            $converted = false;
            
            if ($info !== false) {
                $mime = $info['mime'];
                $image = null;
                switch ($mime) {
                    case 'image/jpeg': $image = @imagecreatefromjpeg($sourcePath); break;
                    case 'image/png': 
                        $image = @imagecreatefrompng($sourcePath);
                        if ($image) { imagepalettetotruecolor($image); imagealphablending($image, true); imagesavealpha($image, true); }
                        break;
                    case 'image/gif': $image = @imagecreatefromgif($sourcePath); break;
                    case 'image/webp': $image = @imagecreatefromwebp($sourcePath); break;
                }
                
                if ($image !== false && $image !== null) {
                    if (imagewebp($image, $targetPathWebp, 85)) {
                        imagedestroy($image);
                        $gambar = substr($targetPathWebp, 3);
                        $converted = true;
                    } else {
                        imagedestroy($image);
                    }
                }
            }
            
            if (!$converted) {
                $targetPathFallback = $targetDir . $baseName . '.' . $ext;
                if (move_uploaded_file($sourcePath, $targetPathFallback)) {
                    $gambar = substr($targetPathFallback, 3);
                }
            }
        }
    }

    if (!empty($upload_error)) {
        $error = $upload_error;
    } elseif (!empty($judul) && !empty($potensi) && !empty($informasi)) {
        try {
            $stmt = $pdo->prepare("UPDATE profile_desa SET judul_profil = ?, potensi_desa = ?, informasi_desa = ?, gambar_desa = ? WHERE id = 1");
            $stmt->execute([$judul, $potensi, $informasi, $gambar]);
            $message = 'Profil desa berhasil diperbarui!';
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan: ' . $e->getMessage();
        }
    } else {
        $error = 'Judul, potensi, dan informasi wajib diisi.';
    }
}

// Fetch current
$profil = [];
$stmt = $pdo->query("SELECT * FROM profile_desa WHERE id = 1");
if ($row = $stmt->fetch()) {
    $profil = $row;
}
?>

<h2>Edit Profil Desa</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Judul Profil</label>
            <input type="text" name="judul_profil" class="form-control" value="<?= htmlspecialchars($profil['judul_profil'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Potensi Desa</label>
            <input type="text" name="potensi_desa" class="form-control" value="<?= htmlspecialchars($profil['potensi_desa'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Deskripsi / Informasi Desa</label>
            <textarea name="informasi_desa" class="form-control" rows="5" required><?= htmlspecialchars($profil['informasi_desa'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Upload Gambar (Pemandangan/Profil) - Biarkan kosong jika tidak ingin mengubah</label>
            <input type="file" name="gambar_desa" class="form-control" accept="image/*">
            <input type="hidden" name="old_gambar_desa" value="<?= htmlspecialchars($profil['gambar_desa'] ?? '') ?>">
            <?php if (!empty($profil['gambar_desa'])): ?>
                <img src="../<?= htmlspecialchars($profil['gambar_desa']) ?>" style="width:150px; margin-top:10px; border-radius:5px;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
