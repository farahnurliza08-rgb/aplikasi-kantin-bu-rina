<?php
require "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn,
    "SELECT * FROM pesanan ORDER BY id_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Pesanan</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header><h1>Data Pesanan Kantin Bu Rina</h1></header>

<nav>
<a href="dashboard.php">Dashboard</a>
<a href="pesanan.php">Pesanan</a>
<a href="menu.php">Menu</a>
<a href="logout.php">Logout</a>
</nav>

<div class="container">
<div class="panel">

<table>
<tr>
<th>Antrean</th>
<th>Nama</th>
<th>Total</th>
<th>Pembayaran</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($query)) { ?>
<tr>
<td><?= htmlspecialchars($row['nomor_antrean']) ?></td>
<td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
<td>Rp <?= number_format($row['total_harga'],0,',','.') ?></td>
<td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
<td>
<form action="../api/update_status.php" method="POST">
<input type="hidden" name="id"
value="<?= $row['id_pesanan'] ?>">

<select name="status">
<option value="Menunggu" <?= $row['status']=='Menunggu'?'selected':'' ?>>Menunggu</option>
<option value="Diproses" <?= $row['status']=='Diproses'?'selected':'' ?>>Diproses</option>
<option value="Selesai" <?= $row['status']=='Selesai'?'selected':'' ?>>Selesai</option>
</select>

<button type="submit">Update</button>
</form>
</td>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>