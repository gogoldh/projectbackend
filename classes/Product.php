<?php
include_once (__DIR__ . "/Db.php");

class Product{
    private $title;
    private $price;
    private $image;
    private $description;
    private $color;
    private $max_speed;
    private $motor_power;
    private $battery_cappacity;
    private $range;	
    private $weight;
    private $wheel_diameter;
    private $max_load;
    private $water_resistance;
    private $bluetooth_speakers;
    private $on_sale;
    private $sale_percentage;
    private $stock;


    public function save()

    {
        $conn = Db::getConnection();

        try {
            $statement = $conn->prepare('INSERT INTO product (title, price, image, description, color, max_speed, motor_power, battery_cappacity, range, weight, wheel_diameter, max_load, water_resistance, bluetooth_speakers, on_sale, sale_percentage, stock) VALUES (:title, :price, :image, :description, :color, :max_speed, :motor_power, :battery_cappacity, :range, :weight, :wheel_diameter, :max_load, :water_resistance, :bluetooth_speakers, :on_sale, :sale_percentage, :stock)');
            $statement->bindValue(':title', $this->title);
            $statement->bindValue(':price', $this->price);
            $statement->bindValue(':image', $this->image);
            $statement->bindValue(':description', $this->description);
            $statement->bindValue(':color', $this->color);
            $statement->bindValue(':max_speed', $this->max_speed);
            $statement->bindValue(':motor_power', $this->motor_power);
            $statement->bindValue(':battery_cappacity', $this->battery_cappacity);
            $statement->bindValue(':range', $this->range);
            $statement->bindValue(':weight', $this->weight);
            $statement->bindValue(':wheel_diameter', $this->wheel_diameter);
            $statement->bindValue(':max_load', $this->max_load);
            $statement->bindValue(':water_resistance', $this->water_resistance);
            $statement->bindValue(':bluetooth_speakers', $this->bluetooth_speakers);
            $statement->bindValue(':on_sale', $this->on_sale);
            $statement->bindValue(':sale_percentage', $this->sale_percentage);
            $statement->bindValue(':stock', $this->stock);
            $statement->execute();
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            // Log the error or display it for debugging
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>