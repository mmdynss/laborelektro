<?php 
require 'function.php'; 
if(isset($_SESSION['log_user'])){
    header('location:katalog.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login User - Lab Elektro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-login { width: 100%; max-width: 400px; padding: 2rem; border-radius: 15px; background: white; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
    </style>
</head>
<body>
    <div class="card card-login">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Login Katalog</h3>
            <p class="text-muted">Masuk menggunakan NIM/NIP Anda</p>
        </div>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">NIM / NIP / NIDN</label>
                <input type="text" name="nomor_induk" class="form-control" required placeholder="Masukkan Nomor Induk">
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Masukkan Password">
            </div>
            <button type="submit" name="login_user" class="btn btn-primary w-100 py-2">Masuk</button>
        </form>
        <div class="text-center mt-4">
            <p class="small">Belum terdaftar? <a href="register.php" class="text-primary fw-bold">Daftar disini</a></p>
            <hr>
            <a href="login.php" class="small text-muted">Login sebagai Admin</a>
        </div>
    </div>
</body>
</html>