<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/orders.php");
    exit;
}

$order_id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();

$_SESSION['success'] = "Pesanan berhasil dihapus!";
header("Location: " . $BASE_URL . "public/views/admin/pages/orders.php");
exit;
