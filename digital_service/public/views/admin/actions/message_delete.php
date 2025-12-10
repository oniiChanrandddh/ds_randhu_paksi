<?php
require_once __DIR__ . "/../../../../middleware/admin_guard.php";
require_once __DIR__ . "/../../../../config/db.php";
require_once __DIR__ . "/../../../../config/app.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: {$BASE_URL}public/views/admin/pages/messages.php");
    exit;
}

$id = (int) $_GET["id"];

$stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: {$BASE_URL}public/views/admin/pages/messages.php");
exit;
