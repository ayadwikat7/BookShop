<?php
global $connection;
session_start();
include "../db.php";

/* Admin protection */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit;
}

if (isset($_POST['remove_book'])) {

    $title = trim($_POST['book_title']);

    /* حذف الكتاب بالاسم */
    $stmt = $connection->prepare(
        "DELETE FROM books WHERE title = ?"
    );

    if (!$stmt) {
        die("SQL Error: " . $connection->error);
    }

    $stmt->bind_param("s", $title);
    $stmt->execute();
}

header("Location: ../AdminDashboard.php");
exit;
