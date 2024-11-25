<?php
include_once (__DIR__ . "/classes/Db.php");

function getProductById($id) {
    // Assuming you have a database connection $conn
    $conn = Db::getConnection();
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
if (isset($_GET['products'])) {
    $products = $_GET['products'];
    $productIds = explode(',', $products);

    // Fetch product details from the database based on $productIds
    // Assuming you have a function getProductById($id) that fetches product details

    $productDetails = [];
    foreach ($productIds as $productId) {
        $productDetails[] = getProductById($productId);
    }
} else {
    // Handle the case where no products are selected
    $productDetails = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Products</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<?php  include_once("nav.inc.php")?>
<body>
    <h2>Compare Products</h2>
    <div class="compare-container">
        <?php foreach ($productDetails as $product): ?>
        <div class="compare-card">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['alt_text']); ?>">
            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
            <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
            <p>Top Speed: <?php echo htmlspecialchars($product['top_speed']); ?> km/h</p>
            <p>Weight: <?php echo htmlspecialchars($product['weight']); ?> kg</p>
            <p>Motor Power: <?php echo htmlspecialchars($product['motor_power']); ?> W</p>
            <p>Range per Charge: <?php echo htmlspecialchars($product['range_per_charge']); ?> km</p>
            <p>Wheel Size: <?php echo htmlspecialchars($product['wheel_size']); ?> inch</p>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>