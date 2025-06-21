<?php
// assign_resources.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']==='studentx') {
    header('Location: login.php');
    exit;
}
$success = $error = '';

// Fetch classes or areas: ধরুন sessioninfo বা areas টেবিল থেকে
// উদাহরণ: teacher-এর class list fetch করুন; এখানে সরল: একটি dropdown সরাসরি sessioninfo থেকে
// Or: $classes = ['9','10']; // hardcode বা DB থেকে আনবেন

// Fetch students for selected class via AJAX or form submit
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['assign'])) {
    $sccode = $_SESSION['sccode'];
    $class = $_POST['class'];
    $stid = $_POST['stid']; // optional: যদি empty, assign to all in class
    $topic_code = $conn->real_escape_string($_POST['topic_code']);
    $resource_link = $conn->real_escape_string($_POST['resource_link']);
    $resource_type = $_POST['resource_type']; // validate among allowed types
    // For single student
    if (!empty($stid)) {
        $stmt = $conn->prepare("INSERT INTO learning_paths (sccode, stid, topic_code, suggested_resource, resource_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $sccode, $stid, $topic_code, $resource_link, $resource_type);
        if ($stmt->execute()) {
            $success = "Resource assigned to student $stid.";
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Assign to all students in that class
        $stmtS = $conn->prepare("SELECT stid FROM sessioninfo WHERE sccode=? AND classname=?");
        $stmtS->bind_param("is", $sccode, $class);
        $stmtS->execute();
        $resS = $stmtS->get_result();
        $count=0;
        while ($row = $resS->fetch_assoc()) {
            $stid_i = $row['stid'];
            $stmt = $conn->prepare("INSERT INTO learning_paths (sccode, stid, topic_code, suggested_resource, resource_type) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $sccode, $stid_i, $topic_code, $resource_link, $resource_type);
            if($stmt->execute()) $count++;
            $stmt->close();
        }
        $stmtS->close();
        $success = "Assigned to $count students in class $class.";
    }
}

// Fetch class list for dropdown
// উদাহরণ: sessioninfo distinct classes
$sccode = $_SESSION['sccode']??1001;
$resC = $conn->prepare("SELECT DISTINCT classname FROM sessioninfo WHERE sccode=?");
$resC->bind_param("i", $sccode);
$resC->execute();
$resC2 = $resC->get_result();
$classes = $resC2->fetch_all(MYSQLI_ASSOC);
$resC->close();

// If AJAX to fetch students per class: implement a separate endpoint (e.g., fetch_students.php)
include 'header.php';
?>
<h4>Assign Resource</h4>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<form method="POST" action="">
  <div class="mb-3">
    <label class="form-label">Class</label>
    <select name="class" id="classSelect" class="form-select" required>
      <option value="">Select Class</option>
      <?php foreach($classes as $c): ?>
        <option value="<?= htmlspecialchars($c['classname']) ?>"><?= htmlspecialchars($c['classname']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Student ID (Optional)</label>
    <input type="text" name="stid" id="stidInput" class="form-control" placeholder="Leave blank to assign to all in class">
  </div>
  <div class="mb-3">
    <label class="form-label">Topic Code</label>
    <input type="text" name="topic_code" class="form-control" placeholder="e.g., 9_algebra_linear_eq" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Resource Link</label>
    <input type="url" name="resource_link" class="form-control" placeholder="https://..." required>
  </div>
  <div class="mb-3">
    <label class="form-label">Resource Type</label>
    <select name="resource_type" class="form-select">
      <option value="pdf">PDF</option>
      <option value="video">Video</option>
      <option value="quiz">Quiz</option>
      <option value="text">Text</option>
      <option value="interactive">Interactive</option>
    </select>
  </div>
  <button type="submit" name="assign" class="btn btn-primary">Assign</button>
</form>

<?php include 'footer.php'; ?>
