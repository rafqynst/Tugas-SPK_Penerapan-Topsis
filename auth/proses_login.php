<?php

session_start();

include '../config/koneksi.php';
/** @var mysqli $conn */
$username = $_POST['username'];
$password = MD5($_POST['password']);

$query = mysqli_query(
$conn,
"SELECT * FROM users
WHERE username='$username'
AND password='$password'"
);

if(mysqli_num_rows($query)>0)
{
    $user = mysqli_fetch_assoc($query);

    $_SESSION['login'] = true;
    $_SESSION['nama'] = $user['nama_lengkap'];

    header("Location: ../dashboard.php");
}
else
{
    echo "
    <script>
    alert('Username atau Password Salah');
    window.location='login.php';
    </script>
    ";
}