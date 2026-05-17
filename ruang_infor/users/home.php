<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// TOTAL MATERI
$total_materi = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM materi
"))['total'];

// TOTAL USER
$total_user = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM users
"))['total'];

// TOTAL UNDUHAN
$total_unduhan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(unduhan),0) as total 
    FROM materi
"))['total'];

$kategori = mysqli_query($conn, "
SELECT * FROM kategori
ORDER BY nama_kategori ASC
");

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

// ==========================
// TRENDING
// ==========================
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

            position: relative;
            overflow-x: hidden;
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
            transition: 0.3s;
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
            opacity: 0.08;

            border-radius: 50%;

            position: absolute;
            top: -150px;
            right: -120px;
        }

        .hero::after {
            content: "";

            width: 300px;
            height: 300px;

            background: #1d4ed8;
            opacity: 0.08;

            border-radius: 50%;

            position: absolute;
            bottom: -120px;
            left: -120px;
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

        .search-box {
            background: white;

            border-radius: 18px;

            padding: 10px;

            display: flex;
            align-items: center;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);

            max-width: 650px;
        }

        .search-box input {
            border: none;
            outline: none;

            width: 100%;
            background: transparent;

            padding: 12px;
            font-size: 15px;
        }

        .search-btn {
            background: #2563eb;
            border: none;
            color: white;

            width: 52px;
            height: 52px;

            border-radius: 14px;
        }


        /* =========================
           SECTION
        ========================= */

        .section {
            padding: 20px 60px 70px;
        }

        .section-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        /* =========================
   KATEGORI
========================= */

        .kategori-section {
            padding: 60px 20px;
        }

        .section-title h2 {
            font-size: 32px;
            font-weight: 650;
            color: #0f172a;

            margin-bottom: 10px;
        }

        .section-subtitle {
            font-size: 15px;
            color: #64748b;

            margin-bottom: 25px;

            font-weight: 400;
        }

        .kategori-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 22px;
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

        .kategori-link {
            text-decoration: none;
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

        .kategori-icon i {
            font-size: 26px;
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
   MATERI TERBARU
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

        /* =========================
           RESPONSIVE
        ========================= */

        @media(max-width: 768px) {

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

            .section {
                padding: 20px;
            }

            footer {
                padding: 40px 20px;
            }

        }

        /* =========================
   LANJUTKAN MEMBACA
========================= */

        .history-section {
            padding: 10px 20px 50px;
        }

        .history-wrapper {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .history-card {
            width: 290px;

            background: white;

            border-radius: 28px;

            padding: 24px;

            box-shadow: 0 8px 24px rgba(0, 0, 0, .05);

            transition: .3s;

            position: relative;
            overflow: hidden;
        }

        .history-card::before {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            background: #2563eb08;

            border-radius: 50%;

            top: -80px;
            right: -80px;
        }

        .history-card:hover {
            transform: translateY(-6px);
        }

        .history-icon {
            width: 72px;
            height: 72px;

            border-radius: 22px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 34px;

            margin: 0 auto 24px;

            box-shadow: 0 10px 24px rgba(0, 0, 0, .08);
        }

        .history-title {
            font-size: 22px;
            font-weight: 700;

            color: #0f172a;

            line-height: 1.4;

            margin-bottom: 22px;

            min-height: 65px;
        }

        .history-progress-top {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 10px;
        }

        .history-label {
            font-size: 14px;
            color: #64748b;
        }

        .history-percent {
            font-size: 14px;
            font-weight: 700;

            color: #2563eb;
        }

        .history-progress {
            width: 100%;
            height: 10px;

            background: #e2e8f0;

            border-radius: 999px;

            overflow: hidden;

            margin-bottom: 20px;
        }

        .history-progress-bar {
            height: 100%;

            background: linear-gradient(135deg, #2563eb, #1d4ed8);

            border-radius: 999px;

            transition: 1s;
        }

        .btn-lanjutkan {
            width: 100%;

            border: none;

            padding: 12px;

            border-radius: 14px;

            background: #2563eb;
            color: white;

            font-weight: 600;

            transition: .3s;
        }

        .btn-lanjutkan:hover {
            background: #1d4ed8;
        }
    </style>
</head>

<body>

    <!-- =========================
         NAVBAR
    ========================= -->

    <nav class="navbar-custom">

        <div class="logo">
            Ruang<span>Infor</span>
        </div>

        <div class="nav-menu">

            <a href="home.php">
                Beranda
            </a>

            <a href="mylist.php">
                MyList
            </a>

            <a href="trending.php">
                Trending
            </a>

            <a href="logout.php" class="btn-login">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>

        </div>

    </nav>

    <!-- =========================
         HERO
    ========================= -->

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

            <!-- SEARCH -->
            <form class="search-box" onsubmit="return false;">

                <input type="text"
                    id="searchMateri"
                    placeholder="Cari materi yang ingin dipelajari...">

                <button class="search-btn">
                    <i class="bi bi-search"></i>
                </button>

            </form>

        </div>

    </section>

    <!-- =========================
     LANJUTKAN MEMBACA
========================= -->

    <section class="history-section">

        <div class="section-title text-center">

            <h2>Lanjutkan Membaca</h2>

            <p class="section-subtitle">
                Progress belajar materi yang terakhir kamu baca
            </p>

        </div>

        <div class="history-wrapper">

            <?php

            $history = mysqli_query($conn, "
        SELECT 
            materi.*,
            kategori.warna,
            kategori.icon

        FROM materi

        LEFT JOIN kategori
        ON materi.id_kategori = kategori.id_kategori

        ORDER BY materi.views DESC
        LIMIT 3
        ");

            while ($h = mysqli_fetch_assoc($history)) :

            ?>

                <div class="history-card">

                    <!-- ICON -->

                    <div class="history-icon"
                        style="
    background: <?= $h['warna'] ?>20;
    color: <?= $h['warna'] ?>;
">

                        <?= $h['icon'] ?>

                    </div>

                    <!-- TITLE -->

                    <div class="history-title">

                        <?= $h['judul'] ?>

                    </div>

                    <!-- PROGRESS -->

                    <div class="history-progress-top">

                        <div class="history-label">
                            Progress Membaca
                        </div>

                        <div class="history-percent"
                            id="percent-<?= $h['id_materi'] ?>">

                            0%

                        </div>

                    </div>

                    <div class="history-progress">

                        <div class="history-progress-bar"
                            id="bar-<?= $h['id_materi'] ?>"
                            style="width:0%">
                        </div>

                    </div>

                    <!-- BUTTON -->

                    <a href="detail_materi.php?id=<?= $h['id_materi'] ?>">

                        <button class="btn-lanjutkan">

                            Lanjutkan Membaca

                        </button>

                    </a>

                </div>

            <?php endwhile; ?>

        </div>

    </section>

    <!-- =========================
     KATEGORI POPULER
========================= -->

    <section class="kategori-section">

        <div class="section-title text-center">
            <h2>Jelajahi Kategori</h2>

            <p class="section-subtitle">
                Pilih kategori materi yang ingin kamu pelajari
            </p>
        </div>

        <div class="kategori-wrapper">

            <?php while ($k = mysqli_fetch_assoc($kategori)) : ?>

                <a href="kategori.php?id=<?= $k['id_kategori'] ?>" class="kategori-link">
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

    <!-- =========================
     MATERI TERBARU
========================= -->

    <section class="materi-section">

        <div class="section-title text-center">
            <h2>Materi Terbaru</h2>

            <p class="section-subtitle">
                Materi terbaru dan paling update untuk dipelajari
            </p>
        </div>

        <div class="materi-wrapper" id="materiContainer">

            <?php while ($m = mysqli_fetch_assoc($materi)) : ?>

                <div class="materi-card materi-item">
                    <!-- TOP -->
                    <div class="materi-top"
                        style="
                        background: <?= $m['warna'] ?>15;
                        color: <?= $m['warna'] ?>;
                    ">

                        <div class="materi-kategori">

                            <?= $m['nama_kategori'] ?>

                        </div>

                        <?php

                        $cekBookmark = mysqli_query($conn, "
SELECT * FROM bookmark
WHERE id_user = '" . $_SESSION['id_user'] . "'
AND id_materi = '" . $m['id_materi'] . "'
");

                        $isBookmarked = mysqli_num_rows($cekBookmark) > 0;

                        ?>

                        <div class="bookmark-icon bookmark-btn"
                            data-id="<?= $m['id_materi'] ?>">

                            <i class="bi 
    <?= $isBookmarked ? 'bi-bookmark-fill' : 'bi-bookmark' ?>">
                            </i>

                            <span>
                                <?= $m['total_bookmark'] ?>
                            </span>

                        </div>

                    </div>

                    <!-- BODY -->
                    <div class="materi-body">

                        <h5>
                            <?= $m['judul'] ?>
                        </h5>

                        <p>
                            <?= substr(strip_tags($m['isi']), 0, 80) ?>...
                        </p>

                        <!-- STAT -->
                        <div class="materi-stat">

                            <span>
                                <i class="bi bi-eye"></i>
                                <?= $m['views'] ?? 0 ?>
                            </span>

                            <span>
                                <i class="bi bi-download"></i>
                                <?= $m['unduhan'] ?? 0 ?>
                            </span>

                        </div>

                        <!-- BUTTON -->
                        <a href="detail_materi.php?id=<?= $m['id_materi'] ?>">

                            <button class="btn-baca">
                                Baca Materi
                            </button>

                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </section>

    <!-- =========================
         TRENDING
    ========================= -->

    <section class="trending-section" id="trending">

        <div class="section-title text-center">
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

                    <a href="login.php" class="trending-link">

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
                                        <?= $t['nama_kategori'] ?? '-' ?>
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

    <!-- =========================
         FOOTER
    ========================= -->

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

                <a href="home.php" class="footer-link">
                    Beranda
                </a>

                <a href="mylist.php" class="footer-link">
                    MyList
                </a>

                <a href="trending.php" class="footer-link">
                    Trending
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

    <script>
        const searchInput = document.getElementById("searchMateri");

        searchInput.addEventListener("keyup", function() {

            let keyword = this.value.toLowerCase();

            let materi = document.querySelectorAll(".materi-item");

            materi.forEach(card => {

                let text = card.innerText.toLowerCase();

                if (text.includes(keyword)) {

                    card.style.display = "flex";
                    card.style.flexDirection = "column";

                } else {

                    card.style.display = "none";

                }

            });

        });

        document.querySelectorAll(".bookmark-btn").forEach(button => {

            button.addEventListener("click", function() {

                let id = this.dataset.id;

                let icon = this.querySelector("i");
                let totalText = this.querySelector("span");

                fetch("bookmark.php", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },

                        body: "id_materi=" + id
                    })

                    .then(res => res.json())

                    .then(data => {

                        totalText.innerText = data.total;

                        if (data.status == "added") {

                            icon.classList.remove("bi-bookmark");
                            icon.classList.add("bi-bookmark-fill");

                        } else {

                            icon.classList.remove("bi-bookmark-fill");
                            icon.classList.add("bi-bookmark");
                        }

                    });

            });

        });
    </script>

    <script>
        // =========================
        // LOAD PROGRESS HISTORY
        // =========================

        <?php

        mysqli_data_seek($history, 0);

        while ($h = mysqli_fetch_assoc($history)) :

        ?>

            let progress<?= $h['id_materi'] ?> =
                localStorage.getItem(
                    "materi_<?= $h['id_materi'] ?>"
                );

            if (!progress<?= $h['id_materi'] ?>) {

                progress<?= $h['id_materi'] ?> = 0;
            }

            document.getElementById(
                    "bar-<?= $h['id_materi'] ?>"
                ).style.width =
                progress<?= $h['id_materi'] ?> + "%";

            document.getElementById(
                    "percent-<?= $h['id_materi'] ?>"
                ).innerText =
                progress<?= $h['id_materi'] ?> + "%";

        <?php endwhile; ?>
    </script>

</body>

</html>