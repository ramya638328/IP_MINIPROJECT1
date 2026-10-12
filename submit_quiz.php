<?php
require_once "includes/auth.php";
requireLogin();
require_once "config/db.php";

$quizId = (int)($_POST["quiz_id"] ?? 0);
$answers = $_POST["answers"] ?? [];

if ($quizId <= 0) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("SELECT question_id, correct_answer FROM questions WHERE quiz_id = ?");
$stmt->bind_param("i", $quizId);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$correct = 0;
foreach ($rows as $row) {
    $given = strtoupper($answers[$row["question_id"]] ?? "");
    if ($given !== "" && $given === strtoupper($row["correct_answer"])) {
        $correct++;
    }
}

$total = count($rows);
$incorrect = $total - $correct;

$stmt = $conn->prepare("INSERT INTO results (user_id, quiz_id, score, correct_answers, incorrect_answers) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iiiii", $_SESSION["user_id"], $quizId, $correct, $correct, $incorrect);
$stmt->execute();
$resultId = $conn->insert_id;
$stmt->close();

header("Location: result.php?id=" . $resultId);
exit;
?>