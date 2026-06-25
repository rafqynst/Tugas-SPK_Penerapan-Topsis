<?php

require_once '../config/koneksi.php';
include '../template/header.php';
include '../template/sidebar.php';
include '../template/notifikasi.php';

$id = $_GET['id'];
/** @var mysqli $conn */
$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM alternatif
         WHERE id_alternatif='$id'"
    )
);

if(isset($_POST['update']))
{
    $nama = $_POST['nama_lokasi'];
    $kecamatan = $_POST['kecamatan'];
    $alamat = $_POST['alamat'];

    $query = mysqli_query(
        $conn,
        "UPDATE alternatif
        SET
            nama_lokasi='$nama',
            kecamatan='$kecamatan',
            alamat='$alamat'
        WHERE id_alternatif='$id'"
    );
 if ($query) {
        $_SESSION['sukses'] = "Data lokasi Berhasil di Update!";
    } else {
        $_SESSION['gagal'] = "Gagal : " . mysqli_error($conn);
    }
    header("Location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-10">

    <div class="bg-white p-8 rounded-xl shadow-lg">

<h2 class="text-2xl font-bold text-shellRed mb-6">
    Edit Data Lokasi
</h2>

<form method="POST">

    <label class="block mb-2 font-medium">
        Nama Lokasi
    </label>

    <input
        type="text"
        name="nama_lokasi"
        value="<?= $data['nama_lokasi'] ?>"
        class="w-full border p-3 rounded mb-4"
        required>

    <label class="block mb-2 font-medium">
        Kecamatan
    </label>

    <input
        type="text"
        name="kecamatan"
        value="<?= $data['kecamatan'] ?>"
        class="w-full border p-3 rounded mb-4"
        required>

    <label class="block mb-2 font-medium">
        Alamat
    </label>

    <textarea
        name="alamat"
        class="w-full border p-3 rounded mb-4"
        rows="4"
        required><?= $data['alamat'] ?></textarea>

    <button
        type="submit"
        name="update"
        class="bg-yellow-500 text-white px-5 py-2 rounded">
        Update
    </button>

    <a href="index.php"
       class="bg-gray-500 text-white px-5 py-2 rounded">
       Kembali
    </a>

</form>

</div>
</div>

</body>
</html>
<?php include '../template/footer.php'; ?>