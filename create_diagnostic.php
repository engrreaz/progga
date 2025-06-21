<?php
// create_diagnostic.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']==='student') {
    header('Location: login.php');
    exit;
}
$error = $success = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $question = $_POST['question_text'];
    $optA = $_POST['option_a'];
    $optB = $_POST['option_b'];
    $optC = $_POST['option_c'];
    $optD = $_POST['option_d'];
    $correct = $_POST['correct_answer']; // 'a'/'b' etc.
    $topic_code = $_POST['topic_code'];
    $sql = "INSERT INTO quiz_questions (question_text, option_a, option_b, option_c, option_d, correct_answer, topic_code, quiz_type) VALUES (?, ?, ?, ?, ?, ?, ?, 'diagnostic')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $question, $optA, $optB, $optC, $optD, $correct, $topic_code);
    if ($stmt->execute()) {
        $success = "Question added.";
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}
include 'header.php';
?>
<h4>Create Diagnostic Question</h4>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<form method="POST" action="">
  <div class="mb-3">
    <label class="form-label">Question Text</label>
    <textarea name="question_text" class="form-control" required></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Option A</label>
    <input type="text" name="option_a" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Option B</label>
    <input type="text" name="option_b" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Option C</label>
    <input type="text" name="option_c" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Option D</label>
    <input type="text" name="option_d" class="form-control">
  </div>
  <div class="mb-3">
    <label class="form-label">Correct Answer (a/b/c/d)</label>
    <input type="text" name="correct_answer" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Topic Code</label>
    <input type="text" name="topic_code" class="form-control" placeholder="e.g., 9_algebra_linear_eq" required>
  </div>
  <button class="btn btn-primary">Add Question</button>
</form>
<?php include 'footer.php'; ?>
