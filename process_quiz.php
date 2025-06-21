<?php
// process_quiz.php
require_once 'db.php';
if (empty($_SESSION['usr']) || $_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['quiz_id']) || empty($_POST['answers'])) {
    die("Invalid request");
}
$usr = $_SESSION['usr'] ?? 'plexiboard';
$sccode = $_SESSION['sccode'] ?? null;
$stid = $_SESSION['stid'] ?? null;
$quiz_id = intval($_POST['quiz_id']);
$answers = $_POST['answers'];       // [question_id => selected_option]
$topics_map = $_POST['topics'];     // [question_id => topic_code]

$total = count($answers);
$score = 0;
$topic_scores = []; // topic_code => ['correct'=>x,'total'=>y]
foreach ($answers as $qid => $ans) {
    $qid = intval($qid);
    $stmt = $conn->prepare("SELECT correct_answer, topic_code FROM quiz_questions WHERE id=? AND quiz_id=?");
    $stmt->bind_param("ii", $qid, $quiz_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $correct = $row['correct_answer'];
        $topic = $row['topic_code'];
        if (!isset($topic_scores[$topic])) {
            $topic_scores[$topic] = ['correct' => 0, 'total' => 0];
        }
        $topic_scores[$topic]['total']++;
        if ($ans === $correct) {
            $score++;
            $topic_scores[$topic]['correct']++;
        }
    }
    $stmt->close();
}
// সাধারণ feedback text তৈরি
$feedback_items = [];
foreach ($topic_scores as $topic_code => $data) {
    $percent = ($data['total'] > 0) ? ($data['correct'] / $data['total'] * 100) : 0;
    if ($percent < 40) {
        $lvl = 'weak';
        $feedback_items[] = "Topic $topic_code এ উন্নতির প্রয়োজন";
    } elseif ($percent < 70) {
        $lvl = 'average';
        $feedback_items[] = "Topic $topic_code এ মাঝারি পারফরম্যান্স";
    } else {
        $lvl = 'strong';
        // optional: praise message
        $feedback_items[] = "Topic $topic_code এ চমৎকার পারফরম্যান্স!";
    }
    // feedback_logs-এ topic অনুযায়ী সংরক্ষণ
    $stmt2 = $conn->prepare("INSERT INTO feedback_logs (sccode, stid, quiz_id, topic_code, score, max_score, feedback_text) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $detail = json_encode([
        'correct' => $data['correct'],
        'total' => $data['total'],
        'level' => $lvl,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    $stmt2->bind_param("isisdds", $sccode, $stid, $quiz_id, $topic_code, $data['correct'], $data['total'], $detail);
    $stmt2->execute();
    $stmt2->close();
    // low score হলে learning_paths-এ পুনরায় resource assign
    if ($percent < 50) {
        // call function generateLearningPath; সরল উদাহরণ:
        // ensure generateLearningPath ফাংশন includes/config বা utils file-এ আছে
        generateLearningPath($conn, $sccode, $stid);
    }
}


$user_id = $_SESSION['user_id'];
$xp = 50;
$reason = "Quiz Completed";

$sql = "INSERT INTO user_xp_log (user_id, email,  xp, reason) VALUES (?, ?,  ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isis", $user_id, $usr, $xp, $reason);
$stmt->execute();


// Student-কে summary দেখানো
include 'header.php';
?>
<h4>Quiz Result</h4>
<p>আপনি পেয়েছেন <?= $score ?> / <?= $total ?> প্রশ্নে সঠিক উত্তর।</p>
<ul>
    <?php foreach ($feedback_items as $item): ?>
        <li><?= htmlspecialchars($item) ?></li>
    <?php endforeach; ?>
</ul>
<a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
<?php include 'footer.php'; ?>