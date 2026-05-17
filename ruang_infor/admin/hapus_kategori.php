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
    header("Location: kategori.php");
    exit;
}

$id = intval($_GET['id']);

// hapus kategori
mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori = $id");

// redirect
header("Location: kategori.php");
exit;