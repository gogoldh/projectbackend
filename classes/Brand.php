<?php
class Brand {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
public static function fetchBrands() {
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT id, name FROM brand');
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
}
?>