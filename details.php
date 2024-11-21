<?php
include_once (__DIR__ . "/classes/Db.php");

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    die('Invalid product ID');
}

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

// Fetch product reviews
function fetchProductReviews($id) {
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT r.rating, r.comment_text, r.created_at, u.fname AS user_name
        FROM reviews r
        LEFT JOIN user u ON r.user_id = u.id
        WHERE r.product_id = :id
        ORDER BY r.created_at DESC');
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// Add a review
function addReview($product_id, $user_id, $rating, $comment_text) {
    $conn = Db::getConnection();
    $statement = $conn->prepare('INSERT INTO reviews (product_id, user_id, rating, comment_text, created_at)
        VALUES (:product_id, :user_id, :rating, :comment_text, NOW())');
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statement->bindParam(':rating', $rating, PDO::PARAM_INT);
    $statement->bindParam(':comment_text', $comment_text, PDO::PARAM_STR);
    $statement->execute();
}

$product = fetchProduct($id);
$productImages = fetchProductImages($id);
$productSpecifications = fetchProductSpecifications($id);
$productReviews = fetchProductReviews($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comment_text'])) {
    $user_id = $_SESSION['id']; // Assuming user ID is stored in session
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment_text = filter_input(INPUT_POST, 'comment_text', FILTER_SANITIZE_STRING);
    addReview($id, $user_id, $rating, $comment_text);
    header("Location: details.php?id=$id");
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include_once("nav.inc.php")?>
    <div class="product-details">
        <h1><?php echo htmlspecialchars($product['title']); ?></h1>
        <div class="product-images">
            <?php foreach ($productImages as $image): ?>
                <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($image['alt_text']); ?>">
            <?php endforeach; ?>
        </div>
        <div class="product-specifications">
            <h2>Specifications</h2>
            <ul>
                <?php foreach ($productSpecifications as $key => $value): ?>
                    <li><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo htmlspecialchars($value); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="product-reviews">
            <h2>Reviews</h2>
            <?php foreach ($productReviews as $review): ?>
                <div class="review">
                    <p><strong><?php echo htmlspecialchars($review['user_name']); ?></strong> (<?php echo htmlspecialchars($review['created_at']); ?>)</p>
                    <p>Rating: <?php echo htmlspecialchars($review['rating']); ?>/5</p>
                    <p><?php echo htmlspecialchars($review['comment_text']); ?></p>
                </div>
            <?php endforeach; ?>
            <?php if (isset($_SESSION['id'])): ?>
                <form method="POST" action="details.php?id=<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="rating">Rating:</label>
                        <select id="rating" name="rating" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comment_text">Comment:</label>
                        <textarea id="comment_text" name="comment_text" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Submit Review</button>
                </form>
            <?php else: ?>
                <p>You must be logged in to add a review.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>