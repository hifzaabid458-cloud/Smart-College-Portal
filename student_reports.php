<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$student_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];


// Get Student Results

$sql = "SELECT
            courses.course_name,
            courses.credit_hours,
            results.marks,
            results.grade,
            results.semester
        FROM results
        JOIN courses
            ON results.course_id = courses.id
        WHERE results.student_id = '$student_id'
        ORDER BY results.semester ASC";

$result = mysqli_query($conn, $sql);


// Arrays

$student_results = [];
$semester_data = [];
$course_data = [];

$total_marks = 0;
$total_subjects = 0;
$total_grade_points = 0;

$best_course = "N/A";
$best_marks = 0;

$weakest_course = "N/A";
$weakest_marks = 101;


// Process Results

while ($row = mysqli_fetch_assoc($result)) {

    $student_results[] = $row;

    $total_subjects++;

    $total_marks += $row['marks'];



    // Best Course

    if ($row['marks'] > $best_marks) {

        $best_marks = $row['marks'];

        $best_course = $row['course_name'];
    }


    // Weakest Course

    if ($row['marks'] < $weakest_marks) {

        $weakest_marks = $row['marks'];

        $weakest_course = $row['course_name'];
    }


    // Course Data

    $course_data[] = [
        "name" => $row['course_name'],
        "marks" => $row['marks']
    ];


    // Semester Data

    $semester = $row['semester'];

    if (!isset($semester_data[$semester])) {

        $semester_data[$semester] = [
            "total_marks" => 0,
            "subjects" => 0
        ];
    }

    $semester_data[$semester]['total_marks']
        += $row['marks'];

    $semester_data[$semester]['subjects']++;
}


// Average Marks

if ($total_subjects > 0) {

    $average_marks =
        $total_marks / $total_subjects;

    $average_gpa =
        $total_grade_points / $total_subjects;

} else {

    $average_marks = 0;
    $average_gpa = 0;
}


// CGPA

$cgpa = $average_gpa;

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width,
               initial-scale=1.0">

<title>
Student Reports & Analytics
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


/* SIDEBAR */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 240px;
    height: 100vh;
    background: #1e1b4b;
    padding: 20px 15px;
    overflow-y: auto;
}

.sidebar h2 {
    color: white;
    text-align: center;
    margin-bottom: 25px;
}

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 12px 15px;
    margin: 5px 0;
    border-radius: 6px;
}

.sidebar a:hover {
    background: #7c3aed;
}


/* MAIN CONTENT */

.dashboard-content {
    margin-left: 240px;
    padding: 35px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.top-bar h1 {
    color: #1e1b4b;
}

.student-info {
    background: white;
    padding: 12px 20px;
    border-radius: 8px;
}


/* SUMMARY CARDS */

.summary-cards {
    display: grid;
    grid-template-columns:
        repeat(
            auto-fit,
            minmax(200px, 1fr)
        );
    gap: 20px;
    margin-top: 30px;
}

.summary-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);
}

.summary-card h3 {
    color: #7c3aed;
    margin-bottom: 12px;
}

.summary-card p {
    font-size: 25px;
    font-weight: bold;
    color: #1e1b4b;
}


/* ANALYTICS CARDS */

.analytics-grid {
    display: grid;
    grid-template-columns:
        repeat(
            auto-fit,
            minmax(350px, 1fr)
        );
    gap: 25px;
    margin-top: 25px;
}

.analytics-card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);
}

.analytics-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}


/* COURSE BARS */

.chart-row {
    margin-bottom: 18px;
}

.chart-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
    font-size: 14px;
}

.bar-container {
    width: 100%;
    height: 22px;
    background: #eee;
    border-radius: 20px;
    overflow: hidden;
}

.bar {
    height: 100%;
    background: #7c3aed;
    border-radius: 20px;
}


/* SEMESTER TABLE */

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 13px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #f3e8ff;
    color: #1e1b4b;
}

tr:hover {
    background: #faf7ff;
}


/* PERFORMANCE */

.performance-box {
    padding: 18px;
    margin-bottom: 15px;
    background: #f3e8ff;
    border-radius: 8px;
}

.performance-box h3 {
    color: #7c3aed;
    margin-bottom: 8px;
}

.performance-box p {
    font-size: 18px;
    font-weight: bold;
}


/* RESPONSIVE */

@media (max-width: 600px) {

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

    .analytics-grid {
        grid-template-columns: 1fr;
    }
}


/* LOGOUT */

.logout-link {
    margin-top: 25px !important;
}

</style>

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>
        🎓 Smart Portal
    </h2>

    <a href="dashboard.php">
        🏠 Dashboard
    </a>

    <a href="profile.php">
        👤 My Profile
    </a>

    <a href="courses.php">
        📚 Courses
    </a>

    <a href="attendance.php">
        📊 Attendance
    </a>

    <a href="results.php">
        📝 Results
    </a>

    <a href="student_reports.php">
        📈 My Reports & Analytics
    </a>

    <a href="fees.php">
        💰 Fees
    </a>

    <a href="announcements.php">
        📢 Announcements
    </a>

    <a href="assignments.php">
        📄 Assignments
    </a>

    <a href="logout.php"
       class="logout-link">
        🚪 Logout
    </a>

</div>


<!-- MAIN CONTENT -->

<div class="dashboard-content">

    <div class="top-bar">

        <h1>
            📊 My Reports & Analytics
        </h1>

        <div class="student-info">

            Welcome,

            <strong>

                <?php

                echo htmlspecialchars(
                    $full_name
                );

                ?>

            </strong>

        </div>

    </div>


    <!-- SUMMARY -->

    <div class="summary-cards">

        <div class="summary-card">

            <h3>
                📚 Total Subjects
            </h3>

            <p>

                <?php

                echo $total_subjects;

                ?>

            </p>

        </div>


        <div class="summary-card">

            <h3>
                📊 Average Marks
            </h3>

            <p>

                <?php

                echo number_format(
                    $average_marks,
                    2
                );

                ?>%

            </p>

        </div>


        <div class="summary-card">

            <h3>
                🎓 GPA
            </h3>

            <p>

                <?php

                echo number_format(
                    $average_gpa,
                    2
                );

                ?>

            </p>

        </div>


        <div class="summary-card">

            <h3>
                📈 CGPA
            </h3>

            <p>

                <?php

                echo number_format(
                    $cgpa,
                    2
                );

                ?>

            </p>

        </div>

    </div>


    <!-- ANALYTICS GRID START -->

    <div class="analytics-grid">


        <!-- COURSE PERFORMANCE -->

        <div class="analytics-card">

            <h2>
                📚 Course-wise Performance
            </h2>

            <?php

            if (count($course_data) > 0) {

                foreach (
                    $course_data
                    as $course
                ) {

                    $marks =
                        $course['marks'];

            ?>

                <div class="chart-row">

                    <div class="chart-label">

                        <span>

                            <?php

                            echo htmlspecialchars(
                                $course['name']
                            );

                            ?>

                        </span>

                        <strong>

                            <?php

                            echo $marks;

                            ?>%

                        </strong>

                    </div>


                    <div class="bar-container">

                        <div class="bar"
                             style="width:
                             <?php
                             echo $marks;
                             ?>%">

                        </div>

                    </div>

                </div>

            <?php

                }

            } else {

                echo "No results available.";

            }

            ?>

        </div>
<!-- PERFORMANCE HIGHLIGHTS -->

        <div class="analytics-card">

            <h2>
                🏆 Performance Highlights
            </h2>


            <div class="performance-box">

                <h3>
                    ⭐ Best Performing Course
                </h3>

                <p>

                    <?php

                    echo htmlspecialchars(
                        $best_course
                    );

                    ?>

                    -

                    <?php

                    echo $best_marks;

                    ?>%

                </p>

            </div>


            <div class="performance-box">

                <h3>
                    📉 Course Needing Improvement
                </h3>

                <p>

                    <?php

                    echo htmlspecialchars(
                        $weakest_course
                    );

                    ?>

                    -

                    <?php

                    echo $weakest_marks;

                    ?>%

                </p>

            </div>


            <div class="performance-box">

                <h3>
                    🎯 Academic Status
                </h3>

                <p>

                    <?php

                    if ($average_marks >= 50) {

                        echo "Good Standing";

                    } else {

                        echo "Needs Improvement";

                    }

                    ?>

                </p>

            </div>

        </div>


        <!-- SEMESTER PERFORMANCE -->

        <div class="analytics-card">

            <h2>
                📈 Semester Performance
            </h2>


            <table>

                <tr>

                    <th>
                        Semester
                    </th>

                    <th>
                        Subjects
                    </th>

                    <th>
                        Average Marks
                    </th>

                </tr>


                <?php

                if (
                    count($semester_data) > 0
                ) {

                    foreach (
                        $semester_data
                        as $semester => $data
                    ) {

                        $semester_average =
                            $data['total_marks']
                            /
                            $data['subjects'];

                ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $semester
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $data['subjects'];

                            ?>

                        </td>


                        <td>

                            <?php

                            echo number_format(
                                $semester_average,
                                2
                            );

                            ?>%

                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="3">

                            No semester data available.

                        </td>

                    </tr>

                <?php

                }

                ?>

            </table>

        </div>


        <!-- ALL RESULTS -->

        <div class="analytics-card">

            <h2>
                📝 Academic Record
            </h2>


            <table>

                <tr>

                    <th>
                        Course
                    </th>

                    <th>
                        Marks
                    </th>

                    <th>
                        Grade
                    </th>

                    <th>
                        Semester
                    </th>

                </tr>


                <?php

                foreach (
                    $student_results
                    as $row
                ) {

                ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['course_name']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $row['marks'];

                            ?>%

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['grade']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['semester']
                            );

                            ?>

                        </td>

                    </tr>

                <?php

                }


                if (
                    count($student_results) == 0
                ) {

                    echo "

                    <tr>

                        <td colspan='4'>

                            No academic records available.

                        </td>

                    </tr>

                    ";

                }

                ?>

            </table>

        </div>


    </div>

</div>


</body>

</html>