<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Materi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CKEDITOR -->
    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #ffffff, #ffffff);
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

        /* INPUT */
        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 10px;
        }

        /* UPLOAD */
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

        /* PREVIEW */
        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px 15px;
            margin-top: 10px;
            animation: fadeIn 0.3s ease;
        }

        .file-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-icon {
            font-size: 20px;
        }

        .file-name {
            font-size: 14px;
            font-weight: 500;
        }

        .file-size {
            font-size: 12px;
            color: gray;
        }

        .remove-btn {
            cursor: pointer;
            color: #ef4444;
            font-size: 18px;
        }

        .remove-btn:hover {
            transform: scale(1.2);
        }

        /* BUTTON */
        .btn-primary {
            border-radius: 12px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #2563eb, #2563eb);
            border: none;
        }

        .btn-secondary {
            border-radius: 12px;
        }

        /* ANIMASI */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="card-box">

            <h3 class="mb-4 fw-semibold">Tambah Materi Baru</h3>

            <form method="POST" action="proses_tambah_materi.php" enctype="multipart/form-data">

                <!-- JUDUL -->
                <div class="mb-3">
                    <label>Judul Materi <span class="text-danger">*</span></label>
                    <input type="text" name="judul" class="form-control" required>
                </div>

                <!-- KATEGORI + PENULIS -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while ($k = mysqli_fetch_assoc($kategori)) : ?>
                                <option value="<?= $k['id_kategori'] ?>">
                                    <?= $k['nama_kategori'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Penulis <span class="text-danger">*</span></label>
                        <input type="text" name="penulis" class="form-control" required>
                    </div>
                </div>

                <!-- RINGKASAN -->
                <div class="mb-3">
                    <label>Ringkasan Materi <span class="text-danger">*</span></label>
                    <textarea name="isi" id="editor"></textarea>
                    <small class="text-muted">Isi ringkasan saja</small>
                </div>

                <!-- FILE -->
                <div class="mb-4">
                    <label>Upload File Materi (Opsional)</label>

                    <div class="upload-box" onclick="document.getElementById('file').click()">
                        <h5>⬆ Upload File</h5>
                        <p>Klik untuk pilih file atau upload banyak sekaligus</p>
                        <small>Hanya PDF (Max 10MB per file)</small>
                    </div>

                    <input type="file" name="file[]" id="file" multiple hidden onchange="previewFiles()">

                    <!-- PREVIEW -->
                    <div id="preview-container"></div>
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-3">
                    <button type="submit" name="simpan" class="btn btn-primary w-50">Simpan</button>
                    <a href="materi.php" class="btn btn-secondary w-50">Batal</a>
                </div>

            </form>

        </div>
    </div>

    <script>
        CKEDITOR.replace('editor');

        let selectedFiles = [];

        function previewFiles() {
            const input = document.getElementById('file');

            for (let i = 0; i < input.files.length; i++) {
                selectedFiles.push(input.files[i]);
            }

            renderPreview();
        }

        function renderPreview() {
            const container = document.getElementById('preview-container');
            container.innerHTML = "";

            selectedFiles.forEach((file, index) => {

                let size = (file.size / 1024 / 1024).toFixed(2) + " MB";

                const div = document.createElement("div");
                div.classList.add("file-item");

                div.innerHTML = `
            <div class="file-left">
                <span class="file-icon">📄</span>
                <div>
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${size}</div>
                </div>
            </div>
            <span class="remove-btn" onclick="removeFile(${index})">✖</span>
        `;

                container.appendChild(div);
            });
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderPreview();
        }
    </script>

</body>

</html>