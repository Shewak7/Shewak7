<?php
session_start();

// Assuming user ID is stored in the session during login
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Check if "job_id" is set in $_POST
    if (isset($_POST["job_id"])) {
        $jobId = $_POST["job_id"];

        // Include the database connection file
        include_once "db_connection.php";

        // Check if the user has already applied for the job
        $stmtCheck = $pdo->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
        $stmtCheck->execute([$userId, $jobId]);

        if ($stmtCheck->rowCount() == 0) {
            // Insert the application into the applications table (assuming you have an 'applications' table)
            $stmt = $pdo->prepare("INSERT INTO applications (user_id, job_id) VALUES (?, ?)");
            $stmt->execute([$userId, $jobId]);
            
            // Redirect to the success page
            header("Location: ajobs.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Apply for Jobs</title>
    <style>
        /* Add your additional styles here */

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

        .job-listings li {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
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
            <li><a href="jpj.php">Home</a></li>
            <li><a href="apply-jobs.php">Apply Jobs</a></li>
            <li><a href="app.php">My Applications</a></li>
            <li><a href="jprofile.php">My Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="job-listings">
    <h2>Available Jobs</h2>

    <?php
    // Include the database connection file
    include_once "db_connection.php";

    // Fetch job listings from the jobs table
    $jobListings = getJobListings();

    // Function to fetch job listings
    function getJobListings() {
        global $pdo; // Assuming $pdo is your database connection

        $stmt = $pdo->query("SELECT * FROM jobs");
        $jobListings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $jobListings;
    }
    ?>

    <?php if (empty($jobListings)): ?>
        <p>No job listings available.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($jobListings as $job): ?>
                <li>
                    <h3><?php echo $job['job_title']; ?></h3>
                    <p>Company: <?php echo $job['company_name']; ?></p>
                    <p>Description: <?php echo $job['job_description']; ?></p>
                    <p>Requirements: <?php echo $job['job_requirements']; ?></p>
                    <p>Position: <?php echo $job['job_position']; ?></p>
                    <p>Salary: <?php echo $job['salary']; ?></p>
                    <form action="ajobs.php" method="post">
                        <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                        <button type="submit">Apply</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<!-- ... (Your existing code) ... -->

</body>
</html>
