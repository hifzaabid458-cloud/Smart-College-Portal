<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$message = "";


/* Delete Fee Record */

if (isset($_GET['delete_id'])) {

    $delete_id = intval($_GET['delete_id']);

    $delete_sql = "DELETE FROM fees WHERE id = '$delete_id'";

    if (mysqli_query($conn, $delete_sql)) {

        header("Location: admin_manage_fees.php");
        exit();

    } else {

        $message = "Error: " . mysqli_error($conn);

    }
}


/* Add Fee */

if (isset($_POST['add_fee'])) {

    $student_id = intval($_POST['student_id']);
    $amount = floatval($_POST['amount']);
    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );
    $due_date = $_POST['due_date'];


    $sql = "INSERT INTO fees
            (
                student_id,
                amount,
                status,
                due_date
            )
            VALUES
            (
                '$student_id',
                '$amount',
                '$status',
                '$due_date'
            )";


    if (mysqli_query($conn, $sql)) {

        $message = "Fee record added successfully!";

    } else {

        $message = "Error: " . mysqli_error($conn);

    }
}


/* Get Students */

$students = mysqli_query(
    $conn,
    "SELECT id, full_name
     FROM users
     WHERE role = 'student'
     ORDER BY full_name ASC"
);


/* Get Fee Records */

$fees = mysqli_query(
    $conn,
    "SELECT
        fees.id,
        fees.student_id,
        users.full_name,
        fees.amount,
        fees.status,
        fees.due_date

     FROM fees

     JOIN users
     ON fees.student_id = users.id

     ORDER BY fees.id DESC"
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
Manage Fees - Smart College Portal
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


/* Sidebar */

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


/* Main Content */

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


/* Back Button */

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


/* Form Card */

.form-card {
    background: white;
    margin-top: 25px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}


.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: bold;
    color: #1e1b4b;
}

select,
input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
}


button {
    padding: 12px 20px;
    background: #7c3aed;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
}

button:hover {
    background: #5b21b6;
}


/* Message */

.message {
    margin-bottom: 20px;
    padding: 12px;
    background: #f3e8ff;
    color: #1e1b4b;
    border-radius: 6px;
}


/* Table */

.table-card {
    background: white;
    margin-top: 25px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.table-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 14px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #f3e8ff;
    color: #1e1b4b;
}

tr:hover {
    background: #f9f7ff;
}

.delete-link {
    color: red;
    font-weight: bold;
    text-decoration: none;
}

.delete-link:hover {
    text-decoration: underline;
}


/* Responsive */

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
        font-size: 13px;
    }

}

</style>

</head>

<body>


<!-- Sidebar -->

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


<!-- Main Content -->

<div class="dashboard-content">


    <div class="top-bar">

        <h1>
            Manage Fees
        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>

    </div>


    <a href="admin_dashboard.php"
       class="back">

        ← Back to Dashboard

    </a>


    <!-- Add Fee -->

    <div class="form-card">

        <h2>
            💰 Add Student Fee
        </h2>


        <?php

        if ($message != "") {

            echo "<div class='message'>";

            echo htmlspecialchars($message);

            echo "</div>";

        }

        ?>


        <form method="POST">


            <div class="form-group">

                <label>
                    Select Student
                </label>


                <select
                    name="student_id"
                    required
                >

                    <option value="">
                        Select Student
                    </option>


                    <?php

                    while (
                        $student =
                        mysqli_fetch_assoc($students)
                    ):

                    ?>


                    <option
                        value="<?php echo $student['id']; ?>"
                    >

                        <?php

                        echo htmlspecialchars(
                            $student['full_name']
                        );

                        ?>

                    </option>


                    <?php

                    endwhile;

                    ?>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Amount (Rs.)
                </label>


                <input
                    type="number"
                    name="amount"
                    min="0"
                    step="0.01"
                    placeholder="Enter fee amount"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Status
                </label>


                <select
                    name="status"
                    required
                >

                    <option value="">
                        Select Status
                    </option>

                    <option value="Paid">
                        Paid
                    </option>

                    <option value="Partial">
                        Partial
                    </option>

                    <option value="Unpaid">
                        Unpaid
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>
                    Due Date
                </label>


                <input
                    type="date"
                    name="due_date"
                    required
                >

            </div>


            <button
                type="submit"
                name="add_fee"
            >

                Add Fee Record

            </button>

        </form>

    </div>


    <!-- Fee Records -->

    <div class="table-card">

        <h2>
            📋 Fee Records
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
                        Amount
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Due Date
                    </th>

                    <th>
                        Action
                    </th>

                </tr>


                <?php

                if (
                    mysqli_num_rows($fees) > 0
                ):

                    while (
                        $fee =
                        mysqli_fetch_assoc($fees)
                    ):

                ?>


                <tr>

                    <td>

                        <?php
                        echo $fee['student_id'];
                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $fee['full_name']
                        );

                        ?>

                    </td>


                    <td>

                        Rs.

                        <?php

                        echo number_format(
                            $fee['amount'],
                            2
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $fee['status']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $fee['due_date']
                        );

                        ?>

                    </td>


                    <td>

                        <a
                            href="admin_manage_fees.php?delete_id=<?php echo $fee['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this fee record?');"
                            class="delete-link"
                        >

                            Delete

                        </a>

                    </td>

                </tr>


                <?php

                    endwhile;

                else:

                ?>


                <tr>

                    <td colspan="6">

                        No fee records available.

                    </td>

                </tr>


                <?php

                endif;

                ?>


            </table>

        </div>

    </div>


</div>


</body>

</html>