<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="jprofilecss.css">
    <title>Job Seeker Profile</title>
    <style>
        /* Add your additional styles here */
        /* ... (your styles) ... */
        .profile-form {
            margin-top: 20px;
        }

        .profile-form label {
            display: block;
            margin-bottom: 8px;
        }

        .profile-form input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
        }

        .profile-form button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
// profile.php

// Include the database connection file
include_once "db_connection.php";

// Check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information from the database
$user_id = $_SESSION['user_id'];
$userInfo = getUserInfo($user_id);
$profileInfo = getProfileInfo($user_id);

// Function to get user information
function getUserInfo($user_id) {
    global $pdo; // Assuming $pdo is your database connection

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    return $userInfo;
}

// Function to get profile information
function getProfileInfo($user_id) {
    global $pdo; // Assuming $pdo is your database connection

    $stmt = $pdo->prepare("SELECT * FROM jprofile WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    // Check if fetch was successful
    $profileInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$profileInfo) {
        return [];
    }

    return $profileInfo;
}

// Update profile information if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $age = $_POST["age"];
    $qualification = $_POST["qualification"];
    $experience = $_POST["experience"];
    $skills = $_POST["skills"];

    // Update or insert data based on whether the profile exists
    if ($profileInfo) {
        $stmtUpdate = $pdo->prepare("UPDATE jprofile SET name=?, age=?, qualification=?, experience=?, skills=? WHERE user_id=?");
        $stmtUpdate->execute([$name, $age, $qualification, $experience, $skills, $user_id]);
    } else {
        $stmtInsert = $pdo->prepare("INSERT INTO jprofile (user_id, name, age, qualification, experience, skills) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtInsert->execute([$user_id, $name, $age, $qualification, $experience, $skills]);
    }

    // Refresh the page to display updated information
    header("Location: jprofile.php");
    exit();
}
?>

<header>
    <div class="logo">Your Logo</div>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="ajobs.php">Apply Jobs</a></li>
            <li><a href="jobmatch.php">My Applications</a></li>
            <li><a href="jprofile.php">My Profile</a></li>
            <li><a href="login.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="profile-info">
    <h2>Job Seeker Profile</h2>

    <?php if ($userInfo): ?>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Age</th>
                <th>Qualification</th>
                <th>Experience</th>
                <th>Skills</th>
                <!-- Add more fields as needed -->
            </tr>
            <tr>
                <td><?php echo $userInfo['user_id']; ?></td>
                <td><?php echo $userInfo['username']; ?></td>
                <td><?php echo $userInfo['email']; ?></td>
                <td><?php echo $profileInfo['name'] ?? ''; ?></td>
                <td><?php echo $profileInfo['age'] ?? ''; ?></td>
                <td><?php echo $profileInfo['qualification'] ?? ''; ?></td>
                <td><?php echo $profileInfo['experience'] ?? ''; ?></td>
                <td><?php echo $profileInfo['skills'] ?? ''; ?></td>
                <!-- Display more fields as needed -->
            </tr>
        </table>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>
</section>

<section class="profile-form">
    <h2>Edit Profile</h2>
    <form action="jprofile.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $profileInfo['name'] ?? ''; ?>" required>

        <label for="age">Age:</label>
        <input type="text" id="age" name="age" value="<?php echo $profileInfo['age'] ?? ''; ?>" required>

        <label for="qualification">Qualification:</label>
        <input type="text" id="qualification" name="qualification" value="<?php echo $profileInfo['qualification'] ?? ''; ?>" required>

        <label for="experience">Experience:</label>
        <input type="text" id="experience" name="experience" value="<?php echo $profileInfo['experience'] ?? ''; ?>" required>

        <label for="skills">Skills:</label>
        <input type="text" id="skills" name="skills" value="<?php echo $profileInfo['skills'] ?? ''; ?>" required>

        <button type="submit">Save Changes</button>
    </form>
</section>



</body>
</html>
