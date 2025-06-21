<?php
// take_quiz.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    header('Location: login.php');
    exit;
}
$sccode = $_SESSION['sccode'] ?? null;
$stid   = $_SESSION['stid'] ?? null;

// ধরুন `quizzes` টেবিল আছে
$sql = "SELECT id, quiz_name, quiz_type FROM quizzes WHERE class=?";
// class পাওয়া যায় sessioninfo থেকে; উদাহরণ: $_SESSION['class']
$class = $_SESSION['class'] ?? '';
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $class);
$stmt->execute();
$res = $stmt->get_result();
$quizzes = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include 'header.php';
?>
<h4>Available Quizzes</h4>
<?php if(empty($quizzes)): ?>
  <p>কোনো Quiz পাওয়া যায়নি।</p>
<?php else: ?>
  <ul class="list-group">
    <?php foreach($quizzes as $q): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <?= htmlspecialchars($q['quiz_name']) ?> 
        <a href="quiz.php?quiz_id=<?= $q['id'] ?>" class="btn btn-sm btn-primary">Take</a>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php include 'footer.php'; ?>
