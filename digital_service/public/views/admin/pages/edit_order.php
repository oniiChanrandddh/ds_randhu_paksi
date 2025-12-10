<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/orders.php");
    exit;
}

$order_id = (int) $_GET['id'];

$q = $conn->query("
    SELECT o.*, 
           u.name AS user_name,
           p.name AS package_name,
           s.title AS service_name
    FROM orders o
    JOIN users u ON u.id = o.user_id
    JOIN packages p ON p.id = o.package_id
    JOIN services s ON s.id = o.service_id
    WHERE o.id = {$order_id}
    LIMIT 1
");

if (!$q || $q->num_rows == 0) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/orders.php");
    exit;
}

$order = $q->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Pesanan</title>
<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/edit_order.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="admin-layout">
<?php include __DIR__ . "/../layout/sidebar.php"; ?>

<div class="admin-content">

    <div class="page-header">
        <h1><i class="fa-solid fa-pen-to-square"></i> Edit Status Pesanan</h1>
        <p>Kelola status dan perkembangan dari pesanan pelanggan.</p>
    </div>

    <div class="form-card">
        <form action="<?= $BASE_URL ?>public/views/admin/actions/order_update.php" method="POST">

            <input type="hidden" name="order_id" value="<?= $order_id ?>">

            <div class="form-group">
                <label>Pemesan</label>
                <input type="text" value="<?= htmlspecialchars($order['user_name']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Layanan</label>
                <input type="text" value="<?= htmlspecialchars($order['service_name']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Paket</label>
                <input type="text" value="<?= htmlspecialchars($order['package_name']) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Status Pesanan</label>
                <select name="status" required>
                    <option value="WAITING CONFIRMATION" <?= $order['status'] == "WAITING CONFIRMATION" ? "selected" : "" ?>>
                        Waiting Confirmation
                    </option>
                    <option value="IN PROGRESS" <?= $order['status'] == "IN PROGRESS" ? "selected" : "" ?>>
                        In Progress
                    </option>
                    <option value="COMPLETED" <?= $order['status'] == "COMPLETED" ? "selected" : "" ?>>
                        Completed
                    </option>
                    <option value="CANCELLED" <?= $order['status'] == "CANCELLED" ? "selected" : "" ?>>
                        Cancelled
                    </option>
                </select>
            </div>

            <div class="submit-row">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="<?= $BASE_URL ?>public/views/admin/pages/orders.php" class="btn-outline">
                    Kembali
                </a>
            </div>

        </form>
    </div>

</div>
</div>

</body>
</html>
