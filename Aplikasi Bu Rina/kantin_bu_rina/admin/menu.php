<?php
require "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama']);
    $kategori = $_POST['kategori'];
    $harga = (int)$_POST['harga'];
    $stok = (int)$_POST['stok'];

    if ($nama !== '' &&
        in_array($kategori,['Makanan','Minuman'],true) &&
        $harga > 0 && $stok >= 0) {

        $stmt = mysqli_prepare($conn,
            "INSERT INTO menu (nama_menu,kategori,harga,stok)
             VALUES (?,?,?,?)");

        mysqli_stmt_bind_param($stmt, "ssii",
            $nama,$kategori,$harga,$stok);
        mysqli_stmt_execute($stmt);
    }

    header("Location: menu.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];

    $stmt = mysqli_prepare($conn,
        "DELETE FROM menu WHERE id_menu=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: menu.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM menu ORDER BY id_menu DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola Menu</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header><h1>Kelola Menu Kantin Bu Rina</h1></header>

<nav>
<a href="dashboard.php">Dashboard</a>
<a href="pesanan.php">Pesanan</a>
<a href="menu.php">Menu</a>
<a href="logout.php">Logout</a>
</nav>

<div class="container">
<div class="panel">

<h2>Tambah Menu</h2>

<form method="POST">
<label>Nama Menu</label>
<input type="text" name="nama" required>

<label>Kategori</label>
<select name="kategori">
<option value="Makanan">Makanan</option>
<option value="Minuman">Minuman</option>
</select>

<label>Harga</label>
<input type="number" name="harga" min="1" required>

<label>Stok</label>
<input type="number" name="stok" min="0" required>

<button name="tambah">Tambah Menu</button>
</form>

<h2>Daftar Menu</h2>

<table>
<tr>
<th>Nama</th>
<th>Kategori</th>
<th>Harga</th>
<th>Stok</th>
<th>Aksi</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($query)) { ?>
<tr>
<td><?= htmlspecialchars($row['nama_menu']) ?></td>
<td><?= htmlspecialchars($row['kategori']) ?></td>
<td>Rp <?= number_format($row['harga'],0,',','.') ?></td>
<td><?= $row['stok'] ?></td>
<td><a href="menu.php?hapus=<?= $row['id_menu'] ?>"
onclick="return confirm('Hapus menu ini?')">Hapus</a></td>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>