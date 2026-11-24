<?php
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../checkout.php");
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$metode = $_POST['metode'] ?? '';

if ($nama === '' ||
    !in_array($metode, ['Cash','QRIS'], true) ||
    empty($_SESSION['keranjang'])) {
    die("Data pesanan tidak valid.");
}

mysqli_begin_transaction($conn);

try {
    $total = 0;
    $items = [];

    foreach ($_SESSION['keranjang'] as $id => $jumlah) {
        $id = (int)$id;
        $jumlah = (int)$jumlah;

        if ($jumlah < 1) throw new Exception("Jumlah tidak valid.");

        $stmt = mysqli_prepare($conn,
            "SELECT * FROM menu WHERE id_menu=? FOR UPDATE");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $menu = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$menu || $menu['stok'] < $jumlah) {
            throw new Exception("Stok tidak mencukupi.");
        }

        $total += $menu['harga'] * $jumlah;

        $items[] = [
            'id' => $id,
            'jumlah' => $jumlah,
            'harga' => $menu['harga']
        ];
    }

    $stmt = mysqli_prepare($conn,
        "INSERT INTO pesanan
        (nama_pelanggan,total_harga,metode_pembayaran)
        VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, "sis", $nama, $total, $metode);
    mysqli_stmt_execute($stmt);

    $id_pesanan = mysqli_insert_id($conn);

    $nomor = "BR" . date("ymd") . "-" .
        str_pad($id_pesanan, 3, "0", STR_PAD_LEFT);

    $stmt = mysqli_prepare($conn,
        "UPDATE pesanan SET nomor_antrean=? WHERE id_pesanan=?");
    mysqli_stmt_bind_param($stmt, "si", $nomor, $id_pesanan);
    mysqli_stmt_execute($stmt);

    foreach ($items as $item) {
        $stmt = mysqli_prepare($conn,
            "INSERT INTO detail_pesanan
            (id_pesanan,id_menu,jumlah,harga)
            VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($stmt, "iiii",
            $id_pesanan, $item['id'],
            $item['jumlah'], $item['harga']);
        mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare($conn,
            "UPDATE menu SET stok=stok-? WHERE id_menu=?");
        mysqli_stmt_bind_param($stmt, "ii",
            $item['jumlah'], $item['id']);
        mysqli_stmt_execute($stmt);
    }

    mysqli_commit($conn);
    unset($_SESSION['keranjang']);

    header("Location: ../antrean.php?id=" . $id_pesanan);
    exit;

} catch (Throwable $e) {
    mysqli_rollback($conn);
    die("Pesanan gagal: " . htmlspecialchars($e->getMessage()));
}
?>