<?php
// getting_emp.php

$server = "localhost";
$user = "root";
$pass = "";
$database = "hris_db";

$conn = mysqli_connect($server, $user, $pass, $database);

$selectedDepartment = $_GET['department'];
echo "Selected Department: " . $selectedDepartment;

// Query the department to get its corresponding col_ID
$deptQuery = "SELECT col_ID FROM dept_tb WHERE col_deptname = '$selectedDepartment'";
$deptResult = mysqli_query($conn, $deptQuery);
$deptRow = mysqli_fetch_assoc($deptResult);
$departmentID = $deptRow['col_ID'];

// Properly quote the department value in the SQL query
$sql = "SELECT * FROM employee_tb WHERE department_name = '$departmentID'";
$result = mysqli_query($conn, $sql);

$options = "";
while ($row = mysqli_fetch_assoc($result)) {
    $options .= "<option value='".$row['empid'] . "'>". $row['empid'] . " ". " - ". " " .$row['fname']. " ".$row['lname']. "</option>";
}

echo $options;
?>
