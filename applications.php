<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
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
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        section {
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .job-applications {
            margin-bottom: 20px;
        }

        .application-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .application-item h3 {
            margin-top: 0;
        }

        .application-item p {
            margin: 5px 0;
        }

        .status-form {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }

        .status-form label {
            margin-right: 10px;
        }

        .status-form select {
            padding: 8px;
            font-size: 14px;
        }

        .status-form button {
            padding: 8px 15px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .success-message {
            color: #4caf50;
            margin-top: 10px;
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-top: 20px;
        }
    </style>
    <title>View Job Applications</title>
</head>
<body>

<div class="container">

    <header>
        <div class="logo">Your Logo</div>
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

                        <!-- Success message after update -->
                        <?php if (isset($_SESSION['update_success'])): ?>
                            <p class="success-message"><?php echo $_SESSION['update_success']; ?></p>
                            <?php unset($_SESSION['update_success']); ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>

    <footer>
        Your Footer
    </footer>

</div>

</body>
</html>
