<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: {$BASE_URL}public/views/admin/pages/portfolios.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT thumbnail FROM portfolios WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: {$BASE_URL}public/views/admin/pages/portfolios.php");
    exit;
}

if (!empty($data['thumbnail'])) {
    $filePath = __DIR__ . "/../../../../uploads/portfolios/" . $data['thumbnail'];
    if (file_exists($filePath)) unlink($filePath);
}

$stmtDel = $conn->prepare("DELETE FROM portfolios WHERE id=?");
$stmtDel->bind_param("i", $id);
$stmtDel->execute();

header("Location: {$BASE_URL}public/views/admin/pages/portfolios.php");
exit;
