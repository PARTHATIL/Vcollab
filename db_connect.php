<?php
$servername = "localhost";
$username = "root";  // Change if needed
$password = "";      // Change if needed
$dbname = "Vcollab"; // Make sure this matches your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
