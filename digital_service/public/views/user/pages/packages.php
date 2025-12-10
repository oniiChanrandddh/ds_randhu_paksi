<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$packages = [];
$q = $conn->query("
    SELECT
        p.id,
        p.name,
        p.description,
        p.price,
        p.thumbnail,
        GROUP_CONCAT(s.title SEPARATOR ', ') AS services
    FROM packages p
    LEFT JOIN package_services ps ON ps.package_id = p.id
    LEFT JOIN services s ON s.id = ps.service_id
    GROUP BY p.id
    ORDER BY p.price ASC
");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $packages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Paket | DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/packages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <?php include __DIR__ . "/../layout/navbar.php"; ?>

    <section class="hero-packages">
        <div class="container">
            <h1>Paket Layanan Premium</h1>
            <p>Kami menyediakan paket lengkap untuk membantu bisnis Anda berkembang pesat di dunia digital.</p>
        </div>
    </section>

    <section class="packages-intro">
        <div class="container">
            <h2 class="section-title">Kenapa Memilih Paket Kami?</h2>
            <p class="section-text">Paket kami dirancang untuk memenuhi kebutuhan bisnis yang berbeda, dengan kualitas terbaik dan harga yang kompetitif.</p>
        </div>
        <section class="section-benefits">
            <div class="container benefit-grid">
                <div class="benefit-card">
                    <i class="fa-solid fa-handshake"></i>
                    Solusi Tepat Sasaran
                </div>
                <div class="benefit-card">
                    <i class="fa-solid fa-bolt"></i>
                    Eksekusi Cepat & Efisien
                </div>
                <div class="benefit-card">
                    <i class="fa-solid fa-award"></i>
                    Standar Profesional Tinggi
                </div>
                <div class="benefit-card">
                    <i class="fa-solid fa-headset"></i>
                    Support Full Konsultasi
                </div>
            </div>

            <section class="section-packages-list">
                <div class="container">
                    <h2 class="section-title">Semua Paket Layanan</h2>
                    <div class="packages-grid">
                        <?php if (!empty($packages)): ?>
                            <?php foreach ($packages as $pkg): ?>
                                <div class="package-card">
                                    <div class="thumb">
                                        <?php if ($pkg["thumbnail"]): ?>
                                            <img src="<?= $BASE_URL ?>uploads/packages/<?= htmlspecialchars($pkg["thumbnail"]) ?>">
                                        <?php else: ?>
                                            <i class="fa-solid fa-box no-thumb"></i>
                                        <?php endif; ?>
                                    </div>

                                    <div class="service-tag">
                                        <i class="fa-solid fa-layer-group"></i>
                                        <?= htmlspecialchars($pkg["services"] ?? "Belum ada layanan") ?>
                                    </div>

                                    <h3 class="pkg-name"><?= htmlspecialchars($pkg["name"]) ?></h3>
                                    <p class="pkg-desc"><?= htmlspecialchars(substr($pkg["description"], 0, 110)) ?>...</p>
                                    <div class="pkg-price">Mulai Rp <?= number_format($pkg["price"], 0, ',', '.') ?></div>

                                    <a href="<?= $BASE_URL ?>public/views/user/pages/orders.php?package_id=<?= $pkg["id"] ?>"
                                        class="btn-order">
                                        <i class="fa-solid fa-cart-plus"></i> Order Sekarang
                                    </a>

                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty">Paket belum tersedia.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </section>
    </section>

    <section class="section-cta">
        <div class="container">
            <h2>Ada Paket Khusus yang Kamu Inginkan?</h2>
            <p>Diskusikan kebutuhan custom dengan kami. Konsultasi GRATIS!</p>
            <a href="<?= $BASE_URL ?>public/views/user/pages/contact.php" class="btn-cta">
                <i class="fa-solid fa-comments"></i> Konsultasi Sekarang
            </a>
        </div>
    </section>

</body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>
</html>