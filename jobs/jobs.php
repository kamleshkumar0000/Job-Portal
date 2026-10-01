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

<title>Available Jobs - JobFinder</title>

<style>

* {
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    margin: 0;
    background: #f5f7fb;
    color: #222;
}

.navbar {
    background: #2563eb;
    padding: 15px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    color: white;
    font-size: 25px;
    font-weight: bold;
}

.navbar a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
}

.container {
    width: 90%;
    max-width: 1100px;
    margin: 40px auto;
}

h1 {
    text-align: center;
    color: #2563eb;
}

.search-box {
    display: flex;
    gap: 10px;
    margin: 30px auto;
    max-width: 700px;
}

.search-box input {
    flex: 1;
    padding: 13px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 15px;
}

.search-box button {
    padding: 13px 25px;
    border: none;
    background: #2563eb;
    color: white;
    border-radius: 7px;
    cursor: pointer;
    font-weight: bold;
}

.jobs {
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
        0 5px 20px rgba(0,0,0,0.08);
}

.job-card h2 {
    color: #2563eb;
    margin-top: 0;
}

.job-card p {
    margin: 10px 0;
}

.apply-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 11px 20px;
    background: #16a34a;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.login-btn {
    background: #f59e0b;
}

.message {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 10px;
}

</style>

</head>

<body>

<div class="navbar">

    <div class="logo">
        JobFinder
    </div>

    <div>

        <a href="../index.html">
            Home
        </a>

        <a href="jobs.php">
            Jobs
        </a>

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

</div>


<div class="container">

    <h1>Jobs Available</h1>


    <!-- Search -->

    <form
        action="search_jobs.php"
        method="GET"
        class="search-box"
    >

        <input
            type="text"
            name="search"
            placeholder="Search by job, company or location"
            required
        >

        <button type="submit">
            Search
        </button>

    </form>


    <?php if (isset($error)) { ?>

        <div class="message">

            <p>
                <?php echo htmlspecialchars($error); ?>
            </p>

        </div>

    <?php } else { ?>


        <div class="jobs">

        <?php

        $hasJobs = false;

        foreach ($jobList as $job) {

            $hasJobs = true;

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


                <!-- Candidate -->

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


                <!-- Not logged in -->

                <?php } elseif (
                    !isset($_SESSION["user_id"])
                ) { ?>

                    <a
                        href="../login.php"
                        class="apply-btn login-btn"
                    >
                        Login to Apply
                    </a>


                <?php } ?>

            </div>

        <?php } ?>


        <?php if (!$hasJobs) { ?>

            <div class="message">

                <h3>
                    No Jobs Available
                </h3>

                <p>
                    Currently there are no jobs posted.
                </p>

            </div>

        <?php } ?>

        </div>

    <?php } ?>

</div>

</body>

</html>