<?php
include_once (__DIR__ . "/classes/user.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();
    $user->setEmail($_POST['email']);
    $user->setPassword($_POST['password']);
    $user->setFname($_POST['fname']);
    $user->setLname($_POST['lname']);
    $user->save();
}
?>
<!DOCTYPE html>
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
                <div class="form__field">
                    <label for="Email">Email</label>
                    <input type="email" name="email" required>
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
					<p>Aleady Signed up?<a href="login.php" class="screenswitch">
					Log in!</p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>