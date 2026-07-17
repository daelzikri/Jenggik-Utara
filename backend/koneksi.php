<?php
// koneksi.php
$host = 'localhost';
$user = 'u602243872_jenggikutara';
$pass = 'JenggikUtara123'; // Sesuaikan dengan password database Anda
$db   = 'u602243872_jenggikutara'; // Sesuaikan dengan nama database Anda

$pdo = null;
$db_error = null;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $db_error = $e->getMessage();
}
?>
