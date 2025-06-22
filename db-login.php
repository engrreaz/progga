<?php
session_start();
require_once 'inc.php';
echo ENV;
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


$cur = date('Y-m-d H:i:s');
$td = date('Y-m-d');
$sessionyear = date('Y');


if ($usr != '') {
    header('Location: dashboard.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>প্রজ্ঞা — AI·PLS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/theme.css">

</head>

<body class="theme-light">

    <div id="splash" hidden>
        <img src="assets/images/progga.png" style="width:100px;" />
        <h1 class="m-0 p-0">প্রজ্ঞা</h1>
        <h6 class="m-0 p-0">শেখো, নিজের মতো করে</h6>
        <div class="loader"></div>
    </div>



    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

        </div>
    </nav>


    <div class="container mt-4">