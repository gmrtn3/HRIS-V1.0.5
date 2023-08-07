<?php
$conn = mysqli_connect("localhost", "root", "", "hris_db");

$query = "SELECT * FROM undertime_tb WHERE `status`='Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../undertime_req.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All or Reject All Button
if (isset($_POST['approve_all']) || isset($_POST['reject_all'])){
  $query = "SELECT DISTINCT `status` FROM undertime_tb WHERE `status`='Pending'";
  $result_pending = mysqli_query($conn, $query);

  if (mysqli_num_rows($result_pending) == 1) {
    $status = mysqli_fetch_assoc($result_pending)['status'];

    if ($status == 'Pending') {
        if (isset($_POST['approve_all'])) {
            $query = "UPDATE undertime_tb SET `status`='Approved' WHERE `status`='Pending'";
            mysqli_query($conn, $query);
        
            $msg = '';
            $error = false;
            $result = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `status`='Approved'");
        
            while ($row_under = mysqli_fetch_assoc($result)) {
                $employeeid = $row_under['empid'];
                $date_under = $row_under['date'];
                $starttime = $row_under['start_time'];
                $endtime = $row_under['end_time'];
                $total_undertime = $row_under['total_undertime'];
                $status_under = $row_under['status'];
            

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

                        $day_of_week = date('l', strtotime($date_under)); // get the day of the week using the "l" format specifier

                        if($day_of_week === 'Monday'){                        
                            if($endtime < $col_monday_timeout){
                                $time_out_datetime1 = new DateTime($endtime);
                                $scheduled_outs = new DateTime($col_monday_timeout);
                                $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                $early_out = $early_interval->format('%h:%i:%s');    
                            }
                            else{
                                $early_out = "00:00:00";
                            }
                            $total_work = strtotime($endtime) + strtotime($starttime) - 7200;
                            $total_work = date('H:i:s', $total_work); 

                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {

                                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                                    `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                    $inner_result = mysqli_query($conn, $sql);
                                }
                                if ($inner_result) {
                                    header("Location: ../../undertime_req.php?msg=You Approved all Request Successfully");
                                } else {
                                    header("Location: ../../undertime_req.php?error=Failed to update attendance records");
                                }
                            } else {
                                  $sql = "UPDATE undertime_tb SET `status`='Pending' WHERE `status`='Approved'";
                                  $results = mysqli_query($conn, $sql);
                            }
                            if($results){
                               header("Location: ../../undertime_req.php?error=The Employee Does Not Have Attendance for that date");
                            }
                     } //Close bracket Monday

                     else if($day_of_week === 'Tuesday'){                        
                        if($endtime < $col_tuesday_timeout){
                            $time_out_datetime1 = new DateTime($endtime);
                            $scheduled_outs = new DateTime($col_tuesday_timeout);
                            $early_interval = $scheduled_outs->diff($time_out_datetime1);
                            $early_out = $early_interval->format('%h:%i:%s');    
                        }
                        else{
                            $early_out = "00:00:00";
                        }
                        $total_work = strtotime($endtime) + strtotime($starttime) - 7200;
                        $total_work = date('H:i:s', $total_work); 

                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                        $query_run = mysqli_query($conn, $query);

                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {

                                $sql = "UPDATE attendances SET `time_out`='$endtime', 
                                `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                $inner_result = mysqli_query($conn, $sql);
                            }
                            if ($inner_result) {
                                header("Location: ../../undertime_req.php?msg=You Approved all Request Successfully");
                            } else {
                                header("Location: ../../undertime_req.php?error=Failed to update attendance records");
                            }
                        } else {
                              $sql = "UPDATE undertime_tb SET `status`='Pending' WHERE `status`='Approved'";
                              $results = mysqli_query($conn, $sql);
                        }
                        if($results){
                           header("Location: ../../undertime_req.php?error=The Employee Does Not Have Attendance for that date");
                        }
                 } //Close bracket Tuesday
                     

                        
          }
        }
      } //While loop Close bracket
    } //Approve all button close bracket 
        
        else {
        $query = "UPDATE undertime_tb SET `status`='Rejected' WHERE `status`='Pending'";
        $result = mysqli_query($conn, $query);

        if ($result) {
          header("Location: ../../undertime_req.php?msg=Rejected the All Request Successfully");
        } else {
          echo "Error updating status: " . mysqli_error($conn);
        }
      }
    } else {
      header("Location: ../../undertime_req.php?error=There are requests with different statuses.");
    }
    mysqli_close($conn);
  }
}

?>