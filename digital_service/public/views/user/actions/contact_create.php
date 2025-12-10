<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: {$BASE_URL}public/views/user/pages/contact.php");
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $subject === '' || $message === '') {
    $_SESSION['error'] = "Semua field wajib diisi.";
    header("Location: {$BASE_URL}public/views/user/pages/contact.php");
    exit;
}

$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    $_SESSION['success'] = "Pesan berhasil dikirim!";
} else {
    $_SESSION['error'] = "Gagal mengirim pesan!";
}

$stmt->close();
header("Location: {$BASE_URL}public/views/user/pages/contact.php");
exit;
