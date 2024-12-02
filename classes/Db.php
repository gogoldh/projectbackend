<?php
// class Db {
//     private static $conn = null;

//     public static function getConnection(){
//         if (self::$conn == null) {
//             self::$conn = new PDO('mysql:host=localhost;dbname=projectbackend', 'root', '');
//         } 
//         return self::$conn;
//     }
// }


$dbHost = "alienrides.mysql.database.azure.com";
$dbUser = "alienrides@alienrides";
$dbPassword = "aB3$XyZ9!qP&7*rT@1n";
$dbName = "database_name";
$conn = new PDO($dbHost, $dbUser, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>