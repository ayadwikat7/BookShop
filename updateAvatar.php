<?php
global $connection;
session_start();
include "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

if (!isset($_FILES['avatar'])) {
    echo json_encode(["success" => false, "error" => "No file uploaded"]);
    exit;
}

$file = $_FILES['avatar'];

if ($file['error'] !== 0) {
    echo json_encode(["success" => false, "error" => "Upload error"]);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(["success" => false, "error" => "Invalid image type"]);
    exit;
}

if ($file['size'] > 2 * 1024 * 1024) {
    echo json_encode(["success" => false, "error" => "File too large"]);
    exit;
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$newName = "avatar_" . $_SESSION['user_id'] . "_" . time() . "." . $ext;

$uploadPath = "imge/" . $newName;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    echo json_encode(["success" => false, "error" => "Failed to save image"]);
    exit;
}

/* Update DB */
$stmt = $connection->prepare(
    "UPDATE users SET avatar = ? WHERE id = ?"
);
$stmt->bind_param("si", $newName, $_SESSION['user_id']);
$stmt->execute();

echo json_encode([
    "success" => true,
    "avatar" => $newName
]);
