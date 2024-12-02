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

    <main class="bg_compare">
    <div class="container_support">
            <div class="support_form">
                <form action="submit_support.php" method="post">
                    <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" class="form__field" required>
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
                    <h3 class="faq_question">What happens after delivery?</h3>
                    <div class="faq_answer">
                        <p>You won't receive your wheel, and we'll keep all your money.</p>
                    </div>
                </div>
                <div class="faq_item">
                    <h3 class="faq_question">When do i receive my wheel?</h3>
                    <div class="faq_answer">
                        <p>NEVER!</p>
                    </div>
                </div>
                <div class="faq_item">
                    <h3 class="faq_question">Who made this project possible?</h3>
                    <div class="faq_answer">
                        <p>me xo</p>
                    </div>
                </div>
            </div>
        </div>
        </main>
        <script>
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq_question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const answer = this.nextElementSibling;
            if (answer.style.maxHeight) {
                answer.style.maxHeight = null;
                this.classList.remove('active');
            } else {
                faqQuestions.forEach(q => {
                    q.nextElementSibling.style.maxHeight = null;
                    q.classList.remove('active');
                });
                answer.style.maxHeight = answer.scrollHeight + 'px';
                this.classList.add('active');
            }
        });
    });
});
</script>
</body>
</html>