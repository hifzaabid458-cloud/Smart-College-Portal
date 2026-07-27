<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$student_id = $_SESSION['user_id'];

$full_name = $_SESSION['full_name'];

$email = $_SESSION['email'];


/* Total Results */

$result_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_results
     FROM results
     WHERE student_id = '$student_id'"
);

$result_data = mysqli_fetch_assoc($result_query);

$total_results = $result_data['total_results'];


/* Latest Semester */

$latest_semester_query = mysqli_query(
    $conn,
    "SELECT semester
     FROM results
     WHERE student_id = '$student_id'
     ORDER BY CAST(REPLACE(semester, 'Semester ', '') AS UNSIGNED) DESC
     LIMIT 1"
);

$latest_semester_data =
    mysqli_fetch_assoc(
        $latest_semester_query
    );

$latest_semester =
    $latest_semester_data
    ? $latest_semester_data['semester']
    : "No Results";


/* Total Available Courses */

$course_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_courses
     FROM courses"
);

$course_data =
    mysqli_fetch_assoc(
        $course_query
    );

$total_courses =
    $course_data['total_courses'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width,
                   initial-scale=1.0">

    <title>
        Student Dashboard - Smart College Portal
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
                Student Dashboard
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


        <!-- Welcome Section -->

        <div class="welcome-card">

            <h2>

                Welcome back,

                <?php

                echo htmlspecialchars(
                    $full_name
                );

                ?>

               ! 👋

            </h2>


            <p>

                Manage your academic activities
                from your Smart College Portal.

            </p>

        </div>


        <!-- Dashboard Cards -->

        <div class="dashboard-cards">


            <!-- Courses Card -->

            <div class="dashboard-card">

                <h3>
                    📚 Courses
                </h3>


                <p>

                    <?php

                    echo $total_courses;

                    ?>

                    courses available.

                </p>


                <a href="courses.php">

                    View Courses

                </a>

            </div>


            <!-- Attendance Card -->

            <div class="dashboard-card">

                <h3>
                    📊 Attendance
                </h3>


                <p>

                    Check your attendance
                    record.

                </p>


                <a href="attendance.php">

                    View Attendance

                </a>

            </div>


            <!-- Results Card -->

            <div class="dashboard-card">

                <h3>
                    📝 Results
                </h3>


                <p>

                    <?php

                    echo $total_results;

                    ?>

                    result(s) available.

                </p>


                <a href="results.php">

                    View Results

                </a>

            </div>


            <!-- Announcements Card -->

            <div class="dashboard-card">

                <h3>
                    📢 Announcements
                </h3>


                <p>

                    View the latest
                    college updates.

                </p>


                <a href="announcements.php">

                    View Updates

                </a>

            </div>


        </div>


        <!-- User Information -->

        <div class="user-information">


            <h2>
                Account Information
            </h2>


            <p>

                <strong>
                    Name:
                </strong>


                <?php

                echo htmlspecialchars(
                    $full_name
                );

                ?>

            </p>


            <p>

                <strong>
                    Email:
                </strong>


                <?php

                echo htmlspecialchars(
                    $email
                );

                ?>

            </p>


            <p>

                <strong>
                    Latest Semester:
                </strong>


                <?php

                echo htmlspecialchars(
                    $latest_semester
                );

                ?>

            </p>


        </div>


    </div>


</body>

</html>