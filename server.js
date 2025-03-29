const socket = io("ws://localhost:3000");

const editor = CodeMirror.fromTextArea(document.getElementById("realtimeEditor"), {
    mode: "javascript",
    theme: "dracula",
    lineNumbers: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
});

editor.on("change", () => {
    const code = editor.getValue();
    socket.emit("codeChange", { roomId, code });
});

socket.on("updateCode", (data) => {
    if (data.code !== editor.getValue()) {
        editor.setValue(data.code);
    }
});

socket.emit("joinRoom", roomId);
