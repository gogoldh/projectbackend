<?php
include_once (__DIR__ . "/classes/Db.php");

function fetchProducts($brand = null, $search = null, $page = 1, $limit = 10){
    $conn = Db::getConnection();
    $offset = ($page - 1) * $limit;
    $sql = '   
        SELECT p.product_id, p.title, p.price, pi.image_url, pi.alt_text, b.name AS brand_name
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
        LEFT JOIN brand b ON p.brand_id = b.id
        WHERE pi.alt_text LIKE "%side view%"';
    
    if ($brand) {
        $sql .= ' AND b.name = :brand';
    }
    if ($search) {
        $sql .= ' AND p.title LIKE :search';
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
    $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
    $statement->bindParam(':offset', $offset, PDO::PARAM_INT);

    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

$brand = filter_input(INPUT_GET, 'brand', FILTER_SANITIZE_STRING); // Get brand from URL
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING); // Get search from URL
$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1; // Get current page from URL
$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT) ?: 10; // Get limit from URL
$products = fetchProducts($brand, $search, $page, $limit); // Pass brand, search, page, and limit to fetchProducts function

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUCSHOP</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="script.js"></script>
    <link rel="icon" type="image/x-icon" href="images/favicon.jpg">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css' rel='stylesheet' />
    <!-- Styling voor bg text product cards -->
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
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src="script.js"></script>
  <!-- ITEM TEMPLATE -->
        <!-- <div class="collection">
            <h2 class="collection__title">Electric Unicycles</h2>
            <div class="collection__items">
                <div class="collection__item">
                    <img src="images/inmotion_V14_50S.webp" alt="side profile picture of inmotion v14 50s">
                    <h3>Electric Unicycle 1</h3>
                    <p>Price: $1000</p>
                    <a href="#" class="like"><img src="images/heart_base.svg" alt="like icon - add to cart"></a>
                    
        </div> -->


        <div class="container">

        <?php foreach ($products as $product):?>
        <div class="card card-<?php echo htmlspecialchars($product['product_id']); ?>">
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
</body>
</html>