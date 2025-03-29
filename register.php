<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Vcollab"; // Change this to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // File Upload Handling
    $target_dir = "uploads/profile_pics/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["file"]["name"]);
    $unique_name = uniqid() . "_" . $file_name; // Unique file name to prevent conflicts
    $target_file = $target_dir . $unique_name;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ["jpg", "png"];

    if ($_FILES["file"]["size"] > 5000000) {
        $message = "File too large!";
    } elseif (!in_array($fileType, $allowed_types)) {
        $message = "Only JPG, PNG allowed!";
    } else {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Insert into Database
            $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password, profile_img) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $phone, $password, $target_file);
            
            if ($stmt->execute()) {
                $message = "Registration successful!";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error uploading file.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="WhatsApp Image 2025-03-27 at 14.45.13_5f59e3bc.jpg" type="image/x-icon">
    <style>
        /* Dark Theme Background */
        body {
            background-color: #121212; /* Dark background */
            color: #ffffff; /* Light text color */
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Centering the Card */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Card Styling */
        .card {
            background: #1e1e1e; /* Darker card background */
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1); /* Soft shadow */
            padding: 25px;
            max-width: 400px;
            width: 100%;
        }

        /* Headings */
        h3 {
            font-weight: 600;
            color: #ffffff;
        }

        /* Form Inputs */
        .form-control {
            border-radius: 8px;
            padding: 10px;
            font-size: 16px;
            background: #2a2a2a;
            color: #ffffff;
            border: 1px solid #444;
        }

        .form-control:focus {
            border-color: #00c853; /* Green glow */
            box-shadow: 0 0 5px rgba(0, 200, 83, 0.8);
            background: #333;
        }

        /* File Input */
        input[type="file"] {
            padding: 10px;
            border-radius: 5px;
            background: #2a2a2a;
            color: #ffffff;
        }

        /* Button */
        .btn-primary {
            background-color: #00c853; /* Green theme */
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
            transition: 0.3s;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #009624;
        }

        /* Login Link */
        p {
            margin-top: 15px;
            font-size: 14px;
            color: #cccccc;
        }

        p a {
            text-decoration: none;
            color: #00c853;
            font-weight: 600;
        }

        p a:hover {
            text-decoration: underline;
        }
      
        
    </style>
</head>
<body>
    <div class="container mb-6">
        <div class="card shadow-lg p-4">
            <img src="WhatsApp Image 2025-03-27 at 14.45.13_5f59e3bc.jpg" alt="Logo" class="img-fluid mx-auto d-block" style="max-width: 150px;">
            <h3 class="text-center mb-4">Register</h3>

            <?php if (!empty($message)): ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username" class="form-label" style="color: #ffffff;">Username</label>
                    <input type="text" class="form-control" name="username" id="username" style="color: #ffffff;" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label"style="color: #ffffff;">Email</label>
                    <input type="email" class="form-control" name="email" id="email" style="color: #ffffff;" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label"style="color: #ffffff;">Phone Number</label>
                    <input type="tel" class="form-control" name="phone" id="phone" style="color: #ffffff;" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label" style="color:rgb(255, 254, 254);">Password</label>
                    <input type="password" class="form-control" name="password" id="password" style="color: #ffffff;" required>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label" style="color: #ffffff;">Upload File</label>
                    <input type="file" class="form-control" name="file" id="file" style="color: #ffffff;" required >
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>

            <p class="text-center mt-3">Have an account? <a href="index.php">Login</a></p>
        </div>
    </div>
</body>
</html>
