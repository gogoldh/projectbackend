<?php
include_once (__DIR__ . "/Db.php");

class User {
    private $email;
    private $password;
    private $fname;
    private $lname;

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    public function setFname($fname) {
        $this->fname = $fname;
    }

    public function setLname($lname) {
        $this->lname = $lname;
    }

    public function save() {
        $conn = Db::getConnection();

        try {
            $statement = $conn->prepare('INSERT INTO user (email, password, fname, lname) VALUES (:email, :password, :fname, :lname)');
            $statement->bindValue(':email', $this->email);
            $statement->bindValue(':password', $this->password);
            $statement->bindValue(':fname', $this->fname);
            $statement->bindValue(':lname', $this->lname);
            $statement->execute();
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            // Log the error or display it for debugging
            echo 'Error: ' . $e->getMessage();
        }
    }

        //static method that checks if a usernname is available (table users, column username)
        public static function isEmailAvailable($email) {
            $conn = Db::getConnection(); // Corrected method to get the database connection
            $statement = $conn->prepare("SELECT * FROM user WHERE email = :email");
            $statement->bindValue(":email", $email);
            $statement->execute();
            if ($statement->rowCount() === 0) {
                return true;
            } else {
                return false;
            }
        }
}