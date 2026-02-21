<?php
require 'function.php';
require 'cek.php';

// --- LOGIKA BATCH INSERT (MULTI SELECT) ---
if(isset($_POST['masukkanbarang_batch'])){
    $kategori_tujuan = $_POST['kategori_tujuan'];
    
    if(isset($_POST['pilihbarang'])){
        $barang_dipilih = $_POST['pilihbarang']; 
        
        $berhasil = 0;
        foreach($barang_dipilih as $idb){
            $update = mysqli_query($conn, "UPDATE stock SET kategori='$kategori_tujuan' WHERE idbarang='$idb'");
            if($update){
                $berhasil++;
            }
        }
        
        echo '
        <script>
            alert("Berhasil memasukkan '.$berhasil.' barang ke kategori '.$kategori_tujuan.'");
            window.location.href="kategori.php";
        </script>
        ';
    } else {
        echo '
        <script>
            alert("Tidak ada barang baru yang dipilih.");
            window.location.href="kategori.php";
        </script>
        ';
    }
}

// Logika Tambah Kategori
if(isset($_POST['addnewkategori'])){
    $namakategori = $_POST['namakategori'];
    $addtotable = mysqli_query($conn,"insert into kategori (namakategori) values('$namakategori')");
    if($addtotable){
        header('location:kategori.php');
    } else {
        echo 'Gagal';
        header('location:kategori.php');
    }
}

// Logika Update Kategori
if(isset($_POST['updatekategori'])){
    $idk = $_POST['idk'];
    $namakategori = $_POST['namakategori'];
    $update = mysqli_query($conn,"update kategori set namakategori='$namakategori' where idkategori='$idk'");
    if($update){
        header('location:kategori.php');
    } else {
        echo 'Gagal';
        header('location:kategori.php');
    }
}

// Logika Hapus Kategori
if(isset($_POST['hapuskategori'])){
    $idk = $_POST['idk'];
    $hapus = mysqli_query($conn,"delete from kategori where idkategori='$idk'");
    if($hapus){
        header('location:kategori.php');
    } else {
        echo 'Gagal';
        header('location:kategori.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Kelola Kategori - Stock Barang</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        
        <style>
            .clickable-text {
                color: #fcebeb;
                font-weight: bold;
                text-decoration: none;
                cursor: pointer;
            }
            .clickable-text:hover {
                text-decoration: underline;
                color: #0056b3;
            }

            /* BOX SCROLL DAFTAR BARANG */
            .checkbox-list {
                max-height: 300px;
                overflow-y: auto;
                border: 1px solid #ced4da;
                padding: 0;
                border-radius: 5px;
                background-color: #fff;
            }
            
            /* ITEM BARANG */
            .checkbox-item {
                display: block;
                padding: 8px 10px;
                border-bottom: 1px solid #f0f0f0;
                cursor: pointer;
                margin-bottom: 0;
                transition: 0.2s;
            }
            
            .checkbox-item:last-child {
                border-bottom: none;
            }

            .checkbox-item:hover {
                background-color: #f8f9fa;
            }

            /* ITEM DISABLED (Sudah punya kategori) */
            .checkbox-item.disabled-item {
                background-color: #fcebeb; /* Merah muda sangat pudar */
                cursor: not-allowed;
                opacity: 0.8;
            }
            
            .checkbox-item.here-item {
                background-color: #e2e6ea; /* Abu-abu */
                cursor: not-allowed;
            }

            .checkbox-item input[type="checkbox"] {
                margin-right: 8px;
                transform: scale(1.2);
            }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">Lab UMRAH</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box-open"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-shipping-fast"></i></div>
                                Barang Keluar
                            </a>
                             <a class="nav-link active" href="kategori.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                                Kelola Kategori
                            </a>
			    <a class="nav-link" href="maintenance.php">
   				<div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div> Maintenance
			    </a>
                            <a class="nav-link" href="peminjaman.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                                Pemakaian/Peminjaman Barang
                            </a>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                                Kelola Admin
                            </a>				
<a class="nav-link" href="halaman_sertifikat.php">
  				<div class="sb-nav-link-icon"><i class="fas fa-certificate"></i></div> Cetak Sertifikat
</a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div> Logout
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Kelola Kategori Barang</h1>

                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Kategori Baru
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th>Nama Kategori (Klik untuk buka)</th>
                                                <th>Jumlah Barang</th>
                                                <th width="20%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambilsemuadatakategori = mysqli_query($conn,"select * from kategori");
                                            $i = 1;
                                            while($data=mysqli_fetch_array($ambilsemuadatakategori)){
                                                $namakategori = $data['namakategori'];
                                                $idk = $data['idkategori'];
                                                
                                                // Hitung jumlah barang
                                                $hitungdata = mysqli_query($conn, "select count(*) as jumlah from stock where kategori='$namakategori'");
                                                $jumlahdata = mysqli_fetch_array($hitungdata);
                                                $jumlah = $jumlahdata['jumlah'];
                                            ?>
                                            
                                            <tr>
                                                <td><?=$i++;?></td>
                                                <td>
                                                    <a href="#detail<?=$idk;?>" data-toggle="collapse" aria-expanded="false" class="clickable-text">
                                                        <i class="fas fa-folder"></i> <?=$namakategori;?> <i class="fas fa-caret-down ml-1"></i>
                                                    </a>
                                                </td>
                                                <td><?=$jumlah;?> Item</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?=$idk;?>">Edit</button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?=$idk;?>">Delete</button>
                                                </td>
                                            </tr>

                                            <tr id="detail<?=$idk;?>" class="collapse">
                                                <td colspan="4" class="bg-light p-3">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            <div class="card shadow-sm mb-3">
                                                                <div class="card-header bg-white">
                                                                    <strong class="text-light">Jenis Kategori <?=$namakategori;?>:</strong>
                                                                </div>
                                                                <div class="card-body p-0">
                                                                    <table class="table table-sm table-striped mb-0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Nama Barang</th>
                                                                                <th>Stok</th>
                                                                                <th>Deskripsi</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                        $ambilbarang = mysqli_query($conn, "select * from stock where kategori='$namakategori'");
                                                                        if(mysqli_num_rows($ambilbarang) == 0){
                                                                            echo "<tr><td colspan='3' class='text-center text-muted'><i>Belum ada barang.</i></td></tr>";
                                                                        } else {
                                                                            while($b=mysqli_fetch_array($ambilbarang)){
                                                                                echo "<tr>";
                                                                                echo "<td class='text-light'>".$b['namabarang']."</td>";
                                                                                echo "<td class='text-light'>".$b['stock']."</td>";
                                                                                echo "<td class='text-light'>".$b['deskripsi']."</td>";
                                                                                echo "</tr>";
                                                                            }
                                                                        }
                                                                        ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <div class="card shadow-sm border-primary">
                                                                <div class="card-header bg-primary text-white">
                                                                    <strong><i class="fas fa-plus-circle"></i> Tambah Barang</strong>
                                                                </div>
                                                                <div class="card-body">
                                                                    <form method="post">
                                                                        <input type="text" id="searchInput<?=$idk;?>" onkeyup="filterBarang(<?=$idk;?>)" class="form-control mb-2 form-control-sm" placeholder="Cari barang...">

                                                                        <div class="checkbox-list mb-2" id="listBarang<?=$idk;?>">
                                                                            <?php
                                                                            $ambilsemuabarang = mysqli_query($conn, "select * from stock order by namabarang ASC");
                                                                            while($brg = mysqli_fetch_array($ambilsemuabarang)){
                                                                                $idb_list = $brg['idbarang'];
                                                                                $nama_list = $brg['namabarang'];
                                                                                $kat_saat_ini = $brg['kategori'];
                                                                                
                                                                                // --- LOGIKA UTAMA ---
                                                                                
                                                                                // KONDISI 1: SUDAH ADA DI KATEGORI YANG SEDANG DIBUKA INI
                                                                                if($kat_saat_ini == $namakategori){
                                                                                    $inputAttr = "checked disabled";
                                                                                    $classItem = "checkbox-item here-item"; // Class khusus abu-abu
                                                                                    $textStyle = "color: #555 !important; font-style: italic;";
                                                                                    $labelBadge = " <small>(Sudah di sini)</small>";
                                                                                
                                                                                // KONDISI 2: SUDAH ADA DI KATEGORI LAIN (TIDAK BOLEH DIAMBIL)
                                                                                } else if($kat_saat_ini != "" && $kat_saat_ini != null){
                                                                                    $inputAttr = "disabled"; // KUNCI! Tidak bisa dicentang
                                                                                    $classItem = "checkbox-item disabled-item"; // Class khusus merah pudar
                                                                                    $textStyle = "color: #d9534f !important; font-size: 0.9em;"; // Warna Merah
                                                                                    $labelBadge = " <small>(Ada di: $kat_saat_ini)</small>";
                                                                                
                                                                                // KONDISI 3: BELUM ADA KATEGORI (AVAILABLE)
                                                                                } else {
                                                                                    $inputAttr = ""; // Bisa dicentang
                                                                                    $classItem = "checkbox-item";
                                                                                    $textStyle = "color: #000000 !important; font-weight: bold;";
                                                                                    $labelBadge = "";
                                                                                }
                                                                            ?>
                                                                            <label class="<?=$classItem;?>">
                                                                                <input type="checkbox" name="pilihbarang[]" value="<?=$idb_list;?>" <?=$inputAttr;?>> 
                                                                                <span style="<?=$textStyle;?>"><?=$nama_list;?><?=$labelBadge;?></span>
                                                                            </label>
                                                                            <?php } ?>
                                                                        </div>
                                                                        
                                                                        <input type="hidden" name="kategori_tujuan" value="<?=$namakategori;?>">
                                                                        <button type="submit" name="masukkanbarang_batch" class="btn btn-primary btn-sm btn-block">Simpan Pilihan</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="edit<?=$idk;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Edit Kategori</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post">
                                                    <div class="modal-body">
                                                        <input type="text" name="namakategori" value="<?=$namakategori;?>" class="form-control" required>
                                                        <input type="hidden" name="idk" value="<?=$idk;?>">
                                                        <br>
                                                        <button type="submit" class="btn btn-warning" name="updatekategori">Simpan</button>
                                                    </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="delete<?=$idk;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Kategori?</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form method="post">
                                                    <div class="modal-body">
                                                        Yakin hapus kategori <b><?=$namakategori;?></b>?
                                                        <input type="hidden" name="idk" value="<?=$idk;?>">
                                                        <br><br>
                                                        <button type="submit" class="btn btn-danger" name="hapuskategori">Hapus</button>
                                                    </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>

                                            <?php
                                            };
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        
        <script>
        function filterBarang(id) {
            var input = document.getElementById("searchInput" + id);
            var filter = input.value.toUpperCase();
            var div = document.getElementById("listBarang" + id);
            var labels = div.getElementsByTagName("label");

            for (var i = 0; i < labels.length; i++) {
                var txtValue = labels[i].textContent || labels[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    labels[i].style.display = "";
                } else {
                    labels[i].style.display = "none";
                }
            }
        }
        </script>
    </body>
    
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Tambah Kategori Baru</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post">
            <div class="modal-body">
                <input type="text" name="namakategori" placeholder="Nama Kategori" class="form-control" required>
                <br>
                <button type="submit" class="btn btn-primary" name="addnewkategori">Submit</button>
            </div>
            </form>
        </div>
        </div>
    </div>
</html>