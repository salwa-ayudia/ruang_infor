<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// CEK ID KATEGORI
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit;
}

$id_kategori = $_GET['id'];

// AMBIL DATA KATEGORI
$kategori = mysqli_query($conn, "
    SELECT * FROM kategori
    WHERE id_kategori = '$id_kategori'
");

$dataKategori = mysqli_fetch_assoc($kategori);

if (!$dataKategori) {
    header("Location: home.php");
    exit;
}

// AMBIL MATERI BERDASARKAN KATEGORI
$materi = mysqli_query($conn, "
    SELECT 
        materi.*,
        kategori.nama_kategori,
        kategori.warna,
        COUNT(bookmark.id) as total_bookmark

    FROM materi

    LEFT JOIN kategori
    ON materi.id_kategori = kategori.id_kategori

    LEFT JOIN bookmark
    ON materi.id_materi = bookmark.id_materi

    WHERE materi.id_kategori = '$id_kategori'

    GROUP BY materi.id_materi

    ORDER BY materi.tanggal_publish DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $dataKategori['nama_kategori'] ?> - Ruang Infor
    </title>

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

            width: 450px;
            height: 450px;

            background: #2563eb;
            opacity: 0.05;

            border-radius: 50%;

            top: -180px;
            left: -120px;

            z-index: -1;
        }

        body::after {
            content: "";

            position: fixed;

            width: 350px;
            height: 350px;

            background: #1d4ed8;
            opacity: 0.05;

            border-radius: 50%;

            bottom: -150px;
            right: -120px;

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

        .btn-logout {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white !important;

            padding: 10px 22px;
            border-radius: 12px;

            transition: .3s;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            color: white !important;
        }

        /* =========================
           HEADER
        ========================= */

        .kategori-header {
            padding: 80px 20px 50px;
            text-align: center;
        }

        .kategori-header h1 {
            font-size: 48px;
            font-weight: bold;
            color: #0f172a;

            margin-bottom: 14px;
        }

        .kategori-header p {
            font-size: 16px;
            color: #64748b;
        }

        /* =========================
           MATERI
        ========================= */

        .materi-section {
            padding: 10px 20px 80px;
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
        }

        .materi-body {
            padding: 22px;
        }

        .materi-body h5 {
            font-size: 18px;
            font-weight: 700;

            color: #0f172a;

            line-height: 1.5;

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
        }

        /* EMPTY */

        .empty-box {
            width: 100%;
            max-width: 600px;

            background: white;

            padding: 50px 30px;

            border-radius: 28px;

            text-align: center;

            margin: auto;

            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);
        }

        .empty-box i {
            font-size: 70px;
            color: #94a3b8;

            margin-bottom: 18px;
        }

        .empty-box h3 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .empty-box p {
            color: #64748b;
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

        .footer-link:hover {
            color: white;
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

            .kategori-header h1 {
                font-size: 38px;
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

            <a href="landing_page.php">
                Beranda
            </a>

            <a href="landing_page.php#about">
                About
            </a>

            <a href="landing_page.php#trending">
                Trending
            </a>

            <a href="logi.php" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i>
                Login
            </a>

        </div>

    </nav>

    <!-- HEADER -->

    <section class="kategori-header">

        <h1>
            <?= $dataKategori['nama_kategori'] ?>
        </h1>

        <p>
            Kumpulan materi berdasarkan kategori
            <?= $dataKategori['nama_kategori'] ?>
        </p>

    </section>

    <!-- MATERI -->

    <section class="materi-section">

        <div class="materi-wrapper">

            <?php if (mysqli_num_rows($materi) > 0) : ?>

                <?php while ($m = mysqli_fetch_assoc($materi)) : ?>

                    <div class="materi-card">

                        <!-- TOP -->
                        <div class="materi-top"
                            style="
                            background: <?= $dataKategori['warna'] ?>15;
                            color: <?= $dataKategori['warna'] ?>;
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

                        <!-- BODY -->
                        <div class="materi-body">

                            <h5>
                                <?= $m['judul'] ?>
                            </h5>

                            <p>
                                <?= substr(strip_tags($m['isi']), 0, 90) ?>...
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

                            <a href="login.php?id=<?= $m['id_materi'] ?>">

                                <button class="btn-baca">
                                    Baca Materi
                                </button>

                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else : ?>

                <div class="empty-box">

                    <i class="bi bi-folder-x"></i>

                    <h3>
                        Belum Ada Materi
                    </h3>

                    <p>
                        Materi pada kategori ini belum tersedia.
                    </p>

                </div>

            <?php endif; ?>

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