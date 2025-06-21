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




require_once 'utils.php';
require_once 'nav.php';
