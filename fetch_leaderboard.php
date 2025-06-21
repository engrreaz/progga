<?php
require_once 'db.php'; // আপনার ডেটাবেজ কানেকশন ফাইল
session_start();

$type = $_GET['type'] ?? 'global';
$user_id = $_SESSION['user_id'] ?? 1; // ডেমো হিসাবে fallback user_id

switch ($type) {
  case 'global':
    $sql = "SELECT name, xp, level FROM usersapp ORDER BY xp DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    break;

  case 'class':
    $sql = "SELECT u.name, u.xp, u.level 
            FROM usersapp u 
            JOIN sessioninfo s ON u.id = s.user_id 
            WHERE s.class = (SELECT class FROM sessioninfo WHERE user_id = ?) 
            ORDER BY u.xp DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    break;

  case 'school':
    $sql = "SELECT u.name, u.xp, u.level 
            FROM usersapp u 
            JOIN students st ON u.id = st.userid 
            WHERE st.sccode = (SELECT sccode FROM students WHERE userid = ?) 
            ORDER BY u.xp DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    break;

  case 'weekly':
    $sql = "SELECT u.name, u.level, SUM(l.xp) as xp 
            FROM user_xp_log l 
            JOIN usersapp u ON l.user_id = u.id 
            WHERE l.date >= CURDATE() - INTERVAL 7 DAY 
            GROUP BY u.id ORDER BY xp DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    break;

  case 'monthly':
    $sql = "SELECT u.name, u.level, SUM(l.xp) as xp 
            FROM user_xp_log l 
            JOIN usersapp u ON l.user_id = u.id 
            WHERE MONTH(l.date) = MONTH(CURDATE()) 
            GROUP BY u.id ORDER BY xp DESC LIMIT 10";
    $stmt = $conn->prepare($sql);
    break;

  default:
    http_response_code(400);
    echo json_encode(['error' => 'Invalid leaderboard type']);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
