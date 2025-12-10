<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: {$BASE_URL}public/views/admin/pages/services.php");
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM services WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: {$BASE_URL}public/views/admin/pages/services.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Layanan | Admin - DigitalService</title>

    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/sidebar.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/service_edit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="admin-layout">
    <?php include __DIR__ . "/../layout/sidebar.php"; ?>

    <main class="admin-content">

        <div class="page-header">
            <h1><i class="fa-solid fa-pen-to-square"></i> Edit Layanan</h1>
            <p class="subtitle">Perbarui informasi layanan digitalmu.</p>
        </div>

        <div class="form-container">
            <form action="<?= $BASE_URL ?>public/views/admin/actions/service_update.php" method="POST" enctype="multipart/form-data">
                
                <input type="hidden" name="id" value="<?= $data['id'] ?>">

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="title">Judul Layanan *</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($data['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi *</label>
                    <textarea id="description" name="description" required><?= htmlspecialchars($data['description']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Harga Dasar (IDR)*</label>
                    <input type="number" id="price" name="price" value="<?= intval($data['base_price']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Thumbnail Saat Ini</label>
                    <div class="current-thumb">
                        <?php if ($data['thumbnail']): ?>
                            <img src="<?= $BASE_URL ?>uploads/services/<?= htmlspecialchars($data['thumbnail']) ?>">
                        <?php else: ?>
                            <span class="no-thumb">Belum ada thumbnail</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ganti Thumbnail (Opsional)</label>
                    <div class="upload-box">
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/png,image/jpg,image/jpeg">
                        <label for="thumbnail" class="upload-label">
                            <i class="fa-solid fa-image"></i>Unggah Thumbnail Baru
                        </label>
                        <div id="preview-container">
                            <img id="preview-img">
                        </div>
                        <span class="upload-info">Format: JPG, JPEG, PNG — Max 2MB</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> Update Layanan
                    </button>
                </div>

            </form>
        </div>

    </main>
</div>

<script>
document.getElementById("thumbnail").addEventListener("change", function() {
    const file = this.files[0];
    const box = document.getElementById("preview-container");
    const img = document.getElementById("preview-img");
    if (file) {
        img.src = URL.createObjectURL(file);
        box.style.display = "flex";
    } else {
        img.src = "";
        box.style.display = "none";
    }
});
</script>

</body>
</html>
