<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

// Ambil semua service untuk checkbox
$services = [];
$q = $conn->query("SELECT id, title FROM services ORDER BY title ASC");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $services[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Paket | Admin - DigitalService</title>

    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/sidebar.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/package_add.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>

<body>

    <div class="admin-layout">

        <?php include __DIR__ . "/../layout/sidebar.php"; ?>

        <main class="admin-content">

            <div class="page-header">
                <h1><i class="fa-solid fa-box"></i> Tambah Paket Baru</h1>
                <p class="subtitle">Paket dapat berisi beberapa layanan yang digabungkan.</p>
            </div>

            <div class="form-container">
                <form action="<?= $BASE_URL ?>public/views/admin/actions/package_create.php" method="POST" enctype="multipart/form-data">

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-error"><?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Nama Paket *</label>
                        <input type="text" name="name" id="name" placeholder="Paket Premium" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Paket *</label>
                        <textarea name="description" id="description" rows="4" placeholder="Isi fitur paket..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga Paket (IDR) *</label>
                        <input type="number" name="price" id="price" placeholder="150000" required>
                    </div>

                    <div class="form-group">
                        <label>Pilih Layanan dalam Paket *</label>
                        <div class="checkbox-list">
                            <?php foreach ($services as $svc): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="services[]" value="<?= $svc['id'] ?>">
                                    <span><?= htmlspecialchars($svc['title']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Thumbnail Paket</label>
                        <div class="upload-box">
                            <input type="file" name="thumbnail" id="thumbnail" accept="image/png,image/jpg,image/jpeg">
                            <label for="thumbnail" class="upload-label">
                                <i class="fa-solid fa-image"></i>Pilih Gambar
                            </label>
                            <div id="preview-container">
                                <img id="preview-img">
                            </div>
                            <span class="upload-info">Format: JPG, JPEG, PNG — Max 2MB</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-save"></i> Simpan Paket
                        </button>
                    </div>

                </form>
            </div>

        </main>
    </div>

    <script>
        document.getElementById("thumbnail").addEventListener("change", function(e) {
            const file = e.target.files[0];
            const previewBox = document.getElementById("preview-container");
            const previewImg = document.getElementById("preview-img");

            if (file) {
                previewImg.src = URL.createObjectURL(file);
                previewBox.style.display = "flex";
            } else {
                previewBox.style.display = "none";
                previewImg.src = "";
            }
        });
    </script>

</body>

</html>