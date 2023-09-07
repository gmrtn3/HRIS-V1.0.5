<?php
    session_start();
include '../../config.php';

    if (isset($_POST['time_in'])) {
        date_default_timezone_set('Asia/Manila');
    
        $employeeid = $_SESSION['empid'];
        $timeIn = date('H:i:s');
        $dateIn = date('Y-m-d');
    
        $resultWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND `status` = 'Approved' AND `date` = '$dateIn'";
        $resultRun = mysqli_query($conn, $resultWFH);
    
        if (mysqli_num_rows($resultRun) > 0) {
            $rowWFHapproved = mysqli_fetch_assoc($resultRun);
            $startTime = $rowWFHapproved['start_time'];
            $endTime = $rowWFHapproved['end_time'];
    
            $day_of_week = date('l', strtotime($dateIn));
    
            if ($timeIn > $startTime) {
                $time_in_datetime = new DateTime($timeIn);
                $scheduled_time = new DateTime($startTime);
                $interval = $time_in_datetime->diff($scheduled_time);
                $late = $interval->format('%h:%i:%s');

                // $scheduled_time = new DateTime($startTime);
                // $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                // $latetotal = new DateTime($interval);
                // $latetotal->sub(new DateInterval('PT1H'));
                // $late = $latetotal->format('H:i:s');
            } else {
                $late = '00:00:00';
            }
    
            $queryAttendance = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
            $runAttendances = mysqli_query($conn, $queryAttendance);
    
            if ($runAttendances) {
                if (mysqli_num_rows($runAttendances) == 0) {
                    $insertAttendance = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                         VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                    $resultAttendance = mysqli_query($conn, $insertAttendance);
                    if ($resultAttendance) {
                        echo '<script>';
                        echo 'alert("Time in successfully on date ' . $dateIn . '");';
                        echo 'window.location.href = "../../Dashboard.php";';
                        echo '</script>';
                        exit;
                    } else {
                        echo "Failed: " . mysqli_error($conn);
                    }
                } else {
                    echo '<script>';
                    echo 'alert("You have already Time In for this day!");';
                    echo 'window.location.href = "../../Dashboard.php";';
                    echo '</script>';
                    exit;
                }
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        } else 
        
        {
                        $CheckEmpSched = "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'";
                        $result_emp_sched = mysqli_query($conn, $CheckEmpSched);

                        if (mysqli_num_rows($result_emp_sched) > 0) {
                            $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                            $schedID = $row_emp_sched['schedule_name'];

                            $CheckSchedule = "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'";
                            $result_sched_tb = mysqli_query($conn, $CheckSchedule);

                            if (mysqli_num_rows($result_sched_tb) > 0) {
                                $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                                $sched_name = $row_sched_tb['schedule_name'];
                                $col_monday_timein = $row_sched_tb['mon_timein'];
                                $col_tuesday_timein =  $row_sched_tb['tues_timein'];
                                $col_wednesday_timein =  $row_sched_tb['wed_timein'];
                                $col_thursday_timein =  $row_sched_tb['thurs_timein'];
                                $col_friday_timein =  $row_sched_tb['fri_timein'];
                                $col_saturday_timein =  $row_sched_tb['sat_timein'];
                                $col_sunday_timein =  $row_sched_tb['sun_timein'];
                                $col_grace_period = $row_sched_tb['grace_period'];

                                $day_of_week = date('l', strtotime($dateIn));

                                if ($day_of_week === 'Monday') {
                                    $late = '';
                                    //I-convert ang time in to new DateTime
                                    $time_in_datetime = new DateTime($timeIn);
    
                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');
    
                                if ($time_in_datetime < $lunchbreak_start){
    
                                    $grace_period_total = new DateTime($col_monday_timein);
                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                    if ($grace_period_minutes > 0) {
                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                        $grace_period_total->add($grace_period_interval);
                                    }
    
                                    if ($grace_period_total < $time_in_datetime) {
                                        $monday_timeIn = new DateTime($col_monday_timein);
                                        if (empty($monday_timeIn)) {
                                            $late = "00:00:00";
                                        } else {
                                            $late = $time_in_datetime->diff($monday_timeIn)->format('%H:%I:%S');
                                        }
                                    }
                                }else{
                                    if($time_in_datetime > $col_monday_timein){
                                        $scheduled_time = new DateTime($col_monday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                        $latetotal = new DateTime($interval);
                                        $latetotal->sub(new DateInterval('PT1H'));
                                        $late = $latetotal->format('H:i:s');
                                    }
                                }
                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
                                    $query_run = mysqli_query($conn, $query);

                                    if ($query_run) {
                                        if (mysqli_num_rows($query_run) == 0) {
                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                                    VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                echo '<script>';
                                                echo 'alert("Time in successfully on date ' . $dateIn . '");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit;
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                                exit;
                                            }
                                        } else {
                                            echo '<script>';
                                            echo 'alert("You have already Time In for this day!");';
                                            echo 'window.location.href = "../../Dashboard.php";';
                                            echo '</script>';
                                            exit;
                                        }
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                        exit;
                                    }
                                } //Monday Close bracket

                               else if ($day_of_week === 'Tuesday') {
                                $late = '';
                                $time_in_datetime = new DateTime($timeIn);

                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');

                            if ($time_in_datetime < $lunchbreak_start){

                                $grace_period_total = new DateTime($col_tuesday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }

                                if ($grace_period_total < $time_in_datetime) {
                                    $tuesday_timeIn = new DateTime($col_tuesday_timein);
                                    if (empty($tuesday_timeIn)) {
                                        $late = "00:00:00";
                                    } else {
                                        $late = $time_in_datetime->diff($tuesday_timeIn)->format('%H:%I:%S');
                                    }
                                }
                            }else{
                                if($time_in_datetime > $col_tuesday_timein){
                                    $scheduled_time = new DateTime($col_tuesday_timein);
                                    $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                    $latetotal = new DateTime($interval);
                                    $latetotal->sub(new DateInterval('PT1H'));
                                    $late = $latetotal->format('H:i:s');
                                }
                            }
                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
                                    $query_run = mysqli_query($conn, $query);

                                    if ($query_run) {
                                        if (mysqli_num_rows($query_run) == 0) {
                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                                    VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                echo '<script>';
                                                echo 'alert("Time in successfully on date ' . $dateIn . '");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit;
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                                exit;
                                            }
                                        } else {
                                            echo '<script>';
                                            echo 'alert("You have already Time In for this day!");';
                                            echo 'window.location.href = "../../Dashboard.php";';
                                            echo '</script>';
                                            exit;
                                        }
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                        exit;
                                    }
                                } //Tuesday Close bracket

                                else if ($day_of_week === 'Wednesday') {
                                    $late = '';
                                    $time_in_datetime = new DateTime($timeIn);

                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');

                                if ($time_in_datetime < $lunchbreak_start){

                                    $grace_period_total = new DateTime($col_wednesday_timein);
                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                    if ($grace_period_minutes > 0) {
                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                        $grace_period_total->add($grace_period_interval);
                                    }

                                    if ($grace_period_total < $time_in_datetime) {
                                        $wednesday_timeIn = new DateTime($col_wednesday_timein);
                                        if (empty($wednesday_timeIn)) {
                                            $late = "00:00:00";
                                        } else {
                                            $late = $time_in_datetime->diff($wednesday_timeIn)->format('%H:%I:%S');
                                        }
                                    }
                                }else{
                                    if($time_in_datetime > $col_wednesday_timein){
                                        $scheduled_time = new DateTime($col_wednesday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                        $latetotal = new DateTime($interval);
                                        $latetotal->sub(new DateInterval('PT1H'));
                                        $late = $latetotal->format('H:i:s');
                                    }
                                }

                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
                                    $query_run = mysqli_query($conn, $query);

                                    if ($query_run) {
                                        if (mysqli_num_rows($query_run) == 0) {
                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                                    VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                echo '<script>';
                                                echo 'alert("Time in successfully on date ' . $dateIn . '");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit;
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                                exit;
                                            }
                                        } else {
                                            echo '<script>';
                                            echo 'alert("You have already Time In for this day!");';
                                            echo 'window.location.href = "../../Dashboard.php";';
                                            echo '</script>';
                                            exit;
                                        }
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                        exit;
                                    }
                                } //Wednesday close bracket

                                else if ($day_of_week === 'Thursday') {
                                    $late = '';
                                    $time_in_datetime = new DateTime($timeIn);

                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');

                                if ($time_in_datetime < $lunchbreak_start){

                                    $grace_period_total = new DateTime($col_thursday_timein);
                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                    if ($grace_period_minutes > 0) {
                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                        $grace_period_total->add($grace_period_interval);
                                    }

                                    if ($grace_period_total < $time_in_datetime) {
                                        $thursday_timeIn = new DateTime($col_thursday_timein);
                                        if (empty($thursday_timeIn)) {
                                            $late = "00:00:00";
                                        } else {
                                            $late = $time_in_datetime->diff($thursday_timeIn)->format('%H:%I:%S');
                                        }
                                    }
                                }else{
                                    if($time_in_datetime > $col_thursday_timein){
                                        $scheduled_time = new DateTime($col_thursday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                        $latetotal = new DateTime($interval);
                                        $latetotal->sub(new DateInterval('PT1H'));
                                        $late = $latetotal->format('H:i:s');
                                    }
                                }

                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
                                    $query_run = mysqli_query($conn, $query);

                                    if ($query_run) {
                                        if (mysqli_num_rows($query_run) == 0) {
                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                                    VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                echo '<script>';
                                                echo 'alert("Time in successfully on date ' . $dateIn . '");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit;
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                                exit;
                                            }
                                        } else {
                                            echo '<script>';
                                            echo 'alert("You have already Time In for this day!");';
                                            echo 'window.location.href = "../../Dashboard.php";';
                                            echo '</script>';
                                            exit;
                                        }
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                        exit;
                                    }
                                } //Thursday Close bracket

                                else if ($day_of_week === 'Friday') {
                                    $late = '';
                                    $time_in_datetime = new DateTime($timeIn);

                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');

                                if ($time_in_datetime < $lunchbreak_start){

                                    $grace_period_total = new DateTime($col_friday_timein);
                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                    if ($grace_period_minutes > 0) {
                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                        $grace_period_total->add($grace_period_interval);
                                    }

                                    if ($grace_period_total < $time_in_datetime) {
                                        $friday_timeIn = new DateTime($col_friday_timein);
                                        if (empty($friday_timeIn)) {
                                            $late = "00:00:00";
                                        } else {
                                            $late = $time_in_datetime->diff($friday_timeIn)->format('%H:%I:%S');
                                        }
                                    }
                                }else{
                                    if($time_in_datetime > $col_friday_timein){
                                        $scheduled_time = new DateTime($col_friday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                        $latetotal = new DateTime($interval);
                                        $latetotal->sub(new DateInterval('PT1H'));
                                        $late = $latetotal->format('H:i:s');
                                    }
                                }
                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
                                    $query_run = mysqli_query($conn, $query);

                                    if ($query_run) {
                                        if (mysqli_num_rows($query_run) == 0) {
                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                                    VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                echo '<script>';
                                                echo 'alert("Time in successfully on date ' . $dateIn . '");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit;
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                                exit;
                                            }
                                        } else {
                                            echo '<script>';
                                            echo 'alert("You have already Time In for this day!");';
                                            echo 'window.location.href = "../../Dashboard.php";';
                                            echo '</script>';
                                            exit;
                                        }
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                        exit;
                                    }
                                } //Friday Close BRacket

                                else if ($day_of_week === 'Saturday') {
                                    $late = '';
                                    $time_in_datetime = new DateTime($timeIn);

                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');

                                if ($time_in_datetime < $lunchbreak_start){

                                    $grace_period_total = new DateTime($col_saturday_timein);
                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                    if ($grace_period_minutes > 0) {
                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                        $grace_period_total->add($grace_period_interval);
                                    }

                                    if ($grace_period_total < $time_in_datetime) {
                                        $saturday_timeIn = new DateTime($col_saturday_timein);
                                        if (empty($saturday_timeIn)) {
                                            $late = "00:00:00";
                                        } else {
                                            $late = $time_in_datetime->diff($saturday_timeIn)->format('%H:%I:%S');
                                        }
                                    }
                                }else{
                                    if($time_in_datetime > $col_saturday_timein){
                                        $scheduled_time = new DateTime($col_saturday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                        $latetotal = new DateTime($interval);
                                        $latetotal->sub(new DateInterval('PT1H'));
                                        $late = $latetotal->format('H:i:s');
                                    }
                                }
                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
                                    $query_run = mysqli_query($conn, $query);

                                    if ($query_run) {
                                        if (mysqli_num_rows($query_run) == 0) {
                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                                    VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                echo '<script>';
                                                echo 'alert("Time in successfully on date ' . $dateIn . '");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit;
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                                exit;
                                            }
                                        } else {
                                            echo '<script>';
                                            echo 'alert("You have already Time In for this day!");';
                                            echo 'window.location.href = "../../Dashboard.php";';
                                            echo '</script>';
                                            exit;
                                        }
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                        exit;
                                    }
                                } //Saturday Close bracket 


                                else if ($day_of_week === 'Sunday') {
                                    $late = '';
                                    $time_in_datetime = new DateTime($timeIn);

                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');

                                if ($time_in_datetime < $lunchbreak_start){

                                    $grace_period_total = new DateTime($col_sunday_timein);
                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                    if ($grace_period_minutes > 0) {
                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                        $grace_period_total->add($grace_period_interval);
                                    }

                                    if ($grace_period_total < $time_in_datetime) {
                                        $sunday_timeIn = new DateTime($col_sunday_timein);
                                        if (empty($sunday_timeIn)) {
                                            $late = "00:00:00";
                                        } else {
                                            $late = $time_in_datetime->diff($sunday_timeIn)->format('%H:%I:%S');
                                        }
                                    }
                                }else{
                                    if($time_in_datetime > $col_sunday_timein){
                                        $scheduled_time = new DateTime($col_sunday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                        $latetotal = new DateTime($interval);
                                        $latetotal->sub(new DateInterval('PT1H'));
                                        $late = $latetotal->format('H:i:s');
                                    }
                                }
                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$dateIn'";
                                    $query_run = mysqli_query($conn, $query);

                                    if ($query_run) {
                                        if (mysqli_num_rows($query_run) == 0) {
                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `late`) 
                                                    VALUES ('Present', '$employeeid', '$dateIn', '$timeIn', '$late')";
                                            $result = mysqli_query($conn, $sql);
                                            if ($result) {
                                                echo '<script>';
                                                echo 'alert("Time in successfully on date ' . $dateIn . '");';
                                                echo 'window.location.href = "../../Dashboard.php";';
                                                echo '</script>';
                                                exit;
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                                exit;
                                            }
                                        } else {
                                            echo '<script>';
                                            echo 'alert("You have already Time In for this day!");';
                                            echo 'window.location.href = "../../Dashboard.php";';
                                            echo '</script>';
                                            exit;
                                        }
                                    } else {
                                        echo "Failed: " . mysqli_error($conn);
                                        exit;
                                    }
                                } //Sunday Close Bracket

                            }
                        } else {
                            echo '<script> alert("Employee has no schedule!"); </script>';
                            exit;
                        }

           
        }
    } //Time in Close bracket
    

?>