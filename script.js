function fetchUsers() {
    $.ajax({
        url: "fetch_users.php",
        method: "GET",
        success: function (data) {
            let users = JSON.parse(data);
            let userList = $("#userList");
            userList.empty();

            users.forEach(user => {
                userList.append(`<div class="user">${user}</div>`);
            });
        }
    });
}

// Refresh user list every 3 seconds
setInterval(fetchUsers, 3000);

// Send username on join
$(document).ready(function () {
    let username = prompt("Enter your name:");
    $.post("update_users.php", { username: username }, function () {
        fetchUsers();
    });
});
