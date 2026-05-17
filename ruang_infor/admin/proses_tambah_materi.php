<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// 🔐 proteksi
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $id_kategori = $_POST['kategori'];
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);

    // validasi wajib
    if ($judul == "" || $isi == "" || $id_kategori == "" || $penulis == "") {
        echo "<script>alert('Semua field wajib harus diisi!'); window.history.back();</script>";
        exit;
    }

    // ================= FILE MULTI =================
    $folder = "../uploads/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $files = $_FILES['file'];
    $nama_files = [];

    // cek apakah ada file yang diupload
    if (!empty($files['name'][0])) {

        $total = count($files['name']);

        for ($i = 0; $i < $total; $i++) {

            $nama  = $files['name'][$i];
            $tmp   = $files['tmp_name'][$i];
            $error = $files['error'][$i];

            if ($error !== 0) {
                echo "<script>alert('Terjadi error saat upload file!'); window.history.back();</script>";
                exit;
            }

            // validasi ekstensi
            $ext = strtolower(pathinfo($nama, PATHINFO_EXTENSION));

            if ($ext != 'pdf') {
                echo "<script>alert('Semua file harus PDF!'); window.history.back();</script>";
                exit;
            }

            // rename file biar unik
            $nama_baru = time() . '_' . uniqid() . '_' . $nama;

            if (!move_uploaded_file($tmp, $folder . $nama_baru)) {
                echo "<script>alert('Gagal upload file!'); window.history.back();</script>";
                exit;
            }

            $nama_files[] = $nama_baru;
        }
    }

    // gabungkan jadi string
    $file_simpan = !empty($nama_files) ? implode(',', $nama_files) : null;

    // ================= INSERT =================
    $query = "INSERT INTO materi 
    (id_kategori, judul, isi, file, penulis, views, unduhan) 
    VALUES 
    ('$id_kategori','$judul','$isi','$file_simpan','$penulis',0,0)";

    if (mysqli_query($conn, $query)) {
        echo "<script>
            alert('Materi berhasil ditambahkan!');
            window.location='materi.php';
        </script>";
    } else {
        echo "<script>alert('Gagal menyimpan data'); window.history.back();</script>";
    }

} else {
    header("Location: tambah_materi.php");
}