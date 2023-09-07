<?php
session_start();
include '../../config.php';
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';

$query = "SELECT * FROM undertime_tb WHERE `status`= 'Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../undertime_req.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All
if (isset($_POST['approve_all_ut'])){
  $msg = "";
  $error = false;

  $query = "SELECT * FROM undertime_tb WHERE `status` = 'Pending'";
  $result_pending = mysqli_query($conn, $query);

  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Manila'));
  $currentDateTime = $now->format('Y-m-d H:i:s');

  $ut_approver_marks = $_POST['ut_all_approve_marks']; //para sa remarks to

  if(mysqli_num_rows($result_pending) > 0){
    while ($row_under = mysqli_fetch_assoc($result_pending)) {
        $underID = $row_under['id'];
        $employeeid = $row_under['empid'];
        $date_under = $row_under['date'];
        $starttime = $row_under['start_time'];
        $endtime = $row_under['end_time'];
        $total_undertime = $row_under['total_undertime'];
        $status_under = $row_under['status'];

      if($status_under === 'Pending'){
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
    
                $day_of_week = date('l', strtotime($date_under)); // get the day of the week using the "l" format specifier
                
                        if ($day_of_week === 'Monday') {
                            $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                            $RunAtt = mysqli_query($conn, $CheckAtt);
                            
                            if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                                    $fetch_timein = $row['time_in'];
                                    $fetch_late = $row['late'];
                    
                            if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($endtime);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                                
                                    
                                    if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_end) {
                                            $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                            $total_under_datetime = new DateTime($undertime_total);
                                            $total_under_datetime->sub(new DateInterval('PT1H'));
                                            $total_work = $total_under_datetime->format('H:i:s');
                                    }else{
                                        $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                            // Subtract 1 hour from total work
                                            $total_under_datetime = new DateTime($undertime_total);
                                            $total_work = $total_under_datetime->format('H:i:s');
                                    }
                                }else{
                                    $total_work = "00:00:00";
                                }    

                              $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                               `early_out` = '$total_undertime', 
                               `total_work` = '$total_work' 
                                WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                               $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE undertime_tb SET `status` = 'Approved', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_under<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_under<br>";
                            }
                          } else {
                            $query = "UPDATE undertime_tb SET `status`= 'Pending', `ut_action_taken` = '0000-00-00 00:00:00', `ut_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Approved' AND `date` = '$date_under'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_under. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_under<br>";
                            }
                          }
                        } //Monday Close Bracket

                        else if ($day_of_week === 'Tuesday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                            $RunAtt = mysqli_query($conn, $CheckAtt);
                            
                            if (mysqli_num_rows($RunAtt) > 0) {
                            while ($row = mysqli_fetch_assoc($RunAtt)) {
                                    $fetch_timein = $row['time_in'];
                                    $fetch_late = $row['late'];
                    
                            if($fetch_timein != '00:00:00'){
                                $existing_timein = new DateTime($fetch_timein);
                                $file_out = new DateTime($endtime);
                                $late_datetime = new DateTime($fetch_late);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  

                                $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                                $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                                
                                    
                                    if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_end) {
                                            $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                            $total_under_datetime = new DateTime($undertime_total);
                                            $total_under_datetime->sub(new DateInterval('PT1H'));
                                            $total_work = $total_under_datetime->format('H:i:s');
                                    }else{
                                        $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                            // Subtract 1 hour from total work
                                            $total_under_datetime = new DateTime($undertime_total);
                                            $total_work = $total_under_datetime->format('H:i:s');
                                    }
                                }else{
                                    $total_work = "00:00:00";
                                }    

                              $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                               `early_out` = '$total_undertime', 
                               `total_work` = '$total_work' 
                                WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                               $inner_result = mysqli_query($conn, $sql);
                            }

                            if ($inner_result) {
                              $UpdateOT = "UPDATE undertime_tb SET `status` = 'Approved', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                              $RunOT = mysqli_query($conn, $UpdateOT);
                              if ($RunOT){
                                $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_under<br>";
                              }
                            } else {
                              $error = true;
                              $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_under<br>";
                            }
                          } else {
                            $query = "UPDATE undertime_tb SET `status`= 'Pending', `ut_action_taken` = '0000-00-00 00:00:00', `ut_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Approved' AND `date` = '$date_under'";
                            $inner_result = mysqli_query($conn, $query);

                            if ($inner_result) {
                              $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_under. Request remains Pending.<br>";
                            } else {
                              $error = true;
                              $msg .= "Failed to update status for employee ID: $employeeid on $date_under<br>";
                            }
                          }
                        } //Tuesday Close Bracket 

                        else if ($day_of_week === 'Wednesday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);
                          
                          if (mysqli_num_rows($RunAtt) > 0) {
                          while ($row = mysqli_fetch_assoc($RunAtt)) {
                                  $fetch_timein = $row['time_in'];
                                  $fetch_late = $row['late'];
                  
                          if($fetch_timein != '00:00:00'){
                              $existing_timein = new DateTime($fetch_timein);
                              $file_out = new DateTime($endtime);
                              $late_datetime = new DateTime($fetch_late);

                              $lunchbreak_start = new DateTime('12:00:00');
                              $lunchbreak_end = new DateTime('13:00:00');  

                              $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                              $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                              
                                  
                                  if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_end) {
                                          $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_under_datetime->sub(new DateInterval('PT1H'));
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }else{
                                      $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          // Subtract 1 hour from total work
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }
                              }else{
                                  $total_work = "00:00:00";
                              }    

                            $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                             `early_out` = '$total_undertime', 
                             `total_work` = '$total_work' 
                              WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                             $inner_result = mysqli_query($conn, $sql);
                          }

                          if ($inner_result) {
                            $UpdateOT = "UPDATE undertime_tb SET `status` = 'Approved', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            }
                          } else {
                            $error = true;
                            $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_under<br>";
                          }
                        } else {
                          $query = "UPDATE undertime_tb SET `status`= 'Pending', `ut_action_taken` = '0000-00-00 00:00:00', `ut_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Approved' AND `date` = '$date_under'";
                          $inner_result = mysqli_query($conn, $query);

                          if ($inner_result) {
                            $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_under. Request remains Pending.<br>";
                          } else {
                            $error = true;
                            $msg .= "Failed to update status for employee ID: $employeeid on $date_under<br>";
                          }
                        }
                      } //Wednesday Close Bracket 

                        else if ($day_of_week === 'Thursday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);
                          
                          if (mysqli_num_rows($RunAtt) > 0) {
                          while ($row = mysqli_fetch_assoc($RunAtt)) {
                                  $fetch_timein = $row['time_in'];
                                  $fetch_late = $row['late'];
                  
                          if($fetch_timein != '00:00:00'){
                              $existing_timein = new DateTime($fetch_timein);
                              $file_out = new DateTime($endtime);
                              $late_datetime = new DateTime($fetch_late);

                              $lunchbreak_start = new DateTime('12:00:00');
                              $lunchbreak_end = new DateTime('13:00:00');  

                              $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                              $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                              
                                  
                                  if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_end) {
                                          $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_under_datetime->sub(new DateInterval('PT1H'));
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }else{
                                      $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          // Subtract 1 hour from total work
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }
                              }else{
                                  $total_work = "00:00:00";
                              }    

                            $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                             `early_out` = '$total_undertime', 
                             `total_work` = '$total_work' 
                              WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                             $inner_result = mysqli_query($conn, $sql);
                          }

                          if ($inner_result) {
                            $UpdateOT = "UPDATE undertime_tb SET `status` = 'Approved', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            }
                          } else {
                            $error = true;
                            $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_under<br>";
                          }
                        } else {
                          $query = "UPDATE undertime_tb SET `status`= 'Pending', `ut_action_taken` = '0000-00-00 00:00:00', `ut_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Approved' AND `date` = '$date_under'";
                          $inner_result = mysqli_query($conn, $query);

                          if ($inner_result) {
                            $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_under. Request remains Pending.<br>";
                          } else {
                            $error = true;
                            $msg .= "Failed to update status for employee ID: $employeeid on $date_under<br>";
                          }
                        }
                      } //Thursday Close Bracket 

                        else if ($day_of_week === 'Friday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);
                          
                          if (mysqli_num_rows($RunAtt) > 0) {
                          while ($row = mysqli_fetch_assoc($RunAtt)) {
                                  $fetch_timein = $row['time_in'];
                                  $fetch_late = $row['late'];
                  
                          if($fetch_timein != '00:00:00'){
                              $existing_timein = new DateTime($fetch_timein);
                              $file_out = new DateTime($endtime);
                              $late_datetime = new DateTime($fetch_late);

                              $lunchbreak_start = new DateTime('12:00:00');
                              $lunchbreak_end = new DateTime('13:00:00');  

                              $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                              $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                              
                                  
                                  if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_end) {
                                          $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_under_datetime->sub(new DateInterval('PT1H'));
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }else{
                                      $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          // Subtract 1 hour from total work
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }
                              }else{
                                  $total_work = "00:00:00";
                              }    

                            $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                             `early_out` = '$total_undertime', 
                             `total_work` = '$total_work' 
                              WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                             $inner_result = mysqli_query($conn, $sql);
                          }

                          if ($inner_result) {
                            $UpdateOT = "UPDATE undertime_tb SET `status` = 'Approved', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            }
                          } else {
                            $error = true;
                            $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_under<br>";
                          }
                        } else {
                          $query = "UPDATE undertime_tb SET `status`= 'Pending', `ut_action_taken` = '0000-00-00 00:00:00', `ut_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Approved' AND `date` = '$date_under'";
                          $inner_result = mysqli_query($conn, $query);

                          if ($inner_result) {
                            $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_under. Request remains Pending.<br>";
                          } else {
                            $error = true;
                            $msg .= "Failed to update status for employee ID: $employeeid on $date_under<br>";
                          }
                        }
                      } //Friday Close Bracket 

                        else if ($day_of_week === 'Saturday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);
                          
                          if (mysqli_num_rows($RunAtt) > 0) {
                          while ($row = mysqli_fetch_assoc($RunAtt)) {
                                  $fetch_timein = $row['time_in'];
                                  $fetch_late = $row['late'];
                  
                          if($fetch_timein != '00:00:00'){
                              $existing_timein = new DateTime($fetch_timein);
                              $file_out = new DateTime($endtime);
                              $late_datetime = new DateTime($fetch_late);

                              $lunchbreak_start = new DateTime('12:00:00');
                              $lunchbreak_end = new DateTime('13:00:00');  

                              $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                              $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                              
                                  
                                  if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_end) {
                                          $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_under_datetime->sub(new DateInterval('PT1H'));
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }else{
                                      $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          // Subtract 1 hour from total work
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }
                              }else{
                                  $total_work = "00:00:00";
                              }    

                            $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                             `early_out` = '$total_undertime', 
                             `total_work` = '$total_work' 
                              WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                             $inner_result = mysqli_query($conn, $sql);
                          }

                          if ($inner_result) {
                            $UpdateOT = "UPDATE undertime_tb SET `status` = 'Approved', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            }
                          } else {
                            $error = true;
                            $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_under<br>";
                          }
                        } else {
                          $query = "UPDATE undertime_tb SET `status`= 'Pending', `ut_action_taken` = '0000-00-00 00:00:00', `ut_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Approved' AND `date` = '$date_under'";
                          $inner_result = mysqli_query($conn, $query);

                          if ($inner_result) {
                            $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_under. Request remains Pending.<br>";
                          } else {
                            $error = true;
                            $msg .= "Failed to update status for employee ID: $employeeid on $date_under<br>";
                          }
                        }
                       } //Saturday Close Bracket 

                        else if ($day_of_week === 'Sunday') {
                          $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                          $RunAtt = mysqli_query($conn, $CheckAtt);
                          
                          if (mysqli_num_rows($RunAtt) > 0) {
                          while ($row = mysqli_fetch_assoc($RunAtt)) {
                                  $fetch_timein = $row['time_in'];
                                  $fetch_late = $row['late'];
                  
                          if($fetch_timein != '00:00:00'){
                              $existing_timein = new DateTime($fetch_timein);
                              $file_out = new DateTime($endtime);
                              $late_datetime = new DateTime($fetch_late);

                              $lunchbreak_start = new DateTime('12:00:00');
                              $lunchbreak_end = new DateTime('13:00:00');  

                              $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                              $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                              
                                  
                                  if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_end) {
                                          $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_under_datetime->sub(new DateInterval('PT1H'));
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }else{
                                      $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                          // Subtract 1 hour from total work
                                          $total_under_datetime = new DateTime($undertime_total);
                                          $total_work = $total_under_datetime->format('H:i:s');
                                  }
                              }else{
                                  $total_work = "00:00:00";
                              }    

                            $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                             `early_out` = '$total_undertime', 
                             `total_work` = '$total_work' 
                              WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                             $inner_result = mysqli_query($conn, $sql);
                          }

                          if ($inner_result) {
                            $UpdateOT = "UPDATE undertime_tb SET `status` = 'Approved', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            }
                          } else {
                            $error = true;
                            $msg .= "Failed to update attendance records for employee ID: $employeeid on $date_under<br>";
                          }
                        } else {
                          $query = "UPDATE undertime_tb SET `status`= 'Pending', `ut_action_taken` = '0000-00-00 00:00:00', `ut_remarks` = '' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Approved' AND `date` = '$date_under'";
                          $inner_result = mysqli_query($conn, $query);

                          if ($inner_result) {
                            $msg .= "The Employee ID: $employeeid Does Not Have Attendance on $date_under. Request remains Pending.<br>";
                          } else {
                            $error = true;
                            $msg .= "Failed to update status for employee ID: $employeeid on $date_under<br>";
                          }
                        }
                      } //Sunday Close Bracket 

                        else {
                          $error = true;
                          $msg .= "Invalid day of the week for employee ID: $employeeid<br>";
                        }

                        if ($error) {
                          header("Location: ../../undertime_req.php?error=$msg");
                        } else {
                          header("Location: ../../undertime_req.php?msg=$msg");
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

                          $selectOT = "SELECT * FROM undertime_tb WHERE id = '$underID' AND empid = '$EmpMail'";  
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
                              $subject = "EMPLOYEE '$empid - $fullname' UNDERTIME REQUEST";

                              $message = "
                              <html>
                              <head>
                              <title>{$subject}</title>
                              </head>
                              <body>
                              <p><strong>Dear $to,</strong></p>
                              <p>Your undertime request on $date_under is approved</p>
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
              header("Location: ../../undertime_req.php?error=$msg");
            } else {
              header("Location: ../../undertime_req.php?msg=$msg");
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

                  $selectOT = "SELECT * FROM undertime_tb WHERE id = '$underID' AND empid = '$EmpMail'";  
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
                      $subject = "EMPLOYEE '$empid - $fullname' UNDERTIME REQUEST";

                      $message = "
                      <html>
                      <head>
                      <title>{$subject}</title>
                      </head>
                      <body>
                      <p><strong>Dear $to,</strong></p>
                      <p>Your undertime request on $date_under is approved</p>
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