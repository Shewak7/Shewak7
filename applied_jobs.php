<?php
// Start the session
session_start();

// Check if the user is logged in and retrieve user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include_once "db_connection.php";

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
