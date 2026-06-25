<?php
require_once '../config/koneksi.php';
include '../template/header.php';
include '../template/sidebar.php';
include '../template/notifikasi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama_lokasi'];
    $alamat = $_POST['alamat'];
    $kecamatan = $_POST['kecamatan'];
    
    /** @var mysqli $conn */
    // 2. Simpan hasil query ke dalam variabel untuk dicek keberhasilannya
    $query = mysqli_query($conn, "
        INSERT INTO alternatif
        (nama_lokasi, alamat, kecamatan)
        VALUES
        ('$nama', '$alamat', '$kecamatan')
    ");
    
    // 3. Cek apakah query berhasil atau gagal, lalu set session terkait
    if ($query) {
        $_SESSION['sukses'] = "Data lokasi baru berhasil disimpan!";
    } else {
        $_SESSION['gagal'] = "Gagal menyimpan data lokasi: " . mysqli_error($conn);
    }
    
    // 4. Alihkan halaman ke index.php
    header("Location: index.php");
    exit(); // Selalu gunakan exit setelah header redirect agar script di bawahnya tidak dieksekusi
}
?>

<div class="bg-white rounded-2xl shadow-lg p-6">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-3xl font-bold">
            Data Lokasi SPBU
        </h1>

    </div>

    <div class="bg-white p-8 rounded-xl shadow-lg">

        <h1 class="text-3xl font-bold text-shellRed mb-6">
            Tambah Lokasi SPBU
        </h1>

        <form method="POST">

            <div class="mb-4">
                <label class="block font-medium mb-2">
                    Nama Lokasi
                </label>

                <input type="text" name="nama_lokasi" class="w-full border rounded-lg p-3" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-2">
                    Kecamatan
                </label>

                <input type="text" name="kecamatan" class="w-full border rounded-lg p-3" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-2">
                    Alamat
                </label>

                <textarea name="alamat" rows="4" class="w-full border rounded-lg p-3" required></textarea>
            </div>

            <button type="submit" name="simpan" class="bg-shellRed text-white px-5 py-3 rounded-lg">
                Simpan
            </button>

            <a href="index.php" class="bg-gray-500 text-white px-5 py-3 rounded-lg">
                Kembali
            </a>

        </form>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-6">

        <table class="w-full table-auto border-collapse">

            <thead>

                <tr class="bg-shellYellow">

                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama Lokasi</th>
                    <th class="p-3 text-left">Kecamatan</th>
                    <th class="p-3 text-left">Alamat</th>
                    <th class="p-3 text-center">Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $no = 1;
                /** @var mysqli $conn */
                $query = mysqli_query(
                    $conn,
                    "SELECT * FROM alternatif ORDER BY id_alternatif DESC"
                );

                while ($data = mysqli_fetch_assoc($query)) {
                    ?>

                    <tr class="border-b">

                        <td class="p-3"><?= $no++ ?></td>

                        <td class="p-3">
                            <?= $data['nama_lokasi'] ?>
                        </td>

                        <td class="p-3">
                            <?= $data['kecamatan'] ?>
                        </td>

                        <td class="p-3">
                            <?= $data['alamat'] ?>
                        </td>

                        <td class="p-3 text-center">

                           
                        <a href="edit.php?id=<?= $data['id_alternatif'] ?>"
                            class="bg-yellow-500 text-white px-3 py-2 rounded">
                            <i class="fa-solid fa-pen-to-square mr-1"></i>
                            Edit
                            </a>

                            <a href="hapus.php?id=<?= $data['id_alternatif'] ?>"
                            onclick="return confirm('Hapus data?')"
                            class="bg-red-600 text-white px-3 py-2 rounded">
                            <i class="fa-solid fa-trash mr-1"></i>
                            Hapus
                            </a>
                     </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php include '../template/footer.php'; ?>