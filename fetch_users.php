<?php
include 'db_connect.php'; // Ensure database connection is correct

if (isset($_GET['roomId'])) {
    $roomId = $_GET['roomId'];

    // Fetch users who joined the specific room
    $stmt = $conn->prepare("SELECT username, email, joined_at FROM join_form WHERE room_id = ?");
    $stmt->bind_param("s", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h4>Users in Room: " . htmlspecialchars($roomId) . "</h4>";
    echo "<table class='table table-dark table-bordered'>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Joined At</th>
                </tr>
            </thead>
            <tbody>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['username']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['joined_at']) . "</td>
              </tr>";
    }

    echo "</tbody></table>";

    $stmt->close();
}
?>
