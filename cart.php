<?php
session_start();
include_once (__DIR__ . "/classes/Db.php");

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    die('You must be logged in to view your cart.');
}

$user_id = $_SESSION['id'];

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT * FROM cart_items WHERE user_id = :user_id AND product_id = :product_id');
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();
    $cart_item = $statement->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        $statement = $conn->prepare('UPDATE cart_items SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id');
    } else {
        $statement = $conn->prepare('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)');
    }
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $statement->execute();
}

// Remove from cart
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['remove_from_cart'];

    $conn = Db::getConnection();
    $statement = $conn->prepare('DELETE FROM cart_items WHERE user_id = :user_id AND product_id = :product_id');
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();
}

// Update cart
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        $conn = Db::getConnection();
        if ($quantity == 0) {
            $statement = $conn->prepare('DELETE FROM cart_items WHERE user_id = :user_id AND product_id = :product_id');
        } else {
            $statement = $conn->prepare('UPDATE cart_items SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id');
            $statement->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        }
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $statement->execute();
    }
}

// View cart
function view_cart($user_id) {
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT ci.product_id, ci.quantity, p.title, p.price FROM cart_items ci LEFT JOIN products p ON ci.product_id = p.product_id WHERE ci.user_id = :user_id');
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statement->execute();
    $cart_items = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cart_items)) {
        echo "<div class='empty-cart'>";
        echo "<p>Your shopping cart is currently empty.</p>";
        echo "<a href='index.php' class='btn-continue'>Continue Browsing</a>";
        echo "</div>";
    } else {
        echo "<form method='post' action='cart.php' class='cart-form'>";
        echo "<table class='cart-table'>";
        echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";
        foreach ($cart_items as $item) {
            echo "<tr>";
            echo "<td>{$item['title']}</td>";
            echo "<td>\${$item['price']}</td>";
            echo "<td><input type='number' name='quantities[{$item['product_id']}]' value='{$item['quantity']}' min='1'></td>";
            echo "<td><button type='submit' name='remove_from_cart' value='{$item['product_id']}' class='btn-remove'>Remove</button></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<button type='submit' name='update_cart' class='btn-update'>Update Cart</button>";
        echo "</form>";
        echo "<div class='cart-buttons'>";
        echo "<a href='checkout.php' class='btn-checkout'>Checkout</a>";
        echo "<a href='index.php' class='btn-checkout'>Continue Browsing</a>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
</head>
<body class="cart_bg">
    <?php include_once("nav.inc.php")?>
    <div class="cart-container">
        <h2>Your Shopping Cart</h2>
        <?php view_cart($user_id); ?>
    </div>
</body>
</html>