<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once (__DIR__ . "/classes/Db.php");

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    $conn = Db::getConnection(); // Ensure you have a valid database connection
    $statement = $conn->prepare('SELECT fname FROM user WHERE id = :id');
    $statement->bindParam(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    $fname = htmlspecialchars($user['fname']);  
} else {
    $fname = 'Guest';
}
?>
<nav class="navbar">
    <a href="index.php" class="home__button"></a>

    <div class="navbar__right">
        <form action="" method="get">
            <input type="text" name="search" class="nav__search">
        </form>
        <a href="mylist.php">Cart</a>
        
        <!-- Profile Button with Dropdown -->
        <div class="profile">
            <a href="#" class="profile__button">
                <i class="fa fa-user"></i> <?php echo $fname; ?>
            </a>
            <div class="profile__dropdown">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div id="nav__wrapper">
    <div class="dropdown">
        <a href="index.php" class="dropbtn">Electric Unicycles
            <i class="fa fa-caret-down"></i>
        </a>
        <div class="dropdown-content">
            <a href="index.php?brand=Inmotion">Inmotion</a>
            <a href="index.php?brand=Kingsong">Kingsong</a>
            <a href="index.php?brand=Leaperkim">Leaperkim</a>
            <a href="index.php?brand=Begode">Begode</a>
            <a href="index.php?brand=Nosfet">Nosfet</a>
        </div>
    </div>
    <a href="#">Sale</a>
    <a href="#">Accessories</a>
    <a href="#">Support</a>
</div>