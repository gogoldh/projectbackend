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
					<input type="text" name="email">
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
				</div>
			</form>
		</div>
	</div>
</body>
</html>