<?php
// updateLoanRequest.php

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Make sure the status and ID are provided in the form submission
    if (isset($_POST['status']) && isset($_POST['id'])) {
        $status = $_POST['status'];
        $id = $_POST['id'];

        // Assuming you have a database connection established already
        $conn = new mysqli('localhost', 'root', '', 'hris_db');
        if ($conn->connect_error) {
            die('Connection Failed: ' . $conn->connect_error);
        }

        // Prepare and execute the SQL update statement
        $stmt = $conn->prepare("UPDATE payroll_loan_tb SET `status` = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    }
}

// Redirect back to the admin table after updating
header("Location: ../../loanRequest");
exit();
?>
