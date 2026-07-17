<?php
// Expects $page_title and $active to be set by the including page.
$page_title = $page_title ?? "ML Meta Hero Hub";
$active = $active ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> — ML Meta Hero Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<!-- <div class="ticker">
    <span>⚡ ML META HERO HUB <span class="tag">// LIVE META TRACKER</span></span>
    <span>DRAFT PICK · BAN · WIN</span>
</div> -->

<nav class="navbar">
    <a href="dashboard.php" class="brand"><img src="assets/img/logo.png" alt="ML Meta Hero Hub Logo"><h3>META HERO HUB</h3></a>
    <div class="nav-links">
        <a href="dashboard.php" class="<?= $active === "dashboard" ? "active" : "" ?>">Meta Board</a>
        <a href="favorites.php" class="<?= $active === "favorites" ? "active" : "" ?>">Favorites</a>
        <a href="patches.php" class="<?= $active === "patches" ? "active" : "" ?>">Patch Notes</a>
    </div>
    <div class="user-chip">
        <span>👤 <?= htmlspecialchars($_SESSION["full_name"] ?? "") ?> <small>(<?= htmlspecialchars($_SESSION["role"] ?? "") ?>)</small></span>
        <a href="logout.php" class="btn btn-small">Logout</a>
    </div>
</nav>

<main class="page-wrap">
