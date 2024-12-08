<?php
include_once (__DIR__ . "/classes/Db.php");
session_start();

function canLogin($p_email, $p_password){
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT * FROM user WHERE email = :email');
    $statement->bindValue(':email', $p_email);
    $statement->execute();

    $user = $statement->fetch(PDO::FETCH_ASSOC);
    if($user){
        $hash = $user['password'];
        if(password_verify($p_password, $hash)){
            // Set session variables on successful login
            $_SESSION['id'] = $user['id']; // Store user ID in session
            $_SESSION['fname'] = $user['fname']; // Store user first name in session

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            return false; // wrong password
        }
    } else {
        return false; // No user Account
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Call the canLogin function
    if (canLogin($email, $password)) {
        // The user will be redirected in the function
    } else {
        $error = true; // If login fails, show the error
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
				<h2 form__title>Sign In</h2>
				<?php if( isset ($error) ): ?>
				<div class="form__error">
					<p>
						Sorry, we can't sign you in with that email address and password. Try again?
					</p>
				</div>
				<?php endif; ?>
				<div class="form__field">
					<label for="Email">Email</label>
					<input type="email" name="email">
				</div>
				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" name="password">
				</div>

				<div class="form__field">
					<input type="submit" value="Sign in" class="btn btn--primary">	
				</div>

				<div class="form_screenswitch">
					<p>Not signed up yet? <a href="signup.php" class="screenswitch">
						
					sign up!</p>
                    <a href="index.php" class="screenswitch">Just want to browse?</a>
				</div>
			</form>
		</div>
	</div>
</body>