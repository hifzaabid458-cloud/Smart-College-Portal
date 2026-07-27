<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$message = "";

if (isset($_POST['add_result'])) {

    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $marks = $_POST['marks'];
    $semester = $_POST['semester'];

    // Calculate grade and grade point
    if ($marks >= 85) {
        $grade = "A";
        $grade_point = 4.00;
    } elseif ($marks >= 80) {
        $grade = "A-";
        $grade_point = 3.70;
    } elseif ($marks >= 75) {
        $grade = "B+";
        $grade_point = 3.30;
    } elseif ($marks >= 70) {
        $grade = "B";
        $grade_point = 3.00;
    } elseif ($marks >= 65) {
        $grade = "B-";
        $grade_point = 2.70;
    } elseif ($marks >= 60) {
        $grade = "C+";
        $grade_point = 2.30;
    } elseif ($marks >= 50) {
        $grade = "C";
        $grade_point = 2.00;
    } else {
        $grade = "F";
        $grade_point = 0.00;
    }

    $sql = "INSERT INTO results
            (student_id, course_id, marks, grade, grade_point, semester)
            VALUES
            ('$student_id', '$course_id', '$marks', '$grade',
             '$grade_point', '$semester')";

    if (mysqli_query($conn, $sql)) {
        $message = "Result added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Manage Records - Smart College Portal</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f3e8ff;
    color: #222;
}

/* Sidebar */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 240px;
    height: 100vh;
    background: #1e1b4b;
    padding: 25px 15px;
}

.sidebar h2 {
    color: white;
    text-align: center;
    margin-bottom: 30px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 14px 15px;
    margin: 5px 0;
    border-radius: 6px;
}

.sidebar a:hover {
    background: #7c3aed;
}

.logout-link {
    margin-top: 30px !important;
}

/* Main Content */

.dashboard-content {
    margin-left: 240px;
    padding: 35px;
    min-height: 100vh;
    background: #f3e8ff;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-bar h1 {
    color: #1e1b4b;
}

.admin-info {
    background: white;
    padding: 12px 20px;
    border-radius: 8px;
}

/* Back Button */

.back {
    display: inline-block;
    margin-top: 25px;
    padding: 10px 15px;
    background: #7c3aed;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.back:hover {
    background: #5b21b6;
}

/* Card */

.form-card {
    background: white;
    margin-top: 25px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}

/* Form */

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 7px;
    color: #1e1b4b;
    font-weight: bold;
}

select,
input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
}

button {
    padding: 12px 20px;
    background: #7c3aed;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
}

button:hover {
    background: #5b21b6;
}

.message {
    margin-bottom: 20px;
    padding: 12px;
    background: #f3e8ff;
    color: #1e1b4b;
    border-radius: 6px;
}

</style>

</head>

<body>

<div class="sidebar">

    <h2>Smart College</h2>

    <a href="admin_dashboard.php">🏠 Dashboard</a>

    <a href="manage_students.php">👨‍🎓 Manage Students</a>

    <a href="manage_courses.php">📚 Manage Courses</a>

    <a href="manage_teachers.php">👨‍🏫 Manage Teachers</a>

    <a href="manage_records.php">📋 Manage Records</a>
	
	<a href="admin_manage_fees.php">
    💰 Manage Fees
</a>

<a href="admin_manage_announcements.php">
    📢 Manage Announcements
</a>

<a href="admin_manage_assignments.php">
    📄 Manage Assignments
</a>
	
	 <a href="manage_attendance.php">
        📊 Manage Attendance
    </a>
		
	<a href="teacher_gpa.php">🎓 Teacher GPA Calculator</a>

    <a href="view_results.php">📊 View Results</a>

    <a href="calculate_sgpa.php">📊 Calculate SGPA</a>
	
	<a href="calculate_cgpa.php">📈 Calculate CGPA</a>
	
	<a href="admin_reports.php">
        📊 Reports & Analytics
    </a>

    <a href="admin_logout.php" class="logout-link">
        🚪 Logout
    </a>

</div>


<div class="dashboard-content">

    <div class="top-bar">

        <h1>Manage Records</h1>

        <div class="admin-info">
            👤 Administrator
        </div>

    </div>


    <a href="admin_dashboard.php" class="back">
        ← Back to Dashboard
    </a>


    <div class="form-card">

        <h2>📋 Add Student Result</h2>

        <?php if ($message != ""): ?>

            <div class="message">
                <?php echo $message; ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label>Select Student</label>

                <select name="student_id" required>

                    <option value="">
                        Select Student
                    </option>

                    <?php

                    $students =
                        mysqli_query(
                            $conn,
                            "SELECT id, full_name
                             FROM users
                             ORDER BY full_name ASC"
                        );

                    while ($student =
                           mysqli_fetch_assoc($students)):

                    ?>

                    <option value="<?php echo $student['id']; ?>">

                        <?php
                        echo $student['full_name'];
                        ?>

                    </option>

                    <?php endwhile; ?>

                </select>

            </div>


            <div class="form-group">

                <label>Select Course</label>

                <select name="course_id" required>

                    <option value="">
                        Select Course
                    </option>

                    <?php

                    $courses =
                        mysqli_query(
                            $conn,
                            "SELECT id, course_name,
							credit_hours
                             FROM courses
                             ORDER BY course_name ASC"
                        );

                    while ($course =
                           mysqli_fetch_assoc($courses)):

                    ?>

                    <option value="<?php echo $course['id']; ?>">

    <?php

    echo $course['course_name'];

    echo " (";

    echo $course['credit_hours'];

    echo " Credit Hours)";

    ?>

</option>
                    <?php endwhile; ?>

                </select>

            </div>


            <div class="form-group">

                <label>Marks</label>

                <input
                    type="number"
                    name="marks"
                    min="0"
                    max="100"
                    required
                >

            </div>


            <div class="form-group">

                <label>Semester</label>

                <select name="semester" required>

                    <option value="">
                        Select Semester
                    </option>

                    <option>Semester 1</option>
                    <option>Semester 2</option>
                    <option>Semester 3</option>
                    <option>Semester 4</option>
                    <option>Semester 5</option>
                    <option>Semester 6</option>
                    <option>Semester 7</option>
                    <option>Semester 8</option>

                </select>

            </div>


            <button
                type="submit"
                name="add_result"
            >
                Add Result
            </button>

        </form>

    </div>

</div>

</body>

</html>