<?php
include_once (__DIR__ . "/classes/user.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Check if email already exists
    $conn = Db::getConnection();
    $statement = $conn->prepare('SELECT COUNT(*) FROM user WHERE email = :email');
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $count = $statement->fetchColumn();

    if ($count > 0) {
        $errors[] = "An account with this email address already exists.";
    } else {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($_POST['password']);
        $user->setFname($_POST['fname']);
        $user->setLname($_POST['lname']);
        $user->save();
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