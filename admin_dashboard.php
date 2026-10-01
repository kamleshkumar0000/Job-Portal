<?php

session_start();

require 'database.php';

// Admin login check
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Check admin role
if ($_SESSION["role"] != "admin") {
    echo "Access Denied. Only admin can access this page.";
    exit;
}


// Get all users
$allUsers = $users->find(
    [],
    [
        'sort' => [
            '_id' => -1
        ]
    ]
);


// Get all jobs
$allJobs = $jobs->find(
    [],
    [
        'sort' => [
            'posted_date' => -1
        ]
    ]
);


// Get all applications
$allApplications = $applications->find(
    [],
    [
        'sort' => [
            'applied_date' => -1
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

    <title>Admin Dashboard - JobFinder</title>

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
            width: 92%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .heading {
            text-align: center;
            margin-bottom: 30px;
        }

        .heading h1 {
            color: #2563eb;
        }

        /* Statistics */

        .stats {
            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));

            gap: 20px;

            margin-bottom: 40px;
        }

        .stat-card {
            background: white;

            padding: 25px;

            border-radius: 12px;

            text-align: center;

            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .stat-card h2 {
            color: #2563eb;
            font-size: 32px;
            margin: 10px;
        }

        .stat-card p {
            color: #666;
        }

        /* Sections */

        .section {
            background: white;

            padding: 25px;

            border-radius: 12px;

            margin-bottom: 30px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.08);

            overflow-x: auto;
        }

        .section h2 {
            color: #2563eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;

            border-bottom: 1px solid #ddd;

            text-align: left;
        }

        th {
            background: #f1f5f9;
        }

        .status {
            padding: 6px 12px;

            background: #dcfce7;

            color: #15803d;

            border-radius: 15px;

            font-weight: bold;
        }

        .empty {
            text-align: center;
            color: #666;
            padding: 20px;
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

            echo htmlspecialchars(
                $_SESSION["name"]
            );

            ?>

            <a href="logout.php">
                Logout
            </a>

        </div>

    </nav>


    <div class="container">


        <!-- Heading -->

        <div class="heading">

            <h1>
                Admin Dashboard
            </h1>

            <p>
                Manage users, jobs and applications.
            </p>

        </div>


        <!-- Statistics -->

        <div class="stats">

            <div class="stat-card">

                <h2>

                    <?php

                    echo $users->countDocuments();

                    ?>

                </h2>

                <p>
                    Total Users
                </p>

            </div>


            <div class="stat-card">

                <h2>

                    <?php

                    echo $jobs->countDocuments();

                    ?>

                </h2>

                <p>
                    Total Jobs
                </p>

            </div>


            <div class="stat-card">

                <h2>

                    <?php

                    echo $applications->countDocuments();

                    ?>

                </h2>

                <p>
                    Applications
                </p>

            </div>

        </div>


        <!-- Users -->

        <div class="section">

            <h2>
                All Users
            </h2>

            <table>

                <tr>

                    <th>
                        Name
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Role
                    </th>

                </tr>


                <?php

                $userFound = false;

                foreach ($allUsers as $user) {

                    $userFound = true;

                ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["name"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["email"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $user["role"]
                            );

                            ?>

                        </td>

                    </tr>

                <?php

                }


                if (!$userFound) {

                ?>

                    <tr>

                        <td
                            colspan="3"
                            class="empty"
                        >
                            No users found.
                        </td>

                    </tr>

                <?php

                }

                ?>

            </table>

        </div>


        <!-- Jobs -->

        <div class="section">

            <h2>
                All Jobs
            </h2>

            <table>

                <tr>

                    <th>
                        Job Title
                    </th>

                    <th>
                        Company
                    </th>

                    <th>
                        Location
                    </th>

                    <th>
                        Salary
                    </th>

                </tr>


                <?php

                $jobFound = false;

                foreach ($allJobs as $job) {

                    $jobFound = true;

                ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $job["job_title"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $job["company"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $job["location"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $job["salary"]
                            );

                            ?>

                        </td>

                    </tr>

                <?php

                }


                if (!$jobFound) {

                ?>

                    <tr>

                        <td
                            colspan="4"
                            class="empty"
                        >
                            No jobs found.
                        </td>

                    </tr>

                <?php

                }

                ?>

            </table>

        </div>


        <!-- Applications -->

        <div class="section">

            <h2>
                All Applications
            </h2>

            <table>

                <tr>

                    <th>
                        Candidate
                    </th>

                    <th>
                        Job
                    </th>

                    <th>
                        Company
                    </th>

                    <th>
                        Status
                    </th>

                </tr>


                <?php

                $applicationFound = false;

                foreach ($allApplications as $application) {

                    $applicationFound = true;

                ?>

                    <tr>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $application["candidate_name"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $application["job_title"]
                            );

                            ?>

                        </td>

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $application["company"]
                            );

                            ?>

                        </td>

                        <td>

                            <span class="status">

                                <?php

                                echo htmlspecialchars(
                                    $application["status"]
                                );

                                ?>

                            </span>

                        </td>

                    </tr>

                <?php

                }


                if (!$applicationFound) {

                ?>

                    <tr>

                        <td
                            colspan="4"
                            class="empty"
                        >
                            No applications found.
                        </td>

                    </tr>

                <?php

                }

                ?>

            </table>

        </div>


    </div>

</body>

</html> 