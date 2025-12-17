<?php
// Prevent multiple inclusions
if (defined('AUTH_PHP_INCLUDED')) {
    return;
}
define('AUTH_PHP_INCLUDED', true);

include 'config.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

// Check if user is admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] == 'admin';
}

// Redirect to login if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Redirect to login if not admin
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit;
    }
}

// Get current user info
function getCurrentUser() {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// Verify login credentials
function verifyLogin($username, $password) {
    global $conn;
    
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND is_active = 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['last_activity'] = time();
        
        // Update last login
        $update_query = "UPDATE users SET last_login = NOW() WHERE id = " . $user['id'];
        mysqli_query($conn, $update_query);
        
        return true;
    }
    return false;
}

// Log out user
function logout() {
    session_destroy();
}

// Get all students
function getAllStudents() {
    global $conn;
    $query = "SELECT * FROM students ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Get student by ID
function getStudent($id) {
    global $conn;
    $id = (int)$id;
    $query = "SELECT * FROM students WHERE id = $id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Add new student
function addStudent($data) {
    global $conn;
    
    $student_id = mysqli_real_escape_string($conn, $data['student_id']);
    $first_name = mysqli_real_escape_string($conn, $data['first_name']);
    $last_name = mysqli_real_escape_string($conn, $data['last_name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $gender = mysqli_real_escape_string($conn, $data['gender']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $city = mysqli_real_escape_string($conn, $data['city']);
    $country = mysqli_real_escape_string($conn, $data['country']);
    
    $query = "INSERT INTO students (student_id, first_name, last_name, email, phone, date_of_birth, gender, address, city, country) 
              VALUES ('$student_id', '$first_name', '$last_name', '$email', '$phone', '$dob', '$gender', '$address', '$city', '$country')";
    
    return mysqli_query($conn, $query);
}

// Update student
function updateStudent($id, $data) {
    global $conn;
    
    $id = (int)$id;
    $first_name = mysqli_real_escape_string($conn, $data['first_name']);
    $last_name = mysqli_real_escape_string($conn, $data['last_name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $gender = mysqli_real_escape_string($conn, $data['gender']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $city = mysqli_real_escape_string($conn, $data['city']);
    $country = mysqli_real_escape_string($conn, $data['country']);
    
    $query = "UPDATE students SET first_name='$first_name', last_name='$last_name', email='$email', phone='$phone', date_of_birth='$dob', gender='$gender', address='$address', city='$city', country='$country' WHERE id=$id";
    
    return mysqli_query($conn, $query);
}

// Delete student
function deleteStudent($id) {
    global $conn;
    $id = (int)$id;
    $query = "DELETE FROM students WHERE id=$id";
    return mysqli_query($conn, $query);
}
?>