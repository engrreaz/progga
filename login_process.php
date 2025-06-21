<?php 
// login প্রসেসে, usersapp query ছাড়াও sessioninfo থেকে student detail fetch
// উদাহরণ:
$_SESSION['user_id'] = $user['id'];
$_SESSION['usr'] = $user['email'];
$_SESSION['role'] = $user['userlevel'];
if ($user['userlevel']==='student') {
    // Query sessioninfo টেবিলে student-এর record
    $stmt = $conn->prepare("SELECT sccode, stid, classname FROM sessioninfo WHERE stid=? LIMIT 1");
    $stmt->bind_param("s", $user['stid']); // ধরে নিচ্ছি usersapp-এর row-এ stid আছে
    $stmt->execute();
    $ri = $stmt->get_result()->fetch_assoc();
    $_SESSION['sccode'] = $ri['sccode'];
    $_SESSION['stid']   = $ri['stid'];
    $_SESSION['class']  = $ri['classname'];
    $stmt->close();
}
