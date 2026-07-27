<?php

session_start();

include "db.php";

$message = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Incorrect password!";

        }

    } else {

        $message = "Email not found!";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Smart College Portal</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <h1>🎓 Smart College Portal</h1>

            <h2>Student Login</h2>

            <?php if ($message != ""): ?>

                <p style="color: #7c3aed; font-weight: bold;">
                    <?php echo $message; ?>
                </p>

            <?php endif; ?>

            <form action="login.php" method="POST">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

                <button type="submit" name="login">
                    Login
                </button>

            </form>

            <p>
                Don't have an account?
                <a href="register.php">Create Account</a>
            </p>

            <a href="index.php">← Back to Home</a>

        </div>

    </div>

</body>

</html>