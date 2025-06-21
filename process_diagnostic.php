<?php
// process_diagnostic.php
require_once 'db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role']!=='student') {
    // header('Location: login.php');
    // exit;
}


$sccode = $_SESSION['sccode'] ?? null;  // sessioninfo থেকে সঠিক মান ensure করুন
$stid   = $_SESSION['stid'] ?? null;
echo $sccode . '/' . $stid;

if (!$sccode || !$stid) {
    die("Student context missing.");
}

// $_POST['answers'] => [question_id => 'a'/'b'/...]
// $_POST['topics'] => [question_id => topic_code]
$total = count($_POST['answers']);
$score = 0;
$topic_scores = []; // topic_code => [correct_count, total_count]
foreach ($_POST['answers'] as $qid => $ans) {
    // প্রশ্নের সঠিক অপশন পান
    $stmt = $conn->prepare("SELECT correct_answer, topic_code FROM quiz_questions WHERE id=?");
    $stmt->bind_param("i", $qid);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $correct = $row['correct_answer'];
        $topic = $row['topic_code'];
        if (!isset($topic_scores[$topic])) {
            $topic_scores[$topic] = ['correct'=>0, 'total'=>0];
        }
        $topic_scores[$topic]['total']++;
        if ($ans === $correct) {
            $score++;
            $topic_scores[$topic]['correct']++;
        }
    }
    $stmt->close();
}
// প্রতিটি টপিকে ফল সংরক্ষণ
foreach ($topic_scores as $topic_code => $data) {
    $t_correct = $data['correct'];
    $t_total = $data['total'];
    $percent = ($t_total>0)? ($t_correct/$t_total*100) : 0;
    if ($percent < 40) $level='weak';
    elseif ($percent < 70) $level='average';
    else $level='strong';
    $stmt2 = $conn->prepare("INSERT INTO diagnosis_results (sccode, stid, topic_code, score, max_score, level, details) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $details = json_encode([
        'correct'=>$t_correct,
        'total'=>$t_total,
        'timestamp'=>date('Y-m-d H:i:s')
    ]);
    $stmt2->bind_param("issddss", $sccode, $stid, $topic_code, $t_correct, $t_total, $level, $details);
    $stmt2->execute();
    $stmt2->close();
}
// redirect back or show summary
header('Location: dashboard.php');
exit;
