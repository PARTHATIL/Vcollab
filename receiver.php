<!DOCTYPE html>
<html lang="en">
<head>
    <title>Video Call (WhatsApp Style)</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #222;
            margin: 0;
            padding: 20px;
            color: white;
        }
        .video-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 10px;
            width: 500px;
            height: 500px;
            margin: auto;
            padding: 10px;
            border-radius: 15px;
            position: relative;
            background: linear-gradient(45deg, gray, goldenrod);
            animation: ringingEffect 1.5s infinite alternate;
            transition: background 0.5s ease-in-out;
        }
        @keyframes ringingEffect {
            0% { background: gray; }
            100% { background: goldenrod; }
        }
        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
            background: black;
        }
        #localVideo {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 120px;
            height: 120px;
            border: 2px solid white;
            border-radius: 10px;
            z-index: 10;
        }
        #ringingText {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            background: rgba(0, 0, 0, 0.6);
            padding: 10px 20px;
            border-radius: 10px;
            display: block;
        }
        .controls {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .btn {
            padding: 12px 20px;
            font-size: 16px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 10px;
            transition: background 0.3s ease;
        }
        .answer { background-color: #25D366; }
        .answer:hover { background-color: #1da851; }
        .reject { background-color: #FF3B30; }
        .reject:hover { background-color: #d72a26; }
        .mute { background-color: #666; }
        .mute:hover { background-color: #444; }
    </style>
</head>
<body>
    <h2>Video Call (WhatsApp Style)</h2>

    <div class="video-container" id="videoContainer">
        <div id="ringingText">📞 Ringing...</div>
    </div>

    <div class="controls">
        <button class="btn answer" onclick="answerCall()">✅ Accept Call</button>
        <button class="btn reject" onclick="rejectCall()">❌ Reject Call</button>
        <button id="muteButton" class="btn mute" onclick="toggleMute()" disabled>🎤 Mute</button>
    </div>

    <script>
        let peerConnections = {};
        let localStream;
        let isMuted = false;
        const videoContainer = document.getElementById("videoContainer");
        const ringingText = document.getElementById("ringingText");

        async function answerCall() {
            // Stop ringing animation
            videoContainer.style.animation = "none";
            videoContainer.style.background = "black";
            ringingText.style.display = "none";

            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });

            for (let i = 0; i < 4; i++) {
                let videoElement = document.createElement("video");
                videoElement.setAttribute("autoplay", true);
                videoElement.setAttribute("playsinline", true);
                videoContainer.appendChild(videoElement);

                if (i === 0) {
                    videoElement.srcObject = localStream;
                }
            }

            document.getElementById("muteButton").disabled = false;
        }

        function rejectCall() {
            alert("Call rejected!");
            endCall();
        }

        function toggleMute() {
            if (!localStream) return;
            const audioTrack = localStream.getAudioTracks()[0];
            if (audioTrack) {
                isMuted = !isMuted;
                audioTrack.enabled = !isMuted;
                document.getElementById("muteButton").textContent = isMuted ? "🔇 Unmute" : "🎤 Mute";
            }
        }

        function endCall() {
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
            videoContainer.innerHTML = `<div id="ringingText">📞 Ringing...</div>`;
            videoContainer.style.animation = "ringingEffect 1.5s infinite alternate";
            videoContainer.style.background = "linear-gradient(45deg, gray, goldenrod)";
        }
    </script>
</body>
</html>
