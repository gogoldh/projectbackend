<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once(__DIR__ . "/classes/Db.php");

// Initialize variables
$fname = 'Guest'; // Default name for guest users
$balance = 0; // Default balance for guests
$isLoggedIn = false;

try {
    // Check if user is logged in via session
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $id = $_SESSION['id'];

        // Get database connection
        $conn = Db::getConnection();

        // Fetch user information
        $statement = $conn->prepare('SELECT fname, balance FROM user WHERE id = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $fname = htmlspecialchars($user['fname']); // Sanitize output
            $balance = (float) $user['balance']; // Cast balance to float for safety
            $isLoggedIn = true;
        }
    }
} catch (Exception $e) {
    error_log("Error in navigation script: " . $e->getMessage());
    // Fallback values for error scenarios
    $fname = 'Guest';
    $balance = 0;
    $isLoggedIn = false;
}
?>
<nav class="navbar">
    <a href="index.php" class="home__button"></a>

    <div class="navbar__right">
        <form action="search.php" method="get">
            <input type="text" name="search" class="nav__search" placeholder="Search...">
        </form>
        <a href="cart.php">Cart</a>
        
        <!-- Profile Button with Dropdown -->
        <div class="profile">
            <a href="#" class="profile__button">
                <i class="fa fa-user"></i> <?php echo $fname; ?>
            </a>
            <div class="profile__dropdown">
                <?php if ($isLoggedIn): ?>
                    <a href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                    <a href="#">Balance: $<?php echo number_format($balance, 2); ?></a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div id="nav__wrapper">
    <!-- Dropdown Navigation -->
    <div class="dropdown">
        <a href="index.php" class="dropbtn">Electric Unicycles
            <i class="fa fa-caret-down"></i>
        </a>
        <div class="dropdown-content">
            <?php
            // Dynamic dropdown items for brands
            $brands = ['Inmotion', 'Kingsong', 'Leaperkim', 'Begode', 'Nosfet'];
            foreach ($brands as $brand) {
                echo '<a href="index.php?brand=' . urlencode($brand) . '">' . htmlspecialchars($brand) . '</a>';
            }
            ?>
        </div>
    </div>
    <a href="#">Sale</a>
    <a href="javascript:void(0);" id="compare-nav-button">Compare</a>
    <a href="support.php">Support</a>
</div>
