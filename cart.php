<?php
global $connection;
session_start();
include "db.php";

$cart = $_SESSION['cart'] ?? [];

$books = [];
$total = 0;

if (!empty($cart)) {
    $ids = implode(",", array_keys($cart));
    $sql = "SELECT * FROM books WHERE id IN ($ids)";
    $result = $connection->query($sql);

    while ($row = $result->fetch_assoc()) {

        // quantity + subtotal
        $row['qty'] = $cart[$row['id']];
        $row['subtotal'] = $row['qty'] * $row['price'];
        $total += $row['subtotal'];

        // fix image path
        $img = ltrim($row['image'], './');
        if (strpos($img, 'categories/') === false) {
            $img = preg_replace('#^imge/#', 'imge/categories/', $img, 1);
        }
        $row['img_fixed'] = $img;

        $books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Shopping Cart</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/cart.css">
</head>

<body>

<?php include "topheader.php"; ?>

<div class="CartPage">

    <h2 class="CartTitle">Your Shopping Cart</h2>

    <?php if (empty($books)): ?>
        <p class="EmptyCart">Your cart is empty 🛒</p>
    <?php else: ?>

        <div class="CartList">

            <?php foreach ($books as $book): ?>
                <div class="CartItem">

                    <img src="<?= $book['img_fixed'] ?>"
                         alt="<?= htmlspecialchars($book['title']) ?>">

                    <div class="CartDetails">
                        <h3><?= htmlspecialchars($book['title']) ?></h3>

                        <p class="price">Price: <span>$<?= $book['price'] ?></span></p>
                        <p class="qty">Quantity: <strong><?= $book['qty'] ?></strong></p>

                        <p class="subtotal">
                            Subtotal: <strong>$<?= number_format($book['subtotal'], 2) ?></strong>
                        </p>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>

        <div class="CartSummary">
            <h3>Total: $<?= number_format($total, 2) ?></h3>
            <button class="CheckoutBtn">
                <i class="fa-solid fa-credit-card"></i> Proceed to Checkout
            </button>
        </div>

    <?php endif; ?>

</div>

<?php include "footer.php"; ?>

</body>
</html>
