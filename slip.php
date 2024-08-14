
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/attendance.css">
</head>


<body>
    <div class="container">

        <div class="sidebar sidebargo">

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
            </nav>

        </div>

        <div class="main">
            <div class="bars">
                <img class="bar" src="bar.png" alt="" width="40px">
                <img class="cross" src="cross.png" alt="" width="40px">
            </div>
            <button onclick="printPage()">Print</button>
        </div>

    </div>
    <div id="payrollInfo">
<?php

// Assuming you have a database connection
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

// Query the payroll table to get the id and additional information
$id_query = "SELECT id, date_from, date_to, type FROM payroll WHERE ref_no = ?";
$id_stmt = $conn->prepare($id_query);
$id_stmt->bind_param("s", $ref_no);
$id_stmt->execute();
$id_result = $id_stmt->get_result();

if ($id_result->num_rows > 0) {
    // Fetch the id and additional information
    $row = $id_result->fetch_assoc();
    $id = $row['id'];
    $date_from = $row['date_from'];
    $date_to = $row['date_to'];
    $type = $row['type'];

    echo "ID: " . $id . "<br>";
    echo "Date From: " . $date_from . "<br>";
    echo "Date To: " . $date_to . "<br>";
    echo "Type: " . $type . "<br>";

    // Use the id to retrieve payroll_items
    $items_query = "SELECT * FROM payroll_items WHERE payroll_id = ?";
    $items_stmt = $conn->prepare($items_query);
    $items_stmt->bind_param("i", $id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();

    if ($items_result->num_rows > 0) {
        // Fetch and display payroll_items
        while ($items_row = $items_result->fetch_assoc()) {
            echo "Payroll ID: " . $items_row['payroll_id'] . "<br>";
            echo "Employee ID: " . $items_row['employee_id'] . "<br>";
            echo "Salary: " . $items_row['salary'] . "<br>";
            echo "Allowance Amount: " . $items_row['allowance_amount'] . "<br>";
            echo "Deduction Amount: " . $items_row['deduction_amount'] . "<br>";
            echo "Net: " . $items_row['net'] . "<br>";
            echo "Date Created: " . $items_row['date_created'] . "<br>";
            echo "<hr>";
        }
    } else {
        echo "No payroll items found for the given ID.";
    }
} else {
    echo "Invalid ref_no.";
}

// Close connections
$id_stmt->close();
$items_stmt->close();
$conn->close();
?>
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
