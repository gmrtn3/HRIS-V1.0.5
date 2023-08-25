<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "hris_db";

$conn = mysqli_connect($server, $user, $pass, $database);

$id = $_GET['id'];

// Select the schedule name before deleting
$sql_select = "SELECT schedule_name FROM `schedule_tb` WHERE id = ?";
$stmt_select = mysqli_prepare($conn, $sql_select);
mysqli_stmt_bind_param($stmt_select, "i", $id);
mysqli_stmt_execute($stmt_select);
mysqli_stmt_bind_result($stmt_select, $schedule_name);
mysqli_stmt_fetch($stmt_select);
mysqli_stmt_close($stmt_select);

// Delete the schedule from schedule_tb
$sql_delete_schedule = "DELETE FROM `schedule_tb` WHERE id = ?";
$stmt_delete_schedule = mysqli_prepare($conn, $sql_delete_schedule);
mysqli_stmt_bind_param($stmt_delete_schedule, "i", $id);

// Delete the corresponding employee schedules from empschedule_tb
$sql_delete_employee_schedules = "DELETE FROM `empschedule_tb` WHERE schedule_name = ?";
$stmt_delete_employee_schedules = mysqli_prepare($conn, $sql_delete_employee_schedules);
mysqli_stmt_bind_param($stmt_delete_employee_schedules, "s", $schedule_name);

// Execute the delete statements
mysqli_begin_transaction($conn);

if (mysqli_stmt_execute($stmt_delete_schedule) && mysqli_stmt_execute($stmt_delete_employee_schedules)) {
    mysqli_commit($conn);
    header("Location: ../../scheduleForm.php?msg=Record deleted successfully");
    exit();
} else {
    mysqli_rollback($conn);
    echo "Failed: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt_delete_schedule);
mysqli_stmt_close($stmt_delete_employee_schedules);
mysqli_close($conn);
?>
