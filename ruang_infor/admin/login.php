<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e3a5f, #3b6ea5, #dbe3ec);
            height: 100vh;
        }
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 900px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .left-panel {
            background: #1e3a5f;
            color: white;
            padding: 40px;
        }
        .right-panel {
            background: #f8f9fa;
            padding: 40px;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px;
        }
        .btn-primary {
            background: #1e3a5f;
            border: none;
            border-radius: 12px;
            padding: 12px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background: #274d7d;
        }
        .captcha-box {
            background: #e9f1fb;
            border-radius: 12px;
            padding: 10px;
        }
    </style>
</head>

<body>

<div class="login-container">
    <div class="row login-card">

        <!-- LEFT -->
        <div class="col-md-5 left-panel d-flex flex-column justify-content-center">
            <h2 class="fw-bold mt-4">Ruang Infor</h2>
            <p class="mb-4">INFORMATIKA S1</p>

            <h2>Area Khusus Admin</h2>
            <p>Kelola konten dan materi melalui panel administrasi yang aman.</p>

            <ul class="mt-4">
                <li>✔ Kelola materi</li>
                <li>✔ Kelola Kategori</li>
            </ul>
        </div>

        <!-- RIGHT -->
        <div class="col-md-7 right-panel">
            <h3 class="fw-bold mb-2">Selamat Datang!</h3>
            <p class="text-muted">Silahkan login untuk masuk ke dashboard admin</p>

            <form action="proses_login.php" method="POST">

                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>
                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁</button>
                    </div>
                </div>

                <!-- CAPTCHA -->
                <div class="mb-3 captcha-box d-flex align-items-center justify-content-between">
                    <div>
                        <strong id="num1"></strong> + <strong id="num2"></strong>
                    </div>

                    <input type="number" name="captcha" id="captcha" class="form-control w-50" required>

                    <input type="hidden" name="captcha_result" id="captcha_result">
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">
                    Login
                </button>

            </form>
        </div>

    </div>
</div>

<script>
let num1 = Math.floor(Math.random() * 10);
let num2 = Math.floor(Math.random() * 10);

document.getElementById("num1").innerText = num1;
document.getElementById("num2").innerText = num2;
document.getElementById("captcha_result").value = num1 + num2;

function togglePassword() {
    let pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>