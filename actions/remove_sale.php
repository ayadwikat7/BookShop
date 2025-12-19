<?php
global $connection;
session_start();
include "../db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit;
}

/* كل الكتب اللي عليها سيل حالياً */
$allSaleBooks = [];
$res = $connection->query("SELECT id FROM books WHERE isSale = 1");
while ($row = $res->fetch_assoc()) {
    $allSaleBooks[] = $row['id'];
}

/* الكتب اللي ضل عليهم check */
$keepSale = $_POST['book_ids'] ?? [];

/* الكتب اللي لازم نشيل عنها السيل */
$removeSale = array_diff($allSaleBooks, $keepSale);

foreach ($removeSale as $bookId) {
    $stmt = $connection->prepare("
        UPDATE books
        SET isSale = 0,
            newPrice = NULL,
            SaleValuePers = NULL
        WHERE id = ?
    ");
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../AdminDashboard.php");
exit;
