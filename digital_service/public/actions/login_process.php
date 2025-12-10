<?php
require_once "../../config/db.php";
require_once "../../config/app.php";
require_once "../../includes/functions.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['login'])) {
    $username = input($_POST['username']);
    $password = $_POST['password'];

    $query = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($query->num_rows == 1) {
        $data = $query->fetch_assoc();

        if (password_verify($password, $data['password'])) {

            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_name'] = $data['name'];
            $_SESSION['user_role'] = $data['role'];

            if ($data['role'] == "admin") {
                header("Location: " . $BASE_URL . "public/views/admin/pages/dashboard.php");
            } else {
                header("Location: " . $BASE_URL . "public/index.php");
            }
            exit;

        } else {
            $_SESSION['error'] = "Password salah";
            header("Location: " . $BASE_URL . "public/login.php");
            exit;
        }

    } else {
        $_SESSION['error'] = "Username tidak ditemukan";
        header("Location: " . $BASE_URL . "public/login.php");
        exit;
    }

} else {
    header("Location: " . $BASE_URL . "public/login.php");
    exit;
}
