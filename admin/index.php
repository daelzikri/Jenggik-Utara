<?php
require_once 'header.php';

// Get counts
$count_umkm = 0;
$count_testimoni = 0;

if ($pdo) {
    $stmt1 = $pdo->query("SELECT COUNT(*) FROM umkm");
    $count_umkm = $stmt1->fetchColumn();

    $stmt2 = $pdo->query("SELECT COUNT(*) FROM testimoni");
    $count_testimoni = $stmt2->fetchColumn();
}
?>

<h2>Ringkasan Statistik</h2>
<div style="display: flex; gap: 20px;">
    <div class="card" style="flex: 1; text-align: center;">
        <h1 style="font-size: 3rem; margin: 0; color: #2980b9;"><?= $count_umkm ?></h1>
        <p style="color: #7f8c8d; font-weight: 600;">Total UMKM</p>
    </div>
    <div class="card" style="flex: 1; text-align: center;">
        <h1 style="font-size: 3rem; margin: 0; color: #27ae60;"><?= $count_testimoni ?></h1>
        <p style="color: #7f8c8d; font-weight: 600;">Total Testimoni</p>
    </div>
</div>

<?php require_once 'footer.php'; ?>
