<?php
global $connection;
include "db.php";
session_start();

$cart = $_SESSION['cart'] ?? [];
$books = [];
$total = 0;

if (!empty($cart)) {
    $ids = implode(",", array_map("intval", array_keys($cart)));
    $sql = "SELECT id, title, price, image FROM books WHERE id IN ($ids)";
    $result = $connection->query($sql);

    while ($row = $result->fetch_assoc()) {
        $qty = (int)$cart[$row['id']];
        $subtotal = $qty * (float)$row['price'];

        $row['qty'] = $qty;
        $row['subtotal'] = $subtotal;
        $total += $subtotal;

        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/checkout.css">
</head>

<body>

<?php include "topheader.php"; ?>

<div class="checkout-container">
    <h2>Checkout</h2>

    <?php if (empty($books)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>

        <div class="order-summary">
            <h3>Your Order</h3>

            <?php foreach ($books as $book): ?>
                <div class="order-item">
                    <div class="item-info">
                        <h4><?= htmlspecialchars($book['title']) ?></h4>
                        <p>Price: $<?= number_format((float)$book['price'], 2) ?></p>
                        <p>Quantity: <?= (int)$book['qty'] ?></p>
                    </div>

                    <div class="item-subtotal">
                        $<?= number_format((float)$book['subtotal'], 2) ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="order-total">
                <span>Total</span>
                <strong>$<?= number_format((float)$total, 2) ?></strong>
            </div>
        </div>

        <form class="checkout-form" method="POST" action="place_order.php">
            <label>Full Name</label>
            <input type="text" name="full_name" placeholder="Your name" required>

            <label>Address</label>
            <input type="text" name="address" placeholder="Your address" required>

            <label>Phone</label>
            <input type="text" name="phone" placeholder="Phone number" required>

            <button type="submit">Confirm Order</button>
        </form>

    <?php endif; ?>
</div>

<?php include "footer.php"; ?>

</body>
</html>
