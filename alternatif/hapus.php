<?php

require_once '../config/koneksi.php';
include '../template/notifikasi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$id = $_GET['id'];
/** @var mysqli $conn */
$query = mysqli_query(
$conn,
"DELETE FROM alternatif
WHERE id_alternatif='$id'"
);
 if ($query) {
        $_SESSION['sukses'] = "Data lokasi Berhasil di hapus!";
    } else {
        $_SESSION['gagal'] = "Gagal : " . mysqli_error($conn);
    }
header("Location:index.php");