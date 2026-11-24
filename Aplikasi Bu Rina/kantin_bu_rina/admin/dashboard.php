<?php
require "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$total_pesanan = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan")
)['total'];

$total_menu = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM menu")
)['total'];

$total_penjualan = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COALESCE(SUM(total_harga),0) AS total
         FROM pesanan WHERE status='Selesai'")
)['total'];

$antrean = mysqli_fetch_assoc(
    mysqli_query($conn,
        "SELECT COUNT(*) AS total FROM pesanan
         WHERE status!='Selesai'")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header><h1>Dashboard Kantin Bu Rina</h1></header>

<nav>
<a href="dashboard.php">Dashboard</a>
<a href="pesanan.php">Pesanan</a>
<a href="menu.php">Menu</a>
<a href="logout.php">Logout</a>
</nav>

<div class="container">
<div class="menu-grid">

<div class="menu-card">
<h3>Total Pesanan</h3>
<h2><?= $total_pesanan ?></h2>
</div>

<div class="menu-card">
<h3>Total Menu</h3>
<h2><?= $total_menu ?></h2>
</div>

<div class="menu-card">
<h3>Antrean Aktif</h3>
<h2><?= $antrean ?></h2>
</div>

<div class="menu-card">
<h3>Penjualan Selesai</h3>
<h2>Rp <?= number_format($total_penjualan,0,',','.') ?></h2>
</div>

</div>
</div>

</body>
</html>