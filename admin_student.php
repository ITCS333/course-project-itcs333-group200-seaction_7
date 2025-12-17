<?php
include_once 'config.php';
include_once 'auth.php';

requireAdmin();

$error = '';
$success = '';
$action = $_GET['action'] ?? '';
$student_id = $_GET['id'] ?? '';

// Handle delete
if ($action == 'delete' && $student_id) {
    if (deleteStudent($student_id)) {
        $success = 'Student deleted successfully';
        $action = '';
    } else {
        $error = 'Error deleting student';
    }
}

// Handle add/edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_action = $_POST['form_action'] ?? '';
    
    if ($post_action == 'add') {
        if (addStudent($_POST)) {
            $success = 'Student added successfully';
        } else {
            $error = 'Error adding student';
        }
    } elseif ($post_action == 'edit') {
        if (updateStudent($_POST['id'], $_POST)) {
            $success = 'Student updated successfully';
            $action = '';
        } else {
            $error = 'Error updating student';
        }
    }
}

$students = getAllStudents();
$edit_student = null;

if ($action == 'edit' && $student_id) {
    $edit_student = getStudent($student_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Student Management System</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .student-container {
            padding: 30px;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .page-header h1 {
            margin: 0;
            color: #333;
        }
        .add-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: transform 0.3s;
        }
        .add-btn:hover {
            transform: translateY(-2px);
        }
        .form-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .btn-submit {
            background: #667eea;
            color: white;
        }
        .btn-cancel {
            background: #999;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .students-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f5f5f5;
        }
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .action-btn {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .edit-btn {
            background: #2196F3;
            color: white;
        }
        .delete-btn {
            background: #f44336;
            color: white;
        }
        .action-btn:hover {
            transform: scale(1.05);
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="student-container">
        <div class="page-header">
            <h1>📚 Manage Students</h1>
            <?php if ($action != 'edit'): ?>
                <button class="add-btn" onclick="toggleAddForm()">+ Add New Student</button>
            <?php endif; ?>
            <?php if ($action == 'edit'): ?>
                <a href="admin_student.php" class="add-btn" style="background: #999; text-decoration: none;">← Back to List</a>
            <?php endif; ?>
        </div>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Add/Edit Form -->
        <div id="addFormSection" class="form-section" style="display: <?php echo ($action == 'edit') ? 'block' : 'none'; ?>;">
            <h2><?php echo ($action == 'edit') ? 'Edit Student' : 'Add New Student'; ?></h2>
            <form method="POST">
                <input type="hidden" name="form_action" value="<?php echo ($action == 'edit') ? 'edit' : 'add'; ?>">
                <?php if ($action == 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_student['id']; ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="student_id">Student ID</label>
                        <input type="text" id="student_id" name="student_id" required 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['student_id']) : ''; ?>"
                               <?php echo $edit_student ? 'readonly' : ''; ?>>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['first_name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['last_name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['phone']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" id="dob" name="dob" 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['date_of_birth']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="">Select</option>
                            <option value="Male" <?php echo ($edit_student && $edit_student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($edit_student && $edit_student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['city']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" 
                               value="<?php echo $edit_student ? htmlspecialchars($edit_student['country']) : ''; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"><?php echo $edit_student ? htmlspecialchars($edit_student['address']) : ''; ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">Save Student</button>
                    <button type="button" class="btn btn-cancel" onclick="toggleAddForm()">Cancel</button>
                </div>
            </form>
        </div>

        <!-- Students Table -->
        <div class="students-table">
            <?php if (count($students) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($student['city'] ?? 'N/A'); ?></td>
                                <td><span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 4px;"><?php echo $student['status']; ?></span></td>
                                <td>
                                    <a href="?action=edit&id=<?php echo $student['id']; ?>" class="action-btn edit-btn">Edit</a>
                                    <a href="?action=delete&id=<?php echo $student['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>No students found. <a href="#" onclick="toggleAddForm(); return false;">Add the first student</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        function toggleAddForm() {
            const form = document.getElementById('addFormSection');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>