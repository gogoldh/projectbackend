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
				<h2 form__title>Sign up</h2>
				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" name="email" required>
				</div>
                <div class="form__field">
					<label for="Fname">First Name</label>
					<input type="text" name="fname" required>
				</div>
                <div class="form__field">
					<label for="Lname">Last name</label>
					<input type="text" name="lname" required>
				</div>
				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" name="password" required>
				</div>

				<div class="form__field">
					<input type="submit" value="Sign up" class="btn btn--primary">	
					<input type="checkbox" id="rememberMe" required>
					<label for="rememberMe" class="label__inline">Remember me</label>
				</div>

				<div class="form_screenswitch">
					<p>Already signed up? <a href="login.php" class="screenswitch">
					sign in!</p>
				</div>
			</form>
		</div>
	</div>
</body>
</html>