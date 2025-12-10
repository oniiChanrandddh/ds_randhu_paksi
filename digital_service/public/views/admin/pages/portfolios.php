<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$portfolios = [];
$q = $conn->query("
    SELECT p.id, p.title, p.thumbnail, p.preview_url, p.created_at,
           pk.name AS package_name
    FROM portfolios p
    LEFT JOIN packages pk ON p.package_id = pk.id
    ORDER BY p.created_at DESC
");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $portfolios[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Portofolio | Admin DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/portfolios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="admin-layout">
    <?php include __DIR__ . "/../layout/sidebar.php"; ?>

    <div class="admin-content">

        <div class="page-header">
            <h1><i class="fa-solid fa-images"></i> Kelola Portofolio</h1>
            <p class="subtitle">Dokumentasi karya terbaik Anda.</p>
        </div>

        <a href="<?= $BASE_URL ?>public/views/admin/pages/add_portfolio.php" class="add-service-btn">
            <i class="fa-solid fa-plus"></i> Tambah Portofolio
        </a>

        <div class="service-grid-admin">
            <?php if (count($portfolios)): ?>
                <?php foreach ($portfolios as $item): ?>
                    <div class="service-card-admin">

                        <div class="service-thumb-admin">
                            <?php if ($item['thumbnail']): ?>
                                <img src="<?= $BASE_URL ?>uploads/portfolios/<?= htmlspecialchars($item['thumbnail']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <?php else: ?>
                                <div class="no-thumb"><i class="fa-solid fa-image"></i></div>
                            <?php endif; ?>
                        </div>

                        <div class="service-info-admin">
                            <h4><?= htmlspecialchars($item['title']) ?></h4>

                            <p class="service-ref">
                                <i class="fa-solid fa-box"></i>
                                <?= htmlspecialchars($item['package_name'] ?? "Tidak ada paket") ?>
                            </p>

                            <div class="card-actions">
                                <?php if (!empty($item['preview_url'])): ?>
                                <a href="<?= htmlspecialchars($item['preview_url']) ?>" target="_blank" class="btn-preview">
                                    <i class="fa-solid fa-eye"></i> Preview
                                </a>
                                <?php endif; ?>

                                <a href="<?= $BASE_URL ?>public/views/admin/pages/edit_portfolio.php?id=<?= $item['id'] ?>" class="btn-edit">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>

                                <a href="<?= $BASE_URL ?>public/views/admin/actions/portfolio_delete.php?id=<?= $item['id'] ?>" class="btn-delete btn-delete-portfolio">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-dim); font-size: 15px;">Belum ada portofolio.</p>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
document.querySelectorAll('.btn-delete-portfolio').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.getAttribute('href');
        Swal.fire({
            title: 'Hapus Portofolio?',
            text: "Data tidak dapat dikembalikan!",
            icon: 'warning',
            background: '#110724',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ff4d4d',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            iconColor: '#ff4d4d'
        }).then(res => {
            if (res.isConfirmed) window.location.href = url;
        });
    });
});
</script>

</body>
</html>
