<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Job Recruitment System</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: url('your-background-image.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .registration-container {
        background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background for the registration box */
        max-width: 400px;
        margin: 100px auto; /* Adjust the margin as needed */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Box shadow for a slight visual lift */
    }

    .registration-container h2 {
        color: #333;
        margin-bottom: 20px;
    }

    .registration-container form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .registration-container label {
        margin-bottom: 10px;
        color: #333;
        display: block;
    }

    .registration-container input,
    .registration-container select {
        padding: 10px;
        margin-bottom: 20px;
        width: 100%;
        box-sizing: border-box;
    }

    .registration-container button {
        padding: 10px 20px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .registration-container p {
        margin-top: 20px;
        color: #333;
    }

    .registration-container a {
        color: #4caf50;
        text-decoration: none;
    }

    .registration-container a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Create an Account</h2>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirmPassword"];
            $email = $_POST["email"];
            $userType = $_POST["userType"];

            // Validate input (you may want to add more validation)
            if ($password !== $confirmPassword) {
                echo '<p style="color: red;">Password and confirm password do not match.</p>';
            } else {
                try {
                    // Perform registration logic here (e.g., insert data into a PostgreSQL database)
                    // Make sure to hash the password before storing it
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Database insert query goes here using PDO
                    $host = "localhost";
                    $db = "jp";
                    $user = "postgres";
                    $password = '1234' ;

                    $pdo = new PDO("pgsql:host=$host;port='5434';dbname=$db", $user, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Insert user data into the 'users' table
                    $sql = "INSERT INTO users (username, password_hash, email, user_type) VALUES (?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$username, $hashedPassword, $email, $userType]);

                    echo '<p style="color: green;">Registration successful. You can now <a href="login.php">login</a>.</p>';
                } catch (PDOException $e) {
                    // Check if the exception message contains the trigger error
                    if (strpos($e->getMessage(), 'Email address must be unique across users, eprofile, and jprofile tables.') !== false) {
                        echo '<p style="color: red;">Email address must be unique across users, eprofile, and jprofile tables.</p>';
                    } else if (strpos($e->getMessage(), 'duplicate key value violates unique constraint "users_email_key"') !== false) {
                        echo '<p style="color: red;">Error: Email address is already in use. Please choose a different email.</p>';
                    } else {
                        // If it's another exception, display a generic error
                        echo '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
                    }
                }

                // Close the connection
                $pdo = null;
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="userType">Select User Type:</label>
            <select id="userType" name="userType" required>
                <option value="employer">Employer</option>
                <option value="jobseeker">Job Seeker</option>
            </select>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
