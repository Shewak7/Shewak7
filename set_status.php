<?php
session_start();

// Check if the user is logged in and retrieve user_id
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
include_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get application ID and status from the form
    $applicationId = $_POST["application_id"];
    $status = $_POST["status"];

    // Update the application status in the database
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
    $stmt->execute([$status, $applicationId]);
}

// Redirect back to the view_applications.php page
header("Location: app.php");
exit();
?>
