<?php
session_start();
include 'db_connect.php';

// Handle leaving the room if requested
if (isset($_POST['leaveRoom'])) {
    if (isset($_SESSION['username'])) {
        $roomId = $_POST['roomId'];
        $username = $_SESSION['username'];
        
        $query = "DELETE FROM join_form WHERE room_id = ? AND username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $roomId, $username);
        $stmt->execute();
        $stmt->close();
        
        // Redirect after leaving
        header("Location: home.php");
        exit();
    }
}

// Get current room users
$users = [];
if (isset($_GET['roomId'])) {
    $roomId = $_GET['roomId'];
    
    $query = "SELECT username FROM join_form WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row['username'];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vcollab</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="shortcut icon" href="WhatsApp Image 2025-03-27 at 14.45.13_5f59e3bc.jpg" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #1c1e29;
            color: white;
            margin: 0;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #111;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .logo {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .users {
            margin-bottom: 20px;
        }
        .user {
            display: flex;
            align-items: center;
            background: #222;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .user span {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            background: #ff0077;
            color: white;
            font-weight: bold;
            border-radius: 50%;
            margin-right: 10px;
        }
        .buttons {
            margin-top: auto;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background: #00ffcc;
            border: none;
            color: #111;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .btn:hover {
            background: #00cca3;
        }
        .editor-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: relative;
        }
        .editor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        select {
            padding: 5px;
            background: #222;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .CodeMirror {
            height: calc(100vh - 220px);
            font-size: 18px;
            background: #1c1e29;
            color: white;
        }
        .right-sidebar {
            width: 250px;
            background: #111;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .call-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }
        .call-btn {
            padding: 10px;
            background: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
        }
        .call-btn:hover {
            background: #0056b3;
        }
        .chat-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #222;
            padding: 10px;
            border-radius: 5px;
            overflow-y: auto;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            max-height: 200px;
        }
        .chat-input {
            display: flex;
            margin-top: 10px;
            border-radius: 60px;
        }
        .chat-input input {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 40px;
            background: #333;
            color: white;
            position: static;
        }
        .chat-input button {
            padding: 5px;
            background: #00ffcc;
            border: none;
            color: #111;
            font-weight: bold;
            cursor: pointer;
            border-radius: 20px;
            margin-left: 5px;
        }
        .folder-section {
            width: 250px;
            background: #111;
            padding: 10px;
            display: flex;
            flex-direction: column;
            border-right: 2px solid #222;
        }
        .folder-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .folder-content ul {
            list-style: none;
            padding: 0;
        }
        .folder-content li {
            background: #222;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 5px;
            cursor: pointer;
        }
        .output-box {
            background: #222;
            padding: 10px;
            border-radius: 5px;
            min-height: 100px;
            overflow-y: auto;
        }
        .video-call-container {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            height: 400px;
            background: #111;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            z-index: 1000;
            overflow: hidden;
        }
        .video-call-header {
            background: #007bff;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .video-call-close {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }
        .video-call-video {
            width: 100%;
            height: calc(100% - 40px);
            position: relative;
        }
        #remoteVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #000;
        }
        #localVideo {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 100px;
            height: 100px;
            border: 2px solid white;
            border-radius: 5px;
            object-fit: cover;
        }
        .video-call-controls {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .video-call-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.5);
            color: white;
        }
        .video-call-btn.end-call {
            background: #FF3B30;
        }
    </style>
</head>
<body>
    <!-- Left Sidebar -->
    <div class="sidebar">
        <div class="logo"><img src="WhatsApp Image 2025-03-27 at 14.45.13_5f59e3bc.jpg" alt="" width="120" height="100"></div>
        
        <div class="user-list">
            <h3>Users in Room:</h3>
            <ul id="userList">
                <?php  
                if (!empty($users)) {
                    foreach ($users as $user) {
                        echo "<li class='user'><span>" . strtoupper(substr($user, 0, 1)) . "</span>" . htmlspecialchars($user) . "</li>";
                    }
                } else {
                    echo "<p>No users found in the room.</p>";
                }
                ?>
            </ul>
        </div>

        <div class="buttons">
            <p>Room ID: <span id="room-id"><?php echo htmlspecialchars($_GET['roomId'] ?? ''); ?></span></p>
            <button onclick="copyRoomID()" class="btn btn-primary">Copy Room ID</button>
            <form method="post" id="leaveForm">
                <input type="hidden" name="leaveRoom" value="1">
                <input type="hidden" name="roomId" value="<?php echo htmlspecialchars($_GET['roomId'] ?? ''); ?>">
                <button type="button" class="btn" style="background: red;" onclick="window.location.href='home.php'">Leave</button>
            </form>
        </div>
    </div>
    
    <div class="folder-section">
        <div class="folder-header">Explorer</div>
        <button onclick="addFolder()" class="btn">+ New Folder</button>
        <ul id="folderList"></ul>
    </div>
    
    <!-- Main Editor Section -->
    <div class="editor-container">
        <div class="editor-header">
            <h3>Collaborative Code Editor</h3>
            <select id="language">
                <option value="select">Select Language</option>
                <option value="python3">Python</option>
                <option value="php">PHP</option>
                <option value="nodejs">Node.js</option>
            </select>
        </div>

        <textarea id="editor"></textarea>
        
        <button class="btn" onclick="runCode()">Run Code</button>
        
        <h3>Output:</h3>
        <pre id="output" class="output-box">Your output will appear here...</pre>
    </div>

    <!-- Right Sidebar (Video & Chat) -->
    <div class="right-sidebar">
        <h3>Call Options</h3>
        <div class="right-sidebar">
        <h3>Call Options</h3>
        <div class="call-buttons">
            <button class="call-btn" id="startVideoCall">Video Call</button>
            <button class="call-btn" id="startAudioCall">Audio Call</button>
        </div>

        <!-- Rest of your right sidebar content -->
    </div>

    <!-- Video Call Popup -->
    <div class="video-call-container" id="videoCallContainer">
        <div class="video-call-header">
            <span>Vcollab</span>
            <button class="video-call-close" id="endVideoCall">×</button>
        </div>
        <div class="video-call-video">
            <video id="remoteVideo" autoplay playsinline></video>
            <video id="localVideo" autoplay playsinline muted></video>
            <div class="video-call-controls">
                <button class="video-call-btn" id="toggleMute">
                    <i class="fas fa-microphone"></i>
                </button>
                <button class="video-call-btn" id="toggleVideo">
                    <i class="fas fa-video"></i>
                </button>
                <button class="video-call-btn end-call" id="endCallBtn">
                    <i class="fas fa-phone"></i>
                </button>
            </div>
        </div>
    </div>

        <h3>Chat</h3>
        <div class="chat-box">
            <div class="chat-messages" id="chat-messages"></div>
            <div class="chat-input">
                <input type="text" id="chatInput" placeholder="Type a message...">
                <button onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        // Initialize CodeMirror editor
        const editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
            mode: "javascript",
            theme: "dracula",
            lineNumbers: true,
            autoCloseBrackets: true,
            autoCloseTags: true
        });

        // Function to run code
        function runCode() {
            const code = editor.getValue();
            const language = document.getElementById("language").value;

            axios.post("compile.php", { code, language })
                .then(response => {
                    document.getElementById("output").innerText = response.data.output;
                })
                .catch(error => {
                    console.error("Compilation error:", error);
                    document.getElementById("output").innerText = "Error compiling code";
                });
        }

        // Function to copy room ID
        function copyRoomID() {
            const roomId = document.getElementById("room-id").innerText;
            navigator.clipboard.writeText(roomId)
                .then(() => alert("Room ID copied: " + roomId))
                .catch(err => alert("Failed to copy: " + err));
        }

        // Function to leave room
        function leaveRoom() {
            if (confirm("Are you sure you want to leave the room?")) {
                document.getElementById("leaveForm").submit();
            }
        }

        // Function to send chat message
        function sendMessage() {
            const chatInput = document.getElementById("chatInput");
            const message = chatInput.value.trim();
            
            if (message) {
                const chatMessages = document.getElementById("chat-messages");
                chatMessages.innerHTML += `<p>${message}</p>`;
                chatInput.value = "";
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }

        // Function to add folder
        function addFolder() {
            const folderName = prompt("Enter folder name:");
            if (folderName) {
                const folderList = document.getElementById("folderList");
                const li = document.createElement("li");
                li.className = "folder";
                li.textContent = folderName;
                li.onclick = function() { addFile(this); };
                folderList.appendChild(li);
            }
        }

        // Function to add file
        function addFile(folder) {
            const fileName = prompt("Enter file name:");
            if (fileName) {
                let ul = folder.querySelector("ul");
                if (!ul) {
                    ul = document.createElement("ul");
                    folder.appendChild(ul);
                }
                const li = document.createElement("li");
                li.className = "file";
                li.textContent = fileName;
                ul.appendChild(li);
            }
        }

        // Function to fetch and update user list
        function fetchUsers() {
            const roomId = document.getElementById("room-id").innerText;
            
            fetch(`fetch_users.php?roomId=${roomId}`)
                .then(response => response.json())
                .then(users => {
                    const userList = document.getElementById("userList");
                    userList.innerHTML = '';
                    
                    if (users.length > 0 && !users.error) {
                        users.forEach(user => {
                            userList.innerHTML += 
                                `<li class="user">
                                    <span>${user.charAt(0).toUpperCase()}</span>
                                    ${user}
                                </li>`;
                        });
                    } else {
                        userList.innerHTML = '<p>No users in room</p>';
                    }
                })
                .catch(error => {
                    console.error("Error fetching users:", error);
                });
        }

        // Refresh user list every 3 seconds
        setInterval(fetchUsers, 3000);

        // Handle Enter key in chat
        document.getElementById("chatInput").addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });
        const videoCallContainer = document.getElementById('videoCallContainer');
        const startVideoCallBtn = document.getElementById('startVideoCall');
        const startAudioCallBtn = document.getElementById('startAudioCall');
        const endVideoCallBtn = document.getElementById('endVideoCall');
        const endCallBtn = document.getElementById('endCallBtn');
        const toggleMuteBtn = document.getElementById('toggleMute');
        const toggleVideoBtn = document.getElementById('toggleVideo');
        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');

        let localStream;
        let isMuted = false;
        let isVideoOff = false;

        // Start Video Call
        startVideoCallBtn.addEventListener('click', async () => {
            try {
                videoCallContainer.style.display = 'block';
                
                // Get user media (video and audio)
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                });
                
                localVideo.srcObject = localStream;
                
                // In a real app, you would connect to other users here
                // For demo, we'll just show local video in both elements
                remoteVideo.srcObject = localStream;
                
            } catch (error) {
                console.error('Error accessing media devices:', error);
                alert('Could not access camera/microphone');
                endCall();
            }
        });

        // Start Audio Call (video off)
        startAudioCallBtn.addEventListener('click', async () => {
            try {
                videoCallContainer.style.display = 'block';
                
                // Get user media (audio only)
                localStream = await navigator.mediaDevices.getUserMedia({
                    video: false,
                    audio: true
                });
                
                localVideo.srcObject = null; // No video for audio call
                localVideo.style.display = 'none';
                
                // In a real app, you would connect to other users here
                // For demo, we'll just show a black screen
                remoteVideo.srcObject = null;
                remoteVideo.style.backgroundColor = '#000';
                
            } catch (error) {
                console.error('Error accessing microphone:', error);
                alert('Could not access microphone');
                endCall();
            }
        });

        // End Call
        function endCall() {
            videoCallContainer.style.display = 'none';
            
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
                localVideo.srcObject = null;
                remoteVideo.srcObject = null;
            }
            
            // Reset states
            isMuted = false;
            isVideoOff = false;
            localVideo.style.display = 'block';
            remoteVideo.style.backgroundColor = 'transparent';
        }

        endVideoCallBtn.addEventListener('click', endCall);
        endCallBtn.addEventListener('click', endCall);

        // Toggle Mute
        toggleMuteBtn.addEventListener('click', () => {
            if (!localStream) return;
            
            isMuted = !isMuted;
            localStream.getAudioTracks().forEach(track => {
                track.enabled = !isMuted;
            });
            
            toggleMuteBtn.innerHTML = isMuted 
                ? '<i class="fas fa-microphone-slash"></i>' 
                : '<i class="fas fa-microphone"></i>';
        });

        // Toggle Video
        toggleVideoBtn.addEventListener('click', () => {
            if (!localStream) return;
            
            isVideoOff = !isVideoOff;
            localStream.getVideoTracks().forEach(track => {
                track.enabled = !isVideoOff;
            });
            
            toggleVideoBtn.innerHTML = isVideoOff 
                ? '<i class="fas fa-video-slash"></i>' 
                : '<i class="fas fa-video"></i>';
        });

        // Add Font Awesome for icons
        const fontAwesome = document.createElement('link');
        fontAwesome.rel = 'stylesheet';
        fontAwesome.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';
        document.head.appendChild(fontAwesome);
    </script>
</body>
</html>