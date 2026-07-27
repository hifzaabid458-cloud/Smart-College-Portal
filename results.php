<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$student_id = $_SESSION['user_id'];

$full_name = $_SESSION['full_name'];


/* Get Student Results */

$sql = "SELECT
            courses.course_name,
            courses.credit_hours,
            results.marks,
            results.grade,
            results.grade_point,
            results.semester,
            results.created_at
        FROM results
        JOIN courses
        ON results.course_id = courses.id
        WHERE results.student_id = '$student_id'
        ORDER BY results.semester DESC, results.id DESC";


$result = mysqli_query(
    $conn,
    $sql
);


/* Group Results By Semester */

$semester_results = [];


while (
    $row =
    mysqli_fetch_assoc($result)
) {

    $semester_name =
        $row['semester'];


    $semester_results[
        $semester_name
    ][] = $row;

}


/* Total Subjects */

$total_subjects = 0;


foreach (
    $semester_results
    as $semester_data
) {

    $total_subjects +=
        count($semester_data);

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
        Results - Smart College Portal
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
                My Results
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


        <!-- Result Summary -->

        <div class="result-summary">


            <div class="result-box">

                <h3>
                    Total Semesters
                </h3>


                <p>

                    <?php

                    echo count(
                        $semester_results
                    );

                    ?>

                </p>

            </div>


            <div class="result-box">

                <h3>
                    Total Subjects
                </h3>


                <p>

                    <?php

                    echo $total_subjects;

                    ?>

                </p>

            </div>


            <div class="result-box">

                <h3>
                    Latest Semester
                </h3>


                <p>

                    <?php

                    if (
                        count(
                            $semester_results
                        ) > 0
                    ) {

                        echo htmlspecialchars(
                            array_key_first(
                                $semester_results
                            )
                        );

                    } else {

                        echo "N/A";

                    }

                    ?>

                </p>

            </div>


            <div class="result-box">

                <h3>
                    Status
                </h3>


                <p>

                    <?php

                    if (
                        $total_subjects > 0
                    ) {

                        echo "Passed";

                    } else {

                        echo "No Results";

                    }

                    ?>

                </p>

            </div>


        </div>


        <!-- Semester Results -->

        <?php

        if (
            count(
                $semester_results
            ) > 0
        ) {


            foreach (
                $semester_results
                as $semester_name
                => $results
            ) {


                $total_quality_points = 0;

                $total_credit_hours = 0;


                foreach (
                    $results
                    as $row
                ) {


                    $total_quality_points +=

                        $row[
                            'grade_point'
                        ]

                        *

                        $row[
                            'credit_hours'
                        ];


                    $total_credit_hours +=

                        $row[
                            'credit_hours'
                        ];

                }


                if (
                    $total_credit_hours > 0
                ) {

                    $sgpa =

                        $total_quality_points

                        /

                        $total_credit_hours;

                } else {

                    $sgpa = 0;

                }


        ?>

        <div class="results-table-container">

            <h2>

                <?php

                echo htmlspecialchars(
                    $semester_name
                );

                ?>

                Results

                —

                SGPA:

                <?php

                echo number_format(
                    $sgpa,
                    2
                );

                ?>

            </h2>


            <table
                class="results-table">


                <thead>

                    <tr>

                        <th>
                            Subject
                        </th>

                        <th>
                            Credit Hours
                        </th>

                        <th>
                            Marks
                        </th>

                        <th>
                            Grade
                        </th>

                        <th>
                            Grade Point
                        </th>

                    </tr>

                </thead>


                <tbody>
				<?php

                    foreach (
                        $results
                        as $row
                    ) {

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

                            echo $row[
                                'credit_hours'
                            ];

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $row[
                                'marks'
                            ];

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['grade']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo $row[
                                'grade_point'
                            ];

                            ?>

                        </td>

                    </tr>


                    <?php

                    }

                    ?>

                </tbody>

            </table>

        </div>


        <?php

            }

        } else {

        ?>

        <div class="results-table-container">

            <h2>
                Semester Results
            </h2>

            <p>
                No results available yet.
            </p>

        </div>

        <?php

        }

        ?>

    </div>

</body>

</html>