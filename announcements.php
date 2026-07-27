<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$full_name = $_SESSION['full_name'];


/* Get Announcements */

$sql = "SELECT
            id,
            title,
            message,
            created_at

        FROM announcements

        ORDER BY created_at DESC";


$result = mysqli_query(
    $conn,
    $sql
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1.0">

    <title>
        Announcements - Smart College Portal
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
            Announcements
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


    <!-- Announcements -->

    <div class="announcements-container">


        <?php

        if (
            mysqli_num_rows(
                $result
            ) > 0
        ) {


            while (

                $announcement =
                mysqli_fetch_assoc(
                    $result
                )

            ):


        ?>


            <div class="announcement-card">


                <h2>

                    📢

                    <?php

                    echo htmlspecialchars(
                        $announcement[
                            'title'
                        ]
                    );

                    ?>

                </h2>


                <p>

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $announcement[
                                'message'
                            ]
                        )
                    );

                    ?>

                </p>


                <small>

                    Posted on:

                    <?php

                    echo htmlspecialchars(
                        $announcement[
                            'created_at'
                        ]
                    );

                    ?>

                </small>


            </div>


        <?php

            endwhile;


        } else {


        ?>


            <div class="no-announcements">

                No announcements available yet.

            </div>


        <?php

        }

        ?>


    </div>


</div>


</body>

</html>