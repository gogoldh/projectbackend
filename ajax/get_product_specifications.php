<?php
require_once dirname(__DIR__) . '/classes/Db.php'; // Ensure correct path to Db.php

// Get the PDO connection using the static method
$conn = Db::getConnection();

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Check if the database connection ($conn) exists
    if ($conn) {
        // Fetch product specifications
        $statement = $conn->prepare('SELECT motor_power, top_speed, battery_capacity, range_per_charge, charging_time, wheel_size, weight_capacity, incline_capability, weight, pedal_height, tire_type, suspension, ip_rating, speaker_system FROM specifications WHERE product_id = :product_id');
        $statement->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $statement->execute();
        $specs = $statement->fetch(PDO::FETCH_ASSOC);

        if ($specs) {
            header('Content-Type: application/json'); // Return JSON response
            echo json_encode($specs);
        } else {
            http_response_code(404); // Specifications not found
            echo json_encode(['error' => 'Specifications not found']);
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