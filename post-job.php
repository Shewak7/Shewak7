<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - Job Recruitment System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset some default styles */
        body, h1, h2, h3, p, input, textarea, button {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
        }

        /* Header styles */
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin-bottom: 10px;
        }

        /* Form container styles */
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Form input styles */
        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
        }

        /* Button styles */
        button {
            background-color: #4caf50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Message container styles */
        .message-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .message-container p {
            margin: 10px 0;
            color: green;
        }

        .message-container p.error-message {
            color: red;
        }
    </style>
</head>
<body>

    <header>
        <h1>Post a Job</h1>
    </header>

    <?php
    session_start();

    // Check if the user is logged in and retrieve user_id
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Include your database connection file
    include_once "db_connection.php";

    // Process form submission to post a job
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userId = $_SESSION['user_id'];
        $companyName = $_POST["company_name"];
        $jobTitle = $_POST["job_title"];
        $jobDescription = $_POST["job_description"];
        $jobRequirements = $_POST["job_requirements"];
        $jobPosition = $_POST["job_position"];
        $salary = $_POST["salary"];
        $otherDetails = $_POST["other_details"];

        try {
            // Insert job details into the jobs table
            $stmt = $pdo->prepare("INSERT INTO jobs (user_id, company_name, job_title, job_description, job_requirements, job_position, salary, other_details) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $companyName, $jobTitle, $jobDescription, $jobRequirements, $jobPosition, $salary, $otherDetails]);
            $postMessage = 'Job posted successfully!';
        } catch (PDOException $e) {
            // Check if the exception message contains the trigger errors
            if (strpos($e->getMessage(), 'Job title cannot exceed 20 characters.') !== false) {
                $postMessage = 'Error: Job title cannot exceed 20 characters.';
            } elseif (strpos($e->getMessage(), 'Invalid salary format. Use format like "USD 50000 per year".') !== false) {
                $postMessage = 'Error: Invalid salary format. Use format like "USD 50000 per year".';
            } else {
                $postMessage = 'Error posting job: ' . $e->getMessage();
            }
        }
    }
    ?>

    <?php if (isset($postMessage)): ?>
        <div class="message-container">
            <p class="<?php echo strpos($postMessage, 'Error') !== false ? 'error-message' : ''; ?>">
                <?php echo $postMessage; ?>
            </p>
        </div>
    <?php else: ?>
        <div class="form-container">
            <h2>Job Details</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" required>

                <label for="job_title">Job Title:</label>
                <input type="text" id="job_title" name="job_title" required>

                <label for="job_description">Job Description:</label>
                <textarea id="job_description" name="job_description" rows="4" required></textarea>

                <label for="job_requirements">Job Requirements:</label>
                <textarea id="job_requirements" name="job_requirements" rows="4" required></textarea>

                <label for="job_position">Job Position:</label>
                <input type="text" id="job_position" name="job_position" required>

                <label for="salary">Salary:</label>
                <input type="text" id="salary" name="salary" required>

                <label for="other_details">Other Details:</label>
                <textarea id="other_details" name="other_details" rows="4"></textarea>

                <button type="submit">Post Job</button>
            </form>
        </div>
    <?php endif; ?>

    <footer>
        <!-- Add footer content if needed -->
    </footer>

</body>
</html>
