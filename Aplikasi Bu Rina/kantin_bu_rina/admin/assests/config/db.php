<?php
$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "kantin_bu_rina"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>