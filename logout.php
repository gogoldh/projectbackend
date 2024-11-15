<?php
    session_start();
    session_destroy();
    header('Location: login.php');
    /*
    setcookie('login', 'weg', time()-3600);
    header('Location: login.php');
    */
?>