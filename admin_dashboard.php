<?php
require_once 'auth.php';
requireLogin();
requireAdmin();
include 'partials/header.php'; ?>
<div class="container mt-5">
    <h1>Admin Dashboard</h1>
    <div class="list-group mt-4">
        <a href="admin_student.php" class="list-group-item list-group-item-action">Manage Students</a>
        <a href="admin_change_password.php" class="list-group-item list-group-item-action">Change Password</a>
        <a href="logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
    </div>
</div>
<?php include 'partials/footer.php'; ?>