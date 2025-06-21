<?php
// view_reports.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']==='studentx') {
    header('Location: login.php');
    exit;
}
$sccode = $_SESSION['sccode'];
$class = $_SESSION['class'] ?? ''; // teacher’s class context; অথবা teacher selects class via GET/POST

// Optionally: class নির্বাচন UI আগে
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['class'])) {
    $class = $_POST['class'];
}

// Fetch distinct classes for dropdown
$resC = $conn->prepare("SELECT DISTINCT classname FROM sessioninfo WHERE sccode=?");
$resC->bind_param("i", $sccode);
$resC->execute();
$resC2 = $resC->get_result()->fetch_all(MYSQLI_ASSOC);
$resC->close();

include 'header.php';
?>
<h4>View Reports</h4>
<form method="POST" action="">
  <div class="mb-3">
    <label class="form-label">Select Class</label>
    <select name="class" class="form-select" required onchange="this.form.submit()">
      <option value="">--Select--</option>
      <?php foreach($resC2 as $c): ?>
        <option value="<?= htmlspecialchars($c['classname']) ?>" <?= ($class===$c['classname'])?'selected':'' ?>><?= htmlspecialchars($c['classname']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</form>

<?php if($class): ?>
  <?php
  // Fetch student list in class
  $stmtS = $conn->prepare("SELECT stid FROM sessioninfo WHERE sccode=? AND classname=?");
  $stmtS->bind_param("is", $sccode, $class);
  $stmtS->execute();
  $resS = $stmtS->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmtS->close();
  // Initialize aggregates
  $report = [];
  foreach($resS as $row) {
    $stid = $row['stid'];
    $report[$stid] = [
      'diagnostic_weak_count'=>0,
      'quiz_avg_score'=>null,
      'learning_pending'=>0,
      'syllabus_completed_count'=>0,
      'syllabus_total'=>0
    ];
    // Diagnostic weak topics count (latest per topic)
    $stmtD = $conn->prepare("
      SELECT COUNT(*) as cnt FROM (
        SELECT dr.topic_code, dr.level
        FROM diagnosis_results dr
        JOIN (
          SELECT topic_code, MAX(attempted_at) AS maxtime
          FROM diagnosis_results
          WHERE sccode=? AND stid=?
          GROUP BY topic_code
        ) sub ON dr.topic_code=sub.topic_code AND dr.attempted_at=sub.maxtime
        WHERE dr.sccode=? AND dr.stid=? AND dr.level='weak'
      ) t
    ");
    $stmtD->bind_param("isis", $sccode, $stid, $sccode, $stid);
    $stmtD->execute();
    $cntD = $stmtD->get_result()->fetch_assoc()['cnt'];
    $stmtD->close();
    $report[$stid]['diagnostic_weak_count'] = $cntD;

    // Quiz average score across recent quizzes: feedback_logs
    $stmtQ = $conn->prepare("
      SELECT AVG(score/max_score)*100 as avgp
      FROM feedback_logs
      WHERE sccode=? AND stid=?
    ");
    $stmtQ->bind_param("is", $sccode, $stid);
    $stmtQ->execute();
    $rQ = $stmtQ->get_result()->fetch_assoc()['avgp'];
    $stmtQ->close();
    $report[$stid]['quiz_avg_score'] = $rQ!==null? round($rQ,2): null;

    // Learning Path pending count
    $stmtL = $conn->prepare("SELECT COUNT(*) as cnt FROM learning_paths WHERE sccode=? AND stid=? AND status!='completed'");
    $stmtL->bind_param("is", $sccode, $stid);
    $stmtL->execute();
    $cntL = $stmtL->get_result()->fetch_assoc()['cnt'];
    $stmtL->close();
    $report[$stid]['learning_pending'] = $cntL;

    // Syllabus total vs completed
    // total topics for class
    $stmtT = $conn->prepare("SELECT COUNT(*) as tot FROM syllabus_topics WHERE class=?");
    $stmtT->bind_param("s", $class);
    $stmtT->execute();
    $tot = $stmtT->get_result()->fetch_assoc()['tot'];
    $stmtT->close();
    // completed count
    $stmtC = $conn->prepare("SELECT COUNT(*) as c FROM syllabus_progress WHERE sccode=? AND stid=? AND class=? AND is_completed=1");
    $stmtC->bind_param("iss", $sccode, $stid, $class);
    $stmtC->execute();
    $comp = $stmtC->get_result()->fetch_assoc()['c'];
    $stmtC->close();
    $report[$stid]['syllabus_completed_count'] = $comp;
    $report[$stid]['syllabus_total'] = $tot;
  }
  ?>
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Student ID</th>
        <th>Weak Topics</th>
        <th>Quiz Avg (%)</th>
        <th>Pending Resources</th>
        <th>Syllabus Completed</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($report as $stid => $data): ?>
        <tr>
          <td><?= htmlspecialchars($stid) ?></td>
          <td><?= $data['diagnostic_weak_count'] ?></td>
          <td><?= $data['quiz_avg_score']!==null? $data['quiz_avg_score']:'N/A' ?></td>
          <td><?= $data['learning_pending'] ?></td>
          <td><?= $data['syllabus_completed_count'] ?> / <?= $data['syllabus_total'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
<?php include 'footer.php'; ?>
