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

$upload_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Helper function for upload
    function handleUpload($fileArray, $targetDir = '../assets/umkm/') {
        global $upload_error;
        if (isset($fileArray) && $fileArray['error'] === UPLOAD_ERR_OK) {
            // Check size (3MB = 3 * 1024 * 1024)
            if ($fileArray['size'] > 3145728) {
                $upload_error = "Ukuran file " . htmlspecialchars($fileArray['name']) . " melebihi 3MB.";
                return false;
            }

            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            
            // Base filename
            $originalName = basename($fileArray["name"]);
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $baseName = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', pathinfo($originalName, PATHINFO_FILENAME));
            $targetPathWebp = $targetDir . $baseName . '.webp';
            
            // Convert to webp
            $sourcePath = $fileArray["tmp_name"];
            $info = @getimagesize($sourcePath);
            
            if ($info !== false) {
                $mime = $info['mime'];
                $image = null;
                switch ($mime) {
                    case 'image/jpeg':
                        $image = @imagecreatefromjpeg($sourcePath);
                        break;
                    case 'image/png':
                        $image = @imagecreatefrompng($sourcePath);
                        if ($image) {
                            imagepalettetotruecolor($image);
                            imagealphablending($image, true);
                            imagesavealpha($image, true);
                        }
                        break;
                    case 'image/gif':
                        $image = @imagecreatefromgif($sourcePath);
                        break;
                    case 'image/webp':
                        $image = @imagecreatefromwebp($sourcePath);
                        break;
                }
                
                if ($image !== false && $image !== null) {
                    if (imagewebp($image, $targetPathWebp, 85)) {
                        imagedestroy($image);
                        return substr($targetPathWebp, 3); // Remove '../' for DB
                    }
                    imagedestroy($image);
                }
            }
            // Fallback move if cannot read by GD (or unsupported)
            $targetPathFallback = $targetDir . $baseName . '.' . $ext;
            if (move_uploaded_file($sourcePath, $targetPathFallback)) {
                return substr($targetPathFallback, 3);
            }
        }
        return false;
    }
    $nama = $_POST['nama_produk'] ?? '';
    $tagline = $_POST['tagline'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $latar = $_POST['latar_belakang_usaha'] ?? '';
    $info = $_POST['informasi_tambahan'] ?? '';
    $gambar = $_POST['old_gambar_umkm'] ?? '';
    $newGambar = handleUpload($_FILES['gambar_umkm'] ?? null);
    if ($newGambar) $gambar = $newGambar;

    $logo = $_POST['old_logo_umkm'] ?? '';
    $newLogo = handleUpload($_FILES['logo_umkm'] ?? null);
    if ($newLogo) $logo = $newLogo;

    $proses = $_POST['old_proses_umkm'] ?? '';
    $newProses = handleUpload($_FILES['proses_umkm'] ?? null);
    if ($newProses) $proses = $newProses;
    
    // Slug generation from name if empty
    $slug = $_POST['slug'] ?? '';
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama)));
    }

    $no_wa = $_POST['no_wa'] ?? '';
    $pesan_wa = $_POST['pesan_wa_default'] ?? '';
    $link_facebook = $_POST['link_facebook'] ?? '';

    // Handle Alur Proses
    $alur_judul = $_POST['alur_judul'] ?? [];
    $alur_deskripsi = $_POST['alur_deskripsi'] ?? [];
    $alur_proses_arr = [];
    for($i = 0; $i < count($alur_judul); $i++) {
        if(trim($alur_judul[$i]) !== '') {
            $alur_proses_arr[] = [
                'judul' => trim($alur_judul[$i]),
                'deskripsi' => trim($alur_deskripsi[$i])
            ];
        }
    }
    $alur_proses_json = empty($alur_proses_arr) ? null : json_encode($alur_proses_arr);

    if (!empty($upload_error)) {
        $error = $upload_error;
    } elseif (!empty($nama) && !empty($deskripsi)) {
        try {
            // Check and auto-add alur_proses column if it doesn't exist
            try {
                $pdo->query("SELECT alur_proses FROM umkm LIMIT 1");
            } catch (PDOException $e) {
                try {
                    $pdo->exec("ALTER TABLE umkm ADD COLUMN alur_proses TEXT NULL AFTER informasi_tambahan");
                } catch (PDOException $ex) {}
            }
            
            $pdo->beginTransaction();
            if ($is_edit) {
                // Update
                $stmt = $pdo->prepare("UPDATE umkm SET nama_produk=?, tagline=?, deskripsi=?, latar_belakang_usaha=?, informasi_tambahan=?, gambar_umkm=?, logo_umkm=?, proses_umkm=?, slug=?, alur_proses=? WHERE id=?");
                $stmt->execute([$nama, $tagline, $deskripsi, $latar, $info, $gambar, $logo, $proses, $slug, $alur_proses_json, $id]);

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
                $stmt = $pdo->prepare("INSERT INTO umkm (nama_produk, tagline, deskripsi, latar_belakang_usaha, informasi_tambahan, gambar_umkm, logo_umkm, proses_umkm, slug, alur_proses) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nama, $tagline, $deskripsi, $latar, $info, $gambar, $logo, $proses, $slug, $alur_proses_json]);
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
    <form method="POST" enctype="multipart/form-data">
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

        <h3>Alur Proses Kerja</h3>
        <div id="alur-proses-container">
            <?php 
            $alur_data = [];
            if (!empty($umkm['alur_proses'])) {
                $alur_data = json_decode($umkm['alur_proses'], true);
            }
            if (empty($alur_data)) {
                // Default 1 empty input
                $alur_data = [['judul' => '', 'deskripsi' => '']];
            }
            ?>
            <?php foreach($alur_data as $index => $alur): ?>
            <div class="alur-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 5px; position: relative;">
                <button type="button" class="btn btn-danger btn-sm" style="position: absolute; right: 10px; top: 10px;" onclick="this.parentElement.remove()">Hapus</button>
                <div class="form-group" style="margin-bottom: 10px;">
                    <label>Judul Proses</label>
                    <input type="text" name="alur_judul[]" class="form-control" value="<?= htmlspecialchars($alur['judul']) ?>" placeholder="Misal: Pemesanan & Uang Muka">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Deskripsi Proses</label>
                    <textarea name="alur_deskripsi[]" class="form-control" rows="2" placeholder="Jelaskan langkah ini..."><?= htmlspecialchars($alur['deskripsi']) ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-info" onclick="tambahAlur()" style="margin-bottom: 20px;">+ Tambah Tahap Proses</button>

        <h3>Galeri & Foto</h3>
        <div class="form-group">
            <label>Upload Foto Produk Utama (Biarkan kosong jika tidak ingin mengubah)</label>
            <input type="file" name="gambar_umkm" class="form-control" accept="image/*">
            <input type="hidden" name="old_gambar_umkm" value="<?= htmlspecialchars($umkm['gambar_umkm'] ?? '') ?>">
            <?php if (!empty($umkm['gambar_umkm'])): ?>
                <img src="../<?= htmlspecialchars($umkm['gambar_umkm']) ?>" style="width:100px; margin-top:10px; border-radius:5px;">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Upload Foto Logo UMKM (Opsional)</label>
            <input type="file" name="logo_umkm" class="form-control" accept="image/*">
            <input type="hidden" name="old_logo_umkm" value="<?= htmlspecialchars($umkm['logo_umkm'] ?? '') ?>">
            <?php if (!empty($umkm['logo_umkm'])): ?>
                <img src="../<?= htmlspecialchars($umkm['logo_umkm']) ?>" style="width:20px; margin-top:10px; border-radius:5px;">
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Upload Foto Proses Produksi (Opsional)</label>
            <input type="file" name="proses_umkm" class="form-control" accept="image/*">
            <input type="hidden" name="old_proses_umkm" value="<?= htmlspecialchars($umkm['proses_umkm'] ?? '') ?>">
            <?php if (!empty($umkm['proses_umkm'])): ?>
                <img src="../<?= htmlspecialchars($umkm['proses_umkm']) ?>" style="width:20px; margin-top:10px; border-radius:5px;">
            <?php endif; ?>
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

<script>
function tambahAlur() {
    const container = document.getElementById('alur-proses-container');
    const html = `
        <div class="alur-item" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 5px; position: relative;">
            <button type="button" class="btn btn-danger btn-sm" style="position: absolute; right: 10px; top: 10px;" onclick="this.parentElement.remove()">Hapus</button>
            <div class="form-group" style="margin-bottom: 10px;">
                <label>Judul Proses</label>
                <input type="text" name="alur_judul[]" class="form-control" placeholder="Misal: Pemesanan & Uang Muka">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Deskripsi Proses</label>
                <textarea name="alur_deskripsi[]" class="form-control" rows="2" placeholder="Jelaskan langkah ini..."></textarea>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}
</script>

<?php require_once 'footer.php'; ?>
