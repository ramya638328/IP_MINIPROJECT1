<?php
require_once "includes/auth.php";
requireLogin();
require_once "config/db.php";

$stmt = $conn->prepare("
    SELECT r.result_id, r.score, r.correct_answers, r.incorrect_answers, r.date,
           q.quiz_name, q.category, q.total_questions
    FROM results r
    JOIN quizzes q ON q.quiz_id = r.quiz_id
    WHERE r.user_id = ?
    ORDER BY r.date DESC
");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$results = $stmt->get_result();
$stmt->close();

$pageTitle = "My Results";
include "includes/header.php";
?>
<section class="dashboard-head">
    <div>
        <div class="eyebrow">PERFORMANCE</div>
        <h1>My Results</h1>
        <p>Review your previous quiz attempts and scores.</p>
    </div>
    <a class="btn btn-primary" href="dashboard.php">Take a Quiz →</a>
</section>

<section class="table-card">
    <?php if ($results->num_rows === 0): ?>
        <div class="empty-state">
            <div class="feature-icon">◎</div>
            <h2>No results yet</h2>
            <p>Complete your first quiz to see your performance here.</p>
            <a class="btn btn-primary" href="dashboard.php">Explore Quizzes</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Quiz</th>
                        <th>Category</th>
                        <th>Score</th>
                        <th>Correct</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['quiz_name']) ?></strong></td>
                        <td><span class="category"><?= htmlspecialchars($row['category']) ?></span></td>
                        <td><strong><?= (int)$row['score'] ?>/<?= (int)$row['total_questions'] ?></strong></td>
                        <td><?= (int)$row['correct_answers'] ?> / <?= (int)$row['total_questions'] ?></td>
                        <td><?= date("d M Y, h:i A", strtotime($row['date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php include "includes/footer.php"; ?>
