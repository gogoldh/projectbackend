<?php
// Database connection
require_once __DIR__ . '/classes/Db.php'; // Ensure the correct path to Db.php

// Database connection
$db = new Db();
$conn = $db->getConnection();

// Check connection
if (!$conn) {
    die("Connection failed: Unable to establish a database connection.");
}

// Get order_id from URL
$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
if ($order_id === false) {
    die('Invalid order ID');
}

// Fetch order details
$order_query = "SELECT o.user_id, o.total_price, oi.product_id, oi.quantity, oi.price_at_purchase, p.title, pi.image_url
                FROM orders o
                JOIN order_items oi ON o.order_id = oi.order_id
                JOIN products p ON oi.product_id = p.product_id
                JOIN product_images pi ON p.product_id = pi.product_id
                WHERE o.order_id = :order_id AND pi.alt_text LIKE '%side view%'";
$order_statement = $conn->prepare($order_query);
$order_statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$order_statement->execute();
$order_details = $order_statement->fetchAll(PDO::FETCH_ASSOC);

if (empty($order_details)) {
    die('Order not found.');
}

$total_price = $order_details[0]['total_price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body_confirmation">
    <a href="index.php" class="btn-continue" style="margin: 20px;">Continue Shopping</a>
    <div class="container_confirmation">
        <div class="header">
            <h2>Order Confirmation</h2>
            <p>Thank you for your purchase! Your order details are below:</p>
        </div>
        <div class="order-details">
            <?php foreach ($order_details as $item): ?>
                <div class="order-item">
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <div class="order-item-info">
                        <h2><?php echo htmlspecialchars($item['title']); ?></h2>
                        <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
                        <p>Price: $<?php echo htmlspecialchars($item['price_at_purchase']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="total-price">
            Total Price: $<?php echo htmlspecialchars($total_price); ?>
        </div>
    </div>
</body>
</html>