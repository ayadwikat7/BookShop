<?php
global $connection;
session_start();
include "db.php";

/* ===== Admin Protection ===== */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: auth.php");
    exit;
}

$adminName = $_SESSION['admin_name'];

/* ===== Safe Count Function ===== */
function getCount($connection, $sql) {
    $res = $connection->query($sql);
    if ($res === false) return 0;
    $row = $res->fetch_assoc();
    return $row['total'] ?? 0;
}
$booksForSale = $connection->query("
    SELECT id, title, price
    FROM books
    WHERE isSale = 0
    ORDER BY title ASC
");
/* ===== Counters ===== */
$totalUsers      = getCount($connection, "SELECT COUNT(*) AS total FROM users");
$totalBooks      = getCount($connection, "SELECT COUNT(*) AS total FROM books");
$totalAuthors    = getCount($connection, "SELECT COUNT(*) AS total FROM authors");
$totalCategories = getCount($connection, "SELECT COUNT(*) AS total FROM categories");

/* Orders (later) */
$booksSold = 0;
$booksReturned = 0;
$booksReceived = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="cssfolder/headerSecond.css">
    <link rel="stylesheet" href="cssfolder/loginStyle.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/Admin.css">
    <link rel="stylesheet" href="cssfolder/AdminDashBoard.css">
    <link rel="stylesheet" href="cssfolder/DialogStyle.css">

    <script src="Js/DashBordScript.js" defer></script>
</head>

<body>

<?php include 'topheader.php'; ?>

<div class="AdminTotal">

    <!-- ===== LEFT SIDE ===== -->

    <!-- ===== CENTER SECTION ===== -->
    <div class="Contersection">

        <div class="Conter">
            <h3>Number of people visited website</h3>
            <div class="ConterBox">
                <i class="fa-solid fa-people-group"></i>
                <p><?= $totalUsers ?> Users</p>
            </div>
        </div>

        <div class="Conter">
            <h3>Number of books in stock</h3>
            <div class="ConterBox">
                <i class="fa-solid fa-store"></i>
                <p><?= $totalBooks ?> Books</p>
            </div>
        </div>

        <div class="Conter">
            <h3>Number of authors</h3>
            <div class="ConterBox">
                <i class="fa-solid fa-user-pen"></i>
                <p><?= $totalAuthors ?> Authors</p>
            </div>
        </div>

        <div class="Conter">
            <h3>Number of categories</h3>
            <div class="ConterBox">
                <i class="fa-solid fa-layer-group"></i>
                <p><?= $totalCategories ?> Categories</p>
            </div>
        </div>

        <div class="Conter">
            <h3>Number of books sold</h3>
            <div class="ConterBox">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <p><?= $booksSold ?> Books</p>
            </div>
        </div>

        <div class="Conter">
            <h3>Number of returned books</h3>
            <div class="ConterBox">
                <i class="fa-solid fa-xmark"></i>
                <p><?= $booksReturned ?> Books</p>
            </div>
        </div>

        <div class="Conter">
            <h3>Number of books received</h3>
            <div class="ConterBox">
                <i class="fa-solid fa-check"></i>
                <p><?= $booksReceived ?> Books</p>
            </div>
        </div>

        <!-- ===== ACTION BUTTONS ===== -->
        <div class="AllItemsProfile">
            <div class="design">

                <div class="infor">
                    <button onclick="openAddBook()" class="buttopnprofile">
                        <i class="fa-solid fa-plus"></i>Add new Book
                    </button>
                    <button onclick="openRemoveBook()" class="buttopnprofile">
                        <i class="fa-solid fa-plus"></i>Remove Book
                    </button>
                </div>

                <div class="infor">
                    <button onclick="openAddAuthor()" class="buttopnprofile">
                        <i class="fa-solid fa-user-plus"></i>Add New Author
                    </button>
                    <button onclick="openRemoveAuthor()" class="buttopnprofile">
                        <i class="fa-solid fa-user-plus"></i>Remove Author
                    </button>
                </div>

                <div class="infor">
                    <button onclick="openAddCategory()" class="buttopnprofile">
                        <i class="fa-solid fa-layer-group"></i>Add New Category
                    </button>
                    <button onclick="openRemoveCategory()" class="buttopnprofile">
                        <i class="fa-solid fa-folder-minus"></i>Remove Category
                    </button>
                </div>
                <div class="infor">
                    <button onclick="openAddSale()" class="buttopnprofile">
                        <i class="fa-solid fa-tags"></i>Add Sale
                    </button>

                    <button onclick="openRemoveSale()" class="buttopnprofile">
                        <i class="fa-solid fa-tag"></i>Remove Sale
                    </button>
                </div>

                <div class="infor">
                    <button class="buttopnprofile">
                        <i class="fa-solid fa-file-lines"></i>Switch account
                    </button>
                    <button class="buttopnprofile">
                        <i class="fa-solid fa-file-arrow-down"></i>download View as pdf
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>



<!-- ===== DIALOGS (كما هي) ===== -->
<?php include "admin_dialogs.php"; ?>

<?php include "footer.php"; ?>

</body>
</html>
