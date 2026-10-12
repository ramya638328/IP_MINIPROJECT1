
<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$quizId = (int)($_GET["quiz_id"] ?? $_GET["id"] ?? 0);

if ($quizId <= 0) {
    header("Location: quizzes.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT quiz_id, quiz_name, category, duration
     FROM quizzes WHERE quiz_id = ?"
);
$stmt->bind_param("i", $quizId);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$quiz) {
    exit("Quiz not found. <a href='quizzes.php'>Return to quizzes</a>");
}

$stmt = $conn->prepare(
    "SELECT question_id, question, option_a, option_b,
            option_c, option_d
     FROM questions
     WHERE quiz_id = ?
     ORDER BY question_id"
);
$stmt->bind_param("i", $quizId);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($questions)) {
    exit("No questions are available for this quiz. <a href='quizzes.php'>Return to quizzes</a>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($quiz["quiz_name"]) ?> | Online Quiz</title>

<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,sans-serif;background:#f1f5f9;color:#1e293b}
header{background:#1e3a8a;color:white;padding:17px 5%;display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}
header a{color:white;text-decoration:none}
.container{max-width:850px;margin:25px auto;padding:15px}
h1{color:#1e3a8a;line-height:1.3}
.subtitle{color:#64748b;line-height:1.6}
.timer{position:sticky;top:8px;z-index:5;background:#fff1f2;color:#be123c;padding:16px;text-align:center;font-size:20px;font-weight:bold;border-radius:10px;margin:20px 0}
.question{background:white;padding:22px;border-radius:13px;margin:18px 0;box-shadow:0 4px 15px #0000000b}
.question h3{line-height:1.6;margin-top:0}
.option{display:flex;align-items:flex-start;gap:12px;padding:14px;margin:9px 0;border:1px solid #e2e8f0;background:#f8fafc;border-radius:9px;cursor:pointer;line-height:1.5}
.option input{margin-top:4px;transform:scale(1.15)}
.option:has(input:checked){background:#dbeafe;border-color:#2563eb}
button{width:100%;padding:15px;background:#2563eb;color:white;border:0;border-radius:9px;font-size:17px;font-weight:bold;cursor:pointer}
button:hover{background:#1d4ed8}
button:disabled{opacity:.6}
@media(max-width:600px){.container{padding:12px;margin:15px auto}.question{padding:16px}.timer{font-size:18px}}
</style>
</head>

<body>
<header>
    <strong>Online Quiz Application</strong>
    <a href="quizzes.php">← Back to Quizzes</a>
</header>

<main class="container">
    <h1><?= htmlspecialchars($quiz["quiz_name"]) ?></h1>
    <p class="subtitle">
        Category: <?= htmlspecialchars($quiz["category"]) ?> |
        Questions: <?= count($questions) ?>
    </p>

    <div class="timer">
        Time Remaining: <span id="timer">--:--</span>
    </div>

    <form id="quizForm" method="POST" action="submit_quiz.php">
        <input type="hidden" name="quiz_id" value="<?= (int)$quizId ?>">

        <?php foreach ($questions as $index => $q): ?>
        <section class="question">
            <h3>
                <?= ($index + 1) ?>.
                <?= htmlspecialchars($q["question"]) ?>
            </h3>

            <?php
            $options = [
                "A" => "option_a",
                "B" => "option_b",
                "C" => "option_c",
                "D" => "option_d"
            ];
            foreach ($options as $letter => $column):
            ?>
            <label class="option">
                <input
                    type="radio"
                    name="answers[<?= (int)$q["question_id"] ?>]"
                    value="<?= $letter ?>">
                <span>
                    <?= $letter ?>.
                    <?= htmlspecialchars($q[$column]) ?>
                </span>
            </label>
            <?php endforeach; ?>
        </section>
        <?php endforeach; ?>

        <button type="submit" id="submitButton">Submit Quiz</button>
    </form>
</main>

<script>
const form = document.getElementById("quizForm");
const timerElement = document.getElementById("timer");
const submitButton = document.getElementById("submitButton");

let secondsLeft = <?= max(1, (int)$quiz["duration"]) ?> * 60;
let submitted = false;

function updateTimer() {
    const minutes = Math.floor(secondsLeft / 60);
    const seconds = secondsLeft % 60;

    timerElement.textContent =
        String(minutes).padStart(2, "0") + ":" +
        String(seconds).padStart(2, "0");

    if (secondsLeft <= 0) {
        submitted = true;
        submitButton.disabled = true;
        alert("Time is over. Submitting your answers.");
        form.submit();
        return;
    }

    secondsLeft--;
}

form.addEventListener("submit", function(event) {
    if (submitted) {
        event.preventDefault();
        return;
    }

    const confirmed = confirm("Are you sure you want to submit your quiz?");
    if (!confirmed) {
        event.preventDefault();
        return;
    }

    submitted = true;
    submitButton.disabled = true;
    submitButton.textContent = "Submitting...";
});

updateTimer();
const timerInterval = setInterval(() => {
    if (!submitted) {
        updateTimer();
    } else {
        clearInterval(timerInterval);
    }
}, 1000);
</script>
</body>
</html>
