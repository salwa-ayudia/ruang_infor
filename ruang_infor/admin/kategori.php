<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// 🔐 proteksi admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// avatar
$nama = $_SESSION['nama_lengkap'] ?? 'Admin';
$words = explode(" ", $nama);

$inisial = "";
foreach ($words as $w) {
    if (!empty($w)) {
        $inisial .= strtoupper($w[0]);
    }
}

// ==========================
// STATISTIK
// ==========================
$total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori"))['total'];
$total_materi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi"))['total'];

// ==========================
// QUERY (SUDAH DIPERBAIKI)
// ==========================
$query = mysqli_query($conn, "
SELECT kategori.*, COUNT(materi.id_materi) as total_materi
FROM kategori
LEFT JOIN materi ON kategori.id_kategori = materi.id_kategori
GROUP BY kategori.id_kategori
ORDER BY kategori.id_kategori DESC
");

// fallback warna
$colors = [
    "linear-gradient(135deg, #3b82f6, #2563eb)",
    "linear-gradient(135deg, #9333ea, #7e22ce)",
    "linear-gradient(135deg, #22c55e, #16a34a)",
    "linear-gradient(135deg, #f59e0b, #d97706)",
    "linear-gradient(135deg, #ef4444, #dc2626)"
];

$i = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Kategori</title>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #1e293b;
            color: white;
            padding: 20px;
        }

        .sidebar a {
            display: flex;
            gap: 10px;
            color: #cbd5e1;
            padding: 10px;
            border-radius: 10px;
            text-decoration: none;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #2563eb;
            color: white;
            transform: translateX(5px);
        }

        .sidebar a.active {
            background: #2563eb;
            color: white;
        }

        .main {
            margin-left: 270px;
            padding: 20px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box {
            width: 300px;
            border-radius: 20px;
            padding: 8px 15px;
            border: 1px solid #ddd;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .notification-wrapper {
            position: relative;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
            font-size: 22px;
            color: #374151;
        }

        .notif-badge {
            position: absolute;
            top: -8px;
            right: -10px;

            width: 20px;
            height: 20px;

            background: #ef4444;
            color: white;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 11px;
            font-weight: bold;
        }

        .notif-panel {
            position: absolute;
            top: 50px;
            right: -100px;

            width: 400px;
            background: white;

            border-radius: 18px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);

            overflow: hidden;

            display: none;
            z-index: 999;
        }

        .notif-header {
            padding: 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            border-bottom: 1px solid #eee;
        }

        .notif-header h5 {
            font-size: 22px;
            font-weight: bold;
            color: #172554;
        }

        .notif-count {
            background: #ef4444;
            color: white;

            width: 26px;
            height: 26px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 12px;
            font-weight: bold;
        }

        .notif-close {
            border: none;
            background: none;
            font-size: 20px;
            color: #64748b;
        }

        .notif-read {
            padding: 12px 20px;

            background: #eff6ff;
            color: #2563eb;

            font-weight: 600;
            cursor: pointer;
        }

        .notif-body {
            max-height: 450px;
            overflow-y: auto;
        }

        .notif-item {
            display: flex;
            gap: 15px;

            padding: 20px;

            border-bottom: 1px solid #f1f5f9;

            position: relative;

            transition: 0.3s;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-icon.blue {
            width: 50px;
            height: 50px;

            border-radius: 50%;

            background: #dbeafe;
            color: #2563eb;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
        }

        .notif-icon.green {
            width: 50px;
            height: 50px;

            border-radius: 50%;

            background: #dcfce7;
            color: #16a34a;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
        }

        .notif-icon.purple {
            width: 50px;
            height: 50px;

            border-radius: 50%;

            background: #f3e8ff;
            color: #9333ea;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
        }

        .notif-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .notif-text {
            font-size: 15px;
            color: #475569;
        }

        .notif-time {
            margin-top: 8px;
            font-size: 13px;
            color: #94a3b8;
        }

        .notif-dot {
            width: 10px;
            height: 10px;

            background: #2563eb;

            border-radius: 50%;

            position: absolute;
            top: 25px;
            right: 20px;
        }

        .notif-footer {
            padding: 16px;
            text-align: center;

            font-weight: 600;
            color: #2563eb;

            cursor: pointer;
        }

        .card-box {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .kategori-card {
            border-radius: 20px;
            overflow: hidden;
            transition: 0.3s;
            cursor: pointer;
        }

        .kategori-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .kategori-header {
            color: white;
            padding: 20px;
        }

        .kategori-body {
            background: white;
            padding: 20px;
        }

        .kategori-header h4 {
            margin-top: 5px;
            font-weight: 600;
        }

        .kategori-body small {
            display: block;
            margin-top: 10px;
        }

        .deskripsi-limit {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* jumlah baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 500;
        }

        .btn-soft {
            border-radius: 10px;
            padding: 8px 15px;
        }

        .btn-edit {
            background: #e0ecff;
            color: #2563eb;
        }

        .btn-hapus {
            background: #ffe5e5;
            color: red;
        }

        .search-kategori {
            border-radius: 20px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h4>Admin Panel</h4>
        <p class="text-secondary">Dashboard Admin</p>

        <a href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
        <a href="materi.php"><i class="bi bi-book"></i> Materi</a>
        <a href="pengguna.php"><i class="bi bi-people"></i> Pengguna</a>
        <a class="active"><i class="bi bi-folder"></i> Kategori</a>

        <hr>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">
            <h2>Kategori</h2>

            <div class="d-flex align-items-center gap-3">
                <input type="text" id="searchTopbar" class="search-box" placeholder="Cari kategori...">
                <?php

                // total notif
                $total_notif = 0;

                // notif user baru
                $user_baru = mysqli_num_rows(mysqli_query($conn, "
                        SELECT id_user 
                        FROM users
                        WHERE DATE(created_at) = CURDATE()
                        "));

                if ($user_baru > 0) {
                    $total_notif++;
                }

                // notif materi baru
                $materi_baru = mysqli_num_rows(mysqli_query($conn, "
                        SELECT id_materi
                        FROM materi
                        WHERE DATE(tanggal_publish) = CURDATE()
                        "));

                if ($materi_baru > 0) {
                    $total_notif++;
                }

                // notif unduhan
                $unduhan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "
                        SELECT COALESCE(SUM(unduhan),0) as total
                        FROM materi
                        "));

                if ($unduhan_hari_ini['total'] > 0) {
                    $total_notif++;
                }

                ?>

                <div class="notification-wrapper">

                    <!-- ICON -->
                    <div class="notification-icon" onclick="toggleNotif()">

                        <i class="bi bi-bell"></i>

                        <span class="notif-badge">
                            <?= $total_notif ?>
                        </span>

                    </div>

                    <!-- PANEL -->
                    <div class="notif-panel" id="notifPanel">

                        <!-- HEADER -->
                        <div class="notif-header">

                            <div class="d-flex align-items-center gap-2">

                                <i class="bi bi-bell"></i>

                                <h5 class="m-0">
                                    Notifikasi
                                </h5>

                                <span class="notif-count">
                                    <?= $total_notif ?>
                                </span>

                            </div>

                            <button class="notif-close" onclick="toggleNotif()">
                                <i class="bi bi-x-lg"></i>
                            </button>

                        </div>

                        <!-- READ -->
                        <div class="notif-read">
                            <i class="bi bi-check2-all"></i>
                            Tandai semua sudah dibaca
                        </div>

                        <!-- BODY -->
                        <div class="notif-body">

                            <!-- USER BARU -->
                            <?php if ($user_baru > 0): ?>

                                <div class="notif-item unread">

                                    <div class="notif-icon purple">
                                        <i class="bi bi-person-plus"></i>
                                    </div>

                                    <div class="notif-content">

                                        <div class="notif-title">
                                            Pengguna Baru
                                        </div>

                                        <div class="notif-text">
                                            <?= $user_baru ?> pengguna baru mendaftar hari ini
                                        </div>

                                        <div class="notif-time">
                                            Hari ini
                                        </div>

                                    </div>

                                    <div class="notif-dot"></div>

                                </div>

                            <?php endif; ?>


                            <!-- MATERI BARU -->
                            <?php if ($materi_baru > 0): ?>

                                <div class="notif-item unread">

                                    <div class="notif-icon green">
                                        <i class="bi bi-file-earmark-plus"></i>
                                    </div>

                                    <div class="notif-content">

                                        <div class="notif-title">
                                            Materi Baru
                                        </div>

                                        <div class="notif-text">
                                            <?= $materi_baru ?> materi baru ditambahkan hari ini
                                        </div>

                                        <div class="notif-time">
                                            Hari ini
                                        </div>

                                    </div>

                                    <div class="notif-dot"></div>

                                </div>

                            <?php endif; ?>


                            <!-- UNDUHAN -->
                            <?php if ($unduhan_hari_ini['total'] > 0): ?>

                                <div class="notif-item unread">

                                    <div class="notif-icon blue">
                                        <i class="bi bi-download"></i>
                                    </div>

                                    <div class="notif-content">

                                        <div class="notif-title">
                                            Total Unduhan
                                        </div>

                                        <div class="notif-text">
                                            Total unduhan materi mencapai
                                            <?= $unduhan_hari_ini['total'] ?>
                                        </div>

                                        <div class="notif-time">
                                            Update terbaru
                                        </div>

                                    </div>

                                    <div class="notif-dot"></div>

                                </div>

                            <?php endif; ?>


                            <!-- KOSONG -->
                            <?php if ($total_notif == 0): ?>

                                <div class="p-4 text-center text-muted">
                                    Tidak ada notifikasi
                                </div>

                            <?php endif; ?>

                        </div>

                        <!-- FOOTER -->
                        <div class="notif-footer">
                            Lihat semua notifikasi
                        </div>

                    </div>

                </div>

                <div class="profile">
                    <div class="avatar"><?= $inisial ?></div>
                    <div>
                        <strong><?= $_SESSION['username'] ?></strong><br>
                        <small class="text-muted"><?= $_SESSION['nama_lengkap'] ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3><b>Manajemen Kategori</b></h3>
                    <p class="text-muted">Kelola kategori materi pembelajaran</p>
                </div>

                <a href="tambah_kategori.php" class="btn btn-primary">
                    + Tambah Kategori
                </a>
            </div>

            <!-- STAT -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card-box">
                        <h6>Total Kategori</h6>
                        <h2><?= $total_kategori ?></h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-box">
                        <h6>Total Materi</h6>
                        <h2><?= $total_materi ?></h2>
                    </div>
                </div>
            </div>

            <!-- LIST -->
            <div class="row g-4" id="kategoriContainer">
                <?php while ($k = mysqli_fetch_assoc($query)): ?>

                    <?php
                    $icon = $k['icon'] ?? '📁';
                    $deskripsi = $k['deskripsi'] ?? 'Tidak ada deskripsi';
                    $warna = !empty($k['warna']) ? $k['warna'] : $colors[$i % count($colors)];
                    ?>

                    <div class="col-md-4 kategori-item">
                        <div class="kategori-card">

                            <div class="kategori-header text-start" style="background: <?= $warna ?>">

                                <!-- ICON -->
                                <div style="font-size: 30px; margin-bottom:8px;">
                                    <?= $icon ?>
                                </div>

                                <!-- NAMA -->
                                <h6 style="margin:0;"><?= $k['nama_kategori'] ?></h6>

                                <small><?= $k['total_materi'] ?> materi</small>
                            </div>

                            <div class="kategori-body">

                                <!-- DESKRIPSI -->
                                <p class="deskripsi-limit"><?= $deskripsi ?></p>

                                <!-- TANGGAL -->
                                <small class="text-muted">
                                    Dibuat pada:
                                    <?= isset($k['created_at']) ? date('d M Y', strtotime($k['created_at'])) : '-' ?>
                                </small>

                                <!-- BUTTON -->
                                <div class="d-flex gap-2 mt-3">
                                    <a href="edit_kategori.php?id=<?= $k['id_kategori'] ?>" class="btn btn-soft btn-edit">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <a href="hapus_kategori.php?id=<?= $k['id_kategori'] ?>"
                                        onclick="return confirm('Yakin mau hapus kategori ini?')"
                                        class="btn btn-soft btn-hapus">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>

                <?php $i++;
                endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        // SEARCH TOPBAR
        document.getElementById("searchTopbar").addEventListener("keyup", function() {

            let keyword = this.value.toLowerCase();

            document.querySelectorAll(".kategori-item").forEach(item => {

                let text = item.innerText.toLowerCase();

                item.style.display = text.includes(keyword) ?
                    "block" :
                    "none";
            });
        });
    </script>

    <script>
        function toggleNotif() {

            let panel = document.getElementById("notifPanel");

            if (panel.style.display === "block") {
                panel.style.display = "none";
            } else {
                panel.style.display = "block";
            }

        }

        // klik luar tutup panel
        window.addEventListener("click", function(e) {

            let panel = document.getElementById("notifPanel");
            let wrapper = document.querySelector(".notification-wrapper");

            if (!wrapper.contains(e.target)) {
                panel.style.display = "none";
            }

        });
    </script>

</body>

</html>