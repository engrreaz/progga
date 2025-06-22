<?php
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('ENV', 'local');
    define('BASE_URL', 'http://localhost/eimbox-dashboard/progga/');
    define('IMG_BASE_URL', 'http://localhost/eimbox-dashboard/');
} else {
    define('ENV', 'server');
    define('BASE_URL', 'https://progga.eimbox.com');
    define('IMG_BASE_URL', 'https://eimbox.com');
}


$usr = $_SESSION['usr'] ?? '';
$cur = date('Y-m-d H:i:s');
$td = date('Y-m-d');
$sessionyear = date('Y');
$sccode =  $_SESSION['sccode'] ?? '';


require_once 'utils.php';
