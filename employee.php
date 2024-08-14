<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allowance Management</title>
    <link rel="stylesheet" href="css/employee.css">
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

            <h2>Employee Details</h2>

            <?php
            
$servername = "localhost";
$username = "admin";
$password = "123";
$database = "payroll";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT e.*, d.name AS department_name, p.name AS position_name
        FROM employee e
        LEFT JOIN department d ON e.department_id = d.id
        LEFT JOIN position p ON e.position_id = p.id";

$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error executing the query: " . $conn->error);
}
            if ($result->num_rows > 0) {
                echo "<table>
                        <thead>
                            <tr>
                                <th>Employee No</th>
                                <th>Firstname</th>
                                <th>Middlename</th>
                                <th>Lastname</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['employee_no']}</td>
                            <td>{$row['firstname']}</td>
                            <td>{$row['middlename']}</td>
                            <td>{$row['lastname']}</td>
                            <td>{$row['department_name']}</td>
                            <td>{$row['position_name']}</td>
                            <td><button onclick=\"deleteEmployee('{$row['employee_no']}')\">Delete</button></td>
                            <td><button onclick=\"selectAllowancesDeductions('{$row['employee_no']}')\">add_allow/deduc</button></td>
                        </tr>";
                }

                echo "</tbody></table>";
               
                echo "<button onclick=\"location.href='add_employee.php'\">Add Entry</button>";
            } else {
                echo "No employees found";
            }

            $conn->close();
            ?>

        </div>
    </div>
    <script src="js/script.js"></script>
    <script src="js/index.js"></script>

    <script>
        // JavaScript function to handle employee deletion
        function deleteEmployee(employeeNo) {
            if (confirm('Are you sure you want to delete this employee?')) {
                // Redirect to delete_employee.php with the employee number as a parameter
                window.location.href = 'delete_employee.php?employee_no=' + employeeNo;
            }
        }
        function selectAllowancesDeductions(employee) {
            // Log the employee number to the console
    console.log("Adding   for employee number:", employee);
        // Redirect to a page where allowances and deductions can be selected for the specific employee
        window.location.href = 'view_allowances_deductions.php?employee_no=' + employee;
    }
    </script>
</body>

</html>
