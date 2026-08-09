<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';


/* =========================
   GET RESULTS
========================= */

$sql = "SELECT 
            results.id,
            results.student_id,
            results.course_id,
            users.full_name,
            courses.course_name,
            results.marks,
            results.grade,
            results.semester

        FROM results

        JOIN users
            ON results.student_id = users.id

        JOIN courses
            ON results.course_id = courses.id

        ORDER BY results.id DESC";


$result = mysqli_query($conn, $sql);


/* Check Query */

if (!$result) {

    die("Database Error: " . mysqli_error($conn));

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
View Results - Smart College Portal
</title>


<style>

/* =========================
   GENERAL
========================= */

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
   RESULTS CARD
========================= */

.results-card {

    background: white;

    margin-top: 25px;

    padding: 30px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);

}


.results-card h2 {

    color: #7c3aed;

    margin-bottom: 20px;

}


/* =========================
   TABLE
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


    th,
    td {

        padding: 10px;

        font-size: 14px;

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


    <a href="teacher_gpa.php">
        🎓 Teacher GPA Calculator
    </a>


    <a href="view_results.php">
        📋 View Results
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
            View Results
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
         RESULTS
    ========================= -->

    <div class="results-card">

        <h2>
            📋 Student Results
        </h2>


        <div class="table-container">

            <table>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Student ID
                    </th>

                    <th>
                        Student Name
                    </th>

                    <th>
                        Course ID
                    </th>

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

                if (
                    mysqli_num_rows($result) > 0
                ) {

                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {

                ?>

                <tr>

                    <!-- ID -->

                    <td>

                        <?php

                        echo $row['id'];

                        ?>

                    </td>


                    <!-- Student ID -->

                    <td>

                        <?php

                        echo $row['student_id'];

                        ?>

                    </td>


                    <!-- Student Name -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row['full_name']
                        );

                        ?>

                    </td>


                    <!-- Course ID -->

                    <td>

                        <?php

                        echo $row['course_id'];

                        ?>

                    </td>


                    <!-- Course -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row['course_name']
                        );

                        ?>

                    </td>


                    <!-- Marks -->

                    <td>

                        <?php

                        echo $row['marks'];

                        ?>

                    </td>


                    <!-- Grade -->

                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row['grade']
                        );

                        ?>

                    </td>


                    <!-- Semester -->

                    <td>

                        <?php

                        echo $row['semester'];

                        ?>

                    </td>

                </tr>


                <?php

                    }

                } else {

                ?>

                <tr>

                    <td colspan="8">

                        No results added yet.

                    </td>

                </tr>

                <?php

                }

                ?>

            </table>

        </div>

    </div>


</div>


</body>

</html>