<?php require 'function.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login Sistem - Universitas Maritim Raja Ali Haji</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- KONFIGURASI WARNA TEMA UMRAH --- */
        :root {
            --umrah-blue: #003366;       /* Biru Gelap Institusi */
            --umrah-blue-light: #005b9f; /* Biru Terang untuk Hover */
            --umrah-gold: #f39c12;       /* Kuning Emas Aksen */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(180deg, #003366 0%, #001f3f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-attachment: fixed;
            overflow-y: auto;
            padding: 20px 0;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1559305616-3f97263921c8?q=80&w=2070&auto=format&fit=crop') no-repeat center center/cover;
            opacity: 0.1;
            z-index: -1;
        }

        .main-container {
            width: 100%;
            max-width: 500px; /* Diperlebar sedikit agar form register lebih lega */
            padding: 15px;
        }

        /* --- HEADER & LOGO --- */
        .university-header {
            text-align: center;
            color: white;
            margin-bottom: 25px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .logo-container {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 20px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }

        .logo-img {
            height: 60px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s;
        }
        
        .logo-img:hover { transform: scale(1.1); }

        .univ-title {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .univ-subtitle {
            font-weight: 300;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        /* --- CARD LOGIN --- */
        .card-auth {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .nav-tabs {
            border-bottom: 2px solid #f1f2f6;
            background: #f8f9fa;
        }
        .nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 600;
            padding: 1rem;
            border: none;
            transition: all 0.3s;
        }
        .nav-tabs .nav-link:hover {
            color: var(--umrah-blue);
            background: rgba(0, 51, 102, 0.05);
        }
        .nav-tabs .nav-link.active {
            color: var(--umrah-blue);
            background: white;
            border-bottom: 3px solid var(--umrah-blue);
        }

        .card-body { padding: 2.5rem; }

        /* Input Form */
        .form-floating label { color: #999; }
        .form-control, .form-select {
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--umrah-blue);
            box-shadow: 0 0 0 0.25rem rgba(0, 51, 102, 0.15);
        }

        /* Tombol */
        .btn-primary-umrah {
            background-color: var(--umrah-blue);
            border: none;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s;
            color: white;
        }
        .btn-primary-umrah:hover {
            background-color: var(--umrah-blue-light);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .btn-gold-umrah {
            background-color: var(--umrah-gold);
            color: white;
            border: none;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-gold-umrah:hover {
            background-color: #e67e22;
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .footer-text {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
            margin-top: 25px;
            text-align: center;
        }
        .fw-600 { font-weight: 600; }
    </style>
</head>
<body>

<div class="main-container">
    
    <div class="university-header">
        <div class="logo-container">
            <img src="images/logo_umrah.png" alt="Logo UMRAH" class="logo-img"> 
        </div>
        <h1 class="univ-title">UNIVERSITAS MARITIM RAJA ALI HAJI</h1>
        <p class="univ-subtitle">Sistem Informasi Inventaris Laboratorium Teknik Elektro</p>
    </div>

    <div class="card card-auth">
        <ul class="nav nav-tabs nav-fill" id="authTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane" type="button" role="tab">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-pane" type="button" role="tab">
                    <i class="fas fa-user-plus me-2"></i>Daftar
                </button>
            </li>
        </ul>

        <div class="card-body">
            <div class="tab-content" id="authTabContent">
                
                <div class="tab-pane fade show active" id="login-pane" role="tabpanel">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-dark">Selamat Datang</h5>
                        <p class="text-muted small">Silakan login untuk mengakses sistem.</p>
                    </div>
                    <form method="post">
                        <div class="form-floating mb-3">
                            <input class="form-control" name="username" type="text" placeholder="Username/NIM" required autocomplete="off" />
                            <label>Email (Admin) / NIM (Mahasiswa)</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input class="form-control" name="password" type="password" placeholder="Password" required />
                            <label>Password</label>
                        </div>
                        <button class="btn btn-primary-umrah w-100" type="submit" name="login_system">
                            Login Sekarang
                        </button>
                    </form>
                </div>

                <div class="tab-pane fade" id="register-pane" role="tabpanel">
                    <div class="text-center mb-4">
                        <h5 class="fw-bold text-dark">Registrasi Akun</h5>
                        <p class="text-muted small">Lengkapi data untuk mempermudah peminjaman alat.</p>
                    </div>
                    <form method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-600 text-secondary">Nama Lengkap</label>
                                <input class="form-control" name="nama_lengkap" type="text" placeholder="Contoh: Budi" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-600 text-secondary">NIM / NIP</label>
                                <input class="form-control" name="nomor_induk" type="text" placeholder="Masukkan NIM" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-600 text-secondary">No. WhatsApp</label>
                                <input class="form-control" name="no_hp" type="number" placeholder="08..." required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-600 text-secondary">Program Studi</label>
                                <select name="prodi" class="form-select" required>
                                    <option value="" selected disabled>Pilih Prodi</option>
                                    <option value="Teknik Elektro">Teknik Elektro</option>
                                    <option value="Informatika">Informatika</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-600 text-secondary">Status Pengguna</label>
                            <select name="status_user" class="form-select" required>
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-600 text-secondary">Password</label>
                            <input class="form-control" name="password" type="password" placeholder="Buat Password" required />
                        </div>

                        <button class="btn btn-gold-umrah w-100" type="submit" name="register_user">
                            <i class="fas fa-paper-plane me-1"></i> Daftar Akun
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <div class="footer-text">
        &copy; 2024 Laboratorium Teknik Elektro<br>
        Universitas Maritim Raja Ali Haji (UMRAH)
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>