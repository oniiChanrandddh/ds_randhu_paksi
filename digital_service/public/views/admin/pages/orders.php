<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$orders = [];
$q = $conn->query("
    SELECT o.*, 
           u.name AS user_name,
           s.title AS service_title,
           p.name AS package_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN services s ON o.service_id = s.id
    LEFT JOIN packages p ON o.package_id = p.id
    ORDER BY o.created_at DESC
");


if ($q) {
    while ($row = $q->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan | Admin DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/orders.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="admin-layout">

<?php include __DIR__ . "/../layout/sidebar.php"; ?>

<div class="admin-content">

    <div class="page-header">
        <h1><i class="fa-solid fa-cart-shopping"></i> Kelola Pesanan</h1>
        <p class="subtitle">Pantau setiap pemesanan layanan yang masuk.</p>
    </div>

    <div class="table-card">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Pemesan</th>
                    <th>Layanan</th>
                    <th>Paket</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php if(count($orders)): ?>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td><?= htmlspecialchars($o['user_name']) ?></td>
                    <td><?= htmlspecialchars($o['service_title']) ?></td>
                    <td><?= $o['package_name'] ? htmlspecialchars($o['package_name']) : '-' ?></td>
                   
                    <td>Rp <?= number_format($o['price'], 0, ',', '.') ?></td>
                    <td><?= date("d M Y", strtotime($o['created_at'])) ?></td>

                    <td class="actions">
                        <a href="<?= $BASE_URL ?>public/views/admin/pages/edit_order.php?id=<?= $o['id'] ?>" class="btn-action">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <a href="<?= $BASE_URL ?>public/views/admin/actions/order_delete.php?id=<?= $o['id'] ?>" class="btn-action delete btn-delete-order">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="empty">Belum ada pesanan masuk.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<script>
document.querySelectorAll('.btn-delete-order').forEach(btn=>{
    btn.onclick=function(e){
        e.preventDefault();
        const url=this.getAttribute('href');
        Swal.fire({
            title:"Hapus Pesanan?",
            text:"Tindakan ini tidak bisa dibatalkan!",
            icon:"warning",
            background:"#110724",
            color:"#fff",
            showCancelButton:true,
            confirmButtonColor:"#ff4d4d",
            cancelButtonColor:"#6b7280",
            confirmButtonText:"Ya, Hapus",
            cancelButtonText:"Batal",
            iconColor:"#ff4d4d"
        }).then(res=>{
            if(res.isConfirmed) window.location=url;
        })
    }
});
</script>

</body>
</html>
