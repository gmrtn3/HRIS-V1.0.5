<?php

include 'config.php';
// SQL query to select data from employee_tb
$query = "SELECT * FROM employee_tb";

// Execute the query
$result = $conn->query($query);

// Check if there are results
if ($result->num_rows > 0) {
    // Create a CSV file
    $filename = "employee_data.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');

    // Add CSV header row
    fputcsv($output, array('empid', 'fname', 'mname', 'lname', 'contact', 'emal'));

    // Add data rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
} else {
    echo "No data found in the database.";
}

// Close the database connection
$conn->close();

?>