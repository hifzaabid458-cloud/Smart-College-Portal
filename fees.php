<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$student_id = $_SESSION['user_id'];

$full_name = $_SESSION['full_name'];


/* Get Student Fee Record */

$sql = "SELECT
            student_id,
            total_fee,
            paid_amount,
            due_date,
            status,
            created_at

        FROM fees

        WHERE student_id = '$student_id'

        ORDER BY id DESC

        LIMIT 1";


$result = mysqli_query(
    $conn,
    $sql
);


$fee = mysqli_fetch_assoc(
    $result
);


/* Fee Calculations */

if ($fee) {

    $total_fee =
        $fee['total_fee'];

    $paid_amount =
        $fee['paid_amount'];

    $remaining_fee =
        $total_fee
        -
        $paid_amount;

    $due_date =
        $fee['due_date'];

    $status =
        $fee['status'];

} else {

    $total_fee = 0;

    $paid_amount = 0;

    $remaining_fee = 0;

    $due_date = "N/A";

    $status = "No Record";

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
        Fees - Smart College Portal
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
            Fee Details
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


    <!-- Fee Summary -->

    <div class="fee-summary">


        <div class="fee-box">

            <h3>
                Total Fee
            </h3>


            <p>

                Rs.

                <?php

                echo number_format(
                    $total_fee,
                    2
                );

                ?>

            </p>

        </div>


        <div class="fee-box">

            <h3>
                Paid Amount
            </h3>


            <p>

                Rs.

                <?php

                echo number_format(
                    $paid_amount,
                    2
                );

                ?>

            </p>

        </div>


        <div class="fee-box">

            <h3>
                Remaining Fee
            </h3>


            <p>

                Rs.

                <?php

                echo number_format(
                    $remaining_fee,
                    2
                );

                ?>

            </p>

        </div>


        <div class="fee-box">

            <h3>
                Status
            </h3>


            <p>

                <?php

                echo htmlspecialchars(
                    $status
                );

                ?>

            </p>

        </div>


    </div>


    <!-- Fee Details Table -->

    <div class="fee-table-container">


        <h2>
            Fee Details
        </h2>


        <table
            class="fee-table">


            <thead>

                <tr>
				
				<th>
                        student_id
                    </th>

                    <th>
                        Total Fee
                    </th>
														
                    <th>
                        Paid Amount
                    </th>


                    <th>
                        Remaining Fee
                    </th>


                    <th>
                        Due Date
                    </th>


                    <th>
                        Status
                    </th>

                </tr>

            </thead>


            <tbody>


                <?php

                if ($fee) {

                ?>


                <tr>
				
				<td>

                        <?php

                        echo $fee['student_id'];

                        ?>

                     </td>

                    <td>

                        Rs.

                        <?php

                        echo number_format(
                            $total_fee,
                            2
                        );

                        ?>

                    </td>


                    <td>

                        Rs.

                        <?php

                        echo number_format(
                            $paid_amount,
                            2
                        );

                        ?>

                    </td>


                    <td>

                        Rs.

                        <?php

                        echo number_format(
                            $remaining_fee,
                            2
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $due_date
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $status
                        );

                        ?>

                    </td>

                </tr>


                <?php

                } else {

                ?>


                <tr>

                    <td
                        colspan="6">

                        No fee record
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