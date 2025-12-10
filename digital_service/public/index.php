<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/app.php";
require_once __DIR__ . "/../includes/functions.php";

$logged_in = isset($_SESSION['user_id']);
$user_id   = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? 'Pengguna';

$summary = [
    'total_orders'    => 0,
    'active_orders'   => 0,
    'completed_orders' => 0,
];

$qSummary = $conn->query("
    SELECT 
        COUNT(*) AS total_orders,
        SUM(CASE WHEN status IN ('pending','proses','in_progress') THEN 1 ELSE 0 END) AS active_orders,
        SUM(CASE WHEN status IN ('selesai','completed') THEN 1 ELSE 0 END) AS completed_orders
    FROM orders
    WHERE user_id = '$user_id'
");
if ($qSummary && $qSummary->num_rows > 0) {
    $summary = $qSummary->fetch_assoc();
}

$lastOrder = null;
$qLast = $conn->query("
    SELECT o.*, s.title AS service_name
    FROM orders o
    LEFT JOIN services s ON s.id = o.service_id
    WHERE o.user_id = '$user_id'
    ORDER BY o.created_at DESC
    LIMIT 1
");
if ($qLast && $qLast->num_rows > 0) {
    $lastOrder = $qLast->fetch_assoc();
}

$activeOrders = [];
$qActive = $conn->query("
    SELECT o.*, s.title AS service_name
    FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE o.user_id='$user_id'
    AND o.status IN ('pending','in_progress','proses')
    ORDER BY o.created_at DESC
    LIMIT 4
");
if ($qActive) {
    while ($row = $qActive->fetch_assoc()) {
        $activeOrders[] = $row;
    }
}

$completedOrders = [];
$qCompleted = $conn->query("
    SELECT o.*, s.title AS service_name
    FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE o.user_id='$user_id'
    AND o.status IN ('selesai','completed')
    ORDER BY o.created_at DESC
    LIMIT 4
");
if ($qCompleted) {
    while ($row = $qCompleted->fetch_assoc()) {
        $completedOrders[] = $row;
    }
}

$popularServices = [];
$qPopular = $conn->query("
    SELECT s.id, s.title, s.thumbnail, COUNT(o.id) AS total_orders
    FROM orders o
    JOIN services s ON s.id = o.service_id
    GROUP BY o.service_id
    ORDER BY total_orders DESC
    LIMIT 6
");
if ($qPopular) {
    while ($row = $qPopular->fetch_assoc()) {
        $popularServices[] = $row;
    }
}

$servicesList = [];
$qServices = $conn->query("
    SELECT id, title, thumbnail, created_at
    FROM services
    ORDER BY created_at DESC
    LIMIT 6
");
if ($qServices) {
    while ($row = $qServices->fetch_assoc()) {
        $servicesList[] = $row;
    }
}

$projects = [];
$qProjects = $conn->query("
    SELECT id, title, thumbnail, created_at
    FROM portfolios
    ORDER BY created_at DESC
    LIMIT 6
");
if ($qProjects) {
    while ($row = $qProjects->fetch_assoc()) {
        $projects[] = $row;
    }
}

$userDetail = $conn->query("SELECT created_at FROM users WHERE id='$user_id' LIMIT 1");
$joined_at = null;
if ($userDetail && $userDetail->num_rows > 0) {
    $joined_at = $userDetail->fetch_assoc()['created_at'];
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard | DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <?php include __DIR__ . "/views/user/layout/navbar.php"; ?>

    <main class="ds-dashboard">

        <section class="user-header">
            <div class="user-greeting">
                <p class="eyebrow">Dashboard Klien</p>
                <h2>Halo, <?= htmlspecialchars($user_name) ?> 👋</h2>
                <p class="subtitle">Kelola semua kebutuhan layanan digitalmu dari satu tempat.</p>
                <div class="user-header-actions">
                    <a href="<?= $BASE_URL ?>public/views/user/pages/services.php" class="btn-primary">
                        <i class="fa-solid fa-cart-plus"></i>
                        Pesan Layanan
                    </a>

                    <?php
                    if (session_status() === PHP_SESSION_NONE) session_start();

                    $user_id = $_SESSION['user_id'] ?? 0;

                    $orders = [];
                    if ($user_id) {
                        $orders_result = $conn->query("
                            SELECT id, status 
                            FROM orders 
                            WHERE user_id = $user_id 
                            ORDER BY created_at DESC
                        ");
                        if ($orders_result && $orders_result->num_rows > 0) {
                            $orders = $orders_result->fetch_all(MYSQLI_ASSOC);
                        }
                    }
                    ?>

                    <?php
                    $latest_order = null;

                    if ($logged_in && $user_id) {
                        $qLatest = $conn->query("
        SELECT id 
        FROM orders 
        WHERE user_id = '$user_id'
        ORDER BY created_at DESC
        LIMIT 1
    ");

                        if ($qLatest && $qLatest->num_rows > 0) {
                            $latest_order = $qLatest->fetch_assoc();
                        }
                    }
                    ?>

                    <?php if ($latest_order): ?>
                        <a href="<?= $BASE_URL ?>public/views/user/pages/order_status.php?id=<?= $latest_order['id'] ?>"
                            class="btn-outline">
                            <i class="fa-solid fa-list-check"></i>
                            Lihat Pesanan
                        </a>
                    <?php else: ?>
                        <a href="<?= $BASE_URL ?>public/views/user/pages/orders.php"
                            class="btn-outline">
                            <i class="fa-solid fa-cart-shopping"></i>
                            Buat Pesanan
                        </a>
                    <?php endif; ?>

                </div>
            </div>
            <div class="hero-side">
                <div class="hero-icon-circle">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
            </div>
        </section>

        <section class="feature-strip">
            <div class="feature-item">
                <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                <div class="feature-text">
                    <h4>Proses Cepat</h4>
                    <p>Pesananmu langsung diteruskan ke tim setelah dibuat.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fa-solid fa-user-tie"></i></div>
                <div class="feature-text">
                    <h4>Talenta Profesional</h4>
                    <p>Dikerjakan oleh kreator dan developer berpengalaman.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="feature-text">
                    <h4>Aman & Terpantau</h4>
                    <p>Status pesanan selalu bisa kamu cek kapan saja.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fa-solid fa-headset"></i></div>
                <div class="feature-text">
                    <h4>Dukungan Ramah</h4>
                    <p>Tim support siap membantumu jika ada kendala.</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="about-card">
                <h3>Tentang DigitalService</h3>
                <p>DigitalService adalah ruang kerja digital dimana kamu bisa memesan desain, website, editing video, branding, hingga layanan kreatif lainnya. Semua pesanan tercatat rapi, status mudah dipantau, dan hasil akhir dikirim langsung ke akunmu.</p>
            </div>
        </section>

        <section class="ds-stats-grid">
            <div class="stat-card">
                <span class="label">Total Pesanan</span>
                <span class="value"><?= (int)$summary['total_orders'] ?></span>
                <i class="fa-solid fa-layer-group icon"></i>
            </div>
            <div class="stat-card">
                <span class="label">Sedang Diproses</span>
                <span class="value"><?= (int)$summary['active_orders'] ?></span>
                <i class="fa-solid fa-spinner icon"></i>
            </div>
            <div class="stat-card">
                <span class="label">Selesai</span>
                <span class="value"><?= (int)$summary['completed_orders'] ?></span>
                <i class="fa-solid fa-circle-check icon"></i>
            </div>
            <div class="stat-card">
                <span class="label">Bergabung Sejak</span>
                <span class="value"><?= $joined_at ? date('d M Y', strtotime($joined_at)) : '-' ?></span>
                <i class="fa-solid fa-calendar icon"></i>
            </div>
        </section>

        <section class="services-section">
            <div class="section-heading">
                <h3>Layanan Untukmu</h3>
                <p>Pilih kategori layanan digital yang paling kamu butuhkan.</p>
            </div>

            <div class="service-grid">
                <?php if (count($servicesList)): ?>
                    <?php foreach ($servicesList as $svc): ?>
                        <a href="<?= $BASE_URL ?>views/user/pages/service_detail.php?id=<?= $svc['id'] ?>" class="service-card">

                            <div class="service-thumb">
                                <?php if (!empty($svc['thumbnail'])): ?>
                                    <img src="<?= $BASE_URL ?>uploads/services/<?= htmlspecialchars($svc['thumbnail']) ?>" alt="<?= htmlspecialchars($svc['title']) ?>">
                                <?php else: ?>
                                    <div class="no-thumb">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="service-info">
                                <h4><?= htmlspecialchars($svc['title']) ?></h4>

                                <div class="meta-row">
                                    <div class="meta-icons">
                                        <i class="fa-regular fa-heart"></i> <span>0</span>
                                        <i class="fa-regular fa-comment"></i> <span>0</span>
                                    </div>

                                    <span class="meta-date">
                                        <?= date('d M Y', strtotime($svc['created_at'])) ?>
                                    </span>
                                </div>
                            </div>

                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-text">Belum ada layanan yang tersedia.</p>
                <?php endif; ?>
            </div>
        </section>


        <section class="ds-content-two">
            <div class="card last-order">
                <div class="headline">
                    <i class="fa-solid fa-clock-rotate-left"></i> Pesanan Terakhir
                </div>
                <?php if ($lastOrder): ?>
                    <div class="order-box">
                        <h4><?= htmlspecialchars($lastOrder['service_name']) ?></h4>
                        <p class="meta">#<?= $lastOrder['id'] ?> • <?= date('d M Y H:i', strtotime($lastOrder['created_at'])) ?></p>
                        <p class="status status-<?= htmlspecialchars($lastOrder['status']) ?>">Status: <?= ucfirst($lastOrder['status']) ?></p>
                    
                    </div>
                <?php else: ?>
                    <p>Belum ada pesanan yang tercatat.</p>
                    <a href="<?= $BASE_URL ?>views/user/pages/services.php" class="btn-primary-small">Buat pesanan pertama</a>
                <?php endif; ?>
            </div>

            <div class="card popular-services">
                <div class="headline">
                    <i class="fa-solid fa-fire"></i> Layanan Terpopuler
                </div>
                <?php if (count($popularServices)): ?>
                    <ul class="list-pop">
                        <?php foreach ($popularServices as $srv): ?>
                            <li>
                                <a href="<?= $BASE_URL ?>views/user/pages/service_detail.php?id=<?= $srv['id'] ?>">
                                    <?= htmlspecialchars($srv['title']) ?>
                                </a>
                                <span class="count"><?= (int)$srv['total_orders'] ?>x</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Belum ada data layanan terpopuler.</p>
                <?php endif; ?>
            </div>
        </section>

        <?php if (count($activeOrders)): ?>
            <section class="project-gallery orders-gallery">
                <div class="section-heading">
                    <h3>Pesanan Sedang Diproses</h3>
                    <p>Pantau pengerjaan pesananmu yang masih berjalan.</p>
                </div>
                <div class="grid-projects">
                    <?php foreach ($activeOrders as $o): ?>
                        <div class="project-item project-order">
                            <div class="order-icon">
                                <i class="fa-solid fa-spinner"></i>
                            </div>
                            <h4><?= htmlspecialchars($o['service_name']) ?></h4>
                            <p class="date">Dibuat: <?= date('d M Y', strtotime($o['created_at'])) ?></p>
                            <p class="status status-<?= htmlspecialchars($o['status']) ?>">Status: <?= ucfirst($o['status']) ?></p>
                            <a href="<?= $BASE_URL ?>views/user/pages/order_detail.php?id=<?= $o['id'] ?>" class="link-more">Lihat detail</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if (count($completedOrders)): ?>
            <section class="project-gallery orders-gallery">
                <div class="section-heading">
                    <h3>Riwayat Pesanan Selesai</h3>
                    <p>Lihat hasil-hasil yang sudah berhasil dikerjakan.</p>
                </div>
                <div class="grid-projects">
                    <?php foreach ($completedOrders as $o): ?>
                        <div class="project-item project-order completed">
                            <div class="order-icon">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <h4><?= htmlspecialchars($o['service_name']) ?></h4>
                            <p class="date">Selesai: <?= date('d M Y', strtotime($o['updated_at'] ?? $o['created_at'])) ?></p>
                            <a href="<?= $BASE_URL ?>views/user/pages/order_detail.php?id=<?= $o['id'] ?>" class="link-more">Lihat detail</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>



        <section class="inspiration">
            <div class="quote-card">
                <i class="fa-solid fa-quote-left"></i>
                <p>“Ide hebat butuh eksekusi yang konsisten. Kami bantu wujudkan visi digitalmu.”</p>
                <a href="<?= $BASE_URL ?>views/user/pages/orders.php" class="help-link">
                    <i class="fa-solid fa-headset"></i>
                    Butuh bantuan dengan pesananmu?
                </a>
            </div>
        </section>

    </main>

</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>
</html>
