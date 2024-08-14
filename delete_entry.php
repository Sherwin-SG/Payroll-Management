<?php
include('db_connect.php'); // Include your database connection file

if (isset($_POST['ref_no'])) {
    $ref_no = $_POST['ref_no'];

    // Perform the deletion query
    $delete_query = "DELETE FROM payroll WHERE ref_no = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param('s', $ref_no); // Assuming ref_no is a varchar, adjust if needed
    $stmt->execute();
    $stmt->close();

    // Check if the deletion was successful
    if ($conn->affected_rows > 0) {
        echo "Entry with Ref_No $ref_no deleted successfully.";
    } else {
        echo "Error deleting entry with Ref_No $ref_no.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
