<?php

class Db {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn == null) {
            try {
                $host = 'alienrides.mysql.database.azure.com';
                $db = 'alienridesdb';
                $user = 'alienrides';
                $pass = 'aB3$XyZ9!qP&7*rT@1n';
                $pathToSSL = __DIR__ . '/cacert.pem'; // Ensure this path is correct and accessible

                // Ensure SSL connection
                $options = array(
                    PDO::MYSQL_ATTR_SSL_CA => $pathToSSL,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );

                self::$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass, $options);
            } catch (PDOException $e) {
                // Handle the error, for example, log it and/or display a user-friendly message
                error_log("Connection failed: " . $e->getMessage());
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>