<?php
require_once 'header.php';

$message = '';
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM umkm WHERE id = ?");
        $stmt->execute([$id]);
        $message = 'Data UMKM berhasil dihapus!';
    } catch (PDOException $e) {
        $message = 'Gagal menghapus: ' . $e->getMessage();
    }
}

$umkm_list = [];
$stmt = $pdo->query("SELECT * FROM umkm ORDER BY id DESC");
$umkm_list = $stmt->fetchAll();
?>

<h2>Manajemen Data UMKM</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="card">
    <a href="umkm_form.php" class="btn btn-success" style="margin-bottom: 15px; display: inline-block;">+ Tambah UMKM Baru</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Tagline</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($umkm_list) > 0): ?>
                <?php foreach ($umkm_list as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td>
                        <?php
                            $imgSrc = htmlspecialchars($row['gambar_umkm']);
                            if (strpos($imgSrc, 'http') !== 0 && strpos($imgSrc, '/') !== 0) {
                                $imgSrc = '../' . $imgSrc;
                            }
                        ?>
                        <img src="<?= $imgSrc ?>" alt="Gambar" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                    </td>
                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($row['tagline']) ?></td>
                    <td>
                        <a href="umkm_form.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                        <a href="umkm.php?delete=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data UMKM ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">Belum ada data UMKM.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>
