<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_POST['id'])) {
    header("Location: {$BASE_URL}public/views/admin/pages/packages.php");
    exit;
}

$id = intval($_POST['id']);
$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$price = intval($_POST['price'] ?? 0);
$services = $_POST['services'] ?? [];

if ($name === '' || $price <= 0 || empty($services)) {
    $_SESSION['error'] = "Pastikan semua form terisi dengan benar dan pilih minimal satu layanan!";
    header("Location: {$BASE_URL}public/views/admin/pages/edit_package.php?id={$id}");
    exit;
}

$stmt = $conn->prepare("SELECT thumbnail FROM packages WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$currentThumb = $current['thumbnail'] ?? null;
$stmt->close();

$uploadDir = __DIR__ . "/../../../../uploads/packages/";
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
$newThumb = $currentThumb;

if (!empty($_FILES['thumbnail']['name'])) {
    $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png'])) {
        $_SESSION['error'] = "Thumbnail harus JPG/PNG!";
        header("Location: {$BASE_URL}public/views/admin/pages/edit_package.php?id={$id}");
        exit;
    }
    if ($_FILES['thumbnail']['size'] > 2 * 1024 * 1024) {
        $_SESSION['error'] = "Ukuran gambar maksimal 2MB!";
        header("Location: {$BASE_URL}public/views/admin/pages/edit_package.php?id={$id}");
        exit;
    }
    $newThumb = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;
    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir.$newThumb)) {
        if ($currentThumb && file_exists($uploadDir.$currentThumb)) unlink($uploadDir.$currentThumb);
    }
}

$stmt2 = $conn->prepare("UPDATE packages SET name=?, description=?, price=?, thumbnail=? WHERE id=?");
$stmt2->bind_param("ssisi", $name, $description, $price, $newThumb, $id);
$stmt2->execute();
$stmt2->close();

$conn->query("DELETE FROM package_services WHERE package_id=$id");

$stmt3 = $conn->prepare("INSERT INTO package_services (package_id, service_id) VALUES (?, ?)");
foreach ($services as $svc) {
    $svc = intval($svc);
    $stmt3->bind_param("ii", $id, $svc);
    $stmt3->execute();
}
$stmt3->close();

$_SESSION['success'] = "Paket berhasil diperbarui!";
header("Location: {$BASE_URL}public/views/admin/pages/packages.php");
exit;
