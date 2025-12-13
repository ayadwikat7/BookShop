<?php
global $connection;
include "db.php";

/* =========================
   Pagination Configuration
========================= */
$catsPerPage = 8; // صفين (4 + 4)

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $catsPerPage;

/* =========================
   Fetch Categories
========================= */
$cat_sql = "
    SELECT *
    FROM categories
    LIMIT $catsPerPage OFFSET $offset
";
$cat_result = $connection->query($cat_sql);

/* =========================
   Count Categories
========================= */
$count_sql = "SELECT COUNT(*) AS total FROM categories";
$count_result = $connection->query($count_sql);
$totalCats = (int)$count_result->fetch_assoc()['total'];
$totalPages = ceil($totalCats / $catsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/CategoriesStyle.css">


    <link rel="stylesheet" href="cssfolder/AllBooks.css">
<!--    <link rel="stylesheet" href="cssfolder/CategoriesStyle.css">-->
<!--    <link rel="stylesheet" href="cssfolder/CategoriesDeatiles.css">-->
</head>

<body>

<div class="main">
    <header class="header">

        <div class="top-bar">
            <p>📚 Free shipping on orders over $50 | Discover your next great read today!</p>
        </div>

        <div class="nav-container">
            <div class="logo">
                <i class="fa-solid fa-book-bookmark"></i>
                <h1>Book Shop</h1>
            </div>

            <div class="user-icons">
                <i class="fa-solid fa-heart"></i>
                <i class="fa-solid fa-cart-shopping"></i>
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <nav class="menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="AllBooks.php">Books</a></li>
                <li><a href="#CategoryId">Categories</a></li>
                <li><a href="Author.php">Authors</a></li>
                <li><a href="ContactUs.php">Contact Us</a></li>
            </ul>
        </nav>

    </header>
</div>

<!-- ================= Categories Section ================= -->

<div id="CategoryId" class="BooksSectionSales">
    <h2 class="SectionTitle">Categories</h2>
    <p class="hint">Click On Category To Show All Books</p>

    <div class="SalesCarousel">

        <?php while ($cat = $cat_result->fetch_assoc()): ?>
            <?php
            $catImg = ltrim($cat['image'], './');
            if (strpos($catImg, 'categories/') === false) {
                $catImg = 'imge/categories/' . $catImg;
            }
            ?>

            <a href="CategoryDeatlies.php?id=<?= $cat['id'] ?>">
                <div class="SaleCard">

                    <img src="<?= $catImg ?>" alt="<?= htmlspecialchars($cat['name']) ?>">

                    <h2><?= htmlspecialchars($cat['name']) ?></h2>



                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                    </div>

                </div>
            </a>

        <?php endwhile; ?>

    </div>

    <!-- ================= Pagination ================= -->
    <?php include "pagination.php"; ?>

</div>


<?php include "footer.php"; ?>

</body>
</html>
