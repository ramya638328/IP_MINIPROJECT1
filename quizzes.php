
<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT q.quiz_id, q.quiz_name, q.category, q.duration,
        COUNT(p.question_id) AS question_count
        FROM quizzes q
        LEFT JOIN questions p ON q.quiz_id = p.quiz_id
        GROUP BY q.quiz_id, q.quiz_name, q.category, q.duration";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Available Quizzes</title>
<style>
body{font-family:Arial;background:#f1f5f9;margin:0;padding:20px}
h1{color:#1e3a8a}
.card{background:white;padding:22px;margin:18px 0;border-radius:12px;box-shadow:0 4px 15px #0001}
.btn{display:inline-block;background:#2563eb;color:white;padding:12px 20px;text-decoration:none;border-radius:8px}
p{line-height:1.6}
</style>
</head>
<body>
<h1>Available Quizzes</h1>
<p>Choose a quiz to attend.</p>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while ($q = $result->fetch_assoc()): ?>
        <div class="card">
            <h2><?= htmlspecialchars($q["quiz_name"]) ?></h2>
            <p>Category: <?= htmlspecialchars($q["category"]) ?></p>
            <p>Duration: <?= (int)$q["duration"] ?> minutes</p>
            <p>Questions: <?= (int)$q["question_count"] ?></p>

            <?php if ((int)$q["question_count"] > 0): ?>
                <a class="btn" href="quiz.php?quiz_id=<?= (int)$q["quiz_id"] ?>">
                    Attend Quiz
                </a>
            <?php else: ?>
                <p>No questions added yet.</p>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No quizzes found in your database.</p>
<?php endif; ?>

<p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
