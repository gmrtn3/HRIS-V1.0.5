<?php
include 'config.php';
$conn = mysqli_connect($server, $user, $pass, $database);

$empid = $_GET['empid'];

$sql = "SELECT work_frequency FROM employee_tb WHERE empid = $empid";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo $row['work_frequency'];
} else {
    echo "Error fetching work frequency.";
}

mysqli_close($conn);

?>

