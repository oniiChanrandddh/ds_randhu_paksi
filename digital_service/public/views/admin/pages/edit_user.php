<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/users.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT id, name, username, role FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit User | Admin</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/sidebar.css">
        <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/user_edit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="admin-layout">
        <?php include __DIR__ . "/../layout/sidebar.php"; ?>

        <div class="admin-content">

            <div class="page-header">
                <h1><i class="fa-solid fa-user-pen"></i> Edit Pengguna</h1>
                <p class="subtitle">Perbarui informasi akun pengguna</p>
            </div>

            <div class="form-container">
                <form action="<?= $BASE_URL ?>public/views/admin/actions/user_update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-error"><?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Nama *</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select name="role" id="role" required>
                            <option value="client" <?= $user['role'] == "client" ? "selected" : "" ?>>Client</option>
                            <option value="admin" <?= $user['role'] == "admin" ? "selected" : "" ?>>Admin</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</body>

</html>