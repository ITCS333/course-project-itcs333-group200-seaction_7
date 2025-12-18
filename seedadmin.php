<?php
require_once 'db.php';
$adminEmail = 'admin@itcs333.com';
$adminPass = password_hash('admin123', PASSWORD_DEFAULT);

// Only seed if no admin exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
$stmt->execute();
if ($stmt->rowCount() == 0) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->execute(['Admin', $adminEmail, $adminPass]);
    echo "Admin seeded. Email: $adminEmail Password: admin123";
} else {
    echo "Admin already exists.";
}
?>