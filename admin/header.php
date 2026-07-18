<?php
require_once 'auth.php';
require_once '../backend/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Desa Jenggik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; margin: 0; display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #1e293b; color: white; display: flex; flex-direction: column; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { text-align: center; padding: 25px 0; margin: 0; background: #0f172a; font-size: 1.5rem; letter-spacing: 1px; }
        .sidebar a { color: #cbd5e1; text-decoration: none; padding: 16px 25px; display: block; transition: all 0.3s ease; border-left: 4px solid transparent; border-bottom: 1px solid #334155; }
        .sidebar a:hover { background: #334155; color: #ffffff; border-left-color: #3b82f6; padding-left: 30px; }
        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px 30px; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); margin-bottom: 25px; }
        .btn { padding: 10px 18px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; transition: 0.3s; font-weight: 500; display: inline-block; }
        .btn:hover { background: #2563eb; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4); }
        .btn-danger { background: #ef4444; }
        .btn-danger:hover { background: #dc2626; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.4); }
        .btn-success { background: #22c55e; }
        .btn-success:hover { background: #16a34a; box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.4); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 15px; }
        table th, table td { padding: 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        table th { background-color: #f8fafc; font-weight: 600; color: #475569; }
        table tr:hover td { background-color: #f1f5f9; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-family: inherit; transition: border-color 0.3s; }
        .form-control:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .alert { padding: 15px 20px; border-radius: 6px; margin-bottom: 25px; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .action-buttons { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
        .btn-sm { padding: 6px 12px; font-size: 0.85rem; border-radius: 4px; }
        /* Mobile Responsiveness */
        .menu-toggle { display: none; background: #1e293b; color: white; padding: 15px; text-align: center; font-size: 1.2rem; cursor: pointer; border: none; width: 100%; font-weight: bold; }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .menu-toggle { display: block; }
            .sidebar { width: 100%; display: none; }
            .sidebar.active { display: flex; }
            .main-content { padding: 15px; width: 100%; box-sizing: border-box; }
            .header { flex-direction: column; gap: 15px; text-align: center; }
            table { display: block; overflow-x: auto; white-space: nowrap; }
            .card { padding: 15px; }
            .form-control { width: 100%; }
        }
    </style>
</head>
<body>

    <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
        ☰ Menu Admin
    </button>

    <div class="sidebar" id="sidebar">
        <h2>Admin Panel</h2>
        <a href="index.php">Dashboard</a>
        <a href="profil.php">Profil Desa</a>
        <a href="umkm.php">Data UMKM</a>
        <a href="testimoni.php">Testimoni</a>
        <a href="logout.php" style="margin-top: auto; background: #c0392b; border: none;">Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h3>Selamat Datang, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h3>
            <a href="../index.php" target="_blank" class="btn">Lihat Website</a>
        </div>
