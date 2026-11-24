<?php
require "config/db.php";

$query = mysqli_query($conn,
    "SELECT * FROM pesanan ORDER BY id_pesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Pesanan</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header><h1>Riwayat Pesanan Kantin Bu Rina</h1></header>

<div class="container">
<div class="panel">

<table>
<tr>
<th>Antrean</th>
<th>Nama</th>
<th>Total</th>
<th>Pembayaran</th>
<th>Status</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($query)) { ?>
<tr>
<td><?= htmlspecialchars($row['nomor_antrean']) ?></td>
<td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
<td>Rp <?= number_format($row['total_harga'],0,',','.') ?></td>
<td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>