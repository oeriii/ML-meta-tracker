<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/auth.php";
require_login();

$hero_id = (int) ($_GET["id"] ?? 0);

// ---- hero + role info ----
$stmt = mysqli_prepare($conn, "
    SELECT h.hero_id, h.hero_name, h.image_url, h.banner_url, h.difficulty, h.overview, r.role_name, r.role_icon
    FROM heroes h
    JOIN roles r ON h.role_id = r.role_id
    WHERE h.hero_id = ?
");
mysqli_stmt_bind_param($stmt, "i", $hero_id);
mysqli_stmt_execute($stmt);
$hero = mysqli_stmt_get_result($stmt)->fetch_assoc();

if (!$hero) {
    header("Location: dashboard.php");
    exit;
}

$page_title = $hero["hero_name"];
$active = "dashboard";

// ---- current patch stats across rank brackets ----
$stmt = mysqli_prepare($conn, "
    SELECT hs.rank_tier, hs.win_rate, hs.pick_rate, hs.ban_rate, hs.tier_grade, p.patch_version
    FROM hero_stats hs
    JOIN patches p ON hs.patch_id = p.patch_id
    WHERE hs.hero_id = ? AND p.status = 'Current'
    ORDER BY hs.win_rate DESC
");
mysqli_stmt_bind_param($stmt, "i", $hero_id);
mysqli_stmt_execute($stmt);
$stats = mysqli_stmt_get_result($stmt);

// ---- builds ----
$stmt = mysqli_prepare($conn, "SELECT build_name, build_type, items, description FROM builds WHERE hero_id = ? ORDER BY build_type");
mysqli_stmt_bind_param($stmt, "i", $hero_id);
mysqli_stmt_execute($stmt);
$builds = mysqli_stmt_get_result($stmt);

// ---- counters (both directions) ----
$stmt = mysqli_prepare($conn, "
    SELECT h2.hero_name AS related_name, hc.counter_type, hc.notes
    FROM hero_counters hc
    JOIN heroes h2 ON hc.related_hero_id = h2.hero_id
    WHERE hc.hero_id = ?
    ORDER BY hc.counter_type
");
mysqli_stmt_bind_param($stmt, "i", $hero_id);
mysqli_stmt_execute($stmt);
$counters = mysqli_stmt_get_result($stmt);

// ---- is this hero already favorited by the current user? ----
$stmt = mysqli_prepare($conn, "SELECT favorite_id FROM favorites WHERE user_id = ? AND hero_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $_SESSION["user_id"], $hero_id);
mysqli_stmt_execute($stmt);
$is_fav = mysqli_stmt_get_result($stmt)->fetch_assoc() ? true : false;

include __DIR__ . "/includes/header.php";
?>

<a href="dashboard.php" class="btn btn-small" style="margin-bottom:16px; display:inline-block;">&larr; Back to Meta Board</a>

<?php $banner = $hero["banner_url"] ?: $hero["image_url"]; ?>
<div class="hero-header" style="background-image:url('<?= htmlspecialchars($banner) ?>');">
    <div class="hero-header-overlay"></div>
    <div class="hero-header-content">
        <span class="badge"><?= htmlspecialchars($hero["role_icon"]) ?> <?= htmlspecialchars($hero["role_name"]) ?></span>
        <span class="badge">Difficulty: <?= htmlspecialchars($hero["difficulty"]) ?></span>
        <h1 style="margin-top:10px;"><?= htmlspecialchars($hero["hero_name"]) ?></h1>
        <p class="overview"><?= htmlspecialchars($hero["overview"]) ?></p>
    </div>
    <form method="POST" action="toggle_favorite.php">
        <input type="hidden" name="hero_id" value="<?= $hero["hero_id"] ?>">
        <input type="hidden" name="redirect" value="hero_detail.php?id=<?= $hero["hero_id"] ?>">
        <button type="submit" class="btn <?= $is_fav ? "btn-gold" : "" ?>">
            <?= $is_fav ? "★ Favorited" : "☆ Add to Favorites" ?>
        </button>
    </form>
</div>

<div class="section">
    <h2>Current Meta Stats</h2>
    <?php if (mysqli_num_rows($stats) === 0): ?>
        <p style="color:var(--text-dim); font-size:13px;">No stats recorded for this hero on the current patch yet.</p>
    <?php else: ?>
        <table class="stats-table">
            <thead>
                <tr><th>Rank</th><th>Patch</th><th>Win Rate</th><th>Pick Rate</th><th>Ban Rate</th><th>Tier</th></tr>
            </thead>
            <tbody>
                <?php while ($s = mysqli_fetch_assoc($stats)): ?>
                <tr>
                    <td><?= htmlspecialchars($s["rank_tier"]) ?></td>
                    <td><?= htmlspecialchars($s["patch_version"]) ?></td>
                    <td><?= number_format($s["win_rate"], 1) ?>%</td>
                    <td><?= number_format($s["pick_rate"], 1) ?>%</td>
                    <td><?= number_format($s["ban_rate"], 1) ?>%</td>
                    <td><span class="tier-hex tier-<?= htmlspecialchars($s["tier_grade"]) ?>" style="position:static; display:inline-flex;"><?= htmlspecialchars($s["tier_grade"]) ?></span></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="section">
    <h2>Recommended Builds</h2>
    <?php if (mysqli_num_rows($builds) === 0): ?>
        <p style="color:var(--text-dim); font-size:13px;">No builds published for this hero yet.</p>
    <?php else: ?>
        <?php while ($b = mysqli_fetch_assoc($builds)): ?>
        <div class="build-card">
            <span class="build-type"><?= htmlspecialchars($b["build_type"]) ?></span>
            <h4 style="margin-top:8px;"><?= htmlspecialchars($b["build_name"]) ?></h4>
            <div class="items"><?= htmlspecialchars($b["items"]) ?></div>
            <p style="font-size:13px; color:var(--text-dim);"><?= htmlspecialchars($b["description"]) ?></p>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<div class="section">
    <h2>Counters &amp; Matchups</h2>
    <?php if (mysqli_num_rows($counters) === 0): ?>
        <p style="color:var(--text-dim); font-size:13px;">No matchup data recorded for this hero yet.</p>
    <?php else: ?>
        <div class="counter-grid">
            <?php while ($c = mysqli_fetch_assoc($counters)): ?>
            <div class="counter-card">
                <span class="ctype <?= $c["counter_type"] === "Strong Against" ? "strong" : "weak" ?>"><?= htmlspecialchars($c["counter_type"]) ?></span>
                <h4><?= htmlspecialchars($c["related_name"]) ?></h4>
                <p><?= htmlspecialchars($c["notes"]) ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . "/includes/footer.php"; ?>
