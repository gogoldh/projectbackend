<?php
include_once (__DIR__ . "/Db.php");

class ProductFilter {
    private $conn;

    public function __construct() {
        $this->conn = Db::getConnection();
    }

    public function filterProducts($brand = null, $price = null, $topSpeed = null, $weight = null, $motorPower = null, $rangePerCharge = null, $wheelSize = null, $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $sql = '   
            SELECT p.product_id, p.title, p.price, p.description, pi.image_url, pi.alt_text, b.name AS brand_name, s.top_speed, s.weight, s.motor_power, s.range_per_charge, s.wheel_size
            FROM products p
            LEFT JOIN product_images pi ON p.product_id = pi.product_id
            LEFT JOIN brand b ON p.brand_id = b.id
            LEFT JOIN specifications s ON p.product_id = s.product_id
            WHERE pi.alt_text LIKE "%side view%"';

        $params = [];

        if ($brand) {
            $sql .= ' AND b.name = :brand';
            $params[':brand'] = $brand;
        }
        if ($price) {
            $sql .= ' AND p.price <= :price';
            $params[':price'] = $price;
        }
        if ($topSpeed) {
            $sql .= ' AND s.top_speed >= :top_speed';
            $params[':top_speed'] = $topSpeed;
        }
        if ($weight) {
            $sql .= ' AND s.weight <= :weight';
            $params[':weight'] = $weight;
        }
        if ($motorPower) {
            $sql .= ' AND s.motor_power >= :motor_power';
            $params[':motor_power'] = $motorPower;
        }
        if ($rangePerCharge) {
            $sql .= ' AND s.range_per_charge >= :range_per_charge';
            $params[':range_per_charge'] = $rangePerCharge;
        }
        if ($wheelSize) {
            $sql .= ' AND s.wheel_size >= :wheel_size';
            $params[':wheel_size'] = $wheelSize;
        }

        $sql .= ' LIMIT ' . intval($limit) . ' OFFSET ' . intval($offset);

        $statement = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>