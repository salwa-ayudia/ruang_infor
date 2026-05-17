<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// ambil data form
$username = $_POST['username'];
$password = $_POST['password'];

// cek username
$query = mysqli_query($conn, "
SELECT * FROM users 
WHERE username = '$username'
");

$data = mysqli_fetch_assoc($query);

// cek user ditemukan
if ($data) {

    // cek password
    if (password_verify($password, $data['password'])) {

        // simpan session
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role'];

        // arahkan sesuai role
        if ($data['role'] == 'admin') {

            header("Location: dashboard.php");
            exit;
        } else {

            header("Location: home.php");
            exit;
        }
    } else {

        echo "
        <script>
            alert('Password salah!');
            window.location='login.php';
        </script>
        ";
    }
} else {

    echo "
    <script>
        alert('Username tidak ditemukan!');
        window.location='login.php';
    </script>
    ";
}
