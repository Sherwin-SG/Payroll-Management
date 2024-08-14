<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sherwin Gonsalves Web Dev</title>
    <link rel="stylesheet" href="intro.css">
    <?php include('db_connect.php'); ?>
</head>

<body>
<div class="container">
    <a class="box home" href="intro.php">
        <div>Home</div>
    </a>
    <a class="box attendance" href="attendance.php">
        <div>Attendance</div>
    </a>
    <a class="box payroll" href="payrollList.php">
        <div>Payroll List</div>
    </a>
    <a class="box employee" href="employee.php">
        <div>Employee List</div>
    </a>
    <a class="box department" href="department.php">
        <div>Department List</div>
    </a>
    <a class="box position" href="position.php">
        <div>Position List</div>
    </a>
    <a class="box allowances" href="allowances.php">
        <div>Allowance List</div>
    </a>
    <a class="box deductions" href="deductions.php">
        <div>Deduction List</div>
    </a>
    <a class="box users" href="users.php">
        <div>User Management</div>
    </a>
    <a class="box logout" href="index.php">
        <div>Logout</div>
    </a>
</div>

   
</body>


</html>
