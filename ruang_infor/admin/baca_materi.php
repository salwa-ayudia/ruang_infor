<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: materi.php");
    exit;
}

$id = $_GET['id'];

// ambil data
$query = mysqli_query($conn, "
SELECT m.*, k.nama_kategori, u.nama_lengkap 
FROM materi m
LEFT JOIN kategori k ON m.id_kategori = k.id_kategori
LEFT JOIN users u ON m.id_user = u.id_user
WHERE m.id_materi = '$id'
");

$data = mysqli_fetch_assoc($query);

// tambah views
mysqli_query($conn, "UPDATE materi SET views = views + 1 WHERE id_materi = '$id'");

// format teks biar lebih rapi
$isi = nl2br(htmlspecialchars($data['isi']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $data['judul'] ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f5f7fb;
    font-family: 'Segoe UI', sans-serif;
}

/* CONTAINER */
.wrapper {
    max-width: 900px;
    margin: 40px auto;
}

/* CARD */
.card-custom {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}

/* HEADER */
.judul {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 10px;
}

.meta {
    font-size: 14px;
    color: #6b7280;
}

/* BADGE */
.badge-kategori {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    color: white;
}

/* CONTENT */
.konten {
    margin-top: 25px;
    line-height: 1.9;
    font-size: 15px;
    color: #374151;
    white-space: normal;
}

/* PARAGRAPH */
.konten p {
    margin-bottom: 15px;
}

/* BUTTON */
.btn-back {
    margin-bottom: 20px;
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="wrapper">

    <a href="materi.php" class="btn btn-secondary btn-back">← Kembali</a>

    <div class="card-custom">

        <!-- HEADER -->
        <div class="judul"><?= $data['judul'] ?></div>

        <div class="meta mb-3">
            ✍️ <?= $data['nama_lengkap'] ?? '-' ?> • 
            📅 <?= date('d M Y', strtotime($data['tanggal_publish'])) ?> • 
            👁 <?= $data['views'] + 1 ?>
        </div>

        <div class="mb-3">
            <span class="badge-kategori" style="background: <?= $data['warna'] ?? '#6b7280' ?>">
                <?= $data['nama_kategori'] ?? 'Tidak ada kategori' ?>
            </span>
        </div>

        <hr>

        <!-- ISI -->
        <div class="konten">
            <?= $isi ?>
        </div>

    </div>

</div>

</body>
</html>