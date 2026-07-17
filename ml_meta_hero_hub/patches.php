<?php
require_once __DIR__ . "/config/db.php";
require_once __DIR__ . "/includes/auth.php";
require_login();

$page_title = "Patch Notes";
$active = "patches";

$patches = mysqli_query($conn, "
    SELECT patch_id, patch_version, release_date, patch_notes, status
    FROM patches
    ORDER BY release_date DESC
");

include __DIR__ . "/includes/header.php";
?>

<div class="page-head">
    <h1>Patch Notes</h1>
    <p>See how balance changes have reshaped the meta over time.</p>
</div>

<?php if (mysqli_num_rows($patches) === 0): ?>
    <div class="empty-state">
        <div class="big">📜</div>
        <p>No patch notes have been published yet.</p>
    </div>
<?php else: ?>
    <?php while ($p = mysqli_fetch_assoc($patches)): ?>
        <div class="patch-card">
            <div class="patch-top">
                <h3>Patch <?= htmlspecialchars($p["patch_version"]) ?></h3>
                <span class="patch-status <?= $p["status"] === "Current" ? "current" : "archived" ?>">
                    <?= htmlspecialchars($p["status"]) ?>
                </span>
            </div>
            <div class="date">
                <?= $p["release_date"] ? date("F j, Y", strtotime($p["release_date"])) : "Release date TBA" ?>
            </div>
            <p class="notes"><?= nl2br(htmlspecialchars($p["patch_notes"] ?? "No notes recorded for this patch.")) ?></p>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<?php include __DIR__ . "/includes/footer.php"; ?>
