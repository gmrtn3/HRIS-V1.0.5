<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hris_db");
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/src/Exception.php';
require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/SMTP.php';

$query = "SELECT * FROM overtime_tb WHERE `status`= 'Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../overtime_req.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All
if (isset($_POST['approve_all'])){
  $msg = "";
  $error = false;

  $query = "SELECT * FROM overtime_tb WHERE `status` = 'Pending'";
  $result_pending = mysqli_query($conn, $query);

  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Manila'));
  $currentDateTime = $now->format('Y-m-d H:i:s');

  $approver_marks = $_POST['ot_approve_marks'];

  if(mysqli_num_rows($result_pending) > 0){
    while($ot_row = mysqli_fetch_assoc($result_pending)){
      $overtime_id = $ot_row['id'];
      $employeeid = $ot_row['empid'];
      $date_ot = $ot_row['work_schedule'];
      $starttime = $ot_row['time_in'];
      $endtime = $ot_row['time_out'];
      $overtimereq = $ot_row['ot_hours'];
      $total_overtime = $ot_row['total_ot'];
      $status_ot = $ot_row['status'];

      
      if($status_ot === 'Pending'){
            $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'");
            if (mysqli_num_rows($result_emp_sched) > 0) {
              $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
              $schedID = $row_emp_sched['schedule_name'];
    
              $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
              if (mysqli_num_rows($result_sched_tb) > 0) {
                $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                $sched_name =  $row_sched_tb['schedule_name'];
                $col_monday_timeout =  $row_sched_tb['mon_timeout'];
                $col_tuesday_timeout =  $row_sched_tb['tues_timeout'];
                $col_wednesday_timeout =  $row_sched_tb['wed_timeout'];
                $col_thursday_timeout =  $row_sched_tb['thurs_timeout'];
                $col_friday_timeout =  $row_sched_tb['fri_timeout'];
                $col_saturday_timeout =  $row_sched_tb['sat_timeout'];
                $col_sunday_timeout =  $row_sched_tb['sun_timeout'];
    
                $day_of_week = date('l', strtotime($date_ot)); // get the day of the week using the "l" format specifier
                
                        if ($day_of_week === 'Monday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);

                          if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                              $fetch_timein = $row['time_in'];
                              $fetch_late = $row['late'];

                              if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($overtimereq);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                            
                                //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start && $fetch_late != '00:00:00') {
                                        $overtime_total = $file_out->diff($existing_timein)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        $total_ot_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }else{
                                        $overtime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        // $total_under_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }
                            }else{
                                $total_work = "00:00:00";
                            }  

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                      `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                              $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE overtime_tb SET `status` = 'Approved', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_ot<br>";
                            }
                          } else {
                            $query = "UPDATE overtime_tb SET `status`= 'Pending', `ot_action_taken` = '0000-00-00 00:00:00', `ot_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Approved' AND `work_schedule` = '$date_ot'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_ot. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_ot<br>";
                            }
                          }
                        } //Monday Close Bracket

                        else if ($day_of_week === 'Tuesday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);

                          if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                              $fetch_timein = $row['time_in'];
                              $fetch_late = $row['late'];

                              if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($overtimereq);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);
                            
                                //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start && $fetch_late != '00:00:00') {
                                        $overtime_total = $file_out->diff($existing_timein)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        $total_ot_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }else{
                                        $overtime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        // $total_under_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }
                            }else{
                                $total_work = "00:00:00";
                            }  

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                      `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                              $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE overtime_tb SET `status` = 'Approved', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_ot<br>";
                            }
                          } else {
                            $query = "UPDATE overtime_tb SET `status`= 'Pending', `ot_action_taken` = '0000-00-00 00:00:00', `ot_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Approved' AND `work_schedule` = '$date_ot'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_ot. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_ot<br>";
                            }
                          }
                        } //Tuesday Close Bracket 

                        else if ($day_of_week === 'Wednesday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);

                          if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                              $fetch_timein = $row['time_in'];
                              $fetch_late = $row['late'];

                              if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($overtimereq);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);
                            
                                //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start && $fetch_late != '00:00:00') {
                                        $overtime_total = $file_out->diff($existing_timein)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        $total_ot_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }else{
                                        $overtime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        // $total_under_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }
                            }else{
                                $total_work = "00:00:00";
                            }  

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                      `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                              $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE overtime_tb SET `status` = 'Approved', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_ot<br>";
                            }
                          } else {
                            $query = "UPDATE overtime_tb SET `status`= 'Pending', `ot_action_taken` = '0000-00-00 00:00:00', `ot_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Approved' AND `work_schedule` = '$date_ot'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_ot. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_ot<br>";
                            }
                          }
                        } //Wednesday Close Bracket 

                        else if ($day_of_week === 'Thursday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);

                          if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                              $fetch_timein = $row['time_in'];
                              $fetch_late = $row['late'];

                              if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($overtimereq);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);
                            
                                //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start && $fetch_late != '00:00:00') {
                                        $overtime_total = $file_out->diff($existing_timein)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        $total_ot_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }else{
                                        $overtime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        // $total_under_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }
                            }else{
                                $total_work = "00:00:00";
                            }  

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                      `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                              $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE overtime_tb SET `status` = 'Approved', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_ot<br>";
                            }
                          } else {
                            $query = "UPDATE overtime_tb SET `status`= 'Pending', `ot_action_taken` = '0000-00-00 00:00:00', `ot_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Approved' AND `work_schedule` = '$date_ot'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_ot. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_ot<br>";
                            }
                          }
                        } //Thursday Close Bracket 

                        else if ($day_of_week === 'Friday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);

                          if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                              $fetch_timein = $row['time_in'];
                              $fetch_late = $row['late'];

                              if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($overtimereq);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);
                            
                                //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start && $fetch_late != '00:00:00') {
                                        $overtime_total = $file_out->diff($existing_timein)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        $total_ot_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }else{
                                        $overtime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        // $total_under_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }
                            }else{
                                $total_work = "00:00:00";
                            }  

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                      `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                              $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE overtime_tb SET `status` = 'Approved', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_ot<br>";
                            }
                          } else {
                            $query = "UPDATE overtime_tb SET `status`= 'Pending', `ot_action_taken` = '0000-00-00 00:00:00', `ot_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Approved' AND `work_schedule` = '$date_ot'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_ot. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_ot<br>";
                            }
                          }
                        } //Friday Close Bracket 

                        else if ($day_of_week === 'Saturday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);

                          if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                              $fetch_timein = $row['time_in'];
                              $fetch_late = $row['late'];

                              if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($overtimereq);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);
                            
                                //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start && $fetch_late != '00:00:00') {
                                        $overtime_total = $file_out->diff($existing_timein)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        $total_ot_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }else{
                                        $overtime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        // $total_under_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }
                            }else{
                                $total_work = "00:00:00";
                            }  

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                      `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                              $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE overtime_tb SET `status` = 'Approved', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_ot<br>";
                            }
                          } else {
                            $query = "UPDATE overtime_tb SET `status`= 'Pending', `ot_action_taken` = '0000-00-00 00:00:00', `ot_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Approved' AND `work_schedule` = '$date_ot'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_ot. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_ot<br>";
                            }
                          }
                        } //Saturday Close Bracket 

                        else if ($day_of_week === 'Sunday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);

                          if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                              $fetch_timein = $row['time_in'];
                              $fetch_late = $row['late'];

                              if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($overtimereq);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);
                            
                                //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start && $fetch_late != '00:00:00') {
                                        $overtime_total = $file_out->diff($existing_timein)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        $total_ot_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }else{
                                        $overtime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_ot_datetime = new DateTime($overtime_total);
                                        // $total_under_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_ot_datetime->format('H:i:s');
                                }
                            }else{
                                $total_work = "00:00:00";
                            }  

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                      `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot' AND `status` = 'Present'";
                              $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE overtime_tb SET `status` = 'Approved', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_ot<br>";
                            }
                          } else {
                            $query = "UPDATE overtime_tb SET `status`= 'Pending', `ot_action_taken` = '0000-00-00 00:00:00', `ot_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Approved' AND `work_schedule` = '$date_ot'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_ot. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_ot<br>";
                            }
                          }
                        } //Sunday Close Bracket 

                        else {
                          $error = true;
                          $msg .= "Invalid day of the week for employee ID: $employeeid<br>";
                        }

                        if ($error) {
                          header("Location: ../../overtime_req.php?error=$msg");
                        } else {
                          header("Location: ../../overtime_req.php?msg=$msg");
                          //Syntax sa email notification
                          $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                          $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                          
                          $EmpApproverArray = array();
                          while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                              $EmployeeApprover = $EmployeeRow['empid'];

                              $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                          }

                          foreach ($EmpApproverArray as $EmailOfEmployee) {
                          $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                          $selectOT = "SELECT * FROM overtime_tb WHERE id = '$overtime_id' AND empid = '$EmpMail'";  
                          $approvedOTRun = mysqli_query($conn, $selectOT);

                          $ApprovedArray = array();
                          while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                              $employeeApproved = $ApprovedRow['empid'];

                              $ApprovedArray[] = array('employeeApproved' => $employeeApproved);
                          }

                          foreach ($ApprovedArray as $ApprovedEmail) {
                              $EmpApprovedEmail = $ApprovedEmail['employeeApproved'];

                              $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$EmpApprovedEmail'";
                              $employeeRun = mysqli_query($conn, $employeeQuery);

                              $EmployeeEmail = mysqli_fetch_assoc($employeeRun);

                              $empid = $EmployeeEmail['empid'];
                              $fullname = $EmployeeEmail['fname'] . ' ' . $EmployeeEmail['lname'];


                              $to = $EmployeeEmail['email'];
                              $subject = "EMPLOYEE '$empid - $fullname' OVERTIME REQUEST";

                              $message = "
                              <html>
                              <head>
                              <title>{$subject}</title>
                              </head>
                              <body>
                              <p><strong>Dear $to,</strong></p>
                              <p>Your overtime request on $date_ot is approved</p>
                              </body>
                              </html>
                              ";
                              $mail = new PHPMailer(true);
                  
                              $mail->isSMTP();
                              $mail->Host = 'smtp.gmail.com';
                              $mail->SMTPAuth = true;
                              $mail->Username = 'hris.payroll.mailer@gmail.com'; //gmail name
                              $mail->Password = 'ndehozbugmfnhmes'; // app password
                              $mail->SMTPSecure = 'ssl';
                              $mail->Port = 465;
                          
                              $mail->setFrom('hris.payroll.mailer@gmail.com'); //gmail name
                          
                              $mail->addAddress($to);
                          
                              $mail->isHTML(true);
                          
                              $imgData = file_get_contents('../../img/Slash Tech Solutions.png');
                              $imgData64 = base64_encode($imgData);
                              $cid = md5(uniqid(time()));
                              $imgSrc = 'data:image/png;base64,' . $imgData64;
                              $mail->addEmbeddedImage('../../img/Slash Tech Solutions.png', $cid, 'Slash Tech Solutions.png');
                          
                              $mail->isHTML(true);                                  //Set email format to HTML
                              $mail->Subject = $subject;
                              $mail->Body    = $message;
                          
                              $mail->send();
                          }
                         }
                        }
              }else {
                $error = true;
                $msg .= "Schedule not found for employee ID: $employeeid<br>";
              }
            } else {
              $error = true;
              $msg .= "Schedule not found for employee ID: $employeeid<br>";
            }   

            if ($error) {
              header("Location: ../../overtime_req.php?error=$msg");
            } else {
              header("Location: ../../overtime_req.php?msg=$msg");
                //Syntax sa email notification
                $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                $GetApproverRun = mysqli_query($conn, $GetapproverQuery);

                $EmpApproverArray = array();
                while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                    $EmployeeApprover = $EmployeeRow['empid'];

                    $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                }

                foreach ($EmpApproverArray as $EmailOfEmployee) {
                $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                $selectOT = "SELECT * FROM overtime_tb WHERE id = '$overtime_id' AND empid = '$EmpMail'";  
                $approvedOTRun = mysqli_query($conn, $selectOT);

                $ApprovedArray = array();
                while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                    $employeeApproved = $ApprovedRow['empid'];

                    $ApprovedArray[] = array('employeeApproved' => $employeeApproved);
                }

                foreach ($ApprovedArray as $ApprovedEmail) {
                    $EmpApprovedEmail = $ApprovedEmail['employeeApproved'];

                    $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$EmpApprovedEmail'";
                    $employeeRun = mysqli_query($conn, $employeeQuery);

                    $EmployeeEmail = mysqli_fetch_assoc($employeeRun);

                    $empid = $EmployeeEmail['empid'];
                    $fullname = $EmployeeEmail['fname'] . ' ' . $EmployeeEmail['lname'];


                    $to = $EmployeeEmail['email'];
                    $subject = "EMPLOYEE '$empid - $fullname' OVERTIME REQUEST";

                    $message = "
                    <html>
                    <head>
                    <title>{$subject}</title>
                    </head>
                    <body>
                    <p><strong>Dear $to,</strong></p>
                    <p>Your overtime request on $date_ot is approved</p>
                    </body>
                    </html>
                    ";
                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'hris.payroll.mailer@gmail.com'; //gmail name
                    $mail->Password = 'ndehozbugmfnhmes'; // app password
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;

                    $mail->setFrom('hris.payroll.mailer@gmail.com'); //gmail name

                    $mail->addAddress($to);

                    $mail->isHTML(true);

                    $imgData = file_get_contents('../../img/Slash Tech Solutions.png');
                    $imgData64 = base64_encode($imgData);
                    $cid = md5(uniqid(time()));
                    $imgSrc = 'data:image/png;base64,' . $imgData64;
                    $mail->addEmbeddedImage('../../img/Slash Tech Solutions.png', $cid, 'Slash Tech Solutions.png');

                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = $subject;
                    $mail->Body    = $message;

                    $mail->send();
                  }
                }
            }
          }//Pending

    }
  }
}//Approve All button



?>