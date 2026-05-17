    <?php
    session_start();
    require_once __DIR__ . '/../config/koneksi.php';

    // 🔐 PROTEKSI ADMIN
    if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
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
    // STATISTIK
    // =====================
    $total_materi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM materi"))['total'];

    $total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kategori"))['total'];

    $total_views = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(views),0) as total FROM materi"))['total'];

    $total_unduhan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(unduhan),0) as total FROM materi"))['total'];


    // =====================
    // MATERI TERBARU
    // =====================
    $materi = mysqli_query($conn, "
    SELECT 
        materi.*, 
        kategori.nama_kategori,
        COUNT(bookmark.id) as total_bookmark
    FROM materi 
    LEFT JOIN kategori ON materi.id_kategori = kategori.id_kategori
    LEFT JOIN bookmark ON materi.id_materi = bookmark.id_materi
    GROUP BY materi.id_materi
    ORDER BY tanggal_publish DESC 
    LIMIT 5
    ");


    // =====================
    // CHART KATEGORI
    // =====================
    $query_chart = mysqli_query($conn, "
    SELECT kategori.nama_kategori, COUNT(materi.id_materi) as total
    FROM kategori
    LEFT JOIN materi ON kategori.id_kategori = materi.id_kategori
    GROUP BY kategori.id_kategori
    ");

    $label = [];
    $data = [];

    while ($row = mysqli_fetch_assoc($query_chart)) {
        $label[] = $row['nama_kategori'] ?? 'Tanpa Kategori';
        $data[] = (int)$row['total'];
    }

    if (empty($data)) {
        $label = ['Belum ada data'];
        $data = [1];
    }


    // =====================
    // CHART BULANAN (REAL DATA)
    // =====================
    $query_bulanan = mysqli_query($conn, "
    SELECT MONTH(tanggal_publish) as bulan, COUNT(*) as total
    FROM materi
    GROUP BY MONTH(tanggal_publish)
    ORDER BY bulan
    ");

    $bulan_label = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $data_bulanan = array_fill(0, 12, 0);

    while ($row = mysqli_fetch_assoc($query_bulanan)) {
        $index = (int)$row['bulan'] - 1;
        $data_bulanan[$index] = (int)$row['total'];
    }
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Dashboard Admin</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            body {
                background: #f5f7fb;
                font-family: 'Segoe UI';
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

            .card-box {
                background: white;
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                transition: 0.3s;
            }

            .card-box:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .chart-wrapper canvas {
                max-height: 250px;
            }

            .badge-aktif {
                background: #d1fae5;
                color: #065f46;
                padding: 5px 12px;
                border-radius: 20px;
            }

            .badge-draft {
                background: #fef3c7;
                color: #92400e;
                padding: 5px 12px;
                border-radius: 20px;
            }
        </style>
    </head>

    <body>

        <div class="sidebar">
            <h4>Admin Panel</h4>
            <p class="text-secondary">Dashboard Admin</p>

            <a class="active"><i class="bi bi-house"></i> Dashboard</a>
            <a href="materi.php"><i class="bi bi-book"></i> Materi</a>
            <a href="pengguna.php"><i class="bi bi-people"></i> Pengguna</a>
            <a href="kategori.php"><i class="bi bi-folder"></i> Kategori</a>

            <hr>
            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <div class="main">

            <div class="topbar">
                <h2>Dashboard</h2>

                <div class="d-flex align-items-center gap-3">
                    <input type="text" id="searchTopbar" class="search-box" placeholder="Cari materi terbaru...">
                        <!-- =========================
                        NOTIFICATION
                        ========================= -->

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

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card-box">
                        <h6>Total Materi</h6>
                        <h2><?= $total_materi ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-box">
                        <h6>Total Kategori</h6>
                        <h2><?= $total_kategori ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-box">
                        <h6>Total Unduhan</h6>
                        <h2><?= $total_unduhan ?></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-box">
                        <h6>Total Views</h6>
                        <h2><?= $total_views ?></h2>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="card-box">
                        <h5>Distribusi Kategori</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartKategori"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card-box">
                        <h5>Materi per Bulan</h5>
                        <div class="chart-wrapper">
                            <canvas id="chartBulanan"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-box mt-4">
                <h5>Materi Terbaru</h5>

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Judul Materi</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Views</th>
                            <th>Unduhan</th>
                            <th>Bookmark</th>
                        </tr>
                    </thead>

                    <!-- PINDAH ID KE SINI -->
                    <tbody id="tableMateri">

                        <?php while ($d = mysqli_fetch_assoc($materi)): ?>
                            <tr>
                                <td><?= $d['judul'] ?></td>
                                <td><?= $d['nama_kategori'] ?? '-' ?></td>
                                <td><?= date('d M Y', strtotime($d['tanggal_publish'])) ?></td>
                                <td><?= $d['views'] ?? 0 ?></td>
                                <td><?= $d['unduhan'] ?? 0 ?></td>

                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-bookmark-fill"></i>
                                        <?= $d['total_bookmark'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>

                </table>
            </div>

        </div>

        <script>
            document.getElementById("searchTopbar").addEventListener("keyup", function() {
                let keyword = this.value.toLowerCase();
                let rows = document.querySelectorAll("#tableMateri tr");

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(keyword) ? "" : "none";
                });
            });

            new Chart(document.getElementById('chartKategori'), {
                type: 'pie',
                data: {
                    labels: <?= json_encode($label) ?>,
                    datasets: [{
                        data: <?= json_encode($data) ?>,
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
                    }]
                }
            });

            new Chart(document.getElementById('chartBulanan'), {
                type: 'line',
                data: {
                    labels: <?= json_encode($bulan_label) ?>,
                    datasets: [{
                        label: 'Jumlah Materi', // 🔥 INI SOLUSINYA
                        data: <?= json_encode($data_bulanan) ?>,
                        borderColor: '#3b82f6',
                        fill: false,
                        tension: 0.4
                    }]
                }
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