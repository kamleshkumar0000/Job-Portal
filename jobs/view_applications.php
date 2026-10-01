<?php

session_start();

require '../database.php';

// Check employer login
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

// Only employer can access
if ($_SESSION["role"] != "employer") {
    echo "Only employers can view applications.";
    exit;
}

// Check job ID
if (!isset($_GET["job_id"])) {
    echo "Job ID is missing.";
    exit;
}

$jobId = $_GET["job_id"];

// Convert ID to MongoDB ObjectId
try {

    $jobObjectId = new MongoDB\BSON\ObjectId($jobId);

} catch (Exception $e) {

    echo "Invalid Job ID.";
    exit;

}


// Find the job
$job = $jobs->findOne([
    "_id" => $jobObjectId,
    "posted_by" => $_SESSION["user_id"]
]);

if (!$job) {
    echo "Job not found or you are not authorized.";
    exit;
}


// Find applications for this job
$jobApplications = $applications->find(
    [
        "job_id" => $jobObjectId
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

    <title>View Applications - JobFinder</title>

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
            margin-bottom: 30px;
        }

        .heading h1 {
            color: #2563eb;
        }

        .job-info {
            background: white;
            padding: 20px;

            border-radius: 10px;

            margin-bottom: 25px;

            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
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
        }

        .info {
            color: #555;
            margin: 10px 0;
        }

        .status {
            display: inline-block;

            padding: 8px 15px;

            background: #dcfce7;

            color: #15803d;

            border-radius: 20px;

            font-weight: bold;
        }

        .back-btn {
            display: inline-block;

            margin-bottom: 25px;

            padding: 10px 18px;

            background: #2563eb;

            color: white;

            text-decoration: none;

            border-radius: 6px;
        }

        .no-applications {
            background: white;

            padding: 40px;

            text-align: center;

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

            <a href="employer_dashboard.php">
                Dashboard
            </a>

            <a href="../logout.php">
                Logout
            </a>

        </div>

    </nav>


    <!-- Main Content -->

    <div class="container">

        <a
            href="employer_dashboard.php"
            class="back-btn"
        >
            ← Back to Dashboard
        </a>


        <div class="heading">

            <h1>
                Job Applications
            </h1>

            <p>
                Candidates who applied for this job
            </p>

        </div>


        <!-- Job Information -->

        <div class="job-info">

            <h2>

                <?php

                echo htmlspecialchars(
                    $job["job_title"]
                );

                ?>

            </h2>

            <p>

                <strong>Company:</strong>

                <?php

                echo htmlspecialchars(
                    $job["company"]
                );

                ?>

            </p>

            <p>

                <strong>Location:</strong>

                <?php

                echo htmlspecialchars(
                    $job["location"]
                );

                ?>

            </p>

        </div>


        <?php

        $found = false;

        foreach ($jobApplications as $application) {

            $found = true;

        ?>

            <div class="application-card">

                <h2>
                    Candidate Details
                </h2>


                <p class="info">

                    👤

                    <strong>Name:</strong>

                    <?php

                    echo htmlspecialchars(
                        $application["candidate_name"]
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

                    <strong>Applied Date:</strong>

                    <?php

                    echo $application["applied_date"]
                        ->toDateTime()
                        ->format("d-m-Y");

                    ?>

                </p>


                <p>
                    <strong>
                        Current Status:
                    </strong>

                    <span class="status">

                        

                        <?php

                        echo htmlspecialchars(
                            $application["status"]
                        );

                        ?>

                    </span>

                </p>
                <form
                    action="update_application_status.php"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="application_id"
                        value="<?php
                           echo $application["_id"];
                        ?>"
                    >

                    <select
                        name="status"
                        required
                        style="
                           "padding: 10px;
                            border-radius: 6px;
                            border: 1px solid #add;
                            margin-right: 10px;
                        "
                    >
                    
                        <option value="Applied">
                            Applied
                        </option>

                        <option value="Shortlisted">
                            Shortlisted
                        </option>

                        <option value="Rejected">
                            Rejected
                        </option>
                        
                        
                 </select>

                    <button
                        type="submit"
                        style="
                            padding: 10px 18px;
                            margin-left:8px;
                            background: #2563eb;
                            color: white;
                            border: none;
                            border-radius: 6px;
                            cursor: pointer;
                        "
                    >

                        Update Status
                    </button>                        

                </form
            </div>
        <?php

        }


        if (!$found) {

        ?>

            <div class="no-applications">

                <h2>
                    No Applications Yet
                </h2>

                <p>
                    No candidate has applied for this job.
                </p>

            </div>

        <?php

        }

        ?>

    </div>

</body>

</html>