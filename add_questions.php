<?php
// add_questions.php
require_once 'db.php';

if ($userlevel != 'Super Administrator') {
    header('Location: dashboard.php');
    exit;
}
$error = $success = '';

$stmtQ = $conn->prepare("SELECT id, quiz_name FROM quizzes WHERE class=?");
$stmtQ->bind_param("s", $clsname);
$stmtQ->execute();
$quizzes = $stmtQ->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtQ->close();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $quiz_id = intval($_POST['quiz_id']);
    $question = $conn->real_escape_string($_POST['question_text']);
    $optA = $conn->real_escape_string($_POST['option_a']);
    $optB = $conn->real_escape_string($_POST['option_b']);
    $optC = $conn->real_escape_string($_POST['option_c']);
    $optD = $conn->real_escape_string($_POST['option_d']);
    $correct = $conn->real_escape_string($_POST['correct_answer']); // 'a','b',...
    $topic_code = $conn->real_escape_string($_POST['topic_code']);
    $stmt = $conn->prepare("INSERT INTO quiz_questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_answer, topic_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $quiz_id, $question, $optA, $optB, $optC, $optD, $correct, $topic_code);
    if ($stmt->execute()) {
        $success = "Question added to Quiz $quiz_id";
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}

?>
<h4>Add Questions to Quiz</h4>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<form method="POST" action="">
  <div class="mb-3">
    <label class="form-label">Select Quiz</label>
    <select name="quiz_id" class="form-select" required>
      <option value="">--Select Quiz--</option>
      <?php foreach($quizzes as $q): ?>
        <option value="<?= $q['id'] ?>"><?= htmlspecialchars($q['quiz_name']) ?> (ID: <?= $q['id'] ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Question Text</label>
    <textarea name="question_text" class="form-control" required></textarea>
  </div>
  <div class="mb-3"><label class="form-label">Option A</label><input type="text" name="option_a" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Option B</label><input type="text" name="option_b" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Option C</label><input type="text" name="option_c" class="form-control"></div>
  <div class="mb-3"><label class="form-label">Option D</label><input type="text" name="option_d" class="form-control"></div>
  <div class="mb-3"><label class="form-label">Correct Answer (a/b/c/d)</label><input type="text" name="correct_answer" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Topic Code</label><input type="text" name="topic_code" class="form-control" placeholder="e.g., 9_algebra_linear_eq" required></div>
  <button class="btn btn-primary">Add Question</button>
</form>
<?php include 'footer.php'; ?>
