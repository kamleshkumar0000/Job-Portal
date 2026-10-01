<?php

session_start();

require '../database.php';

/** @var MongoDB\Collection $jobs */

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}


// Search jobs
if ($search != "") {

    $jobsList = $jobs->find([
        '$or' => [
            [
                "job_title" => [
                    '$regex' => $search,
                    '$options' => 'i'
                ]
            ],
            [
                "company" => [
                    '$regex' => $search,
                    '$options' => 'i'
                ]
            ],
            [
                "location" => [
                    '$regex' => $search,
                    '$options' => 'i'
                ]
            ]
        ]
    ]);

} else {

    // Show all jobs
    $jobsList = $jobs->find(
        [],
        [
            'sort' => [
                'posted_date' => -1
            ]
        ]
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Search Jobs - JobFinder</title>

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

        .search-box {
            background: white;
            padding: 25px;
            border-radius: 12px;

            display: flex;
            gap: 10px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.08);

            margin-bottom: 35px;
        }

        .search-box input {
            flex: 1;

            padding: 14px;

            border: 1px solid #ddd;

            border-radius: 6px;

            font-size: 16px;
        }

        .search-box button {
            padding: 14px 25px;

            border: none;

            border-radius: 6px;

            background: #2563eb;

            color: white;

            font-weight: bold;

            cursor: pointer;
        }

        .search-box button:hover {
            background: #1d4ed8;
        }

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h1 {
            color: #2563eb;
        }

        .job-container {
            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(280px, 1fr));

            gap: 25px;
        }

        .job-card {
            background: white;

            padding: 25px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .job-card h2 {
            color: #2563eb;
        }

        .info {
            color: #555;
            margin: 10px 0;
        }

        .description {
            color: #555;
            line-height: 1.5;
        }

        .apply-btn {
            display: block;

            text-align: center;

            padding: 12px;

            margin-top: 18px;

            background: #2563eb;

            color: white;

            text-decoration: none;

            border-radius: 6px;

            font-weight: bold;
        }

        .no-jobs {
            background: white;

            padding: 40px;

            text-align: center;

            border-radius: 12px;

            color: #666;
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

            <a href="../index.html">
                Home
            </a>

            <a href="jobs.php">
                Jobs
            </a>

            <?php if (isset($_SESSION["user_id"])) { ?>

                <a href="my_applications.php">
                    My Applications
                </a>

                <a href="../logout.php">
                    Logout
                </a>

            <?php } else { ?>

                <a href="../login.php">
                    Login
                </a>

            <?php } ?>

        </div>

    </nav>


    <div class="container">


        <!-- Search -->

        <form
            action="search_jobs.php"
            method="GET"
            class="search-box"
        >

            <input
                type="text"
                name="search"
                placeholder="Search job, company or location..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">
                Search
            </button>

        </form>


        <?php if ($search != "") { ?>

            <div class="heading">

                <h1>
                    Search Results
                </h1>

                <p>
                    Results for:
                    <strong>
                        <?php echo htmlspecialchars($search); ?>
                    </strong>
                </p>

            </div>

        <?php } else { ?>

            <div class="heading">

                <h1>
                    Find Your Dream Job
                </h1>

                <p>
                    Search jobs by title, company or location.
                </p>

            </div>

        <?php } ?>


        <!-- Job Results -->

        <div class="job-container">

            <?php

            $found = false;

            foreach ($jobsList as $job) {

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

                        <strong>
                            Company:
                        </strong>

                        <?php

                        echo htmlspecialchars(
                            $job["company"]
                        );

                        ?>

                    </p>


                    <p class="info">

                        📍

                        <strong>
                            Location:
                        </strong>

                        <?php

                        echo htmlspecialchars(
                            $job["location"]
                        );

                        ?>

                    </p>


                    <p class="info">

                        💰

                        <strong>
                            Salary:
                        </strong>

                        <?php

                        echo htmlspecialchars(
                            $job["salary"]
                        );

                        ?>

                    </p>


                    <p class="description">

                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $job["description"]
                            )
                        );

                        ?>

                    </p>


                    <?php

                    if (
                        isset($_SESSION["user_id"]) &&
                        $_SESSION["role"] == "candidate"
                    ) {

                    ?>

                        <form
                            action="apply_job.php"
                            method="POST"
                        >

                            <input
                                type="hidden"
                                name="job_id"
                                value="<?php
                                    echo $job["_id"];
                                ?>"
                            >

                            <button
                                type="submit"
                                class="apply-btn"
                            >
                                Apply Now
                            </button>

                        </form>

                    <?php

                    }

                    ?>

                </div>

            <?php

            }


            if (!$found) {

            ?>

                <div class="no-jobs">

                    <h2>
                        No Jobs Found
                    </h2>

                    <p>
                        Try another job title, company or location.
                    </p>

                </div>

            <?php

            }

            ?>

        </div>

    </div>

</body>

</html>