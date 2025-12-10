<?php
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../includes/auth.php";
require_once __DIR__ . "/../../../../config/app.php";

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $BASE_URL . "public/index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $BASE_URL . "public/index.php");
    exit;
}

$order_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

$q = $conn->query("
    SELECT 
        o.*, 
        p.name AS package_name,
        p.price AS package_price
    FROM orders o
    JOIN packages p ON p.id = o.package_id
    WHERE o.id = '$order_id'
      AND o.user_id = '$user_id'
    LIMIT 1
");

if (!$q || $q->num_rows === 0) {
    header("Location: " . $BASE_URL . "public/index.php");
    exit;
}

$order = $q->fetch_assoc();
$price = number_format($order["price"], 0, ',', '.');
$status = strtoupper($order["status"]);
$payment_method = $order["payment_method"];
$payment_proof = $order["payment_proof"];

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Status Pesanan</title>
<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/user/order_status.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<?php include __DIR__ . "/../layout/navbar.php"; ?>

<div class="status-container">

    <div class="status-header">
        <h2>Detail & Status Pesanan</h2>
        <p>Pesanan kamu sedang dalam proses pengecekan.</p>
    </div>

    <div class="status-card">

        <h3><?= htmlspecialchars($order["package_name"]) ?></h3>
        <p class="order-id">ID Pesanan: #<?= $order_id ?></p>

        <div class="order-status status-<?= strtolower(str_replace(' ', '-', $status)) ?>">
            <i class="fa-solid fa-circle"></i> <?= ucfirst(strtolower($status)) ?>
        </div>

        <div class="price-box">
            Total Pembayaran: <strong>Rp <?= $price ?></strong>
        </div>

        <div class="pay-box">
            <p>Metode Pembayaran:</p>
            <div class="payment-detail"><?= htmlspecialchars($payment_method) ?></div>

            <?php if ($payment_proof && $payment_method === "QRIS"): ?>
                <img src="<?= $BASE_URL ?>uploads/orders/<?= $payment_proof ?>" class="qris-img">
            <?php endif; ?>
        </div>

        <?php if ($payment_proof): ?>
            <div class="proof-ok">
                Bukti pembayaran sudah terkirim!
                
            </div>
        <?php else: ?>
            <div class="proof-warn">
                Belum ada bukti pembayaran. Unggah pada menu pesanan!
            </div>
        <?php endif; ?>

        <div class="notes-box">
            <p><strong>Catatan Pesanan:</strong></p>
            <p><?= nl2br(htmlspecialchars($order["notes"])) ?></p>
        </div>

        <div class="action-buttons">
          
            <a href="<?= $BASE_URL ?>public/index.php" class="btn-outline">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
        </div>

    </div>

</div>

</body>
<?php include $_SERVER['DOCUMENT_ROOT'] . "/digital_service/public/views/user/layout/footer.php"; ?>
</html>
