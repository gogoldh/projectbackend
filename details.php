<?php
include_once (__DIR__ . "/classes/Db.php");
include_once (__DIR__ . "/classes/Product.php");
session_start(); // Ensure the session is started

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false) {
    die('Invalid product ID');
}

// Fetch product details
// function fetchProduct($id) {
//     $conn = Db::getConnection();
//     $statement = $conn->prepare('
//         SELECT p.*, b.name AS brand_name
//         FROM products p
//         LEFT JOIN brand b ON p.brand_id = b.id
//         WHERE p.product_id = :id
//     ');
//     $statement->bindParam(':id', $id, PDO::PARAM_INT);
//     $statement->execute();
//     return $statement->fetch(PDO::FETCH_ASSOC);
// }

// Fetch product images
// function fetchProductImages($id) {
//     $conn = Db::getConnection();
//     $statement = $conn->prepare('SELECT image_url, alt_text FROM product_images WHERE product_id = :id');
//     $statement->bindParam(':id', $id, PDO::PARAM_INT);
//     $statement->execute();
//     return $statement->fetchAll(PDO::FETCH_ASSOC);
// }

// Fetch product specifications
// function fetchProductSpecifications($id) {
//     $conn = Db::getConnection();
//     $statement = $conn->prepare('SELECT 
//         motor_power,
//         top_speed,
//         battery_capacity,
//         range_per_charge,
//         charging_time,
//         wheel_size,
//         weight_capacity,
//         incline_capability,
//         weight,
//         pedal_height,
//         tire_type,
//         suspension,
//         ip_rating,
//         speaker_system
//     FROM specifications WHERE product_id = :id');
//     $statement->bindParam(':id', $id, PDO::PARAM_INT);
//     $statement->execute();
//     return $statement->fetch(PDO::FETCH_ASSOC);
// }

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

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
    }
}

$images = product::fetchProductImages($id);
$specifications = product::fetchSpecifications($id);
$productReviews = fetchProductReviews($id);
$product = product::fetchProductDetails($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comment_text'])) {
    $user_id = $_SESSION['id']; // Assuming user ID is stored in session
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment_text = filter_input(INPUT_POST, 'comment_text', FILTER_SANITIZE_STRING);
    addReview($id, $user_id, $rating, $comment_text);
    echo json_encode(['status' => 'success']);
    exit;
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['id']; // Assuming user ID is stored in session

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
    echo json_encode(['status' => 'success']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details page</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">

    <script>

        document.addEventListener('DOMContentLoaded', function() {
         //ADD TO CART
            const addToCartButton = document.querySelector('.add_to_cart');
            addToCartButton.addEventListener('click', function() {
                const quantityInput = document.querySelector('.quantity input[type="number"]');
                const quantity = quantityInput.value;
                const product_id = <?php echo $id; ?>;

                const formData = new FormData();
                formData.append('add_to_cart', true);
                formData.append('product_id', product_id);
                formData.append('quantity', quantity);

                fetch('details.php?id=<?php echo $id; ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Product added to cart successfully!');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
        // IMAGE SWITCHING
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

            // COMMENT/REVIEW
            const reviewForm = document.getElementById('reviewForm');
            reviewForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(reviewForm);
                fetch('details.php?id=<?php echo $id; ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Reload reviews section
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const minusButton = document.querySelector('.quantity button:first-of-type');
            const plusButton = document.querySelector('.quantity button:last-of-type');
            const quantityInput = document.querySelector('.quantity input[type="number"]');

            minusButton.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });

            plusButton.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
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

        <!-- DETAILS -->
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['brand_name']); ?> <?php echo htmlspecialchars($product['title']); ?></h1>
            <p class="sale">SALE</p>
            <p class="price">$<?php echo htmlspecialchars($product['price']); ?></p>
            <p class="stock-status">In stock! Ships next business day.</p>
            <p class="shipping-info">Free shipping for black friday</p>

            <!-- BUTTON -->
            <div class="quantity">
                <button type="button">-</button>
                <input type="number" value="1" min="1">
                <button type="button">+</button>
            </div>
            <button class="add_to_cart">Add to cart</button>
            
        
           
    <h2>Specifications</h2>
            <ul class="specifications">
            <?php foreach ($specifications as $key => $value): ?>
                    <?php if ($key != 'product_id'): ?>
                        <li><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?>: <?php echo htmlspecialchars($value); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    
    </div>

    <div class="product-reviews">
        <h2>Reviews</h2>
        <?php foreach ($productReviews as $review): ?>
            <div class="review">
                <p><strong><?php echo htmlspecialchars($review['user_name']); ?></strong> (<?php echo timeAgo($review['created_at']); ?>)</p>
                <div class="star-rating">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <span class="star <?php echo $i < $review['rating'] ? 'filled' : ''; ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>
                <p><?php echo htmlspecialchars($review['comment_text']); ?></p>
            </div>
        <?php endforeach; ?>
        <?php if (isset($_SESSION['id'])): ?>
            <form id="reviewForm" method="POST">
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <div class="star-rating">
                        <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star">&#9733;</label>
                        <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">&#9733;</label>
                        <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">&#9733;</label>
                        <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">&#9733;</label>
                        <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">&#9733;</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="comment_text">Comment:</label>
                    <textarea id="comment_text" name="comment_text" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Submit Review</button>
            </form>
        <?php else: ?>
            <p>You must be logged in to add a review. <a href="login.php">Log in!</a></p>
        <?php endif; ?>
    </div>
    
</body>
</html>