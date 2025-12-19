<?php
global $connection;
session_start();
include "../db.php";

/* ===== Admin Check ===== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Not authorized");
}

/* ===== Remove Category ===== */
if (isset($_POST['remove_category'], $_POST['category_name'])) {

    $name = trim($_POST['category_name']);

    // للتأكد إن الاسم موجود
    $check = $connection->prepare(
        "SELECT id FROM categories WHERE name = ?"
    );
    $check->bind_param("s", $name);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        die("Category not found");
    }

    $check->close();

    // الحذف
    $stmt = $connection->prepare(
        "DELETE FROM categories WHERE name = ?"
    );
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../AdminDashboard.php");
exit;
