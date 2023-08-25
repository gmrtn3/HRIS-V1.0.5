<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "hris_db";

$conn = mysqli_connect($server, $user, $pass, $database);

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