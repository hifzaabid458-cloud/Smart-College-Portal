<?php

session_start();

if (!isset($_SESSION['teacher_id'])) {
    header("Location: teacher_login.php");
    exit();
}

include "db.php";

$teacher_id = $_SESSION['teacher_id'];
$teacher_name = $_SESSION['teacher_name'];
$teacher_email = $_SESSION['teacher_email'];
$course_id = $_SESSION['teacher_course_id'];


/* Get Assigned Course */

$sql = "SELECT
            courses.id,
            courses.course_name,
            courses.course_code,
            courses.credit_hours
        FROM courses
        WHERE courses.id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $course_id
);

$stmt->execute();

$course_result = $stmt->get_result();

$course = $course_result->fetch_assoc();

$stmt->close();


/* Count Students in Assigned Course */

$student_sql = "SELECT COUNT(DISTINCT student_id) AS total_students
                FROM results
                WHERE course_id = ?";

$stmt = $conn->prepare($student_sql);

$stmt->bind_param(
    "i",
    $course_id
);

$stmt->execute();

$student_result = $stmt->get_result();

$student_data = $student_result->fetch_assoc();

$total_students = $student_data['total_students'];

$stmt->close();

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
Teacher Dashboard - Smart College Portal
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


/* Welcome */

.welcome-card {
    background: white;

    padding: 30px;

    margin-top: 25px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.1);
}

.welcome-card h2 {
    color: #7c3aed;

    margin-bottom: 10px;
}


/* Dashboard Cards */

.dashboard-cards {
    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(220px, 1fr)
        );

    gap: 20px;

    margin-top: 25px;
}

.dashboard-card {
    background: white;

    padding: 25px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.1);
}

.dashboard-card h3 {
    color: #7c3aed;

    margin-bottom: 15px;
}

.dashboard-card p {
    color: #555;

    font-size: 16px;
}


/* Course Card */

.course-card {
    background: white;

    margin-top: 25px;

    padding: 30px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.1);
}

.course-card h2 {
    color: #7c3aed;

    margin-bottom: 15px;
}

.course-card p {
    margin: 8px 0;

    color: #444;
}


/* Buttons */

.dashboard-button {
    display: inline-block;

    margin-top: 15px;

    padding: 10px 15px;

    background: #7c3aed;

    color: white;

    text-decoration: none;

    border-radius: 6px;
}

.dashboard-button:hover {
    background: #5b21b6;
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

    .top-bar {
        flex-direction: column;

        align-items: flex-start;

        gap: 15px;
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


    <!-- Top Bar -->

    <div class="top-bar">

        <h1>
            Teacher Dashboard
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


    <!-- Welcome -->

    <div class="welcome-card">

        <h2>

            Welcome,

            <?php

            echo htmlspecialchars(
                $teacher_name
            );

            ?>

           ! 👋

        </h2>


        <p>

            You are successfully logged in
            to the Teacher Portal.

        </p>

    </div>


    <!-- Dashboard Cards -->

    <div class="dashboard-cards">


        <div class="dashboard-card">

            <h3>
                👨‍🎓 My Students
            </h3>

            <p>

                Total students in your
                assigned course:

                <strong>

                    <?php

                    echo $total_students;

                    ?>

                </strong>

            </p>


            <a
                href="teacher_students.php"
                class="dashboard-button"
            >

                View Students

            </a>

        </div>


        <div class="dashboard-card">

            <h3>
                📚 My Course
            </h3>

            <p>

                View your assigned course
                information.

            </p>


            <a
                href="teacher_courses.php"
                class="dashboard-button"
            >

                View Course

            </a>

        </div>


        <div class="dashboard-card">

            <h3>
                🎓 GPA Calculator
            </h3>

            <p>

                Calculate student GPA
                and academic performance.

            </p>


            <a
                href="teacher_gpa.php"
                class="dashboard-button"
            >

                Open Calculator

            </a>

        </div>


    </div>


    <!-- Assigned Course -->

    <div class="course-card">

        <h2>
            📚 Assigned Course
        </h2>


        <?php if ($course): ?>

            <p>

                <strong>
                    Course Name:
                </strong>

                <?php

                echo htmlspecialchars(
                    $course['course_name']
                );

                ?>

            </p>


            <p>

                <strong>
                    Course Code:
                </strong>

                <?php

                echo htmlspecialchars(
                    $course['course_code']
                );

                ?>

            </p>


            <p>

                <strong>
                    Credit Hours:
                </strong>

                <?php

                echo htmlspecialchars(
                    $course['credit_hours']
                );

                ?>

            </p>


        <?php else: ?>

            <p>
                No course has been assigned
                to you yet.
            </p>

        <?php endif; ?>

    </div>


</div>

</body>

</html>