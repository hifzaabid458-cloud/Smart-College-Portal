<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$message = "";


/* Add Assignment */

if (isset($_POST['add_assignment'])) {

    $title = mysqli_real_escape_string(
        $conn,
        $_POST['title']
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );

    $course_name = mysqli_real_escape_string(
        $conn,
        $_POST['course_name']
    );

    $due_date = $_POST['due_date'];


    $sql = "INSERT INTO assignments
            (
                title,
                description,
                course_name,
                due_date
            )

            VALUES

            (
                '$title',
                '$description',
                '$course_name',
                '$due_date'
            )";


    if (mysqli_query($conn, $sql)) {

        $message =
            "Assignment added successfully!";

    } else {

        $message =
            "Error: "
            . mysqli_error($conn);

    }

}


/* Delete Assignment */

if (isset($_GET['delete_id'])) {

    $delete_id =
        intval($_GET['delete_id']);


    $delete_sql =
        "DELETE FROM assignments
         WHERE id = '$delete_id'";


    if (mysqli_query($conn, $delete_sql)) {

        header(
            "Location: admin_manage_assignments.php"
        );

        exit();

    } else {

        echo
            "Error: "
            . mysqli_error($conn);

    }

}


/* Get Courses */

$courses = mysqli_query(
    $conn,

    "SELECT course_name
     FROM courses
     ORDER BY course_name ASC"
);


/* Get Assignments */

$assignments = mysqli_query(
    $conn,

    "SELECT *

     FROM assignments

     ORDER BY due_date ASC"
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
Manage Assignments - Smart College Portal
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

input,
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
}

textarea {
    height: 130px;
    resize: vertical;
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


/* Table Card */

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

.delete-button {
    color: red;
    font-weight: bold;
    text-decoration: none;
}

.delete-button:hover {
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
            Manage Assignments
        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>

    </div>


    <a href="admin_dashboard.php"
       class="back">

        ← Back to Dashboard

    </a>


    <!-- Add Assignment -->

    <div class="form-card">

        <h2>
            📄 Add New Assignment
        </h2>


        <?php

        if ($message != "") {

            echo
                "<div class='message'>";

            echo $message;

            echo
                "</div>";

        }

        ?>


        <form method="POST">


            <div class="form-group">

                <label>
                    Assignment Title
                </label>


                <input
                    type="text"
                    name="title"
                    placeholder="Enter assignment title"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Description
                </label>


                <textarea
                    name="description"
                    placeholder="Write assignment description"
                    required
                ></textarea>

            </div>


            <div class="form-group">

                <label>
                    Select Course
                </label>


                <select
                    name="course_name"
                    required
                >

                    <option value="">
                        Select Course
                    </option>


                    <?php

                    while (

                        $course =
                        mysqli_fetch_assoc(
                            $courses
                        )

                    ):

                    ?>


                    <option
                        value="<?php

                        echo htmlspecialchars(
                            $course[
                                'course_name'
                            ]
                        );

                        ?>"
                    >

                        <?php

                        echo htmlspecialchars(
                            $course[
                                'course_name'
                            ]
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
                name="add_assignment"
            >

                Add Assignment

            </button>

        </form>

    </div>


    <!-- Assignment Records -->

    <div class="table-card">

        <h2>
            📋 All Assignments
        </h2>


        <div class="table-container">

            <table>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Title
                    </th>

                    <th>
                        Course
                    </th>

                    <th>
                        Due Date
                    </th>

                    <th>
                        Description
                    </th>

                    <th>
                        Action
                    </th>

                </tr>


                <?php

                if (
                    mysqli_num_rows(
                        $assignments
                    ) > 0
                ):


                    while (

                        $assignment =
                        mysqli_fetch_assoc(
                            $assignments
                        )

                    ):

                ?>


                <tr>

                    <td>

                        <?php

                        echo
                            $assignment['id'];

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $assignment['title']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $assignment['course_name']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo
                            $assignment['due_date'];

                        ?>

                    </td>


                    <td>

                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $assignment[
                                    'description'
                                ]
                            )
                        );

                        ?>

                    </td>


                    <td>

                        <a
                            href="admin_manage_assignments.php?delete_id=<?php echo $assignment['id']; ?>"
                            class="delete-button"
                            onclick="return confirm('Are you sure you want to delete this assignment?');"
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

                        No assignments available.

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