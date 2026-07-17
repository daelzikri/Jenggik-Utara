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

// Add testimoni (simple form)
if (isset($_POST['add_testimoni'])) {
    $nama = $_POST['nama_pengirim'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    $isi = $_POST['isi_testimoni'] ?? '';
    $rating = (int)($_POST['rating'] ?? 5);

    if (!empty($nama) && !empty($isi)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO testimoni (nama_pengirim, jabatan, isi_testimoni, rating) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama, $jabatan, $isi, $rating]);
            $message = 'Testimoni baru berhasil ditambahkan!';
        } catch (PDOException $e) {
            $message = 'Gagal menambah: ' . $e->getMessage();
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
                        <a href="testimoni.php?delete=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus testimoni ini?');">Hapus</a>
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
