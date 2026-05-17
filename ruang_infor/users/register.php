<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// kalau sudah login
if (isset($_SESSION['login'])) {
    header("Location: dashboard.php");
    exit;
}

// REGISTER
if (isset($_POST['register'])) {

    $nama     = htmlspecialchars($_POST['nama_lengkap']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek username
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if (mysqli_num_rows($cek) > 0) {

        $error = "Username sudah digunakan!";
    } else {

        mysqli_query($conn, "
            INSERT INTO users
            (nama_lengkap, username, password, role, created_at)
            VALUES
            ('$nama', '$username', '$password', 'user', NOW())
        ");

        $_SESSION['success'] = "Registrasi berhasil!";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;

            display: flex;
            justify-content: center;
            align-items: center;

            overflow: hidden;
        }

        /* BACKGROUND */
        .bg-shape1 {
            position: absolute;
            width: 350px;
            height: 350px;
            background: #2563eb;
            border-radius: 50%;
            top: -120px;
            left: -120px;
            opacity: 0.15;
        }

        .bg-shape2 {
            position: absolute;
            width: 300px;
            height: 300px;
            background: #1e40af;
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
            opacity: 0.12;
        }

        /* CARD */
        .register-card {
            position: relative;
            z-index: 10;

            width: 330px;

            background: white;
            border-radius: 20px;

            padding: 24px;

            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .logo-box {
            width: 55px;
            height: 55px;

            margin: auto;
            margin-bottom: 14px;

            border-radius: 16px;

            background: linear-gradient(135deg, #2563eb, #1d4ed8);

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            font-size: 24px;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 18px;
            font-size: 12px;
        }

        .form-label {
            font-size: 13px;
            margin-bottom: 5px;
        }

        .form-control {
            height: 42px;
            border-radius: 12px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.15rem rgba(37, 99, 235, 0.2);
        }

        .btn-register {
            height: 42px;
            border-radius: 12px;

            font-size: 14px;
            font-weight: 600;
        }

        .mb-3 {
            margin-bottom: 14px !important;
        }

        .mb-4 {
            margin-bottom: 18px !important;
        }

        .btn-register:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .login-link {
            margin-top: 16px;
            font-size: 13px;
            text-align: center;
        }

        .login-link a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none !important;
        }

        .login-link a:hover {
            text-decoration: none !important;
            color: #1d4ed8;
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            padding: 0 12px;
        }

        .input-group .form-control {
            border-left: none;
        }

        .alert-danger {
            border-radius: 14px;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <!-- BACKGROUND -->
    <div class="bg-shape1"></div>
    <div class="bg-shape2"></div>

    <!-- REGISTER CARD -->
    <div class="register-card">

        <div class="logo-box">
            <i class="bi bi-person-plus"></i>
        </div>

        <h2 class="title">
            Buat Akun
        </h2>

        <p class="subtitle">
            Daftar untuk mulai menggunakan sistem
        </p>

        <!-- ERROR -->
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <!-- FORM -->
        <form method="POST">

            <!-- NAMA -->
            <div class="mb-3">

                <label class="form-label">
                    Nama Lengkap
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-person-badge"></i>
                    </span>

                    <input type="text"
                        name="nama_lengkap"
                        class="form-control"
                        placeholder="Masukkan nama lengkap"
                        required>

                </div>

            </div>

            <!-- USERNAME -->
            <div class="mb-3">

                <label class="form-label">
                    Username
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>

                    <input type="text"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                        required>

                </div>

            </div>

            <!-- PASSWORD -->
            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>

                    <input type="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        required>

                </div>

            </div>

            <!-- BUTTON -->
            <button type="submit" name="register"
                class="btn btn-primary btn-register w-100">

                <i class="bi bi-person-check"></i>
                Daftar

            </button>

        </form>

        <!-- LOGIN -->
        <div class="login-link">
            Sudah punya akun?
            <a href="login.php">Login</a>
        </div>

    </div>

</body>

</html>