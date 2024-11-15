<?php
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="details">
    <?php include_once("nav.inc.php")?>
    <h1>Inmotion V14 50S</h1>
    <p>
        The Inmotion V14 Adventure electric unicycle is a powerful off-road transport with a 4000W motor and a top speed of 70 km/h. Its 2400Wh battery provides a 120 km range for thrilling adventures.
    </p>
    <div class="wheel-images">
            <img src="images/inmotion_V14_50S.webp" alt="Wheel Image">
    </div>
    <p>Price: <?php echo $wheel['price']; ?></p>
    <p>Manufacturer: <?php echo $wheel['manufacturer']; ?></p>
</body>
</html>