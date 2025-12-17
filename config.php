<?php
// Database Configuration
// Works for both XAMPP and Replit

$host = 'localhost';
$db_user = 'root';
$db_password = ''; // Empty for XAMPP, change if needed
$db_name = 'itcs333_project';

// For Replit, uncomment below and update:
// $host = 'your-replit-db-host';
// $db_user = 'your-username';
// $db_password = 'your-password';
// $db_name = 'your-database';

// Create connection
$conn = mysqli_connect($host, $db_user, $db_password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if (mysqli_query($conn, $sql)) {
    // Database created or already exists
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Select database
mysqli_select_db($conn, $db_name);

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Session configuration - only start if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$session_timeout = 1800; // 30 minutes

// Check session timeout
if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $session_timeout) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
$_SESSION['last_activity'] = time();
?>