
<?php
session_start();
require_once "config/db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Please enter your email and password.";
    } else {
        $stmt = $conn->prepare(
            "SELECT user_id, name, email, password, role
             FROM users WHERE email = ? LIMIT 1"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = (int)$user["user_id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In | QuizMaster</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, Helvetica, sans-serif;
    min-height: 100vh;
    background: #f3f6ff;
    color: #172554;
}

.navbar {
    height: 76px;
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 7%;
    border-bottom: 1px solid #e5eaf5;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 25px;
    font-weight: 800;
    color: #142451;
    text-decoration: none;
}

.logo-icon {
    display: grid;
    place-items: center;
    width: 45px;
    height: 45px;
    color: white;
    border-radius: 14px;
    background: linear-gradient(135deg, #2563eb, #5544ee);
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 25px;
}

.nav-links a {
    text-decoration: none;
    color: #475569;
    font-weight: 600;
}

.nav-links a:hover {
    color: #2563eb;
}

.page {
    min-height: calc(100vh - 140px);
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 45px 20px;
    background:
        radial-gradient(circle at 15% 20%, #dce8ff 0, transparent 35%),
        radial-gradient(circle at 90% 80%, #e8e3ff 0, transparent 32%),
        #f7f9ff;
}

.login-layout {
    width: 100%;
    max-width: 1000px;
    display: grid;
    grid-template-columns: 1fr 430px;
    gap: 55px;
    align-items: center;
}

.intro h1 {
    font-size: clamp(36px, 4vw, 52px);
    line-height: 1.15;
    letter-spacing: -1.5px;
    margin: 20px 0;
}

.intro h1 span {
    color: #3165f5;
}

.intro p {
    color: #64748b;
    font-size: 17px;
    line-height: 1.8;
}

.eyebrow {
    color: #3165f5 !important;
    font-size: 12px !important;
    font-weight: 800;
    letter-spacing: 2px;
}

.features {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 28px;
    color: #475569;
    font-size: 13px;
}

.login-card {
    background: white;
    border: 1px solid #e8edf7;
    border-radius: 22px;
    padding: 38px 32px;
    box-shadow: 0 20px 60px rgba(35, 60, 120, 0.12);
}

.card-icon {
    width: 56px;
    height: 56px;
    display: grid;
    place-items: center;
    border-radius: 17px;
    background: #eaf0ff;
    color: #285bea;
    font-size: 27px;
    margin-bottom: 22px;
}

.login-card h2 {
    font-size: 29px;
    margin-bottom: 10px;
    color: #172554;
}

.subtitle {
    color: #718096;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 26px;
}

.error {
    background: #fff1f2;
    color: #be123c;
    border: 1px solid #fecdd3;
    border-radius: 9px;
    padding: 12px;
    font-size: 14px;
    margin-bottom: 18px;
}

.field {
    margin-bottom: 19px;
}

.field label {
    display: block;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 9px;
    color: #334155;
}

.field input {
    width: 100%;
    height: 49px;
    border: 1px solid #dbe2ee;
    border-radius: 10px;
    padding: 0 14px;
    font-size: 15px;
    color: #172554;
    outline: none;
    background: #fff;
}

.field input:focus {
    border-color: #3165f5;
    box-shadow: 0 0 0 3px #3165f51c;
}

.password-box {
    position: relative;
}

.password-box input {
    padding-right: 60px;
}

.show-password {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    color: #3165f5;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
}

.signin-btn {
    width: 100%;
    height: 51px;
    margin-top: 6px;
    border: none;
    border-radius: 10px;
    color: white;
    background: linear-gradient(110deg, #2563eb, #5145ed);
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 9px 20px #315ef52b;
    transition: transform .2s, box-shadow .2s;
}

.signin-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px #315ef540;
}

.register-text {
    margin-top: 24px;
    text-align: center;
    color: #64748b;
    font-size: 14px;
}

.register-text a {
    color: #285bea;
    font-weight: 700;
    text-decoration: none;
}

.register-text a:hover {
    text-decoration: underline;
}

.footer {
    text-align: center;
    padding: 22px 10px;
    color: #94a3b8;
    font-size: 13px;
    background: white;
}

@media (max-width: 800px) {
    .navbar {
        padding: 0 20px;
    }

    .login-layout {
        max-width: 450px;
        grid-template-columns: 1fr;
        gap: 28px;
    }

    .intro {
        text-align: center;
    }

    .intro h1 {
        font-size: 36px;
    }

    .features {
        justify-content: center;
    }

    .login-card {
        padding: 28px 22px;
    }
}

@media (max-width: 400px) {
    .nav-links {
        gap: 12px;
        font-size: 13px;
    }

    .logo {
        font-size: 21px;
    }
}
</style>
</head>

<body>

<header class="navbar">
    <a href="index.php" class="logo">
        <span class="logo-icon">Q</span>
        QuizMaster
    </a>

    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="register.php">Create Account</a>
    </nav>
</header>

<main class="page">
    <div class="login-layout">

        <section class="intro">
            <p class="eyebrow">SMART • SIMPLE • INTERACTIVE</p>

            <h1>
                Welcome<br>
                back to <span>QuizMaster.</span>
            </h1>

            <p>
                Sign in to attend quizzes, test your knowledge,
                track your scores and continue improving every day.
            </p>

            <div class="features">
                <span>✓ Timed quizzes</span>
                <span>✓ Instant scores</span>
                <span>✓ Result history</span>
            </div>
        </section>

        <section class="login-card">
            <div class="card-icon">Q</div>

            <h2>Sign in</h2>

            <p class="subtitle">
                Enter your account details to continue.
            </p>

            <?php if ($error !== ""): ?>
                <div class="error">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="field">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="<?= htmlspecialchars($_POST["email"] ?? "", ENT_QUOTES, "UTF-8") ?>"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Password</label>

                    <div class="password-box">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="show-password"
                            id="togglePassword"
                        >SHOW</button>
                    </div>
                </div>

                <button type="submit" class="signin-btn">
                    Sign In →
                </button>
            </form>

            <p class="register-text">
                Don't have an account?
                <a href="register.php">Create Account</a>
            </p>
        </section>

    </div>
</main>

<footer class="footer">
    © 2026 QuizMaster. Learn, practise and grow.
</footer>

<script>
const passwordInput = document.getElementById("password");
const toggleButton = document.getElementById("togglePassword");

toggleButton.addEventListener("click", function () {
    const hidden = passwordInput.type === "password";

    passwordInput.type = hidden ? "text" : "password";
    toggleButton.textContent = hidden ? "HIDE" : "SHOW";
});
</script>

</body>
</html>
