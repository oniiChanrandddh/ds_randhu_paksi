<?php

require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../includes/functions.php";

$admin_name = $_SESSION['user_name'] ?? 'Admin';

function get_count($conn, $table)
{
    $table = mysqli_real_escape_string($conn, $table);
    $q = $conn->query("SELECT COUNT(*) AS total FROM {$table}");
    if ($q && $row = $q->fetch_assoc()) {
        return (int)$row['total'];
    }
    return 0;
}

$total_services    = get_count($conn, 'services');
$total_packages    = get_count($conn, 'packages');
$total_categories  = get_count($conn, 'categories');
$total_portfolios  = get_count($conn, 'portfolios');
$total_users       = get_count($conn, 'users');
$total_orders      = get_count($conn, 'orders');

$statusStats = [
    'pending'     => 0,
    'in_progress' => 0,
    'completed'   => 0,
];

$qStatus = $conn->query("
    SELECT 
        SUM(CASE WHEN status IN ('pending') THEN 1 ELSE 0 END)       AS pending,
        SUM(CASE WHEN status IN ('proses','in_progress') THEN 1 ELSE 0 END) AS in_progress,
        SUM(CASE WHEN status IN ('selesai','completed') THEN 1 ELSE 0 END)  AS completed
    FROM orders
");

if ($qStatus && $row = $qStatus->fetch_assoc()) {
    $statusStats['pending']     = (int)$row['pending'];
    $statusStats['in_progress'] = (int)$row['in_progress'];
    $statusStats['completed']   = (int)$row['completed'];
}

$latestOrders = [];
$qLatest = $conn->query("
    SELECT 
        o.id,
        o.user_id,
        o.service_id,
        o.status,
        o.created_at,
        s.title AS service_title
    FROM orders o
    LEFT JOIN services s ON s.id = o.service_id
    ORDER BY o.created_at DESC
    LIMIT 5
");

if ($qLatest) {
    while ($row = $qLatest->fetch_assoc()) {
        $latestOrders[] = $row;
    }
}

$popularServices = [];
$qPopular = $conn->query("
    SELECT 
        s.id,
        s.title,
        COUNT(o.id) AS total_orders
    FROM orders o
    JOIN services s ON s.id = o.service_id
    GROUP BY s.id, s.title
    ORDER BY total_orders DESC
    LIMIT 5
");

if ($qPopular) {
    while ($row = $qPopular->fetch_assoc()) {
        $popularServices[] = $row;
    }
}

$chartLabels = [];
$chartData   = [];

$qMonthly = $conn->query("
    SELECT 
        DATE_FORMAT(created_at, '%Y-%m') AS ym,
        DATE_FORMAT(created_at, '%b %Y') AS label,
        COUNT(*) AS total
    FROM orders
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY ym, label
    ORDER BY ym ASC
");

if ($qMonthly) {
    while ($row = $qMonthly->fetch_assoc()) {
        $chartLabels[] = $row['label'];
        $chartData[]   = (int)$row['total'];
    }
}

if (empty($chartLabels)) {
    $chartLabels = ['Belum ada data'];
    $chartData   = [0];
}

$active = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="admin-layout">
    <?php include __DIR__ . "/../layout/sidebar.php"; ?>

    <main class="admin-main">

        <section class="admin-header">
            <div>
                <p class="eyebrow">Dashboard Admin</p>
                <h2>Selamat datang, <?= htmlspecialchars($admin_name) ?> 👋</h2>
                <p class="subtitle">
                    Kelola layanan, pesanan, dan pengguna DigitalService dari satu tempat.
                </p>
            </div>
            <div class="admin-quick-actions">
                <a href="<?= $BASE_URL ?>public/views/admin/pages/add_service.php" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Tambah Layanan
                </a>
                <a href="<?= $BASE_URL ?>public/views/admin/pages/orders.php" class="btn-outline">
                    <i class="fa-solid fa-list-check"></i> Kelola Pesanan
                </a>
            </div>
        </section>

        <section class="stats-grid">
            <div class="stat-card">
                <div>
                    <span class="label">Total Layanan</span>
                    <h3 class="value"><?= $total_services ?></h3>
                </div>
                <i class="fa-solid fa-briefcase icon"></i>
            </div>

            <div class="stat-card">
                <div>
                    <span class="label">Total Paket</span>
                    <h3 class="value"><?= $total_packages ?></h3>
                </div>
                <i class="fa-solid fa-box icon"></i>
            </div>

            <div class="stat-card">
                <div>
                    <span class="label">Total Portofolio</span>
                    <h3 class="value"><?= $total_portfolios ?></h3>
                </div>
                <i class="fa-solid fa-images icon"></i>
            </div>

            <div class="stat-card">
                <div>
                    <span class="label">Total Pengguna</span>
                    <h3 class="value"><?= $total_users ?></h3>
                </div>
                <i class="fa-solid fa-users icon"></i>
            </div>
        </section>

        <section class="stats-grid small">
            <div class="stat-card soft">
                <div>
                    <span class="label">Total Pesanan</span>
                    <h3 class="value"><?= $total_orders ?></h3>
                </div>
                <i class="fa-solid fa-layer-group icon"></i>
            </div>

            <div class="stat-card soft">
                <div>
                    <span class="label">Menunggu (Pending)</span>
                    <h3 class="value"><?= $statusStats['pending'] ?></h3>
                </div>
                <i class="fa-solid fa-hourglass-half icon"></i>
            </div>

            <div class="stat-card soft">
                <div>
                    <span class="label">Sedang Diproses</span>
                    <h3 class="value"><?= $statusStats['in_progress'] ?></h3>
                </div>
                <i class="fa-solid fa-spinner icon"></i>
            </div>

            <div class="stat-card soft">
                <div>
                    <span class="label">Selesai</span>
                    <h3 class="value"><?= $statusStats['completed'] ?></h3>
                </div>
                <i class="fa-solid fa-circle-check icon"></i>
            </div>
        </section>

        <section class="charts-section">
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Trend Pesanan (6 Bulan Terakhir)</h3>
                    <p class="text-muted">Pantau volume pesanan berdasarkan bulan.</p>
                </div>
                <canvas id="ordersMonthlyChart"></canvas>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3>Komposisi Status Pesanan</h3>
                    <p class="text-muted">Perbandingan pesanan pending, proses, dan selesai.</p>
                </div>
                <canvas id="ordersStatusChart"></canvas>
            </div>
        </section>

        <section class="bottom-grid">
            <div class="panel">
                <div class="panel-header">
                    <h3>Pesanan Terbaru</h3>
                    <a href="<?= $BASE_URL ?>public/views/admin/pages/orders.php" class="panel-link">
                        Lihat semua <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <?php if (count($latestOrders)): ?>
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Layanan</th>
                                <th>Client</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($latestOrders as $ord): ?>
                            <tr>
                                <td>#<?= $ord['id'] ?></td>
                                <td><?= htmlspecialchars($ord['service_title'] ?? 'Tidak diketahui') ?></td>
                                <td>User #<?= (int)$ord['user_id'] ?></td>
                            
                                <td><?= date('d M Y H:i', strtotime($ord['created_at'])) ?></td>
                                <td>
                                    <a href="<?= $BASE_URL ?>public/views/admin/pages/orderS.php?id=<?= $ord['id'] ?>" class="table-link">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="empty-text">Belum ada pesanan yang tercatat.</p>
                <?php endif; ?>
            </div>

            <div class="panel">

                <?php if (count($popularServices)): ?>
                    <ul class="popular-list">
                        <?php foreach ($popularServices as $srv): ?>
                            <li>
                                <span class="title"><?= htmlspecialchars($srv['title']) ?></span>
                                <span class="count"><?= (int)$srv['total_orders'] ?>x dipesan</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="empty-text">Belum ada data layanan terpopuler.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>
</div>

<script>
const ordersMonthlyLabels = <?= json_encode($chartLabels) ?>;
const ordersMonthlyData   = <?= json_encode($chartData) ?>;

const statusData = {
    pending: <?= $statusStats['pending'] ?>,
    in_progress: <?= $statusStats['in_progress'] ?>,
    completed: <?= $statusStats['completed'] ?>,
};

const ctxMonthly = document.getElementById('ordersMonthlyChart').getContext('2d');
new Chart(ctxMonthly, {
    type: 'line',
    data: {
        labels: ordersMonthlyLabels,
        datasets: [{
            label: 'Jumlah Pesanan',
            data: ordersMonthlyData,
            borderWidth: 2,
            tension: 0.35,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            x: {
                ticks: { color: '#c7c2e0' },
                grid: { display: false }
            },
            y: {
                ticks: { color: '#c7c2e0', precision: 0 },
                grid: { color: 'rgba(255,255,255,0.06)' }
            }
        }
    }
});

const ctxStatus = document.getElementById('ordersStatusChart').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Proses', 'Selesai'],
        datasets: [{
            data: [
                statusData.pending,
                statusData.in_progress,
                statusData.completed
            ],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { color: '#c7c2e0' }
            }
        },
        cutout: '60%',
    }
});
</script>


</body>
</html>
