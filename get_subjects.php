<?php
require_once 'db-backend.php';
$class_id = intval($_GET['class_id'] ?? 0);
$data = [];
if ($class_id != '') {


  $stmt = $conn->prepare("  SELECT * FROM (SELECT DISTINCT subject FROM subjectsettinglist WHERE sccategory='School' and  classname=? order by subject) AS t1 JOIN subjects t2 ON t1.subject = t2.subcode;");
  $stmt->bind_param("i", $class_id);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $data[] = $row;
  }
  $stmt->close();
}

// $data = [
//   ['subcode' => 101, 'subject' => 'Bengali 1st Paper'],
//   ['subcode' => 102, 'subject' => 'Bengali 2nd Paper'],
//   ['subcode' => 107, 'subject' => 'English 1st Paper'],
//   ['subcode' => 108, 'subject' => 'English 2nd Paper'],
//   ['subcode' => 109, 'subject' => 'Mathematics'],
// ];
header('Content-Type: application/json');
echo json_encode($data);
