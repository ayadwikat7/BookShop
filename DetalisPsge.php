<?php

global $connection;
include "db.php";

// Get Book ID from URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Bring book + author + category
$sql = "
    SELECT b.*, 
           a.name AS author_name,
           c.name AS category_name
    FROM books b
    JOIN authors a ON b.author_id = a.id
    JOIN categories c ON b.category_id = c.id
    WHERE b.id = $book_id
";

$result = $connection->query($sql);
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found!");
}

/* =========================
   Image Path Fix
========================= */
$imgPath = ltrim($book['image'], './');
if (strpos($imgPath, 'categories/') === false) {
    $imgPath = preg_replace('#^(imge|img)/#', '$1/categories/', $imgPath, 1);
}

/* =========================
   Price Logic
========================= */
$isSale = (int)$book['isSale'] === 1;
$oldPrice = number_format($book['price'], 2);
$newPrice = $isSale ? number_format($book['newPrice'], 2) : null;
$salePercent = $isSale ? (int)$book['SaleValuePers'] : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($book['title']) ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/BookShops/cssfolder/profileStyle.css">
    <link rel="stylesheet" href="/BookShops/cssfolder/footer.css">
    <link rel="stylesheet" href="/BookShops/cssfolder/Detalies.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</head>

<body>

<header class="main-header">
    <div class="header-container">
        <div class="logo">
            <i class="fa-solid fa-book-bookmark"></i>
            <span>Book Shop</span>
        </div>

        <nav class="nav-links">
            <a href="/BookShops/index.php">Home</a>
            <a href="#">Shop</a>
            <a href="categoriesList.php">Categories</a>
            <a href="#">Best Sellers</a>
            <a href="Author.php">Authors</a>
            <a href="ContactUs.php">Contact</a>
        </nav>

        <div class="header-right">
            <button class="signout-btn" onclick="orderNow(<?= $book['id'] ?>)">
                Order Now!
            </button>
        </div>
    </div>
</header>

<div class="top-bar">
    <p>📚 Free shipping on orders over $50 | Discover your next great read today!</p>
</div>

<!-- ================= BOOK DETAILS SECTION ================= -->
<div class="book-details-section">

    <div class="book-info">
        <div class="TitleBooke">
            <h1><?= htmlspecialchars($book['title']) ?></h1>
        </div>

        <div class="DicrebtionBook">
            <p><?= $book['description'] ?></p>

            <!-- 💰 PRICE DISPLAY -->
            <?php if ($isSale): ?>
                <p class="price">
                    <strong><span class="old-price">old price :$<?= $oldPrice ?></span></strong>

                    <br>
                    <strong><span class="new-price">new price :$<?= $newPrice ?></span></strong>
                    <br>
                   <strong> <span class="sale-badge">rating :<?= $salePercent ?>%</span></strong>
                </p>
            <?php else: ?>
                <p class="price">
                    💲 Price: <strong>$<?= $oldPrice ?></strong>
                </p>
            <?php endif; ?>

            <p class="author-name">
                ✍️ Author: <strong><?= htmlspecialchars($book['author_name']) ?></strong>
            </p>

            <div class="Button">
                <button onclick="orderNow(<?= $book['id'] ?>)" class="lablebu">
                    Order Now!
                </button>

                <button class="lablebu add-to-cart-btn"
                        data-book-id="<?= $book['id'] ?>">
                    Add To Cart
                </button>
            </div>
        </div>
    </div>

    <div class="imgeBookHent">
        <div class="book-cover">
            <img src="/BookShops/<?= htmlspecialchars($imgPath) ?>"
                 class="imgeBook"
                 alt="<?= htmlspecialchars($book['title']) ?>">
        </div>
        <p>Click the photo to add it to your cart</p>
    </div>

</div>

<?php include "footer.php"; ?>

<script>
    function orderNow(bookId) {
        fetch("cart_action.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "book_id=" + bookId + "&action=add"
        })
            .then(() => {
                window.location.href = "checkout.php";
            });
    }
</script>

</body>
</html>
