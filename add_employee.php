<link rel="stylesheet" href="css/add.css">
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

// Fetch department options
$departmentOptions = "";
$departmentQuery = "SELECT id, name FROM department";
$departmentResult = $conn->query($departmentQuery);

if ($departmentResult->num_rows > 0) {
    while ($row = $departmentResult->fetch_assoc()) {
        $departmentOptions .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
}

// Fetch position options
$positionOptions = "";
$positionQuery = "SELECT id, name FROM position";
$positionResult = $conn->query($positionQuery);

if ($positionResult->num_rows > 0) {
    while ($row = $positionResult->fetch_assoc()) {
        $positionOptions .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form was submitted, process the data

    // Collect form data
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $department_id = $_POST['department_id'];
    $position_id = $_POST['position_id'];
    $salary = $_POST['salary'];

    // Generate a random but unique employee number
    $employee_no = generateUniqueEmployeeNumber();

    // Insert new employee into the database
    $sql = "INSERT INTO employee (employee_no, firstname, middlename, lastname, department_id, position_id, salary) 
            VALUES ('$employee_no', '$firstname', '$middlename', '$lastname', '$department_id', '$position_id', '$salary')";

    if ($conn->query($sql) === TRUE) {
        echo "New employee added successfully";

        // Redirect to employee.php after successful insertion
        header("Location: employee.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // Display the form

    echo "
    <!DOCTYPE html>
    <html lang=\"en\">
    
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>Allowance Management - Add New Employee</title>
        <link rel=\"stylesheet\" href=\"css/styles.css\">
        
    </head>
    
    <body>
        <div class=\"container\">
    
            <!-- Navigation Sidebar - Assuming you have a similar sidebar structure as in the previous code -->
            <?php include 'navigation_sidebar.php'; ?>
    
            <div class=\"main\">
                <h2>Add New Employee</h2>
    
                <!-- Employee Form -->
                <form action=\"$_SERVER[PHP_SELF]\" method=\"post\">
                    <label for=\"firstname\">First Name:</label>
                    <input type=\"text\" name=\"firstname\" required><br>
    
                    <label for=\"middlename\">Middle Name:</label>
                    <input type=\"text\" name=\"middlename\"><br>
    
                    <label for=\"lastname\">Last Name:</label>
                    <input type=\"text\" name=\"lastname\" required><br>
    
                    <label for=\"department_id\">Department:</label>
                    <select name=\"department_id\" required>
                        $departmentOptions
                    </select><br>
    
                    <label for=\"position_id\">Position:</label>
                    <select name=\"position_id\" required>
                        $positionOptions
                    </select><br>
    
                    <label for=\"salary\">Salary:</label>
                    <input type=\"text\" name=\"salary\" required><br>
    
                    <input type=\"submit\" value=\"Add Employee\">
                </form>
            </div>
        </div>
    </body>
    
    </html>";
}

// Function to generate a random but unique employee number
function generateUniqueEmployeeNumber() {
    $uniqueId = uniqid('employee_no', true);
    return mt_rand(100000, 999999);
}
?>
