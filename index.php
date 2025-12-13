<?php
global $connection;
include "db.php";
//////////////////////////////
// 3) Get Categories
//////////////////////////////
$categories_sql = "SELECT * FROM categories ORDER BY id ASC LIMIT 10";
$categories_result = $connection->query($categories_sql);


$books_sql = "SELECT b.*, a.name AS author_name 
              FROM books b 
              JOIN authors a ON b.author_id = a.id
              ORDER BY rating DESC 
              LIMIT 3";
$books_result = $connection->query($books_sql);

//////////////////////////////
// 2) Get Authors
//////////////////////////////
$authors_sql = "SELECT * FROM authors ORDER BY rating DESC";
$authors_result = $connection->query($authors_sql);

//////////////////////////////
// 3) Get Popular Books (All Books Ordered by Rating)
//////////////////////////////
$popular_sql = "SELECT b.*, a.name AS author_name 
                FROM books b 
                JOIN authors a ON b.author_id = a.id
                ORDER BY rating DESC";
$popular_result = $connection->query($popular_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONTS & ICONS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS FILES -->
    <link rel="stylesheet" href="cssfolder/indexStyle.css">
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">

    <!-- JS -->
    <script src="Js/Scroll.js"></script>
</head>

<body>

<div class="main">

    <!-- ======================================================
                         HEADER SECTION
    ======================================================= -->
    <?php include "header.php"; ?>

</div>

<!-- ==========================================
            TOP RATED BOOKS
=========================================== -->
<div id="Best" class="top-barSecond">
    <p>Meet the Books That Redefined Success and Inspiration</p>
</div>

<div class="HorezantalBook">

    <?php while ($book = $books_result->fetch_assoc()): ?>
        <?php

        $bookImg = $book['image'];
        if (strpos($bookImg, 'categories/') === false) {

            $bookImg = preg_replace('#^imge/#', 'imge/categories/', $bookImg);
        }
        ?>
        <a href="DetalisPsge.php?id=<?= $book['id'] ?>" class="aunco">
            <div class="Books reveal">

                <img src="./<?= $bookImg ?>" class="imgeBook">

                <h2><?= $book['title'] ?></h2>
                <p><?= $book['author_name'] ?></p>

                <div class="stars">
                    <?php for ($i = 0; $i < $book['rating']; $i++): ?>
                        <i class="fa-solid fa-star"></i>
                    <?php endfor; ?>
                </div>

            </div>
        </a>
    <?php endwhile; ?>

    <div class="whySection reveal">
        <h2 class="whyTitle">Why These Books Have the Highest Selling Rate?</h2>

        <div class="whyContainer">
            <div class="whyCard">
                <h3>Inspiring & Life-Changing Lessons</h3>
                <p>These books share timeless lessons helping readers rethink success and mindset.</p>
            </div>

            <div class="whyCard">
                <h3>Global Popularity & Trust</h3>
                <p>Loved by millions worldwide, translated into many languages.</p>
            </div>

            <div class="whyCard">
                <h3>Practical & Easy</h3>
                <p>Real-life examples and practical frameworks for everyone.</p>
            </div>
        </div>
    </div>
</div>


<!-- ==========================================
            AUTHORS SECTION
=========================================== -->
<div id="AuthorId" class="AuthorTap">
    <p>Author in our bookshop</p>
</div>

<div class="Author">
    <section id="AuthorsShowcase" class="hero-author">
        <div id="authorsContainer">

            <?php while ($a = $authors_result->fetch_assoc()): ?>
                <?php
                // إصلاح مسار صورة الكاتب
                $authorImg = $a['image'];          // مثال: imge/authors/jamesclear.jpg
                if (strpos($authorImg, 'categories/') === false) {
                    $authorImg = preg_replace('#^imge/#', 'imge/categories/', $authorImg);
                }
                ?>
                <div class="AuthorBlock reveal">

                    <div class="author-img">
                        <img src="./<?= $authorImg ?>">
                    </div>

                    <div class="author-info">
                        <p class="hello">HELLO! I'M</p>
                        <h1 class="name"><?= $a['name'] ?></h1>
                        <h3 class="role">Author & Writer</h3>

                        <p class="bio">
                            Books written: <?= $a['books_written'] ?> • Rating: <?= $a['rating'] ?>
                        </p>

                        <a href="author.php?id=<?= $a['id'] ?>" class="read-btn">Read Bio →</a>
                        <button class="scrollNext" onclick="scrollToNext()">↓ Scroll</button>
                    </div>

                </div>
            <?php endwhile; ?>

        </div>
    </section>
</div>


<!-- ==========================================
            POPULAR BOOKS (DYNAMIC)
=========================================== -->
<div id="CategoriesBooks" class="AuthorTap">
    <p>Popular Books</p>
</div>

<div class="SecondBackGround">
    <section class="hero">

        <h2 class="SectionTitle">Popular Books</h2>
        <a href="AllBooks.php" class="noLine">
            <h3 class="clickToshow">Click to show all Books</h3>
        </a>

        <div class="BooksSectionBo">
            <div class="BooksCarousel" id="loopCarousel">

                <?php while ($p = $popular_result->fetch_assoc()): ?>
                    <?php

                    $popImg = $p['image'];
                    if (strpos($popImg, 'categories/') === false) {
                        $popImg = preg_replace('#^imge/#', 'imge/categories/', $popImg);
                    }
                    ?>
                    <a href="DetalisPsge.php?id=<?= $p['id'] ?>" class="noLine">
                        <div class="BookCard reveal">

                            <img src="./<?= $popImg ?>">
                            <h2><?= $p['title'] ?></h2>
                            <p>by <?= $p['author_name'] ?></p>

                            <div class="stars">
                                <?php for ($i = 0; $i < $p['rating']; $i++): ?>
                                    <i class="fa-solid fa-star"></i>
                                <?php endfor; ?>
                            </div>

                        </div>
                    </a>
                <?php endwhile; ?>

            </div>
        </div>


        <!-- CATEGORIES STATIC -->
        <h2 class="SectionTitle">Categories</h2>
        <a href="Categories.php" class="noLine">
            <h3 class="clickToshow">click to show all categories</h3>
        </a>

        <div id="CategoriesCarousel" class="CategoriesCarousel3D">

            <?php while ($c = $categories_result->fetch_assoc()): ?>
                <a href="CategoryDeatlies.php?id=<?= $c['id'] ?>" class="noLine">
                    <div class="CatCard reveal">
                        <img src="./<?= $c['image'] ?>">
                        <h3><?= $c['name'] ?></h3>
                    </div>
                </a>
            <?php endwhile; ?>

        </div>


    </section>
</div>


<?php include "footer.php"; ?>

<!-- JS FILES -->
<script src="Js/AuthorCard.js"></script>
<script src="Js/authors.js"></script>
<script src="Js/CategoryMoation.js"></script>
<script src="Js/Recomandedmotion.js"></script>
<script src="Js/PopularBooks.js"></script>

</body>
</html>
