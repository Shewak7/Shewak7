<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Job Recruitment System - Stats</title>
    <style>
        /* Add your global styles here */

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .stats-container {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .stat-box {
            flex: 0 1 calc(30% - 20px);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .stat-box h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .stat-box p {
            margin: 0;
            color: #666;
        }

        .stat-box .highlight {
            font-weight: bold;
            color: #333;
            font-size: 20px; /* Increase font size for highlight */
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
        <h1>Job Recruitment System - Stats</h1>
        <!-- Add navigation or other header content if needed -->
    </header>

    <section class="stats-container">
        <?php
        // Include your database connection file
        include_once "db_connection.php";

        // Function to fetch statistics
        function getStatistics($pdo) {
            $stats = [];

            // Total Users
            $stmt = $pdo->query("SELECT COUNT(*) FROM users");
            $stats['total_users'] = $stmt->fetchColumn();

            // Total Employers
            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'employer'");
            $stats['total_employers'] = $stmt->fetchColumn();

            // Total Job Seekers
            $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'jobseeker'");
            $stats['total_job_seekers'] = $stmt->fetchColumn();

            // Total Companies
            $stmt = $pdo->query("SELECT COUNT(*) FROM eprofile");
            $stats['total_companies'] = $stmt->fetchColumn();

            // Total Job Postings
            $stmt = $pdo->query("SELECT COUNT(*) FROM jobs");
            $stats['total_job_postings'] = $stmt->fetchColumn();

            // Total Applications
            $stmt = $pdo->query("SELECT COUNT(*) FROM applications");
            $stats['total_applications'] = $stmt->fetchColumn();

            // Total Accepted Applications
            $stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'accepted'");
            $stats['total_accepted_applications'] = $stmt->fetchColumn();

            // Total Rejected Applications
            $stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'rejected'");
            $stats['total_rejected_applications'] = $stmt->fetchColumn();

            // Total Pending Applications
            $stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'pending'");
            $stats['total_pending_applications'] = $stmt->fetchColumn();

            // Additional statistics
            // Total Active Employers (who have posted at least one job)
            $stmt = $pdo->query("SELECT COUNT(DISTINCT user_id) FROM jobs");
            $stats['total_active_employers'] = $stmt->fetchColumn();

            // Average Applications per Job Posting
            $stmt = $pdo->query("SELECT AVG(application_count) FROM (SELECT job_id, COUNT(*) AS application_count FROM applications GROUP BY job_id) AS app_counts");
            $stats['avg_applications_per_job'] = round($stmt->fetchColumn(), 2);

            // Highest Salary Job
            $stmt = $pdo->query("SELECT MIN(salary) FROM jobs");
            $stats['highest_salary_job'] = $stmt->fetchColumn();

            return $stats;
        }

        // Fetch statistics
        $statistics = getStatistics($pdo);
        ?>

        <div class="stat-box">
            <h2>Total Users</h2>
            <p class="highlight"><?php echo $statistics['total_users']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Employers</h2>
            <p class="highlight"><?php echo $statistics['total_employers']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Job Seekers</h2>
            <p class="highlight"><?php echo $statistics['total_job_seekers']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Companies</h2>
            <p class="highlight"><?php echo $statistics['total_companies']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Job Postings</h2>
            <p class="highlight"><?php echo $statistics['total_job_postings']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Applications</h2>
            <p class="highlight"><?php echo $statistics['total_applications']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Accepted Applications</h2>
            <p class="highlight"><?php echo $statistics['total_accepted_applications']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Rejected Applications</h2>
            <p class="highlight"><?php echo $statistics['total_rejected_applications']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Pending Applications</h2>
            <p class="highlight"><?php echo $statistics['total_pending_applications']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Total Active Employers</h2>
            <p class="highlight"><?php echo $statistics['total_active_employers']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Avg Applications per Job</h2>
            <p class="highlight"><?php echo $statistics['avg_applications_per_job']; ?></p>
        </div>

        <div class="stat-box">
            <h2>Highest Salary Job</h2>
            <p class="highlight"><?php echo $statistics['highest_salary_job']; ?></p>
        </div>

    </section>

    <footer>
        <!-- Add footer content if needed -->
    </footer>

</body>
</html>
