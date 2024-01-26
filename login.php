<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CareerCraftHub-Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('img/bg1.avif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background for the login box */
            max-width: 400px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Box shadow for a slight visual lift */
        }

        .login-container h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container label {
            margin-bottom: 10px;
            color: #333;
            display: block;
        }

        .login-container input,
        .login-container select {
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .login-container button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container p {
            margin-top: 20px;
            color: #333;
            text-align: center;
        }

        .login-container a {
            color: #4caf50;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to Job Recruitment System</h2>

        <?php
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $userType = $_POST["userType"];

            $host = "localhost";
            $db = "jp";
            $user = "postgres";
            $password = '1234' ;

            try {
                $pdo = new PDO("pgsql:host=$host;port='5434';dbname=$db", $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Retrieve user data from the 'users' table including user_id
                $sql = "SELECT user_id, username, password_hash, user_type FROM users WHERE username = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$username]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row && password_verify($password, $row['password_hash']) && $userType === $row['user_type']) {
                    // Login successful
                    // Store user_id in the session
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['username'] = $row['username'];
                    
                    echo '<p style="color: green;">Login successful. Redirecting...</p>';
                    if ($userType === 'employer') {
                        header("Refresh: 2; URL=jpe.php");
                    } else {
                        header("Refresh: 2; URL=jpj.php");
                    }
                    exit();
                } else {
                    // Login failed
                    echo '<p style="color: red;">Invalid username, password, or user type.</p>';
                }
            } catch (PDOException $e) {
                echo '<p style="color: red;">Error: ' . $e->getMessage() . '</p>';
            }

            $pdo = null;
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="userType">Select User Type:</label>
            <select id="userType" name="userType" required>
                <option value="employer">Employer</option>
                <option value="jobseeker">Job Seeker</option>
            </select>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>
</body>
</html>
