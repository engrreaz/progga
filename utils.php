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
?>
