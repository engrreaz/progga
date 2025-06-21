<?php
// update_path.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    header('Location: login.php');
    exit;
}
$sccode = $_SESSION['sccode'] ?? null;
$stid   = $_SESSION['stid'] ?? null;

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $path_id = intval($_POST['path_id']);
    $action = $_POST['action'];
    if ($action==='start') {
        $stmt = $conn->prepare("UPDATE learning_paths SET status='in_progress' WHERE id=? AND sccode=? AND stid=?");
        $stmt->bind_param("iis", $path_id, $sccode, $stid);
        $stmt->execute();
        $stmt->close();
    } elseif ($action==='complete') {
        $stmt = $conn->prepare("UPDATE learning_paths SET status='completed', completed_at=NOW() WHERE id=? AND sccode=? AND stid=?");
        $stmt->bind_param("iis", $path_id, $sccode, $stid);
        $stmt->execute();
        $stmt->close();
        // syllabus_progress-এ মেলান (optional)
        // এখানে sessioninfo থেকে class নেওয়া যেতে পারে
    }
}
header('Location: learning_path.php');
exit;
