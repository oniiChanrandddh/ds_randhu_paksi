<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$messages = [];
$q = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
if ($q) while ($row = $q->fetch_assoc()) $messages[] = $row;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Masuk | Admin - DigitalService</title>

    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/components/sidebar.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/messages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div class="admin-layout">

    <?php $active = 'messages'; include __DIR__ . "/../layout/sidebar.php"; ?>

    <div class="admin-content">

        <div class="page-header">
            <h1><i class="fa-solid fa-envelope"></i> Pesan Masuk</h1>
            <p class="subtitle">Kelola pesan dari pengunjung website.</p>
        </div>

        <?php if (count($messages)): ?>
        <div class="table-wrapper">
            <table class="table-messages">
                <thead>
                    <tr>
                        <th><i class="fa-solid fa-user"></i> Pengirim</th>
                        <th><i class="fa-solid fa-heading"></i> Subjek</th>
                        <th><i class="fa-solid fa-envelope-open-text"></i> Status</th>
                        <th><i class="fa-solid fa-calendar"></i> Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($messages as $msg): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($msg['name']) ?><br>
                            <small style="color:var(--text-dim)"><?= htmlspecialchars($msg['email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($msg['subject']) ?></td>

                        <td>
                            <?php if ($msg['status'] === 'BELUM DIBALAS'): ?>
                                <span class="status-badge new">Baru</span>
                            <?php else: ?>
                                <span class="status-badge seen">Dibalas</span>
                            <?php endif; ?>
                        </td>

                        <td><?= date("d M Y - H:i", strtotime($msg['created_at'])) ?></td>

                        <td class="actions">
                            <a href="<?= $BASE_URL ?>public/views/admin/pages/message_detail.php?id=<?= $msg['id'] ?>" class="btn-detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button data-id="<?= $msg['id'] ?>" class="btn-delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="empty">Belum ada pesan masuk.</p>
        <?php endif; ?>

    </div>

</div>

<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(){
        const id = this.getAttribute('data-id');
        Swal.fire({
            title: "Hapus Pesan?",
            text: "Pesan ini akan hilang permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ff4d4d",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Ya, hapus",
            cancelButtonText: "Batal",
            background: "#110724",
            color: "#fff",
            iconColor: "#ff4d4d"
        }).then(res=>{
            if(res.isConfirmed){
                window.location.href = `<?= $BASE_URL ?>public/views/admin/actions/message_delete.php?id=${id}`;
            }
        });
    });
});
</script>

</body>
</html>
