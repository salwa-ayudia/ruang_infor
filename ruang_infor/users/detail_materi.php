<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// CEK LOGIN
if (!isset($_SESSION['login'])) {

    header("Location: login.php");
    exit;
}

// CEK ID
if (!isset($_GET['id'])) {

    header("Location: home.php");
    exit;
}

$id = intval($_GET['id']);

// AMBIL DATA
$query = mysqli_query($conn, "
SELECT 
    materi.*,
    kategori.nama_kategori,
    kategori.warna,
    kategori.icon

FROM materi

LEFT JOIN kategori
ON materi.id_kategori = kategori.id_kategori

WHERE materi.id_materi = '$id'
");

$materi = mysqli_fetch_assoc($query);

if (!$materi) {

    header("Location: home.php");
    exit;
}

// UPDATE VIEW
mysqli_query($conn, "
UPDATE materi
SET views = views + 1
WHERE id_materi = '$id'
");

// BOOKMARK
$id_user = $_SESSION['id_user'];

$cekBookmark = mysqli_query($conn, "
SELECT * FROM bookmark
WHERE id_user = '$id_user'
AND id_materi = '$id'
");

$isBookmarked = mysqli_num_rows($cekBookmark) > 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= $materi['judul'] ?> | Ruang Infor
    </title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
        rel="stylesheet">

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
        }

        a {
            text-decoration: none;
        }

        /* BACKGROUND */

        body::before {
            content: "";

            position: fixed;

            width: 450px;
            height: 450px;

            background: #2563eb;

            opacity: .05;

            border-radius: 50%;

            top: -150px;
            right: -150px;

            z-index: -1;
        }

        body::after {
            content: "";

            position: fixed;

            width: 350px;
            height: 350px;

            background: #1d4ed8;

            opacity: .05;

            border-radius: 50%;

            bottom: -120px;
            left: -120px;

            z-index: -1;
        }

        /* NAVBAR */

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

        /* CONTAINER */

        .container-custom {
            max-width: 1100px;

            margin: auto;

            padding: 60px 20px;
        }

        /* CARD */

        .detail-card {
            background: white;

            border-radius: 30px;

            padding: 45px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, .05);

            position: relative;
            overflow: hidden;
        }

        .detail-card::before {
            content: "";

            position: absolute;

            width: 250px;
            height: 250px;

            background: <?= $materi['warna'] ?>15;

            border-radius: 50%;

            top: -100px;
            right: -100px;
        }

        /* BADGE */

        .kategori-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            padding: 10px 18px;

            border-radius: 999px;

            font-size: 14px;
            font-weight: 600;

            margin-bottom: 25px;
        }

        /* TITLE */

        .materi-title {
            font-size: 42px;
            font-weight: 800;

            line-height: 1.3;

            margin-bottom: 25px;

            position: relative;
            z-index: 2;
        }

        /* INFO */

        .materi-info {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;

            color: #64748b;

            font-size: 14px;

            margin-bottom: 35px;
        }

        .materi-info span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* BUTTON */

        .action-wrapper {
            display: flex;
            gap: 15px;

            margin-bottom: 40px;
        }

        .btn-action {
            border: none;

            padding: 13px 22px;

            border-radius: 14px;

            font-weight: 600;
        }

        .btn-download {
            background: #2563eb;
            color: white;
        }

        .btn-bookmark {
            background: #eff6ff;
            color: #2563eb;
        }

        /* CONTENT */

        .materi-content {
            line-height: 2.1;

            color: #334155;

            font-size: 17px;

            text-align: justify;

            position: relative;
            z-index: 2;
        }

        .materi-content p {
            margin-bottom: 22px;
        }

        .materi-content h1,
        .materi-content h2,
        .materi-content h3,
        .materi-content h4,
        .materi-content h5 {
            color: #0f172a;

            margin-top: 24px;
            margin-bottom: 18px;
        }

        .materi-content strong {
            color: #0f172a;
        }

        .materi-content img {
            max-width: 100%;

            border-radius: 18px;

            margin: 30px auto;

            display: block;
        }

        .materi-content iframe {
            width: 100%;
            height: 500px;

            border: none;

            border-radius: 20px;

            margin-top: 25px;
        }

        .materi-content ul,
        .materi-content ol {
            padding-left: 22px;

            margin-bottom: 20px;
        }

        .materi-content li {
            margin-bottom: 10px;
        }

        /* PDF PREVIEW */

        .pdf-preview {
            margin-top: 40px;
        }

        .pdf-preview iframe {
            width: 100%;
            height: 700px;

            border: none;

            border-radius: 24px;

            box-shadow: 0 10px 25px rgba(0, 0, 0, .05);
        }

        /* FOOTER */

        footer {
            background: #0f172a;
            color: white;

            margin-top: 100px;

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

        .footer-desc {
            color: #cbd5e1;

            line-height: 1.8;
        }

        .footer-link {
            display: block;

            color: #cbd5e1;

            margin-bottom: 12px;
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

    <!-- CONTENT -->

    <div class="container-custom">

        <div class="detail-card">

            <!-- BADGE -->

            <div class="kategori-badge"
                style="
                background: <?= $materi['warna'] ?>15;
                color: <?= $materi['warna'] ?>;
            ">

                <?= $materi['icon'] ?>

                <?= $materi['nama_kategori'] ?>

            </div>

            <!-- TITLE -->

            <h1 class="materi-title">
                <?= $materi['judul'] ?>
            </h1>

            <!-- INFO -->

            <div class="materi-info">

                <span>
                    <i class="bi bi-person"></i>
                    <?= $materi['penulis'] ?>
                </span>

                <span>
                    <i class="bi bi-eye"></i>
                    <?= $materi['views'] + 1 ?> Views
                </span>

                <span>
                    <i class="bi bi-download"></i>
                    <?= $materi['unduhan'] ?> Download
                </span>

                <span>
                    <i class="bi bi-calendar-event"></i>

                    <?= date('d F Y', strtotime($materi['tanggal_publish'])) ?>

                </span>

            </div>

            <!-- BUTTON -->

            <div class="action-wrapper">

                <a href="download.php?id=<?= $materi['id_materi'] ?>">

                    <button class="btn-action btn-download">

                        <i class="bi bi-download"></i>
                        Download Materi

                    </button>

                </a>

                <button class="btn-action btn-bookmark">

                    <?php if ($isBookmarked) : ?>

                        <i class="bi bi-bookmark-fill"></i>
                        Tersimpan

                    <?php else : ?>

                        <i class="bi bi-bookmark"></i>
                        Bookmark

                    <?php endif; ?>

                </button>

            </div>

            <!-- ISI -->

            <div class="materi-content">

                <?= $materi['isi'] ?>

            </div>

            <!-- PDF -->

            <?php if (!empty($materi['file'])) : ?>

                <div class="pdf-preview">

                    <iframe
                        src="../uploads/<?= $materi['file'] ?>">
                    </iframe>

                </div>

            <?php endif; ?>

        </div>

    </div>

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

                <a href="home.php"
                    class="footer-link">

                    Beranda

                </a>

                <a href="mylist.php"
                    class="footer-link">

                    MyList

                </a>

                <a href="trending.php"
                    class="footer-link">

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

    <!-- SAVE PROGRESS -->

    <script>

        window.addEventListener("scroll", function() {

            let scrollTop = window.scrollY;

            let docHeight =
                document.body.scrollHeight - window.innerHeight;

            let progress =
                Math.round((scrollTop / docHeight) * 100);

            if (progress > 100) {
                progress = 100;
            }

            localStorage.setItem(
                "materi_<?= $materi['id_materi'] ?>",
                progress
            );

        });

    </script>

</body>

</html>