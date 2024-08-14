<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deduction Management</title>
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

    <h1>Deduction Management</h1>
 
    <?php
$servername = "localhost";
$username = "admin";
$password = "123";
$database = "payroll";
$conn = new mysqli($servername, $username, $password, $database);

// Check if the form is submitted to add a deduction
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_deduction"])) {
    // Get form data
    $deduction = isset($_POST["deduction_info"]) ? $_POST["deduction_info"] : '';

    if (!empty($deduction)) {
        // Insert data into the 'deductions' table
        $insert_query = "INSERT INTO deductions (deduction, description) VALUES ('$deduction', '$deduction')";

        // Execute the query
        if ($conn->query($insert_query) === TRUE) {
            echo "Deduction added successfully.";
        } else {
            echo "Error adding deduction: " . $conn->error;
        }
    } else {
        echo "Error: Deduction information entry is empty.";
    }
}

    // Check if a deduction deletion request is submitted
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        // Perform the deletion
        $delete_query = "DELETE FROM deductions WHERE id = '$deleteId'";
        if ($conn->query($delete_query) === TRUE) {
            echo "Deduction deleted successfully.";
        } else {
            echo "Error deleting deduction: " . $conn->error;
        }
    }

    // Display deductions in the 'deductions' table
    $select_query = "SELECT * FROM deductions";
    $result = $conn->query($select_query);

    if ($result->num_rows > 0) {
        echo "<h2>Deductions:</h2>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Deduction Information</th>
                    <th>Action</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['deduction']}</td>
                    <td>
                        <button type='button' onclick='deleteDeduction({$row['id']})'>Delete</button>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No deductions in the 'deductions' table.</p>";
    }
    ?>

    <h2>Add Entry to Deductions:</h2>
    <form class="form-position-entry" method="POST">
        <label for="deduction_info">Deduction Information:</label>
        <input type="text" name="deduction_info" required>
        <br>
        <input type="submit" name="submit_deduction" value="Add Entry">
    </form>

    <script>
        function deleteDeduction(Id) {
            // Ask for confirmation before deleting
            var confirmDelete = confirm("Are you sure you want to delete this deduction?");
            if (confirmDelete) {
                // Redirect to deductions.php with the delete parameter
                window.location.href = "deductions.php?delete=" + Id;
            }
        }
    </script>

     
</body>

</html>
