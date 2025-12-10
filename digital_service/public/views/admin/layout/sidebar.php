<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$active = $active ?? '';
?>
<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/components/sidebar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<aside class="sidebar">
    <h2 class="brand">DigitalService</h2>

    <ul class="menu">

        <li class="<?= $active === 'dashboard' ? 'active' : '' ?>">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/dashboard.php">
                <i class="fa-solid fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="<?= $active === 'services' ? 'active' : '' ?>">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/services.php">
                <i class="fa-solid fa-briefcase"></i>
                <span>Layanan</span>
            </a>
        </li>

        <li class="<?= $active === 'packages' ? 'active' : '' ?>">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/packages.php">
                <i class="fa-solid fa-box"></i>
                <span>Paket</span>
            </a>
        </li>

        <li class="<?= $active === 'portfolios' ? 'active' : '' ?>">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/portfolios.php">
                <i class="fa-solid fa-images"></i>
                <span>Portofolio</span>
            </a>
        </li>

        <li class="<?= $active === 'messages' ? 'active' : '' ?>">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/messages.php">
                <i class="fa-solid fa-envelope"></i>
                <span>Pesan</span>
            </a>
        </li>

        <li class="<?= $active === 'orders' ? 'active' : '' ?>">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/orders.php">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Pesanan</span>
            </a>
        </li>

        <li class="<?= $active === 'users' ? 'active' : '' ?>">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/users.php">
                <i class="fa-solid fa-users"></i>
                <span>Pengguna</span>
            </a>
        </li>

    </ul>

    <div class="logout-btn">
        <a href="<?= $BASE_URL ?>public/logout.php">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </div>
</aside>