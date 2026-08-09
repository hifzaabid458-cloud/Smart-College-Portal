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
      content="width=device-width, initial-scale=1.0">

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


/* Table */

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
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
    margin-right: 10px;
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

    
    <a href="teacher_gpa.php">
        🎓 Teacher GPA Calculator
    </a>


   
        🚪 Logout

    </a>

</div>


<!-- Main Content -->

<div class="dashboard-content">


    <div class="top-bar">

        <h1>
            Teacher GPA Calculator
        </h1>


        <div class="admin-info">

            👤 Administrator

        </div>

    </div>


    <a href="admin_dashboard.php"
       class="back">

        ← Back to Dashboard

    </a>


    <div class="gpa-card">


        <h2>
            🎓 Calculate GPA
        </h2>


        <div class="table-container">

            <table id="gpaTable">

                <thead>

                    <tr>

                        <th>
                            Course Name
                        </th>

                        <th>
                            Credit Hours
                        </th>

                        <th>
                            Grade Point
                        </th>

                    </tr>

                </thead>


                <tbody>

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
                                placeholder="3"
                            >

                        </td>


                        <td>

                            <input
                                type="number"
                                class="grade"
                                min="0"
                                max="4"
                                step="0.01"
                                placeholder="4.00"
                            >

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        <button onclick="addCourse()">
            ➕ Add Course
        </button>


        <button onclick="calculateGPA()">
            🎓 Calculate GPA
        </button>


        <div id="result"></div>


    </div>


</div>


<script>

function addCourse() {

    let table =
        document
        .getElementById("gpaTable")
        .getElementsByTagName("tbody")[0];


    let row =
        table.insertRow();


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
                placeholder="3"
            >

        </td>

        <td>

            <input
                type="number"
                class="grade"
                min="0"
                max="4"
                step="0.01"
                placeholder="4.00"
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
            parseFloat(
                credits[i].value
            );

        let grade =
            parseFloat(
                grades[i].value
            );


        if (
            isNaN(credit) ||
            isNaN(grade)
        ) {

            alert(
                "Please enter Credit Hours and Grade Point for every course."
            );

            return;

        }


        if (
            credit <= 0 ||
            grade < 0 ||
            grade > 4
        ) {

            alert(
                "Please enter valid Credit Hours and Grade Point."
            );

            return;

        }


        totalQualityPoints +=
            credit * grade;


        totalCredits +=
            credit;

    }


    if (totalCredits === 0) {

        alert(
            "Please add at least one course."
        );

        return;

    }


    let gpa =
        totalQualityPoints /
        totalCredits;


    document
        .getElementById("result")
        .innerHTML =
        "🎓 Your GPA is: " +
        gpa.toFixed(2);

}

</script>


</body>

</html>