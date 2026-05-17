<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    exit;
}

$id_user = $_SESSION['id_user'];
$id_materi = intval($_POST['id_materi']);

$cek = mysqli_query($conn, "
SELECT * FROM bookmark
WHERE id_user = '$id_user'
AND id_materi = '$id_materi'
");

if (mysqli_num_rows($cek) > 0) {

    mysqli_query($conn, "
    DELETE FROM bookmark
    WHERE id_user = '$id_user'
    AND id_materi = '$id_materi'
    ");

    $status = "removed";

} else {

    mysqli_query($conn, "
    INSERT INTO bookmark(
        id_user,
        id_materi
    ) VALUES (
        '$id_user',
        '$id_materi'
    )
    ");

    $status = "added";
}

// TOTAL BOOKMARK BARU
$total = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as total
FROM bookmark
WHERE id_materi = '$id_materi'
"))['total'];

echo json_encode([
    'status' => $status,
    'total' => $total
]);