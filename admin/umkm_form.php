<?php
require_once 'header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_edit = $id > 0;
$message = '';
$error = '';

$umkm = [
    'nama_produk' => '', 'tagline' => '', 'deskripsi' => '', 'latar_belakang_usaha' => '', 'informasi_tambahan' => '', 
    'gambar_umkm' => '', 'logo_umkm' => '', 'proses_umkm' => '', 'slug' => '',
    'no_wa' => '', 'pesan_wa_default' => '', 'link_facebook' => ''
];

if ($is_edit) {
    // Fetch UMKM + Kontak
    $stmt = $pdo->prepare("SELECT u.*, k.no_wa, k.pesan_wa_default, k.link_facebook FROM umkm u LEFT JOIN kontak k ON u.id = k.umkm_id WHERE u.id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if ($row) {
        $umkm = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_produk'] ?? '';
    $tagline = $_POST['tagline'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $latar = $_POST['latar_belakang_usaha'] ?? '';
    $info = $_POST['informasi_tambahan'] ?? '';
    $gambar = $_POST['gambar_umkm'] ?? '';
    $logo = $_POST['logo_umkm'] ?? '';
    $proses = $_POST['proses_umkm'] ?? '';
    
    // Slug generation from name if empty
    $slug = $_POST['slug'] ?? '';
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama)));
    }

    $no_wa = $_POST['no_wa'] ?? '';
    $pesan_wa = $_POST['pesan_wa_default'] ?? '';
    $link_facebook = $_POST['link_facebook'] ?? '';

    if (!empty($nama) && !empty($deskripsi)) {
        try {
            $pdo->beginTransaction();
            if ($is_edit) {
                // Update
                $stmt = $pdo->prepare("UPDATE umkm SET nama_produk=?, tagline=?, deskripsi=?, latar_belakang_usaha=?, informasi_tambahan=?, gambar_umkm=?, logo_umkm=?, proses_umkm=?, slug=? WHERE id=?");
                $stmt->execute([$nama, $tagline, $deskripsi, $latar, $info, $gambar, $logo, $proses, $slug, $id]);

                // Update / Insert Kontak
                $stmt_check = $pdo->prepare("SELECT id FROM kontak WHERE umkm_id=?");
                $stmt_check->execute([$id]);
                if ($stmt_check->rowCount() > 0) {
                    $pdo->prepare("UPDATE kontak SET no_wa=?, pesan_wa_default=?, link_facebook=? WHERE umkm_id=?")->execute([$no_wa, $pesan_wa, $link_facebook, $id]);
                } else {
                    $pdo->prepare("INSERT INTO kontak (umkm_id, no_wa, pesan_wa_default, link_facebook) VALUES (?, ?, ?, ?)")->execute([$id, $no_wa, $pesan_wa, $link_facebook]);
                }
                $message = "Data UMKM berhasil diupdate!";
            } else {
                // Insert
                $stmt = $pdo->prepare("INSERT INTO umkm (nama_produk, tagline, deskripsi, latar_belakang_usaha, informasi_tambahan, gambar_umkm, logo_umkm, proses_umkm, slug) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nama, $tagline, $deskripsi, $latar, $info, $gambar, $logo, $proses, $slug]);
                $new_id = $pdo->lastInsertId();

                $pdo->prepare("INSERT INTO kontak (umkm_id, no_wa, pesan_wa_default, link_facebook) VALUES (?, ?, ?, ?)")->execute([$new_id, $no_wa, $pesan_wa, $link_facebook]);
                $message = "Data UMKM baru berhasil ditambahkan!";
                $is_edit = true;
                $id = $new_id;
            }
            $pdo->commit();
            
            // Refresh data
            $stmt = $pdo->prepare("SELECT u.*, k.no_wa, k.pesan_wa_default, k.link_facebook FROM umkm u LEFT JOIN kontak k ON u.id = k.umkm_id WHERE u.id = ?");
            $stmt->execute([$id]);
            $umkm = $stmt->fetch();
            
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Gagal menyimpan data: ' . $e->getMessage();
        }
    } else {
        $error = 'Nama produk dan deskripsi wajib diisi.';
    }
}
?>

<h2><?= $is_edit ? 'Edit UMKM' : 'Tambah UMKM Baru' ?></h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST">
        <h3 style="margin-top:0;">Informasi Produk</h3>
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($umkm['nama_produk'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Tagline (Opsional)</label>
            <input type="text" name="tagline" class="form-control" value="<?= htmlspecialchars($umkm['tagline'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Slug (URL Ramah)</label>
            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($umkm['slug'] ?? '') ?>" placeholder="Kosongkan agar dibuat otomatis dari nama">
        </div>
        <div class="form-group">
            <label>Deskripsi Produk</label>
            <textarea name="deskripsi" class="form-control" rows="5" required><?= htmlspecialchars($umkm['deskripsi'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Latar Belakang Usaha (Opsional)</label>
            <textarea name="latar_belakang_usaha" class="form-control" rows="3"><?= htmlspecialchars($umkm['latar_belakang_usaha'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Informasi Tambahan (Opsional)</label>
            <textarea name="informasi_tambahan" class="form-control" rows="3"><?= htmlspecialchars($umkm['informasi_tambahan'] ?? '') ?></textarea>
        </div>

        <h3>Galeri & Foto</h3>
        <div class="form-group">
            <label>URL Foto Produk Utama</label>
            <input type="text" name="gambar_umkm" class="form-control" value="<?= htmlspecialchars($umkm['gambar_umkm'] ?? '') ?>" placeholder="https://example.com/foto_produk.jpg">
        </div>
        <div class="form-group">
            <label>URL Foto Logo UMKM (Opsional)</label>
            <input type="text" name="logo_umkm" class="form-control" value="<?= htmlspecialchars($umkm['logo_umkm'] ?? '') ?>" placeholder="https://example.com/logo.jpg">
        </div>
        <div class="form-group">
            <label>URL Foto Proses Produksi (Opsional)</label>
            <input type="text" name="proses_umkm" class="form-control" value="<?= htmlspecialchars($umkm['proses_umkm'] ?? '') ?>" placeholder="https://example.com/proses.jpg">
        </div>

        <h3>Kontak WhatsApp</h3>
        <div class="form-group">
            <label>Nomor WhatsApp (gunakan awalan 62, misal: 628123456789)</label>
            <input type="text" name="no_wa" class="form-control" value="<?= htmlspecialchars($umkm['no_wa'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Pesan Otomatis (Default WA)</label>
            <textarea name="pesan_wa_default" class="form-control" rows="2"><?= htmlspecialchars($umkm['pesan_wa_default'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Link Facebook (Opsional)</label>
            <input type="text" name="link_facebook" class="form-control" value="<?= htmlspecialchars($umkm['link_facebook'] ?? '') ?>" placeholder="https://facebook.com/username">
        </div>

        <button type="submit" class="btn btn-success"><?= $is_edit ? 'Simpan Perubahan' : 'Tambah UMKM' ?></button>
        <a href="umkm.php" class="btn" style="background:#7f8c8d;">Batal</a>
    </form>
</div>

<?php require_once 'footer.php'; ?>
