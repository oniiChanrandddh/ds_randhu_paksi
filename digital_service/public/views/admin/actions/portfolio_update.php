<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_POST['id'])) {
    header("Location: {$BASE_URL}public/views/admin/pages/portfolios.php");
    exit;
}

$id = intval($_POST['id']);
$title = trim($_POST['title'] ?? '');
$preview_url = trim($_POST['preview_url'] ?? '');
$package_id = intval($_POST['package_id'] ?? 0);

if ($package_id <= 0 || $title === '') {
    $_SESSION['error'] = "Pastikan semua form terisi dengan benar!";
    header("Location: {$BASE_URL}public/views/admin/pages/edit_portfolio.php?id={$id}");
    exit;
}

$stmt = $conn->prepare("SELECT thumbnail FROM portfolios WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$currentThumb = $current['thumbnail'] ?? null;

$uploadDir = __DIR__ . "/../../../../uploads/portfolios/";
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

$newThumb = $currentThumb;

if (!empty($_FILES['thumbnail']['name'])) {
    $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg','jpeg','png'])) {
        $_SESSION['error'] = "Thumbnail harus JPG/PNG!";
        header("Location: {$BASE_URL}public/views/admin/pages/edit_portfolio.php?id={$id}");
        exit;
    }

    if ($_FILES['thumbnail']['size'] > 2 * 1024 * 1024) {
        $_SESSION['error'] = "Ukuran gambar maksimal 2MB!";
        header("Location: {$BASE_URL}public/views/admin/pages/edit_portfolio.php?id={$id}");
        exit;
    }

    $newThumb = time() . "_" . bin2hex(random_bytes(4)) . "." . $ext;

    if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir.$newThumb)) {
        $_SESSION['error'] = "Gagal upload gambar!";
        header("Location: {$BASE_URL}public/views/admin/pages/edit_portfolio.php?id={$id}");
        exit;
    }

    if ($currentThumb && file_exists($uploadDir.$currentThumb)) {
        unlink($uploadDir.$currentThumb);
    }
}

$stmtUpdate = $conn->prepare("UPDATE portfolios SET title=?, preview_url=?, package_id=?, thumbnail=? WHERE id=?");
$stmtUpdate->bind_param("ssisi", $title, $preview_url, $package_id, $newThumb, $id);
$stmtUpdate->execute();
$stmtUpdate->close();

$_SESSION['success'] = "Portofolio berhasil diperbarui!";
header("Location: {$BASE_URL}public/views/admin/pages/portfolios.php");
exit;
