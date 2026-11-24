<?php
require "config/db.php";

if (isset($_POST['tambah'])) {
    $id = (int)$_POST['id_menu'];

    $stmt = mysqli_prepare($conn,
        "SELECT stok FROM menu WHERE id_menu=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $item = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($item && $item['stok'] > 0) {
        $_SESSION['keranjang'][$id] =
            ($_SESSION['keranjang'][$id] ?? 0) + 1;
    }

    header("Location: keranjang.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    unset($_SESSION['keranjang'][$id]);
    header("Location: keranjang.php");
    exit;
}

if (isset($_POST['update'])) {
    foreach ($_POST['jumlah'] as $id => $jumlah) {
        $id = (int)$id;
        $jumlah = max(1, (int)$jumlah);

        if (isset($_SESSION['keranjang'][$id])) {
            $_SESSION['keranjang'][$id] = $jumlah;
        }
    }

    header("Location: keranjang.php");
    exit;
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang Kantin Bu Rina</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header><h1>Keranjang Belanja</h1></header>

<div class="container">
<div class="panel">

<?php if (!empty($_SESSION['keranjang'])) { ?>

<form method="POST">
<table>
<tr>
<th>Menu</th>
<th>Harga</th>
<th>Jumlah</th>
<th>Subtotal</th>
<th>Aksi</th>
</tr>

<?php foreach ($_SESSION['keranjang'] as $id => $jumlah) {
    $id = (int)$id;

    $stmt = mysqli_prepare($conn,
        "SELECT * FROM menu WHERE id_menu=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$row) continue;

    $subtotal = $row['harga'] * $jumlah;
    $total += $subtotal;
?>

<tr>
<td><?= htmlspecialchars($row['nama_menu']) ?></td>
<td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
<td>
<input type="number" name="jumlah[<?= $id ?>]"
value="<?= $jumlah ?>" min="1">
</td>
<td>Rp <?= number_format($subtotal,0,',','.') ?></td>
<td><a href="keranjang.php?hapus=<?= $id ?>">Hapus</a></td>
</tr>

<?php } ?>
</table>

<h2>Total Rp <?= number_format($total,0,',','.') ?></h2>

<button name="update">Update Keranjang</button>
<a class="button-link" href="checkout.php">Checkout</a>
</form>

<?php } else { ?>
<p>Keranjang masih kosong.</p>
<a href="index.php">Kembali Belanja</a>
<?php } ?>

</div>
</div>

</body>
</html>