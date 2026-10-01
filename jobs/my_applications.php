<?php

session_start();

require '../database.php';

/** @var MongoDB\Collection $applications */

// Check login
if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;

}

// Only candidate can access
if ($_SESSION["role"] != "candidate") {

    echo "Only candidates can view applications.";
    exit;

}


// Find candidate applications
$myApplications = $applications->find(
    [
        "candidate_id" => $_SESSION["user_id"]
    ],
    [
        "sort" => [
            "applied_date" => -1
        ]
    ]
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Applications - JobFinder</title>

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
            max-width: 1100px;
            margin: 45px auto;
        }

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h1 {
            color: #2563eb;
        }

        .application-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .application-card h2 {
            color: #2563eb;
            margin-bottom: 8px;
        }

        .info {
            color: #555;
            margin: 8px 0;
        }

        .status {
            display: inline-block;

            padding: 8px 15px;

            background: #dcfce7;
            color: #15803d;

            border-radius: 20px;

            font-weight: bold;

            margin-top: 10px;
        }

        .no-applications {
            background: white;
            text-align: center;

            padding: 40px;

            border-radius: 12px;
        }

        .btn {
            display: inline-block;

            margin-top: 20px;

            padding: 12px 25px;

            background: #2563eb;
            color: white;

            text-decoration: none;

            border-radius: 6px;
        }

    </style>

</head>


<body>


    <!-- Navbar -->

    <nav class="navbar">

        <div class="logo">
            <span>Job</span>Finder
        </div>

        <div>

            Welcome,
            <?php
            echo htmlspecialchars($_SESSION["name"]);
            ?>

            <a href="jobs.php">
                Jobs
            </a>

            <a href="../logout.php">
                Logout
            </a>

        </div>

    </nav>


    <!-- Main -->

    <div class="container">

        <div class="heading">

            <h1>My Applications</h1>

            <p>
                View all the jobs you have applied for.
            </p>

        </div>


        <?php

        $found = false;

        foreach ($myApplications as $application) {

            $found = true;

        ?>

            <div class="application-card">

                <h2>

                    <?php

                    echo htmlspecialchars(
                        $application["job_title"]
                    );

                    ?>

                </h2>


                <p class="info">

                    🏢

                    <strong>Company:</strong>

                    <?php

                    echo htmlspecialchars(
                        $application["company"]
                    );

                    ?>

                </p>


                <p class="info">

                    📧

                    <strong>Email:</strong>

                    <?php

                    echo htmlspecialchars(
                        $application["candidate_email"]
                    );

                    ?>

                </p>


                <p class="info">

                    📅

                    <strong>Application Date:</strong>

                    <?php

                    echo $application["applied_date"]
                        ->toDateTime()
                        ->format("d-m-Y");

                    ?>

                </p>


                <span class="status">

                    Status:
                    <?php

                    echo htmlspecialchars(
                        $application["status"]
                    );

                    ?>

                </span>

            </div>

        <?php

        }


        if (!$found) {

        ?>

            <div class="no-applications">

                <h2>
                    No Applications Found
                </h2>

                <p>
                    You have not applied for any job yet.
                </p>

                <a
                    href="jobs.php"
                    class="btn"
                >
                    Browse Jobs
                </a>

            </div>

        <?php

        }

        ?>

    </div>

</body>

</html>