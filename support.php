<?php
session_start(); // Ensure session is started
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Page</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include_once("nav.inc.php") ?>

    <main>
        <div class="container">
            <h2>Support</h2>
            <div class="support_form">
                <form action="submit_support.php" method="post">
                    <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" class="form__field"required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input class="form__field" type="email" id="email" name="email" required>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($_SESSION['user']['name']); ?>">
                        <input class="form__field" type="hidden" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form__field" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="support_faq">
                <h2>Frequently Asked Questions</h2>
                <div class="faq_item">
                    <h3>Question 1</h3>
                    <p>Answer to question 1.</p>
                </div>
                <div class="faq_item">
                    <h3>Question 2</h3>
                    <p>Answer to question 2.</p>
                </div>
                <!-- Add more FAQ items as needed -->
            </div>
        </div>
    </main>
</body>
</html>