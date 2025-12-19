<?php
global $connection;
include "db.php";
session_start();

/* =========================
   1) لازم يكون POST من checkout
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit;
}

/* =========================
   2) قراءة بيانات المستخدم من الفورم
========================= */
$full_name = trim($_POST['full_name'] ?? '');
$address   = trim($_POST['address'] ?? '');
$phone     = trim($_POST['phone'] ?? '');

if ($full_name === '' || $address === '' || $phone === '') {
    header("Location: checkout.php");
    exit;
}

/* =========================
   3) لازم يكون في سلة
========================= */
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php?error=" . urlencode("Cart is empty"));
    exit;
}

/* =========================
   4) لازم يكون المستخدم مسجل دخول (حسب اللي بدك اياه)
========================= */
$user_id = $_SESSION['user_id'] ?? 0;
if ((int)$user_id <= 0) {
    header("Location: login.php?error=" . urlencode("Please login first"));
    exit;
}

/* =========================
   5) جلب الكتب من الداتابيس وحساب التوتال + التأكد من الستوك
========================= */
$ids = array_map("intval", array_keys($cart));
$idsList = implode(",", $ids);

$sql = "SELECT id, price, stock FROM books WHERE id IN ($idsList)";
$result = $connection->query($sql);

if (!$result || $result->num_rows == 0) {
    header("Location: cart.php?error=" . urlencode("Books not found"));
    exit;
}

$booksMap = [];  // book_id => [price, stock]
while ($row = $result->fetch_assoc()) {
    $booksMap[(int)$row['id']] = [
        'price' => (float)$row['price'],
        'stock' => (int)$row['stock']
    ];
}

$total = 0;
$orderItems = []; // each: book_id, qty, price, subtotal

foreach ($cart as $bookId => $qty) {
    $bookId = (int)$bookId;
    $qty    = (int)$qty;

    if ($qty <= 0) continue;

    if (!isset($booksMap[$bookId])) {
        header("Location: cart.php?error=" . urlencode("Some books not found"));
        exit;
    }

    $price = $booksMap[$bookId]['price'];
    $stock = $booksMap[$bookId]['stock'];

    if ($qty > $stock) {
        header("Location: cart.php?error=" . urlencode("Not enough stock for book ID $bookId"));
        exit;
    }

    $subtotal = $qty * $price;
    $total += $subtotal;

    $orderItems[] = [
        'book_id'  => $bookId,
        'qty'      => $qty,
        'price'    => $price,
        'subtotal' => $subtotal
    ];
}

if (empty($orderItems)) {
    header("Location: cart.php?error=" . urlencode("Cart is empty"));
    exit;
}

/* =========================
   6) إدخال الأوردر + العناصر بداخل Transaction
========================= */
$connection->begin_transaction();

try {
    // Insert into orders
    $status = "Pending";

    $stmtOrder = $connection->prepare("
        INSERT INTO orders (user_id, full_name, address, phone, total_price, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    if (!$stmtOrder) {
        throw new Exception("Prepare orders failed: " . $connection->error);
    }

    $stmtOrder->bind_param(
        "isssds",
        $user_id,
        $full_name,
        $address,
        $phone,
        $total,
        $status
    );

    if (!$stmtOrder->execute()) {
        throw new Exception("Execute orders failed: " . $stmtOrder->error);
    }

    $order_id = $connection->insert_id;
    $stmtOrder->close();

    // Insert items
    $stmtItem = $connection->prepare("
        INSERT INTO order_items (order_id, book_id, qty, price, subtotal)
        VALUES (?, ?, ?, ?, ?)
    ");
    if (!$stmtItem) {
        throw new Exception("Prepare order_items failed: " . $connection->error);
    }

    // Update stock
    $stmtStock = $connection->prepare("
        UPDATE books SET stock = stock - ? WHERE id = ? AND stock >= ?
    ");
    if (!$stmtStock) {
        throw new Exception("Prepare stock update failed: " . $connection->error);
    }

    foreach ($orderItems as $it) {
        $bid = (int)$it['book_id'];
        $q   = (int)$it['qty'];
        $p   = (float)$it['price'];
        $sub = (float)$it['subtotal'];

        // insert item
        $stmtItem->bind_param("iiidd", $order_id, $bid, $q, $p, $sub);
        if (!$stmtItem->execute()) {
            throw new Exception("Execute order_items failed: " . $stmtItem->error);
        }

        // decrease stock safely
        $stmtStock->bind_param("iii", $q, $bid, $q);
        if (!$stmtStock->execute() || $stmtStock->affected_rows == 0) {
            throw new Exception("Stock update failed (maybe not enough stock).");
        }
    }

    $stmtItem->close();
    $stmtStock->close();

    // commit
    $connection->commit();

    // clear cart
    unset($_SESSION['cart']);

    // redirect to order details
    header("Location: order_details.php?id=" . $order_id);
    exit;

} catch (Exception $e) {
    $connection->rollback();
    header("Location: cart.php?error=" . urlencode("Order failed: " . $e->getMessage()));
    exit;
}
