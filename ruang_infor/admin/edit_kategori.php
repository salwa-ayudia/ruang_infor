<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// 🔐 proteksi
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// cek id
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori=$id"));

if (!$data) {
    die("Data tidak ditemukan");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Kategori</title>
    <meta charset="UTF-8">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI';
        }

        .container-box {
            max-width: 900px;
            margin: 40px auto;
        }

        .card-box {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {opacity:0; transform:translateY(10px);}
            to {opacity:1; transform:translateY(0);}
        }

        .preview-card {
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .preview-header {
            padding: 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-icon {
            font-size: 40px;
        }

        .preview-body {
            background: #f1f5f9;
            padding: 20px;
        }

        .color-option, .icon-option {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid transparent;
            margin: 5px;
            font-size: 28px;
        }

        .color-option.active, .icon-option.active {
            border: 3px solid #2563eb;
            transform: scale(1.1);
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            border-radius: 15px;
        }

        .btn-cancel {
            width: 100%;
            padding: 12px;
            border-radius: 15px;
            background: #e5e7eb;
            border: none;
        }
    </style>
</head>

<body>

<div class="container-box">
    <div class="card-box">

        <h3 class="mb-4">Edit Kategori</h3>

        <!-- PREVIEW -->
        <div class="preview-card">
            <div id="previewHeader" class="preview-header" style="background:<?= $data['warna'] ?>">
                
                <div>
                    <h4 id="previewNama"><?= $data['nama_kategori'] ?></h4>
                    <p>0 materi</p>
                </div>

                <div id="previewIcon" class="preview-icon"><?= $data['icon'] ?></div>

            </div>
            <div class="preview-body">
                <p id="previewDesk"><?= $data['deskripsi'] ?></p>
            </div>
        </div>

        <!-- FORM -->
        <form action="proses_edit_kategori.php" method="POST" onsubmit="return validasiForm()">

            <input type="hidden" name="id_kategori" value="<?= $data['id_kategori'] ?>">

            <div class="mb-3">
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" id="nama"
                       class="form-control" value="<?= $data['nama_kategori'] ?>" required>
            </div>

            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi"
                          class="form-control" rows="3"><?= $data['deskripsi'] ?></textarea>
            </div>

            <!-- ICON -->
            <label>Pilih Icon</label>
            <div class="d-flex flex-wrap mb-3">
                <?php
                $icons = ['💻','📊','📚','🎓','🛠','📄','🚀','⚡','🎯','🔥','💡','🎮','📁','🏆','⭐','📲'];
                foreach ($icons as $icon):
                ?>
                    <div class="icon-option <?= $data['icon']==$icon ? 'active' : '' ?>"
                         onclick="selectIcon(this,'<?= $icon ?>')">
                        <?= $icon ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="icon" id="icon" value="<?= $data['icon'] ?>">

            <!-- COLOR -->
            <label>Pilih Warna</label>
            <div class="d-flex flex-wrap mb-3">
                <?php
                $colors = ["#3b82f6","#22c55e","#9333ea","#f97316","#ef4444","#ec4899","#06b6d4","#6366f1","#eab308","#0ea5e9"];
                foreach ($colors as $c):
                ?>
                    <div class="color-option <?= $data['warna']==$c ? 'active' : '' ?>"
                         style="background:<?= $c ?>"
                         onclick="selectColor(this,'<?= $c ?>')"></div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="warna" id="warna" value="<?= $data['warna'] ?>">

            <!-- BUTTON -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <button class="btn btn-primary btn-submit">Update Kategori</button>
                </div>
                <div class="col-md-6">
                    <a href="kategori.php" class="btn btn-cancel">Batal</a>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
const nama = document.getElementById("nama");
const deskripsi = document.getElementById("deskripsi");

nama.addEventListener("input", () => {
    document.getElementById("previewNama").innerText = nama.value;
});

deskripsi.addEventListener("input", () => {
    document.getElementById("previewDesk").innerText = deskripsi.value;
});

function selectColor(el, color) {
    document.querySelectorAll('.color-option').forEach(e => e.classList.remove('active'));
    el.classList.add('active');
    document.getElementById("warna").value = color;
    document.getElementById("previewHeader").style.background = color;
}

function selectIcon(el, icon) {
    document.querySelectorAll('.icon-option').forEach(e => e.classList.remove('active'));
    el.classList.add('active');
    document.getElementById("icon").value = icon;
    document.getElementById("previewIcon").innerText = icon;
}

function validasiForm() {
    if (!document.getElementById("icon").value) {
        alert("Pilih icon dulu!");
        return false;
    }
    if (!document.getElementById("warna").value) {
        alert("Pilih warna dulu!");
        return false;
    }
    return true;
}
</script>

</body>
</html>