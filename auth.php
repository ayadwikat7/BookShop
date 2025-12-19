<?php
$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BookShop | Authentication</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cssfolder/auth.css">
    <link rel="stylesheet" href="cssfolder/headerSecond.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
</head>
<body>

<?php include "topheader.php"; ?>

<div class="auth-wrapper">
    <div class="auth-container" id="authContainer">

        <!-- 🔴 BACKGROUND SIDE -->
        <div class="bg-side"></div>

        <!-- CONTENT SIDE -->
        <div class="content-side">
            <h1 class="side-title login-text">Welcome back 📚</h1>
            <h1 class="side-title register-text">Join BookShop ✨</h1>

            <p class="side-desc login-text">
                Thousands of books are waiting for you.<br>
                Continue your reading journey today.
            </p>

            <p class="side-desc register-text">
                Create your free account and explore<br>
                hand-picked books just for you.
            </p>
        </div>

        <!-- 🧊 FORM BOX -->
        <div class="form-box">

            <!-- ================= LOGIN FORM ================= -->
            <form class="form login-form" action="Login.php" method="post">
                <h2>Welcome Back</h2>

                <input type="email" name="Email" placeholder="Email address" required>
                <input type="password" name="Password" placeholder="Password" required>

                <button type="submit">Login</button>

                <p class="switch-text">
                    Or
                    <a href="loginAsAdmin.php" class="admin-link">Login as Admin</a>
                </p>

                <p class="switch-text">
                    Don’t have an account?
                    <strong id="switchToRegister">Create one</strong>
                </p>
            </form>

            <!-- ================= REGISTER FORM ================= -->
            <form class="form register-form" action="UserRegister.php" method="post">
                <h2>Create Account</h2>

                <input type="text" name="Name" placeholder="Full name" required>
                <input type="email" name="Email" placeholder="Email address" required>

                <input type="password" name="Password" placeholder="Password" required>
                <input type="password" name="ConifrmedPassword" placeholder="Confirm password" required>

                <input type="text" name="Phone" placeholder="Phone number">
                <input type="text" name="Location" placeholder="Location (City / Country)">

                <select name="role" required class="role-select">
                    <option value="" disabled selected>Select account type</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>

                <button type="submit">Register</button>

                <p class="switch-text">
                    Already have an account?
                    <strong id="switchToLogin">Login</strong>
                </p>
            </form>

        </div>
    </div>
</div>

<!-- ================= POPUP MESSAGE ================= -->
<div id="popupBox" class="popup">
    <div class="popup-content">
        <span id="closePopup">&times;</span>
        <p id="popupMessage"></p>
    </div>
</div>

<?php include "footer.php"; ?>

<script src="Js/auth.js"></script>
<script src="Js/popMessage.js"></script>

<?php if ($error): ?>
    <script> showError("<?= htmlspecialchars($error) ?>"); </script>
<?php endif; ?>

<?php if ($success): ?>
    <script> showSuccess("<?= htmlspecialchars($success) ?>"); </script>
<?php endif; ?>

</body>
</html>
