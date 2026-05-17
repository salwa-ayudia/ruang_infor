<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// 🔐 proteksi admin
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// =====================
// AMBIL DATA
// =====================
$id        = intval($_POST['id_materi']);
$judul     = mysqli_real_escape_string($conn, $_POST['judul']);
$kategori  = intval($_POST['kategori']);
$penulis   = mysqli_real_escape_string($conn, $_POST['penulis']);
$isi       = mysqli_real_escape_string($conn, $_POST['isi']);
$hapusFile = $_POST['hapus_file'] ?? 0;

// =====================
// AMBIL FILE LAMA
// =====================
$get = mysqli_query($conn, "SELECT file FROM materi WHERE id_materi = $id");
$old = mysqli_fetch_assoc($get);

$file_lama = $old['file'] ?? null;
$file_baru = $file_lama;

// =====================
// FOLDER UPLOAD
// =====================
$upload_dir = "../uploads/";

// =====================
// CEK UPLOAD FILE BARU
// =====================
if (!empty($_FILES['file']['name'])) {

    $namaFile   = $_FILES['file']['name'];
    $tmpFile    = $_FILES['file']['tmp_name'];
    $size       = $_FILES['file']['size'];
    $error      = $_FILES['file']['error'];

    // ambil ekstensi
    $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

    // validasi (optional tapi disarankan)
    $allowed = ['pdf'];

    if (in_array($ext, $allowed) && $error === 0) {

        // buat nama unik
        $newName = time() . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '_', $namaFile);

        // upload file
        if (move_uploaded_file($tmpFile, $upload_dir . $newName)) {

            // hapus file lama
            if (!empty($file_lama) && file_exists($upload_dir . $file_lama)) {
                unlink($upload_dir . $file_lama);
            }

            $file_baru = $newName;
        }
    }
}

// =====================
// CEK HAPUS FILE MANUAL
// =====================
if ($hapusFile == 1) {
    if (!empty($file_lama) && file_exists($upload_dir . $file_lama)) {
        unlink($upload_dir . $file_lama);
    }
    $file_baru = null;
}

// =====================
// UPDATE DATABASE
// =====================
$query = mysqli_query($conn, "
UPDATE materi SET
    judul = '$judul',
    id_kategori = '$kategori',
    penulis = '$penulis',
    isi = '$isi',
    file = " . ($file_baru ? "'$file_baru'" : "NULL") . "
WHERE id_materi = '$id'
");

// =====================
// REDIRECT
// =====================
header("Location: materi.php");
exit;
