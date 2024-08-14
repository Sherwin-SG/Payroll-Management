<?php

$servername = "localhost";
$username = "admin";
$password = "123";
$database = "payroll";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the ref_no parameter is set in the URL
if (isset($_GET['ref_no'])) {
    $refNo = $_GET['ref_no'];

    // Fetch employee details using employee_no
    $employee_query = "SELECT * FROM employee WHERE employee_no = '$refNo'";
    $employee_result = $conn->query($employee_query);

    if ($employee_result->num_rows > 0) {
        $employee = $employee_result->fetch_assoc();

        // Fetch attendance details
        $attendance_query = "SELECT * FROM attendance WHERE employee_id = {$employee['id']} ORDER BY datetime_log";
        $attendance_result = $conn->query($attendance_query);

        $totalHoursWorked = 0;
        $lastLoginTime = null;

        if ($attendance_result->num_rows > 0) {
            while ($attendance = $attendance_result->fetch_assoc()) {
                $dateTimeLog = strtotime($attendance['datetime_log']);
                $type = $attendance['type'];

                // Check if it's a login type
                if ($type == 1) {
                    $lastLoginTime = $dateTimeLog;
                } elseif ($type == 2 && $lastLoginTime !== null) {
                    $logoutTime = $dateTimeLog;

                    $standardWorkStart = strtotime("9:00 AM");
                    $standardWorkEnd = strtotime("5:00 PM");

                    $actualWorkStart = max($lastLoginTime, $standardWorkStart);
                    $actualWorkEnd = min($logoutTime, $standardWorkEnd);

                    $workedHours = max(0, ($actualWorkEnd - $actualWorkStart) / 3600); // Convert seconds to hours

                    $totalHoursWorked += $workedHours;
                    $lastLoginTime = null;
                }
            }
        }

        // Calculate gross salary
        $grossSalary = $employee['salary'] * ($totalHoursWorked / 160); // Assuming 160 hours is a standard working month

        // Fetch deduction amount
        $deductionAmount = $employee['deduction_amount'];

        // Calculate net salary
        $netSalary = $grossSalary - $deductionAmount;

        // Display the result
        echo "<h2>Payroll Calculation for Employee ID: {$employee['id']}</h2>";
        echo "<p>Gross Salary: $grossSalary</p>";
        echo "<p>Deduction Amount: $deductionAmount</p>";
        echo "<p>Net Salary: $netSalary</p>";

        // Fetch and display payroll items for the employee
        $payrollItems_query = "SELECT * FROM payroll_items WHERE employee_id = {$employee['id']}";
        $payrollItems_result = $conn->query($payrollItems_query);

        if ($payrollItems_result->num_rows > 0) {
            echo "<h3>Payroll Items:</h3>";
            echo "<ul>";
            while ($payrollItem = $payrollItems_result->fetch_assoc()) {
                echo "<li>{$payrollItem['item_name']}: {$payrollItem['amount']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No payroll items found for Employee ID: {$employee['id']}</p>";
        }
    } else {
        echo "Employee not found with ID: $refNo.";
    }
}

$conn->close();
?>
