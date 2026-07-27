<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

/* Total Students */

$student_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_students
     FROM users
     WHERE role = 'student'"
);

$student_data = mysqli_fetch_assoc($student_query);

$total_students = $student_data['total_students'];


/* Total Courses */

$course_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_courses
     FROM courses"
);

$course_data = mysqli_fetch_assoc($course_query);

$total_courses = $course_data['total_courses'];


/* Total Results */

$result_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_results
     FROM results"
);

$result_data = mysqli_fetch_assoc($result_query);

$total_results = $result_data['total_results'];


/* Average Marks */

$average_query = mysqli_query(
    $conn,
    "SELECT AVG(marks) AS average_marks
     FROM results"
);

$average_data = mysqli_fetch_assoc($average_query);

$average_marks = $average_data['average_marks'];


/* Top Student */

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


/* Course Performance */

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


/* Store Course Data */

$course_names = [];

$course_marks = [];

while (
    $course =
    mysqli_fetch_assoc(
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

/* Grade Distribution */

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
    $grade =
    mysqli_fetch_assoc(
        $grade_distribution_query
    )
) {

    $grade_names[] =
        $grade['grade'];

    $grade_totals[] =
        $grade['total'];

}


/* Student Performance */

$student_performance_query = mysqli_query(
    $conn,
    "SELECT
        users.full_name,
        AVG(results.marks) AS average_marks,
        AVG(results.grade_point) AS average_grade_point
     FROM results
     JOIN users
     ON results.student_id = users.id
     GROUP BY users.id, users.full_name
     ORDER BY average_marks DESC"
);


/* Store Student Data */

$student_names = [];

$student_marks = [];

$student_grade_points = [];

while (
    $student =
    mysqli_fetch_assoc(
        $student_performance_query
    )
) {

    $student_names[] =
        $student['full_name'];

    $student_marks[] =
        round(
            $student['average_marks'],
            2
        );

    $student_grade_points[] =
        round(
            $student['average_grade_point'],
            2
        );

}

/* Semester Performance */

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
    $semester =
    mysqli_fetch_assoc(
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

<title>Reports - Smart College Portal</title>

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

/* Analytics Cards */

.analytics-container {
    display: grid;
    grid-template-columns:
        repeat(auto-fit, minmax(200px, 1fr));

    gap: 20px;

    margin-top: 25px;
}

.analytics-card {
    background: white;
    padding: 25px;
    border-radius: 15px;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.1);

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

/* Report Cards */

.report-card {
    background: white;
    margin-top: 25px;
    padding: 30px;
    border-radius: 15px;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.1);
}

.report-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}

/* Tables */

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

/* Charts */

.chart-container {
    width: 100%;
    height: 350px;
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

}

</style>

</head>

<body>

<!-- Sidebar -->

<div class="sidebar">

    <h2>Smart College</h2>

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


<!-- Main Content -->

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


    <!-- Analytics Cards -->

    <div class="analytics-container">


        <div class="analytics-card">

            <h3>
                👨‍🎓 Total Students
            </h3>

            <p>

                <?php

                echo $total_students;

                ?>

            </p>

        </div>


        <div class="analytics-card">

            <h3>
                📚 Total Courses
            </h3>

            <p>

                <?php

                echo $total_courses;

                ?>

            </p>

        </div>


        <div class="analytics-card">

            <h3>
                📝 Total Results
            </h3>

            <p>

                <?php

                echo $total_results;

                ?>

            </p>

        </div>


        <div class="analytics-card">

            <h3>
                📊 Average Marks
            </h3>

            <p>

                <?php

                echo $average_marks
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

            <p style="font-size: 18px;">

                <?php

                echo $top_student
                    ? $top_student['full_name']
                    : "No Data";

                ?>

            </p>

        </div>


    </div>
	<!-- Course Performance Table -->

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

                        echo $course_names[$i];

                        ?>

                    </td>

                    <td>

                        <?php

                        echo number_format(
                            $course_marks[$i],
                            2
                        );

                        ?>

                    </td>

                </tr>

                <?php

                    }

                } else {

                    echo "

                    <tr>

                        <td colspan='2'>

                            No course data available.

                        </td>

                    </tr>

                    ";

                }

                ?>

            </table>

        </div>

    </div>


    <!-- Student Performance Table -->

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
                        Average Grade Point
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

                        echo $student_names[$i];

                        ?>

                    </td>

                    <td>

                        <?php

                        echo number_format(
                            $student_marks[$i],
                            2
                        );

                        ?>

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

                    echo "

                    <tr>

                        <td colspan='3'>

                            No student data available.

                        </td>

                    </tr>

                    ";

                }

                ?>

            </table>

        </div>

    </div>


    <!-- Course Performance Chart -->

    <div class="report-card">

        <h2>
            📈 Course Performance Chart
        </h2>

        <div class="chart-container">

            <canvas id="courseChart"></canvas>

        </div>

    </div>
	
	<!-- Grade Distribution Chart -->

<div class="report-card">

    <h2>
        📊 Grade Distribution
    </h2>

    <div class="chart-container">

        <canvas id="gradeChart"></canvas>

    </div>

</div>


    <!-- Student Performance Chart -->

    <div class="report-card">

        <h2>
            🏆 Student Performance Chart
        </h2>

        <div class="chart-container">

            <canvas id="studentChart"></canvas>
       
    </div>
	
	<!-- Semester Performance Chart -->

<div class="report-card">

    <h2>
        📚 Semester Performance
    </h2>

    <div class="chart-container">

        <canvas id="semesterChart"></canvas>

    </div>

</div>

<!-- Chart.js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

const courseNames =

<?php

echo json_encode(
    $course_names
);

?>


const courseMarks =

<?php

echo json_encode(
    $course_marks
);

?>


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
                    "Average Marks",

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


const studentNames =

<?php

echo json_encode(
    $student_names
);

?>


const studentMarks =

<?php

echo json_encode(
    $student_marks
);

?>


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
                    "Average Marks",

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

const gradeNames =

<?php

echo json_encode(
    $grade_names
);

?>


const gradeTotals =

<?php

echo json_encode(
    $grade_totals
);

?>


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

const semesterNames =

<?php

echo json_encode(
    $semester_names
);

?>


const semesterMarks =

<?php

echo json_encode(
    $semester_marks
);

?>


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
                    "Average Marks",

                    data:
                    semesterMarks

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