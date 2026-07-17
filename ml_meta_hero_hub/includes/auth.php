<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Returns true if a user is currently logged in.
function is_logged_in() {
    return isset($_SESSION["user_id"]);
}

// Redirects to the login page if no one is logged in.
// Call this at the top of any page that requires authentication.
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

// Convenience accessor for the logged-in user's session data.
function current_user() {
    return [
        "user_id"   => $_SESSION["user_id"]   ?? null,
        "full_name" => $_SESSION["full_name"] ?? null,
        "role"      => $_SESSION["role"]      ?? null,
    ];
}
