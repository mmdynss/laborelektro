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
        <title>Maintenance - Lab Elektro</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .bg-danger-soft { background-color: #ffe6e6 !important; }
            .text-danger-bold { color: #dc3545; font-weight: bold; }
            .text-success-bold { color: #198754; font-weight: bold; }
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
                            <a class="nav-link" href="index.php">
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
                            
                            <a class="nav-link active" href="maintenance.php">
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
                        <h1 class="mt-4">Jadwal Maintenance</h1>
                        
                        <?php
                            $tgl_hari_ini = date('Y-m-d');
                            $cek_telat = mysqli_query($conn, "SELECT * FROM stock WHERE tgl_jadwal <= '$tgl_hari_ini' AND tgl_jadwal != '0000-00-00'");
                            $jumlah_telat = mysqli_num_rows($cek_telat);
                            if($jumlah_telat > 0){
                        ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Peringatan!</strong> Ada <?=$jumlah_telat;?> alat yang harus segera di-service! Lihat tabel berwarna merah.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i> Data Kondisi Barang
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Stok</th>
                                            <th>Deskripsi Masalah</th> 
                                            <th>Kondisi</th>
                                            <th>Terakhir Servis</th>
                                            <th>Jadwal Berikutnya</th>
                                            <th>Status Jadwal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM stock ORDER BY 
                                                CASE 
                                                    WHEN kondisi = 'Perlu Maintenance' THEN 1 
                                                    WHEN kondisi = 'Rusak' THEN 2 
                                                    ELSE 3 
                                                END ASC
                                            ");

                                            $i = 1;
                                            while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                                $idb = $data['idbarang'];
                                                $namabarang = $data['namabarang'];
                                                $stock = $data['stock']; // Ambil Stok
                                                $kondisi = $data['kondisi'];
                                                $tgl_maintenance = $data['tgl_maintenance'];
                                                $tgl_jadwal = $data['tgl_jadwal'];
                                                
                                                // AMBIL DATA DESKRIPSI (Jika kosong isi "-")
                                                $deskripsi = isset($data['deskripsi_maintenance']) ? $data['deskripsi_maintenance'] : ''; 

                                                // Format Tanggal
                                                $show_last = ($tgl_maintenance && $tgl_maintenance!='0000-00-00') ? date('d M Y', strtotime($tgl_maintenance)) : "-";
                                                $show_next = ($tgl_jadwal && $tgl_jadwal!='0000-00-00') ? date('d M Y', strtotime($tgl_jadwal)) : "-";

                                                // Logika Warna & Status Jadwal
                                                $row_class = "";
                                                $status_text = "Menunggu Jadwal";
                                                $status_color = "text-muted";

                                                if($tgl_jadwal != NULL && $tgl_jadwal != '0000-00-00'){
                                                    if($tgl_jadwal <= $tgl_hari_ini){
                                                        $row_class = "bg-danger-soft"; 
                                                        $status_text = "SUDAH WAKTUNYA!";
                                                        $status_color = "text-danger-bold";
                                                    } else {
                                                        $status_text = "Aman";
                                                        $status_color = "text-success-bold";
                                                    }
                                                }

                                                // Badge Kondisi
                                                if($kondisi == 'Baik'){ $badge = 'badge bg-success'; } 
                                                elseif($kondisi == 'Rusak'){ $badge = 'badge bg-danger'; } 
                                                else { $badge = 'badge bg-warning text-dark'; }
                                            ?>
                                            <tr class="<?=$row_class;?>">
                                                <td><?=$i++;?></td>
                                                <td><?=$namabarang;?></td>
                                                <td><?=$stock;?></td>
                                                <td><?=$deskripsi;?></td> 
                                                <td><span class="<?=$badge;?>"><?=$kondisi;?></span></td>
                                                <td><?=$show_last;?></td>
                                                <td><?=$show_next;?></td>
                                                <td class="<?=$status_color;?>"><?=$status_text;?></td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#edit<?=$idb;?>">
                                                        <i class="fas fa-edit"></i> Update
                                                    </button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="edit<?=$idb;?>">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title"><i class="fas fa-tools me-2"></i>Update Kondisi Barang</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <div class="alert alert-info py-2 mb-3">
                                                                    <small><i class="fas fa-info-circle"></i> Stok saat ini: <strong><?=$stock;?> unit</strong></small>
                                                                </div>

                                                                <input type="hidden" name="idb" value="<?=$idb;?>">
                                                                <input type="hidden" name="namabarang_lama" value="<?=$namabarang;?>">
                                                                <input type="hidden" name="stock_lama" value="<?=$stock;?>">

                                                                <div class="mb-3">
                                                                    <label class="fw-bold small mb-1">Kondisi Baru:</label>
                                                                    <select name="kondisi" class="form-select" required>
                                                                        <option value="Baik" <?=$kondisi=='Baik'?'selected':'';?>>Baik (Siap Pakai)</option>
                                                                        <option value="Perlu Maintenance" <?=$kondisi=='Perlu Maintenance'?'selected':'';?>>Perlu Maintenance</option>
                                                                        <option value="Rusak" <?=$kondisi=='Rusak'?'selected':'';?>>Rusak (Tidak Bisa Dipakai)</option>
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="fw-bold small mb-1">Jumlah Unit yang Mengalami Kondisi Ini:</label>
                                                                    <input type="number" name="qty_ubah" class="form-control" value="<?=$stock;?>" min="1" max="<?=$stock;?>" required>
                                                                    <div class="form-text text-muted small">
                                                                        * Jika hanya 1 yang rusak dari <?=$stock;?> stok, isi <strong>1</strong>. Sistem akan otomatis memisahkan barang tersebut.
                                                                    </div>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label class="fw-bold small mb-1">Catatan / Detail Masalah:</label>
                                                                    <textarea name="deskripsi_maintenance" class="form-control" rows="2" placeholder="Contoh: Unit dengan stiker merah, layar mati..."><?=$deskripsi == 'Tidak ada catatan' ? '' : strip_tags($deskripsi);?></textarea>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="fw-bold small mb-1">Tgl Servis:</label>
                                                                        <input type="date" name="tgl_maintenance" class="form-control" value="<?=$tgl_maintenance;?>">
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <label class="fw-bold small mb-1">Jadwal Berikutnya:</label>
                                                                        <input type="date" name="tgl_jadwal" class="form-control" value="<?=$tgl_jadwal;?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="submit" class="btn btn-primary" name="update_kondisi_barang">Simpan & Proses</button>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>