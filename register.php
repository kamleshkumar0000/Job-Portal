<?php

require 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (empty($name) || empty($email) || empty($password)) {
        $message = "Please fill all fields.";
    } else {

        $existingUser = $users->findOne([
            "email" => $email
        ]);

        if ($existingUser) {
            $message = "Email already registered.";
        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $users->insertOne([
                "name" => $name,
                "email" => $email,
                "password" => $hashedPassword,
                "role" => $role
            ]);

            header("Location: login.php");
            exit;
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

    <title>Register - Job Portal</title>

    <!-- Same CSS as Login page -->
    <link
        rel="stylesheet"
        href="./style.css?v=2"
    >

</head>

<body>

<div class="auth-container">

    <div class="auth-box">

        <h2>Create Account</h2>

        <p>
            Join our Job Portal today
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
                type="text"
                name="name"
                placeholder="Full Name"
                autocomplete="off"
                required
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
                placeholder="Create Password"
                autocomplete="new-password"
                required
            >


            <select
                name="role"
                autocomplete="off"
                required
            >

                <option value="">
                    Select Role
                </option>

                <option value="candidate">
                    Candidate
                </option>

                <option value="employer">
                    Employer
                </option>

            </select>


            <button type="submit">
                Register
            </button>

        </form>


        <p>

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </p>


        <a href="index.html">
            ← Back to Home
        </a>

    </div>

</div>

</body>

</html>