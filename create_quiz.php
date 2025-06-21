<?php
// create_quiz.php
require_once 'includes/config.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']==='student') {
    header('Location: /auth/login.php');
    exit;
}
$error = $success = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $quiz_name = $conn->real_escape_string($_POST['quiz_name']);
    $class = $conn->real_escape_string($_POST['class']);
    $quiz_type = $conn->real_escape_string($_POST['quiz_type']); // e.g. 'regular'
    $stmt = $conn->prepare("INSERT INTO quizzes (quiz_name, class, quiz_type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $quiz_name, $class, $quiz_type);
    if ($stmt->execute()) {
        $quiz_id = $stmt->insert_id;
        $success = "Quiz তৈরি হয়েছে। Quiz ID: $quiz_id";
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}
// Fetch classes
$sccode = $_SESSION['sccode'];
$resC = $conn->prepare("SELECT DISTINCT classname FROM sessioninfo WHERE sccode=?");
$resC->bind_param("i", $sccode);
$resC->execute();
$classes = $resC->get_result()->fetch_all(MYSQLI_ASSOC);
$resC->close();

include 'includes/header.php';
?>
<h4>Create Quiz</h4>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<form method="POST" action="">
  <div class="mb-3">
    <label class="form-label">Quiz Name</label>
    <input type="text" name="quiz_name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Class</label>
    <select name="class" class="form-select" required>
      <option value="">Select Class</option>
      <?php foreach($classes as $c): ?>
        <option value="<?= htmlspecialchars($c['classname']) ?>"><?= htmlspecialchars($c['classname']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Quiz Type</label>
    <select name="quiz_type" class="form-select" required>
      <option value="regular">Regular</option>
      <option value="diagnostic">Diagnostic (overwrite)</option>
    </select>
  </div>
  <button class="btn btn-primary">Create Quiz</button>
</form>
<?php include 'includes/footer.php'; ?>
