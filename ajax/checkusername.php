<?php
    require_once('../bootstrap.php');
    if(!empty($_POST)){
        $email = $_POST['email'];
        $result = User::isEmailAvailable($email);

        if ($result === true) {
            $available = true;
        }
        else {
            $available = false;
        }

        $result = [
            "status" => "success",
            "message" => "email is checked",
            "available" => $available
        ];
        echo json_encode($result);
    }
?>