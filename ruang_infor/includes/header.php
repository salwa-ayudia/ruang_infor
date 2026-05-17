<?php
require_once 'config/koneksi.php';
$judul_halaman = 'Beranda'; // opsional
$breadcrumb = [             // opsional
    ['label' => 'Mata Kuliah', 'url' => 'matakuliah.php']
];
require_once 'includes/header.php';
$halaman_aktif = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Ruang Infor - Kumpulan materi kuliah jurusan Informatika" />
    <title>Ruang Infor <?= isset($judul_halaman) ? '| ' . htmlspecialchars($judul_halaman) : '' ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet" />

    <style>
        :root {
            --ri-navy: #1a3a5c;
            --ri-blue: #185FA5;
            --ri-blue-mid: #378ADD;
            --ri-blue-light: #85B7EB;
            --ri-blue-pale: #E6F1FB;
            --ri-accent: #F5A623;
            --ri-white: #ffffff;
            --ri-text: #1a2533;
            --ri-muted: #6c7a8d;
            --ri-border: rgba(26, 58, 92, 0.12);
            --navbar-h: 68px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ri-text);
            background: #f4f7fb;
            padding-top: var(--navbar-h);
        }

        /* ── TOPBAR ── */
        .ri-topbar {
            background: var(--ri-navy);
            color: rgba(255, 255, 255, 0.75);
            font-size: 12px;
            padding: 5px 0;
            letter-spacing: 0.02em;
        }

        .ri-topbar a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: color .2s;
        }

        .ri-topbar a:hover {
            color: var(--ri-accent);
        }

        .ri-topbar .divider {
            width: 1px;
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            display: inline-block;
            margin: 0 10px;
            vertical-align: middle;
        }

        /* ── NAVBAR ── */
        .ri-navbar {
            background: var(--ri-white);
            height: var(--navbar-h);
            border-bottom: 2px solid var(--ri-blue-pale);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 20px rgba(24, 95, 165, 0.10);
            transition: box-shadow .3s;
        }

        .ri-navbar.scrolled {
            box-shadow: 0 4px 30px rgba(24, 95, 165, 0.18);
        }

        .ri-navbar .container {
            height: 100%;
            display: flex;
            align-items: center;
        }

        /* ── LOGO ── */
        .ri-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .ri-logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--ri-blue), var(--ri-navy));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(24, 95, 165, 0.30);
            flex-shrink: 0;
            transition: transform .2s, box-shadow .2s;
        }

        .ri-logo:hover .ri-logo-icon {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(24, 95, 165, 0.38);
        }

        .ri-logo-text {
            line-height: 1.1;
        }

        .ri-logo-text .brand {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: var(--ri-navy);
            display: block;
            letter-spacing: -0.02em;
        }

        .ri-logo-text .sub {
            font-size: 10px;
            color: var(--ri-muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* ── NAV LINKS ── */
        .ri-nav {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .ri-nav .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: var(--ri-muted);
            padding: 6px 14px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color .2s, background .2s;
            white-space: nowrap;
            position: relative;
        }

        .ri-nav .nav-link:hover {
            color: var(--ri-blue);
            background: var(--ri-blue-pale);
        }

        .ri-nav .nav-link.active {
            color: var(--ri-blue);
            background: var(--ri-blue-pale);
        }

        .ri-nav .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 14px;
            right: 14px;
            height: 2px;
            background: var(--ri-blue);
            border-radius: 2px;
        }

        .ri-nav .nav-link i {
            font-size: 15px;
        }

        /* ── DROPDOWN SEMESTER ── */
        .ri-dropdown {
            position: relative;
        }

        .ri-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--ri-white);
            border: 1px solid var(--ri-border);
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(24, 95, 165, 0.15);
            padding: 8px;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-50%) translateY(-8px);
            transition: opacity .25s, transform .25s, visibility .25s;
            z-index: 100;
        }

        .ri-dropdown:hover .ri-dropdown-menu,
        .ri-dropdown:focus-within .ri-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .ri-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--ri-text);
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .ri-dropdown-menu a:hover {
            background: var(--ri-blue-pale);
            color: var(--ri-blue);
        }

        .ri-dropdown-menu .sem-badge {
            width: 28px;
            height: 28px;
            background: var(--ri-blue-pale);
            color: var(--ri-blue);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .ri-dropdown-menu a:hover .sem-badge {
            background: var(--ri-blue);
            color: white;
        }

        .ri-dropdown-arrow {
            font-size: 10px;
            transition: transform .2s;
            margin-left: 2px;
        }

        .ri-dropdown:hover .ri-dropdown-arrow {
            transform: rotate(180deg);
        }

        /* ── SEARCH BAR ── */
        .ri-search-wrap {
            position: relative;
            width: 220px;
            flex-shrink: 0;
        }

        .ri-search-wrap input {
            width: 100%;
            padding: 8px 16px 8px 38px;
            border: 1.5px solid var(--ri-border);
            border-radius: 50px;
            font-size: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ri-text);
            background: #f4f7fb;
            outline: none;
            transition: border-color .2s, background .2s, width .3s;
        }

        .ri-search-wrap input:focus {
            border-color: var(--ri-blue-mid);
            background: white;
            box-shadow: 0 0 0 3px rgba(55, 138, 221, 0.12);
        }

        .ri-search-wrap .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ri-muted);
            font-size: 15px;
            pointer-events: none;
        }

        .ri-search-wrap input:focus+.search-icon {
            color: var(--ri-blue);
        }

        /* ── HAMBURGER (MOBILE) ── */
        .ri-toggler {
            border: none;
            background: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            padding: 0;
            transition: background .2s;
        }

        .ri-toggler:hover {
            background: var(--ri-blue-pale);
        }

        .ri-toggler span {
            display: block;
            width: 22px;
            height: 2px;
            background: var(--ri-navy);
            border-radius: 2px;
            transition: transform .3s, opacity .3s;
        }

        .ri-toggler.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .ri-toggler.open span:nth-child(2) {
            opacity: 0;
        }

        .ri-toggler.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* ── MOBILE MENU ── */
        .ri-mobile-menu {
            position: fixed;
            top: var(--navbar-h);
            left: 0;
            right: 0;
            background: white;
            border-bottom: 2px solid var(--ri-blue-pale);
            box-shadow: 0 8px 30px rgba(24, 95, 165, 0.12);
            padding: 12px 16px 20px;
            z-index: 1020;
            display: none;
            animation: slideDown .25s ease;
        }

        .ri-mobile-menu.open {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ri-mobile-menu .mob-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            color: var(--ri-text);
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .ri-mobile-menu .mob-link:hover,
        .ri-mobile-menu .mob-link.active {
            background: var(--ri-blue-pale);
            color: var(--ri-blue);
        }

        .ri-mobile-menu .mob-link i {
            font-size: 17px;
            color: var(--ri-blue);
        }

        .ri-mobile-menu .mob-search {
            position: relative;
            margin-top: 10px;
        }

        .ri-mobile-menu .mob-search input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1.5px solid var(--ri-border);
            border-radius: 50px;
            font-size: 14px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            background: #f4f7fb;
        }

        .ri-mobile-menu .mob-search input:focus {
            border-color: var(--ri-blue-mid);
            background: white;
        }

        .ri-mobile-menu .mob-search i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ri-muted);
            font-size: 16px;
        }

        .sem-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-top: 8px;
            padding: 0 14px;
        }

        .sem-grid a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 4px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--ri-blue);
            background: var(--ri-blue-pale);
            text-decoration: none;
            transition: background .15s, color .15s;
        }

        .sem-grid a:hover {
            background: var(--ri-blue);
            color: white;
        }

        /* ── BREADCRUMB ── */
        .ri-breadcrumb {
            background: var(--ri-white);
            border-bottom: 1px solid var(--ri-border);
            padding: 8px 0;
            font-size: 13px;
        }

        .ri-breadcrumb .breadcrumb {
            margin: 0;
        }

        .ri-breadcrumb .breadcrumb-item a {
            color: var(--ri-blue);
            text-decoration: none;
        }

        .ri-breadcrumb .breadcrumb-item.active {
            color: var(--ri-muted);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 991px) {

            .ri-nav,
            .ri-search-wrap {
                display: none !important;
            }

            .ri-toggler {
                display: flex !important;
            }
        }

        @media (max-width: 575px) {
            .ri-topbar {
                display: none;
            }

            .ri-logo-text .sub {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- TOPBAR -->
    <div class="ri-topbar" style="position:fixed;top:0;left:0;right:0;z-index:1031;">
        <div class="container d-flex justify-content-between align-items-center" style="height:28px;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-mortarboard-fill" style="color:var(--ri-accent);font-size:12px;"></i>
                <span>Portal Materi Jurusan Informatika</span>
            </div>
            <div class="d-flex align-items-center">
                <a href="mailto:info@ruanginfor.ac.id"><i class="bi bi-envelope me-1"></i>info@ruanginfor.ac.id</a>
                <span class="divider"></span>
                <a href="admin/login.php"><i class="bi bi-shield-lock me-1"></i>Admin</a>
            </div>
        </div>
    </div>

    <?php
    // Geser body karena ada topbar tambahan
    echo '<style>body { padding-top: calc(var(--navbar-h) + 28px); } .ri-navbar { top: 28px; } @media(max-width:575px){body{padding-top:var(--navbar-h);}.ri-navbar{top:0;}}</style>';
    ?>

    <!-- NAVBAR -->
    <nav class="ri-navbar" id="riNavbar" role="navigation" aria-label="Navigasi utama">
        <div class="container">

            <!-- LOGO -->
            <a href="index.php" class="ri-logo me-4" aria-label="Ruang Infor - Halaman Utama">
                <div class="ri-logo-icon" aria-hidden="true">
                    <i class="bi bi-journal-code"></i>
                </div>
                <div class="ri-logo-text">
                    <span class="brand">Ruang Infor</span>
                    <span class="sub">Informatika S1</span>
                </div>
            </a>

            <!-- NAV LINKS (DESKTOP) -->
            <ul class="ri-nav me-auto" role="menubar">
                <li role="none">
                    <a href="index.php"
                        class="nav-link <?= ($halaman_aktif == 'index.php') ? 'active' : '' ?>"
                        role="menuitem"
                        <?= ($halaman_aktif == 'index.php') ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-house-door" aria-hidden="true"></i> Beranda
                    </a>
                </li>
                <li role="none">
                    <a href="matakuliah.php"
                        class="nav-link <?= ($halaman_aktif == 'matakuliah.php') ? 'active' : '' ?>"
                        role="menuitem"
                        <?= ($halaman_aktif == 'matakuliah.php') ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-book" aria-hidden="true"></i> Mata Kuliah
                    </a>
                </li>

                <!-- DROPDOWN SEMESTER -->
                <li class="ri-dropdown" role="none">
                    <a href="#" class="nav-link" role="menuitem" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-layers" aria-hidden="true"></i>
                        Semester
                        <i class="bi bi-chevron-down ri-dropdown-arrow" aria-hidden="true"></i>
                    </a>
                    <div class="ri-dropdown-menu" role="menu" aria-label="Filter semester">
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <a href="matakuliah.php?semester=<?= $s ?>" role="menuitem">
                                <span class="sem-badge"><?= $s ?></span>
                                Semester <?= $s ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                </li>

                <li role="none">
                    <a href="pencarian.php"
                        class="nav-link <?= ($halaman_aktif == 'pencarian.php') ? 'active' : '' ?>"
                        role="menuitem"
                        <?= ($halaman_aktif == 'pencarian.php') ? 'aria-current="page"' : '' ?>>
                        <i class="bi bi-compass" aria-hidden="true"></i> Jelajahi
                    </a>
                </li>
            </ul>

            <!-- SEARCH BAR (DESKTOP) -->
            <form action="pencarian.php" method="GET" class="ri-search-wrap" role="search" aria-label="Cari materi">
                <input
                    type="search"
                    name="q"
                    placeholder="Cari materi..."
                    value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                    aria-label="Cari materi atau mata kuliah"
                    autocomplete="off" />
                <i class="bi bi-search search-icon" aria-hidden="true"></i>
            </form>

            <!-- HAMBURGER (MOBILE) -->
            <button class="ri-toggler ms-3" id="riToggler" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="riMobileMenu">
                <span></span><span></span><span></span>
            </button>

        </div>
    </nav>

    <!-- MOBILE MENU -->
    <div class="ri-mobile-menu" id="riMobileMenu" role="navigation" aria-label="Menu mobile">

        <!-- Mobile Search -->
        <form action="pencarian.php" method="GET" class="mob-search mb-2" role="search">
            <i class="bi bi-search" aria-hidden="true"></i>
            <input type="search" name="q" placeholder="Cari materi, mata kuliah..."
                value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
                aria-label="Cari materi" />
        </form>

        <!-- Mobile Nav Links -->
        <a href="index.php" class="mob-link <?= ($halaman_aktif == 'index.php') ? 'active' : '' ?>">
            <i class="bi bi-house-door"></i> Beranda
        </a>
        <a href="matakuliah.php" class="mob-link <?= ($halaman_aktif == 'matakuliah.php') ? 'active' : '' ?>">
            <i class="bi bi-book"></i> Mata Kuliah
        </a>
        <a href="pencarian.php" class="mob-link <?= ($halaman_aktif == 'pencarian.php') ? 'active' : '' ?>">
            <i class="bi bi-compass"></i> Jelajahi
        </a>

        <!-- Mobile Semester Grid -->
        <div class="mob-link" style="cursor:default; color:var(--ri-muted); font-size:12px; padding-bottom:4px;">
            <i class="bi bi-layers"></i> Filter Semester
        </div>
        <div class="sem-grid">
            <?php for ($s = 1; $s <= 8; $s++): ?>
                <a href="matakuliah.php?semester=<?= $s ?>" aria-label="Semester <?= $s ?>">Sem <?= $s ?></a>
            <?php endfor; ?>
        </div>

        <hr style="border-color:var(--ri-border); margin: 12px 0 8px;">
        <a href="admin/login.php" class="mob-link">
            <i class="bi bi-shield-lock"></i> Login Admin
        </a>
    </div>

    <!-- BREADCRUMB (tampil jika ada variabel $breadcrumb) -->
    <?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
        <nav class="ri-breadcrumb" aria-label="Breadcrumb">
            <div class="container">
                <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                    <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="index.php" itemprop="item"><span itemprop="name"><i class="bi bi-house-door me-1"></i>Beranda</span></a>
                        <meta itemprop="position" content="1" />
                    </li>
                    <?php foreach ($breadcrumb as $i => $item): ?>
                        <?php if ($i === array_key_last($breadcrumb)): ?>
                            <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <span itemprop="name"><?= htmlspecialchars($item['label']) ?></span>
                                <meta itemprop="position" content="<?= $i + 2 ?>" />
                            </li>
                        <?php else: ?>
                            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a href="<?= htmlspecialchars($item['url']) ?>" itemprop="item"><span itemprop="name"><?= htmlspecialchars($item['label']) ?></span></a>
                                <meta itemprop="position" content="<?= $i + 2 ?>" />
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </div>
        </nav>
    <?php endif; ?>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const navbar = document.getElementById('riNavbar');
            const toggler = document.getElementById('riToggler');
            const mobileMenu = document.getElementById('riMobileMenu');

            // Navbar shadow on scroll
            window.addEventListener('scroll', function() {
                navbar.classList.toggle('scrolled', window.scrollY > 10);
            }, {
                passive: true
            });

            // Mobile menu toggle
            toggler.addEventListener('click', function() {
                const isOpen = mobileMenu.classList.toggle('open');
                toggler.classList.toggle('open', isOpen);
                toggler.setAttribute('aria-expanded', isOpen);
            });

            // Tutup mobile menu jika klik di luar
            document.addEventListener('click', function(e) {
                if (!navbar.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.remove('open');
                    toggler.classList.remove('open');
                    toggler.setAttribute('aria-expanded', 'false');
                }
            });

            // Tutup mobile menu jika resize ke desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991) {
                    mobileMenu.classList.remove('open');
                    toggler.classList.remove('open');
                    toggler.setAttribute('aria-expanded', 'false');
                }
            });
        })();
    </script>