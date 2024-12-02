<?php
include_once (__DIR__ . "/classes/Db.php");
include_once (__DIR__ . "/classes/Product.php");
include_once (__DIR__ . "/classes/Brand.php");


// function fetchProducts($brand = null, $search = null, $page = 1, $limit = 10, $filters = []){
//     $conn = Db::getConnection();
//     $offset = ($page - 1) * $limit;
//     $sql = '   
//         SELECT p.product_id, p.title, p.price, pi.image_url, pi.alt_text, b.name AS brand_name, s.top_speed, s.weight, s.motor_power, s.range_per_charge, s.wheel_size
//         FROM products p
//         LEFT JOIN product_images pi ON p.product_id = pi.product_id
//         LEFT JOIN brand b ON p.brand_id = b.id
//         LEFT JOIN specifications s ON p.product_id = s.product_id
//         WHERE pi.alt_text LIKE "%side view%"';
    
//     if ($brand) {
//         $sql .= ' AND b.name = :brand';
//     }
//     if ($search) {
//         $sql .= ' AND p.title LIKE :search';
//     }
//     if (!empty($filters)) {
//         if (isset($filters['top_speed'])) {
//             $sql .= ' AND s.top_speed >= :top_speed';
//         }
//         if (isset($filters['weight'])) {
//             $sql .= ' AND s.weight <= :weight';
//         }
//         if (isset($filters['motor_power'])) {
//             $sql .= ' AND s.motor_power >= :motor_power';
//         }
//         if (isset($filters['range_per_charge'])) {
//             $sql .= ' AND s.range_per_charge >= :range_per_charge';
//         }
//         if (isset($filters['wheel_size'])) {
//             $sql .= ' AND s.wheel_size >= :wheel_size';
//         }
//     }

//     $sql .= ' LIMIT :limit OFFSET :offset';

//     $statement = $conn->prepare($sql);
    
//     if ($brand) {
//         $statement->bindParam(':brand', $brand, PDO::PARAM_STR);
//     }
//     if ($search) {
//         $search = "%$search%";
//         $statement->bindParam(':search', $search, PDO::PARAM_STR);
//     }
//     if (!empty($filters)) {
//         if (isset($filters['top_speed'])) {
//             $statement->bindParam(':top_speed', $filters['top_speed'], PDO::PARAM_INT);
//         }
//         if (isset($filters['weight'])) {
//             $statement->bindParam(':weight', $filters['weight'], PDO::PARAM_INT);
//         }
//         if (isset($filters['motor_power'])) {
//             $statement->bindParam(':motor_power', $filters['motor_power'], PDO::PARAM_INT);
//         }
//         if (isset($filters['range_per_charge'])) {
//             $statement->bindParam(':range_per_charge', $filters['range_per_charge'], PDO::PARAM_INT);
//         }
//         if (isset($filters['wheel_size'])) {
//             $statement->bindParam(':wheel_size', $filters['wheel_size'], PDO::PARAM_INT);
//         }
//     }
//     $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
//     $statement->bindParam(':offset', $offset, PDO::PARAM_INT);

//     $statement->execute();
//     return $statement->fetchAll(PDO::FETCH_ASSOC);
// }


// function fetchBrands() {
//     $conn = Db::getConnection();
//     $statement = $conn->prepare('SELECT id, name FROM brand');
//     $statement->execute();
//     return $statement->fetchAll(PDO::FETCH_ASSOC);
// }

$brand = filter_input(INPUT_GET, 'brand', FILTER_SANITIZE_STRING); // Get brand from URL
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING); // Get search from URL
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1; // Get current page from URL
$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT) ?: 10; // Get limit from URL



$filters = [
    'top_speed' => filter_input(INPUT_GET, 'top_speed', FILTER_VALIDATE_INT),
    'weight' => filter_input(INPUT_GET, 'weight', FILTER_VALIDATE_INT),
    'motor_power' => filter_input(INPUT_GET, 'motor_power', FILTER_VALIDATE_INT),
    'range_per_charge' => filter_input(INPUT_GET, 'range_per_charge', FILTER_VALIDATE_INT),
    'wheel_size' => filter_input(INPUT_GET, 'wheel_size', FILTER_VALIDATE_INT)
];



$products = Product::fetchProducts($brand, $search, $page, $limit, $filters); // Pass brand, search, page, limit, and filters to fetchProducts function
$brands = Brand::fetchBrands(); // Fetch brands from the database

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUCSHOP</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css' rel='stylesheet' />
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src="script.js"></script>
    
    <style>
    <?php foreach ($products as $product): ?>
    .card-<?php echo htmlspecialchars($product['product_id']); ?>:after {
        content: "<?php echo htmlspecialchars($product['brand_name']); ?>";
        position: absolute;
        padding: 5px;
        border-radius: 5px;
        font-size: 12px;
        text-align: center;
        transform-origin: center center;
    }
    <?php endforeach; ?>
    </style>
</head>
<body>
    <?php include_once("nav.inc.php")?>
    <main>
    <button id="filterButton" class="btn-filter">Filter</button>
<div id="filterModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Filter Options</h2>
        <form id="filterForm">
            <div class="form-group">
                <label for="brand">Brand:</label>
                <select id="brand" name="brand">
                    <option value="">Select a brand</option>
                    <?php foreach ($brands as $brand): ?>
                        <option value="<?php echo htmlspecialchars($brand['name']); ?>"><?php echo htmlspecialchars($brand['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Max Price:</label>
                <input type="range" id="price" name="price" min="0" max="5000" step="100" value="5000" oninput="this.nextElementSibling.value = this.value">
                <output>5000</output>
            </div>
            <div class="form-group">
                <label for="top_speed">Min Top Speed (km/h):</label>
                <input type="range" id="top_speed" name="top_speed" min="0" max="100" step="5" value="0" oninput="this.nextElementSibling.value = this.value">
                <output>0</output>
            </div>
            <div class="form-group">
                <label for="weight">Max Weight (kg):</label>
                <input type="range" id="weight" name="weight" min="0" max="100" step="5" value="100" oninput="this.nextElementSibling.value = this.value">
                <output>100</output>
            </div>
            <div class="form-group">
                <label for="motor_power">Min Motor Power (W):</label>
                <input type="range" id="motor_power" name="motor_power" min="0" max="5000" step="100" value="0" oninput="this.nextElementSibling.value = this.value">
                <output>0</output>
            </div>
            <div class="form-group">
                <label for="range_per_charge">Min Range per Charge (km):</label>
                <input type="range" id="range_per_charge" name="range_per_charge" min="0" max="200" step="10" value="0" oninput="this.nextElementSibling.value = this.value">
                <output>0</output>
            </div>
            <div class="form-group">
                <label for="wheel_size">Min Wheel Size (inch):</label>
                <input type="range" id="wheel_size" name="wheel_size" min="0" max="30" step="1" value="0" oninput="this.nextElementSibling.value = this.value">
                <output>0</output>
            </div>
            <div class="form-group-buttons">
                <button type="submit" class="btn-apply">Apply Filters</button>
                <button type="button" id="resetFilters" class="btn-reset">Reset Filters</button>
            </div>
        </form>
    </div>
</div>

        <div class="container">
            <?php foreach ($products as $product): ?>
                <div class="card card-<?php echo htmlspecialchars($product['product_id']); ?>" data-product-id="<?php echo htmlspecialchars($product['product_id']); ?>" data-product-title="<?php echo htmlspecialchars($product['title']); ?>">
    <div class="imgBx">
        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['alt_text']); ?>">
    </div>
    <div class="contentBx">
        <h2><?php echo htmlspecialchars($product['title']); ?></h2>
        <div class="color">
            <h3>Price: $<?php echo htmlspecialchars($product['price']); ?></h3>
        </div>
        <a href="details.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">Buy Now</a>
        <a href ="#" class="compare-btn" data-product-id="<?php echo $product['product_id']; ?>">Compare</a>
    </div>
</div>
            <?php endforeach; ?>
        </div>
    </main>
    <div id="comparePopupButton">
    Compare (0)
</div>

<div id="comparePopup" class="popup hidden">
    <div class="popup-content">
        <span id="closePopupBtn" class="close-popup-button">&times;</span>
        <h2>Selected Products</h2>
        <ul id="selectedProductsList"></ul>
        <button id="compareNowBtn" class="btn-compare-now" disabled>Compare Now</button>
    </div>
</div>
    <form method="get" action="" class="pages">
        <label for="limit">Products per page:</label>
        <select name="limit" id="limit" onchange="this.form.submit()">
            <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
            <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
            <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
            <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
        </select>
    </form>
    <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">&laquo; Previous</a>
    <?php endif; ?>
    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next &raquo;</a>
</div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterModal = document.getElementById('filterModal');
    const closeModal = document.querySelector('.close');
    const filterForm = document.getElementById('filterForm');
    const resetFiltersButton = document.getElementById('resetFilters');

    filterButton.addEventListener('click', function() {
        filterModal.style.display = 'block';
    });

    closeModal.addEventListener('click', function() {
        filterModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == filterModal) {
            filterModal.style.display = 'none';
        }
    });

    filterForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const brand = document.getElementById('brand').value;
        const price = document.getElementById('price').value;
        const topSpeed = document.getElementById('top_speed').value;
        const weight = document.getElementById('weight').value;
        const motorPower = document.getElementById('motor_power').value;
        const rangePerCharge = document.getElementById('range_per_charge').value;
        const wheelSize = document.getElementById('wheel_size').value;

        const params = new URLSearchParams(window.location.search);
        if (brand) params.set('brand', brand);
        else params.delete('brand');
        if (price) params.set('price', price);
        else params.delete('price');
        if (topSpeed) params.set('top_speed', topSpeed);
        else params.delete('top_speed');
        if (weight) params.set('weight', weight);
        else params.delete('weight');
        if (motorPower) params.set('motor_power', motorPower);
        else params.delete('motor_power');
        if (rangePerCharge) params.set('range_per_charge', rangePerCharge);
        else params.delete('range_per_charge');
        if (wheelSize) params.set('wheel_size', wheelSize);
        else params.delete('wheel_size');

        window.location.search = params.toString();
    });

    resetFiltersButton.addEventListener('click', function() {
        document.getElementById('brand').value = '';
        document.getElementById('price').value = 5000;
        document.getElementById('price').nextElementSibling.value = 5000;
        document.getElementById('top_speed').value = 0;
        document.getElementById('top_speed').nextElementSibling.value = 0;
        document.getElementById('weight').value = 100;
        document.getElementById('weight').nextElementSibling.value = 100;
        document.getElementById('motor_power').value = 0;
        document.getElementById('motor_power').nextElementSibling.value = 0;
        document.getElementById('range_per_charge').value = 0;
        document.getElementById('range_per_charge').nextElementSibling.value = 0;
        document.getElementById('wheel_size').value = 0;
        document.getElementById('wheel_size').nextElementSibling.value = 0;

        const params = new URLSearchParams(window.location.search);
        params.delete('brand');
        params.delete('price');
        params.delete('top_speed');
        params.delete('weight');
        params.delete('motor_power');
        params.delete('range_per_charge');
        params.delete('wheel_size');

        window.location.search = params.toString();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const compareBtns = document.querySelectorAll('.compare-btn');
    const comparePopup = document.getElementById('comparePopup');
    const selectedProductsList = document.getElementById('selectedProductsList');
    const compareNowBtn = document.getElementById('compareNowBtn');
    const closePopupBtn = document.getElementById('closePopupBtn');
    const comparePopupButton = document.getElementById('comparePopupButton');

    // Array to store selected product details
    let selectedProducts = JSON.parse(localStorage.getItem('selectedProducts')) || [];

    // Function to update the popup
    function updatePopup() {
        selectedProductsList.innerHTML = '';
        selectedProducts.forEach(product => {
            const productItem = document.createElement('li');
            productItem.textContent = product.name;
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'Remove';
            removeBtn.className = 'btn-remove';
            removeBtn.addEventListener('click', () => removeProduct(product.id));
            productItem.appendChild(removeBtn);
            selectedProductsList.appendChild(productItem);
        });
        compareNowBtn.disabled = selectedProducts.length !== 2;
        comparePopupButton.textContent = `Compare (${selectedProducts.length})`; // Update floating button text
    }

    // Function to add a product to the selection
    function addProduct(productId) {
        const productCard = document.querySelector(`.card-${productId}`);
        const productName = productCard.getAttribute('data-product-title');
        if (selectedProducts.length < 2 && !selectedProducts.some(product => product.id === productId)) {
            selectedProducts.push({ id: productId, name: productName });
            localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));
            updatePopup();
        }
        if (selectedProducts.length === 2) {
            // alert('You can now compare products.');

            // Maak hier een pop up voor de gebruiker
        }
    }

    // Function to remove a product from the selection
    function removeProduct(productId) {
        selectedProducts = selectedProducts.filter(product => product.id !== productId);
        localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));
        updatePopup();
    }

    // Function to show the popup
    function showPopup() {
        comparePopup.classList.remove('hidden');
        updatePopup();
    }

    // Function to hide the popup
    function hidePopup() {
        comparePopup.classList.add('hidden');
    }

    // Event listener for compare buttons
    compareBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.getAttribute('data-product-id');
            addProduct(productId);
            showPopup();
        });
    });

    // Event listener for Compare Now button
    compareNowBtn.addEventListener('click', function () {
        if (selectedProducts.length === 2) {
            const compareUrl = `compare.php?product1=${selectedProducts[0].id}&product2=${selectedProducts[1].id}`;
            window.location.href = compareUrl;
        } else {
            // alert('Please select 2 products to compare.');
            // Schrijf hier nog een pop up voor de gebruiker


        }
    });

    // Event listener for Compare Popup button
    comparePopupButton.addEventListener('click', function () {
        showPopup();
    });

    // Event listener for Close Popup button
    closePopupBtn.addEventListener('click', hidePopup);

    // Close popup if clicked outside the content
    window.addEventListener('click', function (event) {
        if (event.target === comparePopup) {
            hidePopup();
        }
    });

    // Initialize the popup with any existing selected products
    if (selectedProducts.length > 0) {
        updatePopup();
    }
});
    </script>
</body>
</html>