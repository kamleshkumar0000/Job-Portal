<?php

session_start();

require '../database.php';

/** @var MongoDB\Collection $jobs */

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

// Only employer can access
if ($_SESSION["role"] != "employer") {
    echo "Only employers can access this dashboard.";
    exit;
}

// Get employer's jobs
$myJobs = $jobs->find([
    "posted_by" => $_SESSION["user_id"]
]);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Employer Dashboard - JobFinder</title>

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
            margin: 40px auto;
        }

        .heading {
            text-align: center;
            margin-bottom: 35px;
        }

        .heading h1 {
            color: #2563eb;
        }

        .add-btn {
            display: inline-block;

            padding: 12px 20px;

            background: #2563eb;
            color: white;

            text-decoration: none;

            border-radius: 6px;

            margin-bottom: 25px;
        }

        .job-card {
            background: white;

            padding: 25px;

            margin-bottom: 20px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .job-card h2 {
            color: #2563eb;
        }

        .info {
            color: #555;
            margin: 8px 0;
        }

        .view-btn {
            display: inline-block;

            margin-top: 15px;

            padding: 10px 18px;

            background: #16a34a;
            color: white;

            text-decoration: none;

            border-radius: 6px;
        }

        .no-jobs {
            background: white;
            text-align: center;

            padding: 40px;

            border-radius: 12px;
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

            <a href="add_job.php">
                Post Job
            </a>

            <a href="../logout.php">
                Logout
            </a>

        </div>

    </nav>


    <!-- Dashboard -->

    <div class="container">

        <div class="heading">

            <h1>Employer Dashboard</h1>

            <p>
                Manage your posted jobs and applications.
            </p>

        </div>


        <a
            href="add_job.php"
            class="add-btn"
        >
            + Post New Job
        </a>


        <?php

        $found = false;

        foreach ($myJobs as $job) {

            $found = true;

        ?>

            <div class="job-card">

                <h2>

                    <?php

                    echo htmlspecialchars(
                        $job["job_title"]
                    );

                    ?>

                </h2>


                <p class="info">

                    🏢

                    <strong>Company:</strong>

                    <?php

                    echo htmlspecialchars(
                        $job["company"]
                    );

                    ?>

                </p>


                <p class="info">

                    📍

                    <strong>Location:</strong>

                    <?php

                    echo htmlspecialchars(
                        $job["location"]
                    );

                    ?>

                </p>


                <p class="info">

                    💰

                    <strong>Salary:</strong>

                    <?php

                    echo htmlspecialchars(
                        $job["salary"]
                    );

                    ?>

                </p>


                <p class="info">

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $job["description"]
                        )
                    );

                    ?>

                </p>


                <a
                    href="view_applications.php?job_id=<?php echo $job['_id']; ?>"
                    class="view-btn"
                >
                    View Applications
                </a>

            </div>

        <?php

        }


        if (!$found) {

        ?>

            <div class="no-jobs">

                <h2>
                    No Jobs Posted
                </h2>

                <p>
                    You have not posted any jobs yet.
                </p>

                <a
                    href="add_job.php"
                    class="add-btn"
                >
                    Post Your First Job
                </a>

            </div>

        <?php

        }

        ?>

    </div>

</body>

</html>