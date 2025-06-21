<?php
// learning_path.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    header('Location: login.php');
    exit;
}
$sccode = $_SESSION['sccode'] ?? null;
$stid   = $_SESSION['stid'] ?? null;
include 'header.php';

// Fetch pending/in-progress resources
$stmt = $conn->prepare("SELECT id, topic_code, suggested_resource, resource_type, status FROM learning_paths WHERE sccode=? AND stid=? ORDER BY assigned_at DESC");
$stmt->bind_param("is", $sccode, $stid);
$stmt->execute();
$res = $stmt->get_result();
$paths = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<h4>My Learning Path</h4>
<table class="table">
  <thead>
    <tr>
      <th>Topic</th>
      <th>Resource</th>
      <th>Type</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($paths as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['topic_code']) ?></td>
      <td>
        <a href="<?= htmlspecialchars($row['suggested_resource']) ?>" target="_blank">View</a>
      </td>
      <td><?= htmlspecialchars($row['resource_type']) ?></td>
      <td><?= htmlspecialchars($row['status']) ?></td>
      <td>
        <?php if($row['status']==='pending'): ?>
          <form method="POST" action="update_path.php" style="display:inline">
            <input type="hidden" name="path_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="action" value="start">
            <button class="btn btn-sm btn-primary">Start</button>
          </form>
        <?php elseif($row['status']==='in_progress'): ?>
          <form method="POST" action="update_path.php" style="display:inline">
            <input type="hidden" name="path_id" value="<?= $row['id'] ?>">
            <input type="hidden" name="action" value="complete">
            <button class="btn btn-sm btn-success">Mark Completed</button>
          </form>
        <?php else: ?>
          <span class="text-success">Completed</span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include 'footer.php'; ?>
