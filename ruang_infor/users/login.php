<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
        .login-card {
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
            font-weight: 600;
            color: #334155;
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

        .btn-login {
            height: 42px;
            border-radius: 12px;
            font-size: 14px;
        }

        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #64748b;
            font-size: 14px;
        }

        .register-link a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .input-group-text {
            border-radius: 14px 0 0 14px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
        }

        .input-group .form-control {
            border-left: none;
        }
    </style>
</head>

<body>

    <!-- BACKGROUND -->
    <div class="bg-shape1"></div>
    <div class="bg-shape2"></div>

    <!-- LOGIN CARD -->
    <div class="login-card">

        <div class="logo-box">
            <i class="bi bi-shield-lock"></i>
        </div>

        <h2 class="title">
            Selamat datang!
        </h2>

        <p class="subtitle">
            Silakan login untuk melanjutkan
        </p>

        <!-- FORM -->
        <form action="proses_login.php" method="POST">

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
            <button type="submit" class="btn btn-primary btn-login w-100">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </button>

        </form>

        <!-- REGISTER -->
        <div class="register-link">
            Belum punya akun?
            <a href="register.php">
                Register
            </a>
        </div>

    </div>

</body>

</html>