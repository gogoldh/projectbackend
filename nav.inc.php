<?php
session_start();
?><nav class="navbar">
    <a href="index.php" class="home__button"></a>
    
    <div class="navbar__right">
        <form action="" method="get">
            <input type="text" name="search" class="nav__search">
        </form>
        <a href="mylist.php">Cart</a>
        <a href="logout.php" class="navbar__logout">Hey Lukas, Logout?</a>

        
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

