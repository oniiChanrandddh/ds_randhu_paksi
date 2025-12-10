<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$users = [];
$q = $conn->query("SELECT id, name, username, role, created_at FROM users ORDER BY created_at DESC");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengguna | Admin DigitalService</title>

    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/sidebar.css">

    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="admin-layout">

<?php include __DIR__ . "/../layout/sidebar.php"; ?>

<main class="admin-content">

    <div class="page-header">
        <h1><i class="fa-solid fa-user-gear"></i> Kelola Pengguna</h1>
        <p class="subtitle">Manajemen akun pengguna DigitalService.</p>
    </div>

    <div class="table-card">
        <table class="table-users">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php if(count($users)): ?>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td class="role <?= $u['role'] ?>"><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= htmlspecialchars(date("d M Y", strtotime($u['created_at']))) ?></td>

                    <td class="actions">
                        <a href="<?= $BASE_URL ?>public/views/admin/pages/edit_user.php?id=<?= $u['id'] ?>" class="btn-edit">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <a href="<?= $BASE_URL ?>public/views/admin/actions/user_delete.php?id=<?= $u['id'] ?>" class="btn-delete btn-user-remove">
                            <i class="fa-solid fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" class="empty">Belum ada pengguna.</td></tr>
            <?php endif; ?>
            </tbody>

        </table>
    </div>

</main>
</div>

<script>
document.querySelectorAll('.btn-user-remove').forEach(btn=>{
    btn.onclick=function(e){
        e.preventDefault();
        const url=this.getAttribute('href');
        Swal.fire({
            title:"Hapus Pengguna?",
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
        }).then(r=>{ if(r.isConfirmed) window.location=url })
    }
});
</script>

</body>
</html>
