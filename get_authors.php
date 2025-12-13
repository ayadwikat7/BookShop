<?php
global $connection;
header("Content-Type: application/json");

include"db.php";

// جيب جميع الكتّاب أو أول 5
$sql = "SELECT id, name, birthdate, rating, books_written, image 
        FROM authors";

$result = $connection->query($sql);

$authors = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $authors[] = $row;
    }
}

echo json_encode($authors);
