<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/auth.php";
require_login();

$page_title = "Meta Board";
$active = "dashboard";
$base = "";

// ---- filter inputs ----
$role_filter  = $_GET["role"] ?? "";
$patch_filter = $_GET["patch"] ?? "";
$rank_filter  = $_GET["rank"] ?? "";
$sort         = $_GET["sort"] ?? "win_rate";
$search       = trim($_GET["q"] ?? "");

$allowed_sorts = ["win_rate", "pick_rate", "ban_rate", "hero_name"];
if (!in_array($sort, $allowed_sorts)) $sort = "win_rate";

// ---- build query against hero_stats/heroes/roles/patches directly
// (so filtering can reach into any patch, not just the "current" one the view exposes) ----
$sql = "SELECT h.hero_id, h.hero_name, h.image_url, r.role_name, r.role_icon,
               hs.rank_tier, hs.win_rate, hs.pick_rate, hs.ban_rate, hs.tier_grade,
               p.patch_version
        FROM hero_stats hs
        JOIN heroes h ON hs.hero_id = h.hero_id
        JOIN roles r ON h.role_id = r.role_id
        JOIN patches p ON hs.patch_id = p.patch_id
        WHERE 1=1";
$params = [];
$types = "";

if ($patch_filter !== "") {
    $sql .= " AND p.patch_version = ?";
    $params[] = $patch_filter;
    $types .= "s";
} else {
    $sql .= " AND p.status = 'Current'";
}

if ($role_filter !== "") {
    $sql .= " AND r.role_name = ?";
    $params[] = $role_filter;
    $types .= "s";
}

if ($rank_filter !== "") {
    $sql .= " AND hs.rank_tier = ?";
    $params[] = $rank_filter;
    $types .= "s";
}

if ($search !== "") {
    $sql .= " AND h.hero_name LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= "s";
}

$sql .= " ORDER BY " . ($sort === "hero_name" ? "h.hero_name ASC" : "hs.$sort DESC");

$stmt = mysqli_prepare($conn, $sql);
if ($types !== "") {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$heroes = mysqli_stmt_get_result($stmt);

// lookups for filter dropdowns
$roles = mysqli_query($conn, "SELECT role_name FROM roles ORDER BY role_name");
$patches = mysqli_query($conn, "SELECT patch_version, status FROM patches ORDER BY release_date DESC");
$ranks = mysqli_query($conn, "SELECT DISTINCT rank_tier FROM hero_stats");

// current user's favorites, so hearts render filled
$fav_ids = [];
$fstmt = mysqli_prepare($conn, "SELECT hero_id FROM favorites WHERE user_id = ?");
mysqli_stmt_bind_param($fstmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($fstmt);
$fres = mysqli_stmt_get_result($fstmt);
while ($row = mysqli_fetch_assoc($fres)) { $fav_ids[] = $row["hero_id"]; }

include __DIR__ . "/includes/header.php";
?>

<div class="page-head">
    <h1>Current Meta Board</h1>
    <p>Filter by role, rank bracket, or patch to see who's dominating right now.</p>
</div>

<form class="filters" method="GET">
    <select name="role">
        <option value="">All Roles</option>
        <?php mysqli_data_seek($roles, 0); while ($r = mysqli_fetch_assoc($roles)): ?>
            <option value="<?= htmlspecialchars($r["role_name"]) ?>" <?= $role_filter === $r["role_name"] ? "selected" : "" ?>><?= htmlspecialchars($r["role_name"]) ?></option>
        <?php endwhile; ?>
    </select>

    <select name="rank">
        <option value="">All Ranks</option>
        <?php mysqli_data_seek($ranks, 0); while ($rk = mysqli_fetch_assoc($ranks)): ?>
            <option value="<?= htmlspecialchars($rk["rank_tier"]) ?>" <?= $rank_filter === $rk["rank_tier"] ? "selected" : "" ?>><?= htmlspecialchars($rk["rank_tier"]) ?></option>
        <?php endwhile; ?>
    </select>

    <select name="patch">
        <option value="">Current Patch</option>
        <?php mysqli_data_seek($patches, 0); while ($p = mysqli_fetch_assoc($patches)): ?>
            <option value="<?= htmlspecialchars($p["patch_version"]) ?>" <?= $patch_filter === $p["patch_version"] ? "selected" : "" ?>><?= htmlspecialchars($p["patch_version"]) ?> <?= $p["status"] === "Current" ? "(current)" : "" ?></option>
        <?php endwhile; ?>
    </select>

    <select name="sort">
        <option value="win_rate" <?= $sort === "win_rate" ? "selected" : "" ?>>Sort: Win Rate</option>
        <option value="pick_rate" <?= $sort === "pick_rate" ? "selected" : "" ?>>Sort: Pick Rate</option>
        <option value="ban_rate" <?= $sort === "ban_rate" ? "selected" : "" ?>>Sort: Ban Rate</option>
        <option value="hero_name" <?= $sort === "hero_name" ? "selected" : "" ?>>Sort: Name</option>
    </select>

    <input type="text" name="q" placeholder="Search hero..." value="<?= htmlspecialchars($search) ?>">

    <button type="submit">Apply</button>
    <a href="dashboard.php" class="btn reset" style="align-self:center;">Reset</a>
</form>

<?php if (mysqli_num_rows($heroes) === 0): ?>
    <div class="empty-state">
        <div class="big">🕹️</div>
        <p>No heroes match those filters. Try widening your search.</p>
    </div>
<?php else: ?>
    <div class="hero-grid">
        <?php while ($h = mysqli_fetch_assoc($heroes)): ?>
            <div class="hero-card">
                <div class="hero-thumb">
                    <img src="<?= htmlspecialchars($h["image_url"]) ?>" alt="<?= htmlspecialchars($h["hero_name"]) ?>" loading="lazy" onerror="this.closest('.hero-thumb').classList.add('img-missing'); this.remove();">
                </div>
                <div class="tier-hex tier-<?= htmlspecialchars($h["tier_grade"]) ?>"><?= htmlspecialchars($h["tier_grade"]) ?></div>
                <div class="role-tag"><?= htmlspecialchars($h["role_icon"]) ?> <?= htmlspecialchars($h["role_name"]) ?></div>
                <h3><?= htmlspecialchars($h["hero_name"]) ?></h3>

                <div class="stat-row"><span>Win Rate</span><span class="stat-num"><?= number_format($h["win_rate"], 1) ?>%</span></div>
                <div class="stat-row"><span>Pick Rate</span><span class="stat-num"><?= number_format($h["pick_rate"], 1) ?>%</span></div>
                <div class="stat-row"><span>Ban Rate</span><span class="stat-num"><?= number_format($h["ban_rate"], 1) ?>%</span></div>
                <div class="stat-row"><span>Rank</span><span class="stat-num"><?= htmlspecialchars($h["rank_tier"]) ?> · <?= htmlspecialchars($h["patch_version"]) ?></span></div>

                <div class="card-actions">
                    <a href="hero_detail.php?id=<?= $h["hero_id"] ?>" class="btn btn-primary" style="flex:1;">View</a>
                    <form class="inline" method="POST" action="toggle_favorite.php">
                        <input type="hidden" name="hero_id" value="<?= $h["hero_id"] ?>">
                        <input type="hidden" name="redirect" value="dashboard.php?<?= htmlspecialchars($_SERVER['QUERY_STRING']) ?>">
                        <button type="submit" class="btn <?= in_array($h["hero_id"], $fav_ids) ? "btn-gold" : "" ?>"><?= in_array($h["hero_id"], $fav_ids) ? "★" : "☆" ?></button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
