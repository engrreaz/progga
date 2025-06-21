<?php
// syllabus.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    header('Location: login.php');
    exit;
}
$sccode = $_SESSION['sccode'] ?? null;
$stid   = $_SESSION['stid'] ?? null;
$class  = $_SESSION['class'] ?? ''; // ensure sessioninfo filled

// Handle marking complete via POST
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['topic_code'])) {
    $topic_code = $_POST['topic_code'];
    // Insert or update syllabus_progress
    // Check existing
    $stmt = $conn->prepare("SELECT id FROM syllabus_progress WHERE sccode=? AND stid=? AND class=? AND topic_code=?");
    $stmt->bind_param("isss", $sccode, $stid, $class, $topic_code);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows>0) {
        $row = $res->fetch_assoc();
        $stmt2 = $conn->prepare("UPDATE syllabus_progress SET is_completed=1, completed_at=NOW() WHERE id=?");
        $stmt2->bind_param("i", $row['id']);
        $stmt2->execute();
        $stmt2->close();
    } else {
        $stmt2 = $conn->prepare("INSERT INTO syllabus_progress (sccode, stid, class, topic_code, is_completed, completed_at) VALUES (?, ?, ?, ?, 1, NOW())");
        $stmt2->bind_param("isss", $sccode, $stid, $class, $topic_code);
        $stmt2->execute();
        $stmt2->close();
    }
    $stmt->close();
    // Optionally: remove pending learning_paths for this topic
}

// Fetch syllabus topics for class
$stmt = $conn->prepare("SELECT topic_code, topic_name FROM syllabus_topics WHERE class=? ORDER BY sequence_order ASC");
$stmt->bind_param("s", $class);
$stmt->execute();
$res = $stmt->get_result();
$topics = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch student's completed topics
$stmt2 = $conn->prepare("SELECT topic_code FROM syllabus_progress WHERE sccode=? AND stid=? AND class=? AND is_completed=1");
$stmt2->bind_param("iss", $sccode, $stid, $class);
$stmt2->execute();
$res2 = $stmt2->get_result();
$completed = [];
while ($r = $res2->fetch_assoc()) {
    $completed[] = $r['topic_code'];
}
$stmt2->close();

include 'header.php';
?>
<h4>Syllabus Progress (Class <?= htmlspecialchars($class) ?>)</h4>
<table class="table">
  <thead>
    <tr>
      <th>Topic</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($topics as $t): 
      $code = $t['topic_code'];
      $done = in_array($code, $completed);
    ?>
      <tr>
        <td><?= htmlspecialchars($t['topic_name']) ?></td>
        <td>
          <?php if($done): ?>
            <span class="badge bg-success">Completed</span>
          <?php else: ?>
            <span class="badge bg-secondary">Pending</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if(!$done): ?>
            <form method="POST" action="" style="display:inline">
              <input type="hidden" name="topic_code" value="<?= htmlspecialchars($code) ?>">
              <button class="btn btn-sm btn-primary">Mark as Completed</button>
            </form>
          <?php else: ?>
            —
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include 'footer.php'; ?>
