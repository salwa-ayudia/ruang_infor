<?php
require_once __DIR__ . '/../config/koneksi.php';

$nama = $_POST['nama_kategori'];
$deskripsi = $_POST['deskripsi'];
$icon = $_POST['icon'];
$warna = $_POST['warna'];

mysqli_query($conn, "
INSERT INTO kategori (nama_kategori, deskripsi, icon, warna)
VALUES ('$nama', '$deskripsi', '$icon', '$warna')
");

header("Location: kategori.php");