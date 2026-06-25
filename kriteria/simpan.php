<?php
require_once '../config/koneksi.php';
include '../template/notifikasi.php';

/** @var mysqli $conn */
$total = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT SUM(bobot) total FROM kriteria")
);

if(($total['total'] + $_POST['bobot']) > 100){
    echo "Total bobot tidak boleh lebih dari 100";
    exit;
}

$query = mysqli_query($conn,

"INSERT INTO kriteria
(kode_kriteria,nama_kriteria,bobot,tipe)

VALUES

(
'$_POST[kode_kriteria]',
'$_POST[nama_kriteria]',
'$_POST[bobot]',
'$_POST[tipe]'
)"

);
if ($query) {
        $_SESSION['sukses'] = "Data lokasi Berhasil di hapus!";
    } else {
        $_SESSION['gagal'] = "Gagal : " . mysqli_error($conn);
    }
header("Location:index.php");