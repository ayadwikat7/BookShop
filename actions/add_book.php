<?php
global $connection;
session_start();
include "../db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit;
}

$_SESSION['result'] = null;

if (isset($_POST['add_book'])) {

    $title         = trim($_POST['title']);
    $author_name   = trim($_POST['author_name']);
    $category_name = trim($_POST['category_name']);

    /* 1️⃣ تحقق من تكرار اسم الكتاب */
    $check = $connection->prepare(
        "SELECT id FROM books WHERE title = ? LIMIT 1"
    );
    $check->bind_param("s", $title);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['result'] = [
            "type" => "error",
            "msg"  => "Book with the same title already exists ❌"
        ];
        header("Location: ../adminDashboard.php");
        exit;
    }

    /* 2️⃣ جلب author_id بالاسم */
    $stmt = $connection->prepare(
        "SELECT id FROM authors WHERE name = ? LIMIT 1"
    );
    $stmt->bind_param("s", $author_name);
    $stmt->execute();
    $author = $stmt->get_result()->fetch_assoc();

    if (!$author) {
        $_SESSION['result'] = [
            "type" => "error",
            "msg"  => "Author not found ❌"
        ];
        header("Location: ../adminDashboard.php");
        exit;
    }


    $stmt = $connection->prepare(
        "SELECT id FROM categories WHERE name = ? LIMIT 1"
    );
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $category = $stmt->get_result()->fetch_assoc();

    if (!$category) {
        $_SESSION['result'] = [
            "type" => "error",
            "msg"  => "Category not found ❌"
        ];
        header("Location: ../adminDashboard.php");
        exit;
    }

    /* 4️⃣ رفع الصورة */
    $uploadDir = "../imge/books/";
    $dbPath    = "image/books/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageName = time() . "_" . basename($_FILES['book_image']['name']);
    move_uploaded_file(
        $_FILES['book_image']['tmp_name'],
        $uploadDir . $imageName
    );

    /* 5️⃣ إدخال الكتاب */
    $stmt = $connection->prepare("
        INSERT INTO books
        (title, author_id, category_id, image, description,
         price, newPrice, rating, stock, isSale, SaleValuePers,
         pages, sections)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $str = $dbPath . $imageName;
    $stmt->bind_param(
        "siissdddiisii",
        $title,
        $author['id'],
        $category['id'],
        $str,
        $_POST['description'],
        $_POST['price'],
        $_POST['newPrice'],
        $_POST['rating'],
        $_POST['stock'],
        $_POST['isSale'],
        $_POST['SaleValuePers'],
        $_POST['pages'],
        $_POST['sections']
    );

    $stmt->execute();

    $_SESSION['result'] = [
        "type" => "success",
        "msg"  => "Book added successfully ✅"
    ];
}

header("Location: ../adminDashboard.php");
exit;
