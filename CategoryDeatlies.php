<?php
global $connection;
include "db.php";

/* =========================
   1) استلام ID الكاتيجوري
========================= */
$cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

/* =========================
   2) Pagination Settings
========================= */
$limit = 3; // عدد الكتب في الصفحة
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

/* =========================
   3) جلب بيانات الكاتيجوري
========================= */
$cat_sql = "SELECT * FROM categories WHERE id = $cat_id";
$cat_result = $connection->query($cat_sql);
$category = $cat_result->fetch_assoc();

if (!$category) {
    die("Category not found!");
}

/* =========================
   4) عدد الكتب الكلي
========================= */
$count_sql = "SELECT COUNT(*) AS total FROM books WHERE category_id = $cat_id";
$count_result = $connection->query($count_sql);
$totalBooks = $count_result->fetch_assoc()['total'];

$totalPages = ceil($totalBooks / $limit);

/* =========================
   5) جلب كتب الكاتيجوري (مع LIMIT)
========================= */
$books_sql = "
    SELECT b.*, a.name AS author_name 
    FROM books b
    JOIN authors a ON b.author_id = a.id
    WHERE b.category_id = $cat_id
    LIMIT $limit OFFSET $offset
";
$books_result = $connection->query($books_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $category['name'] ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/CategoriesDeatiles.css">
</head>
<body>

<div class="main">
    <?php include "topheader.php"; ?>
</div>

<!-- ================= SEARCH ================= -->
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

<!-- ================= BOOKS ================= -->
<div id="CategoryId" class="BooksSectionSalesD">
    <h2 class="SectionTitleD"><?= $category['name'] ?></h2>
    <p class="hint">Click on any book to view full details</p>

    <div class="SalesCarouselD">

        <?php if ($books_result->num_rows > 0): ?>
            <?php while ($b = $books_result->fetch_assoc()): ?>

                <?php
                $imgPath = $b['image'];
                if (strpos($imgPath, 'categories/') === false) {
                    $imgPath = str_replace('imge/', 'imge/categories/', $imgPath);
                }
                ?>

                <a href="DetalisPsge.php?id=<?= $b['id'] ?>">
                    <div class="SaleCard">

                        <img src="./<?= $imgPath ?>" alt="Book Image">

                        <h2><?= $b['title'] ?></h2>

                        <p class="author">by <span><?= $b['author_name'] ?></span></p>

                        <p class="desc">
                            <?= substr($b['description'], 0, 120) ?>...
                        </p>

                        <p class="stock">Price: <span><?= $b['price'] ?>$</span></p>

                        <div class="stars">
                            <?php for ($i = 0; $i < $b['rating']; $i++): ?>
                                <i class="fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </div>

                    </div>
                </a>

            <?php endwhile; ?>
        <?php else: ?>
            <p class="noBooks">❌ No books available in this category yet.</p>
        <?php endif; ?>

    </div>

    <!-- ================= PAGINATION ================= -->
    <?php include "categoryPagination.php"; ?>

</div>

<?php include "footer.php"; ?>
</body>
</html>
