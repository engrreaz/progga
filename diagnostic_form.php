<?php
// diagnostic_form.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    // header('Location: login.php');
    // exit;
}
// ধরুন আপনার টেবিলে প্রশ্ন আছে: quiz_questions with fields id, question_text, option_a..d, correct_answer, topic_code
// এখানে sample ফিল্টার: শুধুমাত্র একটি টপিকের জন্য বা class অনুযায়ী
// উদাহরণ সরল: সব প্রশ্ন
$sql = "SELECT id, question_text, option_a, option_b, option_c, option_d, topic_code FROM quiz_questions WHERE quiz_type='diagnostic'";
$res = $conn->query($sql);
$questions = [];
while ($row = $res->fetch_assoc()) {
    $questions[] = $row;
}
include 'header.php';
?>
<h4>Diagnostic Test</h4>
<form method="POST" action="process_diagnostic.php">
  <?php foreach($questions as $i => $q): ?>
    <div class="mb-3">
      <label class="form-label"><?= ($i+1) . ". " . htmlspecialchars($q['question_text']) ?></label>
      <?php foreach (['a','b','c','d'] as $opt): 
        $optKey = 'option_' . $opt;
        if (isset($q[$optKey]) && $q[$optKey]!==''): ?>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="answers[<?= $q['id'] ?>]" id="q<?= $q['id'].$opt ?>" value="<?= $opt ?>">
          <label class="form-check-label" for="q<?= $q['id'].$opt ?>"><?= htmlspecialchars($q[$optKey]) ?></label>
        </div>
      <?php endif; endforeach; ?>
      <input type="hidden" name="topics[<?= $q['id'] ?>]" value="<?= htmlspecialchars($q['topic_code']) ?>">
    </div>
  <?php endforeach; ?>
  <button class="btn btn-primary">Submit</button>
</form>
<?php include 'footer.php'; ?>
