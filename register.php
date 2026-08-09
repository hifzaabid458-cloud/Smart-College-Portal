<?php

include "db.php";

$message = "";

if (isset($_POST['register'])) {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    // Keep the original password for comparison
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check passwords BEFORE hashing
    if ($password !== $confirm_password) {

        $message = "Passwords do not match!";

    } else {

        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email = '$email'";

        $result = $conn->query($check_email);

        if ($result->num_rows > 0) {

            $message = "Email already exists!";

        } else {

            // Hash password only once
            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Register student
            $sql = "INSERT INTO users
                    (full_name, email, password, role)
                    VALUES
                    ('$full_name', '$email', '$hashed_password', 'student')";

            if ($conn->query($sql) === TRUE) {

                $message =
                    "Account created successfully! You can now login.";

            } else {

                $message =
                    "Error: " . $conn->error;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Register - Smart College Portal
    </title>

    <link
        rel="stylesheet"
        href="style.css"
    >

</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <h1>
                🎓 Smart College Portal
            </h1>

            <h2>
                Create Student Account
            </h2>


            <?php if ($message != ""): ?>

                <p
                    style="color: #7c3aed; font-weight: bold;"
                >
                    <?php echo htmlspecialchars($message); ?>
                </p>

            <?php endif; ?>


            <form
                action="register.php"
                method="POST"
            >

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="full_name"
                    placeholder="Enter your full name"
                    required
                >


                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >


                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >


                <label>
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    required
                >


                <button
                    type="submit"
                    name="register"
                >
                    Create Account
                </button>

            </form>


            <p>

                Already have an account?

                <a href="login.php">
                    Login Here
                </a>

            </p>


            <a href="index.php">
                ← Back to Home
            </a>

        </div>

    </div>

</body>

</html>