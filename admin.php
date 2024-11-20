<?php
include_once (__DIR__ . "/classes/Db.php");

$errors = [];

// Fetch available brands
$conn = Db::getConnection();
$statement = $conn->prepare('SELECT id, name FROM brand');
$statement->execute();
$brands = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch available categories
$statement = $conn->prepare('SELECT category_id, name FROM categories');
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate title
    if (empty($_POST['title'])) {
        $errors[] = "Title is required.";
    }

    // Validate price
    if (empty($_POST['price']) || !is_numeric($_POST['price']) || $_POST['price'] <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    // Validate brand_id
    if (empty($_POST['brand_id']) || !is_numeric($_POST['brand_id']) || $_POST['brand_id'] <= 0) {
        $errors[] = "Brand ID must be a positive number.";
    }

    // Validate category_id
    if (empty($_POST['category_id']) || !is_numeric($_POST['category_id']) || $_POST['category_id'] <= 0) {
        $errors[] = "Category ID must be a positive number.";
    }

    // Validate other fields
    $fields = ['motor_power', 'top_speed', 'battery_capacity', 'range_per_charge', 'charging_time', 'wheel_size', 'weight_capacity', 'incline_capability', 'weight', 'pedal_height', 'tire_type', 'suspension', 'ip_rating', 'speaker_system'];
    foreach ($fields as $field) {
        if (empty($_POST[$field]) || $_POST[$field] <= 0) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a positive value.";
        }
    }

    if (empty($errors)) {
        $conn = Db::getConnection();
        
        // Insert product
        $statement = $conn->prepare('INSERT INTO products (title, description, price, brand_id, category_id) VALUES (:title, :description, :price, :brand_id, :category_id)');
        $statement->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
        $statement->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
        $statement->bindParam(':price', $_POST['price'], PDO::PARAM_STR);
        $statement->bindParam(':brand_id', $_POST['brand_id'], PDO::PARAM_INT);
        $statement->bindParam(':category_id', $_POST['category_id'], PDO::PARAM_INT);
        $statement->execute();
        $product_id = $conn->lastInsertId();

        // Insert product images
        $image_urls = $_POST['image_url'];
        $alt_texts = $_POST['alt_text'];
        $statement = $conn->prepare('INSERT INTO product_images (product_id, image_url, alt_text) VALUES (:product_id, :image_url, :alt_text)');
        foreach ($image_urls as $index => $image_url) {
            $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $statement->bindParam(':image_url', $image_url, PDO::PARAM_STR);
            $statement->bindParam(':alt_text', $alt_texts[$index], PDO::PARAM_STR);
            $statement->execute();
        }

        // Insert product specifications
        $statement = $conn->prepare('INSERT INTO specifications (product_id, motor_power, top_speed, battery_capacity, range_per_charge, charging_time, wheel_size, weight_capacity, incline_capability, weight, pedal_height, tire_type, suspension, ip_rating, speaker_system) VALUES (:product_id, :motor_power, :top_speed, :battery_capacity, :range_per_charge, :charging_time, :wheel_size, :weight_capacity, :incline_capability, :weight, :pedal_height, :tire_type, :suspension, :ip_rating, :speaker_system)');
        $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        foreach ($fields as $field) {
            $statement->bindParam(":$field", $_POST[$field], PDO::PARAM_STR);
        }
        $statement->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="admin_body">
<?php include_once("nav.inc.php")?>
    <form method="post" action="" class="admin-form">
        <h2>Add Product</h2>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" required>
        <label for="brand_id">Brand:</label>
        <select id="brand_id" name="brand_id" class="styled-select" required>
            <option value="">Select a brand</option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?php echo htmlspecialchars($brand['id']); ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="category_id">Category:</label>
        <select id="category_id" name="category_id" class="styled-select" required>
            <option value="">Select a category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['category_id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php endforeach; ?>
        </select>

        <h2>Add Product Images</h2>
        <div id="image-fields">
            <div class="image-field">
                <label for="image_url[]">Image URL:</label>
                <input type="text" id="image_url[]" name="image_url[]" required>
                <label for="alt_text[]">Alt Text:</label>
                <input type="text" id="alt_text[]" name="alt_text[]" required>
            </div>
        </div>
        <button type="button" onclick="addImageField()">Add Another Image</button>

        <h2>Add Product Specifications</h2>
        <label for="motor_power">Motor Power:</label>
            <input type="text" id="motor_power" name="motor_power" required>
        <label for="top_speed">Top Speed:</label>
            <input type="text" id="top_speed" name="top_speed" required>
        <label for="battery_capacity">Battery Capacity:</label>
            <input type="text" id="battery_capacity" name="battery_capacity" required>
        <label for="range_per_charge">Range per Charge:</label>
            <input type="text" id="range_per_charge" name="range_per_charge" required>
        <label for="charging_time">Charging Time:</label>
            <input type="text" id="charging_time" name="charging_time" required>
        <label for="wheel_size">Wheel Size:</label>
            <input type="text" id="wheel_size" name="wheel_size" required>
        <label for="weight_capacity">Weight Capacity:</label>
            <input type="text" id="weight_capacity" name="weight_capacity" required>
        <label for="incline_capability">Incline Capability:</label>
            <input type="text" id="incline_capability" name="incline_capability" required>
        <label for="weight">Weight:</label>
            <input type="text" id="weight" name="weight" required>
        <label for="pedal_height">Pedal Height:</label>
            <input type="text" id="pedal_height" name="pedal_height" required>
        <label for="tire_type">Tire Type:</label>
            <input type="text" id="tire_type" name="tire_type" required>
        <label for="suspension">Suspension:</label>
            <input type="text" id="suspension" name="suspension" required>
        <label for="ip_rating">IP Rating:</label>
            <input type="text" id="ip_rating" name="ip_rating" required>
        <label for="speaker_system">Speaker System:</label>
            <input type="text" id="speaker_system" name="speaker_system" required>

        <button type="submit">Add Product</button>
    </form>

    <script>
        function addImageField() {
            const imageFields = document.getElementById('image-fields');
            const newField = document.createElement('div');
            newField.classList.add('image-field');
            newField.innerHTML = `
                <label for="image_url[]">Image URL:</label>
                <input type="text" id="image_url[]" name="image_url[]" required>
                <label for="alt_text[]">Alt Text:</label>
                <input type="text" id="alt_text[]" name="alt_text[]" required>
            `;
            imageFields.appendChild(newField);
        }
    </script>
</body>
</html>