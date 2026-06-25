<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "spk_spbu"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>