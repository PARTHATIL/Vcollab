<?php
session_start();
include 'db_connect.php'; // Ensure database connection is correct

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_id'], $_POST['username'], $_POST['email'])) {
    $roomId = trim($_POST['room_id']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Check if the room exists in the database
    $stmt = $conn->prepare("SELECT room_id FROM rooms WHERE room_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $roomId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();

            // Check if the user has already joined this room
            $checkUserStmt = $conn->prepare("SELECT id FROM join_form WHERE email = ? AND room_id = ?");
            $checkUserStmt->bind_param("ss", $email, $roomId);
            $checkUserStmt->execute();
            $checkUserStmt->store_result();

            if ($checkUserStmt->num_rows == 0) {
                // Insert user into join_form table
                $insertStmt = $conn->prepare("INSERT INTO join_form (username, email, room_id) VALUES (?, ?, ?)");
                if ($insertStmt) {
                    $insertStmt->bind_param("sss", $username, $email, $roomId);
                    $insertStmt->execute();
                    $insertStmt->close();
                }
            }
            $checkUserStmt->close();

            // Store user session details
            $_SESSION['roomId'] = $roomId;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['userType'] = "joiner"; // Mark as a joiner

            header("Location: room.php?roomId=" . urlencode($roomId));
            exit();
        } else {
            $error = "❌ Invalid Room ID! Please try again.";
        }
        $stmt->close();
    } else {
        $error = "⚠️ Database error: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Room</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card bg-black shadow-sm p-4 rounded">
                <div class="card-body text-center">
                    <h4 class="card-title text-light">Join an Existing Room</h4>

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php } ?>

                    <form method="POST">
                        <input type="text" name="username" class="form-control text-center mb-2" placeholder="Enter Your Name" required>
                        <input type="email" name="email" class="form-control text-center mb-2" placeholder="Enter Your Email" required>
                        <input type="text" name="room_id" class="form-control text-center mb-2" placeholder="Enter Room ID" required>
                        <button type="submit" name="join" class="btn btn-primary w-100 mt-3">Join Room</button>
                    </form>

                    <p class="mt-3">Don't have a room? <a href="create_room.php" class="text-success">Create Here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
