<?php
require_once 'header.php';

$message = '';

// Delete testimoni
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM testimoni WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Testimoni berhasil dihapus!';
    } catch (PDOException $e) {
        $message = 'Gagal menghapus: ' . $e->getMessage();
    }
}

// Toggle visibility
if (isset($_GET['toggle']) && isset($_GET['val'])) {
    $id = (int)$_GET['toggle'];
    $val = (int)$_GET['val'];
    try {
        $stmt = $pdo->prepare("UPDATE testimoni SET is_visible = ? WHERE id = ?");
        $stmt->execute([$val, $id]);
        $message = 'Status testimoni berhasil diubah!';
    } catch (PDOException $e) {
        // If column is_visible doesn't exist yet, we can try to create it here
        if (strpos($e->getMessage(), "Unknown column 'is_visible'") !== false) {
            try {
                $pdo->exec("ALTER TABLE testimoni ADD COLUMN is_visible TINYINT(1) DEFAULT 1 AFTER rating");
                $stmt = $pdo->prepare("UPDATE testimoni SET is_visible = ? WHERE id = ?");
                $stmt->execute([$val, $id]);
                $message = 'Kolom status ditambahkan dan status testimoni berhasil diubah!';
            } catch (PDOException $ex) {
                $message = 'Gagal mengubah status: ' . $ex->getMessage();
            }
        } else {
            $message = 'Gagal mengubah status: ' . $e->getMessage();
        }
    }
}

// Add testimoni (simple form)
if (isset($_POST['add_testimoni'])) {
    $nama = $_POST['nama_pengirim'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    $isi = $_POST['isi_testimoni'] ?? '';
    $rating = (int)($_POST['rating'] ?? 5);

    if (!empty($nama) && !empty($isi)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO testimoni (nama_pengirim, jabatan, isi_testimoni, rating, is_visible) VALUES (?, ?, ?, ?, 1)");
            $stmt->execute([$nama, $jabatan, $isi, $rating]);
            $message = 'Testimoni baru berhasil ditambahkan!';
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), "Unknown column 'is_visible'") !== false) {
                 try {
                     $pdo->exec("ALTER TABLE testimoni ADD COLUMN is_visible TINYINT(1) DEFAULT 1 AFTER rating");
                     $stmt = $pdo->prepare("INSERT INTO testimoni (nama_pengirim, jabatan, isi_testimoni, rating, is_visible) VALUES (?, ?, ?, ?, 1)");
                     $stmt->execute([$nama, $jabatan, $isi, $rating]);
                     $message = 'Testimoni baru berhasil ditambahkan!';
                 } catch(PDOException $ex) {
                     $message = 'Gagal menambah: ' . $ex->getMessage();
                 }
            } else {
                 $message = 'Gagal menambah: ' . $e->getMessage();
            }
        }
    }
}

$testimoni_list = [];
$stmt = $pdo->query("SELECT * FROM testimoni ORDER BY id DESC");
$testimoni_list = $stmt->fetchAll();
?>

<h2>Manajemen Testimoni</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card" style="margin-bottom: 30px;">
    <h3>Tambah Testimoni Baru</h3>
    <form method="POST">
        <div style="display:flex; gap:15px; margin-bottom:15px;">
            <div class="form-group" style="flex:1;">
                <label>Nama Pengirim</label>
                <input type="text" name="nama_pengirim" class="form-control" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Jabatan/Peran</label>
                <input type="text" name="jabatan" class="form-control" placeholder="Misal: Wisatawan">
            </div>
            <div class="form-group" style="width:100px;">
                <label>Rating</label>
                <input type="number" name="rating" class="form-control" min="1" max="5" value="5" required>
            </div>
        </div>
        <div class="form-group">
            <label>Isi Testimoni</label>
            <textarea name="isi_testimoni" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" name="add_testimoni" class="btn btn-success">+ Tambah</button>
    </form>
</div>

<div class="card">
    <h3>Daftar Testimoni</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Rating</th>
                <th>Isi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($testimoni_list) > 0): ?>
                <?php foreach ($testimoni_list as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nama_pengirim']) ?></td>
                    <td><?= htmlspecialchars($row['jabatan']) ?></td>
                    <td><?= $row['rating'] ?> ★</td>
                    <td><?= htmlspecialchars($row['isi_testimoni']) ?></td>
                    <td>
                        <?php 
                        // Fallback check if column is_visible exists on old rows before update
                        $is_visible = isset($row['is_visible']) ? $row['is_visible'] : 1; 
                        ?>
                        <?= $is_visible ? '<span style="color:green;font-weight:bold;">Tampil</span>' : '<span style="color:gray;">Disembunyikan</span>' ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <?php if($is_visible): ?>
                                <a href="testimoni.php?toggle=<?= $row['id'] ?>&val=0" class="btn btn-warning btn-sm" style="margin-right:5px; background-color:#ff9800; color:white;">Sembunyikan</a>
                            <?php else: ?>
                                <a href="testimoni.php?toggle=<?= $row['id'] ?>&val=1" class="btn btn-success btn-sm" style="margin-right:5px;">Tampilkan</a>
                            <?php endif; ?>
                            <a href="testimoni.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus testimoni ini?');">Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">Belum ada testimoni.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>
