<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
    $captcha_result = $_POST['captcha_result'];

    // VALIDASI CAPTCHA
    if ($captcha != $captcha_result) {
        echo "<script>alert('Captcha salah!');window.location='login.php';</script>";
        exit;
    }

    // ambil user
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {

        // cek password
        if (password_verify($password, $data['password'])) {

            // cek role admin
            if ($data['role'] == 'admin') {

                $_SESSION['login'] = true;
                $_SESSION['id_user'] = $data['id_user'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
                $_SESSION['role'] = $data['role'];

                header("Location: dashboard.php");
                exit;

            } else {
                echo "<script>alert('Bukan admin!');window.location='login.php';</script>";
            }

        } else {
            echo "<script>alert('Password salah!');window.location='login.php';</script>";
        }

    } else {
        echo "<script>alert('Username tidak ditemukan!');window.location='login.php';</script>";
    }
}
?>