<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Jobs</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
        }

        section {
            padding: 80px 20px;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        input[type="hidden"] {
            display: none;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<section>
    <h2>Application Form</h2>

    <?php
    session_start();
    include_once "db_connection.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $jobId = $_POST['job_id'];

            // Check if the user has already applied for this job
            try {
                $checkStmt = $pdo->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
                $checkStmt->execute([$userId, $jobId]);

                if ($checkStmt->rowCount() > 0) {
                    // Display a message to the user that they have already applied for this job
                    echo "<p>You have already applied for this job.</p>";
                } else {
                    // Insert the application into the applications table
                    try {
                        $insertStmt = $pdo->prepare("INSERT INTO applications (user_id, job_id) VALUES (?, ?)");
                        $insertStmt->execute([$userId, $jobId]);

                        header("Location: apply-success.php");
                        exit();
                    } catch (PDOException $e) {
                        echo "Error applying for the job: " . $e->getMessage();
                    }
                }
            } catch (PDOException $e) {
                echo "Error checking duplicate applications: " . $e->getMessage();
            }
        }
    }
    ?>

    <!-- Application Form -->
    <form action="ajobs.php" method="post">
        <!-- You can include hidden fields or additional form elements if needed -->
        <input type="hidden" name="job_id" value="<?php echo $jobId; ?>">
        <button type="submit">Submit Application</button>
    </form>
</section>

</body>
</html>
