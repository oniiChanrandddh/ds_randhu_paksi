<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . $BASE_URL . "public/views/admin/pages/packages.php");
    exit;
}

$name = trim($_POST['name']);
$description = trim($_POST['description']);
$price = intval($_POST['price']);
$services = $_POST['services'] ?? []; 
$thumbnail = null;

if (!$name || !$description || !$price) {
    $_SESSION['error'] = "Harap lengkapi semua data wajib.";
    header("Location: " . $BASE_URL . "public/views/admin/pages/add_package.php");
    exit;
}

if (empty($services)) {
    $_SESSION['error'] = "Pilih minimal satu layanan dalam paket.";
    header("Location: " . $BASE_URL . "public/views/admin/pages/add_package.php");
    exit;
}

$uploadDir = __DIR__ . "/../../../../uploads/packages/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if (!empty($_FILES['thumbnail']['name'])) {
    $fileName = time() . "_" . basename($_FILES['thumbnail']['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $targetPath = $uploadDir . $fileName;
    $allowed = ['jpg', 'jpeg', 'png'];

    if (in_array($fileType, $allowed)) {
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $targetPath)) {
            $thumbnail = $fileName;
        }
    }
}

$stmt = $conn->prepare("INSERT INTO packages (name, description, price, thumbnail) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssis", $name, $description, $price, $thumbnail);
$stmt->execute();

$package_id = $stmt->insert_id;
$stmt->close();

$stmt2 = $conn->prepare("INSERT INTO package_services (package_id, service_id) VALUES (?, ?)");

foreach ($services as $svc_id) {
    $svc_id = intval($svc_id);
    $stmt2->bind_param("ii", $package_id, $svc_id);
    $stmt2->execute();
}

$stmt2->close();

$_SESSION['success'] = "Paket berhasil ditambahkan!";
header("Location: " . $BASE_URL . "public/views/admin/pages/packages.php");
exit;
