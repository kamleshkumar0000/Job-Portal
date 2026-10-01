<?php

session_start();

require 'database.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $userMessage = trim($_POST["message"]);


    if (
        $name == "" ||
        $email == "" ||
        $subject == "" ||
        $userMessage == ""
    ) {

        $message = "Please fill all fields.";

    } else {

        $utcDateTimeClass = 'MongoDB\\BSON\\UTCDateTime';
        $messages->insertOne([

            "name" => $name,

            "email" => $email,

            "subject" => $subject,

            "message" => $userMessage,

            "created_at" =>
                new $utcDateTimeClass()

        ]);

        $message =
            "Your message has been sent successfully!";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Contact Us - JobFinder</title>

    <style>

        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f5f7fb;
        }

        .navbar {
            background: white;
            padding: 18px 7%;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 27px;
            font-weight: bold;
        }

        .logo span {
            color: #2563eb;
        }

        .navbar a {
            text-decoration: none;
            color: #2563eb;
            font-weight: bold;
            margin-left: 15px;
        }

        .container {
            width: 90%;
            max-width: 600px;

            margin: 50px auto;
        }

        .contact-box {
            background: white;

            padding: 35px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            color: #2563eb;
        }

        .message {
            background: #dcfce7;

            color: #15803d;

            padding: 12px;

            border-radius: 6px;

            text-align: center;

            margin-bottom: 20px;
        }

        label {
            display: block;

            margin-top: 15px;

            margin-bottom: 6px;

            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;

            padding: 12px;

            border: 1px solid #ddd;

            border-radius: 6px;

            font-size: 15px;
        }

        textarea {
            height: 130px;

            resize: vertical;
        }

        button {
            width: 100%;

            margin-top: 20px;

            padding: 13px;

            border: none;

            border-radius: 6px;

            background: #2563eb;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

    </style>

</head>


<body>


    <nav class="navbar">

        <div class="logo">

            <span>Job</span>Finder

        </div>

        <div>

            <a href="index.html">
                Home
            </a>

            <a href="jobs/jobs.php">
                Jobs
            </a>

            <?php if (isset($_SESSION["user_id"])) { ?>

                <a href="logout.php">
                    Logout
                </a>

            <?php } else { ?>

                <a href="login.php">
                    Login
                </a>

            <?php } ?>

        </div>

    </nav>


    <div class="container">

        <div class="contact-box">

            <h1>
                Contact Us
            </h1>

            <?php if ($message != "") { ?>

                <div class="message">

                    <?php
                    echo htmlspecialchars($message);
                    ?>

                </div>

            <?php } ?>


            <form
                action="contact.php"
                method="POST"
            >

                <label>
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your name"
                    required
                >


                <label>
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >


                <label>
                    Subject
                </label>

                <input
                    type="text"
                    name="subject"
                    placeholder="Enter subject"
                    required
                >


                <label>
                    Message
                </label>

                <textarea
                    name="message"
                    placeholder="Write your message..."
                    required
                ></textarea>


                <button type="submit">

                    Send Message

                </button>

            </form>

        </div>

    </div>


</body>

</html>