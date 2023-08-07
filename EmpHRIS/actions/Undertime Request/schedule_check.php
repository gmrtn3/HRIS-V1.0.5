<?php
session_start();
require_once '../../config.php';

$date = $_POST['date'];
$employeeid = $_SESSION['empid'];

$sql = "SELECT empschedule_tb.id, employee_tb.empid, empschedule_tb.sched_from, empschedule_tb.sched_to, empschedule_tb.schedule_name, schedule_tb.mon_timein, schedule_tb.mon_timeout,
        schedule_tb.tues_timein, schedule_tb.tues_timeout,
        schedule_tb.wed_timein, schedule_tb.wed_timeout,
        schedule_tb.thurs_timein, schedule_tb.thurs_timeout,
        schedule_tb.fri_timein, schedule_tb.fri_timeout,
        schedule_tb.sat_timein, schedule_tb.sat_timeout,
        schedule_tb.sun_timein, schedule_tb.sun_timeout
        FROM
        empschedule_tb
        INNER JOIN employee_tb ON empschedule_tb.empid = employee_tb.empid
        INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
        WHERE employee_tb.empid = '$employeeid' AND '$date' BETWEEN empschedule_tb.sched_from AND empschedule_tb.sched_to;";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);

      $dayOfWeek = date('D', strtotime($date));
      switch($dayOfWeek) {
        case 'Mon':
          $timeIn = $row['mon_timein'];
          $timeOut = $row['mon_timeout'];
          break;
        case 'Tue':
          $timeIn = $row['tues_timein'];
          $timeOut = $row['tues_timeout'];
          break;
        case 'Wed':
          $timeIn = $row['wed_timein'];
          $timeOut = $row['wed_timeout'];
          break;
        case 'Thu':
          $timeIn = $row['thurs_timein'];
          $timeOut = $row['thurs_timeout'];
          break;
        case 'Fri':
          $timeIn = $row['fri_timein'];
          $timeOut = $row['fri_timeout'];
          break;
        case 'Sat':
          $timeIn = $row['sat_timein'];
          $timeOut = $row['sat_timeout'];
          break;
        case 'Sun':
          $timeIn = $row['sun_timein'];
          $timeOut = $row['sun_timeout'];
          break;
        default:
          $timeIn = '';
          $timeOut = '';
          break;
      }

  if (empty($timeIn) || empty($timeOut)) {
      echo json_encode(array(
            'schedule' => $date,
            'error' => true,
            'message' => "You don't have a time schedule for that day"
        ));
        exit;
    }
  echo json_encode(array(
        'schedule' => $date,
        'start_time' => $timeIn,
        'end_time' => $timeOut
    ));
} else {
    // Send error response
    echo json_encode(array(
        'error' => true,
        'message' => "Sorry you don't have a schedule for that day"
    ));
}
mysqli_close($conn);
?>