-- Create Students Table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    date_of_birth DATE,
    gender VARCHAR(10),
    address TEXT,
    city VARCHAR(50),
    country VARCHAR(50),
    enrollment_date DATE DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Users Table (for login)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role VARCHAR(20) DEFAULT 'admin', -- admin, teacher, student
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    is_active TINYINT DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Courses Table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(50) UNIQUE NOT NULL,
    course_name VARCHAR(150) NOT NULL,
    description TEXT,
    credits INT,
    instructor VARCHAR(100),
    semester VARCHAR(20),
    status VARCHAR(20) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Enrollments Table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    grade VARCHAR(5),
    status VARCHAR(20) DEFAULT 'Enrolled',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO users (username, password, email, role, first_name, last_name, is_active) 
VALUES ('admin', 'admin123', 'admin@university.edu', 'admin', 'System', 'Administrator', 1)
ON DUPLICATE KEY UPDATE password='admin123';

-- Insert sample students
INSERT INTO students (student_id, first_name, last_name, email, phone, date_of_birth, gender, city, country) 
VALUES 
('STU001', 'Ahmed', 'Ali', 'ahmed@student.edu', '33123456', '2002-05-15', 'Male', 'Manama', 'Bahrain'),
('STU002', 'Fatima', 'Hassan', 'fatima@student.edu', '33234567', '2003-08-22', 'Female', 'Manama', 'Bahrain'),
('STU003', 'Mohammed', 'Abdullah', 'mohammed@student.edu', '33345678', '2002-12-10', 'Male', 'Riffa', 'Bahrain');

-- Insert sample courses
INSERT INTO courses (course_code, course_name, description, credits, instructor, semester) 
VALUES 
('ITCS333', 'Database Management Systems', 'Learn SQL and database design', 3, 'Dr. Smith', 'Spring 2025'),
('ITCS201', 'Web Development', 'HTML, CSS, JavaScript basics', 3, 'Prof. Johnson', 'Spring 2025'),
('ITCS101', 'Programming Fundamentals', 'Introduction to programming', 3, 'Dr. Brown', 'Spring 2025');

-- Insert sample enrollments
INSERT INTO enrollments (student_id, course_id, enrollment_date, status) 
VALUES 
(1, 1, CURRENT_DATE, 'Enrolled'),
(1, 2, CURRENT_DATE, 'Enrolled'),
(2, 1, CURRENT_DATE, 'Enrolled'),
(3, 3, CURRENT_DATE, 'Enrolled');