<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

$packages = [];
$q = $conn->query("SELECT id, name FROM packages ORDER BY name ASC");
if ($q) {
    while ($row = $q->fetch_assoc()) {
        $packages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Portfolio | Admin - DigitalService</title>

    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/sidebar.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/dashboard.css">
    <link rel="stylesheet" href="<?= $BASE_URL ?>public/assets/styles/admin/portfolio_add.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .custom-select { position: relative; width: 100%; }
        .select-input {
            width: 100%;
            background: #0f0624;
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 15px;
            padding: 14px 18px;
            border-radius: 14px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .select-input i { font-size: 13px; color: var(--accent); }
        .select-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            width: 100%;
            background: #0d061c;
            border: 1px solid var(--border);
            border-radius: 14px;
            display: none;
            flex-direction: column;
            max-height: 240px;
            overflow-y: auto;
            z-index: 20;
        }
        .select-option {
            padding: 12px 18px;
            cursor: pointer;
            transition: .22s ease;
        }
        .select-option:hover {
            background: rgba(168,85,255,.25);
            color: var(--accent-hover);
        }
        #package_id { display: none; }
        #preview-container {
            width: 100%;
            max-width: 560px;
            max-height: 300px;
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: 14px;
            display: none;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin-top: 12px;
        }
        #preview-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
    </style>
</head>

<body>
<div class="admin-layout">

    <?php include __DIR__ . "/../layout/sidebar.php"; ?>

    <main class="admin-content">

        <div class="page-header">
            <h1><i class="fa-solid fa-briefcase"></i> Tambah Portfolio Baru</h1>
            <p class="subtitle">Tambahkan hasil kerja yang pernah kamu buat.</p>
        </div>

        <div class="form-container">
            <form action="<?= $BASE_URL ?>public/views/admin/actions/portfolio_create.php" method="POST" enctype="multipart/form-data">

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Paket *</label>
                    <div class="custom-select">
                        <input type="hidden" name="package_id" id="package_id" required>
                        <div class="select-input" id="select-trigger">
                            <span id="selected-text">Pilih Paket</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="select-dropdown" id="dropdown">
                            <?php foreach ($packages as $pkg): ?>
                                <div class="select-option" data-value="<?= $pkg['id'] ?>">
                                    <?= htmlspecialchars($pkg['name']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Judul Portfolio *</label>
                    <input type="text" name="title" id="title" required placeholder="Contoh: Website Fullstack Toko Online">
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi *</label>
                    <textarea name="description" id="description" rows="4" required placeholder="Deskripsikan proyek ini"></textarea>
                </div>

                <div class="form-group">
                    <label for="preview_url">Preview URL (Opsional)</label>
                    <input type="url" name="preview_url" id="preview_url" placeholder="https://contoh.com">
                </div>

                <div class="form-group">
                    <label for="thumbnail">Thumbnail Portfolio</label>
                    <div class="upload-box">
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/png,image/jpg,image/jpeg">
                        <label for="thumbnail" class="upload-label">
                            <i class="fa-solid fa-image"></i> Pilih Gambar
                        </label>
                        <div id="preview-container">
                            <img id="preview-img">
                        </div>
                        <span class="upload-info">Opsional — JPG, JPEG, PNG — Max 2MB</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> Simpan Portfolio
                    </button>
                </div>

            </form>
        </div>

    </main>
</div>

<script>
const trigger = document.getElementById("select-trigger");
const dropdown = document.getElementById("dropdown");
const hiddenInput = document.getElementById("package_id");
const selectedText = document.getElementById("selected-text");
trigger.onclick = () => dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
document.querySelectorAll(".select-option").forEach(opt => {
    opt.onclick = () => {
        hiddenInput.value = opt.dataset.value;
        selectedText.textContent = opt.textContent;
        dropdown.style.display = "none";
    }
});
document.addEventListener("click", e => {
    if (!trigger.contains(e.target) && !dropdown.contains(e.target)) dropdown.style.display = "none";
});
document.getElementById("thumbnail").addEventListener("change", function(e) {
    const file = e.target.files[0];
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
