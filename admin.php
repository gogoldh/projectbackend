<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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

// Fetch all products
$statement = $conn->prepare('SELECT p.product_id, p.title, b.name AS brand_name, c.name AS category_name FROM products p LEFT JOIN brand b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.category_id');
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
    $product_id = $_POST['delete_product_id'];
    
    // Delete product images
    $statement = $conn->prepare('DELETE FROM product_images WHERE product_id = :product_id');
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();

    // Delete product specifications
    $statement = $conn->prepare('DELETE FROM specifications WHERE product_id = :product_id');
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();

    // Delete product
    $statement = $conn->prepare('DELETE FROM products WHERE product_id = :product_id');
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();

    // Refresh the page to reflect the changes
    header("Location: admin.php");
    exit;

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product_id'])) {
    $product_id = $_POST['edit_product_id'];
    $fields = ['title', 'description', 'price', 'brand_id', 'category_id'];

    $updateFields = [];
    foreach ($fields as $field) {
        if (!empty($_POST[$field])) {
            $updateFields[$field] = $_POST[$field];
        }
    }

    if (!empty($updateFields)) {
        $setClause = implode(', ', array_map(function($field) {
            return "$field = :$field";
        }, array_keys($updateFields)));

        $statement = $conn->prepare("UPDATE products SET $setClause WHERE product_id = :product_id");
        foreach ($updateFields as $field => $value) {
            $statement->bindValue(":$field", $value);
        }
        $statement->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $statement->execute();
    }

    // Update product images
    if (!empty($_POST['image_url']) && !empty($_POST['alt_text'])) {
        $statement = $conn->prepare('DELETE FROM product_images WHERE product_id = :product_id');
        $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $statement->execute();

        $image_urls = $_POST['image_url'];
        $alt_texts = $_POST['alt_text'];
        $statement = $conn->prepare('INSERT INTO product_images (product_id, image_url, alt_text) VALUES (:product_id, :image_url, :alt_text)');
        foreach ($image_urls as $index => $image_url) {
            $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $statement->bindParam(':image_url', $image_url, PDO::PARAM_STR);
            $statement->bindParam(':alt_text', $alt_texts[$index], PDO::PARAM_STR);
            $statement->execute();
        }
    }

    // Update product specifications
    $specFields = ['motor_power', 'top_speed', 'battery_capacity', 'range_per_charge', 'charging_time', 'wheel_size', 'weight_capacity', 'incline_capability', 'weight', 'pedal_height', 'tire_type', 'suspension', 'ip_rating', 'speaker_system'];
    $specUpdateFields = [];
    foreach ($specFields as $field) {
        if (!empty($_POST[$field])) {
            $specUpdateFields[$field] = $_POST[$field];
        }
    }

    if (!empty($specUpdateFields)) {
        $setClause = implode(', ', array_map(function($field) {
            return "$field = :$field";
        }, array_keys($specUpdateFields)));

        $statement = $conn->prepare("UPDATE specifications SET $setClause WHERE product_id = :product_id");
        foreach ($specUpdateFields as $field => $value) {
            $statement->bindValue(":$field", $value);
        }
        $statement->bindValue(':product_id', $product_id, PDO::PARAM_INT);
        $statement->execute();
    }

    // Return a JSON response
    echo json_encode(['success' => true]);
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">

    

</head>
<body class="admin_body">
<?php include_once("nav.inc.php")?>

<?php
// Fetch all products
$statement = $conn->prepare('SELECT p.product_id, p.title, p.description, p.price, p.brand_id, p.category_id, b.name AS brand_name, c.name AS category_name FROM products p LEFT JOIN brand b ON p.brand_id = b.id LEFT JOIN categories c ON p.category_id = c.category_id');
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

$formState = isset($_GET['form_state']) ? $_GET['form_state'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product_id'])) {
    $product_id = $_POST['delete_product_id'];
    
    // Delete product images
    $statement = $conn->prepare('DELETE FROM product_images WHERE product_id = :product_id');
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();

    // Delete product specifications
    $statement = $conn->prepare('DELETE FROM specifications WHERE product_id = :product_id');
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();

    // Delete product
    $statement = $conn->prepare('DELETE FROM products WHERE product_id = :product_id');
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();

    // Return a JSON response
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product_id'])) {
    $product_id = $_POST['edit_product_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];

    // Update product details
    $statement = $conn->prepare('UPDATE products SET title = :title, description = :description, price = :price, brand_id = :brand_id, category_id = :category_id WHERE product_id = :product_id');
    $statement->bindParam(':title', $title, PDO::PARAM_STR);
    $statement->bindParam(':description', $description, PDO::PARAM_STR);
    $statement->bindParam(':price', $price, PDO::PARAM_STR);
    $statement->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);
    $statement->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $statement->execute();

    // Return a JSON response
    echo json_encode(['success' => true]);
    exit;
}
?>

    <button class="toggle-button" onclick="toggleForm('add-product-form')">Add Product</button>
    <form id="add-product-form" method="post" action="" class="admin-form">
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

    <button class="toggle-button" onclick="toggleForm('delete-product-form')">Delete Product</button>
    <form id="delete-product-form" method="post" action="" class="admin-form" style="display: none;">
        <h2>Delete Product</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($products as $product): ?>
        <tr data-product-id="<?php echo htmlspecialchars($product['product_id']); ?>">
            <td class="product-title"><?php echo htmlspecialchars($product['title']); ?></td>
            <td class="product-brand"><?php echo htmlspecialchars($product['brand_name']); ?></td>
            <td class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></td>
            <td>
                <input type="hidden" name="delete_product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                <button type="button" class="btn btn--danger" onclick="deleteProduct(<?php echo htmlspecialchars($product['product_id']); ?>)">Delete</button>
                <button type="button" class="btn btn--primary" onclick="editProduct(<?php echo htmlspecialchars($product['product_id']); ?>)">Edit</button>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    </form>

    <form id="edit-product-form" method="post" action="" class="admin-form" style="display: none;">
    <h2>Edit Product</h2>
    <input type="hidden" id="edit_product_id" name="edit_product_id">
    <label for="edit_title">Title:</label>
    <input type="text" id="edit_title" name="title">
    <label for="edit_description">Description:</label>
    <textarea id="edit_description" name="description"></textarea>
    <label for="edit_price">Price:</label>
    <input type="text" id="edit_price" name="price">
    <label for="edit_brand_id">Brand:</label>
    <select id="edit_brand_id" name="brand_id" class="styled-select">
        <option value="">Select a brand</option>
        <?php foreach ($brands as $brand): ?>
            <option value="<?php echo htmlspecialchars($brand['id']); ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <label for="edit_category_id">Category:</label>
    <select id="edit_category_id" name="category_id" class="styled-select">
        <option value="">Select a category</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?php echo htmlspecialchars($category['category_id']); ?>"><?php echo htmlspecialchars($category['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <h2>Edit Product Specifications</h2>
    <label for="edit_motor_power">Motor Power:</label>
    <input type="text" id="edit_motor_power" name="motor_power">
    <label for="edit_top_speed">Top Speed:</label>
    <input type="text" id="edit_top_speed" name="top_speed">
    <label for="edit_battery_capacity">Battery Capacity:</label>
    <input type="text" id="edit_battery_capacity" name="battery_capacity">
    <label for="edit_range_per_charge">Range per Charge:</label>
    <input type="text" id="edit_range_per_charge" name="range_per_charge">
    <label for="edit_charging_time">Charging Time:</label>
    <input type="text" id="edit_charging_time" name="charging_time">
    <label for="edit_wheel_size">Wheel Size:</label>
    <input type="text" id="edit_wheel_size" name="wheel_size">
    <label for="edit_weight_capacity">Weight Capacity:</label>
    <input type="text" id="edit_weight_capacity" name="weight_capacity">
    <label for="edit_incline_capability">Incline Capability:</label>
    <input type="text" id="edit_incline_capability" name="incline_capability">
    <label for="edit_weight">Weight:</label>
    <input type="text" id="edit_weight" name="weight">
    <label for="edit_pedal_height">Pedal Height:</label>
    <input type="text" id="edit_pedal_height" name="pedal_height">
    <label for="edit_tire_type">Tire Type:</label>
    <input type="text" id="edit_tire_type" name="tire_type">
    <label for="edit_suspension">Suspension:</label>
    <input type="text" id="edit_suspension" name="suspension">
    <label for="edit_ip_rating">IP Rating:</label>
    <input type="text" id="edit_ip_rating" name="ip_rating">
    <label for="edit_speaker_system">Speaker System:</label>
    <input type="text" id="edit_speaker_system" name="speaker_system">
    <h2>Edit Product Images</h2>
    <div id="edit-image-fields"></div>
    <button type="submit">Update Product</button>
</form>
    <script>
        function toggleForm(formId) {
            const form = document.getElementById(formId);
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block";
            } else {
                form.style.display = "none";
            }
        }

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
        function editProduct(productId) {
    const product = <?php echo json_encode($products); ?>.find(p => p.product_id == productId);
    if (product) {
        document.getElementById('edit_product_id').value = product.product_id;
        document.getElementById('edit_title').value = product.title;
        document.getElementById('edit_description').value = product.description;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_brand_id').value = product.brand_id;
        document.getElementById('edit_category_id').value = product.category_id;

        // Fetch product images
        fetch('ajax/get_product_images.php?product_id=' + productId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(images => {
                const imageFields = document.getElementById('edit-image-fields');
                imageFields.innerHTML = '';
                images.forEach(image => {
                    const imageField = document.createElement('div');
                    imageField.classList.add('image-field');
                    imageField.innerHTML = `
                        <label for="edit_image_url_${image.image_id}">Image URL:</label>
                        <input type="text" id="edit_image_url_${image.image_id}" name="image_url[]" value="${image.image_url}" class="image-url">
                        <label for="edit_alt_text_${image.image_id}">Alt Text:</label>
                        <input type="text" id="edit_alt_text_${image.image_id}" name="alt_text[]" value="${image.alt_text}" class="alt-text">
                        <button type="button" class="btn-delete" onclick="deleteImage(${image.image_id})">Delete</button>
                    `;
                    imageFields.appendChild(imageField);
                });
                const addImageField = document.createElement('div');
                addImageField.classList.add('image-field');
                addImageField.innerHTML = `
                    <label for="new_image_url">New Image URL:</label>
                    <input type="text" id="new_image_url" name="new_image_url[]" class="image-url">
                    <label for="new_alt_text">New Alt Text:</label>
                    <input type="text" id="new_alt_text" name="new_alt_text[]" class="alt-text">
                    <button type="button" class="btn-add" onclick="addNewImageField()">Add Image</button>
                `;
                imageFields.appendChild(addImageField);
            })
            .catch(error => console.error('Error fetching images:', error));

        // Fetch product specifications
        fetch('ajax/get_product_specifications.php?product_id=' + productId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(specs => {
                document.getElementById('edit_motor_power').value = specs.motor_power || '';
                document.getElementById('edit_top_speed').value = specs.top_speed || '';
                document.getElementById('edit_battery_capacity').value = specs.battery_capacity || '';
                document.getElementById('edit_range_per_charge').value = specs.range_per_charge || '';
                document.getElementById('edit_charging_time').value = specs.charging_time || '';
                document.getElementById('edit_wheel_size').value = specs.wheel_size || '';
                document.getElementById('edit_weight_capacity').value = specs.weight_capacity || '';
                document.getElementById('edit_incline_capability').value = specs.incline_capability || '';
                document.getElementById('edit_weight').value = specs.weight || '';
                document.getElementById('edit_pedal_height').value = specs.pedal_height || '';
                document.getElementById('edit_tire_type').value = specs.tire_type || '';
                document.getElementById('edit_suspension').value = specs.suspension || '';
                document.getElementById('edit_ip_rating').value = specs.ip_rating || '';
                document.getElementById('edit_speaker_system').value = specs.speaker_system || '';
            })
            .catch(error => console.error('Error fetching specifications:', error));

        toggleForm('edit-product-form');
    }
}

function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch('ajax/delete_product_image.php?image_id=' + imageId, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                document.getElementById('edit_image_url_' + imageId).parentElement.remove();
            } else {
                alert('Failed to delete image.');
            }
        })
        .catch(error => console.error('Error deleting image:', error));
    }
}

function addNewImageField() {
    const newImageField = document.createElement('div');
    newImageField.classList.add('image-field');
    newImageField.innerHTML = `
        <label for="new_image_url">New Image URL:</label>
        <input type="text" id="new_image_url" name="new_image_url[]" class="image-url">
        <label for="new_alt_text">New Alt Text:</label>
        <input type="text" id="new_alt_text" name="new_alt_text[]" class="alt-text">
    `;
    document.getElementById('edit-image-fields').appendChild(newImageField);
}

document.getElementById('edit-product-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    const productId = formData.get('edit_product_id');

    // Remove empty fields from formData
    for (let [key, value] of formData.entries()) {
        if (!value) {
            formData.delete(key);
        }
    }

    fetch('admin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Update the product row in the table without reloading the page
            const productRow = document.querySelector(`tr[data-product-id="${productId}"]`);
            if (productRow) {
                productRow.querySelector('.product-title').textContent = formData.get('title') || productRow.querySelector('.product-title').textContent;
                productRow.querySelector('.product-brand').textContent = formData.get('brand_id') || productRow.querySelector('.product-brand').textContent;
                productRow.querySelector('.product-category').textContent = formData.get('category_id') || productRow.querySelector('.product-category').textContent;
            }
            alert('Product updated successfully.');
        } else {
            alert('Failed to update product.');
        }
    })
    .catch(error => console.error('Error updating product:', error));
});
    </script>
</body>
</html>