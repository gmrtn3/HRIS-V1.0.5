<?php
// Include ang configuration para sa database connection
include('config.php');

$departmentID = $_GET['departmentID'];

// Query para kunin ang mga empleyado base sa napiling departmentID
if ($departmentID == "All Department") {
    $sql = "SELECT empid, fname, lname FROM employee_tb";
} else {
    $sql = "SELECT empid, fname, lname FROM employee_tb WHERE department_name = '$departmentID'";
}

$result = mysqli_query($conn, $sql);

$employees = array();
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}

// I-encode ang result bilang JSON at i-echo
echo json_encode($employees);
?>
