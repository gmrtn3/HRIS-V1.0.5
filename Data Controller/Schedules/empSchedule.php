<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "hris_db";

$conn = mysqli_connect($server, $user, $pass, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$empids = $_POST['empid'];
$scheduleNames = $_POST['schedule_name'];
$schedFromDates = $_POST['sched_from'];
$schedToDates = $_POST['sched_to'];

$count = count($empids);

$stmt = $conn->prepare("INSERT INTO empschedule_tb (`empid`, `schedule_name`, `sched_from`, `sched_to`) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE `schedule_name` = VALUES(`schedule_name`), `sched_from` = VALUES(`sched_from`), `sched_to` = VALUES(`sched_to`)");

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

for ($i = 0; $i < $count; $i++) {
    $empid = isset($empids[$i]) ? $empids[$i] : null;
    $scheduleName = isset($scheduleNames[$i]) ? $scheduleNames[$i] : null;
    $schedFromDate = isset($schedFromDates[$i]) ? $schedFromDates[$i] : null;
    $schedToDate = isset($schedToDates[$i]) ? $schedToDates[$i] : null;

    if (!empty($scheduleName)) {
        $stmt->bind_param("ssss", $empid, $scheduleName, $schedFromDate, $schedToDate);
        $stmt->execute();

        if ($stmt->errno) {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
            echo "<script>window.location.href = '../../Schedules';</script>";
            exit;
        }
    }
}

$stmt->close();

// Close the database connection
mysqli_close($conn);

// Redirect back to the form page after successful insertion/update
header("Location: ../../Schedules");
exit;
?>
