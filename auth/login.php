<?php
include_once __DIR__ . '/../config/base.php';
session_start();

if (isset($_SESSION['login'])) {
    header("Location: ../dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login SPK SPBU</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        shellRed: '#E1251B',
                        shellYellow: '#FFD500',
                        shellDark: '#333333'
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-600 via-red-700 to-red-900">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="grid md:grid-cols-2">

            <!-- KIRI -->

            <div class="hidden md:flex flex-col justify-center items-center bg-shellYellow p-10">

                 <img src="<?= $base_url ?>assets/img/shell-logo.png" class="w-40 mb-6">

                <h1 class="text-4xl font-bold text-shellRed text-center">

                    SPK PENENTUAN LOKASI SPBU

                </h1>

                <p class="text-center text-gray-700 mt-4">

                    Metode TOPSIS

                </p>

            </div>

            <!-- KANAN -->

            <div class="p-10">

                <div class="text-center mb-8">

                    <div class="w-20 h-20 rounded-full bg-shellRed mx-auto flex items-center justify-center">

                        <i class="fas fa-user text-white text-3xl"></i>

                    </div>

                    <h2 class="text-3xl font-bold mt-4 text-shellRed">

                        Login Administrator

                    </h2>

                    <p class="text-gray-500">

                        Silakan masuk ke sistem

                    </p>

                </div>

                <form action="proses_login.php" method="POST">

                    <div class="mb-5">

                        <label class="block mb-2 font-medium">

                            Username

                        </label>

                        <div class="relative">

                            <span class="absolute left-3 top-3 text-gray-400">

                                <i class="fas fa-user"></i>

                            </span>

                            <input type="text" name="username" required
                                class="w-full border rounded-xl py-3 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                        </div>

                    </div>

                    <div class="mb-6">

                        <label class="block mb-2 font-medium">

                            Password

                        </label>

                        <div class="relative">

                            <span class="absolute left-3 top-3 text-gray-400">

                                <i class="fas fa-lock"></i>

                            </span>

                            <input type="password" name="password" required
                                class="w-full border rounded-xl py-3 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">

                        </div>

                    </div>

                    <button type="submit"
                        class="w-full bg-shellRed hover:bg-red-700 text-white py-3 rounded-xl font-semibold transition">

                        LOGIN

                    </button>

                </form>

                <div class="text-center mt-8 text-sm text-gray-500">

                    © 2026 SPK Penentuan Lokasi SPBU

                </div>

            </div>

        </div>

    </div>

</body>

</html>