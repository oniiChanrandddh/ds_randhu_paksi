<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: {$BASE_URL}public/views/admin/pages/users.php");
    exit;
}

$id = intval($_GET['id']);

if ($id == $_SESSION['user']['id']) {
    $_SESSION['error'] = "Tidak dapat menghapus akun diri sendiri!";
    header("Location: {$BASE_URL}public/views/admin/pages/users.php");
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: {$BASE_URL}public/views/admin/pages/users.php");
exit;
