<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

// Cek input form
$title = trim($_POST['title'] ?? '');
$desc  = trim($_POST['description'] ?? '');
$price = intval($_POST['price'] ?? 0);

if ($title === '' || $desc === '' || $price <= 0) {
    $_SESSION['error'] = "Semua form wajib diisi dengan benar.";
    header("Location: $BASE_URL" . "public/views/admin/pages/add_service.php");
    exit;
}

$thumbnailName = null; 
$uploadDir = __DIR__ . "/../../../../uploads/services/";


// Buat folder jika belum ada
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!empty($_FILES['thumbnail']['name'])) {
    $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
        $_SESSION['error'] = "Format gambar tidak didukung!";
        exit;
    }

    if ($_FILES['thumbnail']['size'] > 2 * 1024 * 1024) {
        $_SESSION['error'] = "Ukuran gambar tidak boleh lebih dari 2MB!";
        exit;
    }

    $thumbnailName = time() . "_" . rand(1000, 9999) . "." . $ext;
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadDir . $thumbnailName);
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO services (title, description, base_price, thumbnail) VALUES (?,?,?,?)");
$stmt->bind_param("ssis", $title, $desc, $price, $thumbnailName);

if ($stmt->execute()) {
    $_SESSION['success'] = "Layanan berhasil ditambahkan!";
} else {
    $_SESSION['error'] = "Gagal menambahkan layanan!";
}

header("Location: $BASE_URL" . "public/views/admin/pages/services.php");
exit;
