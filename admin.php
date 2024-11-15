<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class ="admin">
<?php include_once("nav.inc.php")?>

    <form action="add_product.php" class="form form--login" method="POST" enctype="multipart/form-data">
        <div>
            <label class="form__title" for="title">Product Title:</label>
            <input class="form__field" type="text" id="title" name="title" required>
        </div>

        <div>
            <label class="form__title" for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required></textarea>
        </div>

        <div>
            <label class="form__title" for="price">Price (€):</label>
            <input class="form__field" type="number" id="price" name="price" step="0.01" required>
        </div>

        <div>
            <label class="form__title" for="stock">Stock Quantity:</label>
            <input class="form__field" type="number" id="stock" name="stock" required>
        </div>

        <div>
            <label class="form__title" for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="monowheels">Monowheels</option>
                <option value="accessories">Accessories</option>
            </select>
        </div>

        <div>
            <label for="image">Product Image:</label>
            <input class="form__field" type="file" id="image" name="image" accept="image/*" required multiple>
        </div>

        <div>
            <button type="submit">Add Product</button>
        </div>
                <!-- Features Information -->
                <h3>Product Features</h3>
        <div>
            <label for="motor_power">Motor Power (W):</label>
            <input type="number" id="motor_power" name="motor_power" required>
        </div>

        <div>
            <label for="top_speed">Top Speed (km/h):</label>
            <input type="number" id="top_speed" name="top_speed" step="0.1" required>
        </div>

        <div>
            <label for="battery_capacity">Battery Capacity (Wh):</label>
            <input type="number" id="battery_capacity" name="battery_capacity" required>
        </div>

        <div>
            <label for="range_per_charge">Range per Charge (km):</label>
            <input type="number" id="range_per_charge" name="range_per_charge" required>
        </div>

        <div>
            <label for="charging_time">Charging Time (hours):</label>
            <input type="number" id="charging_time" name="charging_time" step="0.1" required>
        </div>

    </form>
</body>
</html>
