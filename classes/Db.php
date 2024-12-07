<?php

class Db {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn == null) {
            try {
                $host = getenv('DB_HOST');
                $db = getenv('DB_NAME');
                $user = getenv('DB_USER');
                $pass = getenv('DB_PASSWORD');
                $port = getenv('DB_PORT');
                $pathToSSL = __DIR__ . '/../cacert.pem';

                // Debugging: Print environment variables
                error_log("DB_HOST: $host");
                error_log("DB_NAME: $db");
                error_log("DB_USER: $user");
                error_log("DB_PASSWORD: $pass");
                error_log("DB_PORT: $port");
                error_log("SSL Path: $pathToSSL");

                if (!$host || !$db || !$user || !$pass || !$port) {
                    throw new Exception("Missing required environment variables.");
                }

                if (!file_exists($pathToSSL)) {
                    throw new Exception("SSL certificate not found: $pathToSSL");
                }

                $options = array(
                    PDO::MYSQL_ATTR_SSL_CA => $pathToSSL,
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                );

                self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, $options);
            } catch (PDOException $e) {
                error_log("PDO Error: " . $e->getMessage());
                die("Database connection failed: " . $e->getMessage());
            } catch (Exception $e) {
                error_log("General Error: " . $e->getMessage());
                die("An error occurred: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
?>