<?php
include_once (__DIR__ . "/classes/Db.php");

function fetchProducts($brand = null, $search = null, $page = 1, $limit = 10, $filters = []){
    $conn = Db::getConnection();
    $offset = ($page - 1) * $limit;
    $sql = '   
        SELECT p.product_id, p.title, p.price, pi.image_url, pi.alt_text, b.name AS brand_name, s.top_speed, s.weight, s.motor_power, s.range_per_charge, s.wheel_size
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
        LEFT JOIN brand b ON p.brand_id = b.id
        LEFT JOIN specifications s ON p.product_id = s.product_id
        WHERE pi.alt_text LIKE "%side view%"';
    
    if ($brand) {
        $sql .= ' AND b.name = :brand';
    }
    if ($search) {
        $sql .= ' AND p.title LIKE :search';
    }
    if (!empty($filters)) {
        if (isset($filters['top_speed'])) {
            $sql .= ' AND s.top_speed >= :top_speed';
        }
        if (isset($filters['weight'])) {
            $sql .= ' AND s.weight <= :weight';
        }
        if (isset($filters['motor_power'])) {
            $sql .= ' AND s.motor_power >= :motor_power';
        }
        if (isset($filters['range_per_charge'])) {
            $sql .= ' AND s.range_per_charge >= :range_per_charge';
        }
        if (isset($filters['wheel_size'])) {
            $sql .= ' AND s.wheel_size >= :wheel_size';
        }
    }

    $sql .= ' LIMIT :limit OFFSET :offset';

    $statement = $conn->prepare($sql);
    
    if ($brand) {
        $statement->bindParam(':brand', $brand, PDO::PARAM_STR);
    }
    if ($search) {
        $search = "%$search%";
        $statement->bindParam(':search', $search, PDO::PARAM_STR);
    }
    if (!empty($filters)) {
        if (isset($filters['top_speed'])) {
            $statement->bindParam(':top_speed', $filters['top_speed'], PDO::PARAM_INT);
        }
        if (isset($filters['weight'])) {
            $statement->bindParam(':weight', $filters['weight'], PDO::PARAM_INT);
        }
        if (isset($filters['motor_power'])) {
            $statement->bindParam(':motor_power', $filters['motor_power'], PDO::PARAM_INT);
        }
        if (isset($filters['range_per_charge'])) {
            $statement->bindParam(':range_per_charge', $filters['range_per_charge'], PDO::PARAM_INT);
        }
        if (isset($filters['wheel_size'])) {
            $statement->bindParam(':wheel_size', $filters['wheel_size'], PDO::PARAM_INT);
        }
    }
    $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
    $statement->bindParam(':offset', $offset, PDO::PARAM_INT);

    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function fetchBrands() {
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT id, name FROM brand');
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

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

$products = fetchProducts($brand, $search, $page, $limit, $filters); // Pass brand, search, page, limit, and filters to fetchProducts function
$brands = fetchBrands(); // Fetch brands from the database

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
            <div class="card card-<?php echo htmlspecialchars($product['product_id']); ?> brand-<?php echo htmlspecialchars(str_replace(' ', '-', strtolower($product['brand_name']))); ?>" data-top-speed="<?php echo htmlspecialchars($product['top_speed']); ?>" data-weight="<?php echo htmlspecialchars($product['weight']); ?>" data-motor-power="<?php echo htmlspecialchars($product['motor_power']); ?>" data-range-per-charge="<?php echo htmlspecialchars($product['range_per_charge']); ?>" data-wheel-size="<?php echo htmlspecialchars($product['wheel_size']); ?>">
                <div class="imgBx">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['alt_text']); ?>">
                </div>
                <div class="contentBx">
                    <h2><?php echo htmlspecialchars($product['title']); ?></h2>
                    <div class="color">
                        <h3>Price: $<?php echo htmlspecialchars($product['price']); ?></h3>
                    </div>
                    <a href="details.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">Buy Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
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
    const cards = document.querySelectorAll('.card');

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
        const brand = document.getElementById('brand').value.toLowerCase().replace(' ', '-');
        const price = document.getElementById('price').value;
        const topSpeed = document.getElementById('top_speed').value;
        const weight = document.getElementById('weight').value;
        const motorPower = document.getElementById('motor_power').value;
        const rangePerCharge = document.getElementById('range_per_charge').value;
        const wheelSize = document.getElementById('wheel_size').value;

        cards.forEach(card => {
            const cardBrand = card.classList.contains(`brand-${brand}`);
            const cardPrice = parseFloat(card.querySelector('.color h3').textContent.replace('Price: $', ''));
            const cardTopSpeed = parseFloat(card.dataset.topSpeed);
            const cardWeight = parseFloat(card.dataset.weight);
            const cardMotorPower = parseFloat(card.dataset.motorPower);
            const cardRangePerCharge = parseFloat(card.dataset.rangePerCharge);
            const cardWheelSize = parseFloat(card.dataset.wheelSize);

            if (
                (brand === '' || cardBrand) &&
                (price === '' || cardPrice <= price) &&
                (topSpeed === '' || cardTopSpeed >= topSpeed) &&
                (weight === '' || cardWeight <= weight) &&
                (motorPower === '' || cardMotorPower >= motorPower) &&
                (rangePerCharge === '' || cardRangePerCharge >= rangePerCharge) &&
                (wheelSize === '' || cardWheelSize >= wheelSize)
            ) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        filterModal.style.display = 'none';
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

        cards.forEach(card => {
            card.style.display = 'block';
        });
    });
});
        
    </script>
</body>
</html>