<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/packages.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT thumbnail FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$package = $result->fetch_assoc();
$stmt->close();

if (!$package) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/packages.php");
    exit;
}

if (!empty($package['thumbnail'])) {
    $filePath = __DIR__ . "/../../../../uploads/packages/" . $package['thumbnail'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

$stmt_rel = $conn->prepare("DELETE FROM package_services WHERE package_id = ?");
$stmt_rel->bind_param("i", $id);
$stmt_rel->execute();
$stmt_rel->close();

$stmt_del = $conn->prepare("DELETE FROM packages WHERE id = ?");
$stmt_del->bind_param("i", $id);
$stmt_del->execute();
$stmt_del->close();

header("Location: " . $BASE_URL . "public/views/admin/pages/packages.php");
exit;
