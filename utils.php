<?php
// includes/utils.php

/**
 * Generate learning path for weak topics of a student.
 * আগের উদাহরণ অনুযায়ী rule-based assignment.
 */
function generateLearningPath($conn, $sccode, $stid) {
    // Fetch latest weak topics
    $sql = "SELECT dr.topic_code 
            FROM diagnosis_results dr
            JOIN (
                SELECT topic_code, MAX(attempted_at) AS maxtime
                FROM diagnosis_results
                WHERE sccode=? AND stid=?
                GROUP BY topic_code
            ) sub ON dr.topic_code=sub.topic_code AND dr.attempted_at=sub.maxtime
            WHERE dr.sccode=? AND dr.stid=? AND dr.level='weak'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isis", $sccode, $stid, $sccode, $stid);
    $stmt->execute();
    $res = $stmt->get_result();
    $weak_topics = [];
    while ($row = $res->fetch_assoc()) {
        $weak_topics[] = $row['topic_code'];
    }
    $stmt->close();
    // For each weak topic, fetch some resources from resources table
    foreach ($weak_topics as $topic) {
        $stmt2 = $conn->prepare("SELECT resource_link, resource_type FROM resources WHERE topic_code=? LIMIT 3");
        $stmt2->bind_param("s", $topic);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        while ($r = $res2->fetch_assoc()) {
            $link = $r['resource_link'];
            $type = $r['resource_type'];
            // avoid duplicate pending
            $chk = $conn->prepare("SELECT id FROM learning_paths WHERE sccode=? AND stid=? AND topic_code=? AND suggested_resource=? AND status='pending'");
            $chk->bind_param("isss", $sccode, $stid, $topic, $link);
            $chk->execute();
            $resChk = $chk->get_result();
            if ($resChk->num_rows == 0) {
                $ins = $conn->prepare("INSERT INTO learning_paths (sccode, stid, topic_code, suggested_resource, resource_type) VALUES (?, ?, ?, ?, ?)");
                $ins->bind_param("issss", $sccode, $stid, $topic, $link, $type);
                $ins->execute();
                $ins->close();
            }
            $chk->close();
        }
        $stmt2->close();
    }
}





// util.php

function calculateLevel($totalXP) {
    if ($totalXP >= 3600) return 10;
    elseif ($totalXP >= 2800) return 9;
    elseif ($totalXP >= 2100) return 8;
    elseif ($totalXP >= 1500) return 7;
    elseif ($totalXP >= 1000) return 6;
    elseif ($totalXP >= 700) return 5;
    elseif ($totalXP >= 400) return 4;
    elseif ($totalXP >= 200) return 3;
    elseif ($totalXP >= 100) return 2;
    else return 1;
}

// ঐচ্ছিক: পরবর্তী লেভেলের জন্য কত XP দরকার
function xpForNextLevel($level) {
    $xpMap = [
        1 => 100,
        2 => 200,
        3 => 400,
        4 => 700,
        5 => 1000,
        6 => 1500,
        7 => 2100,
        8 => 2800,
        9 => 3600,
        10 => 999999 // Max level
    ];
    return $xpMap[$level] ?? 100000;
}

// ঐচ্ছিক: লেভেলের মধ্যে কতটা অগ্রগতি (%)
function levelProgressPercent($totalXP) {
    $level = calculateLevel($totalXP);
    $currentMin = xpForNextLevel($level - 1);
    $next = xpForNextLevel($level);

    $currentXP = max(0, $totalXP - $currentMin);
    $levelSpan = $next - $currentMin;
    return round(($currentXP / $levelSpan) * 100);
}



// require_once 'includes/util.php';

// $totalXP = 980;
// $level = calculateLevel($totalXP); // => 5
// $progress = levelProgressPercent($totalXP); // => 93





function checkAndAwardBadges($conn, $user_id) {
    // Step 1: শিক্ষার্থীর মোট XP ও quiz count বের করি
    $xpRes = $conn->query("SELECT SUM(xp) as total FROM user_xp_log WHERE user_id = $user_id");
    $total_xp = $xpRes->fetch_assoc()['total'] ?? 0;

    $quizRes = $conn->query("SELECT COUNT(*) as total FROM user_xp_log WHERE user_id = $user_id AND task_id LIKE 'quiz_%'");
    $quiz_count = $quizRes->fetch_assoc()['total'] ?? 0;

    // Step 2: সব badge খুঁজি যেগুলো অর্জনযোগ্য
    $sql = "SELECT * FROM badges";
    $result = $conn->query($sql);

    while ($badge = $result->fetch_assoc()) {
        $type = $badge['condition_type'];
        $value = $badge['condition_value'];
        $eligible = false;

        if ($type === 'xp' && $total_xp >= $value) $eligible = true;
        elseif ($type === 'quiz_count' && $quiz_count >= $value) $eligible = true;

        if ($eligible) {
            // Check if already earned
            $chk = $conn->prepare("SELECT 1 FROM user_badges WHERE user_id=? AND badge_id=?");
            $chk->bind_param("ii", $user_id, $badge['id']);
            $chk->execute();
            $chk->store_result();

            if ($chk->num_rows === 0) {
                $stmt = $conn->prepare("INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $badge['id']);
                $stmt->execute();
                // Optional: Notification or log
            }
        }
    }
}



// এই ফাংশনটি আপনি XP Add করার পর কল করবেন:

// php
// Copy
// Edit
// addXP(...);
// checkAndAwardBadges($conn, $user_id);




function addXP($conn, $user_id, $email, $xp, $reason) {
    $sql = "INSERT INTO user_xp_log (user_id, email,  xp, reason) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isis", $user_id, $email, $xp, $reason);
    $stmt->execute();
}

// addXP($conn, $_SESSION['user_id'], 'engrreaz@gmail.com', 25, "Chapter Completed");



?>
