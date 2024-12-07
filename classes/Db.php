<?php

class Db {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn == null) {
            try {
                $config = include('db_config.php');

                if (!file_exists($config['ssl'])) {
                    throw new Exception("SSL certificate not found at path: {$config['ssl']}");
                }

                $options = [
                    PDO::MYSQL_ATTR_SSL_CA => $config['ssl'],
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ];

                self::$conn = new PDO(
                    "mysql:host={$config['host']};dbname={$config['db']}",
                    $config['user'],
                    $config['pass'],
                    $options
                );
            } catch (PDOException $e) {
                error_log("Connection failed: " . $e->getMessage());
                die("Database connection failed.");
            } catch (Exception $e) {
                error_log("Error: " . $e->getMessage());
                die("Error occurred.");
            }
        }
        return self::$conn;
    }
}
?>