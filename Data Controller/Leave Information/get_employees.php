<?php

// For fetching employee depends what department selected

// Check if the department parameter is set
if (isset($_GET['department'])) {
    
    include '../../config.php';
    $selectedDept = $_GET['department'];

    // Prepare a SQL query to retrieve employees based on the selected department
    $sql = "SELECT empid, fname, lname FROM employee_tb WHERE department_name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $selectedDept);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the employees into an array
    $employees = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $employees[] = $row;
    }

    // Return the employees as JSON
    echo json_encode($employees);
} else {
    // If the department parameter is not set, return an empty response or an error message
    echo "No department selected";
}
?>
