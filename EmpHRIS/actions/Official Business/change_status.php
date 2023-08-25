<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");

$query = "SELECT * FROM emp_official_tb WHERE `status`='Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../official_business.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All or Reject All Button
if (isset($_POST['approve_all']) || isset($_POST['reject_all'])) {
  $query = "SELECT DISTINCT `status` FROM emp_official_tb WHERE `status`='Pending'";
  $result_pending = mysqli_query($conn, $query);

  if (mysqli_num_rows($result_pending) == 1) {
    $status = mysqli_fetch_assoc($result_pending)['status'];

    if ($status == 'Pending') {
        if (isset($_POST['approve_all'])) {
            $query = "UPDATE emp_official_tb SET `status`='Approved' WHERE `status`='Pending' AND `start_time` IS NOT NULL";
            mysqli_query($conn, $query);
        
            $msg = '';
            $error = false;
            $result = mysqli_query($conn, "SELECT * FROM emp_official_tb WHERE `status`='Approved'");
        
            while ($row_official = mysqli_fetch_assoc($result)) {
                $employeeid = $row_official['employee_id'];
                $date_official_start = $row_official['str_date'];
                $date_official_end = $row_official ['end_date'];
                $starttime_official = $row_official['start_time'];
                $endtime_official = $row_official['end_time'];
                $status_official = $row_official['status'];

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

                        $start_date = new DateTime($date_official_start);
                        $end_date = new DateTime($date_official_end);
                        $interval = new DateInterval('P1D'); // 1 day interval
                        $daterange = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // Include end date

                        foreach ($daterange as $date) {
                          $date_range = $date->format('l');
                          $date_str = $date->format('Y-m-d');

                          if($date_range === 'Monday'){
                            $late = '';
                            if($starttime_official > $col_monday_timein){
                                $time_in_datetime = new DateTime($starttime_official);
                                $scheduled_time = new DateTime($col_monday_timein);
                                $interval = $time_in_datetime->diff($scheduled_time);
                                $late = $interval->format('%h:%i:%s');
                                echo $late;
                            }
    
                            if($endtime_official < $col_monday_timeout){
                                $time_out_datetime1 = new DateTime($endtime_official);
                                $scheduled_outs = new DateTime($col_monday_timeout);
                                $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                $early_out = $early_interval->format('%h:%i:%s');
                                echo $early_out;
                            }
                            else{
                                $early_out = "00:00:00";
                            }
                        
                            if($endtime_official > $col_monday_timeout){
                                $time_out_datetime = new DateTime($endtime_official);
                                $scheduled_timeout = new DateTime( $col_monday_timeout);
                                $intervals = $time_out_datetime->diff($scheduled_timeout);
                                $overtime = $intervals->format('%h:%i:%s');
                                echo $overtime;
                            }else{
                                $overtime = "00:00:00";
                            }
                            $total_work = strtotime($endtime_official) - strtotime($starttime_official) - 7200;
                            $total_work = date('H:i:s', $total_work);
                            echo $total_work;

                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) VALUES ('Present', '$employeeid', '$date_official', '$starttime_official', '$endtime_official', '$late', '$early_out', '$overtime', '$total_work')";
                            $result_attendance = mysqli_query($conn, $sql);
                    
                            if (!$result_attendance) {
                                $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                $error = true;
                                break; 
                            } 
                            if (!$error) {
                                $msg = "You approved all requests successfully.";
                              }
                              header("Location: ../../official_business.php?msg=$msg");
                     } //Close bracket Monday
                     
                     else if($date_range === 'Tuesday'){
                      $late = '';
                      if($starttime_official > $col_tuesday_timein){
                          $time_in_datetime = new DateTime($starttime_official);
                          $scheduled_time = new DateTime($col_tuesday_timein);
                          $interval = $time_in_datetime->diff($scheduled_time);
                          $late = $interval->format('%h:%i:%s');
                          echo $late;
                      }

                      if($endtime_official < $col_tuesday_timeout){
                          $time_out_datetime1 = new DateTime($endtime_official);
                          $scheduled_outs = new DateTime($col_tuesday_timeout);
                          $early_interval = $scheduled_outs->diff($time_out_datetime1);
                          $early_out = $early_interval->format('%h:%i:%s');
                          echo $early_out;
                      }
                      else{
                          $early_out = "00:00:00";
                      }
                  
                      if($endtime_official > $col_tuesday_timeout){
                          $time_out_datetime = new DateTime($endtime_official);
                          $scheduled_timeout = new DateTime( $col_tuesday_timeout);
                          $intervals = $time_out_datetime->diff($scheduled_timeout);
                          $overtime = $intervals->format('%h:%i:%s');
                          echo $overtime;
                      }else{
                          $overtime = "00:00:00";
                      }
                      $total_work = strtotime($endtime_official) - strtotime($starttime_official) - 7200;
                      $total_work = date('H:i:s', $total_work);
                      echo $total_work;

                      $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) VALUES ('Present', '$employeeid', '$date_official', '$starttime_official', '$endtime_official', '$late', '$early_out', '$overtime', '$total_work')";
                      $result_attendance = mysqli_query($conn, $sql);
              
                      if (!$result_attendance) {
                          $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                          $error = true;
                          break; 
                      } 
                      if (!$error) {
                          $msg = "You approved all requests successfully.";
                        }
                        header("Location: ../../official_business.php?msg=$msg");
               } //Close bracket Tuesday

                        else if($date_range === 'Wednesday'){
                          $late = '';
                          if($starttime_official > $col_wednesday_timein){
                              $time_in_datetime = new DateTime($starttime_official);
                              $scheduled_time = new DateTime($col_wednesday_timein);
                              $interval = $time_in_datetime->diff($scheduled_time);
                              $late = $interval->format('%h:%i:%s');
                              echo $late;
                          }

                          if($endtime_official < $col_wednesday_timeout){
                              $time_out_datetime1 = new DateTime($endtime_official);
                              $scheduled_outs = new DateTime($col_wednesday_timeout);
                              $early_interval = $scheduled_outs->diff($time_out_datetime1);
                              $early_out = $early_interval->format('%h:%i:%s');
                              echo $early_out;
                          }
                          else{
                              $early_out = "00:00:00";
                          }
                      
                          if($endtime_official > $col_wednesday_timeout){
                              $time_out_datetime = new DateTime($endtime_official);
                              $scheduled_timeout = new DateTime( $col_wednesday_timeout);
                              $intervals = $time_out_datetime->diff($scheduled_timeout);
                              $overtime = $intervals->format('%h:%i:%s');
                              echo $overtime;
                          }else{
                              $overtime = "00:00:00";
                          }
                          $total_work = strtotime($endtime_official) - strtotime($starttime_official) - 7200;
                          $total_work = date('H:i:s', $total_work);
                          echo $total_work;

                          $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) VALUES ('Present', '$employeeid', '$date_official', '$starttime_official', '$endtime_official', '$late', '$early_out', '$overtime', '$total_work')";
                          $result_attendance = mysqli_query($conn, $sql);
                  
                          if (!$result_attendance) {
                              $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                              $error = true;
                              break; 
                          } 
                          if (!$error) {
                              $msg = "You approved all requests successfully.";
                            }
                            header("Location: ../../official_business.php?msg=$msg");
                    } //Close bracket Wednesday

                    else if($date_range === 'Thursday'){
                      $late = '';
                      if($starttime_official > $col_thursday_timein){
                          $time_in_datetime = new DateTime($starttime_official);
                          $scheduled_time = new DateTime($col_thursday_timein);
                          $interval = $time_in_datetime->diff($scheduled_time);
                          $late = $interval->format('%h:%i:%s');
                          echo $late;
                      }

                      if($endtime_official < $col_thursday_timeout){
                          $time_out_datetime1 = new DateTime($endtime_official);
                          $scheduled_outs = new DateTime($col_thursday_timeout);
                          $early_interval = $scheduled_outs->diff($time_out_datetime1);
                          $early_out = $early_interval->format('%h:%i:%s');
                          echo $early_out;
                      }
                      else{
                          $early_out = "00:00:00";
                      }
                  
                      if($endtime_official > $col_thursday_timeout){
                          $time_out_datetime = new DateTime($endtime_official);
                          $scheduled_timeout = new DateTime( $col_thursday_timeout);
                          $intervals = $time_out_datetime->diff($scheduled_timeout);
                          $overtime = $intervals->format('%h:%i:%s');
                          echo $overtime;
                      }else{
                          $overtime = "00:00:00";
                      }
                      $total_work = strtotime($endtime_official) - strtotime($starttime_official) - 7200;
                      $total_work = date('H:i:s', $total_work);
                      echo $total_work;

                      $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) VALUES ('Present', '$employeeid', '$date_official', '$starttime_official', '$endtime_official', '$late', '$early_out', '$overtime', '$total_work')";
                      $result_attendance = mysqli_query($conn, $sql);
              
                      if (!$result_attendance) {
                          $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                          $error = true;
                          break; 
                      } 
                      if (!$error) {
                          $msg = "You approved all requests successfully.";
                        }
                        header("Location: ../../official_business.php?msg=$msg");
                } //Close bracket Thursday

                      else if($date_range === 'Friday'){
                        $late = '';
                        if($starttime_official > $col_friday_timein){
                            $time_in_datetime = new DateTime($starttime_official);
                            $scheduled_time = new DateTime($col_friday_timein);
                            $interval = $time_in_datetime->diff($scheduled_time);
                            $late = $interval->format('%h:%i:%s');
                            echo $late;
                        }

                        if($endtime_official < $col_friday_timeout){
                            $time_out_datetime1 = new DateTime($endtime_official);
                            $scheduled_outs = new DateTime($col_friday_timeout);
                            $early_interval = $scheduled_outs->diff($time_out_datetime1);
                            $early_out = $early_interval->format('%h:%i:%s');
                            echo $early_out;
                        }
                        else{
                            $early_out = "00:00:00";
                        }
                    
                        if($endtime_official > $col_friday_timeout){
                            $time_out_datetime = new DateTime($endtime_official);
                            $scheduled_timeout = new DateTime( $col_friday_timeout);
                            $intervals = $time_out_datetime->diff($scheduled_timeout);
                            $overtime = $intervals->format('%h:%i:%s');
                            echo $overtime;
                        }else{
                            $overtime = "00:00:00";
                        }
                        $total_work = strtotime($endtime_official) - strtotime($starttime_official) - 7200;
                        $total_work = date('H:i:s', $total_work);
                        echo $total_work;

                        $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) VALUES ('Present', '$employeeid', '$date_official', '$starttime_official', '$endtime_official', '$late', '$early_out', '$overtime', '$total_work')";
                        $result_attendance = mysqli_query($conn, $sql);
                
                        if (!$result_attendance) {
                            $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                            $error = true;
                            break; 
                        } 
                        if (!$error) {
                            $msg = "You approved all requests successfully.";
                          }
                          header("Location: ../../official_business.php?msg=$msg");
                  } //Close bracket Friday

                            else if($date_range === 'Saturday'){
                              $late = '';
                              if($starttime_official > $col_saturday_timein){
                                  $time_in_datetime = new DateTime($starttime_official);
                                  $scheduled_time = new DateTime($col_saturday_timein);
                                  $interval = $time_in_datetime->diff($scheduled_time);
                                  $late = $interval->format('%h:%i:%s');
                                  echo $late;
                              }

                              if($endtime_official < $col_saturday_timeout){
                                  $time_out_datetime1 = new DateTime($endtime_official);
                                  $scheduled_outs = new DateTime($col_saturday_timeout);
                                  $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                  $early_out = $early_interval->format('%h:%i:%s');
                                  echo $early_out;
                              }
                              else{
                                  $early_out = "00:00:00";
                              }
                          
                              if($endtime_official > $col_saturday_timeout){
                                  $time_out_datetime = new DateTime($endtime_official);
                                  $scheduled_timeout = new DateTime( $col_saturday_timeout);
                                  $intervals = $time_out_datetime->diff($scheduled_timeout);
                                  $overtime = $intervals->format('%h:%i:%s');
                                  echo $overtime;
                              }else{
                                  $overtime = "00:00:00";
                              }
                              $total_work = strtotime($endtime_official) - strtotime($starttime_official) - 7200;
                              $total_work = date('H:i:s', $total_work);
                              echo $total_work;

                              $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) VALUES ('Present', '$employeeid', '$date_official', '$starttime_official', '$endtime_official', '$late', '$early_out', '$overtime', '$total_work')";
                              $result_attendance = mysqli_query($conn, $sql);
                      
                              if (!$result_attendance) {
                                  $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                  $error = true;
                                  break; 
                              } 
                              if (!$error) {
                                  $msg = "You approved all requests successfully.";
                                }
                                header("Location: ../../official_business.php?msg=$msg");
                        } //Close bracket Saturday

                        else if($date_range === 'Sunday'){
                          $late = '';
                          if($starttime_official > $col_sunday_timein){
                              $time_in_datetime = new DateTime($starttime_official);
                              $scheduled_time = new DateTime($col_sunday_timein);
                              $interval = $time_in_datetime->diff($scheduled_time);
                              $late = $interval->format('%h:%i:%s');
                              echo $late;
                          }

                          if($endtime_official < $col_sunday_timeout){
                              $time_out_datetime1 = new DateTime($endtime_official);
                              $scheduled_outs = new DateTime($col_sunday_timeout);
                              $early_interval = $scheduled_outs->diff($time_out_datetime1);
                              $early_out = $early_interval->format('%h:%i:%s');
                              echo $early_out;
                          }
                          else{
                              $early_out = "00:00:00";
                          }
                      
                          if($endtime_official > $col_sunday_timeout){
                              $time_out_datetime = new DateTime($endtime_official);
                              $scheduled_timeout = new DateTime( $col_sunday_timeout);
                              $intervals = $time_out_datetime->diff($scheduled_timeout);
                              $overtime = $intervals->format('%h:%i:%s');
                              echo $overtime;
                          }else{
                              $overtime = "00:00:00";
                          }
                          $total_work = strtotime($endtime_official) - strtotime($starttime_official) - 7200;
                          $total_work = date('H:i:s', $total_work);
                          echo $total_work;

                          $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) VALUES ('Present', '$employeeid', '$date_official', '$starttime_official', '$endtime_official', '$late', '$early_out', '$overtime', '$total_work')";
                          $result_attendance = mysqli_query($conn, $sql);
                  
                          if (!$result_attendance) {
                              $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                              $error = true;
                              break; 
                          } 
                          if (!$error) {
                              $msg = "You approved all requests successfully.";
                            }
                            header("Location: ../../official_business.php?msg=$msg");
                    } //Close bracket Sunday

            } //Foreach Close bracket
          }
        }
      } //While loop Close bracket
    } //Approve all button close bracket 
        
        else {
        $query = "UPDATE emp_official_tb SET `status`='Rejected' WHERE `status`='Pending'";
        $result = mysqli_query($conn, $query);

        if ($result) {
          header("Location: ../../official_business.php?msg=Rejected the All Request Successfully");
        } else {
          echo "Error updating status: " . mysqli_error($conn);
        }
      }
    } else {
      header("Location: ../../official_business.php?error=There are requests with different statuses.");
    }
    mysqli_close($conn);
  }
}

?>
