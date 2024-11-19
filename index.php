<?php
include_once (__DIR__ . "/classes/Db.php");


function fetchProducts(){
    $conn = Db::getConnection();
    $statement = $conn->prepare('   
        SELECT p.product_id, p.title, p.price, pi.image_url, pi.alt_text, b.name AS brand_name
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
        LEFT JOIN brand b ON p.brand_id = b.id
        WHERE pi.alt_text LIKE "%side view%"
');
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    
}    

$products = fetchProducts();
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
</body>
</html>