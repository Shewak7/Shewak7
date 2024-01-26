<?php
session_start();

// Check if the user is logged in and retrieve user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Include your database connection file
include_once "db_connection.php";

// Fetch user details from the database based on the user ID
$userId = $_SESSION['user_id'];
$userData = getUserData($userId); // Assume you have a function to fetch user data

// Fetch additional information about the employer from the eprofile table
$eprofileData = getEProfileData($userId);

// Function to fetch user data
function getUserData($userId) {
    global $pdo; // Assuming $pdo is your database connection

    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    return $userData;
}

// Function to fetch eprofile data
function getEProfileData($userId) {
    global $pdo; // Assuming $pdo is your database connection

    $stmt = $pdo->prepare("SELECT * FROM eprofile WHERE user_id = ?");
    $stmt->execute([$userId]);
    $eprofileData = $stmt->fetch(PDO::FETCH_ASSOC);

    return $eprofileData;
}

// Process form submission to update eprofile
$updateMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyName = $_POST["company_name"];
    $industry = $_POST["industry"];
    $aboutCompany = $_POST["about_company"];

    // Update or insert into eprofile table
    try {
        $stmt = $pdo->prepare("INSERT INTO eprofile (user_id, company_name, industry, about_company) VALUES (?, ?, ?, ?) ON CONFLICT (user_id) DO UPDATE SET company_name = EXCLUDED.company_name, industry = EXCLUDED.industry, about_company = EXCLUDED.about_company");
        $stmt->execute([$userId, $companyName, $industry, $aboutCompany]);
        $updateMessage = 'Profile updated successfully!';
    } catch (PDOException $e) {
        $updateMessage = 'Error updating profile: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Profile - CareerCraftHub</title>
    <!-- Include your external CSS file -->
    <link rel="stylesheet" href="styles.css">

    <!-- Internal CSS styles -->
    <style>
        /* Add your additional styles here */
        .profile-container form {
            margin-top: 20px;
        }

        .profile-container label {
            display: block;
            margin-bottom: 8px;
        }

        .profile-container input,
        .profile-container textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        .update-message {
            margin-top: 10px;
            color: green;
        }

        .error-message {
            margin-top: 10px;
            color: red;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }

        header {
            background: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }
        nav ul {
        list-style: none;
        padding: 1 px;
        margin: 1px;
        }

        nav ul li {
        display: inline;
        margin-right: 20px;
        }
        nav ul li a {
        text-decoration: underline;
        color: aqua;
        font-weight: bold;
        transition: color 0.3s ease-in-out; 
        }  

        nav ul li a:hover {
        color: orchid;
        }

        section {
            padding: 20px;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav li {
            display: inline-block;
            margin-right: 20px;
        }

        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            color: #333;
        }

        .profile-container p {
            margin: 10px 0;
        }

        footer {
            background: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <h1>Welcome, <?php echo $userData['username']; ?></h1>
        <nav>
            <ul>
                <li><a href="jpe.php">Home</a></li>
                <li><a href="login.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="profile-container">
        <h2>Employer Profile</h2>
        <p><strong>Username:</strong> <?php echo $userData['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $userData['email']; ?></p>

        <!-- Display all elements from eprofile table -->
        <h3>Additional Information</h3>
        <?php if ($eprofileData): ?>
            <p><strong>Company Name:</strong> <?php echo $eprofileData['company_name']; ?></p>
            <p><strong>Industry:</strong> <?php echo $eprofileData['industry']; ?></p>
            <p><strong>About Company:</strong> <?php echo $eprofileData['about_company']; ?></p>
        <?php else: ?>
            <p>No additional information found. Add your information below:</p>
        <?php endif; ?>

        <!-- Form for additional employer information -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" value="<?php echo isset($eprofileData['company_name']) ? $eprofileData['company_name'] : ''; ?>" required>

            <label for="industry">Industry:</label>
            <input type="text" id="industry" name="industry" value="<?php echo isset($eprofileData['industry']) ? $eprofileData['industry'] : ''; ?>" required>

            <label for="about_company">About Company:</label>
            <textarea id="about_company" name="about_company" rows="4"><?php echo isset($eprofileData['about_company']) ? $eprofileData['about_company'] : ''; ?></textarea>

            <button type="submit">Update Profile</button>
        </form>


        <?php if ($updateMessage): ?>
            <?php if (strpos($updateMessage, 'Error') !== false): ?>
                <p class="error-message"><?php echo $updateMessage; ?></p>
            <?php else: ?>
                <p class="update-message"><?php echo $updateMessage; ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

   

</body>
</html>
