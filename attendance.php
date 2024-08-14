<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/attendance.css">
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

    <main class="main-content">
        <h1>Attendance</h1>

        <?php
        $servername = "localhost";
        $username = "admin";
        $password = "123";
        $database = "payroll";
        $conn = new mysqli($servername, $username, $password, $database);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["delete"]) && isset($_POST["delete_id"])) {
                $delete_id = $_POST["delete_id"];
                $delete_query = "DELETE FROM attendance WHERE employee_id = '$delete_id'";
                if ($conn->query($delete_query) === TRUE) {
                    echo "<script>alert('Entry deleted successfully.');</script>";
                } else {
                    echo "Error deleting entry: " . $conn->error;
                }
            } else {
                $id = $_POST["emp_id"];
                $entry_type = $_POST["entry_type"];
                $datetime_log = $_POST["datetime_log"];

                $insert_query = "INSERT INTO attendance (employee_id, log_type, datetime_log) 
                                 VALUES ('$id', '$entry_type', '$datetime_log')";

                if ($conn->query($insert_query) === TRUE) {
                    echo "<script>alert('Entry added successfully.');</script>";
                } else {
                    echo "Error: " . $conn->error;
                }
            }
        }

        $employee_query = "SELECT employee_no, firstname FROM employee";
        $employee_result = $conn->query($employee_query);
        $employees = [];

        if ($employee_result->num_rows > 0) {
            while ($row = $employee_result->fetch_assoc()) {
                $employees[] = $row;
            }
        }

        $select_query = "SELECT * FROM attendance";
        $result = $conn->query($select_query);

        if ($result->num_rows > 0) {
            echo "<table class='data-table'>
                    <thead>
                        <tr>
                            <th>Employee No</th>
                            <th>Name</th>
                            <th>Time Record</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['employee_id']}</td>
                        <td>{$row['datetime_log']}</td>
                        <td>{$row['log_type']}</td>
                        <td>
                            <form method='POST' class='inline-form'>
                                <input type='hidden' name='delete_id' value='{$row['employee_id']}'>
                                <button type='submit' name='delete' class='delete-button'>Delete</button>
                            </form>
                        </td>
                      </tr>";
            }

            echo "</tbody>
                </table>";
        } else {
            echo "<p>No entries in the database.</p>";
        }

        $conn->close();
        ?>

        <button onclick="openAddEntryForm()" class="add-entry-button">Add Entry</button>

        <form method="POST" id="addEntryForm" class="form-container" style="display: none;">
            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Entry Type</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="emp_id" required>
                                <?php
                                foreach ($employees as $employee) {
                                    echo "<option value='{$employee['employee_no']}'>{$employee['employee_no']} - {$employee['firstname']}</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select name="entry_type" required>
                                <option value="login">Login</option>
                                <option value="logout">Logout</option>
                            </select>
                        </td>
                        <td><input type="datetime-local" name="datetime_log" required></td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="submit" value="Add Entry" class="submit-button">
        </form>
    </main>

    <script>
        function openAddEntryForm() {
            var form = document.getElementById("addEntryForm");
            form.style.display = "block";
        }
    </script>
</body>

</html>
