<?php
session_start();

// Database credentials for the new subdomain LMS
$dbHost = 'localhost';
$dbUser = 'eimbox_user';
$dbPass = 'your_password';
$dbName = 'eimbox';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("ডাটাবেস সংযোগে সমস্যা: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>