<link rel="stylesheet" href="css/login2.css">
<?php
// Assuming you have a form with POST method to collect username and password
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $providedUsername = $_POST["username"];
    $providedPassword = $_POST["password"];

    $servername = "localhost";
    $username = "admin";
    $password = "123";
    $database = "payroll";
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve the password for the provided username
    $sql = "SELECT username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $providedUsername);
    $stmt->execute();
    $stmt->bind_result($username, $storedPassword);

    // Fetch the result
    $stmt->fetch();

    // Verify the password
    if ($username && $providedPassword === $storedPassword) {
        session_start();
        $_SESSION["username"] = $providedUsername; // Store username in session

        // Regenerate session ID for security
        session_regenerate_id(true);

        header("Location: intro.php"); // Redirect to a dashboard page on successful login
        exit();
    } else {
        $error = "Invalid username or password"; // Error message for incorrect login
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) {
            echo "<p style='color: red;'>$error</p>";
        } ?>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>

            <input type="submit" value="Login">
        </form>
    </div>
</body>

</html>