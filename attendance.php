<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$student_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];


/* Get Attendance Records */

$sql = "SELECT
            courses.course_name,
            courses.course_code,
            courses.credit_hours,
            attendance.attendance_percentage

        FROM attendance

        JOIN courses
        ON attendance.course_id = courses.id

        WHERE attendance.student_id = ?

        ORDER BY courses.course_name ASC";


$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $student_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


/* Store Attendance Records */

$attendance_records = [];

$total_percentage = 0;
$record_count = 0;


while ($row = mysqli_fetch_assoc($result)) {

    $attendance_records[] = $row;

    $total_percentage +=
        (float)$row['attendance_percentage'];

    $record_count++;
}


/* Calculate Overall Attendance */

if ($record_count > 0) {

    $overall_percentage =
        $total_percentage / $record_count;

} else {

    $overall_percentage = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Attendance - Smart College Portal
    </title>

    <link rel="stylesheet"
          href="style.css">

</head>

<body>


<!-- Sidebar -->

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


<!-- Main Content -->

<div class="dashboard-content">


    <div class="top-bar">

        <h1>
            Attendance
        </h1>

        <div class="student-info">

            Welcome,

            <strong>

                <?php

                echo htmlspecialchars($full_name);

                ?>

            </strong>

        </div>

    </div>


    <!-- Overall Attendance -->

    <div class="attendance-summary">

        <div class="attendance-box">

            <h3>
                Overall Attendance
            </h3>

            <p>

                <?php

                echo number_format(
                    $overall_percentage,
                    2
                );

                ?>%

            </p>

        </div>

    </div>


    <!-- Subject-wise Attendance -->

    <div class="attendance-table-container">

        <h2>
            Subject-wise Attendance
        </h2>


        <table class="attendance-table">

            <thead>

                <tr>

                    <th>
                        Course
                    </th>

                    <th>
                        Course Code
                    </th>

                    <th>
                        Credit Hours
                    </th>

                    <th>
                        Attendance
                    </th>

                    <th>
                        Status
                    </th>

                </tr>

            </thead>


            <tbody>


            <?php

            if (count($attendance_records) > 0) {

                foreach ($attendance_records as $row) {

                    $percentage =
                        (float)$row['attendance_percentage'];


                    if ($percentage >= 85) {

                        $status = "Good";

                    } elseif ($percentage >= 75) {

                        $status = "Warning";

                    } else {

                        $status = "Low";

                    }

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

                        echo htmlspecialchars(
                            $row['course_code']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row['credit_hours']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo number_format(
                            $percentage,
                            2
                        );

                        ?>%

                    </td>


                    <td>

                        <?php

                        echo $status;

                        ?>

                    </td>

                </tr>


            <?php

                }

            } else {

            ?>


                <tr>

                    <td colspan="5">

                        No attendance records
                        available yet.

                    </td>

                </tr>


            <?php

            }

            ?>


            </tbody>

        </table>

    </div>


</div>


</body>

</html>