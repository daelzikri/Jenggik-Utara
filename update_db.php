<?php
require_once 'backend/koneksi.php';

if ($pdo) {
    try {
        // Check if column exists
        $stmt = $pdo->query("SHOW COLUMNS FROM testimoni LIKE 'is_visible'");
        $exists = $stmt->fetch();
        if (!$exists) {
            $pdo->exec("ALTER TABLE testimoni ADD COLUMN is_visible TINYINT(1) DEFAULT 1 AFTER rating");
            echo "Column 'is_visible' added successfully.\n";
        } else {
            echo "Column 'is_visible' already exists.\n";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No DB connection.\n";
}
?>
