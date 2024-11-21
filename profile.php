<?php
session_start();
include_once (__DIR__ . "/classes/Db.php");

// Check if user ID is set in the session
if (!isset($_SESSION['id'])) {
    die('User not logged in');
}

$id = filter_var($_SESSION['id'], FILTER_VALIDATE_INT); // Ensure $id is an integer

if ($id) {
    try {
        // Establish a database connection using PDO
        $conn = Db::getConnection();

        // Prepare the SQL query to fetch user data
        $statement = $conn->prepare('SELECT fname, lname, email FROM user WHERE id = :id');
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        $statement->execute();

        // Fetch the result
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // If user is found, use the data; otherwise, default to empty values
        if ($user && isset($user['fname'])) {
            $fname = htmlspecialchars($user['fname']);
            $lname = htmlspecialchars($user['lname']);
            $email = htmlspecialchars($user['email']);
        } else {
            $fname = 'Guest'; // No matching user or fname is null
            $lname = '';
            $email = '';
        }
    } catch (PDOException $e) {
        // Handle any database connection or query errors
        echo "Database error: " . $e->getMessage();
        $fname = 'Guest';
        $lname = '';
        $email = '';
    }
} else {
    echo "Invalid ID.";
    $fname = 'Guest'; // Fallback for invalid session ID
    $lname = '';
    $email = '';
}

// Update user data in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Prepare the SQL query to update user data
        $update_statement = $conn->prepare('UPDATE user SET fname = :fname, lname = :lname, email = :email, password = :password WHERE id = :id');
        $update_statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $update_statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $update_statement->bindParam(':email', $email, PDO::PARAM_STR);
        $update_statement->bindParam(':password', $password, PDO::PARAM_STR);
        $update_statement->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the update query
        $update_statement->execute();
    } catch (PDOException $e) {
        // Handle any database connection or query errors
        echo "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="body_profile">
    <?php include_once("nav.inc.php")?>
    <div class="profile-container">
        <h3>Profile page</h3>
        <form method="POST" action="profile.php" class="profile-form">
            <div class="form-group">
                <label for="fname">First Name:</label>
                <input type="text" id="fname" name="fname" value="<?php echo $fname; ?>" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name:</label>
                <input type="text" id="lname" name="lname" value="<?php echo $lname; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-update">Update Profile</button>
        </form>
    </div>
</body>
</html>