<?php
ob_start(); // Start output buffering

// Include the database connection file
include_once "db_connection.php";

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch jobs posted by the current user
$jobs = getMyJobs($user_id);

// Function to fetch jobs posted by the user
function getMyJobs($user_id) {
    global $pdo; // Assuming $pdo is your database connection

    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $jobs;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>My Jobs</title>
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

        section.jobs-list {
            max-width: 800px;
            margin: 20px auto;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        button.delete {
            background-color: #ff3333; /* Red color for delete button */
        }

        button:hover {
            background-color: #45a049;
        }

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

<header>
    <div class="logo">CareerCraftHub</div>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="post-job.php">Post Jobs</a></li>
            <li><a href="ejobs.php">My Jobs</a></li>
            <li><a href="eprofile.php">My Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="jobs-list">
    <h2>My Posted Jobs</h2>

    <?php if (empty($jobs)): ?>
        <p>You haven't posted any jobs yet.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Job ID</th>
                <th>Company Name</th>
                <th>Job Title</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?php echo $job['job_id']; ?></td>
                    <td><?php echo $job['company_name']; ?></td>
                    <td><?php echo $job['job_title']; ?></td>
                    <td>
                        <form action="updatejob.php" method="get" style="display: inline-block;">
                            <input type="hidden" name="job_id" value="<?php echo $job['job_id']; ?>">
                            <button type="submit">Update</button>
                        </form>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline-block;">
                            <input type="hidden" name="delete_job_id" value="<?php echo $job['job_id']; ?>">
                            <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this job?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>

<?php
// Process job deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_job_id"])) {
    $deleteJobId = $_POST["delete_job_id"];

    try {
        // Delete the job from the database
        $stmt = $pdo->prepare("DELETE FROM jobs WHERE job_id = ?");
        $stmt->execute([$deleteJobId]);
        header("Location: ejobs.php");
        exit();
    } catch (PDOException $e) {
        // Handle the exception (you can customize this part based on your needs)
        echo '<p style="color: red;">Error deleting job: ' . $e->getMessage() . '</p>';
    }
}
?>



</body>
</html>
<?php
ob_end_flush(); // Flush the output buffer and turn off output buffering
?>
