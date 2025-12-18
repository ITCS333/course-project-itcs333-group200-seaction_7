<?php
require_once 'auth.php';
requireLogin();
requireAdmin();

// CRUD Action Handlers
$action = $_GET['action'] ?? '';
$student_id = $_GET['id'] ?? '';
$msg = '';

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $studentid = $_POST['student_id'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, student_id, email, password, role) VALUES (?, ?, ?, ?, 'student')");
    try {
        $stmt->execute([$name, $studentid, $email, $password]);
        $msg = "Student added successfully!";
    } catch(PDOException $e) {
        $msg = "Error: ". $e->getMessage();
    }
}

// Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_student'])) {
    $id = $_POST['sid'];
    $name = $_POST['name'];
    $studentid = $_POST['student_id'];
    $email = $_POST['email'];
    $stmt = $pdo->prepare("UPDATE users SET name=?, student_id=?, email=? WHERE id=? AND role='student'");
    $stmt->execute([$name, $studentid, $email, $id]);
    $msg = "Student info updated!";
}

// Delete
if ($action === 'delete' && $student_id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=? AND role='student'");
    $stmt->execute([$student_id]);
    header("Location: admin_student.php?msg=deleted");
    exit;
}

// Fetch for edit
$editStudent = null;
if ($action === 'edit' && $student_id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=? AND role='student'");
    $stmt->execute([$student_id]);
    $editStudent = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all students
$stmt = $pdo->prepare("SELECT * FROM users WHERE role='student' ORDER BY name");
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'partials/header.php';
?>
<div class="container mt-5">
    <h2>Manage Students</h2>
    <?php if ($msg): ?><div class="alert alert-info"><?= $msg ?></div><?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success">Student deleted.</div>
    <?php endif; ?>
    <div class="row">
        <!-- Student Table -->
        <div class="col-md-7 mb-3">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-secondary">
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($students as $stu): ?>
                    <tr>
                        <td><?= htmlspecialchars($stu['name']) ?></td>
                        <td><?= htmlspecialchars($stu['student_id']) ?></td>
                        <td><?= htmlspecialchars($stu['email']) ?></td>
                        <td>
                            <a href="admin_student.php?action=edit&id=<?= $stu['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                            <a href="admin_student.php?action=delete&id=<?= $stu['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Add/Edit Form -->
        <div class="col-md-5 mb-3">
            <h5><?= $editStudent ? 'Edit Student' : 'Add Student' ?></h5>
            <form method="post">
                <?php if ($editStudent): ?>
                    <input type="hidden" name="sid" value="<?= $editStudent['id'] ?>"/>
                <?php endif; ?>
                <div class="form-group mb-2">
                    <label>Name</label>
                    <input type="text" name="name" required class="form-control"
                        value="<?= $editStudent['name'] ?? '' ?>" />
                </div>
                <div class="form-group mb-2">
                    <label>Student ID</label>
                    <input type="text" name="student_id" class="form-control"
                        value="<?= $editStudent['student_id'] ?? '' ?>" />
                </div>
                <div class="form-group mb-2">
                    <label>Email</label>
                    <input type="email" name="email" required class="form-control"
                        value="<?= $editStudent['email'] ?? '' ?>" />
                </div>
                <?php if (!$editStudent): ?>
                <div class="form-group mb-2">
                    <label>Default Password</label>
                    <input type="password" name="password" required class="form-control" value="student123" />
                </div>
                <?php endif; ?>
                <div class="form-group mb-2">
                    <?php if ($editStudent): ?>
                        <button name="edit_student" class="btn btn-warning">Update</button>
                        <a href="admin_student.php" class="btn btn-secondary">Cancel</a>
                    <?php else: ?>
                        <button name="add_student" class="btn btn-success">Add Student</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'partials/footer.php'; ?>