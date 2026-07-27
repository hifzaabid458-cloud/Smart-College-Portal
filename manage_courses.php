<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';


/* Add Course */

if (isset($_POST['add_course'])) {

    $course_name =
        mysqli_real_escape_string(
            $conn,
            $_POST['course_name']
        );

    $description =
        mysqli_real_escape_string(
            $conn,
            $_POST['description']
        );
		
		$credit_hours =
    mysqli_real_escape_string(
        $conn,
        $_POST['credit_hours']
    );

$insert_sql = "INSERT INTO courses
               (course_name, description, credit_hours)
               VALUES
               ('$course_name', '$description', '$credit_hours')";
    

    if (mysqli_query($conn, $insert_sql)) {

        header(
            "Location: manage_courses.php"
        );

        exit();

    } else {

        echo "Error: "
             . mysqli_error($conn);

    }

}


/* Display Courses */

$sql =
    "SELECT *
     FROM courses
     ORDER BY id DESC";


$result =
    mysqli_query(
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
Manage Courses - Smart College Portal
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

.back:hover {
    background: #5b21b6;
}


/* Add Course Card */

.add-course-card {
    background: white;
    margin-top: 25px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.add-course-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}

.add-course-card input,
.add-course-card textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 15px;
}

.add-course-card textarea {
    height: 100px;
    resize: vertical;
}

.add-course-card button {
    padding: 12px 20px;
    background: #7c3aed;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
}

.add-course-card button:hover {
    background: #5b21b6;
}


/* Courses Card */

.courses-card {
    background: white;
    margin-top: 25px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.courses-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}


/* Table */

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

th,
td {
    padding: 15px;
    text-align: left;
    border: 1px solid #e5d5f7;
}

th {
    background: #f3e8ff;
    color: #1e1b4b;
}

td {
    background: white;
    color: #333;
}

tr:hover td {
    background: #f9f7ff;
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
        font-size: 14px;
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
            Manage Courses
        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>

    </div>


    <a href="admin_dashboard.php"
       class="back">

        ← Back to Dashboard

    </a>


    <!-- Add Course -->

    <div class="add-course-card">

        <h2>
            ➕ Add New Course
        </h2>


        <form method="POST">


            <input
                type="text"
                name="course_name"
                placeholder="Enter Course Name"
                required
            >


            <textarea
                name="description"
                placeholder="Enter Course Description"
                required
            ></textarea>
			
			<input
    type="number"
    name="credit_hours"
    placeholder="Enter Credit Hours"
    min="1"
    max="6"
    required
>


            <button
                type="submit"
                name="add_course">

                Add Course

            </button>


        </form>

    </div>


    <!-- Available Courses -->

    <div class="courses-card">

        <h2>
            📚 Available Courses
        </h2>


        <div class="table-container">

            <table>


                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Course Name
                    </th>

                    <th>
                        Description
                    </th>
					
					<th>
                       Credit Hours
                    </th>

                    <th>
                        Created Date
                    </th>

                </tr>


                <?php

                if (
                    mysqli_num_rows(
                        $result
                    ) > 0
                ) {


                    while (
                        $course =
                        mysqli_fetch_assoc(
                            $result
                        )
                    ) {

                ?>


                <tr>

                    <td>

                        <?php

                        echo $course['id'];

                        ?>

                    </td>


                    <td>

                        <?php

                        echo
                        $course['course_name'];

                        ?>

                    </td>


                    <td>

                        <?php

                        echo
                        $course['description'];

                        ?>

                    </td>
					
					<td>

                        <?php

                          echo $course['credit_hours'];

                          ?>

                     </td>


                    <td>

                        <?php

                        echo
                        $course['created_at'];

                        ?>

                    </td>

                </tr>


                <?php

                    }


                } else {


                    echo "

                    <tr>

                        <td colspan='5'>

                            No courses available.

                        </td>

                    </tr>

                    ";

                }

                ?>


            </table>

        </div>

    </div>


</div>


</body>

</html>