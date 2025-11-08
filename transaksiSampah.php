<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'sampah';
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Query transaksi masuk (setoran) limit 5
$setoranLimit = [];
$sqlSetoran = "SELECT s.*, u.namaUser, sa.jenisSampah FROM setoran s JOIN users u ON s.idUser = u.idUser JOIN sampah sa ON s.idSampah = sa.idSampah ORDER BY s.tglSetor DESC LIMIT 5";
$resultSetoran = mysqli_query($conn, $sqlSetoran);
if ($resultSetoran) {
    while ($row = mysqli_fetch_assoc($resultSetoran)) {
        $setoranLimit[] = $row;
    }
}

// Query transaksi keluar (penjualan) limit 5
$penjualanLimit = [];
$sqlPenjualan = "SELECT * FROM penjualan ORDER BY tglPenjualan DESC LIMIT 5";
$resultPenjualan = mysqli_query($conn, $sqlPenjualan);
if ($resultPenjualan) {
    while ($row = mysqli_fetch_assoc($resultPenjualan)) {
        $penjualanLimit[] = $row;
    }
}

// Query semua transaksi masuk (setoran)
$setoranAll = [];
$sqlSetoranAll = "SELECT s.*, u.namaUser, sa.jenisSampah FROM setoran s JOIN users u ON s.idUser = u.idUser JOIN sampah sa ON s.idSampah = sa.idSampah ORDER BY s.tglSetor DESC";
$resultSetoranAll = mysqli_query($conn, $sqlSetoranAll);
if ($resultSetoranAll) {
    while ($row = mysqli_fetch_assoc($resultSetoranAll)) {
        $setoranAll[] = $row;
    }
}

// Query semua transaksi keluar (penjualan)
$penjualanAll = [];
$sqlPenjualanAll = "SELECT * FROM penjualan ORDER BY tglPenjualan DESC";
$resultPenjualanAll = mysqli_query($conn, $sqlPenjualanAll);
if ($resultPenjualanAll) {
    while ($row = mysqli_fetch_assoc($resultPenjualanAll)) {
        $penjualanAll[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Sampah - Bank Sampah</title>
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
            background: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Sidebar */
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
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }
        
        /* Top Bar */
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
        
        /* Header Actions */
        .header-actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }
        
        .btn-add-transaction {
            background: #2d5c3e;
            color: white;
            padding: 12px 24px;
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
        
        .btn-add-transaction:hover {
            background: #1e4129;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 92, 62, 0.3);
        }
        
        /* Transaction Cards */
        .transaction-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 25px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-icon {
            width: 40px;
            height: 40px;
            background: #e8f5e9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d5c3e;
        }
        
        .view-all-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .view-all-link:hover {
            color: #2d5c3e;
        }
        
        /* Table */
        .custom-table {
            width: 100%;
            font-size: 14px;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .custom-table thead th {
            background: #f8f9fa;
            font-weight: 600;
            color: #6c757d;
            font-size: 12px;
            padding: 12px 15px;
            border: none;
            white-space: nowrap;
        }
        
        .custom-table thead th:first-child {
            border-radius: 8px 0 0 0;
        }
        
        .custom-table thead th:last-child {
            border-radius: 0 8px 0 0;
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
        
        .custom-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
        }
        
        .btn-action {
            padding: 5px 12px;
            border-radius: 5px;
            border: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
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
        
        /* Modal Overlay */
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
        
        /* Modal Full Page (Lihat Semua) */
        .modal-full {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 15px;
            padding: 30px;
            width: 95%;
            max-width: 1200px;
            max-height: 90vh;
            overflow-y: auto;
            z-index: 9999;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.25s ease-out;
        }
        
        .modal-full.active {
            display: block;
        }
        
        /* Modal Form (Tambah Transaksi) */
        .modal-form {
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
        
        .modal-form.active {
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
        
        .modal-header-custom {
            margin-bottom: 28px;
            text-align: center;
        }
        
        .modal-title-custom {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }
        
        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 24px;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .modal-close:hover {
            color: #1a1a1a;
        }
        
        /* Form Styles */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
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
        }
        
        .form-control::placeholder {
            color: #9ca3af;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #2d5c3e;
            box-shadow: 0 0 0 3px rgba(45, 92, 62, 0.08);
        }
        
        .form-select {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            color: #9ca3af;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 14 14'%3E%3Cpath fill='%239ca3af' d='M7 10L2 5h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            transition: all 0.2s;
        }
        
        .form-select:focus {
            outline: none;
            border-color: #2d5c3e;
            box-shadow: 0 0 0 3px rgba(45, 92, 62, 0.08);
            color: #374151;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .input-with-icon input {
            padding-left: 40px;
        }
        
        .input-with-icon .search-icon-right {
            position: absolute;
            right: 14px;
            left: auto;
        }
        
        /* Dynamic Sampah Items */
        .sampah-items {
            margin-top: 20px;
        }
        
        .sampah-item {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            position: relative;
        }
        
        .btn-remove-item {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #dc3545;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .btn-add-item {
            background: #e8f5e9;
            color: #2d5c3e;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            margin-top: 12px;
            transition: all 0.3s;
        }
        
        .btn-add-item:hover {
            background: #c8e6c9;
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
        
        /* Responsive */
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
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .modal-full, .modal-form {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
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
                <a href="#" class="menu-link active">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transaksi Sampah</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" class="menu-link">
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
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1 class="page-title">Transaksi</h1>
            
            <div class="d-flex align-items-center gap-3">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari...">
                </div>
                
                <div class="user-profile">
                    <div class="user-avatar">G</div>
                    <div class="user-info">
                        <h4>Ganik</h4>
                        <p>Admin</p>
                    </div>
                    <i class="fas fa-chevron-down" style="color: #6c757d;"></i>
                </div>
            </div>
        </div>
        
        <!-- Header Actions -->
        <div class="header-actions">
            <button class="btn-add-transaction" onclick="openAddTransactionModal()">
                <i class="fas fa-plus"></i>
                Tambah Transaksi
            </button>
        </div>
        
        <!-- Transaksi Masuk -->
        <div class="transaction-section">
            <div class="section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="section-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <h3 class="section-title">Transaksi Masuk</h3>
                </div>
                <a href="#" class="view-all-link" onclick="openViewAllModal('masuk')">Lihat Semua →</a>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Nasabah</th>
                            <th>Jenis Sampah</th>
                            <th>Jumlah (Kg)</th>
                            <th>Harga (Kg)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($setoranLimit as $row): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['tglSetor'])) ?></td>
                            <td><?= htmlspecialchars($row['namaUser']) ?></td>
                            <td><?= htmlspecialchars($row['jenisSampah']) ?></td>
                            <td><?= htmlspecialchars($row['berat']) ?></td>
                            <td><?= isset($row['harga']) ? number_format($row['harga'],0,',','.') : '-' ?></td>
                            <td><?= number_format($row['total'],0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Transaksi Keluar -->
        <div class="transaction-section">
            <div class="section-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="section-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <h3 class="section-title">Transaksi Keluar</h3>
                </div>
                <a href="#" class="view-all-link" onclick="openViewAllModal('keluar')">Lihat Semua →</a>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pengepul</th>
                            <th>Jumlah (Kg)</th>
                            <th>Harga (Kg)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($penjualanLimit as $row): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['tglPenjualan'])) ?></td>
                            <td><?= htmlspecialchars($row['namaPembeli']) ?></td>
                            <td><?= htmlspecialchars($row['berat']) ?></td>
                            <td><?= isset($row['harga']) ? number_format($row['harga'],0,',','.') : (isset($row['hargaPerKg']) ? number_format($row['hargaPerKg'],0,',','.') : '-') ?></td>
                            <td><?= number_format($row['totalPendapatan'],0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay" onclick="closeAllModals()"></div>
        <!-- Modal View All (Lihat Semua) -->
        <div class="modal-full" id="modalViewAll" style="display:none;">
            <button class="modal-close" onclick="closeAllModals()">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-header-custom">
                <h2 class="modal-title-custom" id="viewAllTitle">Transaksi <span id="jenisTransaksiTitle">Masuk</span></h2>
            </div>
            <div class="table-responsive" id="viewAllMasuk" style="display:none;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Nasabah</th>
                            <th>Jenis Sampah</th>
                            <th>Jumlah (Kg)</th>
                            <th>Harga (Kg)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($setoranAll as $row): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['tglSetor'])) ?></td>
                            <td><?= htmlspecialchars($row['namaUser']) ?></td>
                            <td><?= htmlspecialchars($row['jenisSampah']) ?></td>
                            <td><?= htmlspecialchars($row['berat']) ?></td>
                            <td><?= number_format($row['harga'],0,',','.') ?></td>
                            <td><?= number_format($row['total'],0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive" id="viewAllKeluar" style="display:none;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pengepul</th>
                            <th>Jumlah (Kg)</th>
                            <th>Harga (Kg)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($penjualanAll as $row): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['tglPenjualan'])) ?></td>
                            <td><?= htmlspecialchars($row['namaPembeli']) ?></td>
                            <td><?= htmlspecialchars($row['berat']) ?></td>
                            <td><?= number_format($row['harga'],0,',','.') ?></td>
                            <td><?= number_format($row['totalPendapatan'],0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Tambah Transaksi -->
        <div class="modal-form" id="modalAddTransaction" style="display:none;">
            <button class="modal-close" onclick="closeAllModals()">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-header-custom">
                <h2 class="modal-title-custom">Tambah Transaksi</h2>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="jenisTransaksi" class="form-label">Jenis Transaksi</label>
                    <select class="form-select" id="jenisTransaksi" name="jenisTransaksi" onchange="toggleFormTransaksi()" required>
                        <option value="">Pilih Jenis</option>
                        <option value="masuk">Transaksi Masuk</option>
                        <option value="keluar">Transaksi Keluar</option>
                    </select>
                </div>
                <div id="formTransaksiMasuk" style="display:none;">
                    <div class="form-group mb-2">
                        <label class="form-label">Nama Nasabah</label>
                        <select name="idUser" class="form-select" required>
                            <option value="">Pilih Nasabah</option>
                            <?php foreach($users as $u): ?>
                            <option value="<?= $u['idUser'] ?>"><?= htmlspecialchars($u['namaUser']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Jenis Sampah</label>
                        <select name="idSampah" class="form-select" required>
                            <option value="">Pilih Jenis Sampah</option>
                            <?php foreach($sampahList as $s): ?>
                            <option value="<?= $s['idSampah'] ?>"><?= htmlspecialchars($s['jenisSampah']) ?> - <?= htmlspecialchars($s['namaSampah']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Jumlah (Kg)</label>
                        <input type="number" name="berat" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Harga (Kg)</label>
                        <input type="number" name="harga" class="form-control" min="0" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tglSetor" class="form-control" required>
                    </div>
                </div>
                <div id="formTransaksiKeluar" style="display:none;">
                    <div class="form-group mb-2">
                        <label class="form-label">Nama Pengepul</label>
                        <input type="text" name="namaPembeli" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Jumlah (Kg)</label>
                        <input type="number" name="beratKeluar" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Harga (Kg)</label>
                        <input type="number" name="hargaKeluar" class="form-control" min="0" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tglPenjualan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeAllModals()">Batal</button>
                    <button type="submit" class="btn-submit" name="submitTransaksi">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openViewAllModal(jenis) {
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('modalViewAll').style.display = 'block';
    if(jenis === 'masuk') {
        document.getElementById('viewAllMasuk').style.display = 'block';
        document.getElementById('viewAllKeluar').style.display = 'none';
        document.getElementById('jenisTransaksiTitle').innerText = 'Masuk';
    } else {
        document.getElementById('viewAllMasuk').style.display = 'none';
        document.getElementById('viewAllKeluar').style.display = 'block';
        document.getElementById('jenisTransaksiTitle').innerText = 'Keluar';
    }
}

function openAddTransactionModal() {
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('modalAddTransaction').style.display = 'block';
}

function closeAllModals() {
    document.getElementById('modalOverlay').style.display = 'none';
    document.getElementById('modalViewAll').style.display = 'none';
    document.getElementById('modalAddTransaction').style.display = 'none';
}

function toggleFormTransaksi() {
    var jenis = document.getElementById('jenisTransaksi').value;
    document.getElementById('formTransaksiMasuk').style.display = (jenis === 'masuk') ? 'block' : 'none';
    document.getElementById('formTransaksiKeluar').style.display = (jenis === 'keluar') ? 'block' : 'none';
}
    </script>
</body>
</html>