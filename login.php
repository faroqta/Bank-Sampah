<?php
session_start();
require 'functions.php';

	if ( isset($_POST["login"]) ){

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $data_admin = mysqli_query($conn, "SELECT * FROM admins WHERE usernameAdmin = '$username' AND passwordAdmin = '$password'");
        $data_nasabah = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND passwordUser = '$password'");

        $cek_user = mysqli_num_rows($data_nasabah);
        $cek_admin = mysqli_num_rows($data_admin);

        if($cek_admin > 0) {
          $a = mysqli_fetch_array($data_admin);
          // admin
          $IdAdmin = $a['IdAdmin'];
          $nama_a = $a['namaAdmin'];
          $username_a = $a['usernameAdmin'];
          $password_a = $a['passwordAdmin'];
          $level_a = $a['level'];
          $cek_admin = mysqli_num_rows($data_admin);
        } elseif ($cek_user > 0) {
          $n = mysqli_fetch_array($data_nasabah);
          // nasabah
          $id_n = $n['idUser'];
          $nama_n = $n['namaUser'];
          $nik_n = $n['nik'];
          $alamat = $n['alamat'];
          $telepon_n = $n['telepon'];
          $username_n = $n['username'];
          $password_n = $n['passwordUser'];
          $jmlSetoran = $n['jmlSetoran'];
          $saldo = $n['saldo'];
          $cek_user = mysqli_num_rows($data_nasabah);
        } else {
          echo "
            <script>
            alert('Maaf username dan password tidak valid!');
            document.location.href ='login.php';
            </script>
          ";
        }


        if ($username == "" || $password == "") {
            echo "
            <script>
                alert('Username dan Password tidak boleh kosong!');
                document.location.href ='login.php';
            </script>
            ";
        }
        else {
            if ($cek_admin > 0) {
        $_SESSION['IdAdmin'] = $IdAdmin;
        $_SESSION['namaAdmin'] = $nama_a;
        $_SESSION['usernameAdmin'] = $username_a;
        $_SESSION['passwordAdmin'] = $password_a;
        $_SESSION['level'] = $level_a;
        $_SESSION['role'] = 'admin';
        $_SESSION["login"] = true;
        if (isset($_POST['remember'])) {
            setcookie('login', 'true', time()+60);
        }
        echo "<script>alert('Selamat Anda berhasil login!'); document.location.href='admin.php';</script>";
        exit;
        }
        else if ($cek_user > 0) {
        $_SESSION['idUser'] = $id_n;
        $_SESSION['namaUser'] = $nama_n;
        $_SESSION['nik'] = $nik_n;
        $_SESSION['alamat'] = $alamat;
        $_SESSION['telepon'] = $telepon_n;
        $_SESSION['username'] = $username_n;
        $_SESSION['passwordUser'] = $password;
        $_SESSION['jmlSetoran'] = $jmlSetoran;
        $_SESSION['saldo'] = $saldo;
        $_SESSION["login"] = true;
        //cek remember me
				if ( isset($_POST['remember']) ){
					//buat cookie
					setcookie('login', 'true', time()+60);
				}
        echo "
        <script>
            alert('Selamat Anda berhasil login!');
            document.location.href ='user.php';
        </script>
        ";
        }

        else {
        echo "
        <script>
        alert('Maaf username dan password tidak valid!');
        document.location.href ='login.php';
        </script>
        ";
	    }
    }
}


?>
<!doctype html>
<html lang="id">
<head>
    <title>Login - Bank Sampah</title>
    <link rel="icon" type="image/png" href="img/logo/logombs.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #2d5c3e 0%, #1e4129 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .loading img {
            width: 80px;
        }
        
        /* Login Container */
        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 16px;
            padding: 30px 35px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.4s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .logo-img {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            background: none;
            border-radius: 0;
            box-shadow: none;
            padding: 0;
        }
        
        .logo-img img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 0;
        }
        
        .logo-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 3px;
        }
        
        .logo-subtitle {
            font-size: 13px;
            color: #6c757d;
            font-weight: 400;
        }
        
        /* Welcome Text */
        .welcome-text {
            text-align: center;
            margin-bottom: 22px;
        }
        
        .welcome-text h3 {
            font-size: 19px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        
        .welcome-text p {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 6px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
        }
        
        .form-control {
            width: 100%;
            padding: 11px 14px 11px 42px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            color: #374151;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }
        
        .form-control::placeholder {
            color: #9ca3af;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2d5c3e;
            box-shadow: 0 0 0 3px rgba(45, 92, 62, 0.08);
        }
        
        /* Remember Me Checkbox */
        .remember-section {
            display: flex;
            align-items: center;
            margin-bottom: 18px;
            margin-top: -2px;
        }
        
        .remember-section input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            margin-right: 7px;
            accent-color: #2d5c3e;
        }
        
        .remember-section label {
            font-size: 13px;
            color: #6c757d;
            cursor: pointer;
            margin: 0;
        }
        
        /* Button Styles */
        .btn-container {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }
        
        .btn-back {
            width: 46px;
            height: 46px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #6c757d;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        
        .btn-back:hover {
            background: #f8f9fa;
            color: #2d5c3e;
            border-color: #2d5c3e;
        }
        
        .btn-login {
            flex: 1;
            padding: 11px 20px;
            border: none;
            border-radius: 8px;
            background: #2d5c3e;
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .btn-login:hover {
            background: #1e4129;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(45, 92, 62, 0.25);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        /* Register Link */
        .register-link {
            text-align: center;
            font-size: 13px;
            color: #6c757d;
            margin-top: 16px;
        }
        
        .register-link a {
            color: #2d5c3e;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .register-link a:hover {
            color: #1e4129;
            text-decoration: underline;
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 18px 0;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e9ecef;
        }
        
        .divider span {
            padding: 0 12px;
            color: #9ca3af;
            font-size: 12px;
            font-weight: 500;
        }
        
        /* Footer Text */
        .footer-text {
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 18px;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                padding: 25px 28px;
                max-width: 380px;
            }
            
            .logo-title {
                font-size: 18px;
            }
            
            .welcome-text h3 {
                font-size: 17px;
            }
        }
        
        @media (min-height: 700px) {
            body {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loading">
            <img src="img/aset/loading.gif" width="80" alt="Loading">
        </div>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-img">
                <img src="img/logo/logombs.png" alt="logo">
            </div>
            <h2 class="logo-title">Bank Sampah</h2>
            <p class="logo-subtitle">Mugi Berkah Sari</p>
        </div>
        
        <!-- Welcome Text -->
        <div class="welcome-text">
            <h3>Selamat Datang Kembali!</h3>
            <p>Silakan masuk ke akun Anda untuk melanjutkan</p>
        </div>
        
        <!-- Login Form -->
        <form action="" method="post">
            <!-- Username Field -->
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        class="form-control" 
                        placeholder="Masukkan username Anda"
                        required
                    >
                </div>
            </div>
            
            <!-- Password Field -->
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-control" 
                        placeholder="Masukkan password Anda"
                        required
                    >
                </div>
            </div>
            
            <!-- Remember Me -->
            <div class="remember-section">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya</label>
            </div>
            
            <!-- Buttons -->
            <div class="btn-container">
                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <button type="submit" name="login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk
                </button>
            </div>
            
            <!-- Register Link -->
            <div class="register-link">
                Belum punya akun? <a href="registrasi.php">Daftar sekarang</a>
            </div>
        </form>
        
        <!-- Footer -->
        <div class="footer-text">
            © 2025 Mugi Berkah Sari. All rights reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".preloader").fadeOut();
        });
    </script>
</body>
</html>