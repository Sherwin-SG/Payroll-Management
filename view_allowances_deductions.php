<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Allowances/Deductions</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/attendance.css">

</head>

<body>
    
    <?php
    $servername = "localhost";
    $username = "admin";
    $password = "123";
    $database = "payroll";
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['allowance_submit'])) {
            // Process allowance form data
            $employee_id= $_POST['employee_id'];
            $employee = $_POST['employee_no'];
            $allowanceId = $_POST['allowance'];
            $type = $_POST['type'];
            $amount = $_POST['amount'];
            $effectiveDate = $_POST['effective_date'];
            $dateCreated = date('Y-m-d H:i:s');
    
            // Insert data into the database for allowances
            $sqlInsert = "INSERT INTO employee_allowances (employee_id, allowance_id, type, amount, effective_date, date_created)
                          VALUES ('$employee_id', '$allowanceId', '$type', '$amount', '$effectiveDate', '$dateCreated')";
    
            // Execute the query and handle success or error
            if ($conn->query($sqlInsert) === TRUE) {
                echo "Allowance record inserted successfully";
            } else {
                echo "Error: " . $sqlInsert . "<br>" . $conn->error;
            }
        } elseif (isset($_POST['deduction_submit'])) {
            // Process deduction form data
            $employee_id= $_POST['employee_id'];
            $employee = $_POST['employee_no'];
            $deductionId = $_POST['deduction'];
            $typeDeduction = $_POST['type'];
            $amountDeduction = $_POST['amount'];
            $effectiveDateDeduction = $_POST['effective_date'];
            $dateCreatedDeduction = date('Y-m-d H:i:s'); // Current date and time
    
            // Insert data into the database for deductions
            $sqlInsertDeduction = "INSERT INTO employee_deductions (employee_id, deduction_id, type, amount, effective_date, date_created)
                                   VALUES ('$employee_id', '$deductionId', '$typeDeduction', '$amountDeduction', '$effectiveDateDeduction', '$dateCreatedDeduction')";
    
            if ($conn->query($sqlInsertDeduction) === TRUE) {
                echo "Deduction record inserted successfully";
            } else {
                echo "Error: " . $sqlInsertDeduction . "<br>" . $conn->error;
            }
        } else {
            echo "Invalid form submission";
        }
    }  

    $employee = $_GET['employee_no'];
   

    // Retrieve employee details
    $sqlEmployee = "SELECT e.*, d.name AS department_name, p.name AS position_name
                    FROM employee e
                    LEFT JOIN department d ON e.department_id = d.id
                    LEFT JOIN position p ON e.position_id = p.id
                    WHERE e.employee_no = '$employee'";
    $resultEmployee = $conn->query($sqlEmployee);

    if ($resultEmployee->num_rows > 0) {
        $employee = $resultEmployee->fetch_assoc();
    ?>
        <h2>Allowances/Deductions for
            <?php echo $employee['firstname'] . ' ' . $employee['lastname']; ?>
        </h2>

        <!-- Display Employee Details -->
        <p><strong>Employee ID:</strong>
            <?php echo $employee['employee_no']; ?>
        </p>
        <p><strong>Department:</strong>
            <?php echo $employee['department_name']; ?>
        </p>
        <p><strong>Position:</strong>
            <?php echo $employee['position_name']; ?>
        </p>

        <!-- Display Allowances -->
        <h3>Allowances</h3>
        <?php
        $sqlAllowances = "SELECT ea.*, a.allowance
                        FROM employee_allowances ea
                        JOIN allowances a ON ea.allowance_id = a.id
                        WHERE ea.employee_id = " . $employee['id'];

        $resultAllowances = $conn->query($sqlAllowances);

        if ($resultAllowances->num_rows > 0) {
            echo "<ul>";
            while ($rowAllowance = $resultAllowances->fetch_assoc()) {
                echo "<li>{$rowAllowance['allowance']} - Type: {$rowAllowance['type']}, Amount: {$rowAllowance['amount']}, Effective Date: {$rowAllowance['effective_date']} 
                      <button onclick=\"deleteAllowance({$rowAllowance['id']})\">Delete</button></li>";
            }
            echo "</ul>";
        } else {
            echo "No allowances found.";
        }
        ?>

        <!-- Display Deductions -->
        <h3>Deductions</h3>
        <?php
        $sqlDeductions = "SELECT ed.*, d.deduction
                        FROM employee_deductions ed
                        JOIN deductions d ON ed.deduction_id = d.id
                        WHERE ed.employee_id = " . $employee['id'];

        $resultDeductions = $conn->query($sqlDeductions);

        if ($resultDeductions->num_rows > 0) {
            echo "<ul>";
            while ($rowDeduction = $resultDeductions->fetch_assoc()) {
                echo "<li>{$rowDeduction['deduction']} - Type: {$rowDeduction['type']}, Amount: {$rowDeduction['amount']}, Effective Date: {$rowDeduction['effective_date']} 
                      <button onclick=\"deleteDeduction({$rowDeduction['id']})\">Delete</button></li>";
            }
            echo "</ul>";
        } else {
            echo "No deductions found.";
        }
        ?>

        <!-- Add Allowance Button -->
        <button onclick="addAllowance('<?php echo $employee['employee_no']; ?>')">Add Allowance</button>


        <!-- Add Deduction Button -->
        <button onclick="addDeduction('<?php echo $employee['employee_no']; ?>')">Add Deduction</button>

        <button onclick="goBack()">Go Back</button>

        <script>
            function goBack() {
                window.location.href = 'employee.php' ;
            }

            function deleteAllowance(allowanceId) {
                if (confirm('Are you sure you want to delete this allowance?')) {
                    // Redirect to delete_allowance.php with the allowance ID as a parameter
                    window.location.href = 'delete_allowance.php?id=' + allowanceId;
                }
            }

            function deleteDeduction(deductionId) {
                if (confirm('Are you sure you want to delete this deduction?')) {
                    // Redirect to delete_deduction.php with the deduction ID as a parameter
                    window.location.href = 'delete_deduction.php?id=' + deductionId;
                }
            }

            function addAllowance(employee) {
    // Log the employee number to the console
    console.log("Adding allowance for employee number:", employee);

    // Redirect to a page where allowances and deductions can be selected for the specific employee
    window.location.href = 'add_allowances.php?employee_no=' + employee;
}

            function addDeduction(employee) {
                // Log the employee number to the console
    console.log("Adding allowance for employee number:", employee);
                // Redirect to a page to add a new deduction for the employee
                window.location.href = 'add_deduction.php?employee_no=' + employee;
            }
        </script>
    <?php
    } else {
        echo "Employee not found.";
    }  

    $conn->close();
    ?>
</body>

</html>
