<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="img/logo/logombs.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah Mugi Berkah Sari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }
        
        /* Navbar */
        .navbar {
            background: #fff !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: #2d5c3e !important;
            font-size: 1.1rem;
        }
        
        .navbar-brand img {
            width: 45px;
            height: 45px;
        }
        
        .nav-link {
            color: #555 !important;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s;
            font-size: 0.85rem;
        }
        
        .nav-link:hover {
            color: #2d5c3e !important;
        }
        
        .btn-login {
            border: 2px solid #e8f5e9;
            color: #2d5c3e;
            padding: 8px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-login:hover, .btn-login:focus {
            background: #e8f5e9;
            color: #2d5c3e;
            outline: none;
            text-decoration: none;
        }
        
        .btn-register {
            background: #2d5c3e;
            color: white;
            padding: 8px 25px;
            border-radius: 8px;
            font-weight: 600;
            margin-left: 10px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-register:hover, .btn-register:focus {
            background: #1e4129;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(45, 92, 62, 0.3);
            outline: none;
            text-decoration: none;
        }
        
        /* Hero Section */
        .hero-section {
            padding: 100px 0 80px;
            background: linear-gradient(135deg, #f8fef9 0%, #ffffff 100%);
            min-height: 90vh;
            display: flex;
            align-items: center;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: #1a1a1a;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        
        .hero-title .highlight {
            color: #2d5c3e;
            position: relative;
            display: inline-block;
        }
        
        .hero-title .highlight::after {
            content: '';
            position: absolute;
            bottom: 10px;
            left: 0;
            width: 100%;
            height: 15px;
            background: rgba(45, 92, 62, 0.2);
            z-index: -1;
        }
        
        .hero-description {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
            margin-bottom: 2rem;
            max-width: 500px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
        }
        
        .btn-primary-custom {
            background: #2d5c3e;
            color: white;
            padding: 15px 35px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .btn-primary-custom:hover {
            background: #1e4129;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(45, 92, 62, 0.3);
        }
        
        .btn-secondary-custom {
            background: transparent;
            color: #2d5c3e;
            padding: 15px 35px;
            border-radius: 12px;
            font-weight: 600;
            border: 2px solid #2d5c3e;
            transition: all 0.3s;
        }
        
        .btn-secondary-custom:hover {
            background: #2d5c3e;
            color: white;
        }
        
        .hero-image {
            position: relative;
            animation: float 3s ease-in-out infinite;
        }
        
        .hero-image img {
            max-width: 100%;
            height: auto;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        /* Steps Section */
        .steps-section {
            padding: 80px 0;
            background: white;
            margin-top: 20px;
        }
        
        .steps-section .row {
            row-gap: 25px;
        }
        
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .section-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 60px;
            font-size: 1.1rem;
        }
        
        .step-card {
            background: linear-gradient(135deg, #e8f5e9 0%, #f1f8f4 100%);
            border-radius: 20px;
            padding: 40px 30px;
            margin-bottom: 30px;
            transition: all 0.3s;
            border: 2px solid transparent;
            height: 100%;
        }
        
        .step-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(45, 92, 62, 0.15);
            border-color: #2d5c3e;
        }
        
        .step-number {
            font-size: 3rem;
            font-weight: 800;
            color: #2d5c3e;
            margin-bottom: 15px;
            opacity: 0.3;
        }
        
        .step-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 15px;
        }
        
        .step-description {
            color: #666;
            line-height: 1.7;
            margin-bottom: 20px;
        }
        
        .step-link {
            color: #2d5c3e;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: gap 0.3s;
        }
        
        .step-link:hover {
            gap: 15px;
        }
        
        /* Map Section */
        .map-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8fef9 0%, #ffffff 100%);
        }
        
        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-top: 40px;
        }
        
        .map-container iframe {
            width: 100%;
            height: 450px;
            border: none;
        }
        
        /* Footer */
        .footer {
            background: #1e4129;
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .footer-logo img {
            width: 45px;
            height: 45px;
        }
        
        .footer-title {
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .footer-text {
            color: rgba(255,255,255,0.8);
            line-height: 1.8;
        }
        
        .footer h5 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .footer-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: all 0.3s;
        }
        
        .footer-link:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-icons a {
            width: 45px;
            height: 45px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
            outline: none;
            text-decoration: none;
        }
        
        .social-icons a:hover {
            background: #2d5c3e;
            transform: translateY(-5px);
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 40px;
            padding-top: 30px;
            text-align: center;
            color: rgba(255,255,255,0.6);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-section {
                padding: 60px 0;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="img\logo\logombs.png" alt="Logo">
                <span>Bank Sampah<br>Mugi Berkah Sari</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Cara Bergabung</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Informasi Lokasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Kontak Kami</a></li>
                    <li class="nav-item">
                        <a href="admin.php" class="btn-login">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="btn-register ms-2">Registrasi</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">
                        Dari Rumah Kita,<br>
                        Untuk <span class="highlight">Lingkungan</span> Kita
                    </h1>
                    <p class="hero-description">
                        Yuk, kelola sampah bersama Bank Sampah Mugi Berkah Sari! Bersama kami, kita bisa mengubah kebiasaan lingkungan, menambah penghasilan keluarga, dan menciptakan lingkungan yang lebih sehat dan asri 🌱
                    </p>
                    <div class="hero-buttons">
                        <button class="btn-primary-custom">
                            Gabung Sekarang! <i class="fas fa-arrow-right"></i>
                        </button>
                        <button class="btn-secondary-custom">
                            Pelajari Cara Kerjanya
                        </button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <svg width="500" height="400" viewBox="0 0 500 400" xmlns="http://www.w3.org/2000/svg">
                            <!-- Ilustrasi orang dengan tempat sampah -->
                            <!-- Laki-laki -->
                            <ellipse cx="180" cy="380" rx="40" ry="8" fill="#e0e0e0"/>
                            <rect x="165" y="280" width="30" height="100" rx="15" fill="#4CAF50"/>
                            <circle cx="180" cy="250" r="30" fill="#FFD54F"/>
                            <path d="M150 250 Q180 260 210 250" stroke="#333" stroke-width="2" fill="none"/>
                            <circle cx="170" cy="245" r="3" fill="#333"/>
                            <circle cx="190" cy="245" r="3" fill="#333"/>
                            <path d="M165 270 L150 320" stroke="#FFD54F" stroke-width="8" stroke-linecap="round"/>
                            <path d="M195 270 L180 310" stroke="#FFD54F" stroke-width="8" stroke-linecap="round"/>
                            <path d="M175 310 L160 320 L170 330" stroke="#333" stroke-width="6" stroke-linecap="round" fill="none"/>
                            
                            <!-- Perempuan -->
                            <ellipse cx="320" cy="380" rx="40" ry="8" fill="#e0e0e0"/>
                            <rect x="305" y="280" width="30" height="100" rx="15" fill="#FF8A80"/>
                            <circle cx="320" cy="250" r="30" fill="#FFD54F"/>
                            <path d="M305 250 Q295 230 300 220" stroke="#8B4513" stroke-width="4" fill="none"/>
                            <path d="M335 250 Q345 230 340 220" stroke="#8B4513" stroke-width="4" fill="none"/>
                            <circle cx="310" cy="245" r="3" fill="#333"/>
                            <circle cx="330" cy="245" r="3" fill="#333"/>
                            <path d="M310 255 Q320 260 330 255" stroke="#333" stroke-width="2" fill="none"/>
                            <path d="M305 270 L290 320" stroke="#FF8A80" stroke-width="8" stroke-linecap="round"/>
                            <path d="M335 270 L350 310" stroke="#FF8A80" stroke-width="8" stroke-linecap="round"/>
                            
                            <!-- Tempat sampah hijau -->
                            <rect x="380" y="280" width="80" height="100" rx="10" fill="#66BB6A"/>
                            <rect x="385" y="275" width="70" height="15" rx="5" fill="#4CAF50"/>
                            <circle cx="420" cy="330" r="25" fill="white"/>
                            <path d="M410 330 L415 335 L430 320" stroke="#66BB6A" stroke-width="4" fill="none" stroke-linecap="round"/>
                            
                            <!-- Tempat sampah kuning -->
                            <rect x="240" y="300" width="60" height="80" rx="8" fill="#FFD54F"/>
                            <rect x="245" y="295" width="50" height="12" rx="4" fill="#FFC107"/>
                            
                            <!-- Tempat sampah merah -->
                            <rect x="450" y="310" width="50" height="70" rx="8" fill="#EF5350"/>
                            <rect x="455" y="305" width="40" height="10" rx="3" fill="#E53935"/>
                            
                            <!-- Botol plastik di tangan perempuan -->
                            <rect x="345" y="295" width="15" height="25" rx="2" fill="#81D4FA"/>
                            <rect x="345" y="292" width="15" height="5" rx="1" fill="#29B6F6"/>
                            
                            <!-- Kantong plastik di tangan laki-laki -->
                            <path d="M155 315 Q145 325 155 330 L150 340 Q155 345 160 340 L165 330 Q175 325 165 315 Z" fill="#666" opacity="0.6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="steps-section">
        <div class="container">
            <h2 class="section-title">Cara Bergabung Dengan Program Bank Sampah</h2>
            <p class="section-subtitle">Ikuti langkah-langkah mudah berikut untuk bergabung dengan program bank sampah</p>
            
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">01</div>
                        <h3 class="step-title">Tahap 1</h3>
                        <p class="step-description">Lakukan pendaftaran sebagai nasabah Bank Sampah. Masyarakat yang ingin menyetorkan sampah harus mendaftar terlebih dahulu untuk mendapatkan akun dan buku tabungan sampah.</p>
                        <a href="#" class="step-link">Learn more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">02</div>
                        <h3 class="step-title">Tahap 2</h3>
                        <p class="step-description">Lakukan pemilahan sampah di rumah. Sampah harus dipilah berdasarkan jenis-jenisnya seperti plastik, kertas, logam, dan kaca untuk memudahkan proses daur ulang.</p>
                        <a href="#" class="step-link">Learn more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">03</div>
                        <h3 class="step-title">Tahap 3</h3>
                        <p class="step-description">Penimbangan sampah oleh petugas. Sampah yang sudah dipilah akan ditimbang untuk mengetahui berat sampah yang akan disetorkan ke bank sampah.</p>
                        <a href="#" class="step-link">Learn more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">04</div>
                        <h3 class="step-title">Tahap 4</h3>
                        <p class="step-description">Mendapat keuntungan dari sampah. Masyarakat dapat memperoleh sejumlah uang yang akan ditabung atau dapat diambil langsung setelah sampah berhasil dijual.</p>
                        <a href="#" class="step-link">Learn more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">05</div>
                        <h3 class="step-title">Tahap 5</h3>
                        <p class="step-description">Pelaporan dan monitoring berkala. Nasabah dapat memantau saldo tabungan sampah mereka secara berkala melalui buku tabungan atau sistem online bank sampah.</p>
                        <a href="#" class="step-link">Learn more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-number">06</div>
                        <h3 class="step-title">Tahap 6</h3>
                        <p class="step-description">Edukasi berkelanjutan. Ikuti program edukasi dan pelatihan yang diadakan oleh bank sampah untuk meningkatkan kesadaran lingkungan dan manajemen sampah yang lebih baik.</p>
                        <a href="#" class="step-link">Learn more <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2 class="section-title">Informasi Lokasi</h2>
            <p class="section-subtitle">Temukan lokasi Bank Sampah Mugi Berkah Sari terdekat dari Anda</p>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d247.50319249270692!2d110.4216707478376!3d-7.003267956569345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sJalan%20Genuk%20Krajan%207%2C%20Tegalsari%2C%20Semarang%20City%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1762612044773!5m2!1sen!2sid" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">
                        <img src="img\logo\logombs.png" alt="Logo">
                        <div class="footer-title">Bank Sampah<br>Mugi Berkah Sari</div>
                    </div>
                    <p class="footer-text">
                        Mugi Berkah Sari co.<br>
                        Jl. Genuk Krajan 7, Tegalsari, Kec. Candisari,<br>
                        Kota Semarang, Jawa Tengah 50614
                    </p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Menu</h5>
                    <a href="#" class="footer-link">Beranda</a>
                    <a href="#" class="footer-link">Tentang Kami</a>
                    <a href="#" class="footer-link">Cara Bergabung</a>
                    <a href="#" class="footer-link">Kontak Kami</a>
                </div>
                
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Informasi</h5>
                    <a href="#" class="footer-link">Informasi Sampah</a>
                    <a href="#" class="footer-link">Setoran Sampah</a>
                    <a href="#" class="footer-link">Harga Sampah</a>
                    <a href="#" class="footer-link">Panduan</a>
                </div>
                
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Bantuan</h5>
                    <a href="#" class="footer-link">FAQ</a>
                    <a href="#" class="footer-link">About Us</a>
                    <a href="#" class="footer-link">Command Center</a>
                    <p class="footer-text mt-3">
                        <i class="fas fa-phone"></i> +1234 778 991<br>
                        <i class="fas fa-envelope"></i> hello@woc.com
                    </p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2025 Mugi Berkah Sari. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>