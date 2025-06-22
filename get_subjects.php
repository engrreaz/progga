<?php
require_once 'db.php';
$class_id = intval($_GET['class_id'] ?? 0);
$data = [];
if ($class_id > 0) {
  $stmt = $conn->prepare("SELECT subcode, subject_name FROM subjects WHERE class_id=?");
  $stmt->bind_param("i", $class_id);
  $stmt->execute();
  $res = $stmt->get_result();
  while ($row = $res->fetch_assoc()) {
    $data[] = $row;
  }
  $stmt->close();
}

$data = [
    ['subcode' => 101, 'subject_name' => 'Bengali 1st Paper'],
    ['subcode' => 102, 'subject_name' => 'Bengali 2nd Paper'],
    ['subcode' => 107, 'subject_name' => 'English 1st Paper'],
    ['subcode' => 108, 'subject_name' => 'English 2nd Paper'],
    ['subcode' => 109, 'subject_name' => 'Mathematics'],
];
header('Content-Type: application/json');
echo json_encode($data);
