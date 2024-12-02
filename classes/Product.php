<?php
include_once (__DIR__ . "/Db.php");

class Product {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public static function fetchProducts($brand = null, $search = null, $page = 1, $limit = 10, $filters = []) {
        $conn = Db::getConnection();
        $offset = ($page - 1) * $limit;
        $sql = '   
            SELECT p.product_id, p.title, p.price, p.description, pi.image_url, pi.alt_text, b.name AS brand_name, s.top_speed, s.weight, s.motor_power, s.range_per_charge, s.wheel_size
            FROM products p
            LEFT JOIN product_images pi ON p.product_id = pi.product_id
            LEFT JOIN brand b ON p.brand_id = b.id
            LEFT JOIN specifications s ON p.product_id = s.product_id
            WHERE pi.alt_text LIKE "%side view%"';
        
        if ($brand) {
            $sql .= ' AND b.name = :brand';
        }
        if ($search) {
            $sql .= ' AND p.title LIKE :search';
        }
        if (!empty($filters)) {
            if (isset($filters['top_speed'])) {
                $sql .= ' AND s.top_speed >= :top_speed';
            }
            if (isset($filters['weight'])) {
                $sql .= ' AND s.weight <= :weight';
            }
            if (isset($filters['motor_power'])) {
                $sql .= ' AND s.motor_power >= :motor_power';
            }
            if (isset($filters['range_per_charge'])) {
                $sql .= ' AND s.range_per_charge >= :range_per_charge';
            }
            if (isset($filters['wheel_size'])) {
                $sql .= ' AND s.wheel_size >= :wheel_size';
            }
        }
    
        $sql .= ' LIMIT :limit OFFSET :offset';
    
        $statement = $conn->prepare($sql);
        
        if ($brand) {
            $statement->bindParam(':brand', $brand, PDO::PARAM_STR);
        }
        if ($search) {
            $search = "%$search%";
            $statement->bindParam(':search', $search, PDO::PARAM_STR);
        }
        if (isset($filters['top_speed'])) {
            $statement->bindParam(':top_speed', $filters['top_speed'], PDO::PARAM_INT);
        }
        if (isset($filters['weight'])) {
            $statement->bindParam(':weight', $filters['weight'], PDO::PARAM_INT);
        }
        if (isset($filters['motor_power'])) {
            $statement->bindParam(':motor_power', $filters['motor_power'], PDO::PARAM_INT);
        }
        if (isset($filters['range_per_charge'])) {
            $statement->bindParam(':range_per_charge', $filters['range_per_charge'], PDO::PARAM_INT);
        }
        if (isset($filters['wheel_size'])) {
            $statement->bindParam(':wheel_size', $filters['wheel_size'], PDO::PARAM_INT);
        }
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
    
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchImages($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT image_url, alt_text FROM product_images WHERE product_id = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function fetchSpecifications($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT 
            motor_power,
            top_speed,
            battery_capacity,
            range_per_charge,
            charging_time,
            wheel_size,
            weight_capacity,
            incline_capability,
            weight,
            pedal_height,
            tire_type,
            suspension,
            ip_rating,
            speaker_system
        FROM specifications WHERE product_id = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function fetchProductDetails($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare('
            SELECT p.*, b.name AS brand_name
            FROM products p
            LEFT JOIN brand b ON p.brand_id = b.id
            WHERE p.product_id = :id
        ');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public static function fetchProductImages($id) {
        $conn = Db::getConnection();
        $statement = $conn->prepare('SELECT image_url, alt_text FROM product_images WHERE product_id = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>