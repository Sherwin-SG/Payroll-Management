<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/allowance.css">
    <style>
    .dropdown {
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        appearance: auto;
        -webkit-appearance: auto;
        -moz-appearance: auto;
    }
</style>

</head>
<?php

// select_allowances_deductions.php

$employee = $_GET['employee_no'];

$servername = "localhost";
$username = "admin";
$password = "123";
$database = "payroll";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Select Allowances/Deductions</title>
        <link rel="stylesheet" href="css/styles.css">
    </head>

    <body>
        <h2>Select Allowance for
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

        <form action="view_allowances_deductions.php?employee_no=<?php echo $employee['employee_no']; ?>&employee_id=<?php echo $employee['id']; ?>" method="post">


    <!-- Additional Information for Allowances -->
    <label for="type">Type:</label>
    <select name="type" class="dropdown">
        <option value="1">Monthly</option>
        <option value="2">Semi-Monthly</option>
        <option value="3">Once</option>
    </select>
    <br>

    <label for="amount">Amount:</label>
    <input type="text" name="amount">
    <br>

    <label for="effective_date">Effective Date:</label>
    <input type="date" name="effective_date">
    <br>

    <!-- Allowances Selection -->
    <label for="allowance">Select Allowance:</label>
    <select name="allowance">
        <?php
        $sqlAllowances = "SELECT * FROM allowances";
        $resultAllowances = $conn->query($sqlAllowances);
        if ($resultAllowances->num_rows > 0) {
            while ($rowAllowance = $resultAllowances->fetch_assoc()) {
                ?>
                <option value="<?php echo $rowAllowance['id']; ?>">
                    <?php echo $rowAllowance['allowance'] . ' - ' ?>
                </option>
                <?php
            }
        } else {
            echo "No allowances found.";
        }
        ?>
    </select>
    <br>

    <!-- Hidden Fields for Employee Information -->
    <input type="hidden" name="employee_no" value="<?php echo $employee['employee_no']; ?>">
    <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">

    <!-- Submit Button -->
    <input type="submit" name="allowance_submit" value="Submit Allowance">
</form>       

        <button onclick="goBack()">Go Back</button>

        <script>
            function goBack() {
                window.history.back();
            }
        </script>
    </body>

    </html>

    <?php
} else {
    echo "Employee not found.";
}

$conn->close();
?>