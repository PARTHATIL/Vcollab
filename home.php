<?php
session_start();
include 'db_connect.php'; // Include database connection

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data including profile image
$query = "SELECT username, email, file_path FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close(); // Close statement

// Define variables for output
$username = htmlspecialchars($user['username']); 
$email = htmlspecialchars($user['email']);
$file_path = !empty($user['file_path']) ? $user['file_path'] : 'default.png'; // Default image
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collaboration Platform</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: white;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #1e1e1e;
        }
        .logo img {
            height: 40px;
        }
        .nav-container {
            flex: 1;
            display: flex;
            justify-content: center;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 30px;
            padding: 0;
            margin: 0;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 15px;
        }
        .carousel {
            position: relative;
            max-width: 800px;
            margin: auto;
            overflow: hidden;
        }
        .carousel-images {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .carousel-images img {
            width: 100%;
            max-height: 400px;
            display: none;
        }
        .carousel-images img.active {
            display: block;
        }
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .prev { left: 10px; }
        .next { right: 10px; }
        .info-cards {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px;
        }
        .card {
            background-color: #1e1e1e;
            padding: 15px;
            border-radius: 5px;
            max-width: 250px;
            text-align: center;
        }
        .card img {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .features {
            text-align: left;
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }
        .buttons {
            margin: 20px;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .create-btn {
            background-color: #ed123a;
            color: white;
        }
        .join-btn {
            background-color: #ed123a;
            color: white;
        }
        footer {
            background-color: #1e1e1e;
            padding: 10px;
            margin-top: 20px;
        }
        .create-btn:hover, .join-btn:hover {
            background-color: #f0597a;
            transform: scale(1.05);
        }
        nav a:hover {
            background-color: #000000;
            border-radius: 5px;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.jpeg" alt="Logo">
        </div>
        <div class="nav-container">
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    
                    <li><a href="#">Files</a></li>
                    
                </ul>
            </nav>
        </div>
    </header>
    
    <section class="carousel">
        <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        <div class="carousel-images">
            <img src="WhatsApp Image 2025-03-28 at 10.13.00_dc6e78aa.jpg" alt="Slide 1" class="active">
            <img src="WhatsApp Image 2025-03-28 at 10.13.00_50577265.jpg" alt="Slide 2">
            <img src="WhatsApp Image 2025-03-28 at 10.13.00_1394b089.jpg" alt="Slide 3">
        </div>
        <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </section>

    <section class="buttons">
        <button class="create-btn" onclick="window.location.href='create_room.php'">Create</button>
        <button class="join-btn" onclick="window.location.href='Join.php'">Join</button>
    </section>
    
    <section class="info-cards">
        <div class="card">
            <img src="WhatsApp Image 2025-03-28 at 09.38.06_924fea79.jpg" alt="Card Image 1">
            <p>Our real-time platform enables seamless collaboration, bringing teams together anytime, anywhere.</p>
        </div>
        <div class="card">
            <img src="WhatsApp Image 2025-03-28 at 09.46.15_fc453f55.jpg" alt="Card Image 2">
            <p>With built-in video and audio chat, work closely with your team and share ideas in real-time.</p>
        </div>
        <div class="card">
            <img src="WhatsApp Image 2025-03-28 at 09.48.16_950358cf.jpg" alt="Card Image 3">
            <p>Designed for remote teams, freelancers, and businesses, ensuring a smooth workflow.</p>
        </div>
    </section>
    
    <section class="features">
        <h2>FEATURES</h2>
        <ul>
            <li><strong>Built-in Video & Audio Chat:</strong> Enables instant and effective communication.</li>
            <li><strong>Security & Ease of Use:</strong> Built with strong security measures to protect user data.</li>
            <li><strong>Designed for Remote Teams:</strong> Provides a hassle-free collaboration experience.</li>
            <li><strong>Collapsible Chat Box & Code Execution:</strong> Chat can collapse for focused coding.</li>
        </ul>
    </section>
    
    <footer>
        <p>&copy; 2025 Vcollab. All rights reserved.</p>
    </footer>
    
    <script>
        let index = 0;
        function moveSlide(direction) {
            const slides = document.querySelectorAll('.carousel-images img');
            slides[index].classList.remove('active');
            index = (index + direction + slides.length) % slides.length;
            slides[index].classList.add('active');
        }
    </script>
</body>
</html>