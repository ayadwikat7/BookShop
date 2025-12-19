<?php
global $connection;
session_start();
include "../db.php";

/* ===== Admin Check ===== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit;
}

/* ===== Remove Author by Name ===== */
if (isset($_POST['remove_author'], $_POST['author_name'])) {

    $name = trim($_POST['author_name']);

    $stmt = $connection->prepare(
        "DELETE FROM authors WHERE name = ?"
    );
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->close();
}

header("Location: ../AdminDashboard.php");
exit;
