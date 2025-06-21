<?php
require_once 'db.php';
if (empty($_SESSION['usr'])) {
    header('Location: login.php');
    exit;
}
$usr = $_SESSION['usr'];
$role   = $_SESSION['role'];
include 'header.php';
?>
..................
<div class="row">
  <div class="col-md-8">
    <h3>স্বাগতম, <?= htmlspecialchars($_SESSION['usr']) ?></h3>
    <?php if($role==='student'): ?>
      <p>আপনার লার্নিং পাথ এবং অগ্রগতি দেখুন:</p>
      <!-- AJAX/Chart.js দিয়ে learning_paths, syllabus_progress, feedback_logs দেখান -->
    <?php else: ?>
      <p>আপনার ক্লাসের শিক্ষার্থীদের অগ্রগতি মনিটর করুন:</p>
      <div class="row g-3">
        <div class="col-sm-4">
          <div class="card text-bg-primary">
            <div class="card-body">
              <h5 class="card-title">সম্পন্ন লার্নিং টাস্ক</h5>
              <p class="card-text display-6">0</p>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card text-bg-warning">
            <div class="card-body">
              <h5 class="card-title">পেন্ডিং টাস্ক</h5>
              <p class="card-text display-6">0</p>
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card text-bg-danger">
            <div class="card-body">
              <h5 class="card-title">দুর্বল টপিক</h5>
              <p class="card-text display-6">0</p>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <div class="col-md-4">
    <h5>Quick Links</h5>
    <ul class="list-group">
      <?php if($role==='student'): ?>
        <li class="list-group-item"><a href="#">Diagnostic Test</a></li>
        <li class="list-group-item"><a href="#">My Learning Path</a></li>
        <li class="list-group-item"><a href="#">Take Quiz</a></li>
      <?php else: ?>
        <li class="list-group-item"><a href="#">Create Diagnostic</a></li>
        <li class="list-group-item"><a href="#">Assign Resources</a></li>
        <li class="list-group-item"><a href="#">View Reports</a></li>
      <?php endif; ?>
    </ul>




    <h5>Menu</h5>
    <ul class="list-group">
        <li class="list-group-item"><a href="add_questions.php"> Add Questions </a></li>
        <li class="list-group-item"><a href="create_diagnostic.php"> Create Diagnostic </a></li>
        <li class="list-group-item"><a href="create_quiz.php"> Create Quiz </a></li>
        <li class="list-group-item"><a href="diagnostic_form.php"> Diagnostic Form </a></li>
        <li class="list-group-item"><a href="learning_path.php"> Learning Path </a></li>
        <li class="list-group-item"><a href="process_diagnostic.php"> Process Diagnostic </a></li>
        <li class="list-group-item"><a href="update_path.php"> Update Path </a></li>
        <li class="list-group-item"><a href="assign_resources.php"> Assign Resources </a></li>
        <li class="list-group-item"><a href="process_quiz.php"> Process Quiz </a></li>
        <li class="list-group-item"><a href="quiz.php"> Quiz </a></li>
        <li class="list-group-item"><a href="syllabus.php"> Syllabus </a></li>
        <li class="list-group-item"><a href="take_quiz.php"> Take Quiz </a></li>
        <li class="list-group-item"><a href="view_reports.php"> View Reports </a></li>
        <li class="list-group-item"><a href="badges.php"> Badges </a></li>
        <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
        <!-- <li class="list-group-item"><a href=".php">  </a></li> -->


    </ul>
  </div>
</div>

<?php include 'footer.php'; ?>
