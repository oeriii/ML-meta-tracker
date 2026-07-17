<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/auth.php";

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === "" || $password === "") {
        $error = "Please enter both username and password.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT user_id, full_name, role, password FROM users WHERE username = ? AND status = 'Active'");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        $password_ok = false;
        if ($user) {
            if (password_verify($password, $user["password"])) {
                $password_ok = true;
            } elseif ($user["password"] === $password) {
                $password_ok = true;
            }
        }

        if ($password_ok) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid username or password. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ML Meta Hero Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
<!-- <div class="ticker">
    <span>⚡ ML META HERO HUB <span class="tag"></span></span>
    <span>DRAFT PICK · BAN · WIN</span>
</div> -->
<nav class="navbarlogin">
    <a href="dashboard.php" class="brand"><img src="assets/img/logo.png" alt="ML Meta Hero Hub Logo"><h3>META HERO HUB</h3></a>
</nav>



<div class="auth-wrap">
    <div class="auth-box">
        <h1>Welcome back, Jungler</h1>
        <p class="sub">Sign in to check the current meta.</p>

        <?php if ($error): ?>
            <div class="error-msg">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Log In</button>
        </form>

        <div class="demo-creds">
            <strong>Demo accounts</strong><br>
            Admin: admin / admin123<br>
            Player: shadowstrike / player123
        </div>

        <p class="signup-link">Don't have an account? <a href="register.php"><strong>Sign up</strong></a></p>
    </div>
</div>
</body>
</html>
