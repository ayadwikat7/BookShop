<?php
global $connection;
include "db.php";

/* ================= PAGINATION SETTINGS ================= */
$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

/* ================= TOTAL AUTHORS COUNT ================= */
$countResult = $connection->query("SELECT COUNT(*) AS total FROM authors");
$totalAuthors = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalAuthors / $limit);

/* ================= AUTHORS QUERY ================= */
$authors = $connection->query("
    SELECT a.*,
           COUNT(b.id) AS books_count
    FROM authors a
    LEFT JOIN books b ON b.author_id = a.id
    GROUP BY a.id
    ORDER BY a.rating DESC
    LIMIT $limit OFFSET $offset
");

/* ================= IMAGE FIX FUNCTION ================= */
function fixImage($img) {
    $img = ltrim($img, './');
    if (strpos($img, 'authors/') === false) {
        $img = preg_replace('#^imge/#', 'imge/categories/authors/', $img, 1);
    }
    return $img;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Authors</title>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="cssfolder/Authors.css">
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/CategoriesStyle.css">
    <link rel="stylesheet" href="cssfolder/CategoriesDeatiles.css">
</head>

<body>

<?php include "topheader.php"; ?>

<!-- ================= SEARCH SECTION ================= -->
<div class="Search">
    <h2>🔍 Search By Name of Author</h2>
    <p class="hint">Write the name of an author or use filters below</p>

    <div class="SearchBox">
        <input type="text" class="SearchInput" placeholder="Search for an author...">
        <button class="SearchButton">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </div>
</div>

<!-- ================= AUTHORS LIST ================= -->
<?php while($a = $authors->fetch_assoc()): ?>
    <a class="a" href="AuthorBooks.php?id=<?= $a['id'] ?>">
        <div class="AuthorCard">

            <img
                    src="<?= fixImage($a['image']) ?>"
                    alt="<?= htmlspecialchars($a['name']) ?>"
                    class="AuthorImage"
            >

            <div class="AuthorContent">

                <h2 class="AuthorName">
                    <?= htmlspecialchars($a['name']) ?>
                </h2>

                <div class="AuthorStats">
                    <p>
                        <i class="fa-solid fa-book"></i>
                        Books Written:
                        <strong><?= $a['books_written'] ?></strong>
                    </p>

                    <p>
                        <i class="fa-solid fa-layer-group"></i>
                        Books in Store:
                        <strong><?= $a['books_count'] ?></strong>
                    </p>

                    <p>
                        <i class="fa-solid fa-cake-candles"></i>
                        Birthdate:
                        <strong><?= $a['birthdate'] ?></strong>
                    </p>
                </div>

                <div class="AuthorRating">
                    ⭐ <?= number_format($a['rating'], 1) ?> / 5
                </div>

            </div>

        </div>
    </a>
<?php endwhile; ?>
<?php include "pagination.php"?>
<!-- ================= PAGINATION (STATIC FOR NOW) ================= -->
<!--<div class="pagination-container">-->
<!--    <button class="page-btn prev">« Previous Page</button>-->
<!--    <span class="page-number">Page <strong>1</strong></span>-->
<!--    <button class="page-btn next">Next Page »</button>-->
<!--</div>-->

<?php include "footer.php"; ?>

</body>
</html>
