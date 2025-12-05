<?php
include "db.php";
global $connection;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['Email'];
    $password = $_POST['Password'];

    $sql = "SELECT * FROM registerusers WHERE Email='$email' LIMIT 1";
    $result = $connection->query($sql);

    if ($result->num_rows == 0) {
        // Email غير موجود
        header("Location: ../LoginPage.html?error=Email+not+found");
        exit();
    }

    $user = $result->fetch_assoc();

    if ($user['Password'] !== $password) {
        // كلمة السر غلط
        header("Location: ../LoginPage.html?error=Wrong+Password");
        exit();
    }

    // نجاح 💙
    header("Location: ../Categories.html");
    exit();
}
?>
