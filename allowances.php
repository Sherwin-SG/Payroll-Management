<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allowance Management</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/department.css">
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

    <h1>Allowance Management</h1>
    <?php
    $servername = "localhost";
    $username = "admin";
    $password = "123";
    $database = "payroll";
    $conn = new mysqli($servername, $username, $password, $database);

    // Check if the form is submitted to add an allowance
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_allowance"])) {
        // Get form data
        $allowance = isset($_POST["allowance_info"]) ? $_POST["allowance_info"] : '';

        if (!empty($allowance)) {
            // Insert data into the 'allowances' table
            $insert_query = "INSERT INTO allowances (allowance) VALUES ('$allowance')";

            // Execute the query
            if ($conn->query($insert_query) === TRUE) {
                echo "Allowance added successfully.";
            } else {
                echo "Error adding allowance: " . $conn->error;
            }
        } else {
            echo "Error: Allowance information entry is empty.";
        }
    }

    // Check if an allowance deletion request is submitted
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        // Perform the deletion
        $delete_query = "DELETE FROM allowances WHERE id = '$deleteId'";
        if ($conn->query($delete_query) === TRUE) {
            echo "Allowance deleted successfully.";
        } else {
            echo "Error deleting allowance: " . $conn->error;
        }
    }

    // Display allowances in the 'allowances' table
    $select_query = "SELECT * FROM allowances";
    $result = $conn->query($select_query);

    if ($result->num_rows > 0) {
        echo "<h2>Allowances:</h2>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Allowance Information</th>
                    <th>Action</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['allowance']}</td>
                    <td>
                        <button type='button' onclick='deleteAllowance({$row['id']})'>Delete</button>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No allowances in the 'allowances' table.</p>";
    }
    ?>

    <h2>Add Entry to Allowances:</h2>
    <form class="form-position-entry" method="POST">
        <label for="allowance_info">Allowance Information:</label>
        <input type="text" name="allowance_info" required>
        <br>
        <input type="submit" name="submit_allowance" value="Add Entry">
    </form>

    <script>
        function deleteAllowance(Id) {
    // Ask for confirmation before deleting
    var confirmDelete = confirm("Are you sure you want to delete this allowance?");
    if (confirmDelete) {
        // Redirect to allowances.php with the delete parameter
        window.location.href = "allowances.php?delete=" + Id;
    }
}

    </script>
 
</body>

</html>
