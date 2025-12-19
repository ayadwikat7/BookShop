<?php
global $connection;
include "db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// استقبال البيانات
$email    = trim($_POST['Email'] ?? '');
$password = $_POST['Password'] ?? '';

// (اختياري) صفحة نرجع عليها بعد اللوجين
// مثال: auth.php?redirect=my_orders.php
$redirect = $_POST['redirect'] ?? ($_GET['redirect'] ?? 'Profile.php');

// تحقق أساسي
if ($email === '' || $password === '') {
    header("Location: auth.php?error=Please fill all fields");
    exit;
}

// البحث عن المستخدم
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
    header("Location: auth.php?error=Invalid email or password");
    exit;
}

$user = $result->fetch_assoc();

// التحقق من كلمة المرور
if (!password_verify($password, $user['password'])) {
    header("Location: auth.php?error=Invalid email or password");
    exit;
}

// إنشاء Session
$_SESSION['user_id']    = (int)$user['id'];
$_SESSION['user_name']  = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['logged_in']  = true; // (مساعد) بدل isloged بالـ DB

// حماية بسيطة للـ redirect: ما نخليها تروح لرابط خارجي
if (strpos($redirect, "http") === 0) {
    $redirect = "Profile.php";
}

// تحويل
header("Location: " . $redirect);
exit;
