<?php
include 'config.php';

$selectedDepartment = $_GET['department'];

$sql = "SELECT * FROM employee_tb";

if ($selectedDepartment !== 'All') {
    $sql .= " WHERE department_name = '" . mysqli_real_escape_string($conn, $selectedDepartment) . "'";
}

$result = mysqli_query($conn, $sql);

$employees = array();
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}

echo json_encode($employees);
?>
