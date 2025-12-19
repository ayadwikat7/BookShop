<?php
global $connection;
include "db.php";

/* ================= AUTHOR ID ================= */
if (!isset($_GET['id'])) {
    die("Author not found");
}
$author_id = (int)$_GET['id'];

/* ================= PAGINATION ================= */
$limit = 6;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

/* ================= AUTHOR DATA ================= */
$authorResult = $connection->query("
    SELECT a.*, COUNT(b.id) AS books_count
    FROM authors a
    LEFT JOIN books b ON b.author_id = a.id
    WHERE a.id = $author_id
    GROUP BY a.id
");

$author = $authorResult->fetch_assoc();
if (!$author) {
    die("Author not found");
}

/* ================= COUNT BOOKS ================= */
$countResult = $connection->query("
    SELECT COUNT(*) AS total
    FROM books
    WHERE author_id = $author_id
");
$totalBooks = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalBooks / $limit);

/* ================= AUTHOR BOOKS ================= */
$books = $connection->query("
    SELECT *
    FROM books
    WHERE author_id = $author_id
    ORDER BY rating DESC
    LIMIT $limit OFFSET $offset
");

/* ================= IMAGE FIX FUNCTIONS ================= */
function fixImage($img) {
    $img = ltrim($img, './');
    if (strpos($img, 'authors/') === false) {
        $img = preg_replace('#^imge/#', 'imge/categories/authors/', $img, 1);
    }
    return $img;
}

function bookImage($img) {
    $img = ltrim($img, './');
    if (strpos($img, 'categories/') === false) {
        $img = preg_replace('#^imge/#', 'imge/categories/', $img, 1);
    }
    return $img;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Author Books</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="cssfolder/Authors.css">
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
</head>

<body>

<?php include "topheader.php"; ?>

<!-- ====================== SEARCH SECTION ======================= -->
<div class="Search">
    <h2>🔍 Search For Your Favorite Book</h2>
    <p class="hint">Write the name of a Book or use filters below to refine your search</p>

    <div class="SearchBox">
        <input type="text" class="SearchInput" placeholder="Search for a book...">
        <button class="SearchButton">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</div>

<!-- ====================== AUTHOR CARD ======================= -->
<div class="AuthorCard">

    <img src="<?= fixImage($author['image']) ?>" alt="Author Photo" class="AuthorImage">

    <div class="AuthorContent">
        <h2 class="AuthorName"><?= htmlspecialchars($author['name']) ?></h2>

        <div class="AuthorStats">
            <p>
                <i class="fa-solid fa-book"></i>
                Books Written:
                <strong><?= $author['books_written'] ?></strong>
            </p>

            <p>
                <i class="fa-solid fa-layer-group"></i>
                Books in Store:
                <strong><?= $author['books_count'] ?></strong>
            </p>

            <p>
                <i class="fa-solid fa-cake-candles"></i>
                Birthdate:
                <strong><?= $author['birthdate'] ?></strong>
            </p>
        </div>

        <div class="AuthorRating">
            ⭐ <?= number_format($author['rating'], 1) ?> / 5
        </div>
    </div>
</div>

<!-- ====================== BOOKS SECTION ======================= -->
<div id="CategoryId" class="BooksSectionSalesD">

    <h2 class="SectionTitleD">
        Books by <?= htmlspecialchars($author['name']) ?>
    </h2>

    <p class="hint">Click on a book to show all details</p>

    <div class="SalesCarouselD">
        <?php while ($b = $books->fetch_assoc()): ?>
            <a href="DetalisPsge.php?id=<?= $b['id'] ?>" class="BookCard">

                <img src="<?= bookImage($b['image']) ?>" alt="<?= htmlspecialchars($b['title']) ?>">

                <h3><?= htmlspecialchars($b['title']) ?></h3>

                <div class="BookRating">
                    ⭐ <?= number_format($b['rating'], 1) ?>
                </div>

                <p class="BookPrice">
                    $<?= number_format($b['price'], 2) ?>
                </p>

            </a>
        <?php endwhile; ?>
    </div>

    <!-- ====================== PAGINATION ======================= -->


</div>
<?php include "pagination.php"; ?>

<?php include "footer.php"; ?>

</body>
</html>
