<?php
require_once 'db.php';

$sql = "
SELECT b.id, b.name, b.description, b.icon_url,
       CASE WHEN ub.user_id IS NOT NULL THEN 1 ELSE 0 END AS earned
FROM badges b
LEFT JOIN user_badges ub ON b.id = ub.badge_id AND ub.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

$badges = [];
while ($row = $res->fetch_assoc()) {
    $badges[] = $row;
}

header('Content-Type: application/json');
echo json_encode($badges);



