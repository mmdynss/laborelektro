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
        <title>Cetak Sertifikat - Lab Elektro</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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
                            <a class="nav-link" href="maintenance.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tools"></i></div> Maintenance
                            </a>
                            <a class="nav-link" href="peminjaman.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div> Pemakaian/Peminjaman Barang
                            </a>
                            <a class="nav-link" href="Admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div> Kelola Admin
                            </a>
                            
                            <a class="nav-link active" href="halaman_sertifikat.php">
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
                        <h1 class="mt-4">Cetak Sertifikat Asisten</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="text-white breadcrumb-item active">Form Sertifikat</li>
                        </ol>

                        <div class="card mb-4">
                            <div class="card-header text-white">
                                <i class="fas fa-file-alt me-1"></i>
                                Form Input Data Sertifikat
                            </div>
                            <div class="card-body">
                                <form action="cetak_pdf.php" method="POST" target="_blank">
                                    
                                    <h5 class="text-light border-bottom pb-2 mb-3">1. Data Mahasiswa</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="text-black form-floating mb-3 mb-md-0">
                                                <input class="form-control" name="nama" type="text" placeholder="Nama Lengkap" required />
                                                <label>Nama Lengkap</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-black form-floating">
                                                <input class="form-control" name="nim" type="text" placeholder="NIM" required />
                                                <label>NIM</label>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="text-light border-bottom pb-2 mb-3 mt-4">2. Detail Sertifikat</h5>
                                    <div class="text-white mb-3">
                                        <label class="form-label fw-bold">Nomor Sertifikat</label>
                                        <input class="text-dark form-control" name="no_sertifikat" type="text" placeholder="Contoh: 001/SERT-ASPR/LAB-TE/UMRAH/GASAL/2025-2026" required />
                                    </div>
                                    
                                    <div class="text-white mb-3">
                                        <label class="form-label fw-bold">Mata Kuliah Praktikum</label>
                                        <input class="form-control" name="matkul" type="text" placeholder="Contoh: Dasar Sistem Telekomunikasi" required />
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <div class="text-black form-floating mb-3 mb-md-0">
                                                <select class="form-select" name="semester">
                                                    <option value="Ganjil">Ganjil</option>
                                                    <option value="Genap">Genap</option>
                                                </select>
                                                <label>Semester</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-black form-floating mb-3 mb-md-0">
                                                <input class="form-control" name="tahun_akademik" type="text" value="2025/2026" required />
                                                <label>Tahun Akademik</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-dark form-floating">
                                                <input class="form-control" name="no_sk_dekan" type="text" value="02/XX/27.01" required />
                                                <label>No. SK Dekan (Footer)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="text-light border-bottom pb-2 mb-3 mt-4">3. Pejabat Penandatangan</h5>
                                    <div class="text-light mb-3">
                                        <label class="form-label fw-bold">Jabatan</label>
                                        <input class="form-control" name="jabatan" type="text" value="Ka. Lab Teknik Elektro" required />
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="text-dark form-floating mb-3 mb-md-0">
                                                <input class="form-control" name="ka_lab" type="text" value="Rusfa, S.T., M.T." required />
                                                <label>Nama Pejabat</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-dark form-floating">
                                                <input class="form-control" name="nip_ka_lab" type="text" value="198XXXXXXXXXXXXX" required />
                                                <label>NIP Pejabat</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 mb-0">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                                <i class="fas fa-print me-2"></i> Pratinjau & Cetak PDF
                                            </button>
                                        </div>
                                    </div>

                                </form>
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
    </body>
</html>