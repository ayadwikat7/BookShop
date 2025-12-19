<?php
global $connection;
include "db.php";

$name     = $_POST['Name'];
$email    = $_POST['Email'];
$password = $_POST['Password'];
$confirm  = $_POST['ConifrmedPassword'];
$role     = $_POST['role'];

if ($password !== $confirm) {
    header("Location: auth.php?error=Passwords do not match");
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $connection->prepare("
    INSERT INTO users (name, email, password, role)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    header("Location: auth.php?success=Account created successfully");
} else {
    header("Location: auth.php?error=Email already exists");
}
