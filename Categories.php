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

<?php include "topheader.php"; ?>
<div class="Search">
    <h2>🔍 Search For Your Favorite Book</h2>
    <p class="hint">Write the name of a Book or use filters below</p>

    <div class="SearchBox">
        <input type="text" class="SearchInput" placeholder="Search for a book...">
        <button class="SearchButton">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>

    <div class="FilterBox">
        <h3>🎯 Filter Options</h3>

        <div class="filters">
            <select class="FilterSelect">
                <option selected disabled>Category</option>
                <option>Business</option>
                <option>Technology</option>
                <option>Psychology</option>
                <option>Science Fiction</option>
            </select>

            <select class="FilterSelect">
                <option selected disabled>Sort By</option>
                <option>Highest Rated</option>
                <option>Newest</option>
                <option>Oldest</option>
            </select>

            <select class="FilterSelect">
                <option selected disabled>Price Range</option>
                <option>0 - 20$</option>
                <option>21 - 50$</option>
                <option>50$+</option>
            </select>
        </div>

        <button class="ApplyFilter">
            <i class="fa-solid fa-filter"></i> Apply Filters
        </button>
    </div>
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
                    <p style="font-size:14px; color:white; margin:15px 0;">
                        📚 <?= (int)$cat['books_count'] ?> Books
                    </p>
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
