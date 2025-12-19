<?php
global $connection;
include "db.php";
session_start();

$user_id = $_SESSION['user_id'] ?? 0;

if ($user_id <= 0) {
    die("You must be logged in.");
}

$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $connection->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">

    <style>
        body{font-family:Poppins,sans-serif;background:#fde7ef;}
        .wrap{width:min(1000px,92%);margin:60px auto;background:#fff;border-radius:20px;padding:35px;box-shadow:0 10px 30px rgba(166,62,100,0.25);}
        h2{color:#a63e64;text-align:center;margin-bottom:25px;font-size:2.2rem;}
        .order-row{display:flex;justify-content:space-between;align-items:center;background:rgba(166,62,100,0.08);padding:18px 20px;border-radius:16px;margin-bottom:14px;}
        .order-row a{text-decoration:none;color:#a63e64;font-weight:700;}
        .badge{padding:8px 16px;border-radius:25px;background:#a63e64;color:#fff;font-weight:600;}
        .small{color:#444;font-weight:600;}
    </style>
</head>
<body>

<?php include "topheader.php"; ?>

<div class="wrap">
    <h2>📦 My Orders</h2>

    <?php if ($result->num_rows == 0): ?>
        <p style="text-align:center;">No orders yet.</p>
    <?php else: ?>
        <?php while($o = $result->fetch_assoc()): ?>
            <div class="order-row">
                <div>
                    <a href="trackOrder.php?id=<?= $o['id'] ?>">Track Order #<?= $o['id'] ?></a>

                    <div class="small"><?= date("F j, Y", strtotime($o['created_at'])) ?></div>
                    <div class="small">Total: $<?= number_format((float)$o['total_price'], 2) ?></div>
                </div>
                <div class="badge"><?= htmlspecialchars($o['status']) ?></div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php include "footer.php"; ?>
</body>
</html>
