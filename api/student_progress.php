<?php
// api/student_progress.php
require_once '../includes/config.php';
header('Content-Type: application/json');
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    echo json_encode(['error'=>'Unauthorized']);
    exit;
}
$sccode = $_SESSION['sccode'];
$stid   = $_SESSION['stid'];
// উদাহরণ: syllabus progress percentage
$class = $_SESSION['class'];
// total topics
$stmt = $conn->prepare("SELECT COUNT(*) as tot FROM syllabus_topics WHERE class=?");
$stmt->bind_param("s", $class);
$stmt->execute();
$tot = $stmt->get_result()->fetch_assoc()['tot'];
$stmt->close();
// completed
$stmt2 = $conn->prepare("SELECT COUNT(*) as comp FROM syllabus_progress WHERE sccode=? AND stid=? AND class=? AND is_completed=1");
$stmt2->bind_param("iss", $sccode, $stid, $class);
$stmt2->execute();
$comp = $stmt2->get_result()->fetch_assoc()['comp'];
$stmt2->close();
echo json_encode(['total'=>$tot, 'completed'=>$comp]);
