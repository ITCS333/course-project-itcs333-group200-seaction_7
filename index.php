<?php require_once 'auth.php'; include 'partials/header.php'; ?>
<div class="container mt-5">
    <div class="jumbotron text-center p-5 bg-light">
        <h1 class="display-4">Welcome to ITCS333 Course Page</h1>
        <p class="lead">A secure portal for students and the course teacher.</p>
        <?php if (!isLoggedIn()): ?>
            <a href="login.php" class="btn btn-primary btn-lg">Login</a>
        <?php else: ?>
            <a href="<?= isAdmin() ? 'admin_dashboard.php' : '#' ?>" class="btn btn-success btn-lg">
                <?= isAdmin() ? 'Admin Dashboard' : 'Student Dashboard' ?>
            </a>
            <a href="logout.php" class="btn btn-outline-secondary ml-2">Logout</a>
        <?php endif; ?>
    </div>
</div>
<?php include 'partials/footer.php'; ?>