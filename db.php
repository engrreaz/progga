<?php
session_start();
require_once 'inc.php';

if (ENV == 'local') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "eimbox";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
} else {
    include '../db.php';
}



if (isset($_COOKIE['usr'])) {
    $usr = $_COOKIE['usr'];
} else {
    $usr = $_SESSION['usr'] ?? '';
}



if (isset($_COOKIE['sccode'])) {
    $sccode = $_COOKIE['sccode'];
} else {
    $sccode = $_SESSION['sccode'] ?? '';
}
if (isset($_COOKIE['userlevel'])) {
    $userlevel = $_COOKIE['userlevel'];
} else {
    $userlevel = $_SESSION['userlevel'] ?? '';
}
if (isset($_COOKIE['userid'])) {
    $userid = $_COOKIE['userid'];
} else {
    $userid = $_SESSION['userid'] ?? '';
}
if (isset($_COOKIE['stid'])) {
    $stid = $_COOKIE['stid'];
} else {
    $stid = $_SESSION['stid'] ?? '';
}
if (isset($_COOKIE['clsname'])) {
    $clsname = $_COOKIE['clsname'];
} else {
    $clsname = $_SESSION['clsname'] ?? '';
}


// echo $usr . '/' . $sccode . '/' . $userlevel . '/' . $userid;

$cur = date('Y-m-d H:i:s');
$td = date('Y-m-d');
$sessionyear = date('Y');



// if (empty($_SESSION['usr'])) {
// header('Location: login.php');
// exit;
// }

include 'header.php';




require_once 'utils.php';
require_once 'nav.php';
