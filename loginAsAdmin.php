<?php
global $connection;
session_start();
include "db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    /* 1️⃣ جلب المستخدم من جدول users */
    $stmt = $connection->prepare(
            "SELECT id, name, email, password, role 
         FROM users 
         WHERE email = ? 
         LIMIT 1"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        $error = "Account not found ❌";
    } else {

        $user = $result->fetch_assoc();

        /* 2️⃣ تحقق كلمة المرور */
        if (!password_verify($password, $user['password'])) {
            $error = "Incorrect password ❌";
        }
        /* 3️⃣ تحقق الصلاحية */
        elseif ($user['role'] !== 'admin') {
            $error = "You are not authorized as admin ❌";
        }
        /* 4️⃣ نجاح تسجيل الدخول */
        else {
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            $_SESSION['role']       = 'admin';

            $success = "Admin login successful ✅";

            // تحويل بعد نجاح الدخول (اختياري)
            header("Refresh:1; url=adminDashboard.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="cssfolder/headerSecond.css">
    <link rel="stylesheet" href="cssfolder/loginStyle.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/LoginAsadmin.css">
</head>
<body>

<?php include "topheader.php"; ?>

<div class="Admin">
    <img src="imge/Admin-amico.svg" alt="Admin" class="AdminAvatar">

    <form method="post" class="AdminBox" action="adminDashboard.php">
        <h1>Welcome Again to Your Account</h1>
        <p>Login As Admin</p>

        <input type="email" name="email"
               placeholder="Admin Email" required>

        <input type="password" name="password"
               placeholder="Enter Your Code to Continue ..." required>

        <button type="submit">Login to Your Account</button>

        <?php if ($error): ?>
            <p style="color:#c0392b; margin-top:12px;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <?php if ($success): ?>
            <p style="color:#27ae60; margin-top:12px;">
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>
    </form>
</div>

<?php include "footer.php"; ?>

</body>
</html>
