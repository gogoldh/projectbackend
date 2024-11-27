<?php
include_once (__DIR__ . "/classes/Db.php");

function getProductById($id) {
    $conn = Db::getConnection();
    $stmt = $conn->prepare("
        SELECT p.title, p.price, s.*, pi.image_url, pi.alt_text, b.name
        FROM products p
        LEFT JOIN specifications s ON p.product_id = s.product_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
        LEFT JOIN brand b ON p.brand_id = b.id
        WHERE p.product_id = :id AND pi.alt_text LIKE '%side view%'
    ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

$productDetails = [];

if (isset($_GET['product1']) && isset($_GET['product2'])) {
    $product1 = $_GET['product1'];
    $product2 = $_GET['product2'];

    // Fetch product details from the database based on product IDs
    $productDetails[] = getProductById($product1);
    $productDetails[] = getProductById($product2);
} else {
    // Handle the case where no products are selected
    echo "No products selected for comparison.";
    exit;
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
<?php include_once("nav.inc.php") ?>
<body>
    <h2>Compare Products</h2>
    <div class="compare-container">
        <?php foreach ($productDetails as $product): ?>
        <div class="compare-card">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['alt_text']); ?>">
            <h2><?php echo htmlspecialchars($product['name']); ?>  <?php echo htmlspecialchars($product['title']); ?></h2>
            <p> <strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
            <p> <strong>Top Speed: </strong><?php echo htmlspecialchars($product['top_speed']); ?> km/h</p>
            <p> <strong>Weight: </strong><?php echo htmlspecialchars($product['weight']); ?> kg</p>
            <p> <strong>Motor Power: </strong><?php echo htmlspecialchars($product['motor_power']); ?> W</p>
            <p> <strong>Battery Capacity: </strong><?php echo htmlspecialchars($product['battery_capacity']); ?> Ah</p>
            <p> <strong>Range per Charge: </strong><?php echo htmlspecialchars($product['range_per_charge']); ?> km</p>
            <p> <strong>Wheel Size: </strong><?php echo htmlspecialchars($product['wheel_size']); ?> inch</p>
            <p> <strong>Suspension: </strong><?php echo htmlspecialchars($product['suspension']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>