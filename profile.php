<?php
require_once 'db.php';
require_once 'utils.php';


$user_id = $_SESSION['user_id'] ?? 1;

// ইউজার তথ্য
$stmt = $conn->prepare("SELECT id, profilename, email, userlevel FROM usersapp WHERE email=?");
$stmt->bind_param("s", $usr);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// মোট XP
$xpResult = $conn->query("SELECT SUM(xp) as total FROM user_xp_log WHERE user_id = $user_id");
$totalXP = $xpResult->fetch_assoc()['total'] ?? 0;

// লেভেল ও প্রগ্রেস
$level = calculateLevel($totalXP);
$progress = levelProgressPercent($totalXP);

// অর্জিত ব্যাজ
$badgeQuery = "
SELECT b.name, b.description, b.icon_url
FROM badges b
JOIN user_badges ub ON b.id = ub.badge_id
WHERE ub.email= '$usr'
";
$badges = $conn->query($badgeQuery);
?>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <title>আমার প্রোফাইল</title>
  <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>
  <div class="profile-card">
    <h2>👤 প্রোফাইল</h2>
    <p><strong>নাম:</strong> <?= htmlspecialchars($user['profilename']) ?></p>
    <p><strong>ইমেইল:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>লেভেল:</strong> <?= $level ?></p>
    <p><strong>মোট XP:</strong> <?= $totalXP ?> XP</p>

    <div class="progress">
      <div class="progress-bar" style="width: <?= $progress ?>%;">
        <?= $progress ?>%
      </div>
    </div>

    <h3>🎖️ আমার অর্জিত ব্যাজসমূহ</h3>
    <div class="badges">
      <?php while ($badge = $badges->fetch_assoc()): ?>
        <div class="badge">
          <img class="badges-medium" src="<?= $badge['icon_url'] ?? 'images/placeholder.png' ?>" alt="<?= $badge['name'] ?>">
          <p><?= $badge['name'] ?></p>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
