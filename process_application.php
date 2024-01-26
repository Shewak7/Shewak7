<?php
session_start();

// Check if the user is logged in and retrieve user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $applicationId = $_POST["application_id"];
    $action = $_POST["action"];

    // Validate the action
    if (!in_array($action, ['accept', 'reject'])) {
        // Invalid action, handle the error (e.g., redirect to an error page)
        header("Location: error.php");
        exit();
    }

    // Update the application status in the database
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
    $stmt->execute([$action, $applicationId]);

    // Redirect back to the view applications page
    header("Location: view-applications.php");
    exit();
} else {
    // If the form was not submitted via POST, redirect to an error page
    header("Location: error.php");
    exit();
}
?>
