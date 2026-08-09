<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';


/* =========================
   TOTAL STUDENTS
========================= */

$student_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_students
     FROM users
     WHERE role = 'student'"
);

$student_data = mysqli_fetch_assoc($student_query);

$total_students = $student_data['total_students'];


/* =========================
   TOTAL COURSES
========================= */

$course_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_courses
     FROM courses"
);

$course_data = mysqli_fetch_assoc($course_query);

$total_courses = $course_data['total_courses'];


/* =========================
   TOTAL RESULTS
========================= */

$result_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_results
     FROM results"
);

$result_data = mysqli_fetch_assoc($result_query);

$total_results = $result_data['total_results'];


/* =========================
   AVERAGE MARKS
========================= */

$average_query = mysqli_query(
    $conn,
    "SELECT AVG(marks) AS average_marks
     FROM results"
);

$average_data = mysqli_fetch_assoc($average_query);

$average_marks = $average_data['average_marks'];


/* =========================
   TOP STUDENT
========================= */

$top_student_query = mysqli_query(
    $conn,
    "SELECT
        users.full_name,
        AVG(results.marks) AS average_marks
     FROM results
     JOIN users
        ON results.student_id = users.id
     GROUP BY users.id, users.full_name
     ORDER BY average_marks DESC
     LIMIT 1"
);

$top_student = mysqli_fetch_assoc($top_student_query);


/* =========================
   COURSE PERFORMANCE
========================= */

$course_performance_query = mysqli_query(
    $conn,
    "SELECT
        courses.course_name,
        AVG(results.marks) AS average_marks
     FROM results
     JOIN courses
        ON results.course_id = courses.id
     GROUP BY courses.id, courses.course_name
     ORDER BY average_marks DESC"
);


$course_names = [];

$course_marks = [];


while (
    $course = mysqli_fetch_assoc(
        $course_performance_query
    )
) {

    $course_names[] =
        $course['course_name'];

    $course_marks[] =
        round(
            $course['average_marks'],
            2
        );
}


/* =========================
   GRADE DISTRIBUTION
========================= */

$grade_distribution_query = mysqli_query(
    $conn,
    "SELECT
        grade,
        COUNT(*) AS total
     FROM results
     GROUP BY grade
     ORDER BY grade"
);


$grade_names = [];

$grade_totals = [];


while (
    $grade = mysqli_fetch_assoc(
        $grade_distribution_query
    )
) {

    $grade_names[] =
        $grade['grade'];

    $grade_totals[] =
        $grade['total'];
}


/* =========================
   STUDENT PERFORMANCE
   Grade Point calculated
   from marks
========================= */

$student_performance_query = mysqli_query(
    $conn,
    "SELECT
        users.full_name,
        AVG(results.marks) AS average_marks
     FROM results
     JOIN users
        ON results.student_id = users.id
     GROUP BY users.id, users.full_name
     ORDER BY average_marks DESC"
);


$student_names = [];

$student_marks = [];

$student_grade_points = [];


while (
    $student = mysqli_fetch_assoc(
        $student_performance_query
    )
) {

    $student_names[] =
        $student['full_name'];

    $average_student_marks =
        $student['average_marks'];

    $student_marks[] =
        round(
            $average_student_marks,
            2
        );


    /* Calculate Grade Point */

    if ($average_student_marks >= 85) {

        $grade_point = 4.00;

    } elseif ($average_student_marks >= 80) {

        $grade_point = 3.70;

    } elseif ($average_student_marks >= 75) {

        $grade_point = 3.30;

    } elseif ($average_student_marks >= 70) {

        $grade_point = 3.00;

    } elseif ($average_student_marks >= 65) {

        $grade_point = 2.70;

    } elseif ($average_student_marks >= 60) {

        $grade_point = 2.30;

    } elseif ($average_student_marks >= 55) {

        $grade_point = 2.00;

    } elseif ($average_student_marks >= 50) {

        $grade_point = 1.70;

    } else {

        $grade_point = 0.00;

    }


    $student_grade_points[] =
        $grade_point;
}


/* =========================
   SEMESTER PERFORMANCE
========================= */

$semester_performance_query = mysqli_query(
    $conn,
    "SELECT
        semester,
        AVG(marks) AS average_marks
     FROM results
     GROUP BY semester
     ORDER BY semester"
);


$semester_names = [];

$semester_marks = [];


while (
    $semester = mysqli_fetch_assoc(
        $semester_performance_query
    )
) {

    $semester_names[] =
        $semester['semester'];

    $semester_marks[] =
        round(
            $semester['average_marks'],
            2
        );
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
Reports - Smart College Portal
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


/* =========================
   SIDEBAR
========================= */

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


/* =========================
   MAIN CONTENT
========================= */

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


/* =========================
   BACK BUTTON
========================= */

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


/* =========================
   ANALYTICS CARDS
========================= */

.analytics-container {

    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(200px, 1fr)
        );

    gap: 20px;

    margin-top: 25px;

}


.analytics-card {

    background: white;

    padding: 25px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);

    text-align: center;

}


.analytics-card h3 {

    color: #7c3aed;

    margin-bottom: 15px;

}


.analytics-card p {

    font-size: 28px;

    font-weight: bold;

    color: #1e1b4b;

}


/* =========================
   REPORT CARDS
========================= */

.report-card {

    background: white;

    margin-top: 25px;

    padding: 30px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);

}


.report-card h2 {

    color: #7c3aed;

    margin-bottom: 20px;

}


/* =========================
   TABLES
========================= */

.table-container {

    overflow-x: auto;

}


table {

    width: 100%;

    border-collapse: collapse;

}


th,
td {

    padding: 15px;

    text-align: left;

    border-bottom: 1px solid #eee;

}


th {

    background: #f3e8ff;

    color: #1e1b4b;

}


td {

    color: #333;

}


tr:hover {

    background: #f9f7ff;

}


/* =========================
   CHART
========================= */

.chart-container {

    width: 100%;

    height: 350px;

}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 700px) {

    .sidebar {

        width: 200px;

    }


    .dashboard-content {

        margin-left: 200px;

        padding: 20px;

    }

}

</style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <h2>
        Smart College
    </h2>


    <a href="admin_dashboard.php">
        🏠 Dashboard
    </a>


    <a href="manage_students.php">
        👨‍🎓 Manage Students
    </a>


    <a href="manage_courses.php">
        📚 Manage Courses
    </a>


    <a href="manage_teachers.php">
        👨‍🏫 Manage Teachers
    </a>


    <a href="manage_records.php">
        📋 Manage Records
    </a>


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


    <a href="view_results.php">
        📊 View Results
    </a>


    <a href="teacher_gpa.php">
        🎓 Teacher GPA Calculator
    </a>


    <a href="calculate_sgpa.php">
        📊 Calculate SGPA
    </a>


    <a href="calculate_cgpa.php">
        📈 Calculate CGPA
    </a>


    <a href="admin_reports.php">
        📊 Reports & Analytics
    </a>


    <a href="admin_logout.php"
       class="logout-link">

        🚪 Logout

    </a>

</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="dashboard-content">


    <div class="top-bar">

        <h1>
            Reports & Analytics
        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>

    </div>


    <a href="admin_dashboard.php"
       class="back">

        ← Back to Dashboard

    </a>


    <!-- =========================
         ANALYTICS CARDS
    ========================= -->

    <div class="analytics-container">


        <div class="analytics-card">

            <h3>
                👨‍🎓 Total Students
            </h3>

            <p>
                <?php echo $total_students; ?>
            </p>

        </div>


        <div class="analytics-card">

            <h3>
                📚 Total Courses
            </h3>

            <p>
                <?php echo $total_courses; ?>
            </p>

        </div>


        <div class="analytics-card">

            <h3>
                📝 Total Results
            </h3>

            <p>
                <?php echo $total_results; ?>
            </p>

        </div>


        <div class="analytics-card">

            <h3>
                📊 Average Marks
            </h3>

            <p>

                <?php

                echo $average_marks !== null
                    ? number_format(
                        $average_marks,
                        2
                    )
                    : "0";

                ?>

            </p>

        </div>


        <div class="analytics-card">

            <h3>
                🏆 Top Student
            </h3>

            <p style="font-size:18px;">

                <?php

                echo $top_student
                    ? htmlspecialchars(
                        $top_student['full_name']
                    )
                    : "No Data";

                ?>

            </p>

        </div>


    </div>


    <!-- =========================
         COURSE PERFORMANCE TABLE
    ========================= -->

    <div class="report-card">

        <h2>
            📚 Course-wise Performance
        </h2>


        <div class="table-container">

            <table>

                <tr>

                    <th>
                        Course Name
                    </th>

                    <th>
                        Average Marks
                    </th>

                </tr>


                <?php

                if (
                    count($course_names) > 0
                ) {

                    for (
                        $i = 0;
                        $i < count($course_names);
                        $i++
                    ) {

                ?>

                <tr>

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $course_names[$i]
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo number_format(
                            $course_marks[$i],
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

                    <td colspan="2">

                        No course data available.

                    </td>

                </tr>

                <?php

                }

                ?>

            </table>

        </div>

    </div>


    <!-- =========================
         STUDENT PERFORMANCE TABLE
    ========================= -->

    <div class="report-card">

        <h2>
            🏆 Student Performance
        </h2>


        <div class="table-container">

            <table>

                <tr>

                    <th>
                        Student Name
                    </th>

                    <th>
                        Average Marks
                    </th>

                    <th>
                        Grade Point
                    </th>

                </tr>


                <?php

                if (
                    count($student_names) > 0
                ) {

                    for (
                        $i = 0;
                        $i < count($student_names);
                        $i++
                    ) {

                ?>

                <tr>

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $student_names[$i]
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo number_format(
                            $student_marks[$i],
                            2
                        );

                        ?>%

                    </td>


                    <td>

                        <?php

                        echo number_format(
                            $student_grade_points[$i],
                            2
                        );

                        ?>

                    </td>

                </tr>

                <?php

                    }

                } else {

                ?>

                <tr>

                    <td colspan="3">

                        No student data available.

                    </td>

                </tr>

                <?php

                }

                ?>

            </table>

        </div>

    </div>
<!-- =========================
         COURSE PERFORMANCE CHART
    ========================= -->

    <div class="report-card">

        <h2>
            📈 Course Performance Chart
        </h2>


        <div class="chart-container">

            <canvas id="courseChart"></canvas>

        </div>

    </div>


    <!-- =========================
         GRADE DISTRIBUTION
    ========================= -->

    <div class="report-card">

        <h2>
            📊 Grade Distribution
        </h2>


        <div class="chart-container">

            <canvas id="gradeChart"></canvas>

        </div>

    </div>


    <!-- =========================
         STUDENT PERFORMANCE CHART
    ========================= -->

    <div class="report-card">

        <h2>
            🏆 Student Performance Chart
        </h2>


        <div class="chart-container">

            <canvas id="studentChart"></canvas>

        </div>

    </div>


    <!-- =========================
         SEMESTER PERFORMANCE CHART
    ========================= -->

    <div class="report-card">

        <h2>
            📚 Semester Performance
        </h2>


        <div class="chart-container">

            <canvas id="semesterChart"></canvas>

        </div>

    </div>


</div>


<!-- =========================
     CHART.JS
========================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>


/* =========================
   COURSE CHART
========================= */

const courseNames =
<?php
echo json_encode($course_names);
?>;


const courseMarks =
<?php
echo json_encode($course_marks);
?>;


new Chart(

    document.getElementById(
        "courseChart"
    ),

    {

        type: "bar",

        data: {

            labels: courseNames,

            datasets: [

                {

                    label:
                    "Average Marks (%)",

                    data:
                    courseMarks

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true,

                    max: 100

                }

            }

        }

    }

);


/* =========================
   GRADE DISTRIBUTION
========================= */

const gradeNames =
<?php
echo json_encode($grade_names);
?>;


const gradeTotals =
<?php
echo json_encode($grade_totals);
?>;


new Chart(

    document.getElementById(
        "gradeChart"
    ),

    {

        type: "pie",

        data: {

            labels: gradeNames,

            datasets: [

                {

                    label:
                    "Number of Results",

                    data:
                    gradeTotals

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: true,

                    position: "right"

                },

                title: {

                    display: true,

                    text:
                    "Grade Distribution"

                }

            }

        }

    }

);


/* =========================
   STUDENT PERFORMANCE
========================= */

const studentNames =
<?php
echo json_encode($student_names);
?>;


const studentMarks =
<?php
echo json_encode($student_marks);
?>;


new Chart(

    document.getElementById(
        "studentChart"
    ),

    {

        type: "bar",

        data: {

            labels: studentNames,

            datasets: [

                {

                    label:
                    "Average Marks (%)",

                    data:
                    studentMarks

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true,

                    max: 100

                }

            }

        }

    }

);


/* =========================
   SEMESTER PERFORMANCE
========================= */

const semesterNames =
<?php
echo json_encode($semester_names);
?>;


const semesterMarks =
<?php
echo json_encode($semester_marks);
?>;


new Chart(

    document.getElementById(
        "semesterChart"
    ),

    {

        type: "line",

        data: {

            labels: semesterNames,

            datasets: [

                {

                    label:
                    "Average Marks (%)",

                    data:
                    semesterMarks,

                    fill: false,

                    tension: 0.3

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true,

                    max: 100

                }

            }

        }

    }

);

</script>


</body>

</html>