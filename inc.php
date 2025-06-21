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


$usr = $_SESSION['user'];
$user_id = $_SESSION['user_id'];


require_once 'utils.php';
