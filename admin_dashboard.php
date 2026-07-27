<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
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
        Admin Dashboard - Smart College Portal
    </title>


<style>

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

    font-family: Arial, sans-serif;

}


body {

    background: #f3e8ff;

    color: #222;

}


/* SIDEBAR */

.sidebar {

    position: fixed;

    left: 0;

    top: 0;

    width: 240px;

    height: 100vh;

    background: #1e1b4b;

    padding: 20px 15px;

    overflow-y: auto;

    z-index: 1000;

}


/* Scrollbar */

.sidebar::-webkit-scrollbar {

    width: 8px;

}


.sidebar::-webkit-scrollbar-thumb {

    background: #7c3aed;

    border-radius: 10px;

}


.sidebar::-webkit-scrollbar-track {

    background: #1e1b4b;

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

    margin: 4px 0;

    border-radius: 6px;

}


.sidebar a:hover {

    background: #7c3aed;

}


.logout-link {

    margin-top: 25px !important;

    margin-bottom: 20px !important;

}


/* MAIN CONTENT */

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


/* WELCOME CARD */

.welcome-card {

    background: white;

    padding: 30px;

    margin-top: 25px;

    border-radius: 12px;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);

}


.welcome-card h2 {

    color: #7c3aed;

    margin-bottom: 10px;

}


/* DASHBOARD CARDS */

.dashboard-cards {

    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(260px, 1fr)
        );

    gap: 20px;

    margin-top: 25px;

}


.dashboard-card {

    background: white;

    padding: 25px;

    border-radius: 12px;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.1);

}


.dashboard-card h3 {

    color: #7c3aed;

    margin-bottom: 15px;

}


.dashboard-card p {

    color: #555;

}


.dashboard-card a {

    display: inline-block;

    margin-top: 15px;

    padding: 10px 15px;

    background: #7c3aed;

    color: white;

    text-decoration: none;

    border-radius: 6px;

}


.dashboard-card a:hover {

    background: #5b21b6;

}


/* TABLET */

@media (max-width: 900px) {

    .dashboard-cards {

        grid-template-columns:
            repeat(2, 1fr);

    }

}


/* MOBILE */

@media (max-width: 600px) {

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


    .dashboard-cards {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- SIDEBAR -->

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


<!-- MAIN CONTENT -->

<div class="dashboard-content">


    <div class="top-bar">


        <h1>

            Admin Dashboard

        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>


    </div>


    <!-- WELCOME -->

    <div class="welcome-card">


        <h2>

            Welcome, Admin! 👋

        </h2>


        <p>

            You are successfully logged in
            to the Admin Dashboard.

        </p>


    </div>


    <!-- DASHBOARD CARDS -->

    <div class="dashboard-cards">


        <div class="dashboard-card">


            <h3>

                👨‍🎓 Manage Students

            </h3>


            <p>

                View and manage student accounts.

            </p>


            <a href="manage_students.php">

                Open

            </a>


        </div>


        <div class="dashboard-card">


            <h3>

                📚 Manage Courses

            </h3>


            <p>

                Add and manage college courses.

            </p>


            <a href="manage_courses.php">

                Open

            </a>


        </div>


        <div class="dashboard-card">


            <h3>

                👨‍🏫 Manage Teachers

            </h3>


            <p>

                Manage teacher information.

            </p>


            <a href="manage_teachers.php">

                Open

            </a>


        </div>


        <div class="dashboard-card">


            <h3>

                💰 Manage Fees

            </h3>


            <p>

                Manage student fee records.

            </p>


            <a href="admin_manage_fees.php">

                Open

            </a>


        </div>


        <div class="dashboard-card">


            <h3>

                📢 Announcements

            </h3>


            <p>

                Create and manage college announcements.

            </p>


            <a href="admin_manage_announcements.php">

                Open

            </a>


        </div>


        <div class="dashboard-card">


            <h3>

                📄 Assignments

            </h3>


            <p>

                Create and manage student assignments.

            </p>


            <a href="admin_manage_assignments.php">

                Open

            </a>


        </div>


        <div class="dashboard-card">


            <h3>

                📊 Reports

            </h3>


            <p>

                View portal reports and statistics.

            </p>


            <a href="admin_reports.php">

                Open

            </a>


        </div>


        <div class="dashboard-card">


            <h3>

                🎓 Teacher GPA Calculator

            </h3>


            <p>

                Calculate and manage students' GPA.

            </p>


            <a href="teacher_gpa.php">

                Open

            </a>


        </div>


    </div>


</div>


</body>

</html>