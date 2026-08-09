<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$full_name = $_SESSION['full_name'];


/* Get Assignments */

$sql = "SELECT
            assignments.id,
            assignments.title,
            assignments.course_id,
            assignments.description,
            assignments.due_date,
            courses.course_name,
            courses.course_code

        FROM assignments

        LEFT JOIN courses
        ON assignments.course_id = courses.id

        ORDER BY assignments.due_date ASC";


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Assignments - Smart College Portal
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
            Assignments
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


    <!-- Assignments -->

    <div class="assignments-container">


        <?php

        if (
            mysqli_num_rows($result) > 0
        ) {


            while (
                $assignment =
                mysqli_fetch_assoc($result)
            ):

        ?>


            <div class="assignment-card">


                <h2>

                    📄

                    <?php

                    echo htmlspecialchars(
                        $assignment['title']
                    );

                    ?>

                </h2>


                <p>

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $assignment['description']
                        )
                    );

                    ?>

                </p>


                <div class="assignment-info">


                    <strong>
                        📚 Course:
                    </strong>

                    <?php

                    if (
                        !empty(
                            $assignment['course_name']
                        )
                    ) {

                        echo htmlspecialchars(
                            $assignment['course_name']
                        );

                    } else {

                        echo "Course ID: "
                            .
                            htmlspecialchars(
                                $assignment['course_id']
                            );

                    }

                    ?>


                    <br>


                    <strong>
                        🔢 Course Code:
                    </strong>

                    <?php

                    if (
                        !empty(
                            $assignment['course_code']
                        )
                    ) {

                        echo htmlspecialchars(
                            $assignment['course_code']
                        );

                    } else {

                        echo "N/A";

                    }

                    ?>


                    <br>


                    <strong>
                        📅 Due Date:
                    </strong>

                    <?php

                    echo htmlspecialchars(
                        $assignment['due_date']
                    );

                    ?>


                </div>


            </div>


        <?php

            endwhile;


        } else {

        ?>


            <div class="no-assignments">

                No assignments available yet.

            </div>


        <?php

        }

        ?>


    </div>


</div>


</body>

</html>