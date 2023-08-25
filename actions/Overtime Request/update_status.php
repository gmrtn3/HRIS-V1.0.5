<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");

$query = "SELECT * FROM overtime_tb WHERE `status`='Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../overtime_req.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All or Reject All Button
if (isset($_POST['approve_all']) || isset($_POST['reject_all'])){
  $query = "SELECT DISTINCT `status` FROM overtime_tb WHERE `status`='Pending'";
  $result_pending = mysqli_query($conn, $query);

  if (mysqli_num_rows($result_pending) == 1) {
    $status = mysqli_fetch_assoc($result_pending)['status'];

    if ($status == 'Pending') {
        if (isset($_POST['approve_all'])) {
            $query = "UPDATE overtime_tb SET `status`='Approved' WHERE `status`='Pending'";
            mysqli_query($conn, $query);
        
            $msg = '';
            $error = false;
            $result = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `status`='Approved'");
        
            while ($row_ot = mysqli_fetch_assoc($result)) {
                $employeeid = $row_ot['empid'];
                $choose_date = $row_ot['date'];
                $date_ot = $row_ot['work_schedule'];
                $starttime = $row_ot['time_in'];
                $endtime = $row_ot['time_out'];
                $timeout = $row_ot['out_time'];
                $overtimereq = $row_ot['ot_hours'];
                $total_overtime = $row_ot['total_ot'];
                $status_ot = $row_ot['status'];

                $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'");
                if(mysqli_num_rows($result_emp_sched) > 0) {
                $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                $schedID = $row_emp_sched['schedule_name'];
    
                $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
                    if(mysqli_num_rows($result_sched_tb) > 0) {
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

                          if($day_of_week === 'Monday'){                        
                            if($overtimereq > $col_monday_timeout){
                                $time_out_datetime = new DateTime($overtimereq);
                                $scheduled_timeout = new DateTime( $col_monday_timeout);
                                $intervals = $time_out_datetime->diff($scheduled_timeout);
                                $overtime = $intervals->format('%h:%i:%s');
                                echo $overtime;
                            }else{
                                $overtime = "00:00:00";
                            }
                            $total_work = strtotime($overtimereq) - strtotime($starttime) - 7200;
                            $total_work = date('H:i:s', $total_work);

                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
      
                                    $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                    `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                    $inner_result = mysqli_query($conn, $sql);
                                }
                                if ($inner_result) {
                                    header("Location: ../../overtime_req.php?msg=You Approved all Request Successfully");
                                } else {
                                    header("Location: ../../overtime_req.php?error=Failed to update attendance records");
                                }
                            } else {
                                  $sql = "UPDATE overtime_tb SET `status`='Pending' WHERE `status`='Approved'";
                                  $results = mysqli_query($conn, $sql);
                            }
                            if($results){
                               header("Location: ../../overtime_req.php?error=The Employee Does Not Have Attendance for that date");
                            }
                     } //Close bracket Monday

                     else if($day_of_week === 'Tuesday'){                        
                        if($overtimereq > $col_tuesday_timeout){
                            $time_out_datetime = new DateTime($overtimereq);
                            $scheduled_timeout = new DateTime( $col_tuesday_timeout);
                            $intervals = $time_out_datetime->diff($scheduled_timeout);
                            $overtime = $intervals->format('%h:%i:%s');
                            echo $overtime;
                        }else{
                            $overtime = "00:00:00";
                        }
                        $total_work = strtotime($overtimereq) - strtotime($starttime) - 7200;
                        $total_work = date('H:i:s', $total_work);

                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                        $query_run = mysqli_query($conn, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                          while ($row = mysqli_fetch_assoc($query_run)) {

                              $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                              `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                              $inner_result = mysqli_query($conn, $sql);
                          }
                          if ($inner_result) {
                              header("Location: ../../overtime_req.php?msg=You Approved all Request Successfully");
                          } else {
                              header("Location: ../../overtime_req.php?error=Failed to update attendance records");
                          }
                      } else {
                            $sql = "UPDATE overtime_tb SET `status`='Pending' WHERE `status`='Approved'";
                            $results = mysqli_query($conn, $sql);
                      }
                      if($results){
                         header("Location: ../../overtime_req.php?error=The Employee Does Not Have Attendance for that date");
                      }
                 } //Close bracket Tuesday

                          else if($day_of_week === 'Wednesday'){                        
                            if($overtimereq > $col_wednesday_timeout){
                                $time_out_datetime = new DateTime($overtimereq);
                                $scheduled_timeout = new DateTime( $col_wednesday_timeout);
                                $intervals = $time_out_datetime->diff($scheduled_timeout);
                                $overtime = $intervals->format('%h:%i:%s');
                                echo $overtime;
                            }else{
                                $overtime = "00:00:00";
                            }
                            $total_work = strtotime($overtimereq) - strtotime($starttime) - 7200;
                            $total_work = date('H:i:s', $total_work);

                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
      
                                    $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                    `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                    $inner_result = mysqli_query($conn, $sql);
                                }
                                if ($inner_result) {
                                    header("Location: ../../overtime_req.php?msg=You Approved all Request Successfully");
                                } else {
                                    header("Location: ../../overtime_req.php?error=Failed to update attendance records");
                                }
                            } else {
                                  $sql = "UPDATE overtime_tb SET `status`='Pending' WHERE `status`='Approved'";
                                  $results = mysqli_query($conn, $sql);
                            }
                            if($results){
                               header("Location: ../../overtime_req.php?error=The Employee Does Not Have Attendance for that date");
                            }
                    } //Close bracket Wednesday

                            else if($day_of_week === 'Thursday'){                        
                              if($overtimereq > $col_thursday_timeout){
                                  $time_out_datetime = new DateTime($overtimereq);
                                  $scheduled_timeout = new DateTime( $col_thursday_timeout);
                                  $intervals = $time_out_datetime->diff($scheduled_timeout);
                                  $overtime = $intervals->format('%h:%i:%s');
                                  echo $overtime;
                              }else{
                                  $overtime = "00:00:00";
                              }
                              $total_work = strtotime($overtimereq) - strtotime($starttime) - 7200;
                              $total_work = date('H:i:s', $total_work);

                              $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                              $query_run = mysqli_query($conn, $query);

                              if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
      
                                    $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                    `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                    $inner_result = mysqli_query($conn, $sql);
                                }
                                if ($inner_result) {
                                    header("Location: ../../overtime_req.php?msg=You Approved all Request Successfully");
                                } else {
                                    header("Location: ../../overtime_req.php?error=Failed to update attendance records");
                                }
                            } else {
                                  $sql = "UPDATE overtime_tb SET `status`='Pending' WHERE `status`='Approved'";
                                  $results = mysqli_query($conn, $sql);
                            }
                            if($results){
                               header("Location: ../../overtime_req.php?error=The Employee Does Not Have Attendance for that date");
                            }
                      } //Close bracket Thursday

                              else if($day_of_week === 'Friday'){                        
                                if($overtimereq > $col_friday_timeout){
                                    $time_out_datetime = new DateTime($overtimereq);
                                    $scheduled_timeout = new DateTime( $col_friday_timeout);
                                    $intervals = $time_out_datetime->diff($scheduled_timeout);
                                    $overtime = $intervals->format('%h:%i:%s');
                                    echo $overtime;
                                }else{
                                    $overtime = "00:00:00";
                                }
                                $total_work = strtotime($overtimereq) - strtotime($starttime) - 7200;
                                $total_work = date('H:i:s', $total_work);

                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                $query_run = mysqli_query($conn, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
          
                                        $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                        `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                        $inner_result = mysqli_query($conn, $sql);
                                    }
                                    if ($inner_result) {
                                        header("Location: ../../overtime_req.php?msg=You Approved all Request Successfully");
                                    } else {
                                        header("Location: ../../overtime_req.php?error=Failed to update attendance records");
                                    }
                                } else {
                                      $sql = "UPDATE overtime_tb SET `status`='Pending' WHERE `status`='Approved'";
                                      $results = mysqli_query($conn, $sql);
                                }
                                if($results){
                                   header("Location: ../../overtime_req.php?error=The Employee Does Not Have Attendance for that date");
                                }
                        } //Close bracket Friday

                                  else if($day_of_week === 'Saturday'){                        
                                    if($overtimereq > $col_saturday_timeout){
                                        $time_out_datetime = new DateTime($overtimereq);
                                        $scheduled_timeout = new DateTime( $col_saturday_timeout);
                                        $intervals = $time_out_datetime->diff($scheduled_timeout);
                                        $overtime = $intervals->format('%h:%i:%s');
                                        echo $overtime;
                                    }else{
                                        $overtime = "00:00:00";
                                    }
                                    $total_work = strtotime($overtimereq) - strtotime($starttime) - 7200;
                                    $total_work = date('H:i:s', $total_work);

                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                    $query_run = mysqli_query($conn, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        while ($row = mysqli_fetch_assoc($query_run)) {
              
                                            $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                            `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                            $inner_result = mysqli_query($conn, $sql);
                                        }
                                        if ($inner_result) {
                                            header("Location: ../../overtime_req.php?msg=You Approved all Request Successfully");
                                        } else {
                                            header("Location: ../../overtime_req.php?error=Failed to update attendance records");
                                        }
                                    } else {
                                          $sql = "UPDATE overtime_tb SET `status`='Pending' WHERE `status`='Approved'";
                                          $results = mysqli_query($conn, $sql);
                                    }
                                    if($results){
                                       header("Location: ../../overtime_req.php?error=The Employee Does Not Have Attendance for that date");
                                    }
                            } //Close bracket Saturday


                                    else if($day_of_week === 'Sunday'){                        
                                      if($overtimereq > $col_sunday_timeout){
                                          $time_out_datetime = new DateTime($overtimereq);
                                          $scheduled_timeout = new DateTime( $col_sunday_timeout);
                                          $intervals = $time_out_datetime->diff($scheduled_timeout);
                                          $overtime = $intervals->format('%h:%i:%s');
                                          echo $overtime;
                                      }else{
                                          $overtime = "00:00:00";
                                      }
                                      $total_work = strtotime($overtimereq) - strtotime($starttime) - 7200;
                                      $total_work = date('H:i:s', $total_work);

                                      $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                      $query_run = mysqli_query($conn, $query);

                                      if (mysqli_num_rows($query_run) > 0) {
                                        while ($row = mysqli_fetch_assoc($query_run)) {
              
                                            $sql = "UPDATE attendances SET `time_out`='$overtimereq', 
                                            `overtime`='$total_overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_ot'";
                                            $inner_result = mysqli_query($conn, $sql);
                                        }
                                        if ($inner_result) {
                                            header("Location: ../../overtime_req.php?msg=You Approved all Request Successfully");
                                        } else {
                                            header("Location: ../../overtime_req.php?error=Failed to update attendance records");
                                        }
                                    } else {
                                          $sql = "UPDATE overtime_tb SET `status`='Pending' WHERE `status`='Approved'";
                                          $results = mysqli_query($conn, $sql);
                                    }
                                    if($results){
                                       header("Location: ../../overtime_req.php?error=The Employee Does Not Have Attendance for that date");
                                    }
                              } //Close bracket Saturday
                      
          }
        }
      } //While loop Close bracket
    } //Approve all button close bracket 
        
        else {
        $query = "UPDATE overtime_tb SET `status`='Rejected' WHERE `status`='Pending'";
        $result = mysqli_query($conn, $query);

        if ($result) {
          header("Location: ../../overtime_req.php?msg=Rejected the All Request Successfully");
        } else {
          echo "Error updating status: " . mysqli_error($conn);
        }
      }
    } else {
      header("Location: ../../overtime_req.php?error=There are requests with different statuses.");
    }
    mysqli_close($conn);
  }
}

?>