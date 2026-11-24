<?php
require "../config/db.php";

if (!isset($_SESSION['admin'])) {
    header("Location: ../admin/login.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

$allowed = ['Menunggu','Diproses','Selesai'];

if ($id > 0 && in_array($status, $allowed, true)) {
    $stmt = mysqli_prepare($conn,
        "UPDATE pesanan SET status=? WHERE id_pesanan=?");
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    mysqli_stmt_execute($stmt);
}

header("Location: ../admin/pesanan.php");
exit;
?>