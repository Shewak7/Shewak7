<?php
session_start();

// Check if the user is logged in and retrieve user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include_once "db_connection.php";

// ... (rest of your code) ...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>View Job Applications</title>
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

        .application-item button {
            margin-top: 10px;
        }

        .status-form {
            margin-top: 20px;
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
            <li><a href="jpe.php">Home</a></li>
            <li><a href="post-job.php">Post Jobs</a></li>
            <li><a href="ejobs.php">My Jobs</a></li>
            <li><a href="eprofile.php">My Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="job-applications">
    <h2>Job Applications</h2>

    <?php
    // Fetch job applications for the current employer
    $employerId = $_SESSION['user_id'];
    $jobApplications = getJobApplications($employerId);

    // Function to fetch job applications
    function getJobApplications($employerId) {
        global $pdo; // Assuming $pdo is your database connection

        $stmt = $pdo->prepare("SELECT applications.*, jprofile.*, jobs.*, eprofile.company_name as employer_company_name
                               FROM applications
                               INNER JOIN jobs ON applications.job_id = jobs.job_id
                               INNER JOIN jprofile ON applications.user_id = jprofile.user_id
                               INNER JOIN eprofile ON jobs.user_id = eprofile.user_id
                               WHERE eprofile.user_id = ?
        ");
        $stmt->execute([$employerId]);
        $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $jobApplications;
    }
    ?>

    <?php if (empty($jobApplications)): ?>
        <p>No job applications found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($jobApplications as $application): ?>
                <li class="application-item">
                    <h3><?php echo $application['job_title']; ?></h3>
                    <p>Applicant Name: <?php echo $application['name']; ?></p>
                    <p>Applicant Age: <?php echo $application['age']; ?></p>
                    <p>Applicant Qualification: <?php echo $application['qualification']; ?></p>
                    <p>Applicant Experience: <?php echo $application['experience']; ?></p>
                    <p>Applicant Skills: <?php echo $application['skills']; ?></p>
                    <p>Company: <?php echo $application['company_name']; ?></p>
                    <p>Job Description: <?php echo $application['job_description']; ?></p>

                    <!-- Add the status form -->
                    <form class="status-form" action="set_status.php" method="post">
                        <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                        <label for="status">Application Status:</label>
                        <select id="status" name="status">
                            <option value="pending" <?php echo ($application['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="accepted" <?php echo ($application['status'] === 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                            <option value="rejected" <?php echo ($application['status'] === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>



</body>
</html>
