<?php
require_once 'db.php';
if ($usr == '') {
  header('Location: login.php');
  exit;
}
?>
..................

<div class="containerx mt-4">
  <h2>স্বাগতম, শিক্ষার্থী!</h2>
  <p class="lead">আপনার সর্বশেষ মূল্যায়নের ফলাফল ও রিকমেন্ডেশন এখানে দেখুন।</p>

  <div class="row mb-3">
    <div class="d-flex">
      <button class="btn btn-primary shadow-sm shadow-md">সাবমিট</button>
      <button class="btn btn-outline-secondary">বাতিল</button>
    </div>



  </div>


  <div class="row d-flex">
    <div class="col-md-3">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title">আপনার বর্তমান লেভেল</h5>
          <p class="card-text">Level: 3 (উন্নত)</p>
          <p class="text-success fw-bold">XP: 1450</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title">আপনার বর্তমান লেভেল</h5>
          <p class="card-text">Level: 3 (উন্নত)</p>
          <p class="text-success fw-bold">XP: 1450</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title">আপনার বর্তমান লেভেল</h5>
          <p class="card-text">Level: 3 (উন্নত)</p>
          <p class="text-success fw-bold">XP: 1450</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title">আপনার বর্তমান লেভেল</h5>
          <p class="card-text">Level: 3 (উন্নত)</p>
          <p class="text-success fw-bold">XP: 1450</p>
        </div>
      </div>
    </div>


  </div>


  <div class="row">
    <div class="d-flex">
      <span class="badge bg-success">📘 অধ্যয়নরত</span>
      <span class="badge bg-warning text-dark">🧠 স্মরণযোগ্য</span>
      <span class="badge bg-danger">🔥 দক্ষ</span>
    </div>


  </div>


  <table class="table table-bordered table-hover table-sm">
    <thead class="table-light">
      <tr>
        <th>স্থান</th>
        <th>নাম</th>
        <th>XP</th>
        <th>লেভেল</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>🥇</td>
        <td>সাদিয়া</td>
        <td>1800</td>
        <td>9</td>
      </tr>
      <tr>
        <td>🥈</td>
        <td>রায়হান</td>
        <td>1650</td>
        <td>8</td>
      </tr>
    </tbody>
  </table>


  <div class="mb-2 small">লেভেল 3 → 4</div>
  <div class="progress">
    <div class="progress-bar bg-success" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0"
      aria-valuemax="100">60%</div>
  </div>


  <div class="mb-3">
    <label class="form-label">বিষয়ের নাম</label>
    <input type="text" name="subject" class="form-control" required>
  </div>
  <div class="card shadow-sm mb-3">
    <div class="card-body d-flex align-items-center gap-3">
      <img src="assets/img/avatar.png" width="60" class="rounded-circle border">
      <div>
        <h6 class="mb-0">রায়হান ইসলাম</h6>
        <small class="text-muted">Level 4 | XP: 1530</small>
      </div>
    </div>
  </div>
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    ✅ আপনি নতুন ব্যাজ অর্জন করেছেন!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <div class="mb-3">
    <label for="chapter" class="form-label">চ্যাপ্টার</label>
    <select name="chapter" class="form-select" id="chapter">
      <option value="">-- অধ্যায় নির্বাচন করুন --</option>
      <option value="1">১. রাশি</option>
      <option value="2">২. সূচক ও ঘাত</option>
    </select>
  </div>


  <div class="row">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">মোট XP</h5>
          <p class="card-text fw-bold text-success">850 XP</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">বর্তমান স্তর</h5>
          <p class="card-text fw-bold">🎓 দক্ষ শিক্ষার্থী</p>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="row">
  <div class="col-md-8">
    <h3>স্বাগতম, <?= htmlspecialchars($_SESSION['usr']) ?></h3>
    <?php if ($userlevel === 'student'): ?>
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

    <i class="bi bi-shield-lock-fill"></i>
    <i class="bi bi-gear-fill"></i>
    <i class="bi bi-person-video2"></i>
    <i class="bi bi-mortarboard-fill"></i>
    <i class="bi bi-people-fill"></i>


    <ul class="list-group">
      <?php if ($userlevel === 'student'): ?>
        <li class="list-group-item"><a href="#"> Diagnostic Test</a></li>
        <li class="list-group-item"><a href="#">My Learning Path</a></li>
        <li class="list-group-item"><a href="#">Take Quiz</a></li>
      <?php else: ?>
        <li class="list-group-item"><a href="#">Create Diagnostic</a></li>
        <li class="list-group-item"><a href="#">Assign Resources</a></li>
        <li class="list-group-item"><a href="#">View Reports</a></li>
      <?php endif; ?>
    </ul>


    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item"><a href="dashboard.php" class="nav-link active">ড্যাশবোর্ড</a></li>
      <li><a href="quizzes.php" class="nav-link">📊 কুইজ</a></li>
      <li><a href="chapters.php" class="nav-link">📚 অধ্যায়সমূহ</a></li>
      <li><a href="leaderboard.php" class="nav-link">🏆 লিডারবোর্ড</a></li>
      <li><a href="add_questions.php" class="nav-link"> <i class="bi bi-question-circle"></i>  প্রশ্ন যুক্ত/সম্পাদনা </a></li>
      <li><a class="nav-link" href="create_diagnostic.php"> Create Diagnostic </a></li>
      <li><a class="nav-link" href="create_quiz.php"> Create Quiz </a></li>
      <li><a class="nav-link" href="diagnostic_form.php"> Diagnostic Form </a></li>
      <li><a class="nav-link" href="learning_path.php"> <i class="bi bi-journals"></i>   Learning Path </a></li>
      <li><a class="nav-link" href="process_diagnostic.php"> Process Diagnostic </a></li>
      <li><a class="nav-link" href="update_path.php"> Update Path </a></li>
      <li><a class="nav-link" href="assign_resources.php"> Assign Resources </a></li>
      <li><a class="nav-link" href="process_quiz.php"> Process Quiz </a></li>
      <li><a class="nav-link" href="quiz.php"> <i class="bi bi-patch-question"></i>    Quiz </a></li>
      <li><a class="nav-link" href="syllabus.php"> Syllabus </a></li>
      <li><a class="nav-link" href="take_quiz.php"> Take Quiz </a></li>
      <li><a class="nav-link" href="view_reports.php"> View Reports </a></li>
      <li><a class="nav-link" href="badges.php"> Badges </a></li>
      <li><a class="nav-link" href="add_textbook.php"> Entry TextBook </a></li>
      <li><a class="nav-link" href="view_textbook.php"> View Textbook </a></li>
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->
      <!-- <li class="list-group-item"><a href=".php">  </a></li> -->


    </ul>
  </div>
</div>

<?php include 'footer.php'; ?>