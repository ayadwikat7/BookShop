<?php
global $connection;
session_start();
include "../db.php";

/* ===== Admin Check ===== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit;
}

/* ===== Add Category ===== */
if (isset($_POST['add_category'], $_POST['category_name'])) {

    $name = trim($_POST['category_name']);
    $imagePath = null;

    /* ===== Image Upload ===== */
    if (!empty($_FILES['category_image']['name'])) {

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['category_image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowedExt)) {

            $imageName = uniqid('cat_') . '.' . $ext;
            $uploadDir = "../imge/categories/";

            // إنشاء المجلد لو مش موجود
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fullPath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['category_image']['tmp_name'], $fullPath)) {
                $imagePath = "imge/categories/" . $imageName;
            }
        }
    }

    /* ===== Insert Category ===== */
    $stmt = $connection->prepare("
        INSERT INTO categories (name, image, books_count)
        VALUES (?, ?, 0)
    ");

    $stmt->bind_param("ss", $name, $imagePath);
    $stmt->execute();
    $stmt->close();
}

/* ===== Redirect ===== */
header("Location: ../AdminDashboard.php");
exit;
