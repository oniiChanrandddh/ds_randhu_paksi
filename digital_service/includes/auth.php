<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../login.php");
        exit;
    }

    if ($_SESSION['user_role'] !== 'admin') {
        header("Location: ../../index.php");
        exit;
    }
}
?>
