<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Teacher GPA Calculator - Smart College Portal</title>

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

/* GPA Card */

.gpa-card {
    background: white;
    margin-top: 25px;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.gpa-card h2 {
    color: #7c3aed;
    margin-bottom: 20px;
}

/* GPA Table */

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 15px;
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

/* Inputs */

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}

/* Buttons */

button {
    margin-top: 20px;
    padding: 10px 15px;
    background: #7c3aed;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #5b21b6;
}

/* Result */

#result {
    margin-top: 25px;
    padding: 15px;
    background: #f3e8ff;
    color: #1e1b4b;
    border-radius: 6px;
    font-weight: bold;
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

    <h2>Smart College</h2>

    <a href="admin_dashboard.php">🏠 Dashboard</a>

    <a href="manage_students.php">👨‍🎓 Manage Students</a>

    <a href="manage_courses.php">📚 Manage Courses</a>

    <a href="manage_teachers.php">👨‍🏫 Manage Teachers</a>

    <a href="manage_records.php">📋 Manage Records</a>
	
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
		  
    <a href="teacher_gpa.php">🎓 Teacher GPA Calculator</a>
	
	<a href="view_results.php">📊 View Results</a>

    <a href="calculate_sgpa.php">📊 Calculate SGPA</a>
	
	<a href="calculate_cgpa.php">📈 Calculate CGPA</a>
	
	<a href="admin_reports.php">
        📊 Reports & Analytics
    </a>


    <a href="admin_logout.php" class="logout-link">
        🚪 Logout
    </a>

</div>


<!-- Main Content -->

<div class="dashboard-content">

    <div class="top-bar">

        <h1>Teacher GPA Calculator</h1>

        <div class="admin-info">
            👤 Administrator
        </div>

    </div>


    <a href="admin_dashboard.php" class="back">
        ← Back to Dashboard
    </a>


    <div class="gpa-card">

        <h2>🎓 Teacher GPA Calculator</h2>


        <table id="gpaTable">

            <tr>

                <th>Course Name</th>

                <th>Credit Hours</th>

                <th>Grade Point</th>

            </tr>


            <tr>

                <td>

                    <input
                        type="text"
                        class="course"
                        placeholder="Course Name"
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="credit"
                        min="1"
                        max="6"
                    >

                </td>


                <td>

                    <input
                        type="number"
                        class="grade"
                        min="0"
                        max="4"
                        step="0.01"
                    >

                </td>

            </tr>

        </table>


        <button onclick="addCourse()">
            Add Course
        </button>


        <button onclick="calculateGPA()">
            Calculate GPA
        </button>


        <div id="result"></div>

    </div>

</div>


<script>

function addCourse() {

    let table =
        document.getElementById("gpaTable");

    let row =
        table.insertRow(-1);

    row.innerHTML = `

        <td>

            <input
                type="text"
                class="course"
                placeholder="Course Name"
            >

        </td>

        <td>

            <input
                type="number"
                class="credit"
                min="1"
                max="6"
            >

        </td>

        <td>

            <input
                type="number"
                class="grade"
                min="0"
                max="4"
                step="0.01"
            >

        </td>

    `;

}


function calculateGPA() {

    let credits =
        document.querySelectorAll(".credit");

    let grades =
        document.querySelectorAll(".grade");


    let totalQualityPoints = 0;

    let totalCredits = 0;


    for (
        let i = 0;
        i < credits.length;
        i++
    ) {

        let credit =
            parseFloat(credits[i].value);

        let grade =
            parseFloat(grades[i].value);


        if (
            isNaN(credit) ||
            isNaN(grade)
        ) {

            alert(
                "Please fill all Credit Hours and Grade Points."
            );

            return;

        }


        totalQualityPoints +=
            credit * grade;


        totalCredits +=
            credit;

    }


    let gpa =
        totalQualityPoints /
        totalCredits;


    document.getElementById("result").innerHTML =

        "Your GPA is: " +
        gpa.toFixed(2);

}

</script>

</body>

</html>