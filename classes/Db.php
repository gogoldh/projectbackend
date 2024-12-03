<?php

class Db {
    private static $conn = null;

    public static function getConnection() {
        // Db::getConnection();
        if (self::$conn == null) {
            try {
                $pathToSSL = './cacert.pem';
                $options = array(PDO::MYSQL_ATTR_SSL_CA => $pathToSSL);

                $host = 'alienrides.mysql.database.azure.com';
                $db = 'alienridesdb';
                $user = 'alienrides';
                $pass = 'aB3$XyZ9!qP&7*rT@1n';
                self::$conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass, $options);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Handle the error, for example, log it and/or display a user-friendly message
                error_log("Connection failed: " . $e->getMessage());
                die("Database connection failed. Please try again later.");
            }
        }
        return self::$conn;
    }
}
?>