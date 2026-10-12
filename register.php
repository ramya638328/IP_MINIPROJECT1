<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
require_once "config/db.php";

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $error = "Enter a valid name, email and password of at least 6 characters.";
    } else {
        $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;
        $check->close();

        if ($exists) {
            $error = "An account with this email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
            $stmt->bind_param("sss", $name, $email, $hash);
            if ($stmt->execute()) {
                header("Location: login.php?registered=1");
                exit;
            }
            $error = "Registration failed. Please try again.";
            $stmt->close();
        }
    }
}

$pageTitle = "Create Account";
include "includes/header.php";
?>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-side">
            <div class="brand brand-white"><span class="brand-mark">Q</span><span>QuizMaster</span></div>
            <h2>Build your knowledge, one quiz at a time.</h2>
            <p>Join the platform to take quizzes, track results and improve your performance.</p>
            <div class="side-points">
                <span>✓ Secure account</span>
                <span>✓ Instant results</span>
                <span>✓ Personal result history</span>
            </div>
        </div>
        <div class="auth-form">
            <div class="form-title">
                <div class="eyebrow">WELCOME</div>
                <h1>Create your account</h1>
                <p>Start your quiz journey in less than a minute.</p>
            </div>
            <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="post" novalidate>
                <label>Full Name
                    <input type="text" name="name" placeholder="Enter your name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </label>
                <label>Email Address
                    <input type="email" name="email" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </label>
                <label>Password
                    <input type="password" name="password" placeholder="Minimum 6 characters" required>
                </label>
                <button class="btn btn-primary full" type="submit">Create Account</button>
            </form>
            <p class="switch">Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </div>
</div>
<?php include "includes/footer.php"; ?>
