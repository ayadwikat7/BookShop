<?php
global $connection;
session_start();
include "db.php";

$cart = $_SESSION['cart'] ?? [];
$books = [];
$total = 0;

if (!empty($cart)) {
    $ids = implode(",", array_map("intval", array_keys($cart))); // ✅ safer
    $sql = "SELECT * FROM books WHERE id IN ($ids)";
    $result = $connection->query($sql);

    while ($row = $result->fetch_assoc()) {

        $row['qty'] = (int)$cart[$row['id']];
        $row['subtotal'] = $row['qty'] * (float)$row['price'];
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

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

                    <img src="<?= htmlspecialchars($book['img_fixed']) ?>"
                         alt="<?= htmlspecialchars($book['title']) ?>">

                    <div class="CartDetails">
                        <h3><?= htmlspecialchars($book['title']) ?></h3>

                        <p class="price">Price: <span>$<?= number_format((float)$book['price'], 2) ?></span></p>

                        <div class="qty-control"
                             data-id="<?= (int)$book['id'] ?>"
                             data-stock="<?= (int)$book['stock'] ?>">

                            <button class="qty-btn minus" type="button">−</button>
                            <span class="qty-num"><?= (int)$book['qty'] ?></span>
                            <button class="qty-btn plus" type="button">+</button>

                        </div>

                        <p class="subtotal">
                            Subtotal: <strong>$<?= number_format((float)$book['subtotal'], 2) ?></strong>
                        </p>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

        <div class="CartSummary">
            <h3>Total: $<?= number_format((float)$total, 2) ?></h3>

            <!-- ✅ FIX: صار رابط شغال -->
            <a href="checkout.php" class="CheckoutBtn">
                <i class="fa-solid fa-credit-card"></i> Proceed to Checkout
            </a>

            <!-- (اختياري) clear cart لو عندك action=clear -->
            <button class="ClearCartBtn" type="button" onclick="clearCart()">
                <i class="fa-solid fa-trash"></i> Clear Cart
            </button>
        </div>

    <?php endif; ?>

</div>

<?php include "footer.php"; ?>

<script>
    document.querySelectorAll('.qty-control').forEach(control => {
        const bookId = control.dataset.id;
        const stock = parseInt(control.dataset.stock);
        const qtySpan = control.querySelector('.qty-num');

        control.querySelector('.plus').addEventListener('click', () => {
            let currentQty = parseInt(qtySpan.textContent);
            if (currentQty >= stock) {
                alert("⚠️ You reached the maximum available stock!");
                return;
            }
            updateCart(bookId, 'add');
        });

        control.querySelector('.minus').addEventListener('click', () => {
            updateCart(bookId, 'remove');
        });
    });

    function updateCart(bookId, action) {
        fetch('cart_action.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `book_id=${bookId}&action=${action}`
        })
            .then(res => res.json())
            .then(() => location.reload());
    }

    function clearCart(){
        fetch('cart_action.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=clear`
        })
            .then(res => res.json())
            .then(() => location.reload());
    }
</script>

<script src="Js/popMessage.js"></script>

</body>
</html>
