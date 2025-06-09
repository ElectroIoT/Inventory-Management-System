<?php
$servername = "localhost";
$username = "inventory";  // Default username for MySQL
$password = "Master@2773";      // Default password is empty for XAMPP
$dbname = "inventory";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
