<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$services = [];
$q = $conn->query("SELECT id, title, base_price, thumbnail, created_at FROM services ORDER BY created_at DESC");
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
    <title>Kelola Layanan | Admin DigitalService</title>
    
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/services.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="admin-layout">
    <?php include __DIR__ . "/../layout/sidebar.php"; ?>

    <div class="admin-content">
        
        <div class="page-header">
            <h1><i class="fa-solid fa-briefcase"></i> Kelola Layanan</h1>
            <p class="subtitle">Pantau dan atur semua layanan digital yang kamu tawarkan.</p>
        </div>

        <a href="<?= $BASE_URL ?>public/views/admin/pages/add_service.php" class="add-service-btn">
            <i class="fa-solid fa-plus"></i> Tambah Layanan
        </a>

        <div class="service-grid-admin">
            <?php if (count($services)): ?>
                <?php foreach ($services as $svc): ?>
                    <div class="service-card-admin">
                        
                        <div class="service-thumb-admin">
                            <?php if ($svc['thumbnail']): ?>
                                <img src="<?= $BASE_URL ?>uploads/services/<?= htmlspecialchars($svc['thumbnail']) ?>" 
                                     alt="<?= htmlspecialchars($svc['title']) ?>">
                            <?php else: ?>
                                <div class="no-thumb">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="service-info-admin">
                            <h4><?= htmlspecialchars($svc['title']) ?></h4>
                            <p class="price">Rp <?= number_format($svc['base_price'], 0, ',', '.') ?></p>

                            <div class="card-actions">
                                <a href="<?= $BASE_URL ?>public/views/admin/pages/edit_service.php?id=<?= $svc['id'] ?>" class="btn-edit">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>

                                <a href="<?= $BASE_URL ?>public/views/admin/actions/service_delete.php?id=<?= $svc['id'] ?>" 
                                   class="btn-delete btn-delete-service">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </a>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-dim); font-size: 15px; margin-top: 10px;">Belum ada layanan terdaftar.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
document.querySelectorAll('.btn-delete-service').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');

        Swal.fire({
            title: 'Hapus Layanan?',
            text: "Layanan ini akan dihapus secara permanen!",
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
