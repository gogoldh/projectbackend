<?php
include_once (__DIR__ . "/classes/Db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Establish a database connection using PDO
        $conn = Db::getConnection();

        // Prepare the SQL query to insert user data
        $statement = $conn->prepare('INSERT INTO user (fname, lname, email, password) VALUES (:fname, :lname, :email, :password)');
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);

        // Execute the insert query
        $statement->execute();

        // Get the last inserted ID
        $user_id = $conn->lastInsertId();

        // Set session variables
        $_SESSION['id'] = $user_id;
        $_SESSION['fname'] = $fname;

        // Redirect to profile page
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        // Handle any database connection or query errors
        echo "Database error: " . $e->getMessage();
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EUCSHOP</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="EUCLogin">
        <div class="form form--login">
            <form action="" method="post">
                <h2 class="form__title">Sign up</h2>
                <?php if (!empty($errors)): ?>
                    <div class="errors">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="form__field">
                    <label for="Email">Email</label>
                    <input class="emailInput" type="email" name="email" required>
                    <span class="feedback"></span>
                </div>
                <div class="form__field">
                    <label for="Fname">First Name</label>
                    <input type="text" name="fname" required>
                </div>
                <div class="form__field">
                    <label for="Lname">Last Name</label>
                    <input type="text" name="lname" required>
                </div>
                <div class="form__field">
                    <label for="Password">Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form__field">
                    <button type="submit" class="btn btn--primary">Sign Up</button>
                    <div class="form_screenswitch">
                        <p>Already Signed up? <a href="login.php" class="screenswitch">Log in!</a></p>
                        <a href="index.php" class="screenswitch">Just want to browse?</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        // checken of email al bestaat + feedback geven
        document.querySelector('.emailInput').addEventListener('keyup', function(){
            let email = this.value;

            let formData = new FormData();
            formData.append('email', email);

            fetch('ajax/checkusername.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                console.log(result);
                const feedback = document.querySelector('.feedback');
                if(result.available == true){
                    feedback.textContent = 'This email is available';
                    feedback.classList.remove('feedback--unavailable');
                    feedback.classList.add('feedback--available');
                } else {
                    feedback.textContent = 'This email is already taken';
                    feedback.classList.remove('feedback--available');
                    feedback.classList.add('feedback--unavailable');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>