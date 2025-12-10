<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . "/includes/auth.php";
require_once dirname(__DIR__) . "/config/app.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: " . $BASE_URL . "public/index.php");
    exit;
}
