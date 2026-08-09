<?php

session_start();

include "db.php";

$message = "";

if (isset($_POST['teacher_login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    /* Find teacher */

    $stmt = $conn->prepare(
        "SELECT
            id,
            name,
            email,
            password,
            course_id
         FROM teachers
         WHERE email = ?"
    );

    $stmt->bind_param(
        "s",
        $email
    );

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows == 1) {

        $teacher = $result->fetch_assoc();


        /* Verify Password */

        if (
            password_verify(
                $password,
                $teacher['password']
            )
        ) {

            /* Create Teacher Session */

            $_SESSION['teacher_id'] =
                $teacher['id'];

            $_SESSION['teacher_name'] =
                $teacher['name'];

            $_SESSION['teacher_email'] =
                $teacher['email'];

            $_SESSION['teacher_course_id'] =
                $teacher['course_id'];


            /* Go to Teacher Dashboard */

            header(
                "Location: teacher_dashboard.php"
            );

            exit();

        } else {

            $message =
                "Incorrect password!";

        }

    } else {

        $message =
            "Teacher account not found!";

    }

    $stmt->close();
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
Teacher Login - Smart College Portal
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
            👨‍🏫 Teacher Login
        </h2>


        <?php if ($message != ""): ?>

            <p
                style="
                color:#7c3aed;
                font-weight:bold;
                margin-bottom:15px;
                "
            >

                <?php

                echo htmlspecialchars(
                    $message
                );

                ?>

            </p>

        <?php endif; ?>


        <form
            action="teacher_login.php"
            method="POST"
        >

            <label>
                Teacher Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Enter teacher email"
                required
            >


            <label>
                Teacher Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Enter teacher password"
                required
            >


            <button
                type="submit"
                name="teacher_login"
            >

                Login as Teacher

            </button>

        </form>


        <p>

            Are you a student?

            <a href="login.php">
                Student Login
            </a>

        </p>


        <p>

            Are you an administrator?

            <a href="admin_login.php">
                Admin Login
            </a>

        </p>


        <a href="index.php">
            ← Back to Home
        </a>

    </div>

</div>

</body>

</html>