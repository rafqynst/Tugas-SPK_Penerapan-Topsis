<?php

require_once '../config/koneksi.php';
include '../template/notifikasi.php';
/** @var mysqli $conn */

foreach($_POST['nilai'] as $idAlternatif => $kriteria){

    foreach($kriteria as $idKriteria => $nilai){

        $cek = mysqli_query($conn,

        "SELECT * FROM penilaian
        WHERE id_alternatif='$idAlternatif'
        AND id_kriteria='$idKriteria'");

        if(mysqli_num_rows($cek)>0){

            mysqli_query($conn,

            "UPDATE penilaian SET
            nilai='$nilai'

            WHERE id_alternatif='$idAlternatif'
            AND id_kriteria='$idKriteria'");

        }else{

            $query = mysqli_query($conn,

            "INSERT INTO penilaian
            (id_alternatif,id_kriteria,nilai)

            VALUES

            ('$idAlternatif','$idKriteria','$nilai')");
            if ($query) {
        $_SESSION['sukses'] = "Data lokasi Berhasil di hapus!";
    } else {
        $_SESSION['gagal'] = "Gagal : " . mysqli_error($conn);
    }

        }

    }

}

echo "
<script>
alert('Penilaian berhasil disimpan');
window.location='index.php';
</script>
";