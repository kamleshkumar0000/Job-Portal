<?php

session_start();

require '../database.php';

/** @var MongoDB\Collection $jobs */
try {

    $jobList = $jobs->find(
        [],
        [
            'sort' => [
                'posted_date' => -1
            ]
        ]
    );

} catch (Exception $e) {

    $jobList = [];
    $error = "Unable to load jobs.";

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Jobs - Job Portal</title>

    <link rel="stylesheet" href="../style.css">

    <style>

        .jobs-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .jobs-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .jobs-header h1 {
            color: #1e3a8a;
        }

        .jobs-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .job-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow:
                0 5px 20px rgba(0,0,0,0.10);
        }

        .job-card h2 {
            color: #2563eb;
            margin-bottom: 10px;
        }

        .job-card p {
            margin: 8px 0;
            color: #555;
        }

        .apply-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 11px 20px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
        }

        .apply-btn:hover {
            background: #1d4ed8;
        }

        .no-jobs {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow:
                0 5px 20px rgba(0,0,0,0.08);
        }

        .top-nav {
            background: #1e3a8a;
            padding: 15px;
            text-align: center;
        }

        .top-nav a {
            color: white;
            text-decoration: none;
            margin: 0 12px;
        }

    </style>

</head>

<body>

<!-- Navigation -->

<div class="top-nav">

    <a href="../index.html">Home</a>

    <a href="jobs.php">Jobs</a>

    <?php if (isset($_SESSION["user_id"])) { ?>

        <?php if ($_SESSION["role"] == "candidate") { ?>

            <a href="my_applications.php">
                My Applications
            </a>

        <?php } ?>

        <?php if ($_SESSION["role"] == "employer") { ?>

            <a href="employer_dashboard.php">
                Employer Dashboard
            </a>

        <?php } ?>

        <a href="../logout.php">
            Logout
        </a>

    <?php } else { ?>

        <a href="../login.php">
            Login
        </a>

        <a href="../register.php">
            Register
        </a>

    <?php } ?>

</div>


<!-- Jobs -->

<div class="jobs-container">

    <div class="jobs-header">

        <h1>Jobs Available</h1>

        <p>
            Find your perfect job opportunity
        </p>

    </div>


    <?php

    if (isset($error)) {

        echo "<div class='no-jobs'>";
        echo htmlspecialchars($error);
        echo "</div>";

    } else {

        $count = 0;

        foreach ($jobList as $job) {

            $count++;

    ?>

            <div class="job-card">

                <h2>
                    <?php
                    echo htmlspecialchars(
                        $job["job_title"] ?? "Job"
                    );
                    ?>
                </h2>

                <p>
                    <strong>Company:</strong>
                    <?php
                    echo htmlspecialchars(
                        $job["company"] ?? "N/A"
                    );
                    ?>
                </p>

                <p>
                    <strong>Location:</strong>
                    <?php
                    echo htmlspecialchars(
                        $job["location"] ?? "N/A"
                    );
                    ?>
                </p>

                <p>
                    <strong>Salary:</strong>
                    <?php
                    echo htmlspecialchars(
                        $job["salary"] ?? "Not specified"
                    );
                    ?>
                </p>

                <p>
                    <strong>Description:</strong>
                    <?php
                    echo htmlspecialchars(
                        $job["description"] ?? ""
                    );
                    ?>
                </p>


                <?php if (
                    isset($_SESSION["role"]) &&
                    $_SESSION["role"] == "candidate"
                ) { ?>

                    <form
                        action="apply_job.php"
                        method="POST"
                    >

                        <input
                            type="hidden"
                            name="job_id"
                            value="<?php
                                echo htmlspecialchars(
                                    (string)$job["_id"]
                                );
                            ?>"
                        >

                        <button
                            type="submit"
                            class="apply-btn"
                        >
                            Apply Now
                        </button>

                    </form>

                <?php } elseif (!isset($_SESSION["user_id"])) { ?>

                    <a
                        href="../login.php"
                        class="apply-btn"
                    >
                        Login to Apply
                    </a>

                <?php } ?>

            </div>

    <?php

        }

        if ($count == 0) {

    ?>

            <div class="no-jobs">

                <h2>No Jobs Available</h2>

                <p>
                    Currently there are no jobs posted.
                </p>

                <p>
                    Please check again later.
                </p>

            </div>

    <?php

        }

    }

    ?>

</div>

</body>

</html>