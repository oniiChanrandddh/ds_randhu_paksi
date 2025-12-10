<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /digital_service/public/login.php");
    exit;
}

if ($_SESSION['user_role'] !== 'client') {
    header("Location: /digital_service/public/index.php");
    exit;
}
