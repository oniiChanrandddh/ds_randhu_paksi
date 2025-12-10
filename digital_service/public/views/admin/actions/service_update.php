<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";
require_once __DIR__ . "/../../../../middleware/admin_guard.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Metode tidak diizinkan!";
    header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    $_SESSION['error'] = "Permintaan tidak valid!";
    header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
    exit;
}

$id = intval($_POST['id']);
$title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
$description = isset($_POST["description"]) ? trim($_POST["description"]) : "";

$rawPrice = null;
if (isset($_POST['base_price'])) {
    $rawPrice = $_POST['base_price'];
} elseif (isset($_POST['price'])) {
    $rawPrice = $_POST['price'];
}

if ($rawPrice === null || $rawPrice === "") {
    $_SESSION['error'] = "Harga dasar wajib diisi.";
    header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
    exit;
}

$rawPrice = str_replace(['.', ',',' '], '', $rawPrice);
if (!is_numeric($rawPrice)) {
    $_SESSION['error'] = "Format harga tidak valid.";
    header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
    exit;
}

$base_price = intval($rawPrice);

$is_active = isset($_POST["is_active"]) ? intval($_POST["is_active"]) : 1;
if ($is_active !== 0 && $is_active !== 1) {
    $is_active = 1;
}

$q = $conn->query("SELECT thumbnail FROM services WHERE id = $id LIMIT 1");
if (!$q || $q->num_rows === 0) {
    $_SESSION['error'] = "Data layanan tidak ditemukan!";
    header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
    exit;
}

$service = $q->fetch_assoc();
$thumbnail = $service['thumbnail'];

if (!empty($_FILES["thumbnail"]["name"])) {
    $file = $_FILES["thumbnail"];
    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png"];

    if (in_array($ext, $allowed)) {
        $newName = uniqid("srv_") . "." . $ext;
        $uploadPath = __DIR__ . "/../../../../uploads/services/" . $newName;

        if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
            $thumbnail = $newName;
            if ($service["thumbnail"] && file_exists(__DIR__ . "/../../../../uploads/services/" . $service["thumbnail"])) {
                unlink(__DIR__ . "/../../../../uploads/services/" . $service["thumbnail"]);
            }
        }
    }
}

$stmt = $conn->prepare("UPDATE services SET title=?, description=?, base_price=?, thumbnail=?, is_active=? WHERE id=?");
$stmt->bind_param("ssisii", $title, $description, $base_price, $thumbnail, $is_active, $id);
$stmt->execute();

$_SESSION['success'] = "Layanan berhasil diperbarui!";
header("Location: " . $BASE_URL . "public/views/admin/pages/services.php");
exit;
