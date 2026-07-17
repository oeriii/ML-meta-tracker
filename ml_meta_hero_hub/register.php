<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/auth.php";

if (is_logged_in()) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username  = trim($_POST["username"] ?? "");
    $full_name = trim($_POST["full_name"] ?? "");
    $password  = trim($_POST["password"] ?? "");
    $confirm   = trim($_POST["confirm_password"] ?? "");

    if ($username === "" || $full_name === "" || $password === "" || $confirm === "") {
        $error = "Please fill in all fields.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "That username is already taken.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO users (username, password, full_name, role, status) VALUES (?, ?, ?, 'Player', 'Active')"
            );
            mysqli_stmt_bind_param($insert, "sss", $username, $hashed, $full_name);

            if (mysqli_stmt_execute($insert)) {
                $success = "Account created! You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up — ML Meta Hero Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- <div class="ticker">
    <span>⚡ ML META HERO HUB <span class="tag">// LIVE META TRACKER</span></span>
    <span>DRAFT PICK · BAN · WIN</span>
</div> -->
<div class="auth-wrap">
    <div class="auth-box">
        <h1>Join the roster</h1>
        <p class="sub">Create an account to track the meta.</p>

        <?php if ($error): ?>
            <div class="error-msg">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-msg">✅ <?= htmlspecialchars($success) ?> <a href="login.php">Log in →</a></div>
        <?php else: ?>
        <form method="POST">
            <div class="field">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required autofocus>
            </div>
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="field">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Create Account</button>
        </form>
        <?php endif; ?>

        <div class="demo-creds">
            Already have an account? <a href="login.php"><strong>Log in</strong></a>
        </div>
    </div>
</div>
</body>
</html>
