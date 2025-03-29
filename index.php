<?php
session_start(); // Start session
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Vcollab";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id; // Store user ID in session
        header("Location: home.php"); // Redirect to home
        exit();
    } else {
        $message = "Invalid email or password!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            background-color: #f4f4f9; 
            font-family: 'Arial', sans-serif;
        }
        /* Centering the Card */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        /* Card Styling */
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            background: white;
            padding: 25px;
            width: 100%;
            max-width: 400px;
        }
        /* Headings */
        h3 {
            font-weight: 600;
            color: #333;
        }
        /* Form Inputs */
        .form-control {
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        /* Button */
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        /* Login Link */
        p {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }
        p a {
            text-decoration: none;
            color: #007bff;
            font-weight: 600;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card col-md-6 mx-auto">
            <img src="WhatsApp Image 2025-03-27 at 14.45.13_5f59e3bc.jpg" alt="Logo" class="img-fluid mx-auto d-block" style="max-width: 150px;">
            <h3 class="text-center">Login</h3>

            <?php if (!empty($message)): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Create Account</a></p>
            </form>
        </div>
    </div>
</body>
</html>
