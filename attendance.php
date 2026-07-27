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
            attendance.total_classes,
            attendance.attended_classes,
            (
                attendance.total_classes
                -
                attendance.attended_classes
            ) AS missed_classes

        FROM attendance

        JOIN courses
        ON attendance.course_id = courses.id

        WHERE attendance.student_id = '$student_id'

        ORDER BY attendance.id DESC";


$result = mysqli_query(
    $conn,
    $sql
);


/* Overall Attendance */

$total_classes = 0;

$total_attended = 0;

$attendance_records = [];


while (
    $row =
    mysqli_fetch_assoc($result)
) {

    $attendance_records[] = $row;

    $total_classes +=
        $row['total_classes'];

    $total_attended +=
        $row['attended_classes'];

}


$total_missed =
    $total_classes
    -
    $total_attended;


if ($total_classes > 0) {

    $overall_percentage =
        (
            $total_attended
            /
            $total_classes
        )
        * 100;

} else {

    $overall_percentage = 0;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1.0">

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

                echo htmlspecialchars(
                    $full_name
                );

                ?>

            </strong>

        </div>

    </div>


    <!-- Attendance Summary -->

    <div class="attendance-summary">


        <div class="attendance-box">

            <h3>
                Total Classes
            </h3>


            <p>

                <?php

                echo $total_classes;

                ?>

            </p>

        </div>


        <div class="attendance-box">

            <h3>
                Classes Attended
            </h3>


            <p>

                <?php

                echo $total_attended;

                ?>

            </p>

        </div>


        <div class="attendance-box">

            <h3>
                Classes Missed
            </h3>


            <p>

                <?php

                echo $total_missed;

                ?>

            </p>

        </div>


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


        <table
            class="attendance-table">


            <thead>

                <tr>

                    <th>
                        Subject
                    </th>


                    <th>
                        Total Classes
                    </th>


                    <th>
                        Attended
                    </th>


                    <th>
                        Missed
                    </th>


                    <th>
                        Percentage
                    </th>


                    <th>
                        Status
                    </th>

                </tr>

            </thead>


            <tbody>


            <?php

            if (
                count(
                    $attendance_records
                ) > 0
            ) {


                foreach (

                    $attendance_records
                    as $row

                ) {


                    $percentage =

                        (
                            $row[
                                'attended_classes'
                            ]

                            /

                            $row[
                                'total_classes'
                            ]

                        )
                        * 100;


                    if (
                        $percentage >= 85
                    ) {

                        $status =
                            "Good";

                    } elseif (
                        $percentage >= 75
                    ) {

                        $status =
                            "Warning";

                    } else {

                        $status =
                            "Low";

                    }

            ?>


                <tr>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row[
                                'course_name'
                            ]
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo $row[
                            'total_classes'
                        ];

                        ?>

                    </td>


                    <td>

                        <?php

                        echo $row[
                            'attended_classes'
                        ];

                        ?>

                    </td>


                    <td>

                        <?php

                        echo $row[
                            'missed_classes'
                        ];

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

                    <td
                        colspan="6">

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