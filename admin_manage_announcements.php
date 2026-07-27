<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$message = "";


/* Add Announcement */

if (isset($_POST['add_announcement'])) {

    $title = mysqli_real_escape_string(
        $conn,
        $_POST['title']
    );

    $announcement_message =
        mysqli_real_escape_string(
            $conn,
            $_POST['message']
        );


    $sql = "INSERT INTO announcements
            (title, message)

            VALUES

            ('$title', '$announcement_message')";


    if (mysqli_query($conn, $sql)) {

        $message =
            "Announcement added successfully!";

    } else {

        $message =
            "Error: "
            . mysqli_error($conn);

    }

}


/* Delete Announcement */

if (isset($_GET['delete_id'])) {

    $delete_id =
        intval($_GET['delete_id']);


    $delete_sql =
        "DELETE FROM announcements
         WHERE id = '$delete_id'";


    if (mysqli_query($conn, $delete_sql)) {

        header(
            "Location: admin_manage_announcements.php"
        );

        exit();

    } else {

        echo
            "Error: "
            . mysqli_error($conn);

    }

}


/* Get Announcements */

$announcements = mysqli_query(
    $conn,

    "SELECT *

     FROM announcements

     ORDER BY created_at DESC"
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
Manage Announcements - Smart College Portal
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


/* Announcement Table */

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
            Manage Announcements
        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>

    </div>


    <a href="admin_dashboard.php"
       class="back">

        ← Back to Dashboard

    </a>


    <!-- Add Announcement -->

    <div class="form-card">

        <h2>
            📢 Add New Announcement
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
                    Announcement Title
                </label>


                <input
                    type="text"
                    name="title"
                    placeholder="Enter announcement title"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Announcement Message
                </label>


                <textarea
                    name="message"
                    placeholder="Write announcement message"
                    required
                ></textarea>

            </div>


            <button
                type="submit"
                name="add_announcement"
            >

                Add Announcement

            </button>

        </form>

    </div>


    <!-- Announcement Records -->

    <div class="table-card">

        <h2>
            📋 All Announcements
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
                        Message
                    </th>

                    <th>
                        Created Date
                    </th>

                    <th>
                        Action
                    </th>

                </tr>


                <?php

                if (
                    mysqli_num_rows(
                        $announcements
                    ) > 0
                ):


                    while (

                        $announcement =
                        mysqli_fetch_assoc(
                            $announcements
                        )

                    ):

                ?>


                <tr>

                    <td>

                        <?php

                        echo
                            $announcement['id'];

                        ?>

                    </td>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $announcement['title']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $announcement['message']
                            )
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo
                            $announcement['created_at'];

                        ?>

                    </td>


                    <td>

                        <a
                            href="admin_manage_announcements.php?delete_id=<?php echo $announcement['id']; ?>"
                            class="delete-button"
                            onclick="return confirm('Are you sure you want to delete this announcement?');"
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

                    <td colspan="5">

                        No announcements available.

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