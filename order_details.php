<?php
global $connection;
include "db.php";
session_start();

$user_id  = $_SESSION['user_id'] ?? 0;
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0) die("You must be logged in.");
if ($order_id <= 0) die("Invalid order ID");

// Fetch order for THIS user only
$sql = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$result = $connection->query($sql);
$order = $result->fetch_assoc();

if (!$order) die("Order not found!");

// Fetch items
$itemsSql = "
    SELECT oi.*, b.title, b.image
    FROM order_items oi
    JOIN books b ON b.id = oi.book_id
    WHERE oi.order_id = $order_id
";
$itemsRes = $connection->query($itemsSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= (int)$order['id'] ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="cssfolder/header.css">
    <link rel="stylesheet" href="cssfolder/footer.css">

    <style>
        body{font-family:Poppins,sans-serif;background:#fde7ef;}
        .order-container{width:min(950px,92%);margin:60px auto;background:#fff;border-radius:20px;padding:40px;box-shadow:0 10px 30px rgba(166,62,100,0.25);}
        .order-title{text-align:center;color:#a63e64;font-size:2.2rem;margin-bottom:25px;}
        .order-info{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;margin-bottom:30px;}
        .info-box{background:rgba(166,62,100,0.08);border-radius:15px;padding:18px;text-align:center;}
        .info-box h4{color:#a63e64;margin-bottom:6px;}
        .info-box p{font-weight:700;color:#333;}
        .status-badge{display:inline-block;padding:10px 22px;border-radius:30px;font-weight:700;background:#a63e64;color:#fff;}
        .items{margin-top:25px;}
        .item{display:flex;gap:15px;align-items:center;background:rgba(166,62,100,0.06);padding:14px 16px;border-radius:16px;margin-bottom:12px;}
        .item img{width:55px;height:75px;object-fit:cover;border-radius:10px;}
        .item h4{margin:0;color:#a63e64;}
        .item p{margin:4px 0;color:#444;font-weight:600;}
        .back-home{display:block;width:fit-content;margin:30px auto 0;padding:12px 28px;border-radius:30px;background:linear-gradient(135deg,#d46282,#a63e64);color:#fff;text-decoration:none;font-weight:600;}
    </style>
</head>
<body>

<?php include "topheader.php"; ?>

<div class="order-container">
    <h2 class="order-title">📦 Order Details</h2>

    <div class="order-info">
        <div class="info-box">
            <h4>Order Number</h4>
            <p>#<?= (int)$order['id'] ?></p>
        </div>

        <div class="info-box">
            <h4>Order Date</h4>
            <p><?= date("F j, Y", strtotime($order['created_at'])) ?></p>
        </div>

        <div class="info-box">
            <h4>Total Price</h4>
            <p>$<?= number_format((float)$order['total_price'], 2) ?></p>
        </div>

        <div class="info-box">
            <h4>Status</h4>
            <p><span class="status-badge"><?= htmlspecialchars($order['status']) ?></span></p>
        </div>
    </div>

    <div class="info-box" style="margin-bottom:20px;">
        <h4>Shipping Info</h4>
        <p><?= htmlspecialchars($order['full_name'] ?? '') ?></p>
        <p><?= htmlspecialchars($order['address'] ?? '') ?></p>
        <p><?= htmlspecialchars($order['phone'] ?? '') ?></p>
    </div>

    <div class="items">
        <h3 style="color:#a63e64;margin-bottom:15px;">📚 Items</h3>

        <?php while($it = $itemsRes->fetch_assoc()): ?>
            <?php
            $img = ltrim($it['image'], './');
            if (strpos($img, 'categories/') === false) {
                $img = preg_replace('#^imge/#', 'imge/categories/', $img, 1);
            }
            ?>
            <div class="item">
                <img src="<?= htmlspecialchars($img) ?>" alt="">
                <div>
                    <h4><?= htmlspecialchars($it['title']) ?></h4>
                    <p>
                        Qty: <?= (int)$it['qty'] ?>
                        | Price: $<?= number_format((float)$it['price'],2) ?>
                        | Subtotal: $<?= number_format((float)$it['subtotal'],2) ?>
                    </p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="my_orders.php" class="back-home">⬅ Back to My Orders</a>
</div>

<?php include "footer.php"; ?>
</body>
</html>
