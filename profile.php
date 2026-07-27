<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id = '$user_id'";

$result = $conn->query($sql);

$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile - Smart College Portal</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>


    <!-- Sidebar -->

    <div class="sidebar">

        <h2>🎓 Smart Portal</h2>

        <a href="dashboard.php">🏠 Dashboard</a>

        <a href="profile.php">👤 My Profile</a>

        <a href="courses.php">📚 Courses</a>

        <a href="attendance.php">📊 Attendance</a>

        <a href="results.php">📝 Results</a>
		
		<a href="student_reports.php">

        📈 My Reports & Analytics

    </a>

        <a href="fees.php">💰 Fees</a>

        <a href="announcements.php">📢 Announcements</a>

        <a href="assignments.php">📄 Assignments</a>

        <a href="logout.php" class="logout-link">🚪 Logout</a>

    </div>


    <!-- Main Content -->

    <div class="dashboard-content">

        <div class="top-bar">

            <h1>My Profile</h1>

            <div class="student-info">

                Welcome,

                <strong>

                    <?php echo htmlspecialchars($user['full_name']); ?>

                </strong>

            </div>

        </div>


        <!-- Profile Card -->

        <div class="profile-card">

            <div class="profile-icon">

                👤

            </div>


            <h2>

                <?php echo htmlspecialchars($user['full_name']); ?>

            </h2>

            <p class="role">

                <?php echo ucfirst($user['role']); ?>

            </p>


            <div class="profile-details">

                <div class="profile-detail">

                    <strong>Full Name</strong>

                    <p>

                        <?php echo htmlspecialchars($user['full_name']); ?>

                    </p>

                </div>


                <div class="profile-detail">

                    <strong>Email Address</strong>

                    <p>

                        <?php echo htmlspecialchars($user['email']); ?>

                    </p>

                </div>


                <div class="profile-detail">

                    <strong>Account Type</strong>

                    <p>

                        <?php echo ucfirst($user['role']); ?>

                    </p>

                </div>


                <div class="profile-detail">

                    <strong>Account Created</strong>

                    <p>

                        <?php echo date("d M Y", strtotime($user['created_at'])); ?>

                    </p>

                </div>

            </div>

        </div>

    </div>


</body>

</html>