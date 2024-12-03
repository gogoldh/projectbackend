<?php
$servername = 'alienrides.mysql.database.azure.com';
$username = 'alienrides';
$password = 'aB3$XyZ9!qP&7*rT@1n';
$database = 'alienridesdb';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?> 