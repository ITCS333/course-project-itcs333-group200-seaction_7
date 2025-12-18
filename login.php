<?php
require_once 'auth.php';
if (isLoggedIn()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'db.php';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: '.(isAdmin() ? 'admin_dashboard.php' : 'index.php'));
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
include 'partials/header.php';
?>
<div class="container mt-5" style="max-width: 400px">
    <h2 class="mb-3">Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
        <div class="form-group mb-2">
            <label>Email</label>
            <input type="email" name="email" required class="form-control"/>
        </div>
        <div class="form-group mb-2">
            <label>Password</label>
            <input type="password" name="password" required class="form-control"/>
        </div>
        <button class="btn btn-primary w-100">Login</button>
    </form>
</div>
<?php include 'partials/footer.php'; ?>