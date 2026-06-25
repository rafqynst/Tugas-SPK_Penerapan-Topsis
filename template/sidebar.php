<?php include_once __DIR__ . '/../config/base.php'; ?>
<div class="flex">

<!-- SIDEBAR -->
<aside class="fixed top-0 left-0 w-64 h-screen bg-shellRed text-white shadow-xl">

    <div class="p-6 text-center border-b border-red-700">

        <img src="<?= $base_url ?>assets/img/shell-logo.png"
     class="w-16 mx-auto mb-3">

        <h1 class="font-bold text-xl">
            SPK SPBU
        </h1>

        <p class="text-yellow-300 text-sm">
            Metode TOPSIS
        </p>

    </div>

    <nav class="p-4">

        <a href="<?= $base_url ?>dashboard.php"
        class="block py-3 px-4 rounded-lg hover:bg-shellYellow hover:text-black transition mb-2">
        Dashboard
        </a>

        <a href="<?= $base_url ?>alternatif/index.php"
        class="block py-3 px-4 rounded-lg hover:bg-shellYellow hover:text-black transition mb-2">
        Data Lokasi
        </a>

        <a href="<?= $base_url ?>kriteria/index.php"
        class="block py-3 px-4 rounded-lg hover:bg-shellYellow hover:text-black transition mb-2">
        Data Kriteria
        </a>

        <a href="<?= $base_url ?>penilaian/index.php"
        class="block py-3 px-4 rounded-lg hover:bg-shellYellow hover:text-black transition mb-2">
        Penilaian
        </a>

        <a href="<?= $base_url ?>perhitungan/index.php"
        class="block py-3 px-4 rounded-lg hover:bg-shellYellow hover:text-black transition mb-2">
        Hasil TOPSIS
        </a>

        <a href="<?= $base_url ?>auth/logout.php"
        class="block py-3 px-4 rounded-lg hover:bg-gray-500 hover:text-black transition mb-2">
        <i class="fa-solid fa-right-from-bracket"></i>
        Logout

        </a>
        

    </nav>

</aside>

<div class="ml-64 flex-1">