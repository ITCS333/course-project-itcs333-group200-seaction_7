<?php
require_once 'auth.php';
requireLogin();
requireAdmin();
include 'partials/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    $user = fetchUser($_SESSION['user']['id']);

    if (!password_verify($old, $user['password'])) {
        $error = "Old password incorrect";
    } elseif ($new !== $confirm) {
        $error = "Passwords do not match";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $user['id']]);
        $_SESSION['user'] = fetchUser($user['id']);
        $success = "Password updated successfully!";
    }
}
?>
<div class="container mt-5" style="max-width: 450px">
    <h2 class="mb-3">Change Password</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group mb-2">
            <label>Old Password</label>
            <input type="password" name="old_password" required class="form-control"/>
        </div>
        <div class="form-group mb-2">
            <label>New Password</label>
            <input type="password" name="new_password" required class="form-control"/>
        </div>
        <div class="form-group mb-2">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required class="form-control"/>
        </div>
        <button class="btn btn-success w-100">Update</button>
    </form>
</div>
<?php include 'partials/footer.php'; ?>
