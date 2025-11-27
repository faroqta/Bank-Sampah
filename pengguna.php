<?php
session_start();
require 'functions.php';

if (!isset($_SESSION["login"])) {
    echo "<script>alert('Anda Harus Login Terlebih Dahulu!');document.location.href ='login.php';</script>";
    exit;
}

// Proses tambah data
if(isset($_POST['mode']) && $_POST['mode'] == 'tambah'){
    // Generate ID otomatis
    $last = mysqli_query($conn, "SELECT idUser FROM users ORDER BY idUser DESC LIMIT 1");
    $lastId = mysqli_fetch_assoc($last);
    if($lastId && preg_match('/USR(\d+)/', $lastId['idUser'], $m)) {
        $num = (int)$m[1] + 1;
    } else {
        $num = 1;
    }
    $id = 'USR' . str_pad($num, 3, '0', STR_PAD_LEFT);
    $nama = isset($_POST['namaUser']) ? mysqli_real_escape_string($conn, $_POST['namaUser']) : '';
    $nik = isset($_POST['nik']) ? mysqli_real_escape_string($conn, $_POST['nik']) : '';
    $alamat = isset($_POST['alamat']) ? mysqli_real_escape_string($conn, $_POST['alamat']) : '';
    $telepon = isset($_POST['telepon']) ? mysqli_real_escape_string($conn, $_POST['telepon']) : '';
    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $password = isset($_POST['passwordUser']) ? mysqli_real_escape_string($conn, $_POST['passwordUser']) : '';
    $gambar = '';
    $query = "INSERT INTO users (idUser, namaUser, gambar, nik, alamat, telepon, username, passwordUser, jmlSetoran, jmlPenarikan, saldo) 
              VALUES ('$id', '$nama', '$gambar', '$nik', '$alamat', '$telepon', '$username', '$password', 0, 0, 0)";
    $result = mysqli_query($conn, $query);
    if(!$result) {
        echo '<div style="color:red;padding:10px;">Gagal menambah data: '.mysqli_error($conn).'</div>';
        exit;
    }
    echo "<script>window.location='pengguna.php?success=1';</script>";
}

// Proses edit data
if(isset($_POST['mode']) && $_POST['mode'] == 'edit'){
    $id = mysqli_real_escape_string($conn, $_POST['idUser']);
    $nama = mysqli_real_escape_string($conn, $_POST['namaUser']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['passwordUser']);
    
    $gambar_sql = "";
    if(isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] == 0){
        $gambar = uniqid() . '.' . pathinfo($_FILES['fileInput']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['fileInput']['tmp_name'], 'img/user/' . $gambar);
        $gambar_sql = ", gambar='$gambar'";
    }
    
    $query = "UPDATE users SET namaUser='$nama', nik='$nik', alamat='$alamat', telepon='$telepon', 
              username='$username', passwordUser='$password' $gambar_sql WHERE idUser='$id'";
    mysqli_query($conn, $query);
    
    echo "<script>window.location='pengguna.php';</script>";
}

$id = $_SESSION["IdAdmin"];
$biodata = query("SELECT * FROM admins WHERE IdAdmin = '$id'")[0];
$pengguna = query("SELECT * FROM users ORDER BY idUser ASC");
?>

<!doctype html>
<html lang="en">
<head>
    <title>Data Pengguna</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* ===== RESET & BASE ===== */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* ===== PRELOADER ===== */
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

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 260px;
            background: #ffffff;
            padding: 30px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }

        .sidebar-logo img {
            width: 45px;
            height: 45px;
            border-radius: 10px;
        }

        .sidebar-logo-text h3 {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            line-height: 1.3;
        }

        .sidebar-logo-text p {
            font-size: 12px;
            color: #6c757d;
            margin: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .menu-item {
            margin-bottom: 5px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #6c757d;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 14px;
        }

        .menu-link:hover {
            background: #f8f9fa;
            color: #2d5c3e;
        }

        .menu-link.active {
            background: #2d5c3e;
            color: white;
        }

        .menu-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .search-box {
            position: relative;
            width: 350px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 45px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #2d5c3e;
            box-shadow: 0 0 0 3px rgba(45, 92, 62, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .user-profile:hover {
            background: #f8f9fa;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: #2d5c3e;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0;
        }

        .user-info p {
            font-size: 12px;
            color: #6c757d;
            margin: 0;
        }

        /* ===== CONTENT CARD ===== */
        .content-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 25px;
        }

        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .btn-add {
            background: #2d5c3e;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-add:hover {
            background: #1e4129;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 92, 62, 0.3);
        }

        /* ===== TABLE ===== */
        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            font-size: 14px;
        }

        .custom-table thead th {
            background: #f8f9fa;
            font-weight: 600;
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            border: none;
        }

        .custom-table tbody td {
            padding: 15px;
            border-bottom: 1px solid #f1f3f5;
            color: #495057;
            vertical-align: middle;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table tbody tr {
            transition: all 0.2s;
            cursor: pointer;
        }

        .custom-table tbody tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background: #fff3cd;
            color: #856404;
        }

        .btn-edit:hover {
            background: #ffc107;
            color: white;
        }

        .btn-delete {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: white;
        }

        /* ===== MODAL ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
            z-index: 9998;
            animation: fadeIn 0.2s;
        }

        .modal-overlay.active {
            display: block;
        }

        .modal-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 12px;
            padding: 32px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            z-index: 9999;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            animation: slideIn 0.25s ease-out;
        }

        .modal-container.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translate(-50%, -46%);
            }
            to { 
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .modal-header {
            margin-bottom: 28px;
            text-align: center;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #374151;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            background: #ffffff;
        }

        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            border-color: #2d5c3e;
            box-shadow: 0 0 0 3px rgba(45, 92, 62, 0.08);
        }

        .file-upload-wrapper {
            position: relative;
        }

        .file-upload {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 11px 14px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .file-upload:hover {
            border-color: #9ca3af;
        }

        .file-upload:focus-within {
            border-color: #2d5c3e;
            box-shadow: 0 0 0 3px rgba(45, 92, 62, 0.08);
        }

        .file-upload input[type="file"] {
            display: none;
        }

        .file-upload-text {
            color: #9ca3af;
            font-size: 14px;
            flex: 1;
            font-weight: 400;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-upload-text.has-file {
            color: #374151;
            font-weight: 400;
        }

        .file-upload-btn {
            background: white;
            border: 1px solid #d1d5db;
            padding: 6px 14px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
        }

        .btn-cancel {
            flex: 1;
            padding: 11px 20px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-submit {
            flex: 1;
            padding: 11px 20px;
            border: none;
            border-radius: 6px;
            background: #2d5c3e;
            color: white;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background: #234a31;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 76px;
            font-family: 'Inter', sans-serif;
        }

        /* ===== DETAIL PANEL OVERLAY ===== */
        .detail-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 9998;
        }

        .detail-overlay.active {
            display: block;
        }

        /* ===== DETAIL PANEL ===== */
        .detail-panel {
            display: none;
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 900px;
            height: 100vh;
            background: #f8f9fa;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            overflow-y: auto;
            transition: right 0.4s ease-out;
        }

        .detail-panel.active {
            display: block;
            right: 0;
        }

        .detail-header {
            padding: 25px 30px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .detail-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        .btn-close-detail {
            background: none;
            border: none;
            font-size: 20px;
            color: #6c757d;
            cursor: pointer;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-close-detail:hover {
            background: #f8f9fa;
            color: #1a1a1a;
        }

        .detail-content {
            padding: 30px;
        }

        .user-profile-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .user-avatar-large {
            width: 80px;
            height: 80px;
            background: #2d5c3e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 32px;
            flex-shrink: 0;
        }

        .user-profile-info {
            flex: 1;
        }

        .user-name-large {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 5px 0;
        }

        .user-role {
            font-size: 14px;
            color: #6c757d;
            font-weight: 400;
            margin: 0;
        }

        .info-section {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .info-section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 18px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 13px;
            font-weight: 500;
            color: #6c757d;
            margin-bottom: 6px;
            display: block;
        }

        .info-value {
            font-size: 14px;
            color: #1a1a1a;
            padding: 11px 14px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .history-section {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .history-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .history-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px 18px;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }

        .history-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .history-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .history-icon {
            width: 36px;
            height: 36px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d5c3e;
            font-size: 16px;
        }

        .history-card-title {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .history-card-value {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar-logo-text,
            .menu-link span {
                display: none;
            }
            
            .main-content {
                margin-left: 70px;
            }
            
            .search-box {
                width: 200px;
            }
            
            .page-title {
                font-size: 22px;
            }
            
            .detail-panel {
                max-width: 100%;
            }
            
            .history-cards {
                grid-template-columns: 1fr;
            }
            
            .top-bar {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }

            .modal-container {
                width: 95%;
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <!--Pre Loader-->
    <div class="preloader">
        <div class="loading">
            <img src="img/aset/loading.gif" width="80">
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="img/logo/logombs.png" alt="Logo">
            <div class="sidebar-logo-text">
                <h3>Bank Sampah</h3>
                <p>Mugi Berkah Sari</p>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item">
                <a href="admin.php" class="menu-link">
                    <i class="fas fa-th-large"></i>
                    <span>Dasbor</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="sampahAdmin.php" class="menu-link">
                    <i class="fas fa-list"></i>
                    <span>Daftar Sampah</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="transaksiSampah.php" class="menu-link">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transaksi Sampah</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="pengguna.php" class="menu-link active">
                    <i class="fas fa-users"></i>
                    <span>Pengguna</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="menu-item" style="margin-top: 30px;">
                <a href="logout.php" class="menu-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <h1 class="page-title">Daftar Pengguna</h1>
            <div class="d-flex align-items-center gap-3">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari pengguna..." id="searchInput">
                </div>
                <div class="user-profile">
                    <div class="user-avatar">A</div>
                    <div class="user-info">
                        <h4>Admin</h4>
                        <p>Administrator</p>
                    </div>
                    <i class="fas fa-chevron-down" style="color: #6c757d;"></i>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header-custom">
                <div></div>
                <button class="btn-add" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Pengguna
                </button>
            </div>
            <div class="table-responsive">
                <table class="custom-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($pengguna as $row): ?>
                        <tr style="text-align:center; cursor:pointer;" onclick="openDetailPanel(this)"
                            data-nama="<?= htmlspecialchars($row['namaUser']) ?>"
                            data-nik="<?= htmlspecialchars($row['nik']) ?>"
                            data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                            data-telepon="<?= htmlspecialchars($row['telepon']) ?>"
                            data-username="<?= htmlspecialchars($row['username']) ?>"
                            data-saldo="<?= 'Rp. '.number_format($row['saldo'],2,',','.') ?>">

                            <td><?= $i; ?></td>
                            <td>
                                <img src="img/user/<?= htmlspecialchars($row['gambar']) ?>" alt="foto" style="width:40px;height:40px;object-fit:cover;border-radius:50%;border:1.5px solid #e9ecef;" onerror="this.onerror=null;this.src='https://via.placeholder.com/40?text=User'">
                            </td>
                            <td style="text-align:left;"><span style="font-weight:600; color:#222;"><?= htmlspecialchars($row['namaUser']) ?></span></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= 'Rp. '.number_format($row['saldo'],2,',','.') ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-edit" onclick="event.stopPropagation(); openEditModal('<?= $row['idUser']; ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn-action btn-delete" onclick="event.stopPropagation(); deleteUser('<?= $row['idUser']; ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>
    
    <!-- Modal Tambah/Edit Pengguna -->
    <div class="modal-container" id="modalContainer">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">Tambah Pengguna</h2>
        </div>
        
        <form id="penggunaForm" method="POST">
            <input type="hidden" id="formMode" name="mode" value="tambah">
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="namaUser" name="namaUser" placeholder="Masukkan nama lengkap" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap" required></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon" placeholder="Contoh: 081234567890" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" id="passwordUser" name="passwordUser" placeholder="Masukkan password" required>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>

    <!-- Detail Overlay -->
    <div class="detail-overlay" id="detailOverlay" onclick="closeDetailPanel()"></div>
    
    <!-- Detail Panel -->
    <div class="detail-panel" id="detailPanel">
        <div class="detail-header">
            <h2 class="detail-title">Detail Pengguna</h2>
            <button class="btn-close-detail" onclick="closeDetailPanel()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="detail-content">
            <!-- User Profile Section -->
            <div class="user-profile-section">
                <div class="user-avatar-large" id="detailAvatar">G</div>
                <div class="user-profile-info">
                    <h3 class="user-name-large" id="detailName">-</h3>
                    <p class="user-role">Nasabah</p>
                </div>
            </div>
            
            <!-- Detail Information -->
            <div class="info-section">
                <h3 class="info-section-title">Detail Informasi</h3>
                
                <div class="info-item">
                    <label class="info-label">Nama Lengkap</label>
                    <div class="info-value" id="detailFullName">-</div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">NIK</label>
                    <div class="info-value" id="detailNIK">-</div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">Alamat</label>
                    <div class="info-value" id="detailAddress">-</div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">Nomor Telepon</label>
                    <div class="info-value" id="detailPhone">-</div>
                </div>
                
                <div class="info-item">
                    <label class="info-label">Username</label>
                    <div class="info-value" id="detailUsername">-</div>
                </div>
            </div>
            
            <!-- History Section -->
            <div class="history-section">
                <h3 class="info-section-title">Riwayat Pengguna</h3>
                
                <div class="history-cards">
                    <div class="history-card">
                        <div class="history-card-header">
                            <div class="history-icon">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <p class="history-card-title">
                                Jumlah Setoran
                            </p>
                        </div>
                        <h3 class="history-card-value" id="detailJmlSetoran">0</h3>
                    </div>
                    
                    <div class="history-card">
                        <div class="history-card-header">
                            <div class="history-icon">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <p class="history-card-title">
                                Jumlah Penarikan
                            </p>
                        </div>
                        <h3 class="history-card-value" id="detailJmlPenarikan">0</h3>
                    </div>
                    
                    <div class="history-card">
                        <div class="history-card-header">
                            <div class="history-icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <p class="history-card-title">
                                Saldo Saat Ini
                            </p>
                        </div>
                        <h3 class="history-card-value" id="detailSaldo">Rp0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        // Open Detail Panel Function (hanya satu, ambil data dari atribut data-*)
        function openDetailPanel(row) {
            var nama = row.getAttribute('data-nama') || '-';
            document.getElementById('detailFullName').textContent = nama;
            // Juga isi nama besar di atas avatar
            document.getElementById('detailName').textContent = nama;
            document.getElementById('detailNIK').textContent = row.getAttribute('data-nik') || '-';
            document.getElementById('detailAddress').textContent = row.getAttribute('data-alamat') || '-';
            document.getElementById('detailPhone').textContent = row.getAttribute('data-telepon') || '-';
            document.getElementById('detailUsername').textContent = row.getAttribute('data-username') || '-';
            document.getElementById('detailSaldo').textContent = row.getAttribute('data-saldo') || '-';
            document.getElementById('detailOverlay').classList.add('active');
            document.getElementById('detailPanel').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        $(document).ready(function() {
            $(".preloader").fadeOut();
        });
        
        // Open Modal Function
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Pengguna';
            document.getElementById('penggunaForm').reset();
            document.getElementById('formMode').value = 'tambah';
            // Hapus input idUser jika ada
            var idInput = document.getElementById('idUser');
            if (idInput) idInput.remove();
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('modalContainer').classList.add('active');
        }

        function openEditModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Pengguna';
            document.getElementById('formMode').value = 'edit';
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('modalContainer').classList.add('active');

            // Reset form dulu
            document.getElementById('penggunaForm').reset();

            // Ambil data lengkap via AJAX
            fetch('get_user_data.php?idUser=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('namaUser').value = data.namaUser || '';
                    document.getElementById('nik').value = data.nik || '';
                    document.getElementById('alamat').value = data.alamat || '';
                    document.getElementById('telepon').value = data.telepon || '';
                    document.getElementById('username').value = data.username || '';
                    document.getElementById('passwordUser').value = data.passwordUser || '';
                })
                .catch(error => {
                    console.error('Error:', error);
                });

            // Tambahkan input hidden idUser
            var idInput = document.getElementById('idUser');
            if (!idInput) {
                idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.id = 'idUser';
                idInput.name = 'idUser';
                document.getElementById('penggunaForm').prepend(idInput);
            }
            idInput.value = id;
        }
        
        // Close Modal Function
        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('modalContainer').classList.remove('active');
        }
        
        // Delete User Function
        function deleteUser(id) {
            if (confirm('Anda yakin ingin menghapus pengguna ini?')) {
                window.location.href = 'hapus.php?action=delete&id=' + id;
            }
        }
        
        // Close Detail Panel Function
        function closeDetailPanel() {
            document.getElementById('detailOverlay').classList.remove('active');
            document.getElementById('detailPanel').classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Close with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeDetailPanel();
            }
        });
        
        // Prevent closing modal when clicking inside modal
        document.getElementById('modalContainer').addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Search Function
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#dataTable tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
</body>
</html>