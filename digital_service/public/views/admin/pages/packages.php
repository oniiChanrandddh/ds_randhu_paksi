<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$packages = [];
$q = $conn->query("
    SELECT 
        p.id, 
        p.name, 
        p.price,
        p.thumbnail,
        GROUP_CONCAT(s.title SEPARATOR ', ') AS services
    FROM packages p
    LEFT JOIN package_services ps ON ps.package_id = p.id
    LEFT JOIN services s ON s.id = ps.service_id
    GROUP BY p.id
    ORDER BY p.created_at DESC
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
    <title>Kelola Paket | Admin DigitalService</title>
    
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/packages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="admin-layout">
    <?php include __DIR__ . "/../layout/sidebar.php"; ?>

    <div class="admin-content">

        <div class="page-header">
            <h1><i class="fa-solid fa-box-archive"></i> Kelola Paket</h1>
            <p class="subtitle">Atur dan kelola paket layanan digital yang tersedia.</p>
        </div>

        <a href="<?= $BASE_URL ?>public/views/admin/pages/add_package.php" class="add-service-btn">
            <i class="fa-solid fa-plus"></i> Tambah Paket
        </a>

        <div class="service-grid-admin">
            <?php if (count($packages)): ?>
                <?php foreach ($packages as $pkg): ?>
                    <div class="service-card-admin">

                        <div class="service-thumb-admin">
                            <?php if ($pkg['thumbnail']): ?>
                                <img src="<?= $BASE_URL ?>uploads/packages/<?= htmlspecialchars($pkg['thumbnail']) ?>" alt="<?= htmlspecialchars($pkg['name']) ?>">
                            <?php else: ?>
                                <div class="no-thumb">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-info-admin">
                            <h4><?= htmlspecialchars($pkg['name']) ?></h4>
                            <p class="price">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></p>
                            <p class="service-ref">
                                <i class="fa-solid fa-layer-group"></i> <?= htmlspecialchars($pkg['services'] ?? "Belum ada layanan") ?>
                            </p>

                            <div class="card-actions">
                                <a href="<?= $BASE_URL ?>public/views/admin/pages/edit_package.php?id=<?= $pkg['id'] ?>" class="btn-edit">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>

                                <a href="<?= $BASE_URL ?>public/views/admin/actions/package_delete.php?id=<?= $pkg['id'] ?>" class="btn-delete btn-delete-package">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-dim); font-size: 15px; margin-top: 10px;">Belum ada paket tersedia.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
document.querySelectorAll('.btn-delete-package').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');

        Swal.fire({
            title: 'Hapus Paket?',
            text: "Paket ini akan dihapus secara permanen!",
            icon: 'warning',
            background: '#110724',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ff4d4d',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            iconColor: '#ff4d4d'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
});
</script>

</body>
</html>
