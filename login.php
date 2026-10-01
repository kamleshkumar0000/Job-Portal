<?php

session_start();

require 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";

    } else {

        $user = $users->findOne([
            "email" => $email
        ]);

        if (
            $user &&
            password_verify($password, $user["password"])
        ) {

            $_SESSION["user_id"] = (string)$user["_id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];


            // Admin
            if ($user["role"] == "admin") {

                header("Location: admin_dashboard.php");
                exit;

            }


            // Employer
            if ($user["role"] == "employer") {

                header("Location: jobs/employer_dashboard.php");
                exit;

            }


            // Candidate
            header("Location: jobs/jobs.php");
            exit;

        } else {

            $message = "Invalid email or password.";

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

    <title>Login - Job Portal</title>

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="./style.css?v=2"
    >

</head>


<body>

<div class="auth-container">

    <div class="auth-box">

        <h2>
            Welcome Back
        </h2>

        <p>
            Login to your Job Portal account
        </p>


        <?php if (!empty($message)) { ?>

            <div class="message">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php } ?>


        <form
            method="POST"
            autocomplete="off"
        >

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                autocomplete="off"
                required
            >


            <input
                type="password"
                name="password"
                placeholder="Password"
                autocomplete="new-password"
                required
            >


            <button type="submit">
                Login
            </button>

        </form>


        <p>

            Don't have an account?

            <a href="register.php">
                Register
            </a>

        </p>


        <a href="index.html">
            ← Back to Home
        </a>

    </div>

</div>

</body>

</html>