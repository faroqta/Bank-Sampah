<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require 'functions.php';
// Pastikan fungsi query di functions.php menggunakan $conn
// Query statistik utama
$jumlahUser = query("SELECT COUNT(*) as total FROM users")[0]['total'];
$totalSaldoNasabah = query("SELECT SUM(saldo) as total FROM users")[0]['total'];
$totalSaldoKas = query("SELECT saldoBank FROM bank WHERE idBank = 1")[0]['saldoBank'];
$sampahBersih = query("SELECT SUM(jumlah) as total FROM sampah WHERE jenisSampah='Bersih'")[0]['total'];
// Query setoran sampah
$setoran = query("SELECT s.tglSetor, u.namaUser, sa.jenisSampah, s.berat, sa.harga, s.total FROM setoran s JOIN users u ON s.idUser=u.idUser JOIN sampah sa ON s.idSampah=sa.idSampah ORDER BY s.tglSetor DESC LIMIT 6");
// Query riwayat penarikan
$penarikan = query("SELECT p.tglTarik, u.namaUser, p.jumlahTarik, u.saldo FROM penarikan p JOIN users u ON p.idUser=u.idUser ORDER BY p.tglTarik DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bank Sampah</title>
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
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--card-color);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .stat-icon.green { background: #2d5c3e; }
        .stat-icon.blue { background: #0d6efd; }
        .stat-icon.orange { background: #fd7e14; }
        .stat-icon.red { background: #dc3545; }
        
        .info-icon {
            color: #dee2e6;
            cursor: help;
        }
        
        .stat-title {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
            margin: 0 0 8px 0;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }
        
        /* Content Cards */
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
        
        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .view-all-link {
            color: #2d5c3e;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .view-all-link:hover {
            color: #1e4129;
        }
        
        /* Table */
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
        }
        
        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .custom-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* Chart Container */
        .chart-container {
            position: relative;
            height: 350px;
            margin-top: 20px;
        }
        
        .chart-legend {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
        
        .legend-color.kotor { background: #d4a950; }
        .legend-color.bersih { background: #c0c0c0; }
        .legend-color.selisih { background: #8b4513; }
        
        /* Export Button */
        .btn-export {
            background: white;
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-export:hover {
            background: #f8f9fa;
            border-color: #2d5c3e;
            color: #2d5c3e;
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
            
            .search-box {
                width: 200px;
            }
            
            .page-title {
                font-size: 22px;
            }
        }
        
        /* Simple Bar Chart */
        .bar-chart {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 300px;
            padding: 20px 0;
            gap: 15px;
        }
        
        .bar-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .bars {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 8px;
            height: 250px;
            width: 100%;
        }
        
        .bar {
            width: 30%;
            border-radius: 6px 6px 0 0;
            transition: all 0.3s;
            position: relative;
            cursor: pointer;
        }
        
        .bar:hover {
            opacity: 0.8;
        }
        
        .bar.kotor { background: #d4a950; }
        .bar.bersih { background: #c0c0c0; }
        
        .bar-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .axis-y {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 11px;
            color: #adb5bd;
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
                <a href="#" class="menu-link active">
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
                <a href="pengguna.php" class="menu-link">
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
            <h1 class="page-title">Dasbor</h1>
            
            <div class="d-flex align-items-center gap-3">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari...">
                </div>
                
                <button class="btn-export">
                    <i class="fas fa-download"></i>
                    Ekspor
                </button>
                
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
        
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card" style="--card-color: #2d5c3e;">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <i class="fas fa-users"></i>
                    </div>
                    <i class="fas fa-info-circle info-icon"></i>
                </div>
                <p class="stat-title">Total Nasabah</p>
                <h2 class="stat-value"><?php echo $jumlahUser; ?> Orang</h2>
            </div>
            
            <div class="stat-card" style="--card-color: #0d6efd;">
                <div class="stat-header">
                    <div class="stat-icon blue">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <i class="fas fa-info-circle info-icon"></i>
                </div>
                <p class="stat-title">Total Saldo Nasabah</p>
                <h2 class="stat-value">Rp<?php echo number_format($totalSaldoNasabah,0,',','.'); ?></h2>
            </div>
            
            <div class="stat-card" style="--card-color: #fd7e14;">
                <div class="stat-header">
                    <div class="stat-icon orange">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <i class="fas fa-info-circle info-icon"></i>
                </div>
                <p class="stat-title">Total Saldo Kas</p>
                <h2 class="stat-value">Rp<?php echo number_format($totalSaldoKas,0,',','.'); ?></h2>
            </div>
            
            <div class="stat-card" style="--card-color: #dc3545;">
                <div class="stat-header">
                    <div class="stat-icon red">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <i class="fas fa-info-circle info-icon"></i>
                </div>
                <p class="stat-title">Sampah Bersih</p>
                <h2 class="stat-value"><?php echo $sampahBersih; ?> Kg</h2>
            </div>
        </div>
        
        <!-- Content Grid -->
        <div class="row">
            <!-- Setoran Sampah Table -->
            <div class="col-lg-8 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h3 class="card-title">
                            <i class="fas fa-list-alt"></i>
                            Setoran Sampah
                        </h3>
                        <a href="#" class="view-all-link">Lihat Semua →</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jenis Sampah</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Harga/Kg (Rp)</th>
                                    <th>Total (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($setoran as $row): ?>
                                <tr>
                                    <td><?php echo $row['tglSetor']; ?></td>
                                    <td><?php echo $row['namaUser']; ?></td>
                                    <td><?php echo $row['jenisSampah']; ?></td>
                                    <td><?php echo $row['berat']; ?></td>
                                    <td><?php echo number_format($row['harga'],0,',','.'); ?></td>
                                    <td><?php echo number_format($row['total'],0,',','.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Chart -->
            <div class="col-lg-4 mb-4">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie"></i>
                            Sampah Kotor dan Sampah Bersih
                        </h3>
                    </div>
                    
                    <div class="chart-container">
                        <div class="bar-chart">
                            <div class="bar-group">
                                <div class="bars">
                                    <div class="bar kotor" style="height: 87%;"></div>
                                    <div class="bar bersih" style="height: 52%;"></div>
                                </div>
                                <span class="bar-label">0 Kg</span>
                            </div>
                            <div class="bar-group">
                                <div class="bars">
                                    <div class="bar kotor" style="height: 0%;"></div>
                                    <div class="bar bersih" style="height: 0%;"></div>
                                </div>
                                <span class="bar-label">50 Kg</span>
                            </div>
                            <div class="bar-group">
                                <div class="bars">
                                    <div class="bar kotor" style="height: 0%;"></div>
                                    <div class="bar bersih" style="height: 0%;"></div>
                                </div>
                                <span class="bar-label">100 Kg</span>
                            </div>
                            <div class="bar-group">
                                <div class="bars">
                                    <div class="bar kotor" style="height: 0%;"></div>
                                    <div class="bar bersih" style="height: 0%;"></div>
                                </div>
                                <span class="bar-label">150 Kg</span>
                            </div>
                            <div class="bar-group">
                                <div class="bars">
                                    <div class="bar kotor" style="height: 0%;"></div>
                                    <div class="bar bersih" style="height: 0%;"></div>
                                </div>
                                <span class="bar-label">200 Kg</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color kotor"></div>
                            <span>Sampah Kotor</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color bersih"></div>
                            <span>Sampah Bersih</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color selisih"></div>
                            <span>Selisih</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Penarikan Saldo -->
        <div class="content-card">
            <div class="card-header-custom">
                <h3 class="card-title">
                    <i class="fas fa-history"></i>
                    Riwayat Penarikan Saldo
                </h3>
                <a href="#" class="view-all-link">Lihat Semua →</a>
            </div>
            
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Nasabah</th>
                            <th>Jumlah Ditarik (Rp)</th>
                            <th>Sisa Saldo Nasabah (Rp)</th>
                            <th>Sisa Saldo Kas (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($penarikan as $row): ?>
                        <tr>
                               <td><?php echo $row['tglTarik']; ?></td>
                            <td><?php echo $row['namaUser']; ?></td>
                            <td><?php echo number_format($row['jumlahTarik'],0,',','.'); ?></td>
                            <td><?php echo number_format($row['saldo'],0,',','.'); ?></td>
                            <td><?php echo number_format($totalSaldoKas,0,',','.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>