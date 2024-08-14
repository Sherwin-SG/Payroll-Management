<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Allowances/Deductions</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/allowances-deductions.css">
</head>

<body>
    <div class="container">
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
                $employee_id = $_POST['employee_id'];
                $allowanceId = $_POST['allowance'];
                $type = $_POST['type'];
                $amount = $_POST['amount'];
                $effectiveDate = $_POST['effective_date'];
                $dateCreated = date('Y-m-d H:i:s');
    
                $sqlInsert = "INSERT INTO employee_allowances (employee_id, allowance_id, type, amount, effective_date, date_created)
                              VALUES ('$employee_id', '$allowanceId', '$type', '$amount', '$effectiveDate', '$dateCreated')";
    
                if ($conn->query($sqlInsert) === TRUE) {
                    echo "<p class='success'>Allowance record inserted successfully</p>";
                } else {
                    echo "<p class='error'>Error: " . $sqlInsert . "<br>" . $conn->error . "</p>";
                }
            } elseif (isset($_POST['deduction_submit'])) {
                $employee_id = $_POST['employee_id'];
                $deductionId = $_POST['deduction'];
                $typeDeduction = $_POST['type'];
                $amountDeduction = $_POST['amount'];
                $effectiveDateDeduction = $_POST['effective_date'];
                $dateCreatedDeduction = date('Y-m-d H:i:s');
    
                $sqlInsertDeduction = "INSERT INTO employee_deductions (employee_id, deduction_id, type, amount, effective_date, date_created)
                                       VALUES ('$employee_id', '$deductionId', '$typeDeduction', '$amountDeduction', '$effectiveDateDeduction', '$dateCreatedDeduction')";
    
                if ($conn->query($sqlInsertDeduction) === TRUE) {
                    echo "<p class='success'>Deduction record inserted successfully</p>";
                } else {
                    echo "<p class='error'>Error: " . $sqlInsertDeduction . "<br>" . $conn->error . "</p>";
                }
            } else {
                echo "<p class='error'>Invalid form submission</p>";
            }
        }

        $employee = $_GET['employee_no'];
    
        $sqlEmployee = "SELECT e.*, d.name AS department_name, p.name AS position_name
                        FROM employee e
                        LEFT JOIN department d ON e.department_id = d.id
                        LEFT JOIN position p ON e.position_id = p.id
                        WHERE e.employee_no = '$employee'";
        $resultEmployee = $conn->query($sqlEmployee);

        if ($resultEmployee->num_rows > 0) {
            $employee = $resultEmployee->fetch_assoc();
        ?>
            <div class="employee-details">
                <h2>Allowances/Deductions for <?php echo htmlspecialchars($employee['firstname'] . ' ' . $employee['lastname']); ?></h2>

                <p><strong>Employee ID:</strong> <?php echo htmlspecialchars($employee['employee_no']); ?></p>
                <p><strong>Department:</strong> <?php echo htmlspecialchars($employee['department_name']); ?></p>
                <p><strong>Position:</strong> <?php echo htmlspecialchars($employee['position_name']); ?></p>
            </div>

            <div class="allowances-deductions">
                <h3>Allowances</h3>
                <?php
                $sqlAllowances = "SELECT ea.*, a.allowance
                                  FROM employee_allowances ea
                                  JOIN allowances a ON ea.allowance_id = a.id
                                  WHERE ea.employee_id = " . $employee['id'];

                $resultAllowances = $conn->query($sqlAllowances);

                if ($resultAllowances->num_rows > 0) {
                    echo "<ul class='allowances-list'>";
                    while ($rowAllowance = $resultAllowances->fetch_assoc()) {
                        echo "<li>{$rowAllowance['allowance']} - Type: {$rowAllowance['type']}, Amount: {$rowAllowance['amount']}, Effective Date: {$rowAllowance['effective_date']}
                              <button class='delete-button' onclick=\"deleteAllowance({$rowAllowance['id']})\">Delete</button></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No allowances found.</p>";
                }
                ?>

                <h3>Deductions</h3>
                <?php
                $sqlDeductions = "SELECT ed.*, d.deduction
                                  FROM employee_deductions ed
                                  JOIN deductions d ON ed.deduction_id = d.id
                                  WHERE ed.employee_id = " . $employee['id'];

                $resultDeductions = $conn->query($sqlDeductions);

                if ($resultDeductions->num_rows > 0) {
                    echo "<ul class='deductions-list'>";
                    while ($rowDeduction = $resultDeductions->fetch_assoc()) {
                        echo "<li>{$rowDeduction['deduction']} - Type: {$rowDeduction['type']}, Amount: {$rowDeduction['amount']}, Effective Date: {$rowDeduction['effective_date']}
                              <button class='delete-button' onclick=\"deleteDeduction({$rowDeduction['id']})\">Delete</button></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<p>No deductions found.</p>";
                }
                ?>
            </div>

            <div class="action-buttons">
                <button class="action-button" onclick="addAllowance('<?php echo htmlspecialchars($employee['employee_no']); ?>')">Add Allowance</button>
                <button class="action-button" onclick="addDeduction('<?php echo htmlspecialchars($employee['employee_no']); ?>')">Add Deduction</button>
                <button class="action-button" onclick="goBack()">Go Back</button>
            </div>

            <script>
                function goBack() {
                    window.location.href = 'employee.php';
                }

                function deleteAllowance(allowanceId) {
                    if (confirm('Are you sure you want to delete this allowance?')) {
                        window.location.href = 'delete_allowance.php?id=' + allowanceId;
                    }
                }

                function deleteDeduction(deductionId) {
                    if (confirm('Are you sure you want to delete this deduction?')) {
                        window.location.href = 'delete_deduction.php?id=' + deductionId;
                    }
                }

                function addAllowance(employee) {
                    window.location.href = 'add_allowances.php?employee_no=' + employee;
                }

                function addDeduction(employee) {
                    window.location.href = 'add_deduction.php?employee_no=' + employee;
                }
            </script>
        <?php
        } else {
            echo "<p class='error'>Employee not found.</p>";
        }  

        $conn->close();
        ?>
    </div>
</body>

</html>
