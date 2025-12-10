<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$package_id = intval($_POST['package_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$preview_url = trim($_POST['preview_url'] ?? '');

if ($package_id <= 0 || $title === '' || $description === '') {
    $_SESSION['error'] = "Semua field wajib diisi kecuali URL dan Thumbnail!";
    header("Location: {$BASE_URL}public/views/admin/pages/add_portfolio.php");
    exit;
}

$uploadDir = __DIR__ . "/../../../../uploads/portfolios/";
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

$newThumb = null;

if (!empty($_FILES['thumbnail']['name'])) {
    $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $_SESSION['error'] = "Thumbnail harus JPG/PNG!";
        header("Location: {$BASE_URL}public/views/admin/pages/add_portfolio.php");
        exit;
    }
    if ($_FILES['thumbnail']['size'] > 2 * 1024 * 1024) {
        $_SESSION['error'] = "Ukuran gambar maksimal 2MB!";
        header("Location: {$BASE_URL}public/views/admin/pages/add_portfolio.php");
        exit;
    }

    $newThumb = time() . "_" . rand(1000, 9999) . "." . $ext;
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir . $newThumb);
}

$stmt = $conn->prepare("INSERT INTO portfolios (package_id, title, description, thumbnail, preview_url) VALUES (?,?,?,?,?)");
$stmt->bind_param("issss", $package_id, $title, $description, $newThumb, $preview_url);

if ($stmt->execute()) {
    $_SESSION['success'] = "Portfolio berhasil ditambahkan!";
    header("Location: {$BASE_URL}public/views/admin/pages/portfolios.php");
    exit;
}

$_SESSION['error'] = "Gagal menambahkan portfolio!";
header("Location: {$BASE_URL}public/views/admin/pages/add_portfolio.php");
exit;
