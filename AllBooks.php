<?php
global $connection;
include "db.php";

/* =========================
   Pagination Configuration
========================= */
$booksPerPage = 9;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $booksPerPage;

/* =========================
   Fetch Books
========================= */
$books_sql = "
    SELECT b.*, a.name AS author_name
    FROM books b
    JOIN authors a ON b.author_id = a.id
    ORDER BY b.id DESC
    LIMIT $booksPerPage OFFSET $offset
";
$books_result = $connection->query($books_sql);

/* =========================
   Count Total Books
========================= */
$count_sql = "SELECT COUNT(*) AS total FROM books";
$count_result = $connection->query($count_sql);
$totalBooks = (int)$count_result->fetch_assoc()['total'];
$totalPages = (int)ceil($totalBooks / $booksPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Books</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/AllBooks.css">
    <link rel="stylesheet" href="cssfolder/CategoriesStyle.css">
    <link rel="stylesheet" href="cssfolder/CategoriesDeatiles.css">
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


<div class="BooksSectionSalesD">
    <h2 class="SectionTitleD">All Books In Our BookShop</h2>
    <p class="hint">Click on a book to view full details</p>


    <div class="SalesCarouselD">

        <?php while ($book = $books_result->fetch_assoc()): ?>
            <?php
            $bookImg = ltrim($book['image'], './');


            if (strpos($bookImg, 'categories/') === false) {
                $bookImg = preg_replace('#^imge/#', 'imge/categories/', $bookImg, 1);
            }

            $ratingInt = (int) floor($book['rating']);
            ?>


            <a href="DetalisPsge.php?id=<?= $book['id'] ?>">
                <div class="SaleCard">

                    <img src="<?= $bookImg ?>" alt="<?= htmlspecialchars($book['title']) ?>">


                    <h2><?= htmlspecialchars($book['title']) ?></h2>

                    <p class="author">
                        by <span><?= htmlspecialchars($book['author_name']) ?></span>
                    </p>

                    <p class="desc">
                        <?= htmlspecialchars(substr($book['description'], 0, 120)) ?>...
                    </p>

                    <p class="stock">
                        Price: <span>$<?= $book['price'] ?></span>
                    </p>

                    <div class="AddRemoveCart"
                         data-book-id="<?= $book['id'] ?>"
                         data-price="<?= $book['price'] ?>">

                        <button class="page-btn prev add-btn">
                            <i class="fa-solid fa-plus"></i>
                        </button>

                        <span class="qty">0</span>

                        <button class="page-btn next remove-btn">
                            <i class="fa-solid fa-minus"></i>
                        </button>

                    </div>

                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="<?= $i <= $ratingInt ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>

                </div>
            </a>

        <?php endwhile; ?>

    </div>


    <?php include "pagination.php"; ?>

</div>

<?php include "footer.php"; ?>
<script src="Js/cartButtons.js"></script>
<script src="Js/searchBooks.js"></script>
</body>
</html>
