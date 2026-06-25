<?php
require_once '../config/koneksi.php';
include '../template/header.php';
include '../template/sidebar.php';
/** @var mysqli $conn */

$alternatif = mysqli_query(
    $conn,
    "SELECT * FROM alternatif ORDER BY id_alternatif ASC"
);

$kriteria = mysqli_query(
    $conn,
    "SELECT * FROM kriteria ORDER BY id_kriteria ASC"
);

$totalAlternatif = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM alternatif")
);

$totalKriteria = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM kriteria")
);

$totalPenilaian = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM penilaian")
);

$totalData = $totalAlternatif * $totalKriteria;

$persentase = 0;

if ($totalData > 0) {
    $persentase = round(($totalPenilaian / $totalData) * 100);
}
?>

<div class="p-8 bg-gray-100 min-h-screen">

    <!-- Header -->

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Penilaian Alternatif
        </h1>

        <p class="text-gray-500">
            Input nilai setiap lokasi berdasarkan seluruh kriteria yang telah ditentukan.
        </p>

    </div>

    <!-- Statistik -->

    <div class="grid md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-600">
            <h4 class="text-gray-500">
                Total Lokasi
            </h4>

            <h2 class="text-3xl font-bold text-red-600">
                <?= $totalAlternatif ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
            <h4 class="text-gray-500">
                Total Kriteria
            </h4>

            <h2 class="text-3xl font-bold text-yellow-500">
                <?= $totalKriteria ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <h4 class="text-gray-500">
                Total Penilaian
            </h4>

            <h2 class="text-3xl font-bold text-green-500">
                <?= $totalPenilaian ?>
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
            <h4 class="text-gray-500">
                Data Terisi
            </h4>

            <h2 class="text-3xl font-bold text-blue-500">
                <?= $persentase ?>%
            </h2>
        </div>

    </div>

    <!-- Informasi Kriteria -->

    <div class="grid md:grid-cols-4 gap-4 mb-8">

        <?php
        $infoKriteria = mysqli_query(
            $conn,
            "SELECT * FROM kriteria"
        );

        while ($k = mysqli_fetch_assoc($infoKriteria)) {
            ?>

            <div class="bg-white rounded-xl shadow p-4">

                <h4 class="font-bold text-red-600">
                    <?= $k['kode_kriteria'] ?>
                </h4>

                <p class="text-sm text-gray-700">
                    <?= $k['nama_kriteria'] ?>
                </p>

                <?php if ($k['tipe'] == "Benefit") { ?>

                    <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                        Benefit
                    </span>

                <?php } else { ?>

                    <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
                        Cost
                    </span>

                <?php } ?>

            </div>

        <?php } ?>

    </div>

    <!-- Form Penilaian -->

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

        <div class="bg-red-600 text-white px-6 py-4">

            <h3 class="text-xl font-bold">
                Matriks Penilaian TOPSIS
            </h3>

        </div>

        <form action="simpan.php" method="POST">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead>

                        <tr class="bg-gray-100">

                            <th class="p-4 text-left">
                                Lokasi
                            </th>

                            <?php
                            mysqli_data_seek($kriteria, 0);

                            while ($k = mysqli_fetch_assoc($kriteria)) {
                                ?>

                                <th class="p-4 text-center">
                                    <?= $k['kode_kriteria'] ?>
                                </th>

                            <?php } ?>

                        </tr>

                    </thead>

                    <tbody>

                        <?php
                        while ($a = mysqli_fetch_assoc($alternatif)) {
                            ?>

                            <tr class="border-b hover:bg-gray-50">

                                <td class="p-4">

                                    <div>

                                        <div class="font-semibold">
                                            <?= $a['nama_lokasi'] ?>
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            <?= $a['kecamatan'] ?>
                                        </div>

                                    </div>

                                </td>

                                <?php

                                $krit = mysqli_query(
                                    $conn,
                                    "SELECT * FROM kriteria ORDER BY id_kriteria ASC"
                                );

                                while ($k = mysqli_fetch_assoc($krit)) {

                                    $cek = mysqli_query(
                                        $conn,

                                        "SELECT * FROM penilaian
                        WHERE id_alternatif='$a[id_alternatif]'
                        AND id_kriteria='$k[id_kriteria]'"
                                    );

                                    $nilai = mysqli_fetch_assoc($cek);

                                    ?>

                                    <td class="p-3 text-center">

                                        <input type="number" min="1" max="1000000" step="any" required
                                            name="nilai[<?= $a['id_alternatif'] ?>][<?= $k['id_kriteria'] ?>]"
                                            value="<?= $nilai['nilai'] ?? '' ?>"
                                            class="w-24 border rounded-xl p-2 text-center focus:ring-2 focus:ring-red-500">

                                    </td>

                                <?php } ?>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

            <div class="p-6 bg-gray-50 flex justify-end">

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-xl">

                    Simpan Penilaian

                </button>

            </div>

        </form>

    </div>

</div>

<?php include '../template/footer.php'; ?>