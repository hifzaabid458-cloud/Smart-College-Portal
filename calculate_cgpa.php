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
            results.student_id,
            users.full_name,
            results.marks,
            courses.credit_hours

        FROM results

        JOIN users
            ON results.student_id = users.id

        JOIN courses
            ON results.course_id = courses.id

        ORDER BY users.full_name ASC";


$result = mysqli_query($conn, $sql);


if (!$result) {

    die("Database Error: " . mysqli_error($conn));

}


/* =========================
   CALCULATE CGPA
========================= */

$students = array();


while ($row = mysqli_fetch_assoc($result)) {

    $student_id = $row['student_id'];

    $marks = floatval($row['marks']);

    $credit_hours = floatval($row['credit_hours']);


    /*
       Convert marks into grade point.

       You can change this grading scale
       according to your university.
    */

    if ($marks >= 85) {

        $grade_point = 4.00;

    } elseif ($marks >= 80) {

        $grade_point = 3.70;

    } elseif ($marks >= 75) {

        $grade_point = 3.30;

    } elseif ($marks >= 70) {

        $grade_point = 3.00;

    } elseif ($marks >= 65) {

        $grade_point = 2.70;

    } elseif ($marks >= 60) {

        $grade_point = 2.30;

    } elseif ($marks >= 55) {

        $grade_point = 2.00;

    } elseif ($marks >= 50) {

        $grade_point = 1.70;

    } else {

        $grade_point = 0.00;

    }


    if (!isset($students[$student_id])) {

        $students[$student_id] = array(

            'full_name' => $row['full_name'],

            'total_quality_points' => 0,

            'total_credit_hours' => 0

        );

    }


    /*
       Quality Points =
       Grade Point × Credit Hours
    */

    $quality_points =
        $grade_point * $credit_hours;


    $students[$student_id]['total_quality_points']
        += $quality_points;


    $students[$student_id]['total_credit_hours']
        += $credit_hours;

}

?><!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Calculate CGPA - Smart College Portal
</title><style>

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

</style></head><body><!-- =========================
     SIDEBAR
========================= --><div class="sidebar"><h2>
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

</div><!-- =========================
     MAIN CONTENT
========================= --><div class="dashboard-content"><div class="top-bar">

    <h1>
        Calculate CGPA
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
     CGPA CARD
========================= -->

<div class="results-card">

    <h2>
        📈 Student CGPA
    </h2>


    <div class="table-container">

        <table>

            <tr>

                <th>
                    Student ID
                </th>

                <th>
                    Student Name
                </th>

                <th>
                    Total Credit Hours
                </th>

                <th>
                    Total Quality Points
                </th>

                <th>
                    CGPA
                </th>

            </tr>


            <?php

            if (count($students) > 0) {

                foreach ($students as $student_id => $student) {


                    $cgpa = 0;


                    if (
                        $student['total_credit_hours'] > 0
                    ) {

                        $cgpa =
                            $student['total_quality_points']
                            /
                            $student['total_credit_hours'];

                    }

            ?>


            <tr>

                <td>

                    <?php

                    echo htmlspecialchars(
                        $student_id
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

                    echo number_format(
                        $student['total_credit_hours'],
                        2
                    );

                    ?>

                </td>


                <td>

                    <?php

                    echo number_format(
                        $student['total_quality_points'],
                        2
                    );

                    ?>

                </td>


                <td>

                    <strong>

                        <?php

                        echo number_format(
                            $cgpa,
                            2
                        );

                        ?>

                    </strong>

                </td>

            </tr>


            <?php

                }

            } else {

            ?>


            <tr>

                <td colspan="5">

                    No CGPA records available yet.

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