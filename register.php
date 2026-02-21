<?php require 'function.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Akun - Lab Elektro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background-color: #e9ecef; }
        .card-register { max-width: 450px; margin: 80px auto; border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-register p-4">
            <h3 class="text-center mb-4">Pendaftaran Akun</h3>
            <p class="text-center text-muted small mb-4">Silakan isi data diri sesuai identitas kampus.</p>
            
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required placeholder="Contoh: Budi Santoso">
                </div>
                <div class="mb-3">
                    <label class="form-label">NIM / NIP / NIDN</label>
                    <input type="text" name="nomor_induk" class="form-control" required placeholder="Nomor Induk Mahasiswa / Pegawai">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Buat Password">
                </div>
                <button type="submit" name="register_user" class="btn btn-primary w-100">Daftar Sekarang</button>
            </form>
            <div class="text-center mt-3">
                <small>Sudah punya akun? <a href="login_user.php">Login disini</a></small>
            </div>
        </div>
    </div>
</body>
</html>