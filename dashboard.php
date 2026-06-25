<?php
include 'auth/session.php';
include 'config/koneksi.php';
include 'template/header.php';
include 'template/sidebar.php';
?>
<?php

$jmlAlternatif =
mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM alternatif")
);

$jmlKriteria =
mysqli_num_rows(
mysqli_query($conn,"SELECT * FROM kriteria")
);

$query = mysqli_query($conn, "
    SELECT nama, nilai
    FROM ranking
    ORDER BY nilai DESC
    LIMIT 3
");

$podium = [];

$query = mysqli_query($conn, "
    SELECT
        a.nama_lokasi,
        h.nilai_preferensi,
        h.ranking
    FROM hasil_topsis h
    JOIN alternatif a
        ON h.id_alternatif = a.id_alternatif
    ORDER BY h.ranking ASC
    LIMIT 3
");

while ($row = mysqli_fetch_assoc($query)) {
    $podium[] = $row;
}
?>

<div class="bg-white shadow p-4 flex justify-between items-center">

    <h2 class="font-bold text-xl text-shellRed">
        Sistem Penunjang Keputusan SPBU
    </h2>

    <div>
        <span class="font-medium">
            <?= $_SESSION['nama']; ?>
        </span>
    </div>

</div>

<div class="bg-gradient-to-r from-shellRed to-red-700 text-white p-8 rounded-2xl shadow-lg mb-8">
    <h2 class="text-3xl font-bold">
        Sistem Penunjang Keputusan
    </h2>

    <p class="mt-2 text-yellow-200">
        Penentuan Lokasi Pembangunan SPBU Menggunakan Metode TOPSIS
    </p>
</div>

<div class="grid md:grid-cols-3 gap-6 mt-6">

    <div class="bg-shellYellow p-6 rounded-xl shadow">

        <h3 class="text-gray-700">
            Total Lokasi
        </h3>

        <p class="text-4xl font-bold text-shellRed">
            <?= $jmlAlternatif ?>
        </p>

    </div>

    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="text-gray-700">
            Total Kriteria
        </h3>

        <p class="text-4xl font-bold text-shellRed">
            <?= $jmlKriteria ?>
        </p>

    </div>

    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="text-gray-700">
            Metode
        </h3>

        <p class="text-2xl font-bold text-shellRed">
            TOPSIS
        </p>

    </div>

    <div class="bg-white p-6 rounded-xl shadow mt-6">

    <h3 class="font-bold text-lg mb-4">
        Statistik Sistem
    </h3>

    <canvas id="grafik"></canvas>

    </div>
        <div class="bg-white p-6 rounded-xl shadow mt-6">

    <h3 class="font-bold text-lg mb-4">
        Lokasi Terbaru
    </h3>

    <table class="w-full table-auto">

        <thead>

            <tr class="bg-shellYellow">

                <th class="p-3 w-20 text-left">No</th>
                <th class="p-3 text-left">Nama Lokasi</th>

            </tr>

        </thead>

        <tbody>

        <?php

        $no=1;

        $q = mysqli_query(
        $conn,
        "SELECT * FROM alternatif ORDER BY id_alternatif DESC LIMIT 5"
        );

        while($d=mysqli_fetch_assoc($q))
        {

        ?>

        <tr class="border-b">

            <td class="p-3 w-20"><?= $no++ ?></td>
            

            <td class="p-3">
                <?= $d['nama_lokasi'] ?>
            </td>

        </tr>

        <?php } ?>

        </tbody>

    </table>

    </div>

    <div class="bg-white p-6 rounded-xl shadow">

    <h3 class="text-xl font-bold text-center mb-8">
        Top 3 Lokasi Terbaik
    </h3>

    <div class="flex justify-center items-end gap-4">

        <!-- Juara 2 -->
        <?php if(isset($podium[1])): ?>
        <div class="text-center">
            <div class="mb-2 font-semibold">
                <?= $podium[1]['nama_lokasi'] ?>
            </div>

            <div class="w-32 h-32 bg-gray-300 rounded-t-xl flex items-center justify-center text-4xl">
                <span class="inline-flex items-center justify-center bg-slate-100 text-slate-800 text-xs font-bold px-6 py-3 rounded-full border border-slate-300 shadow-sm">
            <span class="w-2 h-2 mr-1.5 bg-slate-400 rounded-full"></span>Top 2
          </span>
            </div>

            <div class="bg-gray-100 py-2 font-bold">
                <?= round($podium[1]['nilai_preferensi'],4) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Juara 1 -->
        <?php if(isset($podium[0])): ?>
        <div class="text-center">
            <div class="mb-2 font-semibold">
                <?= $podium[0]['nama_lokasi'] ?>
            </div>

            <div class="w-32 h-48 bg-yellow-400 rounded-t-xl flex items-center justify-center text-5xl">
                <span class="inline-flex items-center justify-center bg-amber-100 text-amber-800 text-xs font-bold px-6 py-3 rounded-full border border-amber-300 shadow-sm animate-bounce">
            <span class="w-2 h-2 mr-1.5 bg-amber-500 rounded-full"></span>Top 1
          </span>
            </div>

            <div class="bg-yellow-100 py-2 font-bold">
                <?= round($podium[0]['nilai_preferensi'],4) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Juara 3 -->
        <?php if(isset($podium[2])): ?>
        <div class="text-center">
            <div class="mb-2 font-semibold">
                <?= $podium[2]['nama_lokasi'] ?>
            </div>

            <div class="w-32 h-24 bg-orange-300 rounded-t-xl flex items-center justify-center text-4xl">
                <span class="inline-flex items-center justify-center bg-orange-100 text-orange-800 text-xs font-bold px-6 py-3 rounded-full border border-orange-300 shadow-sm">
            <span class="w-2 h-2 mr-1.5 bg-orange-400 rounded-full"></span>Top 3
          </span>
            </div>

            <div class="bg-orange-100 py-2 font-bold">
                <?= round($podium[2]['nilai_preferensi'],4) ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

</div>
</div>
<script>

new Chart(
document.getElementById('grafik'),
{
type:'bar',

data:{
labels:[
'Alternatif',
'Kriteria'
],

datasets:[{
data:[
<?= $jmlAlternatif ?>,
<?= $jmlKriteria ?>
]
}]
}
}
);

</script>
<?php include 'template/footer.php'; ?>