<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$full_name = $_SESSION['full_name'];


/* =========================
   GET ALL COURSES
   WITH ASSIGNED TEACHER
========================= */

$sql = "SELECT
            courses.id,
            courses.course_name,
            courses.course_code,
            courses.credit_hours,

            teachers.name AS teacher_name,
            teachers.email AS teacher_email,
            teachers.phone AS teacher_phone

        FROM courses

        LEFT JOIN teachers
        ON courses.id = teachers.course_id

        ORDER BY courses.course_name ASC";


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width,
                 initial-scale=1.0"
    >

    <title>
        Courses - Smart College Portal
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


        .logout-link {

            margin-top: 25px !important;

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


        .student-info {

            background: white;

            padding: 12px 20px;

            border-radius: 8px;

        }


        /* =========================
           COURSES CONTAINER
        ========================= */

        .courses-container {

            display: grid;

            grid-template-columns:
                repeat(
                    auto-fit,
                    minmax(280px, 1fr)
                );

            gap: 25px;

            margin-top: 30px;

        }


        /* =========================
           COURSE CARD
        ========================= */

        .course-card {

            background: white;

            padding: 25px;

            border-radius: 15px;

            box-shadow:
                0 4px 15px
                rgba(0,0,0,0.1);

            transition: 0.2s;

        }


        .course-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 7px 20px
                rgba(0,0,0,0.15);

        }


        .course-card h2 {

            color: #7c3aed;

            margin-bottom: 18px;

            font-size: 21px;

        }


        .course-card p {

            margin-bottom: 10px;

            color: #333;

            line-height: 1.5;

        }


        .course-card strong {

            color: #1e1b4b;

        }


        /* =========================
           TEACHER BOX
        ========================= */

        .teacher-box {

            margin-top: 20px;

            padding: 15px;

            background: #f3e8ff;

            border-radius: 10px;

            border-left: 4px solid #7c3aed;

        }


        .teacher-box h3 {

            color: #7c3aed;

            margin-bottom: 10px;

            font-size: 17px;

        }


        .teacher-box p {

            margin-bottom: 6px;

            font-size: 14px;

        }


        .not-assigned {

            margin-top: 20px;

            padding: 12px;

            background: #f8f8f8;

            border-radius: 8px;

            color: #777;

            font-size: 14px;

        }


        /* =========================
           NO COURSES
        ========================= */

        .no-courses {

            background: white;

            padding: 30px;

            border-radius: 12px;

            text-align: center;

            color: #666;

            box-shadow:
                0 4px 15px
                rgba(0,0,0,0.1);

            grid-column: 1 / -1;

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


            .top-bar {

                flex-direction: column;

                align-items: flex-start;

                gap: 15px;

            }


            .courses-container {

                grid-template-columns: 1fr;

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


    <a
        href="logout.php"
        class="logout-link"
    >

        🚪 Logout

    </a>


</div>



<!-- =========================
     MAIN CONTENT
========================= -->

<div class="dashboard-content">


    <div class="top-bar">


        <h1>
            Available Courses
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



    <!-- =========================
         COURSES
    ========================= -->

    <div class="courses-container">


        <?php


        if (
            $result &&
            mysqli_num_rows($result) > 0
        ) {


            while (
                $course =
                mysqli_fetch_assoc($result)
            ) {


        ?>


            <div class="course-card">


                <h2>

                    📚

                    <?php

                    echo htmlspecialchars(
                        $course['course_name']
                    );

                    ?>

                </h2>



                <p>

                    <strong>
                        Course Code:
                    </strong>

                    <?php

                    echo htmlspecialchars(
                        $course['course_code']
                    );

                    ?>

                </p>



                <p>

                    <strong>
                        Credit Hours:
                    </strong>

                    <?php

                    echo htmlspecialchars(
                        $course['credit_hours']
                    );

                    ?>

                </p>



                <!-- TEACHER -->

                <?php


                if (
                    !empty(
                        $course['teacher_name']
                    )
                ) {


                ?>


                    <div class="teacher-box">


                        <h3>
                            👨‍🏫 Course Teacher
                        </h3>


                        <p>

                            <strong>
                                Name:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $course['teacher_name']
                            );

                            ?>

                        </p>


                        <p>

                            <strong>
                                Email:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $course['teacher_email']
                            );

                            ?>

                        </p>


                        <p>

                            <strong>
                                Phone:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $course['teacher_phone']
                            );

                            ?>

                        </p>


                    </div>


                <?php


                } else {


                ?>


                    <div class="not-assigned">

                        👨‍🏫

                        No teacher assigned
                        to this course yet.

                    </div>


                <?php


                }


                ?>


            </div>


        <?php


            }


        } else {


        ?>


            <div class="no-courses">

                📚

                No courses available yet.

            </div>


        <?php


        }


        ?>


    </div>


</div>


</body>

</html>