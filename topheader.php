<?php
// نحاول نفتح session بدون ما نكسر الصفحة لو كان في Output قبل
if (session_status() === PHP_SESSION_NONE) {
    if (!headers_sent()) {
        session_start();
    }
}

// فحص هل المستخدم مسجل دخول
$isLogged = (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0);
?>

<header class="header">
    <div class="main">

        <header class="header">

            <div class="top-bar">
                <p>📚 Free shipping on orders over $50 | Discover your next great read today!</p>
            </div>

            <div class="nav-container">
                <div class="logo">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <h1>BookShop</h1>
                </div>

                <div class="user-icons">
                    <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
                    <a href="Profile.php"><i class="fa-solid fa-user"></i></a>
                </div>
            </div>

            <nav class="menu">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="AllBooks.php">Books</a></li>
                    <li><a href="Categories.php">Categories</a></li>
                    <li><a href="Author.php">Authors</a></li>
                    <li><a href="ContactUs.html">Contact Us</a></li>

                    <!-- My Order: إذا مش Logged يطلع Dialog -->
                    <li><a href="#" onclick="return goMyOrders();">My Order</a></li>
                </ul>
            </nav>

        </header>
    </div>
</header>

<script>
    // بنمرر قيمة اللوجين من PHP لJS
    const IS_LOGGED = <?= $isLogged ? 'true' : 'false' ?>;

    function goMyOrders() {
        if (!IS_LOGGED) {
            // لو popMessage.js موجود بيطلع Dialog لطيف، غير هيك alert عادي
            if (typeof showError === "function") {
                showError("You must be logged in to your account");
            } else {
                alert("You must be logged in to your account");
            }
            return false;
        }

        window.location.href = "my_orders.php";
        return false;
    }
</script>

<!-- ================= POPUP MESSAGE (مرة واحدة فقط بالمشروع) ================= -->
<div id="popupBox" class="popup" style="display:none;">
    <div class="popup-content">
        <span id="closePopup">&times;</span>
        <p id="popupMessage"></p>
    </div>
</div>

<!-- لازم يكون موجود عشان showError / showSuccess -->
<script src="Js/popMessage.js"></script>
