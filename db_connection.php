<?php
$host = "localhost";
$db = "jp";
$user = "postgres";
$password = '1234' ;

try {
    $pdo = new PDO("pgsql:host=$host;port='5434';dbname=$db", $user, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
