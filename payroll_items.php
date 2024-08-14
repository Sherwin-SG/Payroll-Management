<?php
$servername = "localhost";
$username = "admin";
$password = "123";
$database = "payroll";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have collected the data through a form or some other means
if (isset($_GET['ref_no'])) {
    $ref_no = $_GET['ref_no'];

    // Retrieve id from the payroll table using ref_no
    $get_id_query = "SELECT id FROM payroll WHERE ref_no = ?";
    $stmt_id = $conn->prepare($get_id_query);
    $stmt_id->bind_param("s", $ref_no);
    $stmt_id->execute();
    $result_id = $stmt_id->get_result();

    if ($result_id === false) {
        echo "Error: " . $conn->error;
    } elseif ($result_id->num_rows > 0) {
        $row_id = $result_id->fetch_assoc();
        $payroll_id = $row_id['id'];

        // Continue with the rest of your code...
    } else {
        echo "Payroll not found for the given ref_no.";
    }

    $stmt_id->close();

    if ($payroll_id) {
        $get_employee_id_query = "SELECT id FROM employee WHERE employee_no = ?";
        $stmt_employee_id = $conn->prepare($get_employee_id_query);
        $stmt_employee_id->bind_param("s", $ref_no);
        $stmt_employee_id->execute();
        $result_employee_id = $stmt_employee_id->get_result();

        if ($result_employee_id->num_rows > 0) {
            $row = $result_employee_id->fetch_assoc();
            $employee_id = $row['id'];

            // Retrieve salary from the employee table
            $get_salary_query = "SELECT salary FROM employee WHERE id = ?";
            $stmt_salary = $conn->prepare($get_salary_query);
            $stmt_salary->bind_param("s", $employee_id);
            $stmt_salary->execute();
            $result_salary = $stmt_salary->get_result();

            if ($result_salary->num_rows > 0) {
                $row_salary = $result_salary->fetch_assoc();
                $salary = $row_salary['salary'];
            }

            $stmt_salary->close();

            // Retrieve and sum up allowance amounts and types from the employee_allowances table
            $get_allowance_query = "SELECT type, SUM(amount) AS total_allowance FROM employee_allowances WHERE employee_id = ? GROUP BY type";
            $stmt_allowance = $conn->prepare($get_allowance_query);
            $stmt_allowance->bind_param("s", $employee_id);
            $stmt_allowance->execute();
            $result_allowance = $stmt_allowance->get_result();

            $allowances = array();
            while ($row_allowance = $result_allowance->fetch_assoc()) {
                $allowance_type = $row_allowance['type'];
                $allowance_amount = $row_allowance['total_allowance'];
                $allowances[$allowance_type] = $allowance_amount;
            }

            $stmt_allowance->close();

            // Retrieve and sum up deduction amounts and types from the employee_deductions table
            $get_deduction_query = "SELECT type, SUM(amount) AS total_deduction FROM employee_deductions WHERE employee_id = ? GROUP BY type";
            $stmt_deduction = $conn->prepare($get_deduction_query);
            $stmt_deduction->bind_param("s", $employee_id);
            $stmt_deduction->execute();
            $result_deduction = $stmt_deduction->get_result();

            $deductions = array();
            while ($row_deduction = $result_deduction->fetch_assoc()) {
                $deduction_type = $row_deduction['type'];
                $deduction_amount = $row_deduction['total_deduction'];
                $deductions[$deduction_type] = $deduction_amount;
            }

            $stmt_deduction->close();

 // Calculate net based on your formula
$net = $salary + array_sum($allowances) - array_sum($deductions);

// Convert arrays to JSON
$json_allowances = json_encode($allowances);
$json_deductions = json_encode($deductions);

// Insert data into the payroll_items table
$insert_query = "INSERT INTO payroll_items (payroll_id, employee_id, salary, allowance_amount, allowances, deduction_amount, deductions, net, date_created) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt_insert = $conn->prepare($insert_query);
$stmt_insert->bind_param("ssssssss", $payroll_id, $employee_id, $salary, $allowance_amount, $json_allowances, $deduction_amount, $json_deductions, $net);

if ($stmt_insert->execute()) {
    echo "Record inserted successfully";
} else {
    echo "Error: " . $stmt_insert->error;
}

$stmt_insert->close();
        } else {
            echo "Employee not found in the payroll table for the given payroll_id.";
        }

        // Redirect to another page after successful record insertion
        header("Location: payrollList.php");
        exit(); // Make sure to exit to prevent further script execution
    }
} else {
    echo "Error: \$ref_no is not set.";
}

$conn->close();
?>