<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// JIKA SUDAH LOGIN
if (isset($_SESSION['login'])) {

    header("Location: home.php");
    exit;
}

// TOTAL MATERI
$total_materi = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as total FROM materi
"))['total'];

// TOTAL USER
$total_user = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as total FROM users
"))['total'];

// TOTAL UNDUHAN
$total_unduhan = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COALESCE(SUM(unduhan),0) as total FROM materi
"))['total'];

// KATEGORI
$kategori = mysqli_query($conn, "
SELECT * FROM kategori
ORDER BY nama_kategori ASC
");

// MATERI
$materi = mysqli_query($conn, "
SELECT 
    materi.*,
    kategori.nama_kategori,
    kategori.warna,
    kategori.icon,
    COUNT(bookmark.id) as total_bookmark

FROM materi

LEFT JOIN kategori
ON materi.id_kategori = kategori.id_kategori

LEFT JOIN bookmark
ON materi.id_materi = bookmark.id_materi

GROUP BY materi.id_materi

ORDER BY materi.tanggal_publish DESC
");

// TRENDING
$trending = mysqli_query($conn, "
SELECT 
    materi.*,
    kategori.nama_kategori

FROM materi

LEFT JOIN kategori
ON materi.id_kategori = kategori.id_kategori

ORDER BY (views + unduhan) DESC
LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ruang Infor</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
            color: #0f172a;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";

            position: fixed;
            top: -150px;
            left: -150px;

            width: 350px;
            height: 350px;

            background: #2563eb;
            opacity: .05;

            border-radius: 50%;
            z-index: -1;
        }

        body::after {
            content: "";

            position: fixed;
            bottom: -180px;
            right: -180px;

            width: 420px;
            height: 420px;

            background: #1d4ed8;
            opacity: .05;

            border-radius: 50%;
            z-index: -1;
        }

        a {
            text-decoration: none;
        }

        /* =========================
           NAVBAR
        ========================= */

        .navbar-custom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);

            padding: 18px 60px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            position: sticky;
            top: 0;

            z-index: 999;

            border-bottom: 1px solid #e2e8f0;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a8a;
        }

        .logo span {
            color: #2563eb;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-menu a {
            color: #334155;
            font-weight: 600;
            transition: .3s;
        }

        .nav-menu a:hover {
            color: #2563eb;
        }

        .btn-login {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white !important;

            padding: 10px 22px;
            border-radius: 12px;

            transition: 0.3s;
        }

        .btn-login:hover {
            background: #dc2626;
            transform: translateY(-2px);
            color: white !important;
        }


        /* =========================
           HERO
        ========================= */

        .hero {
            padding: 100px 60px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";

            width: 450px;
            height: 450px;

            background: #2563eb;
            opacity: .08;

            border-radius: 50%;

            position: absolute;
            top: -150px;
            right: -120px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 700px;
        }

        .hero-badge {
            display: inline-block;

            padding: 8px 18px;

            background: #dbeafe;
            color: #1d4ed8;

            border-radius: 999px;

            font-size: 14px;
            font-weight: 600;

            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: 58px;
            line-height: 1.2;
            font-weight: bold;

            margin-bottom: 20px;
        }

        .hero h1 span {
            color: #2563eb;
        }

        .hero p {
            color: #64748b;
            font-size: 18px;
            line-height: 1.8;

            margin-bottom: 35px;
        }

        .hero-btn {
            background: #2563eb;
            color: white;

            padding: 14px 28px;

            border-radius: 14px;

            font-weight: 600;

            transition: .3s;

            display: inline-block;
        }

        .hero-btn:hover {
            background: #1d4ed8;
            color: white;
            transform: translateY(-3px);
        }

        /* =========================
           SECTION
        ========================= */

        .section-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-title h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .section-subtitle {
            font-size: 15px;
            color: #64748b;
        }

        /* =========================
           ABOUT
        ========================= */

        .about-section {
            padding: 70px 20px;
        }

        .about-wrapper {
            max-width: 1000px;
            margin: auto;

            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .about-card {
            background: white;

            border-radius: 28px;

            padding: 30px;

            text-align: center;

            box-shadow: 0 8px 24px rgba(0, 0, 0, .05);

            transition: .3s;
        }

        .about-card:hover {
            transform: translateY(-6px);
        }

        .about-icon {
            width: 75px;
            height: 75px;

            margin: auto auto 20px;

            border-radius: 22px;

            background: #dbeafe;
            color: #2563eb;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 34px;
        }

        .about-card h4 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .about-card p {
            color: #64748b;
            line-height: 1.8;
            font-size: 15px;
        }

        /* =========================
           KATEGORI
        ========================= */

        .kategori-section {
            padding: 70px 20px;
        }

        .kategori-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .kategori-card {
            width: 250px;
            height: 220px;

            background: white;

            border-radius: 24px;

            padding: 24px;

            box-shadow: 0 8px 24px rgba(0, 0, 0, .05);

            transition: 0.3s;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            text-align: center;
        }

        .kategori-card:hover {
            transform: translateY(-6px);
        }

        .kategori-icon {
            width: 70px;
            height: 70px;

            border-radius: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 30px;

            margin: 0 auto 20px;
        }

        .kategori-card h5 {
            font-size: 22px;
            font-weight: 700;

            color: #0f172a;

            line-height: 1.4;

            margin: 0;

            min-height: 60px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* =========================
           MATERI
        ========================= */

        .materi-section {
            padding: 70px 20px;
        }

        .materi-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .materi-card {
            width: 260px;

            background: white;

            border-radius: 24px;

            overflow: hidden;

            box-shadow: 0 8px 24px rgba(0, 0, 0, .05);

            transition: .3s;

            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .materi-card:hover {
            transform: translateY(-6px);
        }

        .materi-top {
            padding: 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .materi-kategori {
            font-size: 13px;
            font-weight: 700;

            padding: 7px 14px;

            border-radius: 999px;

            background: rgba(255, 255, 255, .6);
        }

        .materi-icon {
            font-size: 28px;
        }

        .materi-body {
            padding: 22px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .materi-body h5 {
            font-size: 20px;
            font-weight: 700;

            color: #0f172a;

            line-height: 1.4;

            margin-bottom: 12px;
        }

        .materi-body p {
            font-size: 14px;
            color: #64748b;

            line-height: 1.7;

            margin-bottom: 18px;
        }

        .materi-stat {
            display: flex;
            gap: 18px;

            font-size: 13px;
            color: #64748b;

            margin-bottom: 18px;
        }

        /* BOOKMARK */

        .bookmark-icon {
            display: flex;
            align-items: center;
            gap: 6px;

            background: rgba(255, 255, 255, .7);

            padding: 7px 12px;

            border-radius: 999px;

            font-size: 13px;
            font-weight: 600;

            color: #0f172a;
            cursor: pointer;
            transition: .3s;
        }

        .bookmark-icon:hover {
            transform: scale(1.05);
        }

        .btn-baca {
            width: 100%;

            border: none;

            padding: 12px;

            border-radius: 14px;

            background: #2563eb;
            color: white;

            font-weight: 600;

            transition: .3s;
        }

        .btn-baca:hover {
            background: #1d4ed8;
            margin-top: auto;
        }

        /* =========================
           TRENDING
        ========================= */

        .trending-section {
            padding: 70px 20px;
        }

        .trending-wrapper {
            display: flex;
            justify-content: center;
        }

        .trending-card {
            width: 100%;
            max-width: 850px;

            background: white;

            border-radius: 28px;

            padding: 18px 30px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);

            position: relative;
            overflow: hidden;
        }

        .trending-card::before {
            content: "";

            position: absolute;

            width: 220px;
            height: 220px;

            background: #2563eb;
            opacity: .05;

            border-radius: 50%;

            top: -100px;
            right: -80px;
        }

        .trending-item {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 22px 0;

            border-bottom: 1px solid #e2e8f0;

            position: relative;
            z-index: 2;
            cursor: pointer;
        }

        .trending-item:last-child {
            border-bottom: none;
        }

        .trending-link {
            text-decoration: none;
        }

        .trending-item {
            transition: .3s;
            border-radius: 18px;
            padding-left: 12px;
            padding-right: 12px;
        }

        .trending-item:hover {
            background: #f8fbff;

            transform: translateX(6px);

            box-shadow: 0 5px 18px rgba(37, 99, 235, .08);
        }

        .trending-item:hover .trend-number {
            transform: scale(1.08);
        }

        .trend-number {
            transition: .3s;
        }

        .trending-item:hover .trend-score {
            transform: scale(1.05);
        }

        .trend-score {
            transition: .3s;
        }

        .trend-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .trend-number {
            width: 48px;
            height: 48px;

            border-radius: 16px;

            background: #dbeafe;
            color: #2563eb;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;
            font-weight: bold;
        }

        .trend-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;

            margin-bottom: 4px;
        }

        .trend-kategori {
            color: #64748b;
            font-size: 14px;
        }

        .trend-score {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;

            padding: 10px 18px;

            border-radius: 999px;

            font-size: 14px;
            font-weight: 600;

            box-shadow: 0 5px 15px rgba(37, 99, 235, .25);
        }

        /* =========================
           FOOTER
        ========================= */

        footer {
            background: #0f172a;
            color: white;

            margin-top: 80px;
            padding: 60px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
        }

        .footer-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .footer-link {
            display: block;

            color: #cbd5e1;

            margin-bottom: 12px;

            transition: 0.3s;
        }

        .footer-map {
            width: 100%;
            height: 180px;

            border-radius: 18px;
            overflow: hidden;

            margin-top: 10px;

            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .footer-map iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .footer-location-text {
            margin-top: 12px;

            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-link:hover {
            color: white;
        }

        .footer-desc {
            color: #cbd5e1;
            line-height: 1.8;
        }

        .copyright {
            border-top: 1px solid #334155;

            margin-top: 40px;
            padding-top: 25px;

            text-align: center;

            color: #94a3b8;
        }


        @media(max-width:768px) {

            .navbar-custom {
                padding: 20px;
                flex-direction: column;
                gap: 20px;
            }

            .hero {
                padding: 80px 20px;
            }

            .hero h1 {
                font-size: 42px;
            }

            footer {
                padding: 40px 20px;
            }

        }
    </style>

</head>

<body>

    <!-- NAVBAR -->

    <nav class="navbar-custom">

        <div class="logo">
            Ruang<span>Infor</span>
        </div>

        <div class="nav-menu">

            <a href="#">
                Beranda
            </a>

            <a href="#about">
                About
            </a>

            <a href="#trending">
                Trending
            </a>

            <a href="login.php" class="btn-login">
                <i class="bi bi-box-arrow-right"></i>
                Login
            </a>

        </div>

    </nav>

    <!-- HERO -->

    <section class="hero">

        <div class="hero-content">

            <div class="hero-badge">
                ✨ Platform Pembelajaran Informatika
            </div>

            <h1>
                Belajar Informatika
                Lebih Mudah di
                <span>Ruang Infor</span>
            </h1>

            <p>
                Temukan berbagai materi pembelajaran informatika,
                mulai dari pemrograman, jaringan, keamanan,
                basis data, hingga teknologi terbaru.
            </p>

            <a href="login.php" class="hero-btn">
                Mulai Belajar
            </a>

        </div>

    </section>

    <!-- ABOUT -->

    <section class="about-section" id="about">

        <div class="section-title">

            <h2>Tentang Ruang Infor</h2>

            <p class="section-subtitle">
                Platform pembelajaran informatika modern dan interaktif
            </p>

        </div>

        <div class="about-wrapper">

            <div class="about-card">

                <div class="about-icon">
                    <i class="bi bi-book"></i>
                </div>

                <h4>Materi Lengkap</h4>

                <p>
                    Tersedia berbagai materi informatika lengkap
                    mulai dari dasar hingga tingkat lanjut.
                </p>

            </div>

            <div class="about-card">

                <div class="about-icon">
                    <i class="bi bi-laptop"></i>
                </div>

                <h4>Pembelajaran Modern</h4>

                <p>
                    Belajar dengan tampilan modern,
                    interaktif dan mudah dipahami.
                </p>

            </div>

            <div class="about-card">

                <div class="about-icon">
                    <i class="bi bi-graph-up"></i>
                </div>

                <h4>Materi Trending</h4>

                <p>
                    Temukan materi paling populer
                    dan paling banyak dipelajari.
                </p>

            </div>

        </div>

    </section>

    <!-- KATEGORI -->

    <section class="kategori-section">

        <div class="section-title text-center">
            <h2>Jelajahi Kategori</h2>

            <p class="section-subtitle">
                Pilih kategori materi yang ingin kamu pelajari
            </p>
        </div>

        <div class="kategori-wrapper">

            <?php while ($k = mysqli_fetch_assoc($kategori)) : ?>

                <a href="kategorilp.php?id=<?= $k['id_kategori'] ?>" class="kategori-link">
                    <div class="kategori-card">

                        <!-- ICON -->
                        <div class="kategori-icon"
                            style="
                        background: <?= $k['warna'] ?>20;
                        color: <?= $k['warna'] ?>;
                        ">

                            <?= $k['icon'] ?>

                        </div>

                        <!-- NAMA KATEGORI -->
                        <h5>
                            <?= $k['nama_kategori'] ?>
                        </h5>

                    </div>
                </a>

            <?php endwhile; ?>

        </div>

    </section>

    <!-- MATERI -->

    <section class="materi-section">

        <div class="section-title">

            <h2>Materi Terbaru</h2>

            <p class="section-subtitle">
                Materi terbaru dan paling update untuk dipelajari
            </p>

        </div>

        <div class="materi-wrapper">

            <?php while ($m = mysqli_fetch_assoc($materi)) : ?>

                <div class="materi-card">

                    <div class="materi-top"
                        style="
                        background: <?= $m['warna'] ?>15;
                        color: <?= $m['warna'] ?>;
                    ">

                        <div class="materi-kategori">
                            <?= $m['nama_kategori'] ?>
                        </div>

                        <a href="login.php" class="bookmark-icon">

                            <i class="bi bi-bookmark"></i>

                            <span>
                                <?= $m['total_bookmark'] ?>
                            </span>

                        </a>

                    </div>

                    <div class="materi-body">

                        <h5>
                            <?= $m['judul'] ?>
                        </h5>

                        <p>
                            <?= substr(strip_tags($m['isi']), 0, 80) ?>...
                        </p>

                        <div class="materi-stat">

                            <span>
                                <i class="bi bi-eye"></i>
                                <?= $m['views'] ?>
                            </span>

                            <span>
                                <i class="bi bi-download"></i>
                                <?= $m['unduhan'] ?>
                            </span>

                        </div>

                        <a href="login.php">

                            <button class="btn-baca">
                                Baca Materi
                            </button>

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </section>

    <!-- TRENDING -->

    <section class="trending-section" id="trending">

        <div class="section-title">

            <h2>🔥 Trending Materi</h2>

            <p class="section-subtitle">
                Materi paling populer berdasarkan views dan unduhan
            </p>

        </div>

        <div class="trending-wrapper">

            <div class="trending-card">

                <?php
                $no = 1;
                while ($t = mysqli_fetch_assoc($trending)) :
                ?>

                    <a href="login.php">

                        <div class="trending-item">

                            <div class="trend-left">

                                <div class="trend-number">
                                    #<?= $no++ ?>
                                </div>

                                <div>

                                    <div class="trend-title">
                                        <?= $t['judul'] ?>
                                    </div>

                                    <div class="trend-kategori">
                                        <?= $t['nama_kategori'] ?>
                                    </div>

                                </div>

                            </div>

                            <div class="trend-score">

                                <i class="bi bi-fire"></i>

                                <?= ($t['views'] + $t['unduhan']) ?>

                            </div>

                        </div>

                    </a>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

    <!-- FOOTER -->

    <footer>

        <div class="footer-grid">

            <div>

                <div class="footer-title">
                    Ruang Infor
                </div>

                <div class="footer-desc">
                    Platform pembelajaran informatika modern
                    dengan materi interaktif, mudah dipahami,
                    dan selalu up-to-date.
                </div>

            </div>

            <div>

                <div class="footer-title">
                    Sitemap
                </div>

                <a href="landing_page.php" class="footer-link">
                    Beranda
                </a>

                <a href="landing_page.php#trending" class="footer-link">
                    Trending
                </a>

                <a href="login.php" class="footer-link">
                    Login
                </a>

            </div>

            <div>

                <div class="footer-title">
                    Lokasi
                </div>

                <div class="footer-map">

                    <iframe
                        src="https://www.google.com/maps?q=UIN%20SSC%20Cirebon&output=embed"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>

                </div>

                <div class="footer-location-text">
                    UIN Siber Syekh Nurjati Cirebon (SSC)
                </div>

            </div>

        </div>

        <div class="copyright">
            © <?= date('Y') ?> Ruang Infor — All Rights Reserved
        </div>

    </footer>

</body>

</html>