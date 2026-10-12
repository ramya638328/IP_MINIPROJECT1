<?php
require_once "includes/auth.php";
requireLogin();
require_once "config/db.php";

$resultId = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("
    SELECT r.*, q.quiz_name, q.category, q.total_questions
    FROM results r
    JOIN quizzes q ON q.quiz_id = r.quiz_id
    WHERE r.result_id = ? AND r.user_id = ?
");
$stmt->bind_param("ii", $resultId, $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$result) {
    header("Location: results.php");
    exit;
}

$total = max(1, (int)$result["total_questions"]);
$percentage = round(((int)$result["correct_answers"] / $total) * 100);

$pageTitle = "Quiz Result";
include "includes/header.php";
?>
<section class="result-page">
    <div class="result-card">
        <div class="result-icon"><?= $percentage >= 50 ? "✓" : "!" ?></div>
        <div class="eyebrow">QUIZ COMPLETED</div>
        <h1><?= htmlspecialchars($result['quiz_name']) ?></h1>
        <p class="result-date"><?= htmlspecialchars($result['category']) ?> • <?= date("d M Y, h:i A", strtotime($result['date'])) ?></p>

        <div class="score-circle">
            <strong><?= $percentage ?>%</strong>
            <span>Score</span>
        </div>

        <div class="result-stats">
            <div><strong><?= (int)$result['correct_answers'] ?></strong><span>Correct</span></div>
            <div><strong><?= (int)$result['incorrect_answers'] ?></strong><span>Incorrect</span></div>
            <div><strong><?= $total ?></strong><span>Total</span></div>
        </div>

        <div class="result-message">
            <?= $percentage >= 80 ? "Excellent performance! Keep up the great work." : ($percentage >= 50 ? "Good job! Keep practicing to improve your score." : "Keep practicing. Every attempt helps you improve.") ?>
        </div>

        <div class="hero-actions center">
            <a class="btn btn-primary" href="dashboard.php">Take Another Quiz</a>
            <a class="btn btn-light" href="results.php">View History</a>
        </div>
    </div>
</section>
<?php include "includes/footer.php"; ?>
