<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Layanan tidak valid!";
    header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
    exit;
}

$service_id = intval($_GET['id']);

$qThumb = $conn->prepare("SELECT thumbnail FROM services WHERE id = ?");
$qThumb->bind_param("i", $service_id);
$qThumb->execute();
$result = $qThumb->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data layanan tidak ditemukan!";
    header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
    exit;
}

$data = $result->fetch_assoc();
$thumbnail = $data['thumbnail'] ?? null;

$stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
$stmt->bind_param("i", $service_id);

if ($stmt->execute()) {
    if ($thumbnail && file_exists(__DIR__ . "/../../../uploads/services/" . $thumbnail)) {
        unlink(__DIR__ . "/../../../uploads/services/" . $thumbnail);
    }

    $_SESSION['success'] = "Layanan berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus layanan!";
}

header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
exit;
