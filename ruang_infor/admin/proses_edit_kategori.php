<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// 🔐 proteksi admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// validasi request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kategori.php");
    exit;
}

// ambil data
$id         = intval($_POST['id_kategori'] ?? 0);
$nama       = trim($_POST['nama_kategori'] ?? '');
$deskripsi  = trim($_POST['deskripsi'] ?? '');
$icon       = trim($_POST['icon'] ?? '');
$warna      = trim($_POST['warna'] ?? '');

// validasi sederhana
if ($id <= 0 || $nama === '' || $icon === '' || $warna === '') {
    header("Location: edit_kategori.php?id=$id");
    exit;
}

// amankan input
$nama      = mysqli_real_escape_string($conn, $nama);
$deskripsi = mysqli_real_escape_string($conn, $deskripsi);
$icon      = mysqli_real_escape_string($conn, $icon);
$warna     = mysqli_real_escape_string($conn, $warna);

// update
mysqli_query($conn, "
UPDATE kategori SET
    nama_kategori = '$nama',
    deskripsi     = '$deskripsi',
    icon          = '$icon',
    warna         = '$warna'
WHERE id_kategori = $id
");

// redirect tanpa alert
header("Location: kategori.php");
exit;