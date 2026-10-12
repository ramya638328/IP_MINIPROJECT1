
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION["name"] ?? "Student";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Online Quiz Application</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f1f5f9;
    color: #1e293b;
}

.navbar {
    background: #1e3a8a;
    color: white;
    padding: 20px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.logo {
    font-size: 22px;
    font-weight: bold;
}

.navbar a {
    color: white;
    text-decoration: none;
    margin-left: 18px;
}

.navbar a:hover {
    color: #bfdbfe;
}

.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}

.welcome {
    background: white;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
}

.welcome h1 {
    color: #1e3a8a;
    margin-bottom: 12px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 25px;
}

.card {
    background: white;
    padding: 35px 22px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
}

.card h2 {
    color: #1e3a8a;
    margin: 18px 0;
    font-size: 22px;
}

.card p {
    line-height: 1.6;
    margin-bottom: 22px;
    min-height: 50px;
}

.icon {
    font-size: 35px;
}

.btn {
    display: inline-block;
    background: #2563eb;
    color: white;
    text-decoration: none;
    padding: 14px 22px;
    border-radius: 8px;
    font-weight: bold;
}

.btn:hover {
    background: #1d4ed8;
}

footer {
    text-align: center;
    padding: 25px;
    color: #64748b;
}

@media (max-width: 800px) {
    .cards {
        grid-template-columns: 1fr;
    }

    .navbar {
        flex-direction: column;
    }

    .navbar nav {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
    }

    .navbar a {
        margin-left: 0;
    }
}
</style>
</head>

<body>

<header class="navbar">
    <div class="logo">Online Quiz Application</div>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="quizzes.php">Quizzes</a>
        <a href="results.php">My Results</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="container">

    <section class="welcome">
        <h1>Welcome, <?= htmlspecialchars($name) ?>!</h1>
        <p>Select a quiz, answer the questions, and check your performance.</p>
    </section>

    <section class="cards">

        <div class="card">
            <div class="icon">📝</div>
            <h2>Available Quizzes</h2>
            <p>Explore available quizzes and test your knowledge.</p>
            <a href="quizzes.php" class="btn">Explore Quizzes</a>
        </div>

        <div class="card">
            <div class="icon">📊</div>
            <h2>My Results</h2>
            <p>View your previous quiz scores and performance.</p>
            <a href="results.php" class="btn">View Results</a>
        </div>

        <div class="card">
            <div class="icon">🎯</div>
            <h2>Start Quiz</h2>
            <p>Choose a quiz and begin your assessment.</p>
            <a href="quizzes.php" class="btn">Start Now</a>
        </div>

    </section>

</main>

<footer>
    © 2026 Online Quiz Application
</footer>

</body>
</html>
