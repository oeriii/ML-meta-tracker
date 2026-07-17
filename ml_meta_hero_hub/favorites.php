<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/auth.php";
require_login();

$page_title = "My Favorites";
$active = "favorites";

// Pull the current patch's stats alongside each favorited hero, if available
$stmt = mysqli_prepare($conn, "
    SELECT h.hero_id, h.hero_name, h.difficulty, r.role_name, r.role_icon,
           hs.win_rate, hs.pick_rate, hs.ban_rate, hs.tier_grade, hs.rank_tier
    FROM favorites f
    JOIN heroes h ON f.hero_id = h.hero_id
    JOIN roles r ON h.role_id = r.role_id
    LEFT JOIN hero_stats hs ON hs.hero_id = h.hero_id
        AND hs.rank_tier = 'Mythic'
        AND hs.patch_id = (SELECT patch_id FROM patches WHERE status = 'Current' LIMIT 1)
    WHERE f.user_id = ?
    ORDER BY f.created_at DESC
");
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$favorites = mysqli_stmt_get_result($stmt);

include __DIR__ . "/includes/header.php";
?>

<div class="page-head">
    <h1>My Favorite Heroes</h1>
    <p>Your saved heroes for quick reference before locking in.</p>
</div>

<?php if (mysqli_num_rows($favorites) === 0): ?>
    <div class="empty-state">
        <div class="big">⭐</div>
        <p>You haven't favorited any heroes yet. Head to the Meta Board and tap the star on a hero card.</p>
    </div>
<?php else: ?>
    <div class="hero-grid">
        <?php while ($h = mysqli_fetch_assoc($favorites)): ?>
            <div class="hero-card">
                <?php if ($h["tier_grade"]): ?>
                    <div class="tier-hex tier-<?= htmlspecialchars($h["tier_grade"]) ?>"><?= htmlspecialchars($h["tier_grade"]) ?></div>
                <?php endif; ?>
                <div class="role-tag"><?= htmlspecialchars($h["role_icon"]) ?> <?= htmlspecialchars($h["role_name"]) ?></div>
                <h3><?= htmlspecialchars($h["hero_name"]) ?></h3>

                <?php if ($h["win_rate"] !== null): ?>
                    <div class="stat-row"><span>Win Rate</span><span class="stat-num"><?= number_format($h["win_rate"], 1) ?>%</span></div>
                    <div class="stat-row"><span>Pick Rate</span><span class="stat-num"><?= number_format($h["pick_rate"], 1) ?>%</span></div>
                    <div class="stat-row"><span>Ban Rate</span><span class="stat-num"><?= number_format($h["ban_rate"], 1) ?>%</span></div>
                <?php else: ?>
                    <div class="stat-row"><span>Difficulty</span><span class="stat-num"><?= htmlspecialchars($h["difficulty"]) ?></span></div>
                    <div class="stat-row"><span>Stats</span><span class="stat-num">Not on current patch</span></div>
                <?php endif; ?>

                <div class="card-actions">
                    <a href="hero_detail.php?id=<?= $h["hero_id"] ?>" class="btn btn-primary" style="flex:1;">View</a>
                    <form class="inline" method="POST" action="toggle_favorite.php">
                        <input type="hidden" name="hero_id" value="<?= $h["hero_id"] ?>">
                        <input type="hidden" name="redirect" value="favorites.php">
                        <button type="submit" class="btn btn-gold" title="Remove from favorites">★</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
