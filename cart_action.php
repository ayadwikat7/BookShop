<?php
session_start();

$bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
$action = $_POST['action'] ?? '';

if ($bookId <= 0) {
    echo json_encode(['success' => false]);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {

    case 'add':
        $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + 1;
        break;

    case 'remove':
        if (isset($_SESSION['cart'][$bookId])) {
            $_SESSION['cart'][$bookId]--;
            if ($_SESSION['cart'][$bookId] <= 0) {
                unset($_SESSION['cart'][$bookId]);
            }
        }
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        break;
}

echo json_encode([
    'success' => true,
    'qty' => $_SESSION['cart'][$bookId] ?? 0
]);
