<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hris_db";


    $conn = mysqli_connect($servername, $username,  $password, $dbname);

    if(isset($_POST['approve_btn']))
{

    $column_id = $_POST['id_check'];

    $result_under = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE id = '$column_id'");
    if(mysqli_num_rows($result_under) > 0) {
        $row_under = mysqli_fetch_assoc($result_under);
    }
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

                if(mysqli_num_rows($query_run) > 0) {
                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                    `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                    $result = mysqli_query($conn, $sql);
                if($result){
                        $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                        $query_run = mysqli_query($conn, $sql);
                            if($query_run){
                                header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                            }else{
                                echo "Failed: " . mysqli_error($conn);
                            } 
                    } else {
                        echo "Failed: " . mysqli_error($conn);
                    }      
                } else {
                    header("Location: ../../undertime_req.php?error=The Employee Does not have attendance for $date_under");
                }
            } //Monday Close Tag

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
                
                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND (status = 'Absent' || status = 'LWOP' || status = 'On-Leave')";
                $query_run = mysqli_query($conn, $query);

                if(mysqli_num_rows($query_run) > 0) {
                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                    `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                    $result = mysqli_query($conn, $sql);
                if($result){
                        $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                        $query_run = mysqli_query($conn, $sql);
                            if($query_run){
                                header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                            }else{
                                echo "Failed: " . mysqli_error($conn);
                            } 
                    } else {
                        echo "Failed: " . mysqli_error($conn);
                    }      
                } else {
                    header("Location: ../../undertime_req.php?error=The Employee Does not have attendance for $date_under");
                }
            } //Tuesday Close Tag

            else if($day_of_week === 'Wednesday'){      
                if($endtime < $col_wednesday_timeout){
                    $time_out_datetime1 = new DateTime($endtime);
                    $scheduled_outs = new DateTime($col_wednesday_timeout);
                    $early_interval = $scheduled_outs->diff($time_out_datetime1);
                    $early_out = $early_interval->format('%h:%i:%s');
                }
                else{
                    $early_out = "00:00:00";
                }
                $total_work = strtotime($endtime) + strtotime($starttime) - 7200;
                $total_work = date('H:i:s', $total_work);
                
                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND (status = 'Absent' || status = 'LWOP' || status = 'On-Leave')";
                $query_run = mysqli_query($conn, $query);

                if(mysqli_num_rows($query_run) > 0) {
                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                    `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                    $result = mysqli_query($conn, $sql);
                if($result){
                        $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                        $query_run = mysqli_query($conn, $sql);
                            if($query_run){
                                header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                            }else{
                                echo "Failed: " . mysqli_error($conn);
                            } 
                    } else {
                        echo "Failed: " . mysqli_error($conn);
                    }      
                } else {
                    header("Location: ../../undertime_req.php?error=The Employee Does not have attendance for $date_under");
                }
            } //Wednesday Close Tag

            else if($day_of_week === 'Thursday'){      
                if($endtime < $col_thursday_timeout){
                    $time_out_datetime1 = new DateTime($endtime);
                    $scheduled_outs = new DateTime($col_thursday_timeout);
                    $early_interval = $scheduled_outs->diff($time_out_datetime1);
                    $early_out = $early_interval->format('%h:%i:%s');
                }
                else{
                    $early_out = "00:00:00";
                }
                $total_work = strtotime($endtime) + strtotime($starttime) - 7200;
                $total_work = date('H:i:s', $total_work);
                
                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND (status = 'Absent' || status = 'LWOP' || status = 'On-Leave')";
                $query_run = mysqli_query($conn, $query);

                if(mysqli_num_rows($query_run) > 0) {
                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                    `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                    $result = mysqli_query($conn, $sql);
                if($result){
                        $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                        $query_run = mysqli_query($conn, $sql);
                            if($query_run){
                                header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                            }else{
                                echo "Failed: " . mysqli_error($conn);
                            } 
                    } else {
                        echo "Failed: " . mysqli_error($conn);
                    }      
                } else {
                    header("Location: ../../undertime_req.php?error=The Employee Does not have attendance for $date_under");
                }
            } //Thursday Close Tag

            else if($day_of_week === 'Friday'){      
                if($endtime < $col_friday_timeout){
                    $time_out_datetime1 = new DateTime($endtime);
                    $scheduled_outs = new DateTime($col_friday_timeout);
                    $early_interval = $scheduled_outs->diff($time_out_datetime1);
                    $early_out = $early_interval->format('%h:%i:%s');
                }
                else{
                    $early_out = "00:00:00";
                }
                $total_work = strtotime($endtime) + strtotime($starttime) - 7200;
                $total_work = date('H:i:s', $total_work);
                
                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND (status = 'Absent' || status = 'LWOP' || status = 'On-Leave')";
                $query_run = mysqli_query($conn, $query);

                if(mysqli_num_rows($query_run) > 0) {
                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                    `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                    $result = mysqli_query($conn, $sql);
                if($result){
                        $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                        $query_run = mysqli_query($conn, $sql);
                            if($query_run){
                                header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                            }else{
                                echo "Failed: " . mysqli_error($conn);
                            } 
                    } else {
                        echo "Failed: " . mysqli_error($conn);
                    }      
                } else {
                    header("Location: ../../undertime_req.php?error=The Employee Does not have attendance for $date_under");
                }
            } //Friday Close Tag

            else if($day_of_week === 'Saturday'){    
                $sat_timein = $row_sched_tb['sat_timein'];
                $sat_timeout = $row_sched_tb['sat_timeout'];

                if ($sat_timein === '' && $sat_timeout === ''){
                    header("Location: ../../undertime_req.php?error= This employee request doesn't have a schedule for this day.");
                }
                else{
                    if($endtime < $col_saturday_timeout){
                        $time_out_datetime1 = new DateTime($endtime);
                        $scheduled_outs = new DateTime($col_saturday_timeout);
                        $early_interval = $scheduled_outs->diff($time_out_datetime1);
                        $early_out = $early_interval->format('%h:%i:%s');
                    }
                    else{
                        $early_out = "00:00:00";
                    }
                    $total_work = strtotime($endtime) + strtotime($starttime) - 7200;
                    $total_work = date('H:i:s', $total_work);

                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                        `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                        $result = mysqli_query($conn, $sql);
                    if($result){
                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                            $query_run = mysqli_query($conn, $sql);
                                if($query_run){
                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                                }else{
                                    echo "Failed: " . mysqli_error($conn);
                                } 
                        } else {
                            echo "Failed: " . mysqli_error($conn);
                        }  
                    
                    // $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND (status = 'Absent' || status = 'LWOP' || status = 'On-Leave')";
                    // $query_run = mysqli_query($conn, $query);
    
                    // if(mysqli_num_rows($query_run) > 0) {
                    //     $sql = "UPDATE attendances SET `time_out`='$endtime', 
                    //     `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                    //     $result = mysqli_query($conn, $sql);
                    // if($result){
                    //         $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                    //         $query_run = mysqli_query($conn, $sql);
                    //             if($query_run){
                    //                 header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                    //             }else{
                    //                 echo "Failed: " . mysqli_error($conn);
                    //             } 
                    //     } else {
                    //         echo "Failed: " . mysqli_error($conn);
                    //     }      
                    // } else {
                    //     header("Location: ../../undertime_req.php?error=The Employee Does not have attendance for $date_under");
                    // }
                }
                
            } //Saturday Close Tag

            else if($day_of_week === 'Sunday'){      
                if($endtime < $col_sunday_timeout){
                    $time_out_datetime1 = new DateTime($endtime);
                    $scheduled_outs = new DateTime($col_sunday_timeout);
                    $early_interval = $scheduled_outs->diff($time_out_datetime1);
                    $early_out = $early_interval->format('%h:%i:%s');
                }
                else{
                    $early_out = "00:00:00";
                }
                $total_work = strtotime($endtime) + strtotime($starttime) - 7200;
                $total_work = date('H:i:s', $total_work);
                
                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND (status = 'Absent' || status = 'LWOP' || status = 'On-Leave')";
                $query_run = mysqli_query($conn, $query);

                if(mysqli_num_rows($query_run) > 0) {
                    $sql = "UPDATE attendances SET `time_out`='$endtime', 
                    `early_out`='$total_undertime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                    $result = mysqli_query($conn, $sql);
                if($result){
                        $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                        $query_run = mysqli_query($conn, $sql);
                            if($query_run){
                                header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                            }else{
                                echo "Failed: " . mysqli_error($conn);
                            } 
                    } else {
                        echo "Failed: " . mysqli_error($conn);
                    }      
                } else {
                    header("Location: ../../undertime_req.php?error=The Employee Does not have attendance for $date_under");
                }
            } //Sunday Close Tag

        }
    }  
 } //Approve button Close Tag

 /************************* For Reject Button ***************************/
if(isset($_POST['reject_btn']))
{

    $column_id = $_POST['id_check'];

    $result_under = mysqli_query($conn, " SELECT * FROM undertime_tb WHERE id = '$column_id'");
    if(mysqli_num_rows($result_under) > 0) {
        $row_under = mysqli_fetch_assoc($result_under);
}
    $status_under = $row_under['status'];
    
    if($status_under === 'Approved'){
        header("Location: ../../undertime_req.php?error=You cannot REJECT a request that is already APPROVED");
    }
    else if($status_under === 'Rejected'){
        header("Location: ../../undertime_req.php?error=You cannot REJECT a request that is already REJECTED");
    }else{
        $query = "UPDATE undertime_tb SET `status` ='Rejected' WHERE `id`='$column_id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../undertime_req.php?msg=You Rejected this Request");
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    
    }
   
}
/************************* End of Reject Button ***************************/

?>    