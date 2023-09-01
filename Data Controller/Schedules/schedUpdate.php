<?php
include '../../config.php';

$conn = mysqli_connect($server, $user, $pass, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$empid = $_POST['empid'];
$scheduleName = $_POST['schedule_name'];
$schedFromDate = $_POST['sched_from'];
$schedToDate = $_POST['sched_to'];

// Update the schedule in the database
$sql = "UPDATE empschedule_tb SET schedule_name = '$scheduleName', sched_from = '$schedFromDate', sched_to = '$schedToDate' WHERE empid = '$empid'";

if (mysqli_query($conn, $sql)) {
    header("Location: ../../Schedules");
} else {
    echo "Error updating schedule: " . mysqli_error($conn);
}


mysqli_close($conn);
?>
