<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$portfolios = [];
$sql = "
    SELECT 
        p.id,
        p.title,
        p.description,
        p.thumbnail,
        p.preview_url,
        GROUP_CONCAT(s.title SEPARATOR ', ') AS services
    FROM portfolios p
    LEFT JOIN package_services ps ON ps.package_id = p.package_id
    LEFT JOIN services s ON s.id = ps.service_id
    GROUP BY p.id
    ORDER BY p.created_at DESC
";

$q = $conn->query($sql);
if ($q) while ($row = $q->fetch_assoc()) $portfolios[] = $row;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Portofolio | DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/portfolios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <?php include __DIR__ . "/../layout/navbar.php"; ?>

    <section class="hero-portfolio">
        <div class="container">
            <h1>Portofolio Karya Terbaik</h1>
            <p>Kumpulan project yang telah kami selesaikan dengan kualitas premium dan hasil memuaskan.</p>
        </div>
    </section>


    <section class="section-intro-portfolio">
        <div class="container">
            <h2>Kenapa Portofolio Penting?</h2>
            <p>
                Kami percaya bahwa karya yang nyata adalah bukti utama kualitas.
                Setiap project yang kami hasilkan dirancang secara profesional
                untuk menjawab kebutuhan bisnis dengan solusi digital yang efektif dan menarik perhatian audiens.
            </p>

            <div class="intro-grid">
                <div class="intro-item">
                    <i class="fa-solid fa-lightbulb"></i>
                    <h4>Ide Kreatif & Solutif</h4>
                    <p>Konsep yang dirancang berdasarkan karakter brand dan tujuan pemasaran.</p>
                </div>
                <div class="intro-item">
                    <i class="fa-solid fa-ranking-star"></i>
                    <h4>Hasil Maksimal</h4>
                    <p>Memastikan setiap project tampil optimal dan memberikan hasil nyata.</p>
                </div>
                <div class="intro-item">
                    <i class="fa-solid fa-headset"></i>
                    <h4>Komunikasi Lancar</h4>
                    <p>Setiap feedback klien selalu kami dengarkan dan tindak lanjuti tepat waktu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="portfolio-showcase">
        <div class="container">
            <h2>Project yang Pernah Kami Buat</h2>
            <?php if (!empty($portfolios)): ?>
                <div class="portfolio-grid">
                    <?php foreach ($portfolios as $p): ?>
                        <div class="portfolio-card">
                            <div class="thumb">
                                <?php if ($p["thumbnail"]): ?>
                                    <img src="<?= $BASE_URL ?>uploads/portfolios/<?= htmlspecialchars($p["thumbnail"]) ?>">
                                <?php else: ?>
                                    <i class="fa-solid fa-image no-thumb"></i>
                                <?php endif; ?>
                            </div>
                            <div class="portfolio-info">
                                <span class="tag"><i class="fa-solid fa-layer-group"></i> <?= htmlspecialchars($p["services"] ?? "Multi Layanan") ?></span>
                                <h3><?= htmlspecialchars($p["title"]) ?></h3>
                                <p><?= htmlspecialchars(substr($p["description"], 0, 100)) ?>...</p>
                                <?php if (!empty($p["preview_url"])): ?>
                                    <a href="<?= htmlspecialchars($p["preview_url"]) ?>" target="_blank" class="btn-preview" style="text-decoration: none;">
                                        <i class="fa-solid fa-up-right-from-square"></i> Lihat Detail
                                    </a>

                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty">Belum ada portofolio tersedia.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section-process">
        <div class="container">
            <h2>Proses Kerja Kami</h2>
            <div class="process-grid">
                <div class="step">
                    <i class="fa-solid fa-comments"></i>
                    <h4>Konsultasi</h4>
                    <p>Memahami kebutuhan project</p>
                </div>
                <div class="step">
                    <i class="fa-solid fa-pen-ruler"></i>
                    <h4>Desain</h4>
                    <p>Membuat konsep visual terbaik</p>
                </div>
                <div class="step">
                    <i class="fa-solid fa-code"></i>
                    <h4>Development</h4>
                    <p>Mengubah desain menjadi solusi</p>
                </div>
                <div class="step">
                    <i class="fa-solid fa-rocket"></i>
                    <h4>Launch</h4>
                    <p>Siap digunakan untuk publik</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-cta">
        <div class="container">
            <h2>Tertarik Memulai Project?</h2>
            <p>Bangun brand kamu dengan solusi digital terbaik. Konsultasi GRATIS!</p>
            <a href="<?= $BASE_URL ?>public/views/user/pages/contact.php" class="btn-cta">
                <i class="fa-solid fa-paper-plane"></i> Konsultasi Sekarang
            </a>
        </div>
    </section>

</body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>
</html>