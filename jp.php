<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="jp.css">
    <title>Job Recruitment System</title>
    <?php
session_start();

// Check if the user is logged in and retrieve user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Use $user_id for further data retrieval or processing
?>

</head>
<body>
    <header>
        <div class="logo">Your Logo</div>
        <nav>
            <ul>
                <li><a href="jp.php">Home</a></li>
                <li><a href="apply-job.php">Apply Jobs</a></li>
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <h1>Welcome to Our Job Recruitment System</h1>
        <p>Find your dream job or hire the perfect candidate with us.</p>
        <a href="#" class="cta-button">Explore Jobs</a>
    </section>

    <section class="search-bar">
        <input type="text" placeholder="Search for jobs...">
        <button type="button">Search</button>
    </section>

    <section class="featured-jobs">
        <h2>Featured Jobs</h2>
        <!-- Featured job listings with images and details -->
        <div class="job-listing">
            <img src="job-image.jpg" alt="Job Image">
            <h3>Job Title</h3>
            <p>Company Name</p>
        </div>
    </section>

    <section class="how-it-works">
        <h2>How It Works</h2>
        <div class="step">
            <h3>Search for Jobs</h3>
            <p>Find relevant job opportunities using our advanced search.</p>
        </div>
        <div class="step">
            <h3>Apply for Jobs</h3>
            <p>Submit your applications directly through our platform.</p>
        </div>
        <div class="step">
            <h3>Post a Job Opening</h3>
            <p>Employers can easily post job openings and find the right candidates.</p>
        </div>
    </section>

    <section class="statistics">
        <h2>Key Statistics</h2>
        <!-- Statistics or infographics -->
    
        <div class="statistic">
            <h3>Registered Employers</h3>
            <p>1000+</p>
        </div>

    </section>

    <section class="latest-job-listings">
        <h2>Latest Job Listings</h2>
        <!-- Latest job listings with titles, companies, and locations -->
    
        <div class="job-listing">
            <h3>Job Title</h3>
            <p>Company Name - Location</p>
        </div>
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
            <a href="#" target="_blank" rel="noopener noreferrer">Facebook</a>
            <a href="#" target="_blank" rel="noopener noreferrer">Twitter</a>
        </div>
    </footer>
</body>
</html>
