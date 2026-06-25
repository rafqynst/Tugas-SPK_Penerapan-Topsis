<?php
require_once '../config/koneksi.php';
include '../template/header.php';
include '../template/sidebar.php';

/** @var mysqli $conn */

$id = $_GET['id'];

$data = mysqli_query($conn,
"SELECT * FROM kriteria WHERE id_kriteria='$id'");

$d = mysqli_fetch_assoc($data);


?>

<div class="p-8 bg-gray-100 min-h-screen">

    <div class="mb-8">

        <h1 class="text-3xl font-bold text-gray-800">
            Edit Kriteria
        </h1>

        <p class="text-gray-500">
            Ubah data kriteria penilaian TOPSIS
        </p>

    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <form action="update.php" method="POST">

            <input
            type="hidden"
            name="id_kriteria"
            value="<?= $d['id_kriteria']; ?>">

            <div class="grid md:grid-cols-2 gap-6">

                <div>

                    <label class="font-medium">
                        Kode Kriteria
                    </label>

                    <input
                    type="text"
                    name="kode_kriteria"
                    value="<?= $d['kode_kriteria']; ?>"
                    readonly
                    class="w-full mt-2 p-3 border rounded-xl bg-gray-100">

                </div>

                <div>

                    <label class="font-medium">
                        Nama Kriteria
                    </label>

                    <input
                    type="text"
                    name="nama_kriteria"
                    value="<?= $d['nama_kriteria']; ?>"
                    required
                    class="w-full mt-2 p-3 border rounded-xl">

                </div>

                <div>

                    <label class="font-medium">
                        Bobot
                    </label>

                    <input
                    type="number"
                    name="bobot"
                    value="<?= $d['bobot']; ?>"
                    required
                    class="w-full mt-2 p-3 border rounded-xl">

                </div>

                <div>

                    <label class="font-medium">
                        Tipe
                    </label>

                    <select
                    name="tipe"
                    class="w-full mt-2 p-3 border rounded-xl">

                        <option
                        value="Benefit"
                        <?= ($d['tipe']=="Benefit") ? 'selected' : ''; ?>>
                            Benefit
                        </option>

                        <option
                        value="Cost"
                        <?= ($d['tipe']=="Cost") ? 'selected' : ''; ?>>
                            Cost
                        </option>

                    </select>

                </div>

            </div>

            <div class="mt-6 flex gap-3">

                <button
                type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl">

                    Update Data

                </button>

                <a
                href="index.php"
                class="bg-gray-300 hover:bg-gray-400 px-6 py-3 rounded-xl">

                    Kembali

                </a>

            </div>

        </form>

    </div>

</div>

<?php include '../template/footer.php'; ?>