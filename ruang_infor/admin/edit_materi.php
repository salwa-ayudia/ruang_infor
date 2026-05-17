<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// cek id
if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);

// ambil data materi
$materi = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT * FROM materi WHERE id_materi = $id
"));

if (!$materi) {
    die("Data tidak ditemukan");
}

// ambil kategori
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Materi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CKEDITOR -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

    <style>
        body {
            background: #ffffff;
            font-family: 'Segoe UI';
        }

        .wrapper {
            max-width: 900px;
            margin: 40px auto;
        }

        .card-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .form-control, .form-select {
            border-radius: 12px;
            padding: 10px;
        }

        .upload-box {
            border: 2px dashed #cbd5e1;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .upload-box:hover {
            border-color: #4f46e5;
            background: #eef2ff;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px 15px;
            margin-top: 10px;
        }

        .file-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon { font-size: 20px; }
        .file-name { font-size: 14px; font-weight: 500; }
        .file-size { font-size: 12px; color: gray; }

        .remove-btn {
            cursor: pointer;
            color: #ef4444;
            font-size: 18px;
        }

        .btn-primary {
            border-radius: 12px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #2563eb, #2563eb);
            border: none;
        }

        .btn-secondary {
            border-radius: 12px;
        }
    </style>
</head>

<body>

<div class="wrapper">
    <div class="card-box">

        <h3 class="mb-4 fw-semibold">Edit Materi</h3>

        <form method="POST" action="proses_edit_materi.php" enctype="multipart/form-data">

            <input type="hidden" name="id_materi" value="<?= $materi['id_materi'] ?>">

            <!-- JUDUL -->
            <div class="mb-3">
                <label>Judul Materi</label>
                <input type="text" name="judul" class="form-control"
                       value="<?= $materi['judul'] ?>" required>
            </div>

            <!-- KATEGORI + PENULIS -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <?php while ($k = mysqli_fetch_assoc($kategori)) : ?>
                            <option value="<?= $k['id_kategori'] ?>"
                                <?= $materi['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                                <?= $k['nama_kategori'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Penulis</label>
                    <!-- ✅ FIX: tampilkan penulis (bukan id_user) -->
                    <input type="text" name="penulis" class="form-control"
                           value="<?= $materi['penulis'] ?? '' ?>">
                </div>
            </div>

            <!-- ISI -->
            <div class="mb-3">
                <label>Ringkasan Materi</label>
                <textarea name="isi" id="editor"><?= $materi['isi'] ?></textarea>
            </div>

            <!-- FILE LAMA -->
            <?php if (!empty($materi['file'])): ?>
                <div class="mb-3">
                    <label>File Saat Ini</label>

                    <div class="file-item" id="fileLama">
                        <div class="file-left">
                            <span class="file-icon">📄</span>
                            <div>
                                <div class="file-name"><?= $materi['file'] ?></div>
                                <div class="file-size">File tersimpan</div>
                            </div>
                        </div>

                        <!-- ❌ tombol hapus -->
                        <span class="remove-btn" onclick="hapusFile()">✖</span>
                    </div>

                    <!-- flag hapus -->
                    <input type="hidden" name="hapus_file" id="hapus_file" value="0">
                </div>
            <?php endif; ?>

            <!-- FILE BARU -->
            <div class="mb-4">
                <label>Ganti File (Opsional)</label>

                <div class="upload-box" onclick="document.getElementById('file').click()">
                    <h5>⬆ Upload File</h5>
                    <p>Klik untuk pilih file</p>
                </div>

                <input type="file" name="file" id="file" hidden>
            </div>

            <!-- BUTTON -->
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary w-50">Update</button>
                <a href="materi.php" class="btn btn-secondary w-50">Batal</a>
            </div>

        </form>

    </div>
</div>

<script>
    CKEDITOR.replace('editor');

    function hapusFile() {
        document.getElementById("hapus_file").value = "1";
        document.getElementById("fileLama").style.display = "none";
    }
</script>

</body>
</html>