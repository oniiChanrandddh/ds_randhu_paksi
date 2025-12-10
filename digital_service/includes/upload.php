<?php

function upload($file, $folder, $allowed = ['jpg','jpeg','png','gif','webp','pdf']) {
    $name = $file['name'];
    $tmp = $file['tmp_name'];
    $error = $file['error'];
    $size = $file['size'];

    if ($error !== 0) return false;
    if ($size > 3000000) return false;

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return false;

    if (!is_dir("../../uploads/$folder")) {
        mkdir("../../uploads/$folder", 0777, true);
    }

    $newName = date("YmdHis") . "_" . uniqid() . "." . $ext;
    $path = "../../uploads/$folder/" . $newName;

    if (move_uploaded_file($tmp, $path)) {
        return $newName;
    }

    return false;
}

function deleteFile($folder, $fileName) {
    $path = "../../uploads/$folder/" . $fileName;
    if (file_exists($path)) unlink($path);
}
