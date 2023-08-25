<?php
// get_unit_types.php

// Include the configuration file and establish a database connection
include 'config.php';
$conn = mysqli_connect($server, $user, $pass, $database);

// Get the employee ID from the URL parameter 'empid'
$empid = isset($_GET['empid']) ? intval($_GET['empid']) : 0;

// Construct the SQL query to fetch the unit types for the selected employee
$sql = "SELECT piece_rate_tb.unit_type, piece_rate_tb.id AS piece_id FROM piece_rate_tb INNER JOIN employee_pakyawan_work_tb ON employee_pakyawan_work_tb.piece_rate_id = piece_rate_tb.id INNER JOIN employee_tb ON employee_pakyawan_work_tb.empid = employee_tb.empid WHERE employee_pakyawan_work_tb.empid = $empid;";

        


$result = mysqli_query($conn, $sql);

// Build the options for the "Unit Type" dropdown
$options = "";
while ($row = mysqli_fetch_assoc($result)) {
    $options .= "<option value='" . $row['piece_id'] . "'>" . $row['unit_type'] . "</option>";
}

// If no options are found, return a default option
if (empty($options)) {
    $options .= "<option value='' disabled>No unit types found</option>";
}

// Return the options to the JavaScript code
echo "<script> alert(".$row['id'].")</script>";
echo $options;

?>

