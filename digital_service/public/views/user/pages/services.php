<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$services = [];
$q = $conn->query("SELECT id, title, thumbnail FROM services WHERE is_active = 1 ORDER BY created_at DESC");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Layanan | DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/services.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <?php include __DIR__ . "/../layout/navbar.php"; ?>

    <section class="hero-services">
        <div class="container">
            <div class="hero-center">
                <i class="fa-solid fa-sparkles hero-icon"></i>
                <h1>Upgrade Level Bisnis Kamu</h1>
                <p>Solusi digital unggulan untuk membangun brand dan meningkatkan penjualan.</p>
                <a href="#layanan" class="btn-start">
                    <i class="fa-solid fa-arrow-down"></i> Lihat Semua Layanan
                </a>
            </div>
        </div>
    </section>
    <section id="layanan" class="services-showcase">

        <section class="key-features">
            <div class="container features-grid">
                <div class="feat-item">
                    <i class="fa-solid fa-lightbulb"></i>
                    <h3>Inovatif</h3>
                    <p>Ide kreatif yang mengikuti perkembangan tren.</p>
                </div>
                <div class="feat-item">
                    <i class="fa-solid fa-gem"></i>
                    <h3>Berkualitas</h3>
                    <p>Setiap detail dikerjakan dengan standar tinggi.</p>
                </div>
                <div class="feat-item">
                    <i class="fa-solid fa-rocket"></i>
                    <h3>Tepat Waktu</h3>
                    <p>Pengerjaan efisien dengan timeline yang jelas.</p>
                </div>
            </div>
        </section>
        <div class="container">
            <h2 class="section-title">Pilihan Layanan Profesional</h2>

            <div class="services-grid">
                <?php if (count($services)): ?>
                    <?php foreach ($services as $srv): ?>
                        <div class="service-card">
                            <div class="thumb">
                                <?php if ($srv["thumbnail"]): ?>
                                    <img src="<?= $BASE_URL ?>uploads/services/<?= htmlspecialchars($srv["thumbnail"]) ?>" alt="">
                                <?php else: ?>
                                    <i class="fa-solid fa-image no-thumb"></i>
                                <?php endif; ?>
                            </div>

                            <h3><?= htmlspecialchars($srv["title"]) ?></h3>
                            <a href="<?= $BASE_URL ?>public/views/user/pages/packages.php?service_id=<?= $srv["id"] ?>"
                                class="btn-detail">
                                <i class="fa-solid fa-list"></i> Pilih Paket
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data">Belum ada layanan yang tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
        <section class="work-process">
            <div class="container">
                <h2 class="section-title">Proses Kerja Kami</h2>
                <div class="process-grid">
                    <div class="step"><span>1</span>
                        <h4>Konsultasi</h4>
                    </div>
                    <div class="step"><span>2</span>
                        <h4>Perencanaan</h4>
                    </div>
                    <div class="step"><span>3</span>
                        <h4>Pengerjaan</h4>
                    </div>
                    <div class="step"><span>4</span>
                        <h4>Revisi & Selesai</h4>
                    </div>
                </div>
            </div>
        </section>

    </section>

    <section class="section-cta">
        <div class="container">
            <h2>Mau mulai transformasi digital bisnismu?</h2>
            <p>Konsultasi GRATIS bersama tim kami sekarang.</p>
            <a href="<?= $BASE_URL ?>public/views/user/pages/contact.php" class="btn-cta">
                <i class="fa-solid fa-comments"></i> Konsultasi Sekarang
            </a>
        </div>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>