<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Layanan | Admin - DigitalService</title>

    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/sidebar.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/service_add.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* PREVIEW FIX */
#preview-container {
    width: 100%;
    max-width: 560px; /* Batas lebar aman */
    max-height: 300px; /* Batas tinggi aman */
    background: rgba(255,255,255,.04);
    border: 1px solid var(--border);
    border-radius: 14px;
    display: none;
    overflow: hidden;
    margin-top: 14px;
    transition: .25s ease;
}

#preview-container img {
    width: 100%;
    height: auto; /* fleksibel mengikuti foto */
    object-fit: contain; /* menjaga proporsi, tidak terpotong */
    display: block;
}

    </style>
</head>

<body>

<div class="admin-layout">

    <?php include __DIR__ . "/../layout/sidebar.php"; ?>

    <main class="admin-content">

        <div class="page-header">
            <h1><i class="fa-solid fa-plus"></i> Tambah Layanan Baru</h1>
            <p class="subtitle">Isi data berikut untuk menambahkan layanan digital baru.</p>
        </div>

        <div class="form-container">
            <form action="<?= $BASE_URL ?>public/views/admin/actions/service_create.php"
                  method="POST" enctype="multipart/form-data">

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="title">Judul Layanan</label>
                    <input type="text" name="title" id="title"
                           placeholder="Contoh: Desain Logo Profesional" required>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" rows="4"
                              placeholder="Ceritakan detail layananmu..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Harga Layanan (IDR)</label>
                    <input type="number" name="price" id="price"
                           placeholder="Contoh: 50000" required>
                </div>

                <div class="form-group">
                    <label for="thumbnail">Thumbnail Layanan</label>

                    <div class="upload-box">
                        <input type="file" name="thumbnail" id="thumbnail"
                               accept="image/png,image/jpg,image/jpeg" required>

                        <label for="thumbnail" class="upload-label">
                            <i class="fa-solid fa-image"></i>
                            Pilih Gambar
                        </label>

                        <div id="preview-container">
                            <img id="preview-img" src="">
                        </div>

                        <span class="upload-info">
                            Format: JPG, JPEG, PNG — Max 2MB
                        </span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Layanan
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
