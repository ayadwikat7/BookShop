<?php
global $connection;
session_start();
include "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

$userId = $_SESSION['user_id'];
$field  = $_POST['field'] ?? '';
$value  = trim($_POST['value'] ?? '');

$allowed = ['name','email','phone','location','favorite','password'];

if (!in_array($field, $allowed)) {
    echo json_encode(["success" => false, "error" => "Invalid field"]);
    exit;
}

if ($value === '') {
    echo json_encode(["success" => false, "error" => "Field cannot be empty"]);
    exit;
}

/* ===== Server-side validation ===== */
if ($field === "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "error" => "Invalid email format"]);
    exit;
}

if ($field === "password") {
    if (strlen($value) < 6) {
        echo json_encode(["success" => false, "error" => "Password too short"]);
        exit;
    }
    $value = password_hash($value, PASSWORD_DEFAULT);
}

/* ===== Update ===== */
$stmt = $connection->prepare(
    "UPDATE users SET $field = ? WHERE id = ?"
);
$stmt->bind_param("si", $value, $userId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Update failed"]);
}
