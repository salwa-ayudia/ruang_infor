<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// 🔐 PROTEKSI ADMIN
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// ==========================
// DATA USER
// ==========================
$queryUser = mysqli_query($conn, "
SELECT *
FROM users
ORDER BY id_user DESC
");

// statistik
$total_user = mysqli_num_rows($queryUser);

$aktif = mysqli_num_rows(mysqli_query($conn, "
SELECT * FROM users 
WHERE status='aktif'
"));

$total_admin = mysqli_num_rows(mysqli_query($conn, "
SELECT * FROM users 
WHERE role='admin'
"));

// avatar admin
$nama = $_SESSION['nama_lengkap'] ?? 'Admin';
$words = explode(" ", $nama);

$inisial = "";

foreach ($words as $w) {
    if (!empty($w)) {
        $inisial .= strtoupper($w[0]);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Pengguna</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ======================
           SIDEBAR
        ====================== */

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #1e293b;
            color: white;
            padding: 20px;
        }

        .sidebar p {
            color: #94a3b8;
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

        /* ======================
           MAIN
        ====================== */

        .main {
            margin-left: 270px;
            padding: 20px;
        }

        /* ======================
           TOPBAR
        ====================== */

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
            outline: none;
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

        /* ======================
           CONTENT
        ====================== */

        .title {
            font-size: 20px;
            font-weight: 700;
            color: #172554;
        }

        .subtitle {
            color: #64748b;
            font-size: 14px;
        }

        /* ======================
           CARD STAT
        ====================== */

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .blue {
            background: #2563eb;
        }

        .green {
            background: #16a34a;
        }

        .purple {
            background: #9333ea;
        }

        .stat-title {
            color: #1e293b;
            font-size: 16px;
            font-weight: 500;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #172554;
            margin-top: 10px;
        }

        /* ======================
           FILTER BOX
        ====================== */

        .filter-box {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .filter-input {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 10px 15px;
            font-size: 14px;
            outline: none;
        }

        /* ======================
           TABLE
        ====================== */

        .table-box {
            background: white;
            border-radius: 15px;
            margin-top: 20px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table-title {
            padding: 20px;
            font-size: 18px;
            font-weight: 700;
            color: #172554;
            border-bottom: 1px solid #eee;
        }

        table {
            margin: 0 !important;
        }

        thead {
            background: #f8fafc;
        }

        th {
            padding: 14px !important;
            font-size: 14px;
            color: #475569 !important;
        }

        td {
            padding: 14px !important;
            vertical-align: middle;
            font-size: 14px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #9333ea);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
        }

        .badge-role {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
        }

        .admin {
            background: #fee2e2;
            color: #dc2626;
        }

        .editor {
            background: #dbeafe;
            color: #2563eb;
        }

        .status {
            color: #16a34a;
            font-weight: 600;
            font-size: 14px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background: #22c55e;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h4>Admin Panel</h4>
        <p class="text-secondary">Dashboard Admin</p>

        <a href="dashboard.php">
            <i class="bi bi-house"></i>
            Dashboard
        </a>

        <a href="materi.php">
            <i class="bi bi-book"></i>
            Materi
        </a>

        <a class="active">
            <i class="bi bi-people"></i>
            Pengguna
        </a>

        <a href="kategori.php">
            <i class="bi bi-folder"></i>
            Kategori
        </a>

        <hr>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- TOPBAR -->
        <div class="topbar">

            <h2>Pengguna</h2>

            <div class="d-flex align-items-center gap-3">

                <input
                    type="text"
                    id="searchTable"
                    class="search-box"
                    placeholder="Cari nama pengguna...">

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

                    <div class="avatar">
                        <?= substr($inisial, 0, 2) ?>
                    </div>

                    <div>
                        <strong><?= $_SESSION['username'] ?></strong><br>
                        <small class="text-muted">
                            <?= $_SESSION['nama_lengkap'] ?>
                        </small>
                    </div>

                </div>

            </div>

        </div>

        <!-- TITLE -->
        <div class="mb-4">

            <h3><b>Manajemen Pengguna</b></h3>
            <p class="text-muted">Daftar seluruh pengguna</p>

        </div>

        <!-- CARD -->
        <div class="row g-4">

            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-title">
                        Total Pengguna
                    </div>

                    <div class="stat-number">
                        <?= $total_user ?>
                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-title">
                        Pengguna Aktif
                    </div>

                    <div class="stat-number">
                        <?= $aktif ?>
                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-title">
                        Total Admin
                    </div>

                    <div class="stat-number">
                        <?= $total_admin ?>
                    </div>

                </div>

            </div>

        </div>

        <!-- TABLE -->
        <div class="table-box">

            <div class="table-title">
                Daftar Pengguna (<?= $total_user ?>)
            </div>

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>PENGGUNA</th>
                        <th>ROLE</th>
                        <th>BERGABUNG</th>
                        <th>STATUS</th>
                    </tr>
                </thead>

                <tbody id="userTable">

                    <?php mysqli_data_seek($queryUser, 0); ?>

                    <?php while ($u = mysqli_fetch_assoc($queryUser)): ?>

                        <?php
                        $namaUser = $u['nama_lengkap'] ?? 'User';
                        $email = $u['email'] ?? '-';

                        $words = explode(" ", $namaUser);

                        $inisialUser = "";

                        foreach ($words as $w) {
                            if (!empty($w)) {
                                $inisialUser .= strtoupper($w[0]);
                            }
                        }

                        $role = strtolower($u['role'] ?? 'editor');
                        ?>

                        <tr>

                            <!-- USER -->
                            <td>

                                <div class="user-info">

                                    <div class="user-avatar">
                                        <?= substr($inisialUser, 0, 2) ?>
                                    </div>

                                    <div>
                                        <div class="user-name">
                                            <?= $namaUser ?>
                                        </div>
                                    </div>

                                </div>

                            </td>

                            <!-- ROLE -->
                            <td>

                                <span class="badge-role <?= $role ?>">
                                    <?= ucfirst($role) ?>
                                </span>

                            </td>

                            <!-- TANGGAL -->
                            <td>

                                <?= isset($u['created_at'])
                                    ? date('d M Y', strtotime($u['created_at']))
                                    : '-' ?>

                            </td>

                            <!-- STATUS -->
                            <td>

                                <span class="status-dot"></span>

                                <span class="status">
                                    <?= ucfirst($u['status'] ?? 'aktif') ?>
                                </span>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

    <script>
        // SEARCH REALTIME
        document.getElementById("searchTable").addEventListener("keyup", function() {

            let value = this.value.toLowerCase();

            document.querySelectorAll("#userTable tr").forEach(row => {

                let text = row.innerText.toLowerCase();

                row.style.display =
                    text.includes(value) ? "" : "none";

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