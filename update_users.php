<?php
session_start();
include "db.php"; // Your database connection file

if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
    $username = $_POST['username'];

    // Add user to active users table
    $query = "INSERT INTO active_users (username) VALUES ('$username') ON DUPLICATE KEY UPDATE last_active=NOW()";
    mysqli_query($conn, $query);
}
?>
