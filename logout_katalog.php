<?php
session_start();

// Menghapus semua session (baik sesi admin maupun user)
session_destroy();

// Mengarahkan kembali ke halaman login utama
header('location:login.php');
exit();
?>