<?php
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password'];

  $sql = "SELECT id, email, fixedpin, userlevel FROM usersapp WHERE email=?";
  echo $sql;
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $res = $stmt->get_result();
  echo $res->num_rows;
  if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();
    // if (password_verify($password, $user['fixedpin'])) {
    echo $user['fixedpin'];
    // if ($password==$user['fixedpin']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['usr'] = $user['email'];
    $_SESSION['role'] = 'student';// $user['userlevel'];


    if ($user['userlevel'] === 'student' || $user['userlevel'] === 'Super Administrator') {
      // Query sessioninfo টেবিলে student-এর record
      $stmt = $conn->prepare("SELECT sccode, stid, classname FROM sessioninfo WHERE stid=? LIMIT 1");
      $stmt->bind_param("s", $user['stid']); // ধরে নিচ্ছি usersapp-এর row-এ stid আছে
      $stmt->execute();
      $ri = $stmt->get_result()->fetch_assoc();
      $_SESSION['sccode'] = '103187'; // $ri['sccode'];
      $_SESSION['stid'] = '1031871299';// $ri['stid'];
      $_SESSION['class'] = '9';//$ri['classname'];
      $stmt->close();
    }
    header('Location: dashboard.php');
    exit;
    // }
  }
  $error = 'অগ্রহণযোগ্য ইউজারনেম বা পাসওয়ার্ড';
}
?>
<!DOCTYPE html>
<html lang="bn">

<head>
  <?php include 'header.php'; ?>
  <title>লগইন</title>
</head>

<body class="d-flex align-items-center vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <h2 class="mb-4 text-center">সিস্টেমে লগইন</h2>
        <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="">
          <div class="mb-3">
            <label class="form-label">ইউজারনেম</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">পাসওয়ার্ড</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">লগইন</button>
        </form>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>

</html>