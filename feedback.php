<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="common.css">
    <title>Feedback - Job Recruitment System</title>
</head>
<body>
    <?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert data into the feedback table
    $stmt = $pdo->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $message]);

    // Redirect to the home page or display a success message
    header("Location: home.html");
    exit();
}
?>

    <header>
        <div class="logo">Your Logo</div>
        <nav>
            <ul>
                <li><a href="jp.html">Home</a></li>
                <li><a href="apply-jobs.html">Apply Jobs</a></li>
                <li><a href="post-job.html">Post a Job</a></li>
                <li><a href="feedback.html">Feedback</a></li>
            </ul>
        </nav>
    </header>

    <section class="feedback-form">
        <h2>Feedback</h2>
        <form action="feedback-backend.php" method="post">
            <!-- Feedback form fields -->
            <label for="name">Your Name:</label>
            <input type="text" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" name="email" required>

            <label for="message">Your Feedback:</label>
            <textarea name="message" rows="4" required></textarea>

            <button type="submit">Submit Feedback</button>
        </form>
    </section>

    <footer>
        <div class="contact-info">
            <h3>Contact Us</h3>
            <p>Email: info@example.com</p>
            <p>Phone: (123) 456-7890</p>
        </div>
        <div class="quick-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">FAQs</a>
        </div>
        <div class="social-media">
            <!-- Add links to your social media profiles -->
            <!-- Example:
            <a href="#" target="_blank" rel="noopener noreferrer">Facebook</a>
            <a href="#" target="_blank" rel="noopener noreferrer">Twitter</a>
            -->
        </div>
    </footer>
</body>
</html>
