<?php
require_once 'header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul_profil'] ?? '';
    $potensi = $_POST['potensi_desa'] ?? '';
    $informasi = $_POST['informasi_desa'] ?? '';
    $gambar = $_POST['gambar_desa'] ?? '';

    if (!empty($judul) && !empty($potensi) && !empty($informasi)) {
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
    <form method="POST">
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
            <label>URL Gambar (Pemandangan/Profil)</label>
            <input type="text" name="gambar_desa" class="form-control" value="<?= htmlspecialchars($profil['gambar_desa'] ?? '') ?>">
            <small style="color:#777;">Masukkan link/URL gambar, contoh: https://example.com/gambar.jpg</small>
        </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
