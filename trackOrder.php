<?php
global $connection;
include "db.php";
session_start();

$user_id  = $_SESSION['user_id'] ?? 0;
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id <= 0) die("You Shoud Be Logged In");
if ($order_id <= 0) die("order ID is required");

$orderSql = "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id";
$orderRes = $connection->query($orderSql);
$order = $orderRes ? $orderRes->fetch_assoc() : null;

if (!$order) die("Order Not Found");

$itemsSql = "
    SELECT oi.*, b.title, b.image
    FROM order_items oi
    JOIN books b ON b.id = oi.book_id
    WHERE oi.order_id = $order_id
";
$itemsRes = $connection->query($itemsSql);

$itemsCount = 0;
if ($itemsRes) {
    $itemsCount = $itemsRes->num_rows;
}


$status = strtolower(trim($order['status'] ?? 'pending'));

function stepActive($current, $step) {
    $map = [
            'pending' => 1,
            'placed'  => 1,
            'packed'  => 2,
            'shipped' => 3,
            'in transit' => 3,
            'out for delivery' => 4,
            'delivered' => 5
    ];
    $c = $map[$current] ?? 1;
    return ($c >= $step) ? "active" : "";
}

$orderDate = date("M jS, Y", strtotime($order['created_at']));
$deliveredDate = ($status === 'delivered') ? $orderDate : "—"; // إذا عندك delivered_at غيريها
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order State</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="cssfolder/profileStyle.css">
    <link rel="stylesheet" href="cssfolder/footer.css">
    <link rel="stylesheet" href="cssfolder/trackOrder.css">
    <link rel="stylesheet" href="cssfolder/headerSecond.css">
</head>
<body>

<?php include "topheader.php"; ?>

<div class="top-bar">
    <p>📚 You can easily track Your Order</p>
    <h2>Orders Status</h2>
</div>

<div class="BoxTracking">

    <div class="orderTtile">
        <div class="orderData">
            <h2>Order Number</h2>
            <h3>#<?= (int)$order['id'] ?></h3>
        </div>

        <div class="orderData">
            <h2>Order Placed</h2>
            <h3><?= $orderDate ?></h3>
        </div>

        <div class="orderData">
            <h2>Order Delivered</h2>
            <h3><?= $deliveredDate ?></h3>
        </div>

        <div class="orderData">
            <h2>No Of Item</h2>
            <h3><?= (int)$itemsCount ?></h3>
        </div>

        <div class="orderData">
            <h2>Status</h2>
            <h3><?= htmlspecialchars($order['status']) ?></h3>
        </div>
    </div>

    <div class="line"></div>

    <h3>Order Tracking</h3>

    <!-- ====== التتبع ====== -->
    <div class="orderDateBox">

        <div class="TrackOrderData <?= stepActive($status,1) ?>">
            <i class="fa-solid fa-cart-arrow-down"></i>
            <h2>Order Placed</h2>
            <h3><?= $orderDate ?></h3>
        </div>

        <div class="TrackOrderData <?= stepActive($status,2) ?>">
            <i class="fa-solid fa-boxes-stacked"></i>
            <h2>Order Packed</h2>
            <h3><?= ($status === 'pending' || $status === 'placed') ? "—" : $orderDate ?></h3>
        </div>

        <div class="TrackOrderData <?= stepActive($status,3) ?>">
            <i class="fa-solid fa-truck-fast"></i>
            <h2>In Transit</h2>
            <h3><?= stepActive($status,3) ? $orderDate : "—" ?></h3>
        </div>

        <div class="TrackOrderData <?= stepActive($status,4) ?>">
            <i class="fa-solid fa-cart-flatbed"></i>
            <h2>Out for Delivery</h2>
            <h3><?= stepActive($status,4) ? $orderDate : "—" ?></h3>
        </div>

        <div class="TrackOrderData <?= stepActive($status,5) ?>">
            <i class="fa-solid fa-house-flag"></i>
            <h2>Delivered</h2>
            <h3><?= ($status === 'delivered') ? $orderDate : "—" ?></h3>
        </div>

    </div>

    <div class="line"></div>

    <h3>Orders & Items in Order</h3>

    <?php if ($itemsRes && $itemsRes->num_rows > 0): ?>
        <?php while($it = $itemsRes->fetch_assoc()): ?>
            <?php
            $img = ltrim($it['image'], './');
            if (strpos($img, 'categories/') === false) {
                $img = preg_replace('#^imge/#', 'imge/categories/', $img, 1);
            }
            ?>
            <div class="Orders">
                <div class="OrderItem">
                    <h2>Products</h2>
                    <img src="<?= htmlspecialchars($img) ?>" alt="img">
                </div>

                <div class="letfItem">
                    <div class="OrderItem">
                        <h2>Quantity</h2>
                        <p><?= (int)$it['qty'] ?></p>
                    </div>

                    <div class="OrderItem">
                        <h2>Total Price</h2>
                        <p>$<?= number_format((float)$it['subtotal'], 2) ?></p>
                    </div>
                </div>
            </div>

            <div class="line"></div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">No items found for this order.</p>
    <?php endif; ?>

</div>

<?php include "footer.php"; ?>

</body>
</html>
