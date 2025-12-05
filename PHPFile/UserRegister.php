<?php
include "db.php";
global $connection;

$name = $_POST['Name'];
$email = $_POST['Email'];
$password = $_POST['Password'];
$confirmedPassword = $_POST['ConifrmedPassword'];

/* =======================
   1) فحص تطابق الباسوردين
   ======================= */
if ($password !== $confirmedPassword) {
    echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    exit();
}

/* =======================
   2) فحص هل الإيميل موجود
   ======================= */
$checkEmail = "SELECT * FROM registerusers WHERE Email='$email' LIMIT 1";
$result = $connection->query($checkEmail);

if ($result->num_rows > 0) {
    echo "<script>alert('Email already exists!'); window.history.back();</script>";
    exit();
}

/* =======================
   3) إدخال البيانات
   ======================= */
$sql = "INSERT INTO registerusers (Name, Email, Password, ConifrmedPassword)
        VALUES ('$name', '$email', '$password', '$confirmedPassword')";

if ($connection->query($sql)) {
    header("Location: ../Categories.html");
    exit();
} else {
    echo "Error: " . $connection->error;
}
?>
