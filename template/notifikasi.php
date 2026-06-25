<?php
// Pastikan session sudah berjalan, jika belum silakan jalankan session_start() di header atau config
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['sukses'])) { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $_SESSION['sukses']; ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
<?php 
    unset($_SESSION['sukses']); // Hapus session agar tidak muncul lagi saat di-refresh
} 

if (isset($_SESSION['gagal'])) { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $_SESSION['gagal']; ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
<?php 
    unset($_SESSION['gagal']);
} ?>