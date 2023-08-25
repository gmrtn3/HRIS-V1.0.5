<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");

$query = "SELECT * FROM wfh_tb WHERE `status`='Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../wfh_request.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All or Reject All Button
if (isset($_POST['approve_all']) || isset($_POST['reject_all'])) {
  $query = "SELECT DISTINCT `status` FROM wfh_tb WHERE `status`='Pending'";
  $result_pending = mysqli_query($conn, $query);

  if (mysqli_num_rows($result_pending) == 1) {
    $status = mysqli_fetch_assoc($result_pending)['status'];

    if ($status == 'Pending') {
        if (isset($_POST['approve_all'])) {
            $query = "UPDATE wfh_tb SET `status`='Approved' WHERE `status`='Pending' AND `start_time` IS NOT NULL";
            mysqli_query($conn, $query);
        
            $msg = '';
            $error = false;
            $result = mysqli_query($conn, "SELECT * FROM wfh_tb WHERE `status`='Approved'");
        
            while ($row_wfh = mysqli_fetch_assoc($result)) {
                $employeeid = $row_wfh['empid'];
                $choose_date = $row_wfh['date'];
                $starttime = $row_wfh['start_time'];
                $endtime = $row_wfh['end_time'];
                $status_ot = $row_wfh['status'];

                $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'");
                if(mysqli_num_rows($result_emp_sched) > 0) {
                $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                $schedID = $row_emp_sched['schedule_name'];
    
                $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
                    if(mysqli_num_rows($result_sched_tb) > 0) {
                        $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                        $sched_name =  $row_sched_tb['schedule_name'];
                        $col_monday_timein =  $row_sched_tb['mon_timein'];
                        $col_tuesday_timein =  $row_sched_tb['tues_timein'];
                        $col_wednesday_timein =  $row_sched_tb['wed_timein'];
                        $col_thursday_timein =  $row_sched_tb['thurs_timein'];
                        $col_friday_timein =  $row_sched_tb['fri_timein'];
                        $col_saturday_timein =  $row_sched_tb['sat_timein'];
                        $col_sunday_timein =  $row_sched_tb['sun_timein'];
                        $col_monday_timeout =  $row_sched_tb['mon_timeout'];
                        $col_tuesday_timeout =  $row_sched_tb['tues_timeout'];
                        $col_wednesday_timeout =  $row_sched_tb['wed_timeout'];
                        $col_thursday_timeout =  $row_sched_tb['thurs_timeout'];
                        $col_friday_timeout =  $row_sched_tb['fri_timeout'];
                        $col_saturday_timeout =  $row_sched_tb['sat_timeout'];
                        $col_sunday_timeout =  $row_sched_tb['sun_timeout'];

                        $day_of_week = date('l', strtotime($choose_date)); // get the day of the week using the "l" format specifier

                          if($day_of_week === 'Monday'){
                            $late = '';
                            if($starttime > $col_monday_timein){
                                $time_in_datetime = new DateTime($starttime);
                                $scheduled_time = new DateTime($col_monday_timein);
                                $interval = $time_in_datetime->diff($scheduled_time);
                                $late = $interval->format('%h:%i:%s');
                            }
                            if($endtime < $col_monday_timeout){
                                $time_out_datetime1 = new DateTime($endtime);
                                $scheduled_outs = new DateTime($col_monday_timeout);
                                $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                $early_out = $early_interval->format('%h:%i:%s');
                            }
                            else{
                                $early_out = "00:00:00";
                            }
                        
                            if($endtime > $col_monday_timeout){
                                $time_out_datetime = new DateTime($endtime);
                                $scheduled_timeout = new DateTime( $col_monday_timeout);
                                $intervals = $time_out_datetime->diff($scheduled_timeout);
                                $overtime = $intervals->format('%h:%i:%s');

                            }else{
                                $overtime = "00:00:00";
                            }
                            $total_work = strtotime($endtime) - strtotime($starttime) - 7200;
                            $total_work = date('H:i:s', $total_work);

                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$choose_date'";
                            $query_run = mysqli_query($conn, $query);
                    
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    $employeeid = $row['empid'];
                                    $choose_date = $row['date'];
                                    $sql = "UPDATE attendances SET `time_in`='$starttime', `time_out`='$endtime', `late`='$late', `early_out`='$early_out',
                                            `overtime`='$overtime', `total_work`='$total_work' WHERE `empid`='$employeeid' AND `date`='$choose_date'";
                                    $inner_result = mysqli_query($conn, $sql);
                                }
                                if ($inner_result) {
                                    header("Location: ../../wfh_request.php?msg=You Approved all Request Successfully");
                                } else {
                                    header("Location: ../../wfh_request.php?error=Failed to update attendance records");
                                }
                            } else {
                                header("Location: ../../wfh_request.php?error=The Employee Does Not Have Attendance for that date");
                            }
                     } //Close bracket Monday
                     
                     else if($day_of_week === 'Tuesday'){
                      $late = '';
                      if($starttime > $col_tuesday_timein){
                          $time_in_datetime = new DateTime($starttime);
                          $scheduled_time = new DateTime($col_tuesday_timein);
                          $interval = $time_in_datetime->diff($scheduled_time);
                          $late = $interval->format('%h:%i:%s');
                      }

                      if($endtime < $col_tuesday_timeout){
                          $time_out_datetime1 = new DateTime($endtime);
                          $scheduled_outs = new DateTime($col_tuesday_timeout);
                          $early_interval = $scheduled_outs->diff($time_out_datetime1);
                          $early_out = $early_interval->format('%h:%i:%s');
                      }
                      else{
                          $early_out = "00:00:00";
                      }
                  
                      if($endtime > $col_tuesday_timeout){
                          $time_out_datetime = new DateTime($endtime);
                          $scheduled_timeout = new DateTime( $col_tuesday_timeout);
                          $intervals = $time_out_datetime->diff($scheduled_timeout);
                          $overtime = $intervals->format('%h:%i:%s');
                      }else{
                          $overtime = "00:00:00";
                      }
                      $total_work = strtotime($endtime) - strtotime($starttime) - 7200;
                      $total_work = date('H:i:s', $total_work);
                      
                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$choose_date'";
                    $query_run = mysqli_query($conn, $query);
            
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            $employeeid = $row['empid'];
                            $choose_date = $row['date'];
                            $sql = "UPDATE attendances SET `time_in`='$starttime', `time_out`='$endtime', `late`='$late', `early_out`='$early_out',
                                    `overtime`='$overtime', `total_work`='$total_work' WHERE `empid`='$employeeid' AND `date`='$choose_date'";
                            $inner_result = mysqli_query($conn, $sql);
                        }
                        if ($inner_result) {
                            header("Location: ../../wfh_request.php?msg=You Approved All Request Successfully");
                        } else {
                            header("Location: ../../wfh_request.php?error=Failed to update attendance records");
                        }
                    } else {
                        header("Location: ../../wfh_request.php?error=The Employee Does Not Have Attendance for that date");
                    }
               } //Close bracket Tuesday

                                else if($day_of_week === 'Wednesday'){
                                    $late = '';
                                    if($starttime > $col_wednesday_timein){
                                        $time_in_datetime = new DateTime($starttime);
                                        $scheduled_time = new DateTime($col_wednesday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time);
                                        $late = $interval->format('%h:%i:%s');
                                    }

                                    if($endtime < $col_wednesday_timeout){
                                        $time_out_datetime1 = new DateTime($endtime);
                                        $scheduled_outs = new DateTime($col_wednesday_timeout);
                                        $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                        $early_out = $early_interval->format('%h:%i:%s');
                                    }
                                    else{
                                        $early_out = "00:00:00";
                                    }
                                
                                    if($endtime > $col_wednesday_timeout){
                                        $time_out_datetime = new DateTime($endtime);
                                        $scheduled_timeout = new DateTime( $col_wednesday_timeout);
                                        $intervals = $time_out_datetime->diff($scheduled_timeout);
                                        $overtime = $intervals->format('%h:%i:%s');
                                    }else{
                                        $overtime = "00:00:00";
                                    }
                                    $total_work = strtotime($endtime) - strtotime($starttime) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                    
                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$choose_date'";
                                $query_run = mysqli_query($conn, $query);
                        
                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        $employeeid = $row['empid'];
                                        $choose_date = $row['date'];
                                        $sql = "UPDATE attendances SET `time_in`='$starttime', `time_out`='$endtime', `late`='$late', `early_out`='$early_out',
                                                `overtime`='$overtime', `total_work`='$total_work' WHERE `empid`='$employeeid' AND `date`='$choose_date'";
                                        $inner_result = mysqli_query($conn, $sql);
                                    }
                                    if ($inner_result) {
                                        header("Location: ../../wfh_request.php?msg=You Approved All Request Successfully");
                                    } else {
                                        header("Location: ../../wfh_request.php?error=Failed to update attendance records");
                                    }
                                } else {
                                    header("Location: ../../wfh_request.php?error=The Employee Does Not Have Attendance for that date");
                                }
                            } //Close bracket Wednesday

                            else if($day_of_week === 'Thursday'){
                                $late = '';
                                if($starttime > $col_thursday_timein){
                                    $time_in_datetime = new DateTime($starttime);
                                    $scheduled_time = new DateTime($col_thursday_timein);
                                    $interval = $time_in_datetime->diff($scheduled_time);
                                    $late = $interval->format('%h:%i:%s');
                                }

                                if($endtime < $col_thursday_timeout){
                                    $time_out_datetime1 = new DateTime($endtime);
                                    $scheduled_outs = new DateTime($col_thursday_timeout);
                                    $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                    $early_out = $early_interval->format('%h:%i:%s');
                                }
                                else{
                                    $early_out = "00:00:00";
                                }
                            
                                if($endtime > $col_thursday_timeout){
                                    $time_out_datetime = new DateTime($endtime);
                                    $scheduled_timeout = new DateTime( $col_thursday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                }else{
                                    $overtime = "00:00:00";
                                }
                                $total_work = strtotime($endtime) - strtotime($starttime) - 7200;
                                $total_work = date('H:i:s', $total_work);
                                
                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$choose_date'";
                            $query_run = mysqli_query($conn, $query);
                    
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    $employeeid = $row['empid'];
                                    $choose_date = $row['date'];
                                    $sql = "UPDATE attendances SET `time_in`='$starttime', `time_out`='$endtime', `late`='$late', `early_out`='$early_out',
                                            `overtime`='$overtime', `total_work`='$total_work' WHERE `empid`='$employeeid' AND `date`='$choose_date'";
                                    $inner_result = mysqli_query($conn, $sql);
                                }
                                if ($inner_result) {
                                    header("Location: ../../wfh_request.php?msg=You Approved All Request Successfully");
                                } else {
                                    header("Location: ../../wfh_request.php?error=Failed to update attendance records");
                                }
                            } else {
                                header("Location: ../../wfh_request.php?error=The Employee Does Not Have Attendance for that date");
                            }
                        } //Close bracket Thursday

                                else if($day_of_week === 'Friday'){
                                    $late = '';
                                    if($starttime > $col_friday_timein){
                                        $time_in_datetime = new DateTime($starttime);
                                        $scheduled_time = new DateTime($col_friday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time);
                                        $late = $interval->format('%h:%i:%s');
                                    }

                                    if($endtime < $col_friday_timeout){
                                        $time_out_datetime1 = new DateTime($endtime);
                                        $scheduled_outs = new DateTime($col_friday_timeout);
                                        $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                        $early_out = $early_interval->format('%h:%i:%s');
                                    }
                                    else{
                                        $early_out = "00:00:00";
                                    }
                                
                                    if($endtime > $col_friday_timeout){
                                        $time_out_datetime = new DateTime($endtime);
                                        $scheduled_timeout = new DateTime( $col_friday_timeout);
                                        $intervals = $time_out_datetime->diff($scheduled_timeout);
                                        $overtime = $intervals->format('%h:%i:%s');
                                    }else{
                                        $overtime = "00:00:00";
                                    }
                                    $total_work = strtotime($endtime) - strtotime($starttime) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                    
                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$choose_date'";
                                $query_run = mysqli_query($conn, $query);
                        
                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        $employeeid = $row['empid'];
                                        $choose_date = $row['date'];
                                        $sql = "UPDATE attendances SET `time_in`='$starttime', `time_out`='$endtime', `late`='$late', `early_out`='$early_out',
                                                `overtime`='$overtime', `total_work`='$total_work' WHERE `empid`='$employeeid' AND `date`='$choose_date'";
                                        $inner_result = mysqli_query($conn, $sql);
                                    }
                                    if ($inner_result) {
                                        header("Location: ../../wfh_request.php?msg=You Approved All Request Successfully");
                                    } else {
                                        header("Location: ../../wfh_request.php?error=Failed to update attendance records");
                                    }
                                } else {
                                    header("Location: ../../wfh_request.php?error=The Employee Does Not Have Attendance for that date");
                                }
                            } //Close bracket Friday

                        else if($day_of_week === 'Saturday'){
                            $late = '';
                            if($starttime > $col_saturday_timein){
                                $time_in_datetime = new DateTime($starttime);
                                $scheduled_time = new DateTime($col_saturday_timein);
                                $interval = $time_in_datetime->diff($scheduled_time);
                                $late = $interval->format('%h:%i:%s');
                            }

                            if($endtime < $col_saturday_timeout){
                                $time_out_datetime1 = new DateTime($endtime);
                                $scheduled_outs = new DateTime($col_saturday_timeout);
                                $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                $early_out = $early_interval->format('%h:%i:%s');
                            }
                            else{
                                $early_out = "00:00:00";
                            }
                        
                            if($endtime > $col_saturday_timeout){
                                $time_out_datetime = new DateTime($endtime);
                                $scheduled_timeout = new DateTime( $col_saturday_timeout);
                                $intervals = $time_out_datetime->diff($scheduled_timeout);
                                $overtime = $intervals->format('%h:%i:%s');
                            }else{
                                $overtime = "00:00:00";
                            }
                            $total_work = strtotime($endtime) - strtotime($starttime) - 7200;
                            $total_work = date('H:i:s', $total_work);
                            
                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$choose_date'";
                        $query_run = mysqli_query($conn, $query);
                
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                $employeeid = $row['empid'];
                                $choose_date = $row['date'];
                                $sql = "UPDATE attendances SET `time_in`='$starttime', `time_out`='$endtime', `late`='$late', `early_out`='$early_out',
                                        `overtime`='$overtime', `total_work`='$total_work' WHERE `empid`='$employeeid' AND `date`='$choose_date'";
                                $inner_result = mysqli_query($conn, $sql);
                            }
                            if ($inner_result) {
                                header("Location: ../../wfh_request.php?msg=You Approved All Request Successfully");
                            } else {
                                header("Location: ../../wfh_request.php?error=Failed to update attendance records");
                            }
                        } else {
                            header("Location: ../../wfh_request.php?error=The Employee Does Not Have Attendance for that date");
                        }
                    } //Close bracket Saturday

                    else if($day_of_week === 'Sunday'){
                        $late = '';
                        if($starttime > $col_sunday_timein){
                            $time_in_datetime = new DateTime($starttime);
                            $scheduled_time = new DateTime($col_sunday_timein);
                            $interval = $time_in_datetime->diff($scheduled_time);
                            $late = $interval->format('%h:%i:%s');
                        }

                        if($endtime < $col_sunday_timeout){
                            $time_out_datetime1 = new DateTime($endtime);
                            $scheduled_outs = new DateTime($col_sunday_timeout);
                            $early_interval = $scheduled_outs->diff($time_out_datetime1);
                            $early_out = $early_interval->format('%h:%i:%s');
                        }
                        else{
                            $early_out = "00:00:00";
                        }
                    
                        if($endtime > $col_sunday_timeout){
                            $time_out_datetime = new DateTime($endtime);
                            $scheduled_timeout = new DateTime( $col_sunday_timeout);
                            $intervals = $time_out_datetime->diff($scheduled_timeout);
                            $overtime = $intervals->format('%h:%i:%s');
                        }else{
                            $overtime = "00:00:00";
                        }
                        $total_work = strtotime($endtime) - strtotime($starttime) - 7200;
                        $total_work = date('H:i:s', $total_work);
                        
                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$choose_date'";
                    $query_run = mysqli_query($conn, $query);
            
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            $employeeid = $row['empid'];
                            $choose_date = $row['date'];
                            $sql = "UPDATE attendances SET `time_in`='$starttime', `time_out`='$endtime', `late`='$late', `early_out`='$early_out',
                                    `overtime`='$overtime', `total_work`='$total_work' WHERE `empid`='$employeeid' AND `date`='$choose_date'";
                            $inner_result = mysqli_query($conn, $sql);
                        }
                        if ($inner_result) {
                            header("Location: ../../wfh_request.php?msg=You Approved All Request Successfully");
                        } else {
                            header("Location: ../../wfh_request.php?error=Failed to update attendance records");
                        }
                    } else {
                        header("Location: ../../wfh_request.php?error=The Employee Does Not Have Attendance for that date");
                    }
                } //Close bracket Saturday

          }
        }
      } //While loop Close bracket
    } //Approve all button close bracket 
        
        else {
        $query = "UPDATE wfh_tb SET `status`='Rejected' WHERE `status`='Pending'";
        $result = mysqli_query($conn, $query);

        if ($result) {
          header("Location: ../../wfh_request.php?msg=Rejected the All Request Successfully");
        } else {
          echo "Error updating status: " . mysqli_error($conn);
        }
      }
    } else {
      header("Location: ../../wfh_request.php?error=There are requests with different statuses.");
    }
    mysqli_close($conn);
  }
}

?>

