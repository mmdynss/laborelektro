<?php
// Cek status dulu: Jika belum ada session aktif, baru mulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Koneksi Database
$conn = mysqli_connect("localhost","root","","stock_barang");

// Cek Koneksi
if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// ======================================================================
// FIX ZONA WAKTU (WIB)
// ======================================================================
date_default_timezone_set('Asia/Jakarta'); 
mysqli_query($conn, "SET time_zone = '+07:00'");

// ======================================================================
// BAGIAN 1: SISTEM LOGIN & REGISTER (ONE GATE SYSTEM)
// ======================================================================

// LOGIKA LOGIN
if(isset($_POST['login_system'])){
    $input_user = $_POST['username']; 
    $password = $_POST['password'];

    // --- CEK 1: ADMIN (Tabel 'login') ---
    // Pastikan password di database admin sudah di-hash SHA256 atau sesuaikan
    $hash_password = hash("sha256", $password); 
    $query_admin = "SELECT * FROM login WHERE username = '$input_user' AND password = '$hash_password'";
    $cek_admin = mysqli_query($conn, $query_admin);
    
    if(mysqli_num_rows($cek_admin) > 0){
        $_SESSION['log'] = 'True';
        $_SESSION['role'] = 'admin';
        $_SESSION['email'] = $input_user;
        header('location:index.php');
        exit();
    }

    // --- CEK 2: MAHASISWA/DOSEN (Tabel 'users') ---
    $query_user = "SELECT * FROM users WHERE nomor_induk = '$input_user' AND password = '$password'";
    $cek_user = mysqli_query($conn, $query_user);
    
    if(mysqli_num_rows($cek_user) > 0){
        $data = mysqli_fetch_array($cek_user);
        $_SESSION['log_user'] = 'True';
        $_SESSION['role'] = 'user';
        
        // --- UPDATE: MENYIMPAN DATA LENGKAP KE SESSION ---
        $_SESSION['user_nama']   = $data['nama_lengkap'];
        $_SESSION['user_induk']  = $data['nomor_induk']; // Sesi bawaan Anda
        $_SESSION['user_nim']    = $data['nomor_induk']; // Sesi tambahan untuk peminjaman
        $_SESSION['user_hp']     = $data['no_hp'];
        $_SESSION['user_prodi']  = $data['prodi'];
        $_SESSION['user_status'] = $data['status_user'];
        
        header('location:katalog.php'); 
        exit();
    }

    echo '<script>alert("Username/NIM atau Password Salah!"); window.location.href="login.php";</script>';
}

// LOGIKA REGISTER
if(isset($_POST['register_user'])){
    // --- UPDATE: MENGAMBIL DATA LENGKAP DARI FORM REGISTRASI ---
    $nama     = $_POST['nama_lengkap'];
    $induk    = $_POST['nomor_induk'];
    $nohp     = $_POST['no_hp'];
    $prodi    = $_POST['prodi'];
    $status   = $_POST['status_user'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn, "select * from users where nomor_induk='$induk'");
    if(mysqli_num_rows($cek) > 0){
        echo '<script>alert("NIM/NIP Sudah Terdaftar!"); window.location.href="login.php";</script>';
    } else {
        // --- UPDATE: INSERT DATA LENGKAP KE TABEL USERS ---
        $insert = mysqli_query($conn, "insert into users (nama_lengkap, no_hp, prodi, status_user, nomor_induk, password) values('$nama','$nohp','$prodi','$status','$induk','$password')");
        if($insert){
            echo '<script>alert("Berhasil Daftar! Silakan Login."); window.location.href="login.php";</script>';
        } else {
            echo '<script>alert("Gagal Daftar: ' . mysqli_error($conn) . '"); window.location.href="login.php";</script>';
        }
    }
}

// ======================================================================
// BAGIAN 2: STOCK BARANG
// ======================================================================

if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $kategori = $_POST['kategori']; 
    $tgl_sekarang = date('Y-m-d H:i:s'); 

    $allowed_extension = array('png','jpg','jpeg');
    $nama = $_FILES['file']['name']; 
    $dot = explode('.',$nama);
    $ekstensi = strtolower(end($dot)); 
    $ukuran = $_FILES['file']['size']; 
    $file_tmp = $_FILES['file']['tmp_name']; 

    // Enkripsi nama file agar unik
    $image = md5(uniqid($nama,true) . time()).'.'.$ekstensi;

    if($ukuran == 0){
        // Jika tidak upload gambar
        $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock, kategori, tanggal) values('$namabarang','$deskripsi','$stock','$kategori','$tgl_sekarang')");
        header('location:index.php');
    } else {
        // Jika upload gambar
        if(in_array($ekstensi, $allowed_extension)){
            move_uploaded_file($file_tmp, 'images/'.$image);
            $addtotable = mysqli_query($conn,"insert into stock (namabarang, deskripsi, stock, kategori, image, tanggal) values('$namabarang','$deskripsi','$stock','$kategori','$image','$tgl_sekarang')");
            header('location:index.php');
        } else {
            echo '<script>alert("Format file tidak didukung"); window.location.href="index.php";</script>';
        }
    }
}

// ======================================================================
// BAGIAN 3: PERBAIKAN SISTEM PEMINJAMAN (LOGIKA STOK DIPERBAIKI)
// ======================================================================

// 1. USER: Mengajukan Pinjaman (HANYA INSERT, JANGAN KURANGI STOK DULU)
if(isset($_POST['addpinjam'])){
    $idbarang = $_POST['idbarang'];
    $qty = $_POST['qty']; 
    
    // Identitas diambil secara otomatis (hidden input dari katalog.php)
    $penerima = $_POST['penerima']; 
    $status_peminjam = $_POST['status_peminjam']; 
    $nidn_nim = $_POST['nidn_nim']; 
    $prodi = $_POST['prodi']; 
    $no_hp = $_POST['no_hp']; 
    $alasan = $_POST['alasan']; 

    // Cek Stok (Hanya validasi, tidak update)
    $cekstok = mysqli_query($conn, "select stock from stock where idbarang='$idbarang'");
    $ambildatanya = mysqli_fetch_array($cekstok);
    $stoksekarang = $ambildatanya['stock'];

    if($stoksekarang >= $qty){
        // Cukup Insert ke Peminjaman dengan status Pending
        $insert = mysqli_query($conn, "INSERT INTO peminjaman (idbarang, qty, peminjam, status, status_peminjam, nidn_nim, prodi, no_hp, alasan) VALUES ('$idbarang', '$qty', '$penerima', 'Pending', '$status_peminjam', '$nidn_nim', '$prodi', '$no_hp', '$alasan')");
        
        if($insert){
            echo "<script>alert('Berhasil mengajukan! Stok akan berkurang setelah disetujui Admin.'); window.location.href='katalog.php';</script>";
        } else {
            echo "<script>alert('Gagal mengirim pengajuan.'); window.location.href='katalog.php';</script>";
        }
    } else {
        echo "<script>alert('Stok barang saat ini tidak mencukupi untuk request Anda!'); window.location.href='katalog.php';</script>";
    }
}

// 2. ADMIN: Setujui Pinjaman (BARU KURANGI STOK DISINI)
if(isset($_POST['setuju_pinjam'])){
    $idp = $_POST['idpeminjaman'];
    $idb = $_POST['idbarang'];
    $qty = $_POST['qty']; // Qty yang diminta

    // Cek Stok Aktual
    $cekstok = mysqli_query($conn, "SELECT stock FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($cekstok);
    $stok_sekarang = $data['stock'];

    if($stok_sekarang >= $qty){
        // 1. Kurangi Stok
        $stok_baru = $stok_sekarang - $qty;
        $potong_stok = mysqli_query($conn, "UPDATE stock SET stock='$stok_baru' WHERE idbarang='$idb'");
        
        // 2. Ubah Status
        $update_status = mysqli_query($conn, "UPDATE peminjaman SET status='Dipinjam' WHERE idpeminjaman='$idp'");
        
        if($update_status && $potong_stok){
            echo "<script>alert('Peminjaman Disetujui & Stok Berkurang'); window.location.href='peminjaman.php';</script>";
        }
    } else {
        echo "<script>alert('GAGAL! Stok fisik sudah tidak cukup.'); window.location.href='peminjaman.php';</script>";
    }
}

// 3. ADMIN: Tolak Pinjaman
if(isset($_POST['tolak_pinjam'])){
    $idp = $_POST['idpeminjaman'];
    $alasan_admin = $_POST['alasan_tolak']; 

    // Cukup update status saja
    $tolak = mysqli_query($conn, "UPDATE peminjaman SET status = 'Ditolak', ket_tolak = '$alasan_admin' WHERE idpeminjaman='$idp'");
    
    if($tolak){
        echo "<script>alert('Permintaan Ditolak.'); window.location.href='peminjaman.php';</script>";
    }
}

// 4. ADMIN: Tambah Manual
if(isset($_POST['pinjam'])){
    $idbarang = $_POST['barangnya'];
    $qty = $_POST['qty'];
    $penerima = $_POST['penerima'];
    $status_peminjam = $_POST['status_peminjam'];
    $nidn_nim = $_POST['nidn_nim'];
    $prodi = $_POST['prodi'];

    $cekstok = mysqli_query($conn, "select stock from stock where idbarang='$idbarang'");
    $ambildatanya = mysqli_fetch_array($cekstok);
    $stoksekarang = $ambildatanya['stock'];
    
    if($stoksekarang >= $qty){
        $tambahkanstocksekarang = $stoksekarang - $qty;

        $addtotable = mysqli_query($conn, "INSERT INTO peminjaman (idbarang, qty, peminjam, status, status_peminjam, nidn_nim, prodi) VALUES ('$idbarang', '$qty', '$penerima', 'Dipinjam', '$status_peminjam', '$nidn_nim', '$prodi')");
        $updatestokmasuk = mysqli_query($conn, "update stock set stock='$tambahkanstocksekarang' where idbarang='$idbarang'");
        
        if($addtotable && $updatestokmasuk){
            header('location:peminjaman.php');
        }
    } else {
        echo "<script>alert('Stok Kurang'); window.location.href='peminjaman.php';</script>";
    }
}

// 5. ADMIN: Barang Kembali
if(isset($_POST['barangkembali'])){
    $idpinjam = $_POST['idpinjam'];
    $idbarang = $_POST['idbarang'];

    $cek_status = mysqli_query($conn, "SELECT status, qty FROM peminjaman WHERE idpeminjaman='$idpinjam'");
    $data_pinjam = mysqli_fetch_array($cek_status);
    
    if($data_pinjam['status'] == 'Dipinjam'){
        $qty_kembali = $data_pinjam['qty'];

        $stok_saat_ini = mysqli_query($conn, "select stock from stock where idbarang='$idbarang'");
        $stok_nya = mysqli_fetch_array($stok_saat_ini);
        $new_stock = $stok_nya['stock'] + $qty_kembali;

        $kembalikan_stock = mysqli_query($conn, "update stock set stock='$new_stock' where idbarang='$idbarang'");
        $update_status = mysqli_query($conn, "update peminjaman set status='Kembali' where idpeminjaman='$idpinjam'");

        if($update_status && $kembalikan_stock){
            echo "<script>alert('Barang Diterima Kembali.'); window.location.href='peminjaman.php';</script>";
        }
    } else {
         echo "<script>alert('Error: Barang ini sudah dikembalikan sebelumnya.'); window.location.href='peminjaman.php';</script>";
    }
}

// ======================================================================
// BAGIAN LAIN (UPDATE, DELETE, MASUK, KELUAR)
// ======================================================================

if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];
    $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi', stock='$stock' where idbarang='$idb'");
    header('location:index.php');
}

if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb']; 
    mysqli_query($conn, "delete from stock where idbarang = '$idb'");
    header('location:index.php');
}

if(isset($_POST['barangmasuk'])){
    $idb = $_POST['barangnya'];
    $qty = $_POST['qty'];
    $ket = $_POST['keterangan'];
    $tgl = date('Y-m-d H:i:s');
    
    $getstok = mysqli_query($conn, "select stock from stock where idbarang='$idb'");
    $data = mysqli_fetch_array($getstok);
    $newstok = $data['stock'] + $qty;
    
    mysqli_query($conn, "insert into masuk (idbarang, qty, keterangan, tanggal) values ('$idb','$qty','$ket','$tgl')");
    mysqli_query($conn, "update stock set stock='$newstok' where idbarang='$idb'");
    header('location:masuk.php');
}

if(isset($_POST['addnewkategori'])){
    $namakategori = $_POST['namakategori'];
    mysqli_query($conn, "insert into kategori (namakategori) values ('$namakategori')");
    header('location:kategori.php');
}

// ======================================================================
// FITUR UPDATE MAINTENANCE
// ======================================================================

if(isset($_POST['update_kondisi_barang'])){
    $idb = $_POST['idb'];
    $namabarang_lama = $_POST['namabarang_lama'];
    $stock_lama = $_POST['stock_lama']; // Stok total saat ini
    
    $kondisi_baru = $_POST['kondisi'];
    $qty_ubah = $_POST['qty_ubah']; // Jumlah yang kondisinya berubah
    
    $deskripsi = $_POST['deskripsi_maintenance'];
    $tgl_m = $_POST['tgl_maintenance'];
    $tgl_j = $_POST['tgl_jadwal'];

    // SKENARIO 1: JUMLAH SAMA (Update Biasa)
    if($qty_ubah == $stock_lama){
        $update = mysqli_query($conn, "UPDATE stock SET kondisi='$kondisi_baru', deskripsi_maintenance='$deskripsi', tgl_maintenance='$tgl_m', tgl_jadwal='$tgl_j' WHERE idbarang='$idb'");
        
        if($update){
            header('location:maintenance.php');
        } else {
            echo "<script>alert('Gagal Update Database'); window.location.href='maintenance.php';</script>";
        }
    } 
    // SKENARIO 2: JUMLAH LEBIH KECIL (Pecah Barang / Split)
    else {
        $sisa_stok = $stock_lama - $qty_ubah;
        $kurangi_stok = mysqli_query($conn, "UPDATE stock SET stock='$sisa_stok' WHERE idbarang='$idb'");

        $nama_baru = $namabarang_lama . " (" . $kondisi_baru . ")";

        $cek_data_lama = mysqli_query($conn, "SELECT image, kategori FROM stock WHERE idbarang='$idb'");
        $data_lama = mysqli_fetch_array($cek_data_lama);
        $img_lama = $data_lama['image'];
        $kat_lama = $data_lama['kategori'];
        $tgl_sekarang = date('Y-m-d H:i:s');

        $tambah_baru = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock, kategori, image, kondisi, deskripsi_maintenance, tgl_maintenance, tgl_jadwal, tanggal) VALUES ('$nama_baru', '$namabarang_lama - Pecahan', '$qty_ubah', '$kat_lama', '$img_lama', '$kondisi_baru', '$deskripsi', '$tgl_m', '$tgl_j', '$tgl_sekarang')");

        if($kurangi_stok && $tambah_baru){
             echo "<script>alert('Berhasil! Barang telah dipecah stoknya.');window.location.href='maintenance.php';</script>";
        } else {
             echo "<script>alert('Gagal Memecah Barang');window.location.href='maintenance.php';</script>";
        }
    }
}
?>