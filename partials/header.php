<?php
// Prevent multiple inclusions
if (defined('HEADER_PHP_INCLUDED')) {
    return;
}
define('HEADER_PHP_INCLUDED', true);

include_once __DIR__ . '/../auth.php';
requireAdmin();

// Get the base path
$base_path = '/php-practice/TASK1/';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/style.css">
</head>
<body>
    <header class="navbar">
        <div class="navbar-brand">
            <span class="logo-text">📚 Student Management System</span>
        </div>
        <nav class="navbar-nav">
            <a href="<?php echo $base_path; ?>admin_dashboard.php" class="nav-link">Dashboard</a>
            <a href="<?php echo $base_path; ?>admin_student.php" class="nav-link">Students</a>
            <a href="<?php echo $base_path; ?>admin_change_password.php" class="nav-link">Settings</a>
            <span class="nav-user">
                👤 <?php echo htmlspecialchars($_SESSION['first_name']); ?>
            </span>
            <a href="<?php echo $base_path; ?>logout.php" class="nav-link logout-link">Logout</a>
        </nav>
    </header>