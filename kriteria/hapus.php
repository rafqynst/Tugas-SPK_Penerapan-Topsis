<?php

require_once '../config/koneksi.php';
/** @var mysqli $conn */

$id = $_GET['id'];

mysqli_query($conn,
"DELETE FROM kriteria
WHERE id_kriteria='$id'");

header("Location:index.php");
exit;