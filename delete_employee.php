<?php
$servername = "localhost";
$username = "admin";
$password = "123";
$database = "payroll";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['employee_no'])) {
    $employeeNo = $_GET['employee_no'];

    // Delete the employee record from the database
    $deleteSql = "DELETE FROM employee WHERE employee_no = '$employeeNo'";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Employee deleted successfully";

        // Redirect to employee.php
        header("Location: employee.php");
        exit();
    } else {
        echo "Error deleting employee: " . $conn->error;
    }
} else {
    echo "Invalid request";
}

$conn->close();
?>
