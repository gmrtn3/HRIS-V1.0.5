<?php
session_start();
require_once '../../config.php';

$date = $_POST['name_STRdate'];
$employeeid = $_SESSION['empid'];

$sql = "SELECT empschedule_tb.id, employee_tb.empid, empschedule_tb.sched_from, empschedule_tb.sched_to, empschedule_tb.schedule_name,
        schedule_tb.mon_timein, schedule_tb.mon_timeout,
        schedule_tb.tues_timein, schedule_tb.tues_timeout,
        schedule_tb.wed_timein, schedule_tb.wed_timeout,
        schedule_tb.thurs_timein, schedule_tb.thurs_timeout,
        schedule_tb.fri_timein, schedule_tb.fri_timeout,
        schedule_tb.sat_timein, schedule_tb.sat_timeout,
        schedule_tb.sun_timein, schedule_tb.sun_timeout
        FROM empschedule_tb
        INNER JOIN employee_tb ON empschedule_tb.empid = employee_tb.empid
        INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
        WHERE empschedule_tb.sched_from <= '$date'
        AND empschedule_tb.sched_to >= '$date'
        AND (schedule_tb.mon_timein IS NOT NULL AND schedule_tb.mon_timeout IS NOT NULL)
        AND (schedule_tb.tues_timein IS NOT NULL AND schedule_tb.tues_timeout IS NOT NULL)
        AND (schedule_tb.wed_timein IS NOT NULL AND schedule_tb.wed_timeout IS NOT NULL)
        AND (schedule_tb.thurs_timein IS NOT NULL AND schedule_tb.thurs_timeout IS NOT NULL)
        AND (schedule_tb.fri_timein IS NOT NULL AND schedule_tb.fri_timeout IS NOT NULL)
        AND (schedule_tb.sat_timein IS NOT NULL AND schedule_tb.sat_timeout IS NOT NULL)
        AND (schedule_tb.sun_timein IS NOT NULL AND schedule_tb.sun_timeout IS NOT NULL)";

// Execute the query and check if a valid schedule is found
$result = mysqli_query($conn, $sql);
$response = array();
if (mysqli_num_rows($result) > 0) {
    $response['validSchedule'] = true;
} else {
    $response['validSchedule'] = false;
}

echo json_encode($response);
