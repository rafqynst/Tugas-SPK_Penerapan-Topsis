<?php
require_once '../config/koneksi.php';
include '../template/header.php';
include '../template/sidebar.php';

/** @var mysqli $conn */
$data = mysqli_query($conn,
"SELECT * FROM kriteria ORDER BY id_kriteria DESC");

$total = mysqli_num_rows($data);

$benefit = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM kriteria WHERE tipe='Benefit'")
);

$cost = mysqli_num_rows(
mysqli_query($conn,
"SELECT * FROM kriteria WHERE tipe='Cost'")
);

$bobot = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT SUM(bobot) as total FROM kriteria")
);
?>

<div class="p-8">

    <!-- Header -->

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Data Kriteria
        </h1>

        <p class="text-gray-500">
            Kelola kriteria penilaian lokasi pembangunan SPBU
        </p>

    </div>

    <!-- Statistik -->

    <div class="grid md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-red-600">
            <h5 class="text-gray-500">Total Kriteria</h5>
            <h2 class="text-3xl font-bold text-red-600">
                <?= $total ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-green-500">
            <h5 class="text-gray-500">Benefit</h5>
            <h2 class="text-3xl font-bold text-green-600">
                <?= $benefit ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-yellow-500">
            <h5 class="text-gray-500">Cost</h5>
            <h2 class="text-3xl font-bold text-yellow-600">
                <?= $cost ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-blue-500">
            <h5 class="text-gray-500">Total Bobot</h5>
            <h2 class="text-3xl font-bold text-blue-600">
                <?= $bobot['total'] ?>
            </h2>
        </div>

    </div>

    <!-- Form -->

    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">

        <h3 class="text-xl font-bold mb-6">
            Tambah Kriteria
        </h3>

        <form action="simpan.php" method="POST">

            <div class="grid md:grid-cols-2 gap-6">

                <div>
                    <label>Kode Kriteria</label>

                    <?php

                    $q = mysqli_query($conn,
                    "SELECT MAX(id_kriteria) as id FROM kriteria");

                    $r = mysqli_fetch_assoc($q);

                    $kode = "C".($r['id']+1);
                    ?>

                    <input
                    type="text"
                    name="kode_kriteria"
                    value="<?= $kode ?>"
                    readonly
                    class="w-full border rounded-xl p-3 mt-2 bg-gray-100">
                </div>

                <div>
                    <label>Nama Kriteria</label>

                    <input
                    type="text"
                    name="nama_kriteria"
                    required
                    class="w-full border rounded-xl p-3 mt-2">
                </div>

                <div>
                    <label>Bobot</label>

                    <input
                    type="number"
                    name="bobot"
                    required
                    class="w-full border rounded-xl p-3 mt-2">
                </div>

                <div>
                    <label>Tipe</label>

                    <select
                    name="tipe"
                    class="w-full border rounded-xl p-3 mt-2">

                        <option value="Benefit">
                            Benefit
                        </option>

                        <option value="Cost">
                            Cost
                        </option>

                    </select>
                </div>

            </div>

            <button
            class="mt-6 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl">

                Simpan Data

            </button>

        </form>

    </div>

    <!-- Tabel -->

    <div class="bg-white rounded-2xl shadow-lg p-6">

        <h3 class="text-xl font-bold mb-5">
            Daftar Kriteria
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-red-600 text-white text-left ">

                        <th class="p-3">No</th>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Bobot</th>
                        <th>Tipe</th>
                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    <?php
                    $no=1;

                    mysqli_data_seek($data,0);

                    while($d=mysqli_fetch_array($data)){
                    ?>

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-3"><?= $no++ ?></td>

                        <td><?= $d['kode_kriteria'] ?></td>

                        <td><?= $d['nama_kriteria'] ?></td>

                        <td><?= $d['bobot'] ?></td>

                        <td>

                            <?php
                            if($d['tipe']=="Benefit"){
                            ?>

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Benefit
                            </span>

                            <?php
                            } else {
                            ?>

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                Cost
                            </span>

                            <?php } ?>

                        </td>

                        <td>

                            <div class="flex gap-2">

                                <a
                                href="edit.php?id=<?= $d['id_kriteria']; ?>"
                                class="bg-yellow-500 hover:bg-yellow-600
                                text-white px-3 py-2 rounded-lg">

                                    <i class="fas fa-edit"></i>

                                </a>

                                <a
                                href="hapus.php?id=<?= $d['id_kriteria']; ?>"
                                onclick="return confirm('Yakin ingin menghapus data ini?')"
                                class="bg-red-600 hover:bg-red-700
                                text-white px-3 py-2 rounded-lg">

                                    <i class="fas fa-trash"></i>

                                </a>

                            </div>

                        </td>

                    </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
<?php include '../template/footer.php'; ?>