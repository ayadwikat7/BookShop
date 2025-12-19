<?php
global $connection;
include "db.php";

/* ================= HERO BOOK ================= */
$hero_sql = "
    SELECT b.*, a.name AS author_name
    FROM books b
    JOIN authors a ON b.author_id = a.id
    ORDER BY b.rating DESC
    LIMIT 1
";
$hero = $connection->query($hero_sql)->fetch_assoc();

/* ================= AUTHORS ================= */
$authors = $connection->query("
    SELECT * FROM authors
    ORDER BY rating DESC
    LIMIT 4
");

/* ================= POPULAR BOOKS ================= */
$books = $connection->query("
    SELECT b.*, a.name AS author_name
    FROM books b
    JOIN authors a ON b.author_id = a.id
    ORDER BY b.rating DESC
    LIMIT 8
");

/* ================= CATEGORIES ================= */
$categories = $connection->query("
    SELECT c.*, COUNT(b.id) AS books_count
    FROM categories c
    LEFT JOIN books b ON b.category_id = c.id
    GROUP BY c.id
    LIMIT 6
");

/* ================= IMAGE FIX FUNCTION ================= */
function fixImage($img) {
    $img = ltrim($img, './');
    if (strpos($img, 'categories/') === false) {
        $img = preg_replace('#^imge/#', 'imge/categories/', $img, 1);
    }
    return $img;
}
/* ================= SALE BOOKS ================= */
$sale_books = $connection->query("
    SELECT b.*, a.name AS author_name
    FROM books b
    JOIN authors a ON b.author_id = a.id
    WHERE b.isSale = 1
    ORDER BY b.rating DESC
   
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/indexStyle.css">
    <link rel="stylesheet" href="cssfolder/CategoriesDeatiles.css">
</head>
<body>

<?php include "header.php"; ?>

<!-- ================= HERO ================= -->
<section class="hero reveal">
    <div class="hero-content">
        <span class="badge">Top Rated</span>
        <h1><?= htmlspecialchars($hero['title']) ?></h1>
        <p class="author">by <?= htmlspecialchars($hero['author_name']) ?></p>
        <p class="desc"><?= htmlspecialchars(substr($hero['description'],0,160)) ?>...</p>

        <a href="DetalisPsge.php?id=<?= $hero['id'] ?>" class="btn-primary">
            Explore Book →
        </a>
    </div>

    <img src="<?= fixImage($hero['image']) ?>" class="hero-img" alt="<?= htmlspecialchars($hero['title']) ?>">
</section>

<!-- ================= POPULAR BOOKS ================= -->
<section class="section">
    <h2 class="section-title">Popular Books</h2>
    <p class="hint">Readers’ favorites</p>

    <div class="grid books">
        <?php while($b = $books->fetch_assoc()): ?>
            <a href="DetalisPsge.php?id=<?= $b['id'] ?>" class="book-card reveal">
                <img src="<?= fixImage($b['image']) ?>" alt="<?= htmlspecialchars($b['title']) ?>">
                <h3><?= htmlspecialchars($b['title']) ?></h3>
                <p><?= htmlspecialchars($b['author_name']) ?></p>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="cta">
        <a href="AllBooks.php" class="btn-outline">View All Books</a>
    </div>
</section>

<!-- ================= AUTHORS ================= -->
<section class="section alt">
    <h2 class="section-title">Top Authors</h2>

    <div class="grid authors">
        <?php while($a = $authors->fetch_assoc()): ?>
            <div class="author-card reveal">
                <img src="<?= fixImage($a['image']) ?>" alt="<?= htmlspecialchars($a['name']) ?>">
                <h3><?= htmlspecialchars($a['name']) ?></h3>
                <span>⭐ <?= $a['rating'] ?></span>
                <a href="AuthorBooks.php?id=<?= $a['id'] ?>">View Profile</a>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="cta">
        <a href="Author.php" class="btn-outline">View All Authors</a>
    </div>
</section>

<!-- ================= CATEGORIES ================= -->
<section class="section">
    <h2 class="section-title">Categories</h2>

    <div class="grid categories">
        <?php while($c = $categories->fetch_assoc()): ?>
            <a href="CategoryDeatlies.php?id=<?= $c['id'] ?>" class="category-card reveal">
                <img src="<?= fixImage($c['image']) ?>" alt="<?= htmlspecialchars($c['name']) ?>">
                <div class="overlay">
                    <h3><?= htmlspecialchars($c['name']) ?></h3>
                    <span><?= $c['books_count'] ?> Books</span>
                </div>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="cta">
        <a href="Categories.php" class="btn-primary">Browse All Categories</a>
    </div>
</section>
<!-- ================= SALE SECTION ================= -->
<section class="section sale-section">
    <h2 class="section-title">🔥 Special Offers</h2>
    <p class="hint">Limited-time deals you don’t want to miss</p>

    <div class="grid sale-grid">
        <?php while($s = $sale_books->fetch_assoc()): ?>
            <a href="DetalisPsge.php?id=<?= $s['id'] ?>" class="sale-card reveal">
                <span class="sale-badge">SALE</span>

                <img src="<?= fixImage($s['image']) ?>" alt="<?= htmlspecialchars($s['title']) ?>">

                <h3><?= htmlspecialchars($s['title']) ?></h3>
                <p class="author"><?= htmlspecialchars($s['author_name']) ?></p>

                <div class="price-box">
                    <?php if (!empty($s['old_price'])): ?>
                        <span class="old-price">$<?= $s['old_price'] ?></span>
                    <?php endif; ?>
                    <span class="new-price">$<?= $s['price'] ?></span>
                </div>
            </a>
        <?php endwhile; ?>
    </div>


</section>

<?php include "footer.php"; ?>

<script src="Js/index.js"></script>
</body>
</html>
