<?php
session_start(); // যদি session না শুরু করা থাকে

// সমস্ত কুকির নামগুলো খুঁজে নিয়ে expire করে দিন
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);

    foreach ($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);

        // কুকি মেয়াদ শেষ
        setcookie($name, '', time() - 3600, '/');
    }
}

// সেশন রিমুভ
session_unset();
session_destroy();

// লগইন পেইজে পাঠানো
header("Location: login.php");
exit;
?>
