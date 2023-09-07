<?php
include 'config.php';

if (isset($_GET['col_ID'])) {
    $selectedDepartment = $_GET['col_ID'];

    $sql = "SELECT empid, fname, lname FROM employee_tb WHERE department_name = '$selectedDepartment'";
    $result = mysqli_query($conn, $sql);

    $employeeData = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $employeeData[] = $row;
    }

    echo json_encode($employeeData);
}
?>