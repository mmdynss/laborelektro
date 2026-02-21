<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Stock Barang - Lab Elektro</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .zoomable{ width: 100px; }
            .zoomable:hover{ transform: scale(2.5); transition: 0.3s ease; }
            a{ text-decoration:none; color:black; }
            
            /* Warna khusus untuk notifikasi maintenance */
            .bg-danger-soft { background-color: #ffe6e6 !important; }
            .text-danger-bold { color: #dc3545; font-weight: bold; }
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.php">Laboratorium Elektro</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link active" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div> Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box-open"></i></div> Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-shipping-fast"></i></div> Barang Keluar
                            </a>
                            <a class="nav-link" href="kategori.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div> Kelola Kategori
                            </a>
                            <a class="nav-link" href="maintenance.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div> Maintenance
                            </a>
                            <a class="nav-link" href="peminjaman.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div> Pemakaian/Peminjaman Barang
                            </a>
                            <a class="nav-link" href="Admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div> Kelola Admin
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
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Stock Barang</h1>
                        
                        <?php
                            $tgl_hari_ini = date('Y-m-d');
                            $cek_telat = mysqli_query($conn, "SELECT * FROM stock WHERE tgl_jadwal <= '$tgl_hari_ini' AND tgl_jadwal != '0000-00-00'");
                            $jumlah_telat = mysqli_num_rows($cek_telat);
                            if($jumlah_telat > 0){
                        ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Perhatian!</strong> Ada <?=$jumlah_telat;?> barang yang <strong>Jadwal Maintenancenya SUDAH TIBA/LEWAT!</strong> Cek tabel di bawah.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>

                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                    Tambah Barang
                                </button>
                                <a href="export_excel.php" class="btn btn-success ms-1">Export Excel</a>
                                <a href="export_pdf.php" class="btn btn-danger ms-1">Export PDF</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Gambar</th>
                                                <th>Nama Barang</th>
                                                <th>Kategori</th>
                                                <th>Stock</th>
                                                <th>Kondisi</th>
                                                <th>Terakhir Servis</th> <th>Next Service</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambilsemuadatastock = mysqli_query($conn,"select * from stock");
                                            $i = 1;
                                            while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                                $namabarang = $data['namabarang'];
                                                $deskripsi = $data['deskripsi'];
                                                $stock = $data['stock'];
                                                $idb = $data['idbarang'];
                                                $kategori = $data['kategori'];

                                                // Gambar
                                                $image = $data['image'];
                                                if($image==null){
                                                    $img = 'No Photo';
                                                } else {
                                                    $img = '<img src="images/'.$image.'" class="zoomable">';
                                                }

                                                // Maintenance Info
                                                $kondisi = $data['kondisi'];
                                                $tgl_maintenance = $data['tgl_maintenance'];
                                                $tgl_jadwal = $data['tgl_jadwal'];
                                                $deskripsi_maintenance = $data['deskripsi_maintenance'];

                                                // Format Tanggal Terakhir Servis
                                                $show_last_main = "-";
                                                if($tgl_maintenance != NULL && $tgl_maintenance != '0000-00-00'){
                                                    $show_last_main = date('d M Y', strtotime($tgl_maintenance));
                                                }

                                                // Logika Warna Baris (Jika Jadwal Lewat)
                                                $row_class = "";
                                                $text_jadwal_class = "";
                                                $tgl_show_next = "-";

                                                if($tgl_jadwal != NULL && $tgl_jadwal != '0000-00-00'){
                                                    $tgl_show_next = date('d M Y', strtotime($tgl_jadwal));
                                                    if($tgl_jadwal <= $tgl_hari_ini){
                                                        $row_class = "bg-danger-soft"; // Merah muda
                                                        $text_jadwal_class = "text-danger-bold";
                                                        $tgl_show_next .= " (SEGERA!)";
                                                    }
                                                }

                                                // Badge Kondisi
                                                if($kondisi == 'Baik'){
                                                    $badge = 'badge bg-success';
                                                } elseif($kondisi == 'Rusak'){
                                                    $badge = 'badge bg-danger';
                                                } else {
                                                    $badge = 'badge bg-warning text-dark';
                                                }

                                            ?>
                                            <tr class="<?=$row_class;?>">
                                                <td><?=$i++;?></td>
                                                <td><?=$img;?></td>
                                                <td>
                                                    <a href="detail.php?id=<?=$idb;?>" class="badge text-white" style="text-decoration:none; font-size: 14px;">
                                                        <?=$namabarang;?>
                                                    </a>
                                                </td>
                                                <td><?=$kategori;?></td>
                                                <td><?=$stock;?></td>
                                                <td><span class="<?=$badge;?>"><?=$kondisi;?></span></td>
                                                <td><?=$show_last_main;?></td> <td class="<?=$text_jadwal_class;?>"><?=$tgl_show_next;?></td>
                                                <td><?=$deskripsi;?></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?=$idb;?>">
                                                        Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete<?=$idb;?>">
                                                        Del
                                                    </button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="edit<?=$idb;?>">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Barang: <?=$namabarang;?></h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        
                                                        <div class="modal-body">
                                                            <form method="post" class="mb-4">
                                                                <h6 class="text-primary"><i class="fas fa-edit"></i> Edit Info Dasar</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Nama Barang</label>
                                                                        <input type="text" name="namabarang" value="<?=$namabarang;?>" class="form-control mb-2" required>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Kategori</label>
                                                                        <select name="kategori" class="form-control mb-2">
                                                                            <option value="<?=$kategori;?>"><?=$kategori;?> (Saat ini)</option>
                                                                            <?php
                                                                            $det=mysqli_query($conn,"select * from kategori");
                                                                            while($d=mysqli_fetch_array($det)){
                                                                            ?>
                                                                            <option value="<?=$d['namakategori'];?>"><?=$d['namakategori'];?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Deskripsi</label>
                                                                        <input type="text" name="deskripsi" value="<?=$deskripsi;?>" class="form-control mb-2" required>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Total Stock (Manual)</label>
                                                                        <input type="number" name="stock" value="<?=$stock;?>" class="form-control mb-2" required>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="idb" value="<?=$idb;?>">
                                                                <button type="submit" class="btn btn-primary btn-sm" name="updatebarang">Simpan Info Dasar</button>
                                                            </form>

                                                            <hr class="border-secondary">

                                                            <form method="post">
                                                                <h6 class="text-danger"><i class="fas fa-tools"></i> Update Kondisi / Maintenance (Pecah Stok)</h6>
                                                                <p class="small text-muted mb-2">Gunakan formulir ini jika ada barang yang rusak atau perlu maintenance. Jika jumlah yang diubah < stock total, stok akan dipecah otomatis.</p>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Kondisi Baru:</label>
                                                                        <select name="kondisi" class="form-control mb-2">
                                                                            <option value="Baik" <?=$kondisi=='Baik'?'selected':'';?>>Baik</option>
                                                                            <option value="Perlu Maintenance" <?=$kondisi=='Perlu Maintenance'?'selected':'';?>>Perlu Maintenance</option>
                                                                            <option value="Rusak" <?=$kondisi=='Rusak'?'selected':'';?>>Rusak</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Jumlah Barang yang Berubah Kondisi:</label>
                                                                        <input type="number" name="qty_ubah" class="form-control mb-2" min="1" max="<?=$stock;?>" value="<?=$stock;?>" required>
                                                                        <small class="text-muted">Max: <?=$stock;?></small>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Tanggal Maintenance/Cek:</label>
                                                                        <input type="date" name="tgl_maintenance" class="form-control mb-2" value="<?=$tgl_maintenance;?>">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1">Jadwal Servis Berikutnya:</label>
                                                                        <input type="date" name="tgl_jadwal" class="form-control mb-2" value="<?=$tgl_jadwal;?>">
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="small mb-1">Keterangan Maintenance (Opsional):</label>
                                                                        <textarea name="deskripsi_maintenance" class="form-control mb-2" rows="2"><?=$deskripsi_maintenance;?></textarea>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="idb" value="<?=$idb;?>">
                                                                <input type="hidden" name="namabarang_lama" value="<?=$namabarang;?>">
                                                                <input type="hidden" name="stock_lama" value="<?=$stock;?>">

                                                                <button type="submit" class="btn btn-danger btn-sm" name="update_kondisi_barang">Proses Update Kondisi</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="delete<?=$idb;?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Hapus Barang?</h4>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus <?=$namabarang;?>?
                                                                <input type="hidden" name="idb" value="<?=$idb;?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-danger" name="hapusbarang">Hapus</button>
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
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Laboratorium Elektro</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Barang Baru</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control mb-2" required>
                            <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control mb-2" required>
                            
                            <select name="kategori" class="form-control mb-2">
                                <?php
                                $ambilsemuadatakategori = mysqli_query($conn,"select * from kategori");
                                while($fetcharray = mysqli_fetch_array($ambilsemuadatakategori)){
                                    $namakategori = $fetcharray['namakategori'];
                                ?>
                                <option value="<?=$namakategori;?>"><?=$namakategori;?></option>
                                <?php
                                }
                                ?>
                            </select>

                            <input type="number" name="stock" placeholder="Stock Awal" class="form-control mb-2" required>
                            <input type="file" name="file" class="form-control mb-2">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>