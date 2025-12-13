<?php
global $connection;
include "db.php";

if (!isset($_GET['id'])) {
    die("Missing Author ID");
}
$author_id = intval($_GET['id']);

// ===== 1) Fetch Author =====
$author_sql = "SELECT * FROM authors WHERE id = $author_id";
$author_res = $connection->query($author_sql);

if ($author_res->num_rows == 0) {
    die("Author Not Found");
}
$author = $author_res->fetch_assoc();

// ===== 2) Fetch Books for this Author =====
$books_sql = "SELECT * FROM books WHERE author_id = $author_id";
$books_res = $connection->query($books_sql);

// Build books HTML from database
$books_html = "";

while ($book = $books_res->fetch_assoc()) {
    $books_html .= "
        <a href='detalies.php?id={$book['id']}'>
            <div class='SaleCard'>
                <img src='../{$book['image']}' alt='Book Image'>
                <h2>{$book['title']}</h2>
                <p class='author'>by <span>{$author['name']}</span></p>
                <p class='desc'>{$book['description']}</p>
                <p class='stock'>Price: <span>{$book['price']}$</span></p>
            </div>
        </a>
    ";
}

// ===== 3) Load HTML Template =====
$template = file_get_contents("AothorBook.html");

// ===== 4) Replace Placeholders =====
$output = str_replace(
    ["{{AUTHOR_NAME}}", "{{AUTHOR_IMAGE}}", "{{AUTHOR_BIO}}", "{{BOOKS_SECTION}}"],
    [
        $author['name'],
        "../" . $author['image'],
        "Born on {$author['birthdate']}. Rating: {$author['rating']}. Books written: {$author['books_written']}.",
        $books_html
    ],
    $template
);

// ===== 5) Print Final Page =====
echo $output;
?>
