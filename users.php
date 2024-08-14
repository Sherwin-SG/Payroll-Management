<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="css/styles.css">
<script src="script.js" defer></script>
</head>

<body>
<div class="navbar-container">
        <div class="navbar-toggle">
            <div class="bars"></div>
            <div class="cross"></div>
        </div>
        <div class="sidebar">
            <nav>
                <ul>
                    <li><a href="intro.php">Home</a></li>
                    <li><a href="attendance.php">Attendance</a></li>
                    <li><a href="payrollList.php">Payroll List</a></li>
                    <li><a href="employee.php">Employee List</a></li>
                    <li><a href="department.php">Department List</a></li>
                    <li><a href="position.php">Position List</a></li>
                    <li><a href="allowances.php">Allowance List</a></li>
                    <li><a href="deductions.php">Deduction List</a></li>
                    <li><a href="users.php">User Management</a></li>
                    <li><a href="index.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <h1>User Management</h1>

    <button onclick="openAddUserForm()">Add User</button>

    <div id="addUserForm" style="display: none;">
        <h2>Add New User:</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            <br>
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <br>
            <input type="submit" name="submit_user" value="Add Entry">
        </form>
    </div>

    <h2>Edit User:</h2>

    <?php
    $servername = "localhost";
    $username = "admin";
    $password = "123";
    $database = "payroll";
    $conn = new mysqli($servername, $username, $password, $database);

    // Check if an edit user request is submitted
   // Check if the form is submitted to add a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_user"])) {
    // Get form data
    $name = isset($_POST["name"]) ? $_POST["name"] : '';
    $username = isset($_POST["username"]) ? $_POST["username"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    if (!empty($name) && !empty($username) && !empty($password)) {
        // Insert data into the 'users' table
        $insert_query = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$password')";

        // Execute the query
        if ($conn->query($insert_query) === TRUE) {
            echo "User added successfully.";
        } else {
            echo "Error adding user: " . $conn->error;
        }
    } else {
        echo "Error: Name, username, or password is empty.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $deleteId = $_POST['delete'];

    // Perform the deletion
    $delete_query = "DELETE FROM users WHERE id = '$deleteId'";
    if ($conn->query($delete_query) === TRUE) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}


    // Display users in the 'users' table
    $select_query = "SELECT * FROM users";
    $result = $conn->query($select_query);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['password']}</td>
                    <td>
                        <button type='button' onclick='deleteUser({$row['id']})'>Delete</button>
                        <button type='button' onclick='editUser({$row['id']}, \"{$row['username']}\", \"{$row['password']}\")'>Edit</button>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No users in the 'users' table.</p>";
    }
    ?>

    <script>
        function openAddUserForm() {
            var form = document.getElementById("addUserForm");
            form.style.display = "block";
        }

        function deleteUser(Id) {
    // Ask for confirmation before deleting
    var confirmDelete = confirm("Are you sure you want to delete this user?");
    if (confirmDelete) {
        // Create a form dynamically
        var form = document.createElement("form");
        form.method = "POST";
        form.action = "users.php";

        // Create hidden input for delete
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "delete";
        input.value = Id;
        form.appendChild(input);

        // Append form to the body
        document.body.appendChild(form);

        // Submit the form
        form.submit();
    }
}

function editUser(Id, currentUsername, currentPassword) {
    // Ask for new username and password
    var newUsername = prompt("Enter the new username:", currentUsername);
    var newPassword = prompt("Enter the new password:", currentPassword);

    if (newUsername !== null && newPassword !== null) {
        // Create a form dynamically
        var form = document.createElement("form");
        form.method = "POST";
        form.action = "users.php";

        // Create hidden input for edit_user
        var input = document.createElement("input");
        input.type = "hidden";
        input.name = "edit_user";
        input.value = Id;
        form.appendChild(input);

        // Create hidden input for new_username
        input = document.createElement("input");
        input.type = "hidden";
        input.name = "new_username";
        input.value = newUsername;
        form.appendChild(input);

        // Create hidden input for new_password
        input = document.createElement("input");
        input.type = "hidden";
        input.name = "new_password";
        input.value = newPassword;
        form.appendChild(input);

        // Append form to the body
        document.body.appendChild(form);

        // Submit the form
        form.submit();
    }
}
    </script>

     
</body>

</html>
