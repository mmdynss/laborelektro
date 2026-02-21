<?php
// MULAI SESSION PALING ATAS
session_start();

require 'function.php';
// require 'cek.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Katalog Peminjaman - Lab Elektro</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- IDENTITAS UMRAH (Biru Laut & Emas) --- */
        :root {
            --umrah-blue: #0f2f5e;     /* Biru Tua Kampus */
            --umrah-light-blue: #1e4d8c; 
            --umrah-gold: #f1c40f;     /* Kuning Emas */
            --bg-light: #f4f7fa;        /* Background sedikit kebiruan */
            --card-bg: #ffffff;
            --text-main: #1e293b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
        }

        /* --- NAVBAR --- */
        .navbar {
            background: var(--umrah-blue);
            box-shadow: 0 4px 15px rgba(15, 47, 94, 0.2);
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 700;
            color: white !important;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .navbar-brand i {
            color: var(--umrah-gold) !important;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--umrah-gold) !important;
        }
        .navbar .btn-primary {
            background-color: var(--umrah-gold);
            color: var(--umrah-blue);
            border: none;
            font-weight: 700;
        }
        .navbar .btn-primary:hover {
            background-color: #d4ac0d;
            color: var(--umrah-blue);
        }

        /* --- HERO SECTION --- */
        .hero {
            background: linear-gradient(160deg, var(--umrah-blue) 0%, var(--umrah-light-blue) 100%);
            color: white;
            padding: 90px 0 70px;
            margin-bottom: 50px;
            border-radius: 0 0 60px 0;
            position: relative;
            overflow: hidden;
            border-bottom: 5px solid var(--umrah-gold);
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        /* --- SEARCH BAR --- */
        .search-container {
            position: relative;
            max-width: 600px;
            width: 100%;
        }
        .search-input {
            border-radius: 50px;
            padding: 12px 20px;
            border: 2px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .search-input:focus {
            border-color: var(--umrah-blue);
            box-shadow: 0 0 0 4px rgba(15, 47, 94, 0.1);
        }
        .search-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            bottom: 5px;
            border-radius: 50px;
            padding: 0 25px;
            background: var(--umrah-blue);
            border: none;
            color: white;
            font-weight: 600;
        }
        .search-btn:hover {
            background: var(--umrah-light-blue);
        }

        /* --- DOWNLOAD CENTER STYLES --- */
        .list-group-item-action:hover {
            background-color: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.2s;
            border-color: #e2e8f0;
            z-index: 1;
        }

        /* --- CARDS (KATALOG) --- */
        .card-product {
            border: none;
            border-radius: 15px;
            background: var(--card-bg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            border-top: 4px solid var(--umrah-gold);
        }
        .card-product:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -10px rgba(15, 47, 94, 0.2);
        }
        .img-wrapper {
            height: 200px;
            overflow: hidden;
            position: relative;
            background-color: #e2e8f0;
            border-bottom: 1px solid #eee;
        }
        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .card-product:hover .card-img-top {
            transform: scale(1.1);
        }
        .grayscale { filter: grayscale(100%); opacity: 0.6; }

        .badge-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--umrah-blue);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            z-index: 2;
        }
        .badge-kondisi {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            z-index: 2;
            color: white;
            text-transform: uppercase;
        }

        .card-body { padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column; }
        .card-title { 
            font-weight: 700; 
            color: var(--umrah-blue); 
            margin-bottom: 0.5rem; 
            font-size: 1.1rem;
        }
        .card-text { font-size: 0.85rem; color: #64748b; margin-bottom: 1.5rem; flex-grow: 1; }

        .btn-pinjam {
            background-color: var(--umrah-blue);
            color: white;
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
            border: 1px solid var(--umrah-blue);
            width: 100%;
            transition: 0.3s;
        }
        .btn-pinjam:hover {
            background-color: white;
            color: var(--umrah-blue);
            border-color: var(--umrah-blue);
        }
        .btn-disabled {
            background-color: #e2e8f0;
            color: #94a3b8;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }
        
        /* TOMBOL KERANJANG STATUS BARU */
        .btn-cek-status {
            background-color: var(--umrah-gold);
            color: var(--umrah-blue);
            border: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .btn-cek-status:hover {
            background-color: #d4ac0d;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(241, 196, 15, 0.4);
            color: white;
        }

        /* --- STATUS COLORS --- */
        .bg-baik { background-color: #10b981; } 
        .bg-maintenance { background-color: #f59e0b; } 
        .bg-rusak { background-color: #ef4444; } 
        
        /* --- TABLE & ICONS --- */
        .table-custom thead th {
            background-color: var(--umrah-blue);
            color: white;
            font-weight: 500;
            font-size: 0.9rem;
            border: none;
            padding: 1rem;
        }
        .table-custom tbody td { 
            padding: 1rem; 
            border-bottom: 1px solid #f1f5f9; 
            vertical-align: middle; 
            font-size: 0.9rem;
        }
        
        .status-pill { padding: 5px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; display: inline-block; letter-spacing: 0.5px; }
        .status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; }
        .status-dipinjam { background: #eff6ff; color: #1d4ed8; border: 1px solid #93c5fd; }
        .status-kembali { background: #f0fdf4; color: #15803d; border: 1px solid #86efac; }
        .status-ditolak { background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5; }
        
        .section-icon-bg { background: var(--umrah-blue) !important; color: white !important; }
        .section-icon-bg-warning { background: var(--umrah-gold) !important; color: var(--umrah-blue) !important; }

        footer {
            background: var(--umrah-blue) !important;
            border-top: 5px solid var(--umrah-gold);
            color: rgba(255,255,255,0.7) !important;
        }

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="katalog.php">
                <i class="fas fa-anchor me-2"></i>Lab Elektro
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="katalog.php">Katalog Alat</a></li>
                    
                    <?php if(isset($_SESSION['log_user'])) { ?>
                        <li class="nav-item ms-3">
                            <span class="nav-link text-white fw-bold">
                                <i class="fas fa-user-graduate me-1 text-warning"></i> Halo, 
                                <?php echo isset($_SESSION['user_nama']) ? $_SESSION['user_nama'] : 'Admin'; ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm px-3 ms-2" href="logout.php">
                                Logout <i class="fas fa-sign-out-alt ms-1"></i>
                            </a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item ms-3">
                            <a class="btn btn-primary px-4 shadow-sm" href="login.php">
                                Login Mahasiswa <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">Peminjaman Alat Laboratorium</h1>
            <p class="lead opacity-75">Sistem Inventaris Terpadu Teknik Elektro</p>
        </div>
    </div>

    <div class="container">

        <div class="card mb-4 border-0 shadow-sm" style="border-top: 4px solid var(--umrah-gold);">
            <div class="card-header bg-white py-3 border-bottom-0">
                 <h5 class="m-0 fw-bold" style="color: var(--umrah-blue);"><i class="fas fa-file-download me-2"></i>Pusat Unduhan Dokumen</h5>
            </div>
            <div class="card-body pt-0">
                <div class="alert alert-light border border-info text-info shadow-sm mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i> 
                    <strong>Penting:</strong> Unduh dan lengkapi berkas yang sesuai sebelum mengajukan peminjaman. Berkas wajib dilampirkan nanti.
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold border-bottom pb-2 mb-3" style="font-size: 0.75rem;">
                            <i class="fas fa-building me-1"></i> Izin Penggunaan Ruangan
                        </h6>
                        <div class="list-group list-group-flush">
                            <a href="dokumen/1_permohonan_penelitian.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Form Penelitian & Lainnya</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Izin Penggunaan Lab</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                            <a href="dokumen/2_permohonan_praktikum.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Form Praktikum</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Khusus Kegiatan Praktikum</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                            <a href="dokumen/3_surat_pengantar_fakultas.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Surat Pengantar Fakultas</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Syarat Lintas Fakultas</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold border-bottom pb-2 mb-3" style="font-size: 0.75rem;">
                            <i class="fas fa-tools me-1"></i> Peminjaman Alat
                        </h6>
                        <div class="list-group list-group-flush">
                            <a href="dokumen/5_peminjaman_alat.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Form Peminjaman Alat</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Untuk Mahasiswa/Umum</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                            <a href="dokumen/8_peminjaman_laboran.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Form Pinjam (Laboran)</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Khusus Staff/Internal</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                            <a href="dokumen/7_pernyataan_ganti_rugi.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Surat Ganti Rugi</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Pernyataan Kerusakan</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold border-bottom pb-2 mb-3" style="font-size: 0.75rem;">
                            <i class="fas fa-clipboard-check me-1"></i> Berita Acara & Survei
                        </h6>
                        <div class="list-group list-group-flush">
                            <a href="dokumen/4_berita_acara_lab.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Berita Acara Penggunaan Lab</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Laporan Selesai Kegiatan</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                            <a href="dokumen/6_berita_acara_alat.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded" download>
                                <div class="me-3 text-primary"><i class="fas fa-file-word fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Berita Acara Pengecekan Alat</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Cek Kondisi Barang</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                            <a href="dokumen/9_survei_kepuasan.docx" class="list-group-item list-group-item-action d-flex align-items-center p-2 border-0 mb-1 rounded bg-light" download>
                                <div class="me-3 text-primary"><i class="fas fa-poll-h fa-2x"></i></div>
                                <div class="flex-grow-1" style="line-height: 1.2;">
                                    <small class="d-block fw-bold text-dark">Survei Kepuasan</small>
                                    <small class="text-muted" style="font-size: 0.7rem;">Masukan untuk Lab</small>
                                </div>
                                <i class="fas fa-download text-muted"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2 col-md-8 mx-auto mb-5">
            <button class="btn btn-cek-status btn-lg shadow rounded-pill py-3" type="button" data-bs-toggle="modal" data-bs-target="#modalStatus">
                <i class="fas fa-clipboard-list fa-lg me-2"></i> Klik disini untuk melihat status pengajuan
            </button>
        </div>

        <div class="row align-items-end mb-4 mt-5 pb-3 border-bottom" style="border-color: #e2e8f0 !important;">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-2 d-flex align-items-center justify-content-center section-icon-bg-warning" style="width: 40px; height: 40px;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="fw-bold mb-0" style="color: var(--umrah-blue);">Daftar Alat</h3>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-md-end">
                <form method="GET" class="search-container">
                    <input type="text" name="search" class="form-control search-input" placeholder="Cari nama alat atau komponen..." value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <?php if(isset($_GET['search'])) { ?>
            <div class="alert alert-info mb-4 border-0 shadow-sm" style="background-color: #eff6ff; color: var(--umrah-blue);">
                <i class="fas fa-info-circle me-2"></i>Menampilkan hasil pencarian untuk: <strong>"<?= htmlspecialchars($_GET['search']); ?>"</strong>
                <a href="katalog.php" class="float-end text-decoration-none fw-bold small">Reset Pencarian</a>
            </div>
        <?php } ?>

        <div class="row g-4 mb-5">
            <?php
            // --- LOGIKA PENCARIAN ---
            if(isset($_GET['search'])){
                $keyword = mysqli_real_escape_string($conn, $_GET['search']);
                $query = "SELECT * FROM stock WHERE namabarang LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' ORDER BY idbarang DESC";
            } else {
                $query = "SELECT * FROM stock ORDER BY idbarang DESC";
            }
            
            $ambilstock = mysqli_query($conn, $query);
            
            // CEK JIKA BARANG TIDAK DITEMUKAN
            if(mysqli_num_rows($ambilstock) == 0){
                echo '
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-3 opacity-50">
                    <h4 class="text-muted fw-bold">Barang tidak ditemukan</h4>
                    <p class="text-secondary">Silakan coba kata kunci lain atau hubungi laboran.</p>
                    <a href="katalog.php" class="btn btn-outline-primary rounded-pill px-4">Lihat Semua Barang</a>
                </div>';
            }

            while($data = mysqli_fetch_array($ambilstock)){
                $idb = $data['idbarang'];
                $namabarang = $data['namabarang'];
                $deskripsi = $data['deskripsi'];
                $stock = $data['stock'];
                
                $kondisi = isset($data['kondisi']) ? $data['kondisi'] : 'Baik'; 

                // Tentukan Warna Badge Kondisi
                if($kondisi == 'Rusak'){ 
                    $badge_class = 'bg-rusak'; $badge_text = 'Rusak';
                } elseif($kondisi == 'Maintenance' || $kondisi == 'Perlu Maintenance'){ 
                    $badge_class = 'bg-maintenance'; $badge_text = 'Maintenance';
                } else { 
                    $badge_class = 'bg-baik'; $badge_text = 'Baik';
                }

                // --- LOGIKA STOK & KETERSEDIAAN ---
                if($stock > 0){
                    $status_tersedia = true;
                    $status_text = "Tersedia: $stock Unit";
                    $status_color = "text-success";
                    $img_filter = ""; 
                } else {
                    $status_tersedia = false;
                    $status_text = "Stok Habis";
                    $status_color = "text-danger";
                    $img_filter = "grayscale"; 
                }

                // --- LOGIKA GAMBAR ---
                $image = $data['image'];
                if($image == null || $image == ''){
                    $img_src = "https://dummyimage.com/600x400/f1f5f9/94a3b8.jpg&text=No+Image";
                } else {
                    $img_src = "images/" . $image;
                }
            ?>
            
            <div class="col-md-6 col-lg-3">
                <div class="card card-product">
                    
                    <div class="img-wrapper">
                        <span class="badge-kondisi <?=$badge_class;?>"><?=$badge_text;?></span>
                        <span class="badge-stock"><i class="fas fa-layer-group me-1"></i> <?=$stock;?></span>
                        <img src="<?=$img_src;?>" class="card-img-top <?=$img_filter;?>" alt="<?=$namabarang;?>">
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?=$namabarang;?></h5>
                        
                        <p class="small fw-bold <?=$status_color;?> mb-2">
                            <i class="fas <?=$status_tersedia ? 'fa-check-circle' : 'fa-times-circle';?> me-1"></i> <?=$status_text;?>
                        </p>

                        <p class="card-text">
                            <?= (strlen($deskripsi) > 45) ? substr($deskripsi,0,45)."..." : $deskripsi; ?>
                        </p>

                        <?php if($status_tersedia && $kondisi != 'Rusak') { ?>
                            <button type="button" class="btn btn-pinjam" data-bs-toggle="modal" data-bs-target="#pinjam<?=$idb;?>">
                                Ajukan Pinjam
                            </button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-pinjam btn-disabled" disabled>
                                Tidak Tersedia
                            </button>
                        <?php } ?>

                    </div>
                </div>
            </div>

            <?php if($status_tersedia) { ?>
            <div class="modal fade" id="pinjam<?=$idb;?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background: var(--umrah-blue); color: white;">
                            <h5 class="modal-title fw-bold">Form Peminjaman</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                
                                <div class="d-flex align-items-center mb-4 p-3 rounded-3" style="background: #f1f5f9; border-left: 4px solid var(--umrah-gold);">
                                    <img src="<?=$img_src;?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark"><?=$namabarang;?></h6>
                                        <small class="text-muted">Stok Tersedia: <b class="text-success"><?=$stock;?></b></small>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="idbarang" value="<?=$idb;?>">
                                
                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold text-uppercase">Jumlah</label>
                                    <input type="number" name="qty" class="form-control fw-bold" min="1" max="<?=$stock;?>" value="1" required>
                                </div>

                               <div class="mb-3">
    <label class="form-label small text-muted fw-bold text-uppercase">Data Peminjam</label>
    
    <input type="text" name="penerima" class="form-control mb-2 bg-light" 
           value="<?= isset($_SESSION['user_nama']) ? $_SESSION['user_nama'] : ''; ?>" 
           placeholder="Nama Lengkap" readonly required>
           
    <div class="row g-2">
        <div class="col-6">
            <input type="text" name="status_peminjam" class="form-control bg-light" 
                   value="<?= isset($_SESSION['user_status']) ? $_SESSION['user_status'] : 'Mahasiswa'; ?>" 
                   readonly required>
        </div>
        <div class="col-6">
            <input type="number" name="nidn_nim" class="form-control bg-light" 
                   value="<?= isset($_SESSION['user_nim']) ? $_SESSION['user_nim'] : ''; ?>" 
                   placeholder="NIM/NIDN/NIP" readonly required>
        </div>
    </div>
</div>

<div class="mb-3">
    <input type="text" name="prodi" class="form-control mb-2 bg-light" 
           value="<?= isset($_SESSION['user_prodi']) ? $_SESSION['user_prodi'] : ''; ?>" 
           placeholder="Program Studi" readonly required>
           
    <input type="number" name="no_hp" class="form-control bg-light" 
           value="<?= isset($_SESSION['user_hp']) ? $_SESSION['user_hp'] : ''; ?>" 
           placeholder="No. WhatsApp (08xx)" readonly required>
</div>

                                <div class="mb-3">
                                    <label class="form-label small text-muted fw-bold text-uppercase">Tujuan</label>
                                    <textarea name="alasan" class="form-control" rows="2" placeholder="Keperluan peminjaman..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold" name="addpinjam" style="background: var(--umrah-blue); border: none;">Kirim Pengajuan</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>
        </div>
    </div>

    <div class="modal fade" id="modalStatus" tabindex="-1" aria-labelledby="modalStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--umrah-blue); color: white;">
                    <h5 class="modal-title fw-bold" id="modalStatusLabel"><i class="fas fa-history me-2"></i>Status Pengajuan Terkini</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Detail Barang</th>
                                    <th>Peminjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
<tbody>
    <?php
    // 1. Cek apakah user sudah login?
    if(isset($_SESSION['log_user'])){
        
        // 2. Ambil Nama user yang sedang login dari session
        // Pastikan nama di session sama dengan nama yang diinput saat meminjam
        $nama_peminjam = $_SESSION['user_nama']; 

        // 3. Query dengan FILTER (WHERE p.peminjam = '$nama_peminjam')
        // Sesuaikan 'peminjam' dengan nama kolom di tabel database Anda yang menyimpan nama mahasiswa
        $riwayat = mysqli_query($conn, "SELECT * FROM peminjaman p, stock s 
                                      WHERE p.idbarang = s.idbarang 
                                      AND p.peminjam = '$nama_peminjam' 
                                      ORDER BY idpeminjaman DESC");
        
        if(mysqli_num_rows($riwayat) > 0){
            while($r = mysqli_fetch_array($riwayat)){
                $status = $r['status'];
                $pesan_alasan = isset($r['alasan']) ? $r['alasan'] : ''; // Kolom 'alasan'

                // Logic warna dan icon
                if($status=='Pending'){ 
                    $class='status-pending'; $icon='fa-clock'; 
                } elseif($status=='Dipinjam'){ 
                    $class='status-dipinjam'; $icon='fa-hand-holding-box'; 
                } elseif($status=='Kembali'){ 
                    $class='status-kembali'; $icon='fa-check-circle'; 
                } else { 
                    $class='status-ditolak'; $icon='fa-times-circle'; 
                } 
    ?>
        <tr>
            <td class="text-muted fw-bold"><?=date('d/m/Y', strtotime($r['tanggalpinjam']));?></td>
            <td>
                <span class="d-block fw-bold text-dark"><?=$r['namabarang'];?></span>
                <small class="text-muted">Jml: <?=$r['qty'];?></small>
            </td>
            <td><?=$r['peminjam'];?></td>
            <td>
                <span class="status-pill <?=$class;?> mb-1">
                    <i class="fas <?=$icon;?> me-1"></i> <?=$status;?>
                </span>
                
                <?php if($status == 'Ditolak' && !empty($pesan_alasan)){ ?>
                    <div class="mt-2 p-2 rounded bg-light border border-danger text-danger position-relative" style="font-size: 0.75rem; line-height: 1.2;">
                        <i class="fas fa-info-circle me-1"></i><strong>Info:</strong><br>
                        <i>"<?=$pesan_alasan;?>"</i>
                    </div>
                <?php } ?>
            </td>
        </tr>

    <?php 
            } // Akhir While
        } else { 
            // Jika sudah login tapi belum pernah meminjam
            echo '<tr><td colspan="4" class="text-center text-muted py-5">
                    <i class="fas fa-folder-open fa-2x mb-3 opacity-50"></i><br>
                    Hai <b>'.$nama_peminjam.'</b>, Anda belum memiliki riwayat peminjaman.
                  </td></tr>';
        }

    } else {
        // Jika BELUM LOGIN (Status Guest)
        echo '<tr><td colspan="4" class="text-center text-muted py-5">
                <i class="fas fa-lock fa-2x mb-3 text-warning"></i><br>
                Silakan <b>Login</b> terlebih dahulu untuk melihat status pengajuan Anda.
              </td></tr>';
    }
    ?>
</tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <small class="text-muted me-auto"><i>*Hanya menampilkan 5 transaksi terakhir</i></small>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 mt-auto text-center">
        <div class="container">
            <small>&copy; 2024 Laboratorium Teknik Elektro - UMRAH.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>