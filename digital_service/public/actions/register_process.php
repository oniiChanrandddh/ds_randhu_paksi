<?php
require_once "../../config/db.php";
require_once "../../config/app.php";
require_once "../../includes/functions.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] == "admin") {
        header("Location: " . $BASE_URL . "public/views/admin/pages/dashboard.php");
    } else {
        header("Location: " . $BASE_URL . "index.php");
    }
    exit;
}

if (isset($_POST['register'])) {

    $name = trim(input($_POST['name']));
    $username = trim(input($_POST['username']));
    $password_raw = $_POST['password'];

    if (strlen($password_raw) < 6) {
        $_SESSION['error'] = "Password minimal 6 karakter!";
        header("Location: " . $BASE_URL . "public/register.php");
        exit;
    }

    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $check = $stmt->get_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Username sudah digunakan";
        header("Location: " . $BASE_URL . "public/register.php");
        exit;
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, username, password, role) VALUES (?, ?, ?, 'client')");
    $stmt->bind_param("sss", $name, $username, $password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Akun berhasil dibuat!";
    } else {
        $_SESSION['error'] = "Terjadi kesalahan! Coba lagi.";
    }

    header("Location: " . $BASE_URL . "public/register.php");
    exit;

} else {
    header("Location: " . $BASE_URL . "public/register.php");
    exit;
}
