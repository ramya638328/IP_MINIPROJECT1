```php
<?php
require_once "includes/auth.php";

$pageTitle = "Home";
require "includes/header.php";
?>

<section class="hero">
    <p class="muted">SMART • SIMPLE • INTERACTIVE</p>

    <h1>
        Test your knowledge.<br>
        <span style="color:#2563eb;">Track your progress.</span>
    </h1>

    <p>
        Take timed quizzes, get instant scores, and improve
        your performance through an engaging online quiz experience.
    </p>

    <div class="actions" style="margin-top:25px;">

        <?php if (isset($_SESSION["user_id"])): ?>

            <a href="dashboard.php" class="btn">
                Go to Dashboard →
            </a>

            <a href="quizzes.php" class="btn secondary">
                Explore Quizzes
            </a>

        <?php else: ?>

            <a href="login.php" class="btn">
                Sign In →
            </a>

            <a href="register.php" class="btn secondary">
                Create Account
            </a>

        <?php endif; ?>

    </div>

    <p style="margin-top:25px; color:#64748b;">
        ✓ Instant evaluation &nbsp;
        ✓ Timed assessments &nbsp;
        ✓ Result history
    </p>
</section>

<?php require "includes/footer.php"; ?>
```