<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_POST['id'])) {
    header("Location: {$BASE_URL}public/views/admin/pages/users.php");
    exit;
}

$id = intval($_POST['id']);
$name = trim($_POST['name'] ?? '');
$username = trim($_POST['username'] ?? '');
$role = trim($_POST['role'] ?? '');

if ($name === '' || $username === '' || $role === '') {
    $_SESSION['error'] = "Semua field wajib diisi!";
    header("Location: {$BASE_URL}public/views/admin/pages/edit_user.php?id={$id}");
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username=? AND id!=? LIMIT 1");
$stmt->bind_param("si", $username, $id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $_SESSION['error'] = "Username sudah digunakan!";
    header("Location: {$BASE_URL}public/views/admin/pages/edit_user.php?id={$id}");
    exit;
}

$stmt = $conn->prepare("UPDATE users SET name=?, username=?, role=? WHERE id=?");
$stmt->bind_param("sssi", $name, $username, $role, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User berhasil diperbarui!";
    header("Location: {$BASE_URL}public/views/admin/pages/users.php");
    exit;
}

$_SESSION['error'] = "Gagal memperbarui user!";
header("Location: {$BASE_URL}public/views/admin/pages/edit_user.php?id={$id}");
exit;
