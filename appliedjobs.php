<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Jobseeker Dashboard</title>
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

        .job-applications {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        .application-item {
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
    <div class="logo">Your Logo</div>
    <nav>
        <ul>
            <li><a href="jobseeker_dashboard.php">Dashboard</a></li>
            <li><a href="applied_jobs.php">Applied Jobs</a></li>
            <li><a href="jprofile.php">My Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="job-applications">
    <h2>Applied Jobs</h2>

    <?php
    // Fetch job applications for the current jobseeker
    $jobseekerId = $_SESSION['user_id'];
    $appliedJobs = getAppliedJobs($jobseekerId);

    // Function to fetch applied jobs
    function getAppliedJobs($jobseekerId) {
        global $pdo; // Assuming $pdo is your database connection

        $stmt = $pdo->prepare("SELECT applications.*, jobs.*
                               FROM applications
                               INNER JOIN jobs ON applications.job_id = jobs.job_id
                               WHERE applications.user_id = ?");
        $stmt->execute([$jobseekerId]);
        $appliedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $appliedJobs;
    }
    ?>

    <?php if (empty($appliedJobs)): ?>
        <p>No applied jobs found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($appliedJobs as $job): ?>
                <li class="application-item">
                    <h3><?php echo $job['job_title']; ?></h3>
                    <p>Company: <?php echo $job['company_name']; ?></p>
                    <p>Job Description: <?php echo $job['job_description']; ?></p>
                    <p>Status: <?php echo $job['status']; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<footer>
    <div class="contact-info">
        <h3>Contact Us</h3>
        <p>Email: info@example.com</p>
        <p>Phone: (123) 456-7890</p>
    </div>
</footer>

</body>
</html>
