<?php
include 'config.php';
include 'auth.php';

// Redirect to login if not logged in
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Redirect to dashboard if logged in
header("Location: admin_dashboard.php");
exit;
?>