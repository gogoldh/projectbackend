<?php
require_once dirname(__DIR__) . '/classes/Db.php'; // Ensure correct path to Db.php

// Get the PDO connection using the static method
$conn = Db::getConnection();

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Check if the database connection ($conn) exists
    if ($conn) {
        // Fetch product images
        $statement = $conn->prepare('SELECT image_url, alt_text FROM product_images WHERE product_id = :product_id');
        $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $statement->execute();
        $images = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($images) {
            header('Content-Type: application/json'); // Return JSON response
            echo json_encode($images);
        } else {
            http_response_code(404); // Images not found
            echo json_encode(['error' => 'No images found for this product']);
        }
    } else {
        http_response_code(500); // Database connection error
        echo json_encode(['error' => 'Database connection failed']);
    }
} else {
    http_response_code(400); // Invalid product ID
    echo json_encode(['error' => 'Invalid product ID']);
}
?>