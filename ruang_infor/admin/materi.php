<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// ambil nama
$nama = $_SESSION['nama_lengkap'] ?? 'Admin';
$words = explode(" ", $nama);

// inisial
$inisial = "";
foreach ($words as $w) {
    if (!empty($w)) {
        $inisial .= strtoupper($w[0]);
    }
}

// =====================
// FORMAT BULAN INDONESIA
// =====================
$bulanIndo = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember"
];

$query = "
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
GROUP BY materi.id_materi
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Materi</title>

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

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
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

        .card-custom {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-box {
            width: 300px;
            border-radius: 20px;
            padding: 8px 15px;
            border: 1px solid #ddd;
        }

        .table-header {
            font-size: 13px;
            color: #9ca3af;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .item {
            padding: 18px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .judul {
            font-size: 16px;
            font-weight: 600;
        }

        .deskripsi {
            font-size: 13px;
            color: #6b7280;
        }

        .badge-kategori {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            color: white;
            font-weight: 500;
            display: inline-block;
        }

        .stat {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.8;
            text-align: center;
        }

        .aksi i {
            margin-right: 10px;
            cursor: pointer;
            font-size: 15px;
        }

        .lihat {
            color: #3b82f6;
        }

        .edit {
            color: #22c55e;
        }

        .hapus {
            color: #ef4444;
        }

        .grid {
            display: grid;
            grid-template-columns: 3fr 1.5fr 1.5fr 1.5fr 1fr 1fr;
            align-items: center;
            gap: 10px;
        }

        /* ===================== */
        /* PERATAAN KOLOM       */
        /* ===================== */
        .grid>div:nth-child(2),
        .grid>div:nth-child(4),
        .grid>div:nth-child(5),
        .grid>div:nth-child(6) {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h4>Admin Panel</h4>
        <p class="text-secondary">Dashboard Admin</p>

        <a href="dashboard.php"><i class="bi bi-house"></i> Dashboard</a>
        <a class="active" href="materi.php"><i class="bi bi-book"></i> Materi</a>
        <a href="pengguna.php"><i class="bi bi-people"></i> Pengguna</a>
        <a href="kategori.php"><i class="bi bi-folder"></i> Kategori</a>

        <hr>
        <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <div class="main">

        <div class="topbar">
            <h2>Materi</h2>

            <div class="d-flex align-items-center gap-3">
                <input type="text" class="search-box" placeholder="Cari materi atau penulis...">
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
                    <div class="avatar"><?php echo $inisial; ?></div>
                    <div>
                        <strong><?= $_SESSION['username'] ?></strong><br>
                        <small class="text-muted"><?= $_SESSION['nama_lengkap'] ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4">

            <div class="top-section mb-4">
                <div>
                    <h3><b>Manajemen Materi</b></h3>
                    <p class="text-muted">Kelola semua materi pembelajaran informatika</p>
                </div>

                <a href="tambah_materi.php" class="btn btn-primary">
                    + Tambah Materi
                </a>
            </div>

            <div class="card-custom">

                <h5 class="mb-3"><b>Daftar Materi</b></h5>

                <div class="grid table-header">
                    <div>JUDUL MATERI</div>
                    <div>KATEGORI</div>
                    <div>PENULIS</div>
                    <div>TANGGAL PUBLISH</div>
                    <div>STATISTIK</div>
                    <div>AKSI</div>
                </div>

                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <div class="grid item materi-item">

                        <div>
                            <div class="judul"><?= $row['judul']; ?></div>
                            <div class="deskripsi">
                                <?= substr($row['isi'], 0, 90); ?>...
                            </div>
                        </div>

                        <div>
                            <span class="badge-kategori" style="background: <?= $row['warna'] ?? '#6b7280' ?>">
                                <?= $row['nama_kategori'] ?? 'Tidak ada'; ?>
                            </span>
                        </div>

                        <div>
                            <?= $row['penulis'] ?? '-'; ?>
                        </div>

                        <!-- TANGGAL FORMAT INDONESIA -->
                        <div>
                            <?php
                            $tgl = strtotime($row['tanggal_publish']);
                            echo date('j', $tgl) . ' ' .
                                $bulanIndo[(int)date('n', $tgl) - 1] . ' ' .
                                date('Y', $tgl);
                            ?>
                        </div>

                        <!-- STATISTIK -->
                        <div class="d-flex align-items-center gap-3 mt-2 small text-muted">

                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-eye"></i>
                                <?= $row['views'] ?? 0 ?>
                            </span>

                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-download"></i>
                                <?= $row['unduhan'] ?? 0 ?>
                            </span>

                            <span class="d-flex align-items-center gap-1">
                                <i class="bi bi-bookmark-fill"></i>
                                <?= $row['total_bookmark'] ?>
                            </span>

                        </div>

                        <!-- AKSI -->
                        <div class="aksi">

                            <!-- EDIT -->
                            <a href="edit_materi.php?id=<?= $row['id_materi'] ?>" class="edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- HAPUS -->
                            <a href="hapus_materi.php?id=<?= $row['id_materi'] ?>"
                                class="hapus"
                                onclick="return confirm('Yakin ingin menghapus materi ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>

                    </div>
                <?php endwhile; ?>

            </div>

        </div>

        <script>
            // SEARCH MATERI
            document.querySelector(".search-box").addEventListener("keyup", function() {

                let keyword = this.value.toLowerCase();

                let items = document.querySelectorAll(".materi-item");

                items.forEach(item => {

                    // ambil judul
                    let judul = item.querySelector(".judul")
                        .innerText.toLowerCase();

                    // ambil penulis
                    let penulis = item.children[2]
                        .innerText.toLowerCase();

                    // cek pencarian
                    if (
                        judul.includes(keyword) ||
                        penulis.includes(keyword)
                    ) {
                        item.style.display = "grid";
                    } else {
                        item.style.display = "none";
                    }

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