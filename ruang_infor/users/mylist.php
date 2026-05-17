<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// CEK LOGIN
if (!isset($_SESSION['login'])) {

    header("Location: login.php");
    exit;
}

// AMBIL BOOKMARK USER
$id_user = $_SESSION['id_user'];

$materi = mysqli_query($conn, "
SELECT 
    materi.*,
    kategori.nama_kategori,
    kategori.warna,
    kategori.icon

FROM bookmark

LEFT JOIN materi
ON bookmark.id_materi = materi.id_materi

LEFT JOIN kategori
ON materi.id_kategori = kategori.id_kategori

WHERE bookmark.id_user = '$id_user'

ORDER BY bookmark.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyList - Ruang Infor</title>

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
            padding: 80px 20px 40px;
            text-align: center;
        }

        .page-header h1 {
            font-size: 54px;
            font-weight: bold;

            margin-bottom: 14px;
        }

        .page-header p {
            font-size: 16px;
            color: #64748b;
        }

        /* =========================
           CARD
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
            width: 42px;
            height: 42px;

            border-radius: 12px;

            background: rgba(255, 255, 255, .7);

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;

            transition: .3s;
        }

        .bookmark-icon:hover {
            transform: scale(1.08);
            background: white;
        }

        .bookmark-icon i {
            font-size: 18px;
            color: #0f172a;
        }

        /* JARAK KE FOOTER */

        .materi-wrapper {
            margin-bottom: 100px;
        }

        .btn-baca {
            width: 100%;

            border: none;

            padding: 13px;

            border-radius: 16px;

            background: linear-gradient(135deg, #2563eb, #1d4ed8);

            color: white;

            font-weight: 600;

            transition: .3s;
        }

        .btn-baca:hover {
            transform: translateY(-2px);
        }

        /* =========================
           EMPTY
        ========================= */

        .empty-state {
            text-align: center;

            padding: 100px 20px;
        }

        .empty-state i {
            font-size: 70px;
            color: #94a3b8;

            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 30px;
            margin-bottom: 12px;
        }

        .empty-state p {
            color: #64748b;
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

            transition: .3s;
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

            footer {
                padding: 40px 20px;
            }

            .page-header h1 {
                font-size: 42px;
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
            MyList
        </h1>

        <p>
            Semua materi yang sudah kamu bookmark
        </p>

    </div>

    <!-- LIST -->
    <div class="materi-wrapper" id="materiContainer">

        <?php if (mysqli_num_rows($materi) > 0) : ?>

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

                        <div class="bookmark-icon bookmark-btn"
                            data-id="<?= $m['id_materi'] ?>">

                            <i class="bi bi-bookmark-fill"></i>

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

        <?php else : ?>

            <div class="empty-state" id="emptyState">

                <i class="bi bi-bookmark"></i>

                <h3>
                    Belum Ada Bookmark
                </h3>

                <p>
                    Simpan materi favoritmu ke MyList
                </p>

            </div>

        <?php endif; ?>

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

                <a href="home.php" class="footer-link">
                    Beranda
                </a>

                <a href="mylist.php" class="footer-link">
                    MyList
                </a>

                <a href="home.php#trending" class="footer-link">
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

    <script>
        document.querySelectorAll(".bookmark-btn").forEach(button => {

            button.addEventListener("click", function() {

                let idMateri = this.dataset.id;
                let card = this.closest(".materi-card");

                fetch("bookmark.php", {

                        method: "POST",

                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },

                        body: "id_materi=" + idMateri

                    })

                    .then(response => response.json())

                    .then(data => {

                        if (data.status == "removed") {

                            card.style.opacity = "0";
                            card.style.transform = "scale(.9)";

                            setTimeout(() => {

                                card.remove();

                                // CEK SISA CARD
                                let sisaCard = document.querySelectorAll(".materi-card");

                                if (sisaCard.length == 0) {

                                    document.getElementById("materiContainer").innerHTML = `

    <div class="empty-state" id="emptyState">

        <i class="bi bi-bookmark"></i>

        <h3>
            Belum Ada Bookmark
        </h3>

        <p>
            Simpan materi favoritmu ke MyList
        </p>

    </div>

`;

                                }

                            }, 300);

                        }

                    });

            });

        });
    </script>

</body>

</html>