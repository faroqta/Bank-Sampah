<?php
require 'functions.php';
// Proses tambah data
if(isset($_POST['mode']) && $_POST['mode'] == 'tambah'){
    // Generate ID otomatis
    $last = mysqli_query($conn, "SELECT idSampah FROM sampah ORDER BY idSampah DESC LIMIT 1");
    $lastId = mysqli_fetch_assoc($last);
    if($lastId && preg_match('/SMP(\d+)/', $lastId['idSampah'], $m)) {
        $num = (int)$m[1] + 1;
    } else {
        $num = 1;
    }
    $id = 'SMP' . str_pad($num, 3, '0', STR_PAD_LEFT);
    $jenis = $_POST['jenisSampah'];
    $nama = $_POST['namaSampah'];
    $harga = $_POST['hargaSatuan'];
    $keterangan = $_POST['keterangan'];
    $gambar = '';
    if(isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] == 0){
        $gambar = $_FILES['fileInput']['name'];
        move_uploaded_file($_FILES['fileInput']['tmp_name'], 'img/sampah/' . $gambar);
    }
    $query = "INSERT INTO sampah (idSampah, jenisSampah, namaSampah, satuan, harga, gambar, deskripsi) VALUES ('$id', '$jenis', '$nama', 'KG', '$harga', '$gambar', '$keterangan')";
    $result = mysqli_query($conn, $query);
    if(!$result) {
        echo '<div style="color:red;padding:10px;">Gagal menambah data: '.mysqli_error($conn).'</div>';
        exit;
    }
    echo "<script>window.location='sampahAdmin.php?success=1';</script>";
}
// Proses edit data
if(isset($_POST['mode']) && $_POST['mode'] == 'edit'){
    $id = $_POST['idSampah'];
    $jenis = $_POST['jenisSampah'];
    $nama = $_POST['namaSampah'];
    $harga = $_POST['hargaSatuan'];
    $keterangan = $_POST['keterangan'];
    $gambar = '';
    if(isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] == 0){
        $gambar = $_FILES['fileInput']['name'];
        move_uploaded_file($_FILES['fileInput']['tmp_name'], 'img/sampah/' . $gambar);
        $gambar_sql = ", gambar='$gambar'";
    } else {
        $gambar_sql = "";
    }
    mysqli_query($conn, "UPDATE sampah SET jenisSampah='$jenis', namaSampah='$nama', satuan='KG', harga='$harga', deskripsi='$keterangan' $gambar_sql WHERE idSampah='$id'");
    echo "<script>window.location='sampahAdmin.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Sampah - Bank Sampah</title>
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
        
        /* Content Card */
        .content-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        
        .card-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .filter-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .filter-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .filter-select {
            padding: 8px 35px 8px 15px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
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
            font-size: 13px;
            padding: 15px;
            border: none;
            position: relative;
            cursor: pointer;
        }
        
        .custom-table thead th:first-child {
            border-radius: 10px 0 0 0;
        }
        
        .custom-table thead th:last-child {
            border-radius: 0 10px 0 0;
        }
        
        .custom-table thead th i {
            margin-left: 5px;
            font-size: 12px;
        }
        
        .custom-table tbody td {
            padding: 18px 15px;
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
        
        .trash-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 8px;
            background: #f8f9fa;
            padding: 8px;
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
        
        /* Modal */
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
            max-width: 520px;
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
            font-weight: 400;
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
        
        .form-select option {
            color: #374151;
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
            
            .modal-container {
                width: 95%;
                padding: 25px;
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
                <a href="#" class="menu-link active">
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
                <a href="#" class="menu-link">
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
            <h1 class="page-title">Daftar Sampah</h1>
            
            <div class="d-flex align-items-center gap-3">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari..." id="searchInput">
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
        
        <!-- Content Card -->
        <div class="content-card">
            <div class="card-header-custom" style="display: flex; justify-content: space-between; align-items: center;">
                <div></div>
                <button class="btn-add" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Daftar Sampah
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="custom-table" id="dataTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Jenis Sampah <i class="fas fa-sort"></i></th>
                            <th>Nama Sampah <i class="fas fa-sort"></i></th>
                            <th>Harga/Kg (Rp) <i class="fas fa-sort"></i></th>
                            <th>Keterangan <i class="fas fa-sort"></i></th>
                            <th>Gambar <i class="fas fa-sort"></i></th>
                            <th>Aksi <i class="fas fa-sort"></i></th>
                        </tr>
                    </thead>
                    <tbody>
<?php
$sampah = query("SELECT * FROM sampah ORDER BY idSampah DESC");
$no = 1;
foreach($sampah as $row):
?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($row['jenisSampah']); ?></td>
        <td><?= htmlspecialchars($row['namaSampah']); ?></td>
            <td>Rp<?= number_format($row['harga'], 0, '', '.'); ?></td>
        <td><?= htmlspecialchars($row['deskripsi']); ?></td>
        <td>
            <img src="img/sampah/<?= htmlspecialchars($row['gambar']); ?>" alt="<?= htmlspecialchars($row['namaSampah']); ?>" class="trash-img">
        </td>
        <td>
            <div class="action-buttons">
                <button class="btn-action btn-edit" onclick="openEditModal('<?= $row['idSampah']; ?>')">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn-action btn-delete" onclick="deleteItem('<?= $row['idSampah']; ?>')">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </td>
    </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal Overlay -->
    <div class="modal-overlay" id="modalOverlay" onclick="closeModal()"></div>
    
    <!-- Modal Tambah/Edit Sampah -->
    <div class="modal-container" id="modalContainer">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">Tambah Data Sampah</h2>
        </div>
        
        <form id="sampahForm" method="POST" enctype="multipart/form-data">
                <!-- input idSampah hanya untuk edit, diisi via JS saat edit -->
            <input type="hidden" id="formMode" name="mode" value="tambah">
            <div class="form-group">
                <label class="form-label">Jenis Sampah</label>
                <select class="form-select" id="jenisSampah" name="jenisSampah" required>
                    <option value="">Pilih Jenis Sampah</option>
                    <option value="Organik">Organik</option>
                    <option value="Anorganik">Anorganik</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Nama Sampah</label>
                <input type="text" class="form-control" id="namaSampah" name="namaSampah" placeholder="Ketik nama di sini..." required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Harga Satuan</label>
                <input type="number" class="form-control" id="hargaSatuan" name="hargaSatuan" placeholder="Ketik harga satuan di sini..." required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Gambar</label>
                <div class="file-upload-wrapper">
                    <div class="file-upload" onclick="document.getElementById('fileInput').click()">
                        <span class="file-upload-text" id="fileText">Tidak ada file yang dipilih</span>
                        <span class="file-upload-btn">Pilih File</span>
                    </div>
                    <input type="file" id="fileInput" name="fileInput" accept="image/*">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Ketik keterangan di sini..." required></textarea>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-submit">Simpan</button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Modal Functions
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Data Sampah';
            document.getElementById('sampahForm').reset();
            document.getElementById('formMode').value = 'tambah';
            document.getElementById('sampahForm').setAttribute('data-mode', 'tambah');
            // Hapus input idSampah jika ada
            var idInput = document.getElementById('idSampah');
            if (idInput) idInput.remove();
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('modalContainer').classList.add('active');
        }
        function openEditModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Data Sampah';
            document.getElementById('formMode').value = 'edit';
            document.getElementById('sampahForm').setAttribute('data-mode', 'edit');
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('modalContainer').classList.add('active');
            var row = document.querySelector('button[onclick="openEditModal(\'' + id + '\')"]').closest('tr');
            if(row) {
                document.getElementById('jenisSampah').value = row.children[1].textContent.trim();
                document.getElementById('namaSampah').value = row.children[2].textContent.trim();
                document.getElementById('hargaSatuan').value = row.children[3].textContent.replace(/[^\d]/g, '');
                document.getElementById('keterangan').value = row.children[4].textContent.trim();
                // Tambahkan input hidden idSampah jika belum ada
                var idInput = document.getElementById('idSampah');
                if (!idInput) {
                    idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.id = 'idSampah';
                    idInput.name = 'idSampah';
                    document.getElementById('sampahForm').prepend(idInput);
                }
                idInput.value = id;
            }
        }
        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('modalContainer').classList.remove('active');
        }
        // // Delete Function
        // Ensure $conn is available if this script is inline, but better to use a separate file.

        function deleteItem(id) {
            if (confirm('Anda yakin ingin menghapus data ini?')) {
                // Use the fetch API to send the request to a dedicated PHP file
                fetch('hapussampah.php?idSampah=' + id, {
                    method: 'GET' // Use GET for simplicity, POST is generally better for deletes
                })
                .then(response => {
                    // Check if the request was successful
                    if (response.ok) {
                        alert('Data berhasil dihapus!');
                        // Automatically refresh the page to show the updated data
                        window.location.reload(); 
                    } else {
                        alert('Gagal menghapus data. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan jaringan.');
                });
            }
        }

        // Form Submit
        // Tidak perlu JS submit logic, biarkan form submit normal
        // File Upload Text
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Tidak ada file yang dipilih';
            const fileTextElement = document.getElementById('fileText');
            fileTextElement.textContent = fileName;
            if (e.target.files[0]) {
                fileTextElement.classList.add('has-file');
            } else {
                fileTextElement.classList.remove('has-file');
            }
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
        // Close modal when clicking ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        // Prevent closing modal when clicking inside modal
        document.getElementById('modalContainer').addEventListener('click', function(e) {
            e.stopPropagation();
        });
        // Event listener for button
        document.addEventListener('DOMContentLoaded', function() {
            var btnAdd = document.querySelector('.btn-add');
            if (btnAdd) {
                btnAdd.addEventListener('click', function(e) {
                    e.preventDefault();
                    openModal();
                });
            }
        });
    </script>
    <script>
        // Modal Functions
        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Data Sampah';
            document.getElementById('sampahForm').reset();
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('modalContainer').classList.add('active');
        }
        
        function openEditModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Data Sampah';
            // Load data untuk edit
            document.getElementById('modalOverlay').classList.add('active');
            document.getElementById('modalContainer').classList.add('active');
            
            // Simulasi load data
            if (id === 1) {
                document.getElementById('jenisSampah').value = 'Anorganik';
                document.getElementById('namaSampah').value = 'Plastik';
                document.getElementById('hargaSatuan').value = '600';
                document.getElementById('keterangan').value = 'Semua jenis plastik';
            }
        }
        
        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.getElementById('modalContainer').classList.remove('active');
        }
        
        // Delete Function
        // function deleteItem(id) {
        //     if (confirm('Anda yakin ingin menghapus data ini?')) {
        //         // Add delete logic here
        //         mysqli_query($conn, "DELETE FROM sampah WHERE idSampah='$id'");
        //         alert('Data berhasil dihapus!');
        //     }
        // }
        
        // Form Submit
        document.getElementById('sampahForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Data berhasil disimpan!');
            closeModal();
        });
        
        // File Upload Text
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Tidak ada file yang dipilih';
            const fileTextElement = document.getElementById('fileText');
            fileTextElement.textContent = fileName;
            
            if (e.target.files[0]) {
                fileTextElement.classList.add('has-file');
            } else {
                fileTextElement.classList.remove('has-file');
            }
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
        
        // Close modal when clicking ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // Prevent closing modal when clicking inside modal
        document.getElementById('modalContainer').addEventListener('click', function(e) {
            e.stopPropagation();
        });