<?php
    session_start();
include '../../config.php';

    if (isset($_POST['time_out'])) {
        date_default_timezone_set('Asia/Manila');

        $employeeid = $_SESSION['empid'];
        $timeOut = date('H:i:s');
        $dateOut = date('Y-m-d');

                        $resultWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND `status` = 'Approved' AND `date` = '$dateOut'";
                        $resultRun = mysqli_query($conn, $resultWFH);
                    
                        if (mysqli_num_rows($resultRun) > 0) {
                            $rowWFHapproved = mysqli_fetch_assoc($resultRun);
                            $startTime = $rowWFHapproved['start_time'];
                            $endTime = $rowWFHapproved['end_time'];

                            // Check if the user has already timed out for the day
                            $existingTimeoutResult = mysqli_query($conn, "SELECT time_out FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'");
                            $existingTimeoutRow = mysqli_fetch_assoc($existingTimeoutResult);
                            if (!empty($existingTimeoutRow['time_out']) && $existingTimeoutRow['time_out'] !== '00:00:00') {
                                // Display an error message if the user has already timed out
                                echo '<script>';
                                echo 'alert("You have already Time out for this day!");';
                                echo 'window.location.href = "../../Dashboard.php";';
                                echo '</script>';
                                exit();
                            }
                            
                            $day_of_week = date('l', strtotime($dateOut));
                            
                            // else if ($timeOut > $endTime) {
                            //     $time_out_datetime = new DateTime($timeOut);
                            //     $scheduled_time = new DateTime($endTime);
                            //     $interval = $time_out_datetime->diff($scheduled_time);
                            //     $overtime = $interval->format('%h:%i:%s');
                            // } para to sa pagkalkula ng overtime 

                            // Get the employee's time in from the attendances table
                            $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'");
                            if (mysqli_num_rows($result) > 0) {
                                $rowattendance = mysqli_fetch_assoc($result);
                                $timeIn_retrieve = $rowattendance['time_in'];
                                $late_retrieve = $rowattendance['late'];

                                if($timeIn_retrieve != '00:00:00'){
                                    $WfhTimeIn = new DateTime($startTime);
                                    $WfhTimeOut = new DateTime($endTime); 
                                    //I-convert ang existing time in to new DateTime
                                    $time_in_datetime = new DateTime($timeIn_retrieve);
                                    $late_datetime = new DateTime($late_retrieve);
                                    $time_out_datetime = new DateTime($timeOut);
                                
                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');

                                if($time_in_datetime < $lunchbreak_start) {
                                    //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                     if($late_datetime != '00:00:00'){
                                        $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                     }else{
                                        //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                        $total_works = $WfhTimeIn->diff($WfhTimeOut)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($total_works);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
                                     }
                                }else{
                                    $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Remove Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_workss);
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }

                                //Check kung ang pag time out ay greater than sa lunchbreak
                                if($time_out_datetime > $lunchbreak_start){
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }else{
                                    $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Remove Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_workss);
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }

                                if ($timeOut < $endTime) {
                                    $time_out_datetime = new DateTime($timeOut);
                                    $scheduled_time = new DateTime($endTime);
                                    $interval = $time_out_datetime->diff($scheduled_time);
                                    $early_out = $interval->format('%h:%i:%s');
                                }else{
                                    $early_out = "00:00:00";
                                } 
                            }else{
                                $total_work = "00:00:00";
                            }
                            // echo $total_work;
                        }

                            $queryAttendance = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                            $runAttendances = mysqli_query($conn, $queryAttendance);
                    
                            if ($runAttendances) {
                                if (mysqli_num_rows($runAttendances) > 0) {
                                    $insertAttendance = "UPDATE attendances SET `time_out` = '$timeOut', `early_out` = '$early_out',
                                    `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                    $resultAttendance = mysqli_query($conn, $insertAttendance);
                                    if ($resultAttendance) {
                                        echo '<script>';
                                        echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                        echo 'window.location.href = "../../Dashboard.php";';
                                        echo '</script>';
                                        exit;
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                } else {
                                    echo '<script>';
                                    echo 'alert("You need to Time In first for this day!");';
                                    echo 'window.location.href = "../../Dashboard.php";';
                                    echo '</script>';
                                    exit;
                                }
                            } else {
                                echo "Failed: " . mysqli_error($conn);
                            }
                        }
                        else{
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


                                            $day_of_week = date('l', strtotime($dateOut)); // get the day of the week using the "l" format specifier


                                            // Check if the user has already timed out for the day
                                            $existingTimeoutResult = mysqli_query($conn, "SELECT time_out FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'");
                                            $existingTimeoutRow = mysqli_fetch_assoc($existingTimeoutResult);
                                            if (!empty($existingTimeoutRow['time_out']) && $existingTimeoutRow['time_out'] !== '00:00:00') {
                                                // Display an error message if the user has already timed out
                                                echo '<script>';
                                                echo 'alert("You have already Time out for this day!");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit();
                                            }

                                            if ($day_of_week === 'Monday') {
                                                $SelectPresent = "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'";
                                                $result = mysqli_query($conn, $SelectPresent);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late']; 
                                                
                                                    if($fetch_timein != '00:00:00'){
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($timeOut);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');
                                                        
                                                        //Check kung ang existing time in ay before lunchbreak at pag time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        //Get the undertime of employee
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $intervals = (new DateTime($interval))->diff($late_datetime)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($intervals);
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                    }else{
                                                        $total_work = "00:00:00";
                                                    }
                                                    // echo $total_work;
                                              }
                                        
                                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                                                $query_run = mysqli_query($conn, $query);

                                                if ($query_run) {
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out` = '$timeOut', `early_out` = '$early_out',
                                                                `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                                        $result = mysqli_query($conn, $sql);
                                                        if ($result) {
                                                                echo '<script>';
                                                                echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                                                echo 'window.location.href = "../../Dashboard.php";';
                                                                echo '</script>';
                                                                exit;
                                                        } else {
                                                            echo "Failed: " . mysqli_error($conn);
                                                        }
                                                    } else {
                                                    echo '<script>';
                                                    echo 'alert("You need to time in first this day!");';
                                                    echo 'window.location.href = "../../Dashboard.php";';
                                                    echo '</script>';
                                                    exit;
                                                    }
                                                }
                                            } //Monday Close bracket

                                            else if ($day_of_week === 'Tuesday') {
                                                $SelectPresent = "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'";
                                                $result = mysqli_query($conn, $SelectPresent);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late']; 
                                                
                                                    if($fetch_timein != '00:00:00'){
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($timeOut);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');
                                                        
                                                        //Check kung ang existing time in ay before lunchbreak at pag time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        //Get the undertime of employee
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $intervals = (new DateTime($interval))->diff($late_datetime)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($intervals);
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                    }else{
                                                        $total_work = "00:00:00";
                                                    }
                                                    // echo $total_work;
                                              }
                                        
                                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                                                $query_run = mysqli_query($conn, $query);

                                                if ($query_run) {
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out` = '$timeOut', `early_out` = '$early_out',
                                                                `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                                        $result = mysqli_query($conn, $sql);
                                                        if ($result) {
                                                                echo '<script>';
                                                                echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                                                echo 'window.location.href = "../../Dashboard.php";';
                                                                echo '</script>';
                                                                exit;
                                                        } else {
                                                            echo "Failed: " . mysqli_error($conn);
                                                        }
                                                    } else {
                                                    echo '<script>';
                                                    echo 'alert("You need to time in first this day!");';
                                                    echo 'window.location.href = "../../Dashboard.php";';
                                                    echo '</script>';
                                                    exit;
                                                    }
                                                }
                                            } //Tuesday Close bracket

                                            else if ($day_of_week === 'Wednesday') {
                                                $SelectPresent = "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'";
                                                $result = mysqli_query($conn, $SelectPresent);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late']; 
                                                
                                                    if($fetch_timein != '00:00:00'){
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($timeOut);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $SchedTimeIn = new DateTime($row_sched_tb['wed_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['wed_timeout']);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');
                                                        
                                                        //Check kung ang existing time in ay before lunchbreak at pag time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        //Get the undertime of employee
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $intervals = (new DateTime($interval))->diff($late_datetime)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($intervals);
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                    }else{
                                                        $total_work = "00:00:00";
                                                    }
                                                    // echo $total_work;
                                              }
                                        
                                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                                                $query_run = mysqli_query($conn, $query);

                                                if ($query_run) {
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out` = '$timeOut', `early_out` = '$early_out',
                                                                `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                                        $result = mysqli_query($conn, $sql);
                                                        if ($result) {
                                                                echo '<script>';
                                                                echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                                                echo 'window.location.href = "../../Dashboard.php";';
                                                                echo '</script>';
                                                                exit;
                                                        } else {
                                                            echo "Failed: " . mysqli_error($conn);
                                                        }
                                                    } else {
                                                    echo '<script>';
                                                    echo 'alert("You need to time in first this day!");';
                                                    echo 'window.location.href = "../../Dashboard.php";';
                                                    echo '</script>';
                                                    exit;
                                                    }
                                                }
                                            } //Wednesday Close bracket

                                            else if ($day_of_week === 'Thursday') {
                                                $SelectPresent = "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'";
                                                $result = mysqli_query($conn, $SelectPresent);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late']; 
                                                
                                                    if($fetch_timein != '00:00:00'){
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($timeOut);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $SchedTimeIn = new DateTime($row_sched_tb['thurs_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['thurs_timeout']);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');
                                                        
                                                        //Check kung ang existing time in ay before lunchbreak at pag time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        //Get the undertime of employee
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $intervals = (new DateTime($interval))->diff($late_datetime)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($intervals);
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                    }else{
                                                        $total_work = "00:00:00";
                                                    }
                                                    // echo $total_work;
                                              }
                                        
                                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                                                $query_run = mysqli_query($conn, $query);

                                                if ($query_run) {
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out`='$timeOut', `early_out`='$early_out',
                                                                `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                                        $result = mysqli_query($conn, $sql);
                                                        if ($result) {
                                                                echo '<script>';
                                                                echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                                                echo 'window.location.href = "../../Dashboard.php";';
                                                                echo '</script>';
                                                                exit;
                                                        } else {
                                                            echo "Failed: " . mysqli_error($conn);
                                                        }
                                                    } else {
                                                    echo '<script>';
                                                    echo 'alert("You need to time in first this day!");';
                                                    echo 'window.location.href = "../../Dashboard.php";';
                                                    echo '</script>';
                                                    exit;
                                                    }
                                                }
                                            } //Thursday Close bracket

                                            else if ($day_of_week === 'Friday') {
                                                $SelectPresent = "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'";
                                                $result = mysqli_query($conn, $SelectPresent);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late']; 
                                                
                                                    if($fetch_timein != '00:00:00'){
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($timeOut);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $SchedTimeIn = new DateTime($row_sched_tb['fri_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['fri_timeout']);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');
                                                        
                                                        //Check kung ang existing time in ay before lunchbreak at pag time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        //Get the undertime of employee
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $intervals = (new DateTime($interval))->diff($late_datetime)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($intervals);
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                    }else{
                                                        $total_work = "00:00:00";
                                                    }
                                                    // echo $total_work;
                                              }
                                        
                                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                                                $query_run = mysqli_query($conn, $query);

                                                if ($query_run) {
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out`='$timeOut', `early_out`='$early_out',
                                                               `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                                        $result = mysqli_query($conn, $sql);
                                                        if ($result) {
                                                                echo '<script>';
                                                                echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                                                echo 'window.location.href = "../../Dashboard.php";';
                                                                echo '</script>';
                                                                exit;
                                                        } else {
                                                            echo "Failed: " . mysqli_error($conn);
                                                        }
                                                    } else {
                                                    echo '<script>';
                                                    echo 'alert("You need to time in first this day!");';
                                                    echo 'window.location.href = "../../Dashboard.php";';
                                                    echo '</script>';
                                                    exit;
                                                    }
                                                }
                                            } //Friday close bracket

                                            else if ($day_of_week === 'Saturday') {
                                                $SelectPresent = "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'";
                                                $result = mysqli_query($conn, $SelectPresent);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late']; 
                                                
                                                    if($fetch_timein != '00:00:00'){
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($timeOut);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $SchedTimeIn = new DateTime($row_sched_tb['sat_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['sat_timeout']);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');
                                                        
                                                        //Check kung ang existing time in ay before lunchbreak at pag time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        //Get the undertime of employee
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $intervals = (new DateTime($interval))->diff($late_datetime)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($intervals);
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                    }else{
                                                        $total_work = "00:00:00";
                                                    }
                                                    // echo $total_work;
                                              }
                                        
                                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                                                $query_run = mysqli_query($conn, $query);

                                                if ($query_run) {
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out`='$timeOut', `early_out`='$early_out',
                                                                `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                                        $result = mysqli_query($conn, $sql);
                                                        if ($result) {
                                                                echo '<script>';
                                                                echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                                                echo 'window.location.href = "../../Dashboard.php";';
                                                                echo '</script>';
                                                                exit;
                                                        } else {
                                                            echo "Failed: " . mysqli_error($conn);
                                                        }
                                                    } else {
                                                    echo '<script>';
                                                    echo 'alert("You need to time in first this day!");';
                                                    echo 'window.location.href = "../../Dashboard.php";';
                                                    echo '</script>';
                                                    exit;
                                                    }
                                                }
                                            } //Saturday Close bracket

                                            else if ($day_of_week === 'Sunday') {
                                                $SelectPresent = "SELECT * FROM attendances WHERE `date` = '$dateOut' AND `empid` = '$employeeid' AND `status` = 'Present'";
                                                $result = mysqli_query($conn, $SelectPresent);
                                                if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late']; 
                                                
                                                    if($fetch_timein != '00:00:00'){
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($timeOut);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $SchedTimeIn = new DateTime($row_sched_tb['sun_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['sun_timeout']);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');
                                                        
                                                        //Check kung ang existing time in ay before lunchbreak at pag time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        //Get the undertime of employee
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $intervals = (new DateTime($interval))->diff($late_datetime)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($intervals);
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                    }else{
                                                        $total_work = "00:00:00";
                                                    }
                                                    // echo $total_work;
                                              }
                                        
                                                $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateOut' AND `status` = 'Present'";
                                                $query_run = mysqli_query($conn, $query);

                                                if ($query_run) {
                                                    if (mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out`='$timeOut', `early_out`='$early_out',
                                                                `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$dateOut'";
                                                        $result = mysqli_query($conn, $sql);
                                                        if ($result) {
                                                                echo '<script>';
                                                                echo 'alert("Time out successfully on date ' . $dateOut . '");';
                                                                echo 'window.location.href = "../../Dashboard.php";';
                                                                echo '</script>';
                                                                exit;
                                                        } else {
                                                            echo "Failed: " . mysqli_error($conn);
                                                        }
                                                    } else {
                                                    echo '<script>';
                                                    echo 'alert("You need to time in first this day!");';
                                                    echo 'window.location.href = "../../Dashboard.php";';
                                                    echo '</script>';
                                                    exit;
                                                    }
                                                }
                                            } //Sunday Close bracket

                                }
                            }
                        }


    }
    
?>