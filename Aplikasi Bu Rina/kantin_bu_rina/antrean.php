<?php
require "config/db.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = mysqli_prepare($conn,
    "SELECT * FROM pesanan WHERE id_pesanan=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Antrean Kantin Bu Rina</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header><h1>Nomor Antrean</h1></header>

<div class="container">
<div class="panel">

<?php if ($data) { ?>

<h2><?= htmlspecialchars($data['nomor_antrean']) ?></h2>

<p>Nama: <?= htmlspecialchars($data['nama_pelanggan']) ?></p>

<p>Total: Rp <?= number_format($data['total_harga'],0,',','.') ?></p>

<p>Metode: <?= htmlspecialchars($data['metode_pembayaran']) ?></p>

<p>Status: <?= htmlspecialchars($data['status']) ?></p>

<?php } else { ?>

<p>Pesanan tidak ditemukan.</p>

<?php } ?>

<a href="index.php">Kembali ke Beranda</a>

</div>
</div>

</body>
</html>