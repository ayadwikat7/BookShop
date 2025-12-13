<?php
global $connection;
include "db.php";

// ------------------------
// 1) استلام ID الكاتيجوري
// ------------------------
$cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// ------------------------
// 2) جلب بيانات الكاتيجوري
// ------------------------
$cat_sql = "SELECT * FROM categories WHERE id = $cat_id";
$cat_result = $connection->query($cat_sql);
$category = $cat_result->fetch_assoc();

// إذا الـ ID غلط
if (!$category) {
    die("Category not found!");
}

// ------------------------
// 3) جلب كتب الكاتيجوري
// ------------------------
$books_sql = "
    SELECT b.*, a.name AS author_name 
    FROM books b
    JOIN authors a ON b.author_id = a.id
    WHERE b.category_id = $cat_id
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
    <link rel="stylesheet" href="cssfolder/CategoriesStyle.css">
    <link rel="stylesheet" href="cssfolder/CategoriesDeatiles.css">
</head>
<body>

<div class="main">
    <!-- نفس الهيدر تبعك -->
    <?php include "topheader.php"; ?>
</div>

<!-- ======================= SEARCH + FILTER (نفس هيكلك القديم) ======================= -->
<div class="Search">
    <h2>🔍 Search Inside <?= $category['name'] ?></h2>
    <p class="hint">Search for books or authors inside this category</p>

    <div class="SearchBox">
        <input type="text" class="SearchInput" placeholder="Search for a category...">
        <button class="SearchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
    </div>

    <div class="FilterBox">
        <h3>🎯 Filter Options</h3>
        <div class="filters">
            <select class="FilterSelect">
                <option selected disabled>Category Type</option>
                <option>Business</option>
                <option>Technology</option>
                <option>Health</option>
                <option>Fiction</option>
                <option>Self-Development</option>
            </select>

            <select class="FilterSelect">
                <option selected disabled>Sort By</option>
                <option>Most Popular</option>
                <option>Newest</option>
                <option>Oldest</option>
                <option>Highest Rated</option>
            </select>
        </div>
        <button class="ApplyFilter"><i class="fa-solid fa-filter"></i> Apply Filters</button>
    </div>
</div>

<!-- ======================= BOOKS OF THIS CATEGORY ======================= -->
<div id="CategoryId" class="BooksSectionSalesD">
    <h2 class="SectionTitleD"><?= $category['name'] ?></h2>
    <p class="hint">Click on any book to view full details</p>

    <div class="SalesCarouselD">

        <?php while ($b = $books_result->fetch_assoc()): ?>

            <?php
            // إصلاح مسار الصورة:
            // في الداتابيس: imge/health/atomic.jpg
            // في المشروع:   imge/categories/health/atomic.jpg
            $imgPath = $b['image'];
            if (strpos($imgPath, 'categories/') === false) {
                $imgPath = str_replace('imge/', 'imge/categories/', $imgPath);
            }
            ?>

            <a href="detalies.php?id=<?= $b['id'] ?>">
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

        <?php if ($books_result->num_rows == 0): ?>
            <p class="noBooks">❌ No books available in this category yet.</p>
        <?php endif; ?>

        <!-- نفس الباجينيشن التصميمي اللي عندك -->
        <div class="pagination-container">
            <button class="page-btn prev">« Previous Page</button>
            <span class="page-number">Page <strong>1</strong></span>
            <button class="page-btn next">Next Page »</button>
        </div>

    </div>
</div>

<!-- FOOTER -->
<?php include "footer.php"; ?>

</body>
</html>
