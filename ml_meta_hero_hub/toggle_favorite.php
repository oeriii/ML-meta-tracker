<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/auth.php";
require_login();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hero_id  = (int) ($_POST["hero_id"] ?? 0);
    $user_id  = $_SESSION["user_id"];
    $redirect = $_POST["redirect"] ?? "dashboard.php";

    // Only allow redirecting back to a local page (no host, no scheme) — avoids open redirects.
    if (!preg_match('#^[a-zA-Z0-9_\-]+\.php(\?[a-zA-Z0-9_\-\.=&%+]*)?$#', $redirect)) {
        $redirect = "dashboard.php";
    }

    if ($hero_id > 0) {
        // Check if this hero is already favorited by this user
        $stmt = mysqli_prepare($conn, "SELECT favorite_id FROM favorites WHERE user_id = ? AND hero_id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $hero_id);
        mysqli_stmt_execute($stmt);
        $existing = mysqli_stmt_get_result($stmt)->fetch_assoc();

        if ($existing) {
            // Already favorited -> remove it
            $del = mysqli_prepare($conn, "DELETE FROM favorites WHERE favorite_id = ?");
            mysqli_stmt_bind_param($del, "i", $existing["favorite_id"]);
            mysqli_stmt_execute($del);
        } else {
            // Not favorited yet -> add it
            $ins = mysqli_prepare($conn, "INSERT INTO favorites (user_id, hero_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($ins, "ii", $user_id, $hero_id);
            mysqli_stmt_execute($ins);
        }
    }

    header("Location: " . $redirect);
    exit;
}

header("Location: dashboard.php");
exit;
