<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $BASE_URL . "public/views/user/pages/services.php");
    exit;
}

$id = intval($_GET['id']);
$q = $conn->query("SELECT * FROM services WHERE id = $id AND is_active = 1 LIMIT 1");
if (!$q || $q->num_rows == 0) {
    header("Location: " . $BASE_URL . "public/views/user/pages/services.php");
    exit;
}

$service = $q->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($service["title"]) ?> | DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/service_detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include __DIR__ . "/../layout/navbar.php"; ?>

<section class="hero-detail">
    <div class="container">
        <h1 class="detail-title"><?= htmlspecialchars($service["title"]) ?></h1>
        <p class="detail-sub">Layanan berkualitas untuk mendukung kebutuhan bisnis Anda</p>
    </div>
</section>

<section class="detail-main">
    <div class="container">
        <div class="detail-content">
            <div class="detail-image">
                <?php if ($service["thumbnail"]): ?>
                <img src="<?= $BASE_URL ?>uploads/services/<?= htmlspecialchars($service["thumbnail"]) ?>">
                <?php else: ?>
                <i class="fa-solid fa-image no-thumb"></i>
                <?php endif; ?>
            </div>

            <div class="detail-info">
                <h2>Tentang Layanan Ini</h2>
                <p><?= nl2br(htmlspecialchars($service["description"])) ?></p>

                <div class="price-box">
                    <span>Mulai dari</span>
                    <h3>Rp <?= number_format($service["base_price"], 0, ',', '.') ?></h3>
                </div>

                <a href="<?= $BASE_URL ?>public/views/user/pages/packages.php?service_id=<?= $service["id"] ?>" class="btn-primary">
                    Lihat Paket Layanan
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section-benefit">
    <div class="container">
        <h2>Kenapa Memilih Kami?</h2>
        <div class="benefit-grid">
            <div class="benefit-item"><i class="fa-solid fa-bolt"></i><span>Proses Cepat</span></div>
            <div class="benefit-item"><i class="fa-solid fa-award"></i><span>Hasil Berkualitas</span></div>
            <div class="benefit-item"><i class="fa-solid fa-handshake"></i><span>Layanan Ramah</span></div>
        </div>
    </div>
</section>

<section class="section-cta">
    <div class="container">
        <h2>Siap Memulai Proyek?</h2>
        <a href="<?= $BASE_URL ?>public/views/user/pages/packages.php?service_id=<?= $service["id"] ?>" class="btn-cta">
            Pilih Paket Sekarang
        </a>
    </div>
</section>

</body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>
</html>
