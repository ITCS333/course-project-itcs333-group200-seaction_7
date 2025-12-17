<?php
include_once 'config.php';
include_once 'auth.php';

requireAdmin();

$total_students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students"));
$total_courses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM courses"));
$total_enrollments = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM enrollments"));
$active_students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students WHERE status='Active'"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Management System</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .dashboard-container {
            padding: 30px;
        }
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .welcome-section h1 {
            margin: 0 0 10px 0;
            font-size: 2em;
        }
        .welcome-section p {
            margin: 0;
            opacity: 0.9;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #667eea;
        }
        .stat-card.students {
            border-left-color: #4CAF50;
        }
        .stat-card.courses {
            border-left-color: #2196F3;
        }
        .stat-card.enrollments {
            border-left-color: #FF9800;
        }
        .stat-card.active {
            border-left-color: #9C27B0;
        }
        .stat-label {
            color: #666;
            font-size: 0.95em;
            margin-bottom: 10px;
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
        }
        .action-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .action-section h2 {
            margin-top: 0;
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 15px;
        }
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .quick-link-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1em;
        }
        .quick-link-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>! 👋</h1>
            <p>Student Management System - Dashboard</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card students">
                <div class="stat-label">Total Students</div>
                <div class="stat-number"><?php echo $total_students; ?></div>
            </div>
            <div class="stat-card courses">
                <div class="stat-label">Total Courses</div>
                <div class="stat-number"><?php echo $total_courses; ?></div>
            </div>
            <div class="stat-card enrollments">
                <div class="stat-label">Total Enrollments</div>
                <div class="stat-number"><?php echo $total_enrollments; ?></div>
            </div>
            <div class="stat-card active">
                <div class="stat-label">Active Students</div>
                <div class="stat-number"><?php echo $active_students; ?></div>
            </div>
        </div>

        <div class="action-section">
            <h2>Quick Actions</h2>
            <div class="quick-links">
                <a href="admin_student.php" class="quick-link-btn">👥 Manage Students</a>
                <a href="admin_change_password.php" class="quick-link-btn">🔐 Change Password</a>
                <a href="logout.php" class="quick-link-btn" style="background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);">🚪 Logout</a>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>
</body>
</html>