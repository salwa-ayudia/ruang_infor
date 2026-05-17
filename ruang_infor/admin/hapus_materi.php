<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// 🔐 proteksi admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// cek id
if (!isset($_GET['id'])) {
    header("Location: materi.php");
    exit;
}

$id = intval($_GET['id']);

// ambil data file dulu
$get = mysqli_query($conn, "SELECT file FROM materi WHERE id_materi = $id");
$data = mysqli_fetch_assoc($get);

if ($data) {

    // 🔥 hapus file kalau ada
    if (!empty($data['file'])) {
        $path = "../uploads/" . $data['file'];

        if (file_exists($path)) {
            unlink($path);
        }
    }

    // 🔥 hapus dari database
    mysqli_query($conn, "DELETE FROM materi WHERE id_materi = $id");
}

// redirect balik
header("Location: materi.php");
exit;
