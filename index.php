<?php
include_once (__DIR__ . "/classes/Db.php");


function fetchProducts(){
    $conn = Db::getConnection();
    $statement = $conn->prepare('   
        SELECT p.title, p.price, pi.image_url, pi.alt_text
        FROM products p
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
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
    <link rel="icon" type="image/x-icon" href="images/inmotion_V14_50S.webp">
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css' rel='stylesheet' />
</head>
<body>
    <?php include_once("nav.inc.php")?>
    <main>
    <div class="scroller">
    </div>
    <div class="progress-wrap">
      <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
          <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
      </svg>
  </div>
  <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src="script.js"></script>
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

        <?php foreach ($products as $product): ?>
        <div class="card">
            <div class="imgBx">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Inmotion V14 50S">
            </div>

            <div class="contentBx">

                <h2><?php echo htmlspecialchars($product['title']); ?></h2>

                <div class="color">

                    <h3>Price: $<?php echo htmlspecialchars($product['price']); ?></h3>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <a href="details.php">Buy Now</a>
            </div>
        </div>
        <?php endforeach; ?>
     
        </div>
    </div>
        

    </main>
</body>
</html>