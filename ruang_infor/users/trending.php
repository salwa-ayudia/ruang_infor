<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// CEK LOGIN
if (!isset($_SESSION['login'])) {

    header("Location: login.php");
    exit;
}

/// TOP 3 MATERI
$top3 = mysqli_query($conn, "
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

ORDER BY materi.views DESC

LIMIT 3
");

// TRENDING LAINNYA
$trending = mysqli_query($conn, "
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

ORDER BY materi.views DESC

LIMIT 3, 20
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trending - Ruang Infor</title>

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

            width: 500px;
            height: 500px;

            background: #2563eb;
            opacity: .05;

            border-radius: 50%;

            top: -180px;
            right: -180px;

            z-index: -1;
        }

        body::after {
            content: "";

            position: fixed;

            width: 400px;
            height: 400px;

            background: #1d4ed8;
            opacity: .04;

            border-radius: 50%;

            bottom: -150px;
            left: -150px;

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
           HEADER
        ========================= */

        .page-header {
            padding: 70px 20px 50px;
            text-align: center;
        }

        .page-header h1 {
            font-size: 42px;
            font-weight: 800;

            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 15px;
            color: #64748b;
        }

        /* =========================
           SECTION TITLE
        ========================= */

        .section-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-title h2 {
            font-size: 28px;
            font-weight: 700;

            margin-bottom: 10px;
        }

        .section-title p {
            font-size: 14px;
            color: #64748b;
        }

        /* =========================
           MATERI CARD
        ========================= */

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
            position: relative;
        }

        .materi-card:hover {
            transform: translateY(-6px);
        }

        .crown-badge {
            position: absolute;

            top: 0;
            left: 0;

            width: 64px;
            height: 65px;

            background: linear-gradient(135deg, #facc15, #ce9e10);

            clip-path: polygon(0 0, 100% 0, 0 100%);

            display: flex;
            align-items: flex-start;
            justify-content: flex-start;

            padding: 8px;

            z-index: 20;

            animation: crownFloat 2s ease-in-out infinite;
        }

        .crown-badge i {
    color: white;

    font-size: 20px;

    z-index: 2;
}

        @keyframes crownFloat {

            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-4px);
            }

            100% {
                transform: translateY(0px);
            }
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
           FOOTER
        ========================= */

        footer {
            background: #0f172a;
            color: white;

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
        }

        .footer-desc {
            color: #cbd5e1;
            line-height: 1.8;
        }

        .footer-map {
            width: 100%;
            height: 180px;

            border-radius: 18px;
            overflow: hidden;

            margin-top: 10px;
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

        @media(max-width:768px) {

            .navbar-custom {
                padding: 20px;
                flex-direction: column;
                gap: 20px;
            }

            .materi-wrapper {
                padding: 0 20px 60px;
            }

            .materi-card {
                width: 100%;
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

    <!-- HEADER -->

    <div class="page-header">

        <h1>
            Trending
        </h1>

    </div>

    <!-- TOP 3 -->

    <div class="section-title">

        <h2>
            Top 3 Materi Paling Banyak Dibaca
        </h2>

        <p>
            Materi terpopuler berdasarkan total views
        </p>

    </div>

    <section>

        <div class="materi-wrapper" id="materiContainer">

            <?php while ($m = mysqli_fetch_assoc($top3)) : ?>

                <div class="materi-card materi-item">
                    <div class="crown-badge">
    <i class="bi bi-award-fill"></i>
</div>
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

    <!-- TRENDING LAINNYA -->

    <section style="margin-top: 50px;">

        <div class="section-title">
            <h2>Materi Trending Lainnya</h2>
        </div>

        <div class="materi-wrapper" id="materiContainer">

            <?php while ($m = mysqli_fetch_assoc($trending)) : ?>

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