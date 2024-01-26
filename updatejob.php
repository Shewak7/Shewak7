<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Update Job</title>
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

.logo {
    font-size: 24px;
    font-weight: bold;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

nav ul li {
    display: inline-block;
    margin-right: 20px;
}

nav ul li a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
}

section.update-job-form {
    max-width: 600px;
    margin: 20px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

form {
    display: grid;
    gap: 15px;
}

label {
    font-weight: bold;
}

input, textarea {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background-color: #4caf50;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

/* Add your additional styles here */
/* ... (your additional styles) ... */

footer {
    background-color: #333;
    color: #fff;
    padding: 10px;
    text-align: center;
}

.contact-info {
    margin-top: 20px;
}

    </style>
</head>
<body>

<?php
// updatejob.php

// Include the database connection file
include_once "db_connection.php";

// Check if the job_id is provided in the URL
if (!isset($_GET['job_id'])) {
    // Redirect to a relevant page if job_id is not provided
    header("Location: my-jobs.php");
    exit();
}

// Get the job_id from the URL
$job_id = $_GET['job_id'];

// Fetch job details based on the job_id
$jobDetails = getJobDetails($job_id);

// Function to get job details
function getJobDetails($job_id) {
    global $pdo; // Assuming $pdo is your database connection

    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = ?");
    $stmt->execute([$job_id]);
    $jobDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    return $jobDetails;
}

// Check if the job details are retrieved
if (!$jobDetails) {
    // Redirect to a relevant page if job details are not found
    header("Location: my-jobs.php");
    exit();
}

// Handle the form submission for updating job details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated job details from the form
    $company_name = $_POST["company_name"];
    $job_title = $_POST["job_title"];
    $job_description = $_POST["job_description"];
    $job_requirements = $_POST["job_requirements"];
    $job_position = $_POST["job_position"];
    $salary = $_POST["salary"];
    // Add more fields as needed

    // Update the job details in the database
    $stmt = $pdo->prepare("UPDATE jobs SET company_name = ?, job_title = ?, job_description = ?, job_requirements = ?, job_position = ?, salary = ? WHERE job_id = ?");
    $stmt->execute([$company_name, $job_title, $job_description, $job_requirements, $job_position, $salary, $job_id]);

    // Redirect back to the my-jobs.php page or any other page
    header("Location: ejobs.php");
    exit();
}
?>

<header>
    <div class="logo">Your Logo</div>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="post-job.php">Post Jobs</a></li>
            <li><a href="ejobs.php">My Jobs</a></li>
            <li><a href="eprofile.php">My Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="update-job-form">
    <h2>Update Job Details</h2>

    <form action="updatejob.php?job_id=<?php echo $job_id; ?>" method="post">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo $jobDetails['company_name']; ?>" required>

        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" value="<?php echo $jobDetails['job_title']; ?>" required>

        <label for="job_description">Job Description:</label>
        <textarea id="job_description" name="job_description" required><?php echo $jobDetails['job_description']; ?></textarea>

        <label for="job_requirements">Job Requirements:</label>
        <textarea id="job_requirements" name="job_requirements" required><?php echo $jobDetails['job_requirements']; ?></textarea>

        <label for="job_position">Job Position:</label>
        <input type="text" id="job_position" name="job_position" value="<?php echo $jobDetails['job_position']; ?>" required>

        <label for="salary">Salary:</label>
        <input type="text" id="salary" name="salary" value="<?php echo $jobDetails['salary']; ?>" required>

        <!-- Add more input fields for other job details as needed -->

        <button type="submit">Update Job</button>
    </form>
</section>


</body>
</html>
