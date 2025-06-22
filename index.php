<?php
// Redirect to login or dashboard
session_start();
include('inc.php');
if (!empty($_SESSION['usr'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
