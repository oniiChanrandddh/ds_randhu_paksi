<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../../../../config/app.php";
require_once __DIR__ . "/../../../../includes/functions.php";

$logged_in = isset($_SESSION['user_id']);
$name = $logged_in ? ($_SESSION['user_name'] ?? 'Pengguna') : "Pengguna";
?>

<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/components/navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<nav class="user-navbar">

    <div class="nav-left">
        <a href="<?= $BASE_URL ?>public/index.php" class="nav-logo">DigitalService</a>
        <ul class="nav-links">
            <li><a href="<?= $BASE_URL ?>public/index.php"
                    class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i> Home
            </a></li>

            <li><a href="<?= $BASE_URL ?>public/views/user/pages/services.php">
                    <i class="fa-solid fa-briefcase"></i> Layanan
            </a></li>

            <li><a href="<?= $BASE_URL ?>public/views/user/pages/packages.php">
                    <i class="fa-solid fa-box"></i> Paket
            </a></li>

            <li><a href="<?= $BASE_URL ?>public/views/user/pages/portfolios.php">
                    <i class="fa-solid fa-images"></i> Portofolio
            </a></li>
        </ul>
    </div>

    <div class="nav-right">
        <?php if ($logged_in): ?>
            <div class="nav-user">
                <i class="fa-solid fa-circle-user"></i>
                <span class="username"><?= htmlspecialchars($name) ?></span>
            </div>
            <a href="<?= $BASE_URL ?>public/logout.php" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        <?php else: ?>
            <a href="<?= $BASE_URL ?>public/login.php" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
            <a href="<?= $BASE_URL ?>public/register.php" class="btn-register">
                <i class="fa-solid fa-user-plus"></i> Daftar
            </a>
        <?php endif; ?>
    </div>

</nav>
