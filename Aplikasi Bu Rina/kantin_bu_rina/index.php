<?php
require "config/db.php";

$query = mysqli_query($conn, "SELECT * FROM menu ORDER BY kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kantin Bu Rina</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header>
    <h1>KANTIN BU RINA</h1>
    <p>Enak, Murah, dan Mengenyangkan</p>
</header>

<nav>
    <a href="index.php">Beranda</a>
    <a href="keranjang.php">Keranjang</a>
    <a href="riwayat.php">Riwayat</a>
    <a href="admin/login.php">Admin</a>
</nav>

<div class="container">
    <h2>Daftar Menu</h2>

    <div class="menu-grid">
    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
        <div class="menu-card">
            <span><?= htmlspecialchars($row['kategori']) ?></span>
            <h3><?= htmlspecialchars($row['nama_menu']) ?></h3>
            <p>Rp <?= number_format($row['harga'],0,',','.') ?></p>
            <p>Stok: <?= (int)$row['stok'] ?></p>

            <?php if ($row['stok'] > 0) { ?>
            <form action="keranjang.php" method="POST">
                <input type="hidden" name="id_menu"
                    value="<?= $row['id_menu'] ?>">
                <button name="tambah">Tambah ke Keranjang</button>
            </form>
            <?php } else { ?>
                <p>Stok Habis</p>
            <?php } ?>
        </div>
    <?php } ?>
    </div>
</div>

<footer>
    <p>2026 Kantin Bu Rina</p>
</footer>

</body>
</html>