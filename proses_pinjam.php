<?php
// 1. Panggil koneksi database
require 'function.php';

// 2. Pastikan session aktif (jika belum ada di function.php)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 3. Cek apakah tombol ditekan
if(isset($_POST['ajukan_pinjam'])){
    
    // Ambil data dari form
    $idbarang = $_POST['idbarang'];
    $qty = $_POST['qty'];
    
    // Lebih aman ambil nama dari session daripada dari input form
    $peminjam = $_SESSION['user_nama']; 
    
    // Status default
    $status = 'Menunggu Persetujuan';
    $tanggal = date('Y-m-d H:i:s');

    // 4. Validasi Stok (Mencegah user nakal mengubah HTML inspect element)
    $cek_stok = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang = '$idbarang'");
    $ambildata = mysqli_fetch_array($cek_stok);
    $stok_saat_ini = $ambildata['stock'];

    if($qty > $stok_saat_ini){
        echo "<script>alert('Permintaan gagal! Stok tidak mencukupi.'); window.location.href='index.php';</script>";
        exit();
    }

    // 5. Masukkan ke database tabel peminjaman
    // Asumsi nama tabelnya 'peminjaman' dan kolomnya sesuai
    $insert = mysqli_query($conn, "INSERT INTO peminjaman (idbarang, qty, peminjam, status, tgl_pinjam) VALUES ('$idbarang', '$qty', '$peminjam', '$status', '$tanggal')");

    if($insert){
        // Berhasil
        echo "
        <script>
            alert('Berhasil mengajukan peminjaman! Menunggu persetujuan Admin.');
            window.location.href='index.php'; // Ganti dengan nama file katalogmu jika beda
        </script>
        ";
    } else {
        // Gagal
        echo "
        <script>
            alert('Gagal mengajukan peminjaman.');
            window.location.href='index.php';
        </script>
        ";
    }
} else {
    // Jika user mencoba akses file ini langsung tanpa lewat form
    header('location:index.php');
}
?>