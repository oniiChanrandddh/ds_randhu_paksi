<?php

function user() {
    return isset($_SESSION['user_id']) ? $_SESSION : null;
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function is_client() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function money($number) {
    return "Rp " . number_format($number, 0, ',', '.');
}

function input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

function notifCount($user_id) {
    global $conn;
    $q = $conn->query("SELECT COUNT(*) AS total FROM notifications WHERE user_id='$user_id' AND status='unread'");
    $d = $q->fetch_assoc();
    return $d['total'];
}

function currentUrl() {
    return $_SERVER['REQUEST_URI'];
}

function isActive($path) {
    return strpos(currentUrl(), $path) !== false ? "active" : "";
}
