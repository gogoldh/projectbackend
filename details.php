<?php
include_once (__DIR__ . "/classes/Db.php");

// Sanitize and validate the id
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    die('Invalid product ID');
}

// Fetch product details
function fetchProduct($id) {
    $conn = Db::getConnection();
    $statement = $conn->prepare('
        SELECT p.*, b.name AS brand_name
        FROM products p
        LEFT JOIN brand b ON p.brand_id = b.id
        WHERE p.product_id = :id
    ');
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

// Fetch product images
function fetchProductImages($id) {
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT image_url, alt_text FROM product_images WHERE product_id = :id');
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch product specifications
function fetchProductSpecifications($id) {
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT 
        motor_power,
        top_speed,
        battery_capacity,
        range_per_charge,
        charging_time,
        wheel_size,
        weight_capacity,
        incline_capability,
        weight,
        pedal_height,
        tire_type,
        suspension,
        ip_rating,
        speaker_system
    FROM specifications WHERE product_id = :id');
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

$product = fetchProduct($id);
if (!$product) {
    die('Product not found');
}

$images = fetchProductImages($id);
$specifications = fetchProductSpecifications($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details page</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">

    <!-- Script om te switchen tussen main images en thumbnails -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.querySelector('.main-image img');
            const thumbnails = document.querySelectorAll('.thumbnail-images img');
            let currentIndex = 0;

            function updateMainImage(index) {
                mainImage.src = thumbnails[index].src;
                mainImage.alt = thumbnails[index].alt;
            }

            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', function() {
                    currentIndex = index;
                    updateMainImage(currentIndex);
                });
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'ArrowRight') {
                    currentIndex = (currentIndex + 1) % thumbnails.length;
                    updateMainImage(currentIndex);
                } else if (event.key === 'ArrowLeft') {
                    currentIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
                    updateMainImage(currentIndex);
                }
            });
        });
    </script>
</head>
<body class="details">
    <?php include_once("nav.inc.php")?>
    <div class="product-container">
        <!-- images -->
        <div class="product-images zoomable">
            <div class="main-image">
                <img src="<?php echo htmlspecialchars($images[0]['image_url']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
            </div>
            <div class="thumbnail-images">
                <?php foreach ($images as $image): ?>
                    <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($image['alt_text']); ?>">
                <?php endforeach; ?>
            </div>
        </div>

        <!-- info -->
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['brand_name']); ?> <?php echo htmlspecialchars($product['title']); ?></h1>
            <p class="sale">SALE</p>
            <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
            <p class="stock-status">In stock! Ships next business day.</p>
            <p class="shipping-info">Free shipping for black friday</p>

            <!-- buttons -->
            <div class="quantity">
                <button>-</button>
                <input type="number" value="1" min="1">
                <button>+</button>
            </div>
            <button class="add_to_cart">Add to cart</button>
            <button class="buy_now">Buy now</button>

            <!-- specifications -->
            <h2>Specifications</h2>
            <ul class="specifications">
            <?php foreach ($specifications as $key => $value): ?>
                    <?php if ($key != 'product_id'): ?>
                        <li><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo htmlspecialchars($value); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <!-- extra -->
            <!-- <h2>Accessories & Options</h2>
            <div class="accessories">
                <label>
                    <input type="checkbox"> V13 Kickstand - $44.00
                </label>
                <label>
                    <input type="checkbox"> V13 Street Tire Upgrade (Michelin Pilot 2 Tire and Install Labor) - $250.00
                </label>
                <label>
                    <input type="checkbox"> 108V (126V Max) Adjustable Fast Charger - $325.00
                </label>
            </div> -->
        </div>
    </div>
</body>
</html>