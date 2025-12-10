<?php
require_once "../config/app.php";
require_once "../config/db.php";
require_once "../includes/functions.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] == 'admin') {
        header("Location: " . $BASE_URL . "public/views/admin/pages/dashboard.php");
    } else {
        header("Location: " . $BASE_URL . "index.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/auth/register.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

.swal2-popup {
    background: #150f25 !important;
    color: #ffffff !important;
    border-radius: 20px !important;
    box-shadow: 0 0 18px rgba(138, 99, 255, .25);
}

.swal2-title {
    color: #ffffff !important;
    font-weight: 700 !important;
}

.swal2-html-container {
    color: #c7c2e0 !important;
}

.swal2-confirm {
    background: linear-gradient(145deg, #7e5cf3, #6d4de0) !important;
    border-radius: 10px !important;
    padding: 10px 24px !important;
    font-weight: 600 !important;
    border: none !important;
    cursor: pointer !important;
    pointer-events: auto !important;
    transition: .25s ease;
}

.swal2-confirm:hover {
    background: #a98cff !important;
    transform: translateY(-3px);
}


.swal2-icon.swal2-error {
    border-color: #b6485c !important;
    background: #2c0f17 !important;
}

.swal2-x-mark-line-left,
.swal2-x-mark-line-right {
    background: #e98096 !important;
}

.swal2-show {
    animation: swalFade .2s ease-out;
}

@keyframes swalFade {
    from { transform: scale(.95); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}

</style>

</head>
<body>

<div class="auth-overlay"></div>

<div class="auth-wrapper">
    <form action="<?= $BASE_URL ?>public/actions/register_process.php" method="POST" class="auth-box">
        <h2>Daftar Sekarang</h2>
        <p class="subtitle">Akses layanan digital kami</p>

        <?php if(isset($_SESSION['error'])): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '<?= $_SESSION['error']; ?>',
                    confirmButtonText: 'Mengerti'
                });
            </script>
        <?php unset($_SESSION['error']); endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '<?= $_SESSION['success']; ?>',
                    confirmButtonText: 'Lanjut Login'
                }).then(() => {
                    window.location.href = "<?= $BASE_URL ?>public/login.php";
                });
            </script>
        <?php unset($_SESSION['success']); endif; ?>

        <div class="input-item">
            <i class="fa fa-user"></i>
            <input type="text" name="name" placeholder="Nama lengkap" required>
        </div>

        <div class="input-item">
            <i class="fa fa-user"></i>
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="input-item">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="register" class="btn-auth">Buat Akun</button>

        <p class="link">Sudah punya akun? 
            <a href="<?= $BASE_URL ?>public/login.php">Masuk</a>
        </p>
    </form>
</div>

</body>
</html>
