<?php
global $connection;
session_start();
include "../db.php";

/* ================== ADMIN CHECK ================== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth.php");
    exit;
}

/* ================== ADD AUTHOR ================== */
if (
    isset($_POST['name']) &&
    isset($_POST['birthdate']) &&
    isset($_POST['rating']) &&
    isset($_POST['books_written'])
) {

    $name         = trim($_POST['name']);
    $birthdate    = $_POST['birthdate'];
    $rating       = floatval($_POST['rating']);
    $booksWritten = intval($_POST['books_written']);

    /* ================== IMAGE UPLOAD ================== */
    $imagePath = null;

    if (!empty($_FILES['image']['name'])) {

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowedExt)) {

            $imageName = uniqid('author_') . '.' . $ext;
            $uploadDir = "../imge/categories/authors/";

            // تأكدي إن المجلد موجود
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fullPath = $uploadDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $fullPath)) {
                // نخزن المسار النسبي بالـ DB
                $imagePath = "imge/categories/authors/" . $imageName;
            }
        }
    }

    /* ================== INSERT AUTHOR ================== */
    $stmt = $connection->prepare("
        INSERT INTO authors (name, birthdate, rating, books_written, image)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssdis",
        $name,
        $birthdate,
        $rating,
        $booksWritten,
        $imagePath
    );

    $stmt->execute();
    $stmt->close();
}

/* ================== REDIRECT ================== */
header("Location: ../AdminDashboard.php");
exit;
