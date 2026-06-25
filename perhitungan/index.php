<?php

require_once '../config/koneksi.php';
include '../template/header.php';
include '../template/sidebar.php';
/** @var mysqli $conn */
/*
|--------------------------------------------------------------------------
| AMBIL DATA ALTERNATIF DAN KRITERIA
|--------------------------------------------------------------------------
*/

$alternatif = mysqli_query(
    $conn,
    "SELECT * FROM alternatif ORDER BY id_alternatif ASC"
);

$kriteria = mysqli_query(
    $conn,
    "SELECT * FROM kriteria ORDER BY id_kriteria ASC"
);

?>

<div class="p-8 bg-gray-100 min-h-screen">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Perhitungan TOPSIS
        </h1>

        <p class="text-gray-500">
            Proses perhitungan metode TOPSIS untuk menentukan lokasi pembangunan SPBU terbaik.
        </p>

    </div>

    <!-- ===================================================== -->
    <!-- MATRIKS KEPUTUSAN -->
    <!-- ===================================================== -->

    <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">

        <div class="bg-red-600 text-white px-6 py-4">

            <h2 class="text-xl font-bold">
                Matriks Keputusan (X)
            </h2>

            <p class="text-sm opacity-90">
                Data penilaian awal setiap alternatif terhadap seluruh kriteria.
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Alternatif
                        </th>

                        <?php

                        $kritHeader = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria ASC"
                        );

                        while ($k = mysqli_fetch_assoc($kritHeader)) {
                            ?>

                            <th class="p-4 text-center">

                                <?= $k['kode_kriteria']; ?>

                            </th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>
                    <?php

                    $altData = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif ORDER BY id_alternatif ASC"
                    );

                    while ($a = mysqli_fetch_assoc($altData)) {

                        ?>

                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-4 font-semibold">

                                <?= $a['nama_lokasi']; ?>

                            </td>

                            <?php

                            $krit = mysqli_query(
                                $conn,
                                "SELECT * FROM kriteria ORDER BY id_kriteria ASC"
                            );

                            while ($k = mysqli_fetch_assoc($krit)) {

                                $nilai = mysqli_fetch_assoc(

                                    mysqli_query(
                                        $conn,

                                        "SELECT nilai
                            FROM penilaian

                            WHERE id_alternatif='$a[id_alternatif]'
                            AND id_kriteria='$k[id_kriteria]'"
                                    )

                                );

                                ?>

                                <td class="text-center p-4">

                                    <?= $nilai['nilai']; ?>

                                </td>

                            <?php } ?>

                        </tr>

                    <?php } ?>
                </tbody>

            </table>

        </div>

    </div>

    <?php

    /*
    |--------------------------------------------------------------------------
    | MENCARI PEMBAGI NORMALISASI
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | √(Σ Xij²)
    |
    | Digunakan sebagai penyebut pada proses normalisasi.
    |
    */

    $pembagi = [];

    $kritPembagi = mysqli_query(
        $conn,
        "SELECT * FROM kriteria"
    );

    while ($k = mysqli_fetch_assoc($kritPembagi)) {

        $jumlahKuadrat = 0;

        $nilai = mysqli_query(
            $conn,

            "SELECT nilai

    FROM penilaian

    WHERE id_kriteria='$k[id_kriteria]'"
        );

        while ($n = mysqli_fetch_assoc($nilai)) {

            $jumlahKuadrat += pow($n['nilai'], 2);

        }

        $pembagi[$k['id_kriteria']] =
            sqrt($jumlahKuadrat);
    }
    ?>

    <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">

        <div class="bg-yellow-500 text-white px-6 py-4">

            <h2 class="text-xl font-bold">
                Matriks Normalisasi (R)
            </h2>

            <p class="text-sm">

                Rumus:
                Rij = Xij / √(ΣX²)

            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Alternatif
                        </th>

                        <?php

                        $kritHeader = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria"
                        );

                        while ($k = mysqli_fetch_assoc($kritHeader)) {
                            ?>

                            <th class="p-4">

                                <?= $k['kode_kriteria']; ?>

                            </th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>
                    <?php

                    $altNormal = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($altNormal)) {

                        ?>

                        <tr class="border-b">

                            <td class="p-4 font-semibold">

                                <?= $a['nama_lokasi']; ?>

                            </td>

                            <?php

                            $krit = mysqli_query(
                                $conn,
                                "SELECT * FROM kriteria"
                            );

                            while ($k = mysqli_fetch_assoc($krit)) {

                                $nilai = mysqli_fetch_assoc(

                                    mysqli_query(
                                        $conn,

                                        "SELECT nilai

                            FROM penilaian

                            WHERE id_alternatif='$a[id_alternatif]'
                            AND id_kriteria='$k[id_kriteria]'"
                                    )

                                );

                                $normalisasi =
                                    $nilai['nilai'] /
                                    $pembagi[$k['id_kriteria']];
                                ?>

                                <td class="text-center p-4">

                                    <?= round($normalisasi, 4); ?>

                                </td>

                            <?php } ?>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <?php

    /*
    |--------------------------------------------------------------------------
    | MATRIKS TERNORMALISASI TERBOBOT (Y)
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | Yij = Rij × Wj
    |
    | Rij = nilai normalisasi
    | Wj  = bobot kriteria
    |
    */

    $matriksTerbobot = [];

    $alternatifData = mysqli_query(
        $conn,
        "SELECT * FROM alternatif ORDER BY id_alternatif"
    );

    while ($alt = mysqli_fetch_assoc($alternatifData)) {

        $kritData = mysqli_query(
            $conn,
            "SELECT * FROM kriteria ORDER BY id_kriteria"
        );

        while ($k = mysqli_fetch_assoc($kritData)) {

            $nilai = mysqli_fetch_assoc(

                mysqli_query(
                    $conn,

                    "SELECT nilai

        FROM penilaian

        WHERE id_alternatif='$alt[id_alternatif]'
        AND id_kriteria='$k[id_kriteria]'"
                )

            );

            $rij =
                $nilai['nilai'] /
                $pembagi[$k['id_kriteria']];

            /*
            Bobot disimpan:
            25,20,15 dst

            Maka harus menjadi:

            0.25
            0.20
            0.15
            */

            $bobot = $k['bobot'] / 100;

            $yij = $rij * $bobot;

            $matriksTerbobot
            [$alt['id_alternatif']]
            [$k['id_kriteria']]
                = $yij;

        }

    }

    ?>
    <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">

        <div class="bg-green-600 text-white px-6 py-4">

            <h2 class="text-xl font-bold">
                Matriks Ternormalisasi Terbobot (Y)
            </h2>

            <p class="text-sm">

                Rumus :

                Yij = Rij × Wj

            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Alternatif
                        </th>

                        <?php

                        $kritHeader = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria"
                        );

                        while ($k = mysqli_fetch_assoc($kritHeader)) {
                            ?>

                            <th class="p-4">

                                <?= $k['kode_kriteria']; ?>

                            </th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>
                    <?php

                    $alt = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($alt)) {

                        ?>

                        <tr class="border-b">

                            <td class="p-4 font-semibold">

                                <?= $a['nama_lokasi']; ?>

                            </td>

                            <?php

                            $krit = mysqli_query(
                                $conn,
                                "SELECT * FROM kriteria"
                            );

                            while ($k = mysqli_fetch_assoc($krit)) {

                                ?>

                                <td class="text-center p-4">

                                    <?= round(

                                        $matriksTerbobot
                                        [$a['id_alternatif']]
                                        [$k['id_kriteria']]

                                        ,
                                        4
                                    ); ?>

                                </td>

                            <?php } ?>

                        </tr>

                    <?php } ?>
                </tbody>

            </table>

        </div>

    </div>

    <?php

    /*
    |--------------------------------------------------------------------------
    | SOLUSI IDEAL POSITIF (A+)
    |--------------------------------------------------------------------------
    |
    | Benefit = Nilai Terbesar
    | Cost    = Nilai Terkecil
    |
    */

    /*
    |--------------------------------------------------------------------------
    | SOLUSI IDEAL NEGATIF (A-)
    |--------------------------------------------------------------------------
    |
    | Benefit = Nilai Terkecil
    | Cost    = Nilai Terbesar
    |
    */

    $aplus = [];
    $amin = [];

    $kritIdeal = mysqli_query(
        $conn,
        "SELECT * FROM kriteria"
    );

    while ($k = mysqli_fetch_assoc($kritIdeal)) {

        $kolom = [];

        foreach ($matriksTerbobot as $alternatif) {

            $kolom[] =
                $alternatif[$k['id_kriteria']];
        }

        if ($k['tipe'] == "Benefit") {

            $aplus[$k['id_kriteria']]
                = max($kolom);

            $amin[$k['id_kriteria']]
                = min($kolom);

        } else {

            $aplus[$k['id_kriteria']]
                = min($kolom);

            $amin[$k['id_kriteria']]
                = max($kolom);

        }

    }
    ?>
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="bg-blue-600 text-white px-6 py-4">

                <h2 class="font-bold text-xl">
                    Solusi Ideal Positif (A+)
                </h2>

            </div>

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-3 text-left">
                            Kriteria
                        </th>

                        <th>
                            Nilai
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $krit = mysqli_query(
                        $conn,
                        "SELECT * FROM kriteria"
                    );

                    while ($k = mysqli_fetch_assoc($krit)) {
                        ?>

                        <tr class="border-b">

                            <td class="p-3">

                                <?= $k['kode_kriteria']; ?>

                            </td>

                            <td class="text-center">

                                <?= round(
                                    $aplus[$k['id_kriteria']]
                                    ,
                                    4
                                ); ?>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <div class="bg-red-600 text-white px-6 py-4">

                <h2 class="font-bold text-xl">
                    Solusi Ideal Negatif (A-)
                </h2>

            </div>

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-3 text-left">
                            Kriteria
                        </th>

                        <th>
                            Nilai
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $krit = mysqli_query(
                        $conn,
                        "SELECT * FROM kriteria"
                    );

                    while ($k = mysqli_fetch_assoc($krit)) {
                        ?>

                        <tr class="border-b">

                            <td class="p-3">

                                <?= $k['kode_kriteria']; ?>

                            </td>

                            <td class="text-center">

                                <?= round(
                                    $amin[$k['id_kriteria']]
                                    ,
                                    4
                                ); ?>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>
    </div>

    <?php

    /*
    |--------------------------------------------------------------------------
    | JARAK TERHADAP SOLUSI IDEAL POSITIF (D+)
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | D+ = √ Σ(Yij - A+)²
    |
    */

    /*
    |--------------------------------------------------------------------------
    | JARAK TERHADAP SOLUSI IDEAL NEGATIF (D-)
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | D- = √ Σ(Yij - A-)²
    |
    */

    $dplus = [];
    $dminus = [];

    $alternatifData = mysqli_query(
        $conn,
        "SELECT * FROM alternatif ORDER BY id_alternatif"
    );

    while ($alt = mysqli_fetch_assoc($alternatifData)) {

        $jumlahPlus = 0;
        $jumlahMinus = 0;

        $krit = mysqli_query(
            $conn,
            "SELECT * FROM kriteria"
        );

        while ($k = mysqli_fetch_assoc($krit)) {

            $nilaiY =
                $matriksTerbobot
                [$alt['id_alternatif']]
                [$k['id_kriteria']];

            $jumlahPlus += pow(
                $nilaiY -
                $aplus[$k['id_kriteria']]
                ,
                2
            );

            $jumlahMinus += pow(
                $nilaiY -
                $amin[$k['id_kriteria']]
                ,
                2
            );

        }

        $dplus[$alt['id_alternatif']]
            = sqrt($jumlahPlus);

        $dminus[$alt['id_alternatif']]
            = sqrt($jumlahMinus);

    }
    ?>
    <div class="bg-white rounded-2xl shadow-lg mb-8 overflow-hidden">

        <div class="bg-purple-600 text-white px-6 py-4">

            <h2 class="font-bold text-xl">
                Jarak Solusi Ideal
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4 text-left">
                            Alternatif
                        </th>

                        <th>
                            D+
                        </th>

                        <th>
                            D-
                        </th>

                    </tr>

                </thead>

                <tbody>
                    <?php

                    $alt = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($alt)) {
                        ?>

                        <tr class="border-b">

                            <td class="p-4 font-semibold">

                                <?= $a['nama_lokasi']; ?>

                            </td>

                            <td class="text-center">

                                <?= round(
                                    $dplus[$a['id_alternatif']]
                                    ,
                                    4
                                ); ?>

                            </td>

                            <td class="text-center">

                                <?= round(
                                    $dminus[$a['id_alternatif']]
                                    ,
                                    4
                                ); ?>

                            </td>

                        </tr>

                    <?php } ?>
                </tbody>

            </table>

        </div>

    </div>

    <?php

    /*
    |--------------------------------------------------------------------------
    | NILAI PREFERENSI (V)
    |--------------------------------------------------------------------------
    |
    | Rumus:
    |
    | V = D- / (D+ + D-)
    |
    | Semakin mendekati 1
    | maka semakin baik.
    |
    */

    $ranking = [];

    $alt = mysqli_query(
        $conn,
        "SELECT * FROM alternatif"
    );

    while ($a = mysqli_fetch_assoc($alt)) {

        $penyebut =

            $dplus[$a['id_alternatif']]
            +
            $dminus[$a['id_alternatif']];

        if ($penyebut == 0) {

            $preferensi = 0;

        } else {

            $preferensi =
                $dminus[$a['id_alternatif']]
                /
                $penyebut;

        }

        $ranking[] = [

            'id' => $a['id_alternatif'],

            'nama' => $a['nama_lokasi'],

            'nilai' => $preferensi

        ];

    }
    usort($ranking, function ($a, $b) {

        return $b['nilai']
            <=>
            $a['nilai'];

    });

    if(isset($_POST['simpan_hasil'])){

    mysqli_query($conn,"TRUNCATE TABLE hasil_topsis");

$urutan = 1;

foreach($ranking as $r){

    mysqli_query($conn,

    "INSERT INTO hasil_topsis
    (
        id_alternatif,
        nilai_preferensi,
        ranking
    )

    VALUES
    (
        '".$r['id']."',
        '".$r['nilai']."',
        '$urutan'
    )");

    $urutan++;
}
}
    ?>
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">

        <div class="bg-green-600 text-white px-6 py-4">

            <h2 class="font-bold text-xl">

                Ranking Lokasi SPBU

            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="bg-gray-100">

                        <th class="p-4">
                            Ranking
                        </th>

                        <th>
                            Lokasi
                        </th>

                        <th>
                            Nilai Preferensi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $no = 1;

                    foreach ($ranking as $r) {

                        ?>

                        <tr class="border-b hover:bg-gray-50">

                            <td class="text-center p-4">

                                <?php

                                if ($no == 1) {

                                    echo "🥇";

                                } elseif ($no == 2) {

                                    echo "🥈";

                                } elseif ($no == 3) {

                                    echo "🥉";

                                } else {

                                    echo $no;

                                }

                                ?>

                            </td>

                            <td class="text-center p-4">

                                <?= $r['nama']; ?>

                            </td>

                            <td class="text-center font-bold text-green-600">

                                <?= round($r['nilai'], 4); ?>

                            </td>

                        </tr>

                        <?php

                        $no++;

                    }
                    ?>
                </tbody>

            </table>

        </div>
        <form method="POST">
    <button
        name="simpan_hasil"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg">
        Simpan Hasil Perhitungan
    </button>
</form>
    </div>

    <?php

    $pemenang = $ranking[0];

    ?>

    <div class="bg-gradient-to-r from-red-600 to-yellow-400 rounded-2xl shadow-lg p-8 text-white mb-8">

        <h2 class="text-2xl font-bold mb-4">

            Rekomendasi Lokasi Terbaik

        </h2>

        <div class="text-4xl font-bold mb-2">

            <?= $pemenang['nama']; ?>

        </div>

        <div class="text-lg">

            Nilai Preferensi :

            <strong>

                <?= round($pemenang['nilai'], 4); ?>

            </strong>

        </div>

    </div>
    <a href="cetak_pdf.php" target="_blank" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-medium shadow-md transition-all transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Laporan PDF
        </a>
</div>
<?php include '../template/footer.php'; ?>