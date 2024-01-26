<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Applied Jobs</title>
    <!-- Add your additional styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        section {
            padding: 20px;
        }

        .applied-jobs {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">CareerCraftHub</div>
    <nav>
        <ul>
            <li><a href="jpj.php">Dashboard</a></li>
            <li><a href="jobmatch.php">Applied Jobs</a></li>
            <li><a href="jprofile.php">My Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="applied-jobs">
    <h2>Applied Jobs</h2>

    <?php
    // Start the session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect to the login page or any other page
        header("Location: login.php");
        exit();
    }

    // Include the database connection file
    include_once "db_connection.php";

    // Fetch applied jobs for the current job seeker
    $jobSeekerId = $_SESSION['user_id'];
    $appliedJobs = getAppliedJobs($jobSeekerId);

    // Function to fetch applied jobs
    function getAppliedJobs($jobSeekerId) {
        global $pdo; // Assuming $pdo is your database connection

        $stmt = $pdo->prepare("SELECT applications.*, jobs.*
                               FROM applications
                               INNER JOIN jobs ON applications.job_id = jobs.job_id
                               WHERE applications.user_id = ?");
        $stmt->execute([$jobSeekerId]);
        $appliedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $appliedJobs;
    }
    ?>

    <?php if (empty($appliedJobs)): ?>
        <p>You have not applied to any jobs yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($appliedJobs as $job): ?>
                <li>
                    <h3><?php echo $job['job_title']; ?></h3>
                    <p>Company: <?php echo $job['company_name']; ?></p>
                    <p>Status: <?php echo $job['status']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>


</body>
</html>
