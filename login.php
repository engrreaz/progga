<?php
require_once 'db-login.php';



$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password'];

  $sql = "SELECT id, email, fixedpin, userlevel, userid FROM usersapp WHERE email=?";
  // echo $sql;
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $res = $stmt->get_result();
  // echo $res->num_rows;
  if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();
    // if (password_verify($password, $user['fixedpin'])) {
    // echo $user['fixedpin'];
    // if ($password==$user['fixedpin']) {

    $usr = $user['email'];
    $sccode = $user['sccode'];
    $userlevel = $user['userlevel'];
    $userid = $user['userid'];

    $_SESSION['usr'] = $usr;
    $_SESSION['sccode'] = $sccode;
    $_SESSION['userlevel'] = $userlevel;
    $_SESSION['userid'] = $userid;

    setcookie("usr", $usr, time() + (86400 * 30), "/");
    setcookie("sccode", $sccode, time() + (86400 * 30), "/");
    setcookie( "userlevel", $userlevel, time() + (86400 * 30), "/");
    setcookie("userid", $userid, time() + (86400 * 30), "/");

    if ($user['userlevel'] === 'student') {
      // Query sessioninfo টেবিলে student-এর record
      $stmt = $conn->prepare("SELECT sccode, stid, classname FROM sessioninfo WHERE stid=? LIMIT 1");
      $stmt->bind_param("s", $user['stid']); // ধরে নিচ্ছি usersapp-এর row-এ stid আছে
      $stmt->execute();
      $ri = $stmt->get_result()->fetch_assoc();

      $stid = $ri['stid'] ?? '';
      $clsname = $ri['classname'] ?? '';

      $_SESSION['stid'] = $stid;
      $_SESSION['clsname'] = $clsname;

      $stmt->close();
    }
    setcookie("stid", $stid, time() + (86400 * 30), "/"); // 30 দিন
    setcookie("clsname", $clsname, time() + (86400 * 30), "/"); // 30 দিন



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
            <input type="password" name="password" class="form-control" autocomplete="new-password" required>
          </div>
          <button class="btn btn-primary w-100">লগইন</button>
        </form>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>

</html>