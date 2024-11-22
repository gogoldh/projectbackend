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

// User ID (this should be retrieved from session or authentication system)
session_start();
if (!isset($_SESSION['id'])) {
    die('You must be logged in to proceed with the checkout.');
}
$user_id = $_SESSION['id'];

// Fetch user balance
$user_query = "SELECT balance FROM user WHERE id = :user_id";
$user_statement = $conn->prepare($user_query);
$user_statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$user_statement->execute();
$user = $user_statement->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User not found.');
}

$user_balance = $user['balance'];

// Fetch cart items
$cart_query = "SELECT ci.product_id, p.title, p.price, ci.quantity 
               FROM cart_items ci 
               JOIN products p ON ci.product_id = p.product_id 
               WHERE ci.user_id = :user_id";
$cart_statement = $conn->prepare($cart_query);
$cart_statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$cart_statement->execute();
$cart_items = $cart_statement->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
$order_items = [];

foreach ($cart_items as $row) {
    $total_price += $row['price'] * $row['quantity'];
    $order_items[] = $row;
}

// Check if user has enough balance
if ($user_balance >= $total_price) {
    // Deduct balance
    $new_balance = $user_balance - $total_price;
    $update_balance_query = "UPDATE user SET balance = :new_balance WHERE id = :user_id";
    $update_balance_statement = $conn->prepare($update_balance_query);
    $update_balance_statement->bindParam(':new_balance', $new_balance, PDO::PARAM_INT);
    $update_balance_statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $update_balance_statement->execute();

    // Create order
    $create_order_query = "INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)";
    $create_order_statement = $conn->prepare($create_order_query);
    $create_order_statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $create_order_statement->bindParam(':total_price', $total_price, PDO::PARAM_INT);
    $create_order_statement->execute();
    $order_id = $conn->lastInsertId();

    // Insert order items
    foreach ($order_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];
        $insert_order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (:order_id, :product_id, :quantity, :price_at_purchase)";
        $insert_order_item_statement = $conn->prepare($insert_order_item_query);
        $insert_order_item_statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $insert_order_item_statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $insert_order_item_statement->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $insert_order_item_statement->bindParam(':price_at_purchase', $price, PDO::PARAM_INT);
        $insert_order_item_statement->execute();
    }

    // Clear cart
    $clear_cart_query = "DELETE FROM cart_items WHERE user_id = :user_id";
    $clear_cart_statement = $conn->prepare($clear_cart_query);
    $clear_cart_statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $clear_cart_statement->execute();

    echo "Order placed successfully!";
    header("Location: order_confirmation.php?order_id=$order_id");
} else {
    echo "Insufficient balance.";
}
?>