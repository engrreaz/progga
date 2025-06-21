<?php
// quiz.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    header('Location: login.php');
    exit;
}
if (empty($_GET['quiz_id'])) {
    die("Quiz ID প্রয়োজন");
}
$quiz_id = intval($_GET['quiz_id']);

// প্রথমে যাচাই করুন student এর class-এর জন্য পর্যাপ্ত কি না (optional)
// Fetch quiz info
$stmtQ = $conn->prepare("SELECT quiz_name FROM quizzes WHERE id=?");
$stmtQ->bind_param("i", $quiz_id);
$stmtQ->execute();
$resQ = $stmtQ->get_result();
if ($resQ->num_rows!==1) {
    die("Invalid Quiz");
}
$quizInfo = $resQ->fetch_assoc();
$stmtQ->close();

// Fetch questions
$stmt = $conn->prepare("SELECT id, question_text, option_a, option_b, option_c, option_d, topic_code FROM quiz_questions WHERE quiz_id=?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$res = $stmt->get_result();
$questions = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();



include 'header.php';
?>
<h4>Quiz: <?= htmlspecialchars($quizInfo['quiz_name']) ?></h4>
<form method="POST" action="process_quiz.php">
  <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
  <?php foreach($questions as $i => $q): ?>
    <div class="mb-3">
      <label class="form-label"><?= ($i+1) . ". " . htmlspecialchars($q['question_text']) ?></label>
      <?php foreach (['a','b','c','d'] as $opt): 
        $optKey = 'option_' . $opt;
        if (!empty($q[$optKey])): ?>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="answers[<?= $q['id'] ?>]" id="q<?= $q['id'].$opt ?>" value="<?= $opt ?>" required>
          <label class="form-check-label" for="q<?= $q['id'].$opt ?>"><?= htmlspecialchars($q[$optKey]) ?></label>
        </div>
      <?php endif; endforeach; ?>
      <input type="hidden" name="topics[<?= $q['id'] ?>]" value="<?= htmlspecialchars($q['topic_code']) ?>">
    </div>
  <?php endforeach; ?>
  <button class="btn btn-primary">Submit Quiz</button>
</form>
<?php include 'footer.php'; ?>
