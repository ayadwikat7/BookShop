<?php
global $connection;
session_start();
include "../db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit;
}

if (isset($_POST['add_sale'], $_POST['book_ids'], $_POST['sale_percent'])) {

    $salePercent = intval($_POST['sale_percent']);
    $bookIds = $_POST['book_ids'];

    foreach ($bookIds as $bookId) {

        // جلب السعر الأصلي
        $stmt = $connection->prepare(
            "SELECT price FROM books WHERE id = ?"
        );
        $stmt->bind_param("i", $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
        $stmt->close();

        if (!$book) continue;

        $newPrice = $book['price'] - ($book['price'] * $salePercent / 100);

        // تحديث الكتاب
        $update = $connection->prepare("
            UPDATE books
            SET isSale = 1,
                newPrice = ?,
                SaleValuePers = ?
            WHERE id = ?
        ");
        $update->bind_param("dii", $newPrice, $salePercent, $bookId);
        $update->execute();
        $update->close();
    }
}

header("Location: ../AdminDashboard.php");
exit;
