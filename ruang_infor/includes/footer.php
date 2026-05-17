<?php
$total_matkul = 0;
$total_materi = 0;
if (isset($koneksi)) {
    $r1 = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM mata_kuliah");
    if ($r1) $total_matkul = mysqli_fetch_assoc($r1)['total'];

    $r2 = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM materi");
    if ($r2) $total_materi = mysqli_fetch_assoc($r2)['total'];
}
?>

<footer class="ri-footer" role="contentinfo">

    <!-- Wave Divider -->
    <div class="ri-footer-wave" aria-hidden="true">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#1a3a5c" />
        </svg>
    </div>

    <!-- Main Footer -->
    <div class="ri-footer-main">
        <div class="container">
            <div class="row g-5">

                <!-- Kolom 1: Brand -->
                <div class="col-lg-4 col-md-6">
                    <a href="index.php" class="ri-footer-brand" aria-label="Ruang Infor - Halaman Utama">
                        <div class="ri-footer-logo-icon" aria-hidden="true">
                            <i class="bi bi-journal-code"></i>
                        </div>
                        <div>
                            <span class="brand-name">Ruang Infor</span>
                            <span class="brand-sub">Informatika S1</span>
                        </div>
                    </a>
                    <p class="ri-footer-desc">
                        Portal materi kuliah jurusan Informatika S1. Belajar lebih mudah, kapan saja dan di mana saja.
                    </p>

                    <!-- Statistik -->
                    <div class="ri-footer-stats">
                        <div class="stat-item">
                            <span class="stat-num"><?= $total_matkul ?>+</span>
                            <span class="stat-label">Mata Kuliah</span>
                        </div>
                        <div class="stat-divider" aria-hidden="true"></div>
                        <div class="stat-item">
                            <span class="stat-num"><?= $total_materi ?>+</span>
                            <span class="stat-label">Materi</span>
                        </div>
                        <div class="stat-divider" aria-hidden="true"></div>
                        <div class="stat-item">
                            <span class="stat-num">8</span>
                            <span class="stat-label">Semester</span>
                        </div>
                    </div>
                </div>

                <!-- Kolom 2: Navigasi -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h3 class="ri-footer-heading">Navigasi</h3>
                    <ul class="ri-footer-links" role="list">
                        <li><a href="index.php"><i class="bi bi-house-door" aria-hidden="true"></i> Beranda</a></li>
                        <li><a href="matakuliah.php"><i class="bi bi-book" aria-hidden="true"></i> Mata Kuliah</a></li>
                        <li><a href="pencarian.php"><i class="bi bi-search" aria-hidden="true"></i> Cari Materi</a></li>
                        <li><a href="pencarian.php"><i class="bi bi-compass" aria-hidden="true"></i> Jelajahi</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Semester -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h3 class="ri-footer-heading">Per Semester</h3>
                    <ul class="ri-footer-links" role="list">
                        <?php for ($s = 1; $s <= 8; $s++): ?>
                            <li>
                                <a href="matakuliah.php?semester=<?= $s ?>">
                                    <i class="bi bi-layers" aria-hidden="true"></i> Semester <?= $s ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </div>

                <!-- Kolom 4: Format Materi -->
                <div class="col-lg-4 col-md-6">
                    <h3 class="ri-footer-heading">Format Materi</h3>
                    <div class="ri-footer-formats">
                        <div class="format-card">
                            <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                            <div>
                                <span class="format-name">PDF / Modul</span>
                                <span class="format-desc">Unduh & baca offline</span>
                            </div>
                        </div>
                        <div class="format-card">
                            <i class="bi bi-play-circle" aria-hidden="true"></i>
                            <div>
                                <span class="format-name">Video</span>
                                <span class="format-desc">Tonton di YouTube</span>
                            </div>
                        </div>
                        <div class="format-card">
                            <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                            <div>
                                <span class="format-name">Artikel</span>
                                <span class="format-desc">Baca langsung</span>
                            </div>
                        </div>
                        <div class="format-card">
                            <i class="bi bi-link-45deg" aria-hidden="true"></i>
                            <div>
                                <span class="format-name">Link Referensi</span>
                                <span class="format-desc">Sumber eksternal</span>
                            </div>
                        </div>
                    </div>

                    <!-- Search kecil di footer -->
                    <form action="pencarian.php" method="GET" class="ri-footer-search mt-3" role="search" aria-label="Cari materi dari footer">
                        <input
                            type="search"
                            name="q"
                            placeholder="Cari materi..."
                            aria-label="Cari materi" />
                        <button type="submit" aria-label="Mulai pencarian">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="ri-footer-bottom">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <p class="mb-0" style="font-size:13px; color:rgba(255,255,255,0.5);">
                    &copy; <?= date('Y') ?> <strong style="color:rgba(255,255,255,0.8);">Ruang Infor</strong>
                    &mdash; Jurusan Informatika S1
                </p>
                <div class="d-flex align-items-center gap-3" style="font-size:12px; color:rgba(255,255,255,0.4);">
                    <span><i class="bi bi-stack me-1" aria-hidden="true"></i>PHP + MySQL</span>
                    <span><i class="bi bi-bootstrap me-1" aria-hidden="true"></i>Bootstrap 5</span>
                    <a href="admin/login.php" style="color:rgba(255,255,255,0.4); text-decoration:none; transition:color .2s;"
                        onmouseover="this.style.color='rgba(255,255,255,0.8)'"
                        onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                        <i class="bi bi-shield-lock me-1" aria-hidden="true"></i>Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

</footer>

<!-- ══════════════════════════════════════════
     FOOTER STYLES
══════════════════════════════════════════ -->
<style>
    .ri-footer {
        margin-top: 80px;
        position: relative;
    }

    /* Wave */
    .ri-footer-wave {
        display: block;
        line-height: 0;
        overflow: hidden;
        background: #f4f7fb;
    }

    .ri-footer-wave svg {
        display: block;
        width: 100%;
        height: 60px;
    }

    /* Main */
    .ri-footer-main {
        background: #1a3a5c;
        padding: 56px 0 40px;
    }

    /* Brand */
    .ri-footer-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        margin-bottom: 16px;
    }

    .ri-footer-logo-icon {
        width: 42px;
        height: 42px;
        background: rgba(55, 138, 221, 0.25);
        border: 1px solid rgba(55, 138, 221, 0.4);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #85B7EB;
        font-size: 20px;
        flex-shrink: 0;
        transition: background .2s;
    }

    .ri-footer-brand:hover .ri-footer-logo-icon {
        background: rgba(55, 138, 221, 0.4);
    }

    .brand-name {
        display: block;
        font-family: 'DM Serif Display', serif;
        font-size: 20px;
        color: #ffffff;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }

    .brand-sub {
        display: block;
        font-size: 10px;
        color: rgba(255, 255, 255, 0.45);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 600;
    }

    .ri-footer-desc {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.55);
        line-height: 1.7;
        margin-bottom: 20px;
    }

    /* Stats */
    .ri-footer-stats {
        display: flex;
        align-items: center;
        gap: 0;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 12px;
        padding: 14px 0;
        overflow: hidden;
    }

    .stat-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        padding: 0 12px;
    }

    .stat-num {
        font-size: 22px;
        font-weight: 700;
        color: #85B7EB;
        line-height: 1;
    }

    .stat-label {
        font-size: 11px;
        color: rgba(255, 255, 255, 0.45);
        font-weight: 500;
        letter-spacing: 0.03em;
    }

    .stat-divider {
        width: 1px;
        height: 36px;
        background: rgba(255, 255, 255, 0.10);
        flex-shrink: 0;
    }

    /* Heading */
    .ri-footer-heading {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.4);
        margin-bottom: 18px;
    }

    /* Links */
    .ri-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .ri-footer-links a {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        padding: 4px 0;
        transition: color .2s, padding-left .2s;
    }

    .ri-footer-links a:hover {
        color: #85B7EB;
        padding-left: 4px;
    }

    .ri-footer-links a i {
        font-size: 13px;
    }

    /* Format Cards */
    .ri-footer-formats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .format-card {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 10px 12px;
        transition: background .2s, border-color .2s;
    }

    .format-card:hover {
        background: rgba(55, 138, 221, 0.15);
        border-color: rgba(55, 138, 221, 0.3);
    }

    .format-card>i {
        font-size: 20px;
        color: #85B7EB;
        flex-shrink: 0;
    }

    .format-name {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.2;
    }

    .format-desc {
        display: block;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.4);
    }

    /* Footer Search */
    .ri-footer-search {
        display: flex;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.06);
        transition: border-color .2s;
    }

    .ri-footer-search:focus-within {
        border-color: rgba(55, 138, 221, 0.6);
        background: rgba(255, 255, 255, 0.10);
    }

    .ri-footer-search input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        padding: 9px 16px;
        font-size: 13px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: rgba(255, 255, 255, 0.8);
    }

    .ri-footer-search input::placeholder {
        color: rgba(255, 255, 255, 0.3);
    }

    .ri-footer-search button {
        background: #378ADD;
        border: none;
        padding: 0 16px;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: background .2s;
    }

    .ri-footer-search button:hover {
        background: #185FA5;
    }

    /* Bottom Bar */
    .ri-footer-bottom {
        background: #0f2540;
        padding: 16px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    /* Back to top button */
    .ri-back-top {
        position: fixed;
        bottom: 28px;
        right: 28px;
        width: 42px;
        height: 42px;
        background: #185FA5;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 16px rgba(24, 95, 165, 0.35);
        opacity: 0;
        transform: translateY(12px);
        transition: opacity .3s, transform .3s, background .2s;
        z-index: 900;
    }

    .ri-back-top.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .ri-back-top:hover {
        background: #1a3a5c;
    }
</style>

<!-- Back to Top Button -->
<button class="ri-back-top" id="backToTop" aria-label="Kembali ke atas">
    <i class="bi bi-arrow-up" aria-hidden="true"></i>
</button>

<script>
    (function() {
        const btn = document.getElementById('backToTop');

        window.addEventListener('scroll', function() {
            btn.classList.toggle('visible', window.scrollY > 300);
        }, {
            passive: true
        });

        btn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    })();
</script>

</body>

</html>