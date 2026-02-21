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
        <title>Data Peminjaman - Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .badge-status { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; color: white; }
            .bg-pending { background-color: #f39c12; }  
            .bg-dipinjam { background-color: #3498db; } 
            .bg-selesai { background-color: #2ecc71; }  
            .bg-ditolak { background-color: #e74c3c; }
            
            /* Style tambahan untuk identitas */
            .badge-role { font-size: 0.7rem; padding: 3px 6px; border-radius: 4px; vertical-align: middle; }
            .role-mahasiswa { background-color: #1abc9c; color: white; } /* Tosca */
            .role-dosen { background-color: #9b59b6; color: white; } /* Ungu */
            .role-staff { background-color: #34495e; color: white; } /* Dark */
        </style>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.php">Lab Elektro Admin</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
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
			    <a class="nav-link active" href="peminjaman.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                                Pemakaian/Peminjaman Barang
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
                        <h1 class="mt-4">Data Peminjaman</h1>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Daftar Riwayat Peminjaman
                                <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#myModal">
                                    <i class="fas fa-plus"></i> Pinjam Manual
                                </button>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th width="25%">Identitas Peminjam</th>
                                            <th>Prodi</th>
                                            <th>Barang</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM peminjaman p, stock s WHERE p.idbarang = s.idbarang ORDER BY idpeminjaman DESC");
                                        while($data = mysqli_fetch_array($ambilsemuadatastock)){
                                            $idk = $data['idpeminjaman'];
                                            $idb = $data['idbarang'];
                                            $namabarang = $data['namabarang'];
                                            $qty = $data['qty'];
                                            $peminjam = $data['peminjam'];
                                            $status = $data['status'];
                                            $tanggal = $data['tanggalpinjam'];
                                            
                                            // Data Baru
                                            $no_hp = $data['no_hp'] ?? '-';
                                            $alasan = $data['alasan'] ?? '-';
                                            
                                            // Data Identitas Baru
                                            $status_peminjam = $data['status_peminjam'] ?? 'Umum';
                                            $nidn_nim = $data['nidn_nim'] ?? '-';
                                            $prodi = $data['prodi'] ?? '-';
                                            
                                            $stok_gudang = $data['stock'];

                                            // Logika Warna Status Peminjaman
                                            if($status=='Pending'){ $class='bg-pending'; }
                                            elseif($status=='Dipinjam'){ $class='bg-dipinjam'; }
                                            elseif($status=='Kembali'){ $class='bg-selesai'; }
                                            else{ $class='bg-ditolak'; }

                                            // Logika Warna Role
                                            if($status_peminjam == 'Mahasiswa') { $role_class = 'role-mahasiswa'; }
                                            elseif($status_peminjam == 'Dosen') { $role_class = 'role-dosen'; }
                                            else { $role_class = 'role-staff'; }
                                        ?>
                                        <tr>
                                            <td><?=$tanggal;?></td>
                                            <td>
                                                <strong><?=$peminjam;?></strong> 
                                                <span class="badge-role <?=$role_class;?>"><?=$status_peminjam;?></span><br>
                                                <small class="text-light">NIM/NIDN/NIP: <?=$nidn_nim;?></small><br>
                                                <small class="text-light"><i class="fab fa-whatsapp text-success"></i> <?=$no_hp;?></small>
                                            </td>
                                            <td><?=$prodi;?></td>
                                            <td><?=$namabarang;?></td>
                                            <td><?=$qty;?></td>
                                            <td><span class="badge-status <?=$class;?>"><?=$status;?></span></td>
                                            <td>
                                                <?php if($status == 'Pending'){ ?>
                                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#proses<?=$idk;?>">
                                                        <i class="fas fa-edit"></i> Proses
                                                    </button>
                                                <?php } elseif($status == 'Dipinjam'){ ?>
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#selesai<?=$idk;?>">
                                                        <i class="fas fa-check"></i> Selesai
                                                    </button>
                                                <?php } else { ?>
                                                    <button class="btn btn-secondary btn-sm" disabled><i class="fas fa-check-double"></i> Done</button>
                                                <?php } ?>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="proses<?=$idk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning">
                                                        <h4 class="modal-title">Konfirmasi Peminjaman</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="idpeminjaman" value="<?=$idk;?>">
                                                            <input type="hidden" name="idbarang" value="<?=$idb;?>">
                                                            <input type="hidden" name="qty" value="<?=$qty;?>">

                                                            <p>Detail Permintaan:</p>
                                                            <ul class="list-group mb-3">
                                                                <li class="list-group-item">
                                                                    <strong>Peminjam:</strong> <?=$peminjam;?> (<?=$status_peminjam;?>)<br>
                                                                    <small>NIM/NIP: <?=$nidn_nim;?></small><br>
                                                                    <small>Prodi: <?=$prodi;?></small>
                                                                </li>
                                                                <li class="list-group-item"><strong>Barang:</strong> <?=$namabarang;?> (<?=$qty;?> pcs)</li>
                                                                <li class="list-group-item list-group-item-secondary"><strong>Alasan:</strong><br><?=$alasan;?></li>
                                                            </ul>
                                                            <hr>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold text-danger">Isi Alasan Jika Menolak:</label>
                                                                <textarea name="alasan_tolak" class="form-control" rows="2" placeholder="Contoh: Barang maintenance / Stok fisik rusak"></textarea>
                                                            </div>
                                                            <?php if($stok_gudang < $qty){ ?>
                                                                <div class="alert alert-danger"><strong>PERINGATAN:</strong> Stok kurang!</div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-between">
                                                            <button type="submit" class="btn btn-danger" name="tolak_pinjam"><i class="fas fa-times"></i> Tolak</button>
                                                            <?php if($stok_gudang >= $qty){ ?>
                                                                <button type="submit" class="btn btn-primary" name="setuju_pinjam"><i class="fas fa-check"></i> Setujui</button>
                                                            <?php } else { ?>
                                                                <button type="button" class="btn btn-secondary" enable>Stok Kurang</button>
                                                            <?php } ?>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="selesai<?=$idk;?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-success text-white">
                                                        <h4 class="modal-title">Pengembalian Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="idpinjam" value="<?=$idk;?>">
                                                            <input type="hidden" name="idbarang" value="<?=$idb;?>">
                                                            <p>Pastikan barang sudah diterima kembali dengan kondisi baik.</p>
                                                            <div class="alert alert-info">
                                                                Barang: <strong><?=$namabarang;?></strong><br>
                                                                Jumlah Kembali: <strong><?=$qty;?></strong>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success" name="barangkembali">Terima & Restock</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Lab Elektro 2024</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Peminjaman Manual</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <label>Pilih Barang</label>
                            <select name="barangnya" class="form-control mb-3">
                                <?php
                                $ambilsemuadatanya = mysqli_query($conn, "select * from stock where stock > 0");
                                while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
                                    $namabarangnya = $fetcharray['namabarang'];
                                    $idbarangnya = $fetcharray['idbarang'];
                                    $sisa = $fetcharray['stock'];
                                ?>
                                <option value="<?=$idbarangnya;?>"><?=$namabarangnya;?> (Sisa: <?=$sisa;?>)</option>
                                <?php } ?>
                            </select>

                            <label>Jumlah</label>
                            <input type="number" name="qty" class="form-control mb-3" min="1" required>

                            <label>Nama Peminjam</label>
                            <input type="text" name="penerima" class="form-control mb-3" placeholder="Nama Lengkap" required>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Status</label>
                                    <select name="status_peminjam" class="form-control mb-3">
                                        <option value="Mahasiswa">Mahasiswa</option>
                                        <option value="Dosen">Dosen</option>
                                        <option value="Staff">Staff</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>NIM / NIP / NIDN</label>
                                    <input type="text" name="nidn_nim" class="form-control mb-3" placeholder="Nomor Induk" required>
                                </div>
                            </div>

                            <label>Program Studi</label>
                            <input type="text" name="prodi" class="form-control mb-3" placeholder="Contoh: Teknik Elektro" required>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="pinjam">Submit</button>
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
