<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include "db.php";


/* =========================
   ADD TEACHER
========================= */

$message = "";
$message_type = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $course_id = intval($_POST['course_id']);


    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        $course_id <= 0
    ) {

        $message = "Please fill all fields.";
        $message_type = "error";

    } else {

        /* Check whether course already has a teacher */

        $check = mysqli_prepare(
            $conn,
            "SELECT id
             FROM teachers
             WHERE course_id = ?"
        );

        mysqli_stmt_bind_param(
            $check,
            "i",
            $course_id
        );

        mysqli_stmt_execute($check);

        mysqli_stmt_store_result($check);


        if (mysqli_stmt_num_rows($check) > 0) {

            $message =
                "This course already has a teacher assigned.";

            $message_type = "error";

        } else {

            /* Insert Teacher */

            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO teachers
                (name, email, phone, course_id)
                VALUES (?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $insert,
                "sssi",
                $name,
                $email,
                $phone,
                $course_id
            );


            if (mysqli_stmt_execute($insert)) {

                $message =
                    "Teacher added and course assigned successfully.";

                $message_type = "success";

            } else {

                $message =
                    "Database Error: " .
                    mysqli_error($conn);

                $message_type = "error";

            }

        }

    }

}


/* =========================
   GET COURSES
========================= */

$course_query = mysqli_query(
    $conn,
    "SELECT
        id,
        course_name,
        course_code
     FROM courses
     ORDER BY course_name ASC"
);


/* =========================
   GET TEACHERS
========================= */

$sql = "SELECT
            teachers.id,
            teachers.name,
            teachers.email,
            teachers.phone,
            teachers.course_id,
            courses.course_name,
            courses.course_code

        FROM teachers

        LEFT JOIN courses
        ON teachers.course_id = courses.id

        ORDER BY teachers.id DESC";


$result = mysqli_query(
    $conn,
    $sql
);

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
Manage Teachers - Smart College Portal
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

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);

}


.form-card h2 {

    color: #7c3aed;

    margin-bottom: 20px;

}


.form-grid {

    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(200px, 1fr)
        );

    gap: 18px;

}


.form-group {

    display: flex;

    flex-direction: column;

}


.form-group label {

    font-weight: bold;

    margin-bottom: 7px;

    color: #1e1b4b;

}


.form-group input,
.form-group select {

    padding: 12px;

    border: 1px solid #ddd;

    border-radius: 7px;

    font-size: 15px;

}


.form-group input:focus,
.form-group select:focus {

    outline: none;

    border-color: #7c3aed;

}


.add-btn {

    margin-top: 20px;

    padding: 12px 22px;

    background: #7c3aed;

    color: white;

    border: none;

    border-radius: 7px;

    cursor: pointer;

    font-size: 15px;

}


.add-btn:hover {

    background: #5b21b6;

}


/* =========================
   MESSAGES
========================= */

.message {

    margin-top: 20px;

    padding: 14px;

    border-radius: 7px;

    font-weight: bold;

}


.success {

    background: #dcfce7;

    color: #166534;

}


.error {

    background: #fee2e2;

    color: #991b1b;

}


/* =========================
   TEACHERS CARD
========================= */

.teachers-card {

    background: white;

    margin-top: 25px;

    padding: 30px;

    border-radius: 15px;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);

}


.teachers-card h2 {

    color: #7c3aed;

    margin-bottom: 20px;

}


/* =========================
   TABLE
========================= */

.table-container {

    overflow-x: auto;

}


table {

    width: 100%;

    border-collapse: collapse;

    border: 1px solid #e5d5f7;

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

    color: #333;

}


tr:hover td {

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

        font-size: 14px;

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


    <a
        href="admin_logout.php"
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
            Manage Teachers
        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>


    </div>



    <a
        href="admin_dashboard.php"
        class="back"
    >

        ← Back to Dashboard

    </a>



    <!-- =========================
         ADD TEACHER
    ========================= -->

    <div class="form-card">


        <h2>
            ➕ Add Teacher
        </h2>


        <form
            method="POST"
            action=""
        >


            <div class="form-grid">


                <div class="form-group">


                    <label>
                        Teacher Name
                    </label>


                    <input
                        type="text"
                        name="name"
                        placeholder="Enter teacher name"
                        required
                    >


                </div>



                <div class="form-group">


                    <label>
                        Email
                    </label>


                    <input
                        type="email"
                        name="email"
                        placeholder="Enter teacher email"
                        required
                    >


                </div>



                <div class="form-group">


                    <label>
                        Phone
                    </label>


                    <input
                        type="text"
                        name="phone"
                        placeholder="Enter phone number"
                        required
                    >


                </div>



                <div class="form-group">


                    <label>
                        Assign Course
                    </label>


                    <select
                        name="course_id"
                        required
                    >


                        <option value="">
                            -- Select Course --
                        </option>


                        <?php


                        if (
                            $course_query &&
                            mysqli_num_rows(
                                $course_query
                            ) > 0
                        ) {


                            while (
                                $course =
                                mysqli_fetch_assoc(
                                    $course_query
                                )
                            ) {


                        ?>


                            <option
                                value="<?php
                                    echo $course['id'];
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $course['course_name']
                                );

                                ?>


                                <?php

                                if (
                                    !empty(
                                        $course['course_code']
                                    )
                                ) {

                                    echo " (" .
                                        htmlspecialchars(
                                            $course[
                                                'course_code'
                                            ]
                                        ) .
                                        ")";

                                }

                                ?>

                            </option>


                        <?php


                            }


                        } else {


                        ?>


                            <option value="">

                                No courses available

                            </option>


                        <?php


                        }


                        ?>


                    </select>


                </div>


            </div>



            <button
                type="submit"
                class="add-btn"
            >

                ➕ Add Teacher

            </button>


        </form>



        <?php


        if (!empty($message)) {


        ?>


            <div
                class="message
                <?php echo $message_type; ?>"
            >

                <?php

                echo htmlspecialchars(
                    $message
                );

                ?>

            </div>


        <?php


        }


        ?>


    </div>



    <!-- =========================
         REGISTERED TEACHERS
    ========================= -->

    <div class="teachers-card">


        <h2>
            👨‍🏫 Registered Teachers
        </h2>



        <div class="table-container">


            <table>


                <thead>


                    <tr>


                        <th>
                            ID
                        </th>


                        <th>
                            Teacher Name
                        </th>


                        <th>
                            Email
                        </th>


                        <th>
                            Phone
                        </th>


                        <th>
                            Course ID
                        </th>


                        <th>
                            Course Name
                        </th>


                    </tr>


                </thead>



                <tbody>


                <?php


                if (
                    $result &&
                    mysqli_num_rows($result) > 0
                ) {


                    while (
                        $teacher =
                        mysqli_fetch_assoc(
                            $result
                        )
                    ) {


                ?>


                    <tr>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $teacher['id']
                            );

                            ?>

                        </td>



                        <td>

                            <?php

                            echo htmlspecialchars(
                                $teacher['name']
                            );

                            ?>

                        </td>



                        <td>

                            <?php

                            echo htmlspecialchars(
                                $teacher['email']
                            );

                            ?>

                        </td>



                        <td>

                            <?php

                            echo htmlspecialchars(
                                $teacher['phone']
                            );

                            ?>

                        </td>



                        <td>

                            <?php

                            echo htmlspecialchars(
                                $teacher['course_id']
                            );

                            ?>

                        </td>



                        <td>

                            <?php

                            echo htmlspecialchars(
                                $teacher['course_name']
                                ?? 'Not Assigned'
                            );

                            ?>

                        </td>


                    </tr>


                <?php


                    }


                } else {


                ?>


                    <tr>


                        <td
                            colspan="6"
                            style="
                                text-align:center;
                                padding:25px;
                                color:#666;
                            "
                        >

                            No teachers registered yet.

                        </td>


                    </tr>


                <?php


                }


                ?>


                </tbody>


            </table>


        </div>


    </div>


</div>


</body>

</html>