<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$message = "";


/* =========================
   ADD ATTENDANCE
========================= */

if (isset($_POST['add_attendance'])) {

    $student_id = intval($_POST['student_id']);
    $course_id = intval($_POST['course_id']);
    $attendance_percentage = floatval($_POST['attendance_percentage']);

    if ($attendance_percentage < 0 || $attendance_percentage > 100) {

        $message = "Attendance percentage must be between 0 and 100.";

    } else {

        $sql = "INSERT INTO attendance
                (student_id, course_id, attendance_percentage)
                VALUES
                ('$student_id', '$course_id', '$attendance_percentage')";

        if (mysqli_query($conn, $sql)) {

            $message = "Attendance added successfully!";

        } else {

            $message = "Error: " . mysqli_error($conn);

        }
    }
}


/* =========================
   GET ATTENDANCE RECORDS
========================= */

$attendance_query = mysqli_query(
    $conn,

    "SELECT
        attendance.id,
        attendance.student_id,
        attendance.course_id,
        users.full_name,
        courses.course_name,
        attendance.attendance_percentage

    FROM attendance

    JOIN users
        ON attendance.student_id = users.id

    JOIN courses
        ON attendance.course_id = courses.id

    ORDER BY attendance.id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
Manage Attendance - Smart College Portal
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
   FORM CARD
========================= */

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
    color: #1e1b4b;
    font-weight: bold;
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


/* =========================
   MESSAGE
========================= */

.message {
    margin-bottom: 20px;
    padding: 12px;
    background: #f3e8ff;
    color: #1e1b4b;
    border-radius: 6px;
}


/* =========================
   TABLE
========================= */

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
        font-size: 13px;
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


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="dashboard-content">


    <div class="top-bar">

        <h1>
            Manage Attendance
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
         ADD ATTENDANCE
    ========================= -->

    <div class="form-card">

        <h2>
            📊 Add Student Attendance
        </h2>


        <?php if ($message != ""): ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST">


            <!-- Student -->

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

                    $students = mysqli_query(
                        $conn,

                        "SELECT id, full_name
                         FROM users
                         WHERE role = 'student'
                         ORDER BY full_name ASC"
                    );


                    while (
                        $student = mysqli_fetch_assoc($students)
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

                    <?php endwhile; ?>

                </select>

            </div>


            <!-- Course -->

            <div class="form-group">

                <label>
                    Select Course
                </label>


                <select
                    name="course_id"
                    required
                >

                    <option value="">
                        Select Course
                    </option>


                    <?php

                    $courses = mysqli_query(
                        $conn,

                        "SELECT id, course_name
                         FROM courses
                         ORDER BY course_name ASC"
                    );


                    while (
                        $course = mysqli_fetch_assoc($courses)
                    ):

                    ?>

                        <option
                            value="<?php echo $course['id']; ?>"
                        >

                            <?php

                            echo htmlspecialchars(
                                $course['course_name']
                            );

                            ?>

                        </option>

                    <?php endwhile; ?>

                </select>

            </div>


            <!-- Attendance Percentage -->

            <div class="form-group">

                <label>
                    Attendance Percentage (%)
                </label>


                <input
                    type="number"
                    name="attendance_percentage"
                    min="0"
                    max="100"
                    step="0.01"
                    placeholder="Enter attendance percentage"
                    required
                >

            </div>


            <button
                type="submit"
                name="add_attendance"
            >

                Add Attendance

            </button>

        </form>

    </div>


    <!-- =========================
         ATTENDANCE RECORDS
    ========================= -->

    <div class="table-card">

        <h2>
            📊 Attendance Records
        </h2>


        <div class="table-container">

            <table>

                <tr>

                    <th>
                        Student ID
                    </th>

                    <th>
                        Student
                    </th>

                    <th>
                        Course ID
                    </th>

                    <th>
                        Course
                    </th>

                    <th>
                        Attendance Percentage
                    </th>

                </tr>


                <?php

                if (
                    mysqli_num_rows(
                        $attendance_query
                    ) > 0
                ):

                    while (
                        $row =
                        mysqli_fetch_assoc(
                            $attendance_query
                        )
                    ):

                ?>

                    <tr>

                        <td>
                            <?php
                            echo $row['student_id'];
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['full_name']
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo $row['course_id'];
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $row['course_name']
                            );
                            ?>
                        </td>


                        <td>

                            <?php

                            echo number_format(
                                $row['attendance_percentage'],
                                2
                            );

                            ?>%

                        </td>

                    </tr>

                <?php

                    endwhile;

                else:

                ?>

                    <tr>

                        <td colspan="5">

                            No attendance records
                            available yet.

                        </td>

                    </tr>

                <?php endif; ?>

            </table>

        </div>

    </div>


</div>


</body>

</html>