<?php
require_once __DIR__ . "/../../../../middleware/user_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: " . $BASE_URL . "public/index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . $BASE_URL . "public/index.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];
$package_id = isset($_POST["package_id"]) ? (int) $_POST["package_id"] : null;
$service_id = isset($_POST["service_id"]) ? (int) $_POST["service_id"] : null;
$price = isset($_POST["price"]) ? (int) $_POST["price"] : null;
$notes = trim($_POST["notes"] ?? "");
$payment_method = trim($_POST["payment_method"] ?? "");

if (!$package_id || !$service_id || !$price || empty($payment_method)) {
    header("Location: " . $BASE_URL . "public/views/user/pages/orders.php?package_id=$package_id");
    exit;
}

$payment_proof = null;
if (!empty($_FILES["payment_proof"]["name"])) {
    $ext = pathinfo($_FILES["payment_proof"]["name"], PATHINFO_EXTENSION);
    $filename = "order_" . time() . "_" . rand(1000, 9999) . "." . $ext;
    $upload_path = __DIR__ . "/../../../../uploads/orders/" . $filename;
    move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $upload_path);
    $payment_proof = $filename;
}

$stmt = $conn->prepare("
    INSERT INTO orders (user_id, service_id, package_id, price, notes, payment_method, payment_proof, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'WAITING CONFIRMATION')
");

$stmt->bind_param("iiiisss", 
    $user_id,
    $service_id,
    $package_id,
    $price,
    $notes,
    $payment_method,
    $payment_proof
);

$stmt->execute();
$new_order_id = $stmt->insert_id;

$stmt->close();
$conn->close();

header("Location: " . $BASE_URL . "public/views/user/pages/order_status.php?id=$new_order_id");
exit;
