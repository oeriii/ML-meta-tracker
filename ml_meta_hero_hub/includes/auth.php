<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in() {
    return isset($_SESSION["user_id"]);
}


function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function current_user() {
    return [
        "user_id"   => $_SESSION["user_id"]   ?? null,
        "full_name" => $_SESSION["full_name"] ?? null,
        "role"      => $_SESSION["role"]      ?? null,
    ];
}
