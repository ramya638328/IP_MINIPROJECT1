<?php
require_once "includes/auth.php";
requireAdmin();
require_once "config/db.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $quizName = trim($_POST["quiz_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $duration = (int)($_POST["duration"] ?? 0);

    if ($quizName === "" || $category === "" || $duration < 1) {
        $error = "Enter a quiz name, category and valid duration.";
    } else {
        $stmt = $conn->prepare("INSERT INTO quizzes (quiz_name, category, duration, total_questions) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("ssi", $quizName, $category, $duration);
        if ($stmt->execute()) {
            $message = "Quiz created successfully. Add questions using phpMyAdmin or extend the admin module.";
        } else {
            $error = "Unable to create quiz.";
        }
        $stmt->close();
    }
}

$users = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()["c"];
$quizzes = $conn->query("SELECT COUNT(*) AS c FROM quizzes")->fetch_assoc()["c"];
$results = $conn->query("SELECT COUNT(*) AS c FROM results")->fetch_assoc()["c"];

$pageTitle = "Admin Panel";
include "includes/header.php";
?>
<section class="dashboard-head">
    <div>
        <div class="eyebrow">ADMINISTRATION</div>
        <h1>Quiz Management</h1>
        <p>Monitor the application and create new quizzes.</p>
    </div>
</section>

<div class="admin-stats">
    <div><span>Users</span><strong><?= (int)$users ?></strong></div>
    <div><span>Quizzes</span><strong><?= (int)$quizzes ?></strong></div>
    <div><span>Attempts</span><strong><?= (int)$results ?></strong></div>
</div>

<section class="admin-grid">
    <div class="table-card admin-form-card">
        <div class="eyebrow">CREATE QUIZ</div>
        <h2>Add a new quiz</h2>
        <?php if ($message): ?><div class="alert success"><?= htmlspecialchars($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <label>Quiz Name
                <input name="quiz_name" placeholder="e.g. Web Technology Basics" required>
            </label>
            <label>Category
                <input name="category" placeholder="e.g. Computer Science" required>
            </label>
            <label>Duration (minutes)
                <input type="number" name="duration" min="1" value="10" required>
            </label>
            <button class="btn btn-primary" type="submit">Create Quiz</button>
        </form>
    </div>
</section>
<?php include "includes/footer.php"; ?>
