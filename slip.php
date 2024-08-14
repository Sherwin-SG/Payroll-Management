<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/slip.css">
</head>
<body>
    <div class="navbar-container">
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
                    <li><a href="login.php">Logout</a></li>
                </ul>
            </nav>
        </div>
        <div class="main">
            <button class="print-button" onclick="printPage()">Print</button>
            <div id="payrollInfo">
                <?php
                // Database connection
                $servername = "localhost";
                $username = "admin";
                $password = "123";
                $database = "payroll";
                $conn = new mysqli($servername, $username, $password, $database);

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get ref_no from the URL
                $ref_no = $_GET['ref_no'];

                // Query the payroll table
                $id_query = "SELECT id, date_from, date_to, type FROM payroll WHERE ref_no = ?";
                $id_stmt = $conn->prepare($id_query);
                $id_stmt->bind_param("s", $ref_no);
                $id_stmt->execute();
                $id_result = $id_stmt->get_result();

                if ($id_result->num_rows > 0) {
                    $row = $id_result->fetch_assoc();
                    $id = $row['id'];
                    $date_from = $row['date_from'];
                    $date_to = $row['date_to'];
                    $type = $row['type'];

                    echo "<h2>Payroll Details</h2>";
                    echo "<p><strong>ID:</strong> $id</p>";
                    echo "<p><strong>Date From:</strong> $date_from</p>";
                    echo "<p><strong>Date To:</strong> $date_to</p>";
                    echo "<p><strong>Type:</strong> $type</p>";

                    // Retrieve payroll_items
                    $items_query = "SELECT * FROM payroll_items WHERE payroll_id = ?";
                    $items_stmt = $conn->prepare($items_query);
                    $items_stmt->bind_param("i", $id);
                    $items_stmt->execute();
                    $items_result = $items_stmt->get_result();

                    if ($items_result->num_rows > 0) {
                        echo "<h3>Payroll Items</h3>";
                        while ($items_row = $items_result->fetch_assoc()) {
                            echo "<p><strong>Payroll ID:</strong> " . $items_row['payroll_id'] . "<br>";
                            echo "<strong>Employee ID:</strong> " . $items_row['employee_id'] . "<br>";
                            echo "<strong>Salary:</strong> " . $items_row['salary'] . "<br>";
                            echo "<strong>Allowance Amount:</strong> " . $items_row['allowance_amount'] . "<br>";
                            echo "<strong>Deduction Amount:</strong> " . $items_row['deduction_amount'] . "<br>";
                            echo "<strong>Net:</strong> " . $items_row['net'] . "<br>";
                            echo "<strong>Date Created:</strong> " . $items_row['date_created'] . "</p><hr>";
                        }
                    } else {
                        echo "<p>No payroll items found for the given ID.</p>";
                    }
                } else {
                    echo "<p>Invalid ref_no.</p>";
                }

                // Close connections
                $id_stmt->close();
                $items_stmt->close();
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="js/index.js"></script>
    <script>
    function printPage() {
        window.print();
    }
    </script>
</body>
</html>
