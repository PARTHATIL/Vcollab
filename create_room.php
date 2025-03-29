<?php
session_start();
include 'db_connect.php';

function generateRoomId() {
    return substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 6);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate'], $_POST['username'], $_POST['email'])) {
    $roomId = generateRoomId();
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Check if the room ID already exists (avoid duplicate room IDs)
    $stmt = $conn->prepare("SELECT room_id FROM rooms WHERE room_id = ?");
    if ($stmt) {
        $stmt->bind_param("s", $roomId);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->close();
            $error = "⚠️ Room ID already exists. Please try again.";
        } else {
            $stmt->close();
            
            // Insert into rooms table
            $insertRoomStmt = $conn->prepare("INSERT INTO rooms (room_id) VALUES (?)");
            if ($insertRoomStmt) {
                $insertRoomStmt->bind_param("s", $roomId);
                if ($insertRoomStmt->execute()) {
                    // Insert creator into join_form table (tracks who created the room)
                    $insertUserStmt = $conn->prepare("INSERT INTO join_form (username, email, room_id, role) VALUES (?, ?, ?, 'creator')");
                    if ($insertUserStmt) {
                        $insertUserStmt->bind_param("sss", $username, $email, $roomId);
                        $insertUserStmt->execute();
                        $insertUserStmt->close();
                    }

                    // Store user session details
                    $_SESSION['roomId'] = $roomId;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['userType'] = "creator"; // Mark as room creator

                    header("Location: room.php?roomId=" . urlencode($roomId));
                    exit();
                } else {
                    $error = "⚠️ Database Error: " . $insertRoomStmt->error;
                }
                $insertRoomStmt->close();
            } else {
                $error = "⚠️ Prepare Statement Error: " . $conn->error;
            }
        }
    } else {
        $error = "⚠️ Database Connection Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Room</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark text-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-6">
            <div class="card bg-black shadow-sm p-4 rounded">
                <div class="card-body text-center">
                    <h4 class="card-title text-light">Create a New Room</h4>

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php } ?>

                    <form method="POST">
                        <input type="text" name="username" class="form-control mb-2 text-center" placeholder="Enter Your Name" required>
                        <input type="email" name="email" class="form-control mb-2 text-center" placeholder="Enter Your Email" required>
                        <button type="submit" name="generate" class="btn btn-primary w-100 mt-3">Generate Room ID</button>
                    </form>

                    <p class="mt-3">Already have a room? <a href="join_room.php" class="text-success">Join Here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


