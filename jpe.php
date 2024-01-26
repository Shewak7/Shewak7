<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerCraftHub | Home</title>
    <style>
        
        body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: burlywood;
}

header {
    background-color: skyblue;
    color: #fff;
    padding: 10px 0;
    text-align: center;
}

header .logo {
    font-size: 10px;
    font-weight: bold;
    display: flex;
    align-items: center;
}

nav {
    margin-top: 10px;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    text-align: center;
}

nav ul li {
    display: inline;
    margin-right: 20px;
}

nav ul li a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    font-size: 16px;
}

nav ul li a:hover {
    color: #ffcc00;
}

section.hero {
    background-color: #fff;
    text-align: center;
    padding: 80px 0;
}

section.hero h1 {
    font-size: 36px;
    color: green;
}

section.hero p {
    font-size: 18px;
    color: black;
    margin-top: 20px;
}

section.search-bar {
    background-color: goldenrod;
    padding: 20px 0;
    text-align: center;
}

section.search-bar input[type="text"] {
    padding: 10px;
    font-size: 16px;
}

section.search-bar button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: blue;
    color: #fff;
    border: none;
    cursor: pointer;
}

section.latest-job-listings {
    padding: 40px 0;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

section.how-it-works {
    background-color: burlywood;
    padding: 40px 0;
    text-align: center;
}

section.how-it-works h2 {
    font-size: 24px;
    color: #333;
}

section.how-it-works .step {
    margin-top: 40px;
}

section.how-it-works h3 {
    font-size: 20px;
    color: #333;
}

section.how-it-works p {
    font-size: 16px;
    color: #666;
    margin-top: 10px;
}

section.statistics {
    background-color: #eee;
    padding: 40px 0;
    text-align: center;
}

section.statistics h2 {
    font-size: 24px;
    color: #333;
}

section.statistics .statistic {
    margin-top: 20px;
}

section.statistics h3 {
    font-size: 20px;
    color: #333;
}

section.statistics p {
    font-size: 16px;
    color: #666;
    margin-top: 10px;
}

footer {
    background-color: #333;
    color: #fff;
    padding: 20px 0;
    text-align: center;
}

footer .contact-info h3,
footer .quick-links h3 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #ffcc00;
}

footer .contact-info p,
footer .quick-links a {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    display: block;
    margin-bottom: 10px;
}

footer .quick-links a:hover {
    color: #ffcc00;
}

footer .social-media a {
    display: inline-block;
    margin: 0 10px;
    color: #fff;
    text-decoration: none;
    font-size: 24px;
}

footer .social-media a:hover {
    color: #ffcc00;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}
    </style>
</head>
<body>

<header>
    
    <nav>
        <ul>
            <li><a href="jpe.php">Home</a></li>
            <li><a href="post-job.php">Post Jobs</a></li>
            <li><a href="app.php">View Applications</a></li>
            <li><a href="ejobs.php">Posted Jobs</a></li>
            <li><a href="stats.php">Stats</a></li>
            <li><a href="eprofile.php">My Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="hero">
    <h1>Welcome to Our Job Recruitment System</h1>
    <p>Find your dream job or hire the perfect candidate with us.</p>
</section>

<section class="search-bar">
    <form action="" method="get">
        <input type="text" name="search" placeholder="Search for jobs..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>
</section>

<section class="latest-job-listings">
    <h2>Latest Job Listings</h2>
    <!-- Latest job listings with titles, companies, and locations -->
    <?php
    // Include the database connection file
    include_once "db_connection.php";

    // Define allowed columns for sorting
    $allowedColumns = ['job_id', 'user_id', 'company_name'];

    // Set the default column for sorting
    $sortColumn = 'job_id';

    // Check if the user selected a valid column for sorting
    if (isset($_GET['sort']) && in_array($_GET['sort'], $allowedColumns)) {
        $sortColumn = $_GET['sort'];
    }

    // Fetch job listings from the jobs table with sorting and searching
    $jobListings = getJobListings($sortColumn);

    // Function to fetch job listings with sorting and searching
    function getJobListings($sortColumn) {
        global $pdo; // Assuming $pdo is your database connection

        // Validate and sanitize the column name to prevent SQL injection
        $sortColumn = in_array($sortColumn, ['job_id', 'user_id', 'company_name']) ? $sortColumn : 'job_id';

        // Build the SQL query with sorting
        $sql = "SELECT * FROM jobs";

        // Check if there is a search query
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            // Add a WHERE clause to filter by job title
            $search = '%' . $_GET['search'] . '%';
            $sql .= " WHERE job_title LIKE :search";
        }

        // Add ORDER BY clause
        $sql .= " ORDER BY $sortColumn";

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($sql);

        // Bind parameters if there is a search query
        if (isset($search)) {
            $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        }

        $stmt->execute();

        $jobListings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $jobListings;
    }
    ?>

    <?php if (empty($jobListings)): ?>
        <p>No job listings available.</p>
    <?php else: ?>
        <table>
            <thead>
            <tr>
                <!-- Add sorting links for each column -->
                <th><a href="?sort=job_id">Job ID</a></th>
                <th><a href="?sort=user_id">User ID</a></th>
                <th><a href="?sort=company_name">Company Name</a></th>
                <!-- Add similar links for other columns -->
                <!-- ... -->
                <th>Job Title</th>
                <th>Job Description</th>
                <th>Job Requirements</th>
                <th>Job Position</th>
                <th>Salary</th>
                <th>Other Details</th>
                <th>Created At</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($jobListings as $job): ?>
                <tr>
                    <td><?php echo $job['job_id']; ?></td>
                    <td><?php echo $job['user_id']; ?></td>
                    <td><?php echo $job['company_name']; ?></td>
                    <td><?php echo $job['job_title']; ?></td>
                    <td><?php echo $job['job_description']; ?></td>
                    <td><?php echo $job['job_requirements']; ?></td>
                    <td><?php echo $job['job_position']; ?></td>
                    <td><?php echo $job['salary']; ?></td>
                    <td><?php echo $job['other_details']; ?></td>
                    <td><?php echo $job['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<!-- ... (remaining code) ... -->
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

<!-- Key Statistics Section Styles -->
<section class="statistics">
    <h2>Key Statistics</h2>
    <?php
    // Include the database connection file
    include_once "db_connection.php";

    // Fetch the count of distinct users
    $userCount = getUserCount();

    // Function to get the count of distinct users
    function getUserCount() {
        global $pdo; // Assuming $pdo is your database connection

        $stmt = $pdo->query("SELECT COUNT(DISTINCT user_id) AS user_count FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['user_count'];
    }
    ?>
    <div class="statistic">
        <h3>Registered Users</h3>
        <p><?php echo $userCount; ?></p>
    </div>
</section>



</body>
</html>
