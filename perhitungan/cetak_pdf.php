<?php
require_once '../config/koneksi.php';
// Load autoloader Dompdf
require_once '../vendor/autoload.php'; 
/** @var mysqli $conn */
use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Inisialisasi Dompdf dengan opsi agar bisa membaca CSS eksternal/style tag
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); 
$dompdf = new Dompdf($options);

// 2. Ambil data perhitungan kembali (Gunakan logika yang sama seperti di file utama)
$pembagi = [];
$kritPembagi = mysqli_query($conn, "SELECT * FROM kriteria");
while ($k = mysqli_fetch_assoc($kritPembagi)) {
    $jumlahKuadrat = 0;
    $nilai = mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_kriteria='$k[id_kriteria]'");
    while ($n = mysqli_fetch_assoc($nilai)) {
        $jumlahKuadrat += pow($n['nilai'], 2);
    }
    $pembagi[$k['id_kriteria']] = sqrt($jumlahKuadrat);
}

// Mulai pembungkusan HTML menggunakan Output Buffering
ob_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perhitungan TOPSIS</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #333; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px double #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 22px; color: #1e293b; }
        .header p { margin: 5px 0 0 0; color: #64748b; }
        
        .section-title { font-size: 14px; font-bold : true; background-color: #f1f5f9; padding: 8px; margin-top: 25px; margin-bottom: 10px; border-left: 5px solid #dc2626; color: #1e293b; }
        .section-title.normal { border-left-color: #eab308; }
        .section-title.terbobot { border-left-color: #16a34a; }
        .section-title.ideal { border-left-color: #2563eb; }
        .section-title.jarak { border-left-color: #9333ea; }
        .section-title.ranking { border-left-color: #16a34a; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; page-break-inside: avoid; }
        th { background-color: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; text-align: center; font-weight: bold; color: #334155; }
        td { border: 1px solid #cbd5e1; padding: 7px; text-align: center; }
        .text-left { text-align: left; }
        .font-semibold { font-weight: bold; }
        
        .rekomendasi { background: #fef2f2; border: 2px dashed #dc2626; padding: 15px; margin-top: 30px; text-align: center; border-radius: 8px; }
        .rekomendasi h2 { margin: 0 0 10px 0; color: #dc2626; font-size: 16px; }
        .rekomendasi .nama { font-size: 24px; font-weight: bold; color: #1e293b; }
        
        /* Utility untuk flex layout alternatif di PDF */
        .grid { width: 100%; }
        .col { width: 48%; float: left; }
        .col:last-child { float: right; }
        .clear { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN PERHITUNGAN TOPSIS</h1>
        <p>Sistem Pendukung Keputusan Penentuan Lokasi Pembangunan SPBU Terbaik</p>
    </div>

    <div class="section-title">Matriks Keputusan (X)</div>
    <table>
        <thead>
            <tr>
                <th class="text-left">Alternatif</th>
                <?php
                $kritHeader = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                while ($k = mysqli_fetch_assoc($kritHeader)) { echo "<th>".$k['kode_kriteria']."</th>"; }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $altData = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");
            while ($a = mysqli_fetch_assoc($altData)) { ?>
                <tr>
                    <td class="text-left font-semibold"><?= $a['nama_lokasi']; ?></td>
                    <?php
                    $krit = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                    while ($k = mysqli_fetch_assoc($krit)) {
                        $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_alternatif='$a[id_alternatif]' AND id_kriteria='$k[id_kriteria]'"));
                        echo "<td>".$nilai['nilai']."</td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="section-title normal">Matriks Normalisasi (R)</div>
    <table>
        <thead>
            <tr>
                <th class="text-left">Alternatif</th>
                <?php
                $kritHeader = mysqli_query($conn, "SELECT * FROM kriteria");
                while ($k = mysqli_fetch_assoc($kritHeader)) { echo "<th>".$k['kode_kriteria']."</th>"; }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $altNormal = mysqli_query($conn, "SELECT * FROM alternatif");
            while ($a = mysqli_fetch_assoc($altNormal)) { ?>
                <tr>
                    <td class="text-left font-semibold"><?= $a['nama_lokasi']; ?></td>
                    <?php
                    $krit = mysqli_query($conn, "SELECT * FROM kriteria");
                    while ($k = mysqli_fetch_assoc($krit)) {
                        $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_alternatif='$a[id_alternatif]' AND id_kriteria='$k[id_kriteria]'"));
                        $normalisasi = $nilai['nilai'] / $pembagi[$k['id_kriteria']];
                        echo "<td>".round($normalisasi, 4)."</td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="section-title terbobot">Matriks Ternormalisasi Terbobot (Y)</div>
    <table>
        <thead>
            <tr>
                <th class="text-left">Alternatif</th>
                <?php
                $kritHeader = mysqli_query($conn, "SELECT * FROM kriteria");
                while ($k = mysqli_fetch_assoc($kritHeader)) { echo "<th>".$k['kode_kriteria']."</th>"; }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $matriksTerbobot = [];
            $alternatifData = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif");
            while ($alt = mysqli_fetch_assoc($alternatifData)) { ?>
                <tr>
                    <td class="text-left font-semibold"><?= $alt['nama_lokasi']; ?></td>
                    <?php
                    $kritData = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria");
                    while ($k = mysqli_fetch_assoc($kritData)) {
                        $nilai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nilai FROM penilaian WHERE id_alternatif='$alt[id_alternatif]' AND id_kriteria='$k[id_kriteria]'"));
                        $rij = $nilai['nilai'] / $pembagi[$k['id_kriteria']];
                        $bobot = $k['bobot'] / 100;
                        $yij = $rij * $bobot;
                        $matriksTerbobot[$alt['id_alternatif']][$k['id_kriteria']] = $yij;
                        echo "<td>".round($yij, 4)."</td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    // Hitung Solusi Ideal
    $aplus = []; $amin = [];
    $kritIdeal = mysqli_query($conn, "SELECT * FROM kriteria");
    while ($k = mysqli_fetch_assoc($kritIdeal)) {
        $kolom = [];
        foreach ($matriksTerbobot as $alternatif) { $kolom[] = $alternatif[$k['id_kriteria']]; }
        if ($k['tipe'] == "Benefit") {
            $aplus[$k['id_kriteria']] = max($kolom);
            $amin[$k['id_kriteria']] = min($kolom);
        } else {
            $aplus[$k['id_kriteria']] = min($kolom);
            $amin[$k['id_kriteria']] = max($kolom);
        }
    }
    ?>

    <div class="grid">
        <div class="col">
            <div class="section-title ideal">Solusi Ideal Positif (A+)</div>
            <table>
                <thead><tr><th>Kriteria</th><th>Nilai</th></tr></thead>
                <tbody>
                    <?php
                    $krit = mysqli_query($conn, "SELECT * FROM kriteria");
                    while ($k = mysqli_fetch_assoc($krit)) {
                        echo "<tr><td>".$k['kode_kriteria']."</td><td>".round($aplus[$k['id_kriteria']], 4)."</td></tr>";
                    } ?>
                </tbody>
            </table>
        </div>
        <div class="col">
            <div class="section-title ideal" style="border-left-color:#dc2626;">Solusi Ideal Negatif (A-)</div>
            <table>
                <thead><tr><th>Kriteria</th><th>Nilai</th></tr></thead>
                <tbody>
                    <?php
                    $krit = mysqli_query($conn, "SELECT * FROM kriteria");
                    while ($k = mysqli_fetch_assoc($krit)) {
                        echo "<tr><td>".$k['kode_kriteria']."</td><td>".round($amin[$k['id_kriteria']], 4)."</td></tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clear"></div>

    <?php
    // Hitung Jarak dan Preferensi
    $dplus = []; $dminus = []; $ranking = [];
    $alternatifData = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif");
    while ($alt = mysqli_fetch_assoc($alternatifData)) {
        $jumlahPlus = 0; $jumlahMinus = 0;
        $krit = mysqli_query($conn, "SELECT * FROM kriteria");
        while ($k = mysqli_fetch_assoc($krit)) {
            $nilaiY = $matriksTerbobot[$alt['id_alternatif']][$k['id_kriteria']];
            $jumlahPlus += pow($nilaiY - $aplus[$k['id_kriteria']], 2);
            $jumlahMinus += pow($nilaiY - $amin[$k['id_kriteria']], 2);
        }
        $dplus[$alt['id_alternatif']] = sqrt($jumlahPlus);
        $dminus[$alt['id_alternatif']] = sqrt($jumlahMinus);

        $penyebut = $dplus[$alt['id_alternatif']] + $dminus[$alt['id_alternatif']];
        $preferensi = ($penyebut == 0) ? 0 : $dminus[$alt['id_alternatif']] / $penyebut;
        
        $ranking[] = [
            'nama' => $alt['nama_lokasi'],
            'nilai' => $preferensi
        ];
    }
    usort($ranking, function ($a, $b) { return $b['nilai'] <=> $a['nilai']; });
    ?>

    <div class="section-title ranking">Hasil Ranking Akhir</div>
    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Ranking</th>
                <th>Lokasi</th>
                <th>Nilai Preferensi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($ranking as $r) { ?>
                <tr>
                    <td><b><?= $no++; ?></b></td>
                    <td class="text-left"><?= $r['nama']; ?></td>
                    <td class="font-semibold" style="color: #16a34a;"><?= round($r['nilai'], 4); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="rekomendasi">
        <h2>Rekomendasi Lokasi Terbaik</h2>
        <div class="nama"><?= $ranking[0]['nama']; ?></div>
        <p>Dengan Nilai Preferensi tertinggi sebesar <b><?= round($ranking[0]['nilai'], 4); ?></b></p>
    </div>

</body>
</html>
<?php
// Ambil html dari buffer dan matikan buffer
$html = ob_get_clean();

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// (Opsional) Mengatur Ukuran Kertas dan Orientasi (A4 Potrait)
$dompdf->setPaper('A4', 'portrait');

// Render HTML ke PDF
$dompdf->render();

// Keluarkan file ke browser untuk diunduh langsung
$dompdf->stream("Laporan_Perhitungan_TOPSIS.pdf", array("Attachment" => 0)); 
// Nilai 0 = Preview di browser, Nilai 1 = Auto-download
?>