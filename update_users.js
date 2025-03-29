function fetchUsers() {
    let roomId = "<?php echo $_GET['roomId']; ?>";
    
    fetch(`fetch_users.php?roomId=${roomId}`)
        .then(response => response.json())
        .then(data => {
            const userList = document.querySelector('.user-list ul');
            userList.innerHTML = '';

            data.forEach(user => {
                userList.innerHTML += `<li class="user"><span>${user[0].toUpperCase()}</span>${user}</li>`;
            });
        })
        .catch(error => console.error("Error fetching users:", error));
}

setInterval(fetchUsers, 5000); // Update user list every 5 seconds
