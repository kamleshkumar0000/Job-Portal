<?php

session_start();

require '../database.php';

/** @var MongoDB\Collection $jobs */
/** @var MongoDB\Collection $applications */


// Candidate login check
if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;

}


// Only candidate can apply
if ($_SESSION["role"] != "candidate") {

    echo "Only candidates can apply for jobs.";
    exit;

}


// Check Job ID
if (!isset($_POST["job_id"]) || empty($_POST["job_id"])) {

    echo "Invalid Job ID.";
    exit;

}


$jobId = $_POST["job_id"];


// Convert ID into MongoDB ObjectId
try {

    $objectIdClass = 'MongoDB\\BSON\\ObjectId';

    $jobObjectId = new $objectIdClass($jobId);

} catch (Exception $e) {

    echo "Invalid Job ID.";
    exit;

}


// Find Job
$job = $jobs->findOne([
    "_id" => $jobObjectId
]);


if (!$job) {

    echo "Job not found.";
    exit;

}


// Check whether already applied
$existingApplication = $applications->findOne([

    "job_id" => $jobObjectId,

    "candidate_id" => $_SESSION["user_id"]

]);


if ($existingApplication) {

?>

<!DOCTYPE html>

<html>

<head>

    <title>Already Applied</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            text-align: center;
            padding-top: 100px;
        }

        .box {
            background: white;
            width: 450px;
            max-width: 90%;
            margin: auto;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            color: #dc2626;
        }

        a {
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

    <div class="box">

        <h2>Already Applied</h2>

        <p>
            You have already applied for this job.
        </p>

        <a href="jobs.php">
            Back to Jobs
        </a>

    </div>

</body>

</html>

<?php

    exit;

}


// ------------------------------------------------
// FORM SUBMISSION
// ------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST["submit_application"])) {


    $qualification = trim(
        $_POST["qualification"] ?? ""
    );

    $skills = trim(
        $_POST["skills"] ?? ""
    );

    $experience = trim(
        $_POST["experience"] ?? ""
    );


    // Check required fields
    if (
        empty($qualification) ||
        empty($skills)
    ) {

        $error = "Please fill all required fields.";

    } else {


        // Save Application
        $utcDateTimeClass = 'MongoDB\\BSON\\UTCDateTime';


        $applications->insertOne([

            "job_id" => $jobObjectId,

            "candidate_id" =>
                $_SESSION["user_id"],

            "candidate_name" =>
                $_SESSION["name"],

            "candidate_email" =>
                $_SESSION["email"],

            "job_title" =>
                $job["job_title"],

            "company" =>
                $job["company"],

            "qualification" =>
                $qualification,

            "skills" =>
                $skills,

            "experience" =>
                $experience,

            "status" =>
                "Applied",

            "applied_date" =>
                new $utcDateTimeClass()

        ]);


        // Application submitted
        $success = true;

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

    <title>Apply for Job - JobFinder</title>


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


        .container {

            width: 90%;

            max-width: 600px;

            margin: 50px auto;

        }


        .application-box {

            background: white;

            padding: 35px;

            border-radius: 12px;

            box-shadow:
                0 5px 20px rgba(0,0,0,0.1);

        }


        h1 {

            text-align: center;

            color: #2563eb;

            margin-bottom: 10px;

        }


        .job-details {

            background: #eff6ff;

            padding: 18px;

            border-radius: 8px;

            margin-bottom: 25px;

        }


        .job-details p {

            margin: 8px 0;

        }


        label {

            display: block;

            font-weight: bold;

            margin-bottom: 7px;

        }


        input,
        textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #ccc;

            border-radius: 7px;

            margin-bottom: 18px;

            font-size: 15px;

            outline: none;

        }


        input:focus,
        textarea:focus {

            border-color: #2563eb;

        }


        textarea {

            min-height: 110px;

            resize: vertical;

        }


        .submit-btn {

            width: 100%;

            padding: 13px;

            background: #2563eb;

            color: white;

            border: none;

            border-radius: 7px;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

        }


        .submit-btn:hover {

            background: #1d4ed8;

        }


        .error {

            background: #fee2e2;

            color: #b91c1c;

            padding: 12px;

            border-radius: 7px;

            margin-bottom: 20px;

        }


        .success {

            text-align: center;

        }


        .success h2 {

            color: #16a34a;

        }


        .back-btn {

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


<div class="container">


<?php if (isset($success) && $success) { ?>


    <!-- SUCCESS MESSAGE -->

    <div class="application-box success">

        <h2>
            Application Submitted Successfully! 🎉
        </h2>

        <p>
            Your application has been sent successfully.
        </p>

        <p>

            <strong>Job:</strong>

            <?php

            echo htmlspecialchars(
                $job["job_title"]
            );

            ?>

        </p>


        <p>

            <strong>Company:</strong>

            <?php

            echo htmlspecialchars(
                $job["company"]
            );

            ?>

        </p>


        <a
            href="jobs.php"
            class="back-btn"
        >
            Back to Jobs
        </a>

    </div>


<?php } else { ?>


    <!-- APPLICATION FORM -->

    <div class="application-box">


        <h1>
            Apply for Job
        </h1>


        <div class="job-details">

            <p>

                <strong>Job:</strong>

                <?php

                echo htmlspecialchars(
                    $job["job_title"]
                );

                ?>

            </p>


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


        <?php if (!empty($error)) { ?>

            <div class="error">

                <?php

                echo htmlspecialchars($error);

                ?>

            </div>

        <?php } ?>


        <form
            method="POST"
            action=""
        >


            <input
                type="hidden"
                name="job_id"
                value="<?php
                    echo htmlspecialchars($jobId);
                ?>"
            >


            <label>
                Full Name
            </label>

            <input
                type="text"
                value="<?php
                    echo htmlspecialchars(
                        $_SESSION["name"]
                    );
                ?>"
                readonly
            >


            <label>
                Email
            </label>

            <input
                type="email"
                value="<?php
                    echo htmlspecialchars(
                        $_SESSION["email"]
                    );
                ?>"
                readonly
            >


            <label>
                Qualification *
            </label>

            <input
                type="text"
                name="qualification"
                placeholder="Example: B.Sc Computer Science"
                required
            >


            <label>
                Skills *
            </label>

            <textarea
                name="skills"
                placeholder="Example: HTML, CSS, JavaScript, PHP, MongoDB"
                required
            ></textarea>


            <label>
                Experience
            </label>

            <textarea
                name="experience"
                placeholder="Enter your experience or write Fresher"
            ></textarea>


            <button
                type="submit"
                name="submit_application"
                class="submit-btn"
            >
                Submit Application
            </button>


        </form>


    </div>


<?php } ?>


</div>


</body>

</html>