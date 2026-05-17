<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// CEK ID
if (!isset($_GET['id'])) {

    header("Location: home.php");
    exit;
}

$id = intval($_GET['id']);

// AMBIL DATA FILE
$query = mysqli_query($conn, "
SELECT * FROM materi
WHERE id_materi = '$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {

    header("Location: home.php");
    exit;
}

// UPDATE TOTAL DOWNLOAD
mysqli_query($conn, "
UPDATE materi
SET unduhan = unduhan + 1
WHERE id_materi = '$id'
");

// LOKASI FILE
$file = "../uploads/" . $data['file'];

// CEK FILE ADA
if (file_exists($file)) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');

    header(
        'Content-Disposition: attachment; filename="' .
            basename($file) . '"'
    );

    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    header('Content-Length: ' . filesize($file));

    readfile($file);
    exit;
}

// JIKA FILE TIDAK ADA
echo "
<script>
    alert('File tidak ditemukan!');
    window.history.back();
</script>
";
?>