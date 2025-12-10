<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/packages.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    header("Location: " . $BASE_URL . "public/views/admin/pages/packages.php");
    exit;
}

$services = [];
$q = $conn->query("SELECT id, title FROM services ORDER BY title ASC");
while ($row = $q->fetch_assoc()) {
    $services[] = $row;
}

$checked = [];
$q2 = $conn->query("SELECT service_id FROM package_services WHERE package_id = $id");
while ($row2 = $q2->fetch_assoc()) {
    $checked[] = $row2['service_id'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Paket | Admin - DigitalService</title>
<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/sidebar.css">
<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
<link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/package_add.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
img { max-width: 100%; height: auto; }
.current-thumb { max-width: 300px; max-height: 300px; overflow: hidden; border-radius: 14px; }
.current-thumb img { width: 100%; height: 100%; object-fit: cover; }
#preview-container { max-width: 280px; max-height: 280px; overflow: hidden; border-radius: 14px; }
#preview-container img { width: 100%; height: 100%; object-fit: cover; }
</style>
</head>

<body>
<div class="admin-layout">

<?php include __DIR__ . "/../layout/sidebar.php"; ?>

<main class="admin-content">

    <div class="page-header">
        <h1><i class="fa-solid fa-pen-to-square"></i> Edit Paket</h1>
        <p class="subtitle">Perbarui informasi paket layanan digital.</p>
    </div>

    <div class="form-container">

        <form action="<?= $BASE_URL ?>public/views/admin/actions/package_update.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label>Layanan dalam Paket *</label>
                <div class="checkbox-list">
                    <?php foreach ($services as $svc): ?>
                        <label class="checkbox-item">
                            <input type="checkbox" name="services[]" value="<?= $svc['id'] ?>" <?= in_array($svc['id'], $checked) ? "checked" : "" ?>>
                            <span><?= htmlspecialchars($svc['title']) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Nama Paket *</label>
                <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Deskripsi *</label>
                <textarea name="description" rows="4" required><?= htmlspecialchars($data['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label>Harga Paket (IDR) *</label>
                <input type="number" name="price" value="<?= $data['price'] ?>" required>
            </div>

            <div class="form-group">
                <label>Thumbnail Sekarang</label>
                <div class="current-thumb">
                    <?php if ($data['thumbnail']): ?>
                        <img src="<?= $BASE_URL ?>uploads/packages/<?= htmlspecialchars($data['thumbnail']) ?>">
                    <?php else: ?>
                        <span class="no-thumb">Belum ada thumbnail</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Ganti Thumbnail</label>
                <div class="upload-box">
                    <input type="file" name="thumbnail" id="thumbnail" accept="image/jpeg,image/jpg,image/png">
                    <label for="thumbnail" class="upload-label"><i class="fa-solid fa-image"></i> Pilih Gambar Baru</label>

                    <div id="preview-container"><img id="preview-img"></div>

                    <span class="upload-info">Opsional — JPG, JPEG, PNG — Max 2MB</span>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-save"></i> Update Paket</button>
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
        box.style.display = "block";
    } else {
        img.src = "";
        box.style.display = "none";
    }
});
</script>

</body>
</html>
