<?php

require_once '../config/koneksi.php';
/** @var mysqli $conn */

$id = $_POST['id_kriteria'];
$nama = $_POST['nama_kriteria'];
$bobot = $_POST['bobot'];
$tipe = $_POST['tipe'];

$id = $_POST['id_kriteria'];
$bobotBaru = $_POST['bobot'];

$dataLama = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT bobot FROM kriteria WHERE id_kriteria='$id'")
);

$total = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT SUM(bobot) total FROM kriteria")
);

$totalBaru =
$total['total']
- $dataLama['bobot']
+ $bobotBaru;

if($totalBaru > 100){

    echo "
    <script>
    alert('Total bobot melebihi 100');
    window.history.back();
    </script>
    ";

    exit;
}

mysqli_query($conn,

"UPDATE kriteria SET

nama_kriteria='$nama',
bobot='$bobot',
tipe='$tipe'

WHERE id_kriteria='$id'

");

header("Location:index.php");
exit;