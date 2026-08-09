<?php

session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

include "db.php";

$teacher_id = $_SESSION['teacher_id'];

/* Get teacher's assigned course */

$sql = "SELECT
            courses.id,
            courses.course_name,
            courses.course_code,
            courses.credit_hours
        FROM teachers
        JOIN courses
            ON teachers.course_id = courses.id
        WHERE teachers.id = '$teacher_id'";

$result = mysqli_query($conn, $sql);

$teacher = $_SESSION['teacher_name'] ?? 'Teacher';

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>My Course - Smart College Portal</title>

<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: #f3e8ff;
}

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

.dashboard-content {
    margin-left: 240px;
    padding: 35px;
    min-height: 100vh;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-bar h1 {
    color: #1e1b4b;
}

.teacher-info {
    background: white;
    padding: 12px 20px;
    border-radius: 8px;
}

.card {
    background: white;
    margin-top: 30px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}

.course-box {
    background: #f3e8ff;
    padding: 25px;
    border-radius: 10px;
}

.course-box p {
    margin: 12px 0;
    color: #333;
}

.back {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 15px;
    background: #7c3aed;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.back:hover {
    background: #5b21b6;
}

</style>

</head>

<body>

<div class="sidebar">

    <h2>Smart College</h2>

    <a href="teacher_dashboard.php">
        🏠 Dashboard
    </a>

    <a href="teacher_students.php">
        👨‍🎓 My Students
    </a>

    <a href="teacher_courses.php">
        📚 My Course
    </a>

    <a href="teacher_gpa.php">
        🎓 GPA Calculator
    </a>

    <a href="teacher_logout.php">
        🚪 Logout
    </a>

</div>


<div class="dashboard-content">

    <div class="top-bar">

        <h1>
            My Course
        </h1>

        <div class="teacher-info">

            👨‍🏫 Welcome,

            <strong>
                <?php echo htmlspecialchars($teacher); ?>
            </strong>

        </div>

    </div>


    <div class="card">

        <h2>
            📚 My Assigned Course
        </h2>

        <?php

        if ($result && mysqli_num_rows($result) > 0) {

            $course = mysqli_fetch_assoc($result);

        ?>

            <div class="course-box">

                <p>
                    <strong>Course Name:</strong>
                    <?php echo htmlspecialchars($course['course_name']); ?>
                </p>

                <p>
                    <strong>Course Code:</strong>
                    <?php echo htmlspecialchars($course['course_code']); ?>
                </p>

                <p>
                    <strong>Credit Hours:</strong>
                    <?php echo htmlspecialchars($course['credit_hours']); ?>
                </p>

            </div>

        <?php

        } else {

        ?>

            <div class="course-box">

                No course is currently assigned to you.

            </div>

        <?php

        }

        ?>

    </div>

</div>

</body>

</html>