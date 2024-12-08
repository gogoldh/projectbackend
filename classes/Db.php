<?php

class Db {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn == null) {
            try {
                putenv('DB_HOST=alienrides.mysql.database.azure.com');
                putenv('DB_NAME=alienridesdb');
                putenv('DB_USER=alienrides');
                putenv('DB_PASSWORD=aB3$XyZ9!qP&7*rT@1n');
                putenv('DB_PORT=3306');
                $host = getenv('DB_HOST');
                $db = getenv('DB_NAME');
                $user = getenv('DB_USER');
                $pass = getenv('DB_PASSWORD');
                $port = getenv('DB_PORT');
                $pathToSSL = __DIR__ . '/../cacert.pem';


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