<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
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

    <h1>Position Management</h1>
    <?php
    $servername = "localhost";
    $username = "admin";
    $password = "123";
    $database = "payroll";
    $conn = new mysqli($servername, $username, $password, $database);

   // Check if the form is submitted to add a position
   if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_position"])) {
    // Get form data
    $departmentId = isset($_POST["department_id"]) ? $_POST["department_id"] : '';
    $positionName = isset($_POST["position_name_entry"]) ? $_POST["position_name_entry"] : '';

    if (!empty($departmentId) && !empty($positionName)) {
        // Insert data into the 'position' table
        $insert_query = "INSERT INTO position (department_id, name) VALUES ('$departmentId', '$positionName')";

        // Execute the query
        if ($conn->query($insert_query) === TRUE) {
            echo "Position added successfully.";
        } else {
            echo "Error adding position: " . $conn->error;
        }
    } else {
        echo "Error: Department ID or position name entry is empty.";
    }
}


    // Fetch department names for the dropdown menu
    $department_query = "SELECT * FROM department";
    $department_result = $conn->query($department_query);
    $departments = [];
    
    while ($department_row = $department_result->fetch_assoc()) {
        $departments[] = $department_row;
    }

    // Check if a position deletion request is submitted
    if (isset($_GET['delete'])) {
        $deleteId = $_GET['delete'];

        // Perform the deletion
        $delete_query = "DELETE FROM position WHERE id = '$deleteId'";
        if ($conn->query($delete_query) === TRUE) {
            echo "Position deleted successfully.";
        } else {
            echo "Error deleting position: " . $conn->error;
        }
    }

    // Display positions in the 'position' table
    $select_query = "SELECT * FROM position";
    $result = $conn->query($select_query);

    if ($result->num_rows > 0) {
        echo "<h2>Positions:</h2>";
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>
                        <button type='button' onclick='deletePosition({$row['id']})'>Delete</button>
                    </td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No positions in the 'position' table.</p>";
    }
    ?>

<h2>Add Entry to Positions:</h2>
<form class="form-position-entry" method="POST">
    <label for="department_name">Department:</label>
    <select name="department_id" required>
        <?php
        foreach ($departments as $department) {
            echo "<option value='{$department['id']}'>{$department['id']} - {$department['name']}</option>";
        }
        ?>
    </select>
    <br>
    <label for="position_name_entry">Position Name:</label>
    <input type="text" name="position_name_entry" required>
    <br>
    <input type="submit" name="submit_position" value="Add Entry">
</form>

    <script>
    function deletePosition(Id) {
        // Ask for confirmation before deleting
        var confirmDelete = confirm("Are you sure you want to delete this position?");
        if (confirmDelete) {
            // Redirect to position.php with the delete parameter
            window.location.href = "position.php?delete=" + Id;
        }
    }

    document.querySelector('form').addEventListener('submit', function (event) {
        console.log('Form submitted!');
    });
</script>


    <script src="js/script.js"></script>
    <script src="js/index.js"></script>
</body>

</html>
