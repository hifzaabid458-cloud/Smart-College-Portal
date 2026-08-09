<?php

session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

include "db.php";

$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
$course_id = $_SESSION['teacher_course_id'];


/* Get Teacher's Course */

$course_sql = "SELECT
                    course_name,
                    course_code,
                    credit_hours
               FROM courses
               WHERE id = ?";

$stmt = $conn->prepare($course_sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();

$course_result = $stmt->get_result();
$course = $course_result->fetch_assoc();

$stmt->close();


/* Get Students Connected to Teacher's Course */

$sql = "SELECT DISTINCT
            users.id,
            users.full_name,
            users.email
        FROM results
        JOIN users
            ON results.student_id = users.id
        WHERE results.course_id = ?
          AND users.role = 'student'
        ORDER BY users.full_name ASC";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $course_id
);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
My Students - Smart College Portal
</title>

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

    overflow-y: auto;
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


/* Top Bar */

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


/* Students Card */

.students-card {
    background: white;

    margin-top: 25px;

    padding: 30px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.1);
}

.students-card h2 {
    color: #7c3aed;

    margin-bottom: 10px;
}

.course-info {
    color: #555;

    margin-bottom: 20px;
}


/* Table */

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;

    border-collapse: collapse;

    border: 1px solid #e5d5f7;
}

th,
td {
    padding: 15px;

    text-align: left;

    border: 1px solid #e5d5f7;
}

th {
    background: #f3e8ff;

    color: #1e1b4b;
}

td {
    background: white;

    color: #333;
}

tr:hover td {
    background: #f9f7ff;
}


/* Empty */

.empty-message {
    text-align: center;

    padding: 25px;

    color: #666;
}


/* Responsive */

@media (max-width: 700px) {

    .sidebar {
        width: 200px;
    }

    .dashboard-content {
        margin-left: 200px;

        padding: 20px;
    }

    th,
    td {
        padding: 10px;

        font-size: 14px;
    }

}

</style>

</head>

<body>


<!-- Sidebar -->

<div class="sidebar">

    <h2>
        Smart College
    </h2>

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

    <a
        href="teacher_logout.php"
        class="logout-link"
    >
        🚪 Logout
    </a>

</div>


<!-- Main Content -->

<div class="dashboard-content">


    <div class="top-bar">

        <h1>
            My Students
        </h1>

        <div class="teacher-info">

            👨‍🏫

            <?php

            echo htmlspecialchars(
                $teacher_name
            );

            ?>

        </div>

    </div>


    <a
        href="teacher_dashboard.php"
        class="back"
    >
        ← Back to Dashboard
    </a>


    <div class="students-card">

        <h2>
            👨‍🎓 Students in My Course
        </h2>


        <?php if ($course): ?>

            <div class="course-info">

                <strong>
                    Course:
                </strong>

                <?php
                echo htmlspecialchars(
                    $course['course_name']
                );
                ?>

                &nbsp; | &nbsp;

                <strong>
                    Code:
                </strong>

                <?php
                echo htmlspecialchars(
                    $course['course_code']
                );
                ?>

            </div>

        <?php endif; ?>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>
                            Student ID
                        </th>

                        <th>
                            Student Name
                        </th>

                        <th>
                            Email
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php

                if ($result->num_rows > 0) {

                    while (
                        $student =
                        $result->fetch_assoc()
                    ) {

                ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $student['id']
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $student['full_name']
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $student['email']
                            );

                            ?>

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="3"
                            class="empty-message"
                        >

                            No students are currently
                            connected to your course.

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        </div>

    </div>


</div>

</body>

</html>