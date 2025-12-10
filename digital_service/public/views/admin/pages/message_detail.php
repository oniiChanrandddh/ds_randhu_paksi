<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: {$BASE_URL}public/views/admin/pages/messages.php");
    exit;
}

$message_id = (int) $_GET["id"];

$q = $conn->query("SELECT * FROM contact_messages WHERE id = {$message_id} LIMIT 1");
if (!$q || $q->num_rows === 0) {
    header("Location: {$BASE_URL}public/views/admin/pages/messages.php");
    exit;
}

$message = $q->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesan | Admin DigitalService</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/messages_detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="admin-layout">
    <?php 
        $active = "messages";
        include __DIR__ . "/../layout/sidebar.php"; 
    ?>

    <div class="admin-content">
        <div class="page-header">
            <h1><i class="fa-solid fa-envelope-open-text"></i> Detail Pesan</h1>
            <p class="subtitle">Lihat isi pesan yang dikirim oleh pengguna.</p>
        </div>

        <div class="detail-card">
            <div class="detail-row">
                <span class="label">Nama Pengirim</span>
                <span class="value"><?= htmlspecialchars($message["name"]) ?></span>
            </div>

            <div class="detail-row">
                <span class="label">Email</span>
                <span class="value"><?= htmlspecialchars($message["email"]) ?></span>
            </div>

            <?php if (!empty($message["subject"])): ?>
            <div class="detail-row">
                <span class="label">Subjek</span>
                <span class="value"><?= htmlspecialchars($message["subject"]) ?></span>
            </div>
            <?php endif; ?>

            <div class="detail-message">
                <?= nl2br(htmlspecialchars($message["message"])) ?>
            </div>
        </div>

        <div class="actions-row">
            <a href="<?= $BASE_URL ?>public/views/admin/pages/messages.php" class="btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>

            <a 
                href="<?= $BASE_URL ?>public/views/admin/actions/message_delete.php?id=<?= $message_id ?>" 
                class="btn-danger btn-delete">
                <i class="fa-solid fa-trash"></i> Hapus Pesan
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelector(".btn-delete").onclick = function(e){
    e.preventDefault();
    const url = this.getAttribute("href");

    Swal.fire({
        title: "Hapus Pesan?",
        text: "Tindakan tidak dapat dibatalkan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
        confirmButtonColor: "#ff4d4d",
        background: "#110724",
        color: "#fff"
    }).then(res => {
        if (res.isConfirmed) window.location = url;
    });
};
</script>

</body>
</html>
