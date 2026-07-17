<?php
// ---- Database connection settings ----
// Update these if your XAMPP/MySQL setup uses different credentials.
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "ml_meta_hero_hub";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
