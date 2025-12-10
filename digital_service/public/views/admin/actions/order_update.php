<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . $BASE_URL . "public/views/admin/pages/orders.php");
    exit;
}

$order_id = $_POST['order_id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: " . $BASE_URL . "public/views/admin/pages/orders.php?updated=1");
exit;
