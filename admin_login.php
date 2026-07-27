<?php

session_start();

include "db.php";

$message = "";

if (isset($_POST['admin_login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'admin'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_email'] = $admin['email'];

            header("Location: admin_dashboard.php");

            exit();

        } else {

            $message = "Incorrect password!";

        }

    } else {

        $message = "Admin account not found!";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - Smart College Portal</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <h1>🎓 Smart College Portal</h1>

            <h2>🔐 Admin Login</h2>

            <?php if ($message != ""): ?>

                <p style="color: #7c3aed; font-weight: bold;">

                    <?php echo $message; ?>

                </p>

            <?php endif; ?>


            <form action="admin_login.php" method="POST">

                <label>Admin Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter admin email"
                    required
                >


                <label>Admin Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter admin password"
                    required
                >


                <button type="submit" name="admin_login">

                    Login as Admin

                </button>

            </form>


            <p>

                Are you a student?

                <a href="login.php">
                    Student Login
                </a>

            </p>


            <a href="index.php">
                ← Back to Home
            </a>

        </div>

    </div>

</body>

</html>