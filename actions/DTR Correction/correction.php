<?php 
include '../../config.php';

if(isset($_POST['yesCorrect'])){
    $EmployeeId = $_POST['employeeId'];
    $DateDTR = $_POST['dateDtr'];
    $TimeDTR = $_POST['timeDtr'];
    $TypeDTR = $_POST['typeDtr'];


    if($TypeDTR === 'IN'){
                $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$EmployeeId'");
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
                    $col_grace_period = $row_sched_tb['grace_period'];


                    $day_of_week = date('l', strtotime($DateDTR));

                    if($day_of_week === 'Monday'){
                        $checkAtt = mysqli_query($conn, "SELECT * FROM attendances WHERE `time_in` = '00:00:00' AND `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                        if($checkAtt && mysqli_num_rows($checkAtt) > 0){
                            $attendrow = mysqli_fetch_assoc($checkAtt);
                            $fetch_timeout = $attendrow['time_out'];

                            $time_in_datetime = new DateTime($TimeDTR);
                            $time_out_datetime = new DateTime($fetch_timeout);

                            $actual_timein = new DateTime($col_monday_timein);
                            $actual_timeout = new DateTime($col_monday_timeout);

                            $lunchbreak_start = new DateTime('12:00:00');
                            $lunchbreak_end = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_monday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }

                            // Check if $TimeDTR is greater than sa sum ng $col_monday_timein and grace period
                            if($time_in_datetime < $lunchbreak_start){
                                if ($time_in_datetime < $grace_period_total) {
                                    $late = '00:00:00';
                                } else {
                                    $late = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                }
                            }else{
                                //subtract 1 hour sa late
                                $lates = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                $late_datetime = new DateTime($lates);
                                $late_datetime->sub(new DateInterval('PT1H'));
                                $late = $late_datetime->format('H:i:s');
                            }

                            //para naman sa total work
                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                $grace_period_total = new DateTime($col_monday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }
                                // Check if $time_in is less than the sum of $col_monday_timein and grace period
                                if ($time_in_datetime < $grace_period_total) {
                                    $total_works = $actual_timeout->diff($actual_timein)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                } else {
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }
                            }else{
                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                $total_work_datetime = new DateTime($total_works);
                                $total_work = $total_work_datetime->format('H:i:s');
                            }

                            $updateAtt = mysqli_query($conn,"UPDATE attendances SET `status` = 'Present', `time_in` = '$TimeDTR', `late` = '$late', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_in` = '00:00:00' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                            header("Location: ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                        }else{
                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                        }
                    } //Close bracket monday


                    else if($day_of_week === 'Tuesday'){
                        $checkAtt = mysqli_query($conn, "SELECT * FROM attendances WHERE `time_in` = '00:00:00' AND `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                        if($checkAtt && mysqli_num_rows($checkAtt) > 0){
                            $attendrow = mysqli_fetch_assoc($checkAtt);
                            $fetch_timeout = $attendrow['time_out'];

                            $time_in_datetime = new DateTime($TimeDTR);
                            $time_out_datetime = new DateTime($fetch_timeout);

                            $actual_timein = new DateTime($col_tuesday_timein);
                            $actual_timeout = new DateTime($col_tuesday_timeout);

                            $lunchbreak_start = new DateTime('12:00:00');
                            $lunchbreak_end = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_tuesday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }

                            // Check if $TimeDTR is greater than sa sum ng $col_tuesday_timein and grace period
                            if($time_in_datetime < $lunchbreak_start){
                                if ($time_in_datetime < $grace_period_total) {
                                    $late = '00:00:00';
                                } else {
                                    $late = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                }
                            }else{
                                //subtract 1 hour sa late
                                $lates = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                $late_datetime = new DateTime($lates);
                                $late_datetime->sub(new DateInterval('PT1H'));
                                $late = $late_datetime->format('H:i:s');
                            }

                            //para naman sa total work
                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                $grace_period_total = new DateTime($col_tuesday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }
                                // Check if $time_in is less than the sum of $col_tuesday_timein and grace period
                                if ($time_in_datetime < $grace_period_total) {
                                    $total_works = $actual_timeout->diff($actual_timein)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                } else {
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }
                            }else{
                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                $total_work_datetime = new DateTime($total_works);
                                $total_work = $total_work_datetime->format('H:i:s');
                            }
                            $updateAtt = mysqli_query($conn,"UPDATE attendances SET `status` = 'Present', `time_in` = '$TimeDTR', `late` = '$late', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_in` = '00:00:00' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                            header("Location: ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                        }else{
                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                        }
                    } //Close bracket tuesday

                    else if($day_of_week === 'Wednesday'){
                        $checkAtt = mysqli_query($conn, "SELECT * FROM attendances WHERE `time_in` = '00:00:00' AND `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                        if($checkAtt && mysqli_num_rows($checkAtt) > 0){
                            $attendrow = mysqli_fetch_assoc($checkAtt);
                            $fetch_timeout = $attendrow['time_out'];
  
                            $time_in_datetime = new DateTime($TimeDTR);
                            $time_out_datetime = new DateTime($fetch_timeout);

                            $actual_timein = new DateTime($col_wednesday_timein);
                            $actual_timeout = new DateTime($col_wednesday_timeout);

                            $lunchbreak_start = new DateTime('12:00:00');
                            $lunchbreak_end = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_wednesday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }

                            // Check if $TimeDTR is greater than sa sum ng $col_wednesday_timein and grace period
                            if($time_in_datetime < $lunchbreak_start){
                                if ($time_in_datetime < $grace_period_total) {
                                    $late = '00:00:00';
                                } else {
                                    $late = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                }
                            }else{
                                //subtract 1 hour sa late
                                $lates = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                $late_datetime = new DateTime($lates);
                                $late_datetime->sub(new DateInterval('PT1H'));
                                $late = $late_datetime->format('H:i:s');
                            }

                            //para naman sa total work
                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                $grace_period_total = new DateTime($col_wednesday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }
                                // Check if $time_in is less than the sum of $col_wednesday_timein and grace period
                                if ($time_in_datetime < $grace_period_total) {
                                    $total_works = $actual_timeout->diff($actual_timein)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                } else {
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }
                            }else{
                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                $total_work_datetime = new DateTime($total_works);
                                $total_work = $total_work_datetime->format('H:i:s');
                            }

                            $updateAtt = mysqli_query($conn,"UPDATE attendances SET `status` = 'Present', `time_in` = '$TimeDTR', `late` = '$late', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_in` = '00:00:00' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                            header("Location: ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                        }else{
                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                        }
                    } //Close bracket Wednesday

                    else if($day_of_week === 'Thursday'){
                        $checkAtt = mysqli_query($conn, "SELECT * FROM attendances WHERE `time_in` = '00:00:00' AND `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                        if($checkAtt && mysqli_num_rows($checkAtt) > 0){
                            $attendrow = mysqli_fetch_assoc($checkAtt);
                            $fetch_timeout = $attendrow['time_out'];

                            $time_in_datetime = new DateTime($TimeDTR);
                            $time_out_datetime = new DateTime($fetch_timeout);

                            $actual_timein = new DateTime($col_thursday_timein);
                            $actual_timeout = new DateTime($col_thursday_timeout);

                            $lunchbreak_start = new DateTime('12:00:00');
                            $lunchbreak_end = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_thursday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }

                            // Check if $TimeDTR is greater than sa sum ng $col_thursday_timein and grace period
                            if($time_in_datetime < $lunchbreak_start){
                                if ($time_in_datetime < $grace_period_total) {
                                    $late = '00:00:00';
                                } else {
                                    $late = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                }
                            }else{
                                //subtract 1 hour sa late
                                $lates = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                $late_datetime = new DateTime($lates);
                                $late_datetime->sub(new DateInterval('PT1H'));
                                $late = $late_datetime->format('H:i:s');
                            }

                            //para naman sa total work
                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                $grace_period_total = new DateTime($col_thursday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }
                                // Check if $time_in is less than the sum of $col_thursday_timein and grace period
                                if ($time_in_datetime < $grace_period_total) {
                                    $total_works = $actual_timeout->diff($actual_timein)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                } else {
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }
                            }else{
                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                $total_work_datetime = new DateTime($total_works);
                                $total_work = $total_work_datetime->format('H:i:s');
                            }

                            $updateAtt = mysqli_query($conn,"UPDATE attendances SET `status` = 'Present', `time_in` = '$TimeDTR', `late` = '$late', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_in` = '00:00:00' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                            header("Location: ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                        }else{
                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                        }
                    } //Close bracket Thursday


                    else if($day_of_week === 'Friday'){
                        $checkAtt = mysqli_query($conn, "SELECT * FROM attendances WHERE `time_in` = '00:00:00' AND `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                        if($checkAtt && mysqli_num_rows($checkAtt) > 0){
                            $attendrow = mysqli_fetch_assoc($checkAtt);
                            $fetch_timeout = $attendrow['time_out'];

                            $time_in_datetime = new DateTime($TimeDTR);
                            $time_out_datetime = new DateTime($fetch_timeout);

                            $actual_timein = new DateTime($col_friday_timein);
                            $actual_timeout = new DateTime($col_friday_timeout);

                            $lunchbreak_start = new DateTime('12:00:00');
                            $lunchbreak_end = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_friday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }

                            // Check if $TimeDTR is greater than sa sum ng $col_friday_timein and grace period
                            if($time_in_datetime < $lunchbreak_start){
                                if ($time_in_datetime < $grace_period_total) {
                                    $late = '00:00:00';
                                } else {
                                    $late = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                }
                            }else{
                                //subtract 1 hour sa late
                                $lates = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                $late_datetime = new DateTime($lates);
                                $late_datetime->sub(new DateInterval('PT1H'));
                                $late = $late_datetime->format('H:i:s');
                            }

                            //para naman sa total work
                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                $grace_period_total = new DateTime($col_friday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }
                                // Check if $time_in is less than the sum of $col_friday_timein and grace period
                                if ($time_in_datetime < $grace_period_total) {
                                    $total_works = $actual_timeout->diff($actual_timein)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                } else {
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }
                            }else{
                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                $total_work_datetime = new DateTime($total_works);
                                $total_work = $total_work_datetime->format('H:i:s');
                            }

                            $updateAtt = mysqli_query($conn,"UPDATE attendances SET `status` = 'Present', `time_in` = '$TimeDTR', `late` = '$late', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_in` = '00:00:00' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                            header("Location: ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                        }else{
                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                        }
                    } //Close bracket Friday


                    else if($day_of_week === 'Saturday'){
                        $checkAtt = mysqli_query($conn, "SELECT * FROM attendances WHERE `time_in` = '00:00:00' AND `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                        if($checkAtt && mysqli_num_rows($checkAtt) > 0){
                            $attendrow = mysqli_fetch_assoc($checkAtt);
                            $fetch_timeout = $attendrow['time_out'];

                            $time_in_datetime = new DateTime($TimeDTR);
                            $time_out_datetime = new DateTime($fetch_timeout);

                            $actual_timein = new DateTime($col_saturday_timein);
                            $actual_timeout = new DateTime($col_saturday_timeout);

                            $lunchbreak_start = new DateTime('12:00:00');
                            $lunchbreak_end = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_saturday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }

                            // Check if $TimeDTR is greater than sa sum ng $col_saturday_timein and grace period
                            if($time_in_datetime < $lunchbreak_start){
                                if ($time_in_datetime < $grace_period_total) {
                                    $late = '00:00:00';
                                } else {
                                    $late = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                }
                            }else{
                                //subtract 1 hour sa late
                                $lates = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                $late_datetime = new DateTime($lates);
                                $late_datetime->sub(new DateInterval('PT1H'));
                                $late = $late_datetime->format('H:i:s');
                            }

                            //para naman sa total work
                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                $grace_period_total = new DateTime($col_saturday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }
                                // Check if $time_in is less than the sum of $col_saturday_timein and grace period
                                if ($time_in_datetime < $grace_period_total) {
                                    $total_works = $actual_timeout->diff($actual_timein)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                } else {
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }
                            }else{
                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                $total_work_datetime = new DateTime($total_works);
                                $total_work = $total_work_datetime->format('H:i:s');
                            }

                            $updateAtt = mysqli_query($conn,"UPDATE attendances SET `status` = 'Present', `time_in` = '$TimeDTR', `late` = '$late', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_in` = '00:00:00' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                            header("Location: ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                        }else{
                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                        }
                    } //Close bracket Saturday


                    else if($day_of_week === 'Sunday'){
                        $checkAtt = mysqli_query($conn, "SELECT * FROM attendances WHERE `time_in` = '00:00:00' AND `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                        if($checkAtt && mysqli_num_rows($checkAtt) > 0){
                            $attendrow = mysqli_fetch_assoc($checkAtt);
                            $fetch_timeout = $attendrow['time_out'];

                            $time_in_datetime = new DateTime($TimeDTR);
                            $time_out_datetime = new DateTime($fetch_timeout);

                            $actual_timein = new DateTime($col_sunday_timein);
                            $actual_timeout = new DateTime($col_sunday_timeout);

                            $lunchbreak_start = new DateTime('12:00:00');
                            $lunchbreak_end = new DateTime('13:00:00');

                            $grace_period_total = new DateTime($col_sunday_timein);
                            $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                            
                            if ($grace_period_minutes > 0) {
                                $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                $grace_period_total->add($grace_period_interval);
                            }

                            // Check if $TimeDTR is greater than sa sum ng $col_sunday_timein and grace period
                            if($time_in_datetime < $lunchbreak_start){
                                if ($time_in_datetime < $grace_period_total) {
                                    $late = '00:00:00';
                                } else {
                                    $late = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                }
                            }else{
                                //subtract 1 hour sa late
                                $lates = $time_in_datetime->diff($actual_timein)->format('%H:%I:%S');
                                $late_datetime = new DateTime($lates);
                                $late_datetime->sub(new DateInterval('PT1H'));
                                $late = $late_datetime->format('H:i:s');
                            }

                            //para naman sa total work
                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                $grace_period_total = new DateTime($col_sunday_timein);
                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                if ($grace_period_minutes > 0) {
                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                    $grace_period_total->add($grace_period_interval);
                                }
                                // Check if $time_in is less than the sum of $col_sunday_timein and grace period
                                if ($time_in_datetime < $grace_period_total) {
                                    $total_works = $actual_timeout->diff($actual_timein)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                } else {
                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($total_works);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_work = $total_work_datetime->format('H:i:s');
                                }
                            }else{
                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                $total_work_datetime = new DateTime($total_works);
                                $total_work = $total_work_datetime->format('H:i:s');
                            }

                            $updateAtt = mysqli_query($conn,"UPDATE attendances SET `status` = 'Present', `time_in` = '$TimeDTR', `late` = '$late', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_in` = '00:00:00' AND `date` = '$DateDTR' AND `time_out` != '00:00:00'");
                            header("Location: ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                        }else{
                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                        }
                    } //Close bracket Sunday

           }
        }
    } //type DTR (IN)
                                else if($TypeDTR === 'OUT'){
                                    $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$EmployeeId'");
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
                                        $col_grace_period = $row_sched_tb['grace_period'];
                    
                    
                                        $day_of_week = date('l', strtotime($DateDTR));

                                        if($day_of_week === 'Monday'){
                                           $attquery = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                           
                                           if($attquery && mysqli_num_rows($attquery) > 0){
                                              $attrow = $attquery->fetch_assoc();
                                              $fetch_timein = $attrow['time_in'];

                                              $time_in_datetime = new DateTime($fetch_timein);
                                              $time_out_datetime = new DateTime($TimeDTR);

                                              $lunchbreak_start = new DateTime('12:00:00');
                                              $lunchbreak_end = new DateTime('13:00:00');  

                                              $SchedTimeIn = new DateTime($col_monday_timein);
                                              $SchedTimeOut = new DateTime($col_monday_timeout);


                                                // Check if there's an approved undertime record for the employee and date
                                                $checkUT = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `status` = 'Approved'");

                                                if ($checkUT && mysqli_num_rows($checkUT) > 0) {
                                                    $rowOt = $checkUT->fetch_assoc();
                                                    $Undertime = $rowOt['total_undertime']; // This will be the initial value of $undertime

                                                    // Check if $time_out_datetime is earlier than the scheduled time out
                                                    if ($time_out_datetime < $SchedTimeOut) {
                                                        $early_interval = $SchedTimeOut->diff($time_out_datetime);
                                                        $undertime = $early_interval->format('%H:%I:%S'); // Use uppercase H and I for hours and minutes
                                                    } else {
                                                        $undertime = '00:00:00';
                                                    }
                                                } else {
                                                    $undertime = '00:00:00';
                                                }

                                                // check if may naapproved na sa overtime table para kung nag-import ng csv ay hindi automatic mag-overtime
                                                $checkOT = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `empid` = '$EmployeeId' AND `work_schedule` = '$DateDTR' AND `status` = 'Approved'");
                                                if ($checkOT && mysqli_num_rows($checkOT) > 0) {
                                                    $rowOt = $checkOT->fetch_assoc();
                                                    $overtime = $rowOt['total_ot']; // Ito ang magiging value ng overtime
                                                
                                                    if ($time_out_datetime > $SchedTimeOut) {
                                                        $intervals = $SchedTimeOut->diff($time_out_datetime);
                                                        $overtimes = $intervals->format('%H:%I:%S');
                                                    } else {
                                                        $overtimes = '00:00:00';
                                                    }
                                                } else {
                                                    $overtimes = '00:00:00';
                                                }

                                              if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                                $grace_period_total = new DateTime($col_monday_timein);
                                                $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                
                                                if ($grace_period_minutes > 0) {
                                                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                    $grace_period_total->add($grace_period_interval);
                                                }
                                                // Check if $time_in is less than the sum of $col_monday_timein and grace period
                                                if ($time_in_datetime < $grace_period_total) {
                                                    $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                    // Subtract 1 hour from total work
                                                    $total_work_datetime = new DateTime($total_works);
                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                } else {
                                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                    // Subtract 1 hour from total work
                                                    $total_work_datetime = new DateTime($total_works);
                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                }
                                            }else{
                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                $total_work_datetime = new DateTime($total_works);
                                                $total_work = $total_work_datetime->format('H:i:s');
                                            }

                                            $upAtt = mysqli_query($conn, "UPDATE attendances SET `time_out` = '$TimeDTR', `early_out` = '$undertime', `overtime` = '$overtimes', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                            header("Location ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                                           }else{
                                            header("Location: ../../dtr_admin.php?error=No attendance found!");
                                           }
                                        }//Close bracket monday

                                        else if($day_of_week === 'Tuesday'){
                                            $attquery = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                            
                                            if($attquery && mysqli_num_rows($attquery) > 0){
                                               $attrow = $attquery->fetch_assoc();
                                               $fetch_timein = $attrow['time_in'];
 
                                               $time_in_datetime = new DateTime($fetch_timein);
                                               $time_out_datetime = new DateTime($TimeDTR);
 
                                               $lunchbreak_start = new DateTime('12:00:00');
                                               $lunchbreak_end = new DateTime('13:00:00');  
 
                                               $SchedTimeIn = new DateTime($col_tuesday_timein);
                                               $SchedTimeOut = new DateTime($col_tuesday_timeout);
 
 
                                                 // Check if there's an approved undertime record for the employee and date
                                                 $checkUT = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `status` = 'Approved'");
 
                                                 if ($checkUT && mysqli_num_rows($checkUT) > 0) {
                                                     $rowOt = $checkUT->fetch_assoc();
                                                     $Undertime = $rowOt['total_undertime']; // This will be the initial value of $undertime
 
                                                     // Check if $time_out_datetime is earlier than the scheduled time out
                                                     if ($time_out_datetime < $SchedTimeOut) {
                                                         $early_interval = $SchedTimeOut->diff($time_out_datetime);
                                                         $undertime = $early_interval->format('%H:%I:%S'); // Use uppercase H and I for hours and minutes
                                                     } else {
                                                         $undertime = '00:00:00';
                                                     }
                                                 } else {
                                                     $undertime = '00:00:00';
                                                 }
 
                                                 // check if may naapproved na sa overtime table para kung nag-import ng csv ay hindi automatic mag-overtime
                                                 $checkOT = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `empid` = '$EmployeeId' AND `work_schedule` = '$DateDTR' AND `status` = 'Approved'");
                                                 if ($checkOT && mysqli_num_rows($checkOT) > 0) {
                                                     $rowOt = $checkOT->fetch_assoc();
                                                     $overtime = $rowOt['total_ot']; // Ito ang magiging value ng overtime
                                                 
                                                     if ($time_out_datetime > $SchedTimeOut) {
                                                         $intervals = $SchedTimeOut->diff($time_out_datetime);
                                                         $overtimes = $intervals->format('%H:%I:%S');
                                                     } else {
                                                         $overtimes = '00:00:00';
                                                     }
                                                 } else {
                                                     $overtimes = '00:00:00';
                                                 }
 
                                               if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                                 $grace_period_total = new DateTime($col_tuesday_timein);
                                                 $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                 
                                                 if ($grace_period_minutes > 0) {
                                                     $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                     $grace_period_total->add($grace_period_interval);
                                                 }
                                                 // Check if $time_in is less than the sum of $col_tuesday_timein and grace period
                                                 if ($time_in_datetime < $grace_period_total) {
                                                     $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 } else {
                                                     $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 }
                                             }else{
                                                 $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                 $total_work_datetime = new DateTime($total_works);
                                                 $total_work = $total_work_datetime->format('H:i:s');
                                             }
 
                                             $upAtt = mysqli_query($conn, "UPDATE attendances SET `time_out` = '$TimeDTR', `early_out` = '$undertime', `overtime` = '$overtimes', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                             header("Location ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                                            }else{
                                             header("Location: ../../dtr_admin.php?error=No attendance found!");
                                            }
                                         }//Close bracket Tuesday

                                         else if($day_of_week === 'Wednesday'){
                                            $attquery = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                            
                                            if($attquery && mysqli_num_rows($attquery) > 0){
                                               $attrow = $attquery->fetch_assoc();
                                               $fetch_timein = $attrow['time_in'];
 
                                               $time_in_datetime = new DateTime($fetch_timein);
                                               $time_out_datetime = new DateTime($TimeDTR);
 
                                               $lunchbreak_start = new DateTime('12:00:00');
                                               $lunchbreak_end = new DateTime('13:00:00');  
 
                                               $SchedTimeIn = new DateTime($col_wednesday_timein);
                                               $SchedTimeOut = new DateTime($col_wednesday_timeout);
 
 
                                                 // Check if there's an approved undertime record for the employee and date
                                                 $checkUT = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `status` = 'Approved'");
 
                                                 if ($checkUT && mysqli_num_rows($checkUT) > 0) {
                                                     $rowOt = $checkUT->fetch_assoc();
                                                     $Undertime = $rowOt['total_undertime']; // This will be the initial value of $undertime
 
                                                     // Check if $time_out_datetime is earlier than the scheduled time out
                                                     if ($time_out_datetime < $SchedTimeOut) {
                                                         $early_interval = $SchedTimeOut->diff($time_out_datetime);
                                                         $undertime = $early_interval->format('%H:%I:%S'); // Use uppercase H and I for hours and minutes
                                                     } else {
                                                         $undertime = '00:00:00';
                                                     }
                                                 } else {
                                                     $undertime = '00:00:00';
                                                 }
 
                                                 // check if may naapproved na sa overtime table para kung nag-import ng csv ay hindi automatic mag-overtime
                                                 $checkOT = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `empid` = '$EmployeeId' AND `work_schedule` = '$DateDTR' AND `status` = 'Approved'");
                                                 if ($checkOT && mysqli_num_rows($checkOT) > 0) {
                                                     $rowOt = $checkOT->fetch_assoc();
                                                     $overtime = $rowOt['total_ot']; // Ito ang magiging value ng overtime
                                                 
                                                     if ($time_out_datetime > $SchedTimeOut) {
                                                         $intervals = $SchedTimeOut->diff($time_out_datetime);
                                                         $overtimes = $intervals->format('%H:%I:%S');
                                                     } else {
                                                         $overtimes = '00:00:00';
                                                     }
                                                 } else {
                                                     $overtimes = '00:00:00';
                                                 }
 
                                               if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                                 $grace_period_total = new DateTime($col_wednesday_timein);
                                                 $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                 
                                                 if ($grace_period_minutes > 0) {
                                                     $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                     $grace_period_total->add($grace_period_interval);
                                                 }
                                                 // Check if $time_in is less than the sum of $col_wednesday_timein and grace period
                                                 if ($time_in_datetime < $grace_period_total) {
                                                     $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 } else {
                                                     $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 }
                                             }else{
                                                 $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                 $total_work_datetime = new DateTime($total_works);
                                                 $total_work = $total_work_datetime->format('H:i:s');
                                             }
 
                                             $upAtt = mysqli_query($conn, "UPDATE attendances SET `time_out` = '$TimeDTR', `early_out` = '$undertime', `overtime` = '$overtimes', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                             header("Location ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                                            }else{
                                             header("Location: ../../dtr_admin.php?error=No attendance found!");
                                            }
                                         }//Close bracket Wednesday

                                         else if($day_of_week === 'Thursday'){
                                            $attquery = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                            
                                            if($attquery && mysqli_num_rows($attquery) > 0){
                                               $attrow = $attquery->fetch_assoc();
                                               $fetch_timein = $attrow['time_in'];
 
                                               $time_in_datetime = new DateTime($fetch_timein);
                                               $time_out_datetime = new DateTime($TimeDTR);
 
                                               $lunchbreak_start = new DateTime('12:00:00');
                                               $lunchbreak_end = new DateTime('13:00:00');  
 
                                               $SchedTimeIn = new DateTime($col_thursday_timein);
                                               $SchedTimeOut = new DateTime($col_thursday_timeout);
 
 
                                                 // Check if there's an approved undertime record for the employee and date
                                                 $checkUT = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `status` = 'Approved'");
 
                                                 if ($checkUT && mysqli_num_rows($checkUT) > 0) {
                                                     $rowOt = $checkUT->fetch_assoc();
                                                     $Undertime = $rowOt['total_undertime']; // This will be the initial value of $undertime
 
                                                     // Check if $time_out_datetime is earlier than the scheduled time out
                                                     if ($time_out_datetime < $SchedTimeOut) {
                                                         $early_interval = $SchedTimeOut->diff($time_out_datetime);
                                                         $undertime = $early_interval->format('%H:%I:%S'); // Use uppercase H and I for hours and minutes
                                                     } else {
                                                         $undertime = '00:00:00';
                                                     }
                                                 } else {
                                                     $undertime = '00:00:00';
                                                 }
 
                                                 // check if may naapproved na sa overtime table para kung nag-import ng csv ay hindi automatic mag-overtime
                                                 $checkOT = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `empid` = '$EmployeeId' AND `work_schedule` = '$DateDTR' AND `status` = 'Approved'");
                                                 if ($checkOT && mysqli_num_rows($checkOT) > 0) {
                                                     $rowOt = $checkOT->fetch_assoc();
                                                     $overtime = $rowOt['total_ot']; // Ito ang magiging value ng overtime
                                                 
                                                     if ($time_out_datetime > $SchedTimeOut) {
                                                         $intervals = $SchedTimeOut->diff($time_out_datetime);
                                                         $overtimes = $intervals->format('%H:%I:%S');
                                                     } else {
                                                         $overtimes = '00:00:00';
                                                     }
                                                 } else {
                                                     $overtimes = '00:00:00';
                                                 }
 
                                               if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                                 $grace_period_total = new DateTime($col_thursday_timein);
                                                 $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                 
                                                 if ($grace_period_minutes > 0) {
                                                     $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                     $grace_period_total->add($grace_period_interval);
                                                 }
                                                 // Check if $time_in is less than the sum of $col_thursday_timein and grace period
                                                 if ($time_in_datetime < $grace_period_total) {
                                                     $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 } else {
                                                     $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 }
                                             }else{
                                                 $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                 $total_work_datetime = new DateTime($total_works);
                                                 $total_work = $total_work_datetime->format('H:i:s');
                                             }
 
                                             $upAtt = mysqli_query($conn, "UPDATE attendances SET `time_out` = '$TimeDTR', `early_out` = '$undertime', `overtime` = '$overtimes', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                             header("Location ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                                            }else{
                                             header("Location: ../../dtr_admin.php?error=No attendance found!");
                                            }
                                         }//Close bracket Thursday

                                         else if($day_of_week === 'Friday'){
                                            $attquery = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                            
                                            if($attquery && mysqli_num_rows($attquery) > 0){
                                               $attrow = $attquery->fetch_assoc();
                                               $fetch_timein = $attrow['time_in'];
 
                                               $time_in_datetime = new DateTime($fetch_timein);
                                               $time_out_datetime = new DateTime($TimeDTR);
 
                                               $lunchbreak_start = new DateTime('12:00:00');
                                               $lunchbreak_end = new DateTime('13:00:00');  
 
                                               $SchedTimeIn = new DateTime($col_friday_timein);
                                               $SchedTimeOut = new DateTime($col_friday_timeout);
 
 
                                                 // Check if there's an approved undertime record for the employee and date
                                                 $checkUT = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `status` = 'Approved'");
 
                                                 if ($checkUT && mysqli_num_rows($checkUT) > 0) {
                                                     $rowOt = $checkUT->fetch_assoc();
                                                     $Undertime = $rowOt['total_undertime']; // This will be the initial value of $undertime
 
                                                     // Check if $time_out_datetime is earlier than the scheduled time out
                                                     if ($time_out_datetime < $SchedTimeOut) {
                                                         $early_interval = $SchedTimeOut->diff($time_out_datetime);
                                                         $undertime = $early_interval->format('%H:%I:%S'); // Use uppercase H and I for hours and minutes
                                                     } else {
                                                         $undertime = '00:00:00';
                                                     }
                                                 } else {
                                                     $undertime = '00:00:00';
                                                 }
 
                                                 // check if may naapproved na sa overtime table para kung nag-import ng csv ay hindi automatic mag-overtime
                                                 $checkOT = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `empid` = '$EmployeeId' AND `work_schedule` = '$DateDTR' AND `status` = 'Approved'");
                                                 if ($checkOT && mysqli_num_rows($checkOT) > 0) {
                                                     $rowOt = $checkOT->fetch_assoc();
                                                     $overtime = $rowOt['total_ot']; // Ito ang magiging value ng overtime
                                                 
                                                     if ($time_out_datetime > $SchedTimeOut) {
                                                         $intervals = $SchedTimeOut->diff($time_out_datetime);
                                                         $overtimes = $intervals->format('%H:%I:%S');
                                                     } else {
                                                         $overtimes = '00:00:00';
                                                     }
                                                 } else {
                                                     $overtimes = '00:00:00';
                                                 }
 
                                               if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                                 $grace_period_total = new DateTime($col_friday_timein);
                                                 $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                 
                                                 if ($grace_period_minutes > 0) {
                                                     $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                     $grace_period_total->add($grace_period_interval);
                                                 }
                                                 // Check if $time_in is less than the sum of $col_friday_timein and grace period
                                                 if ($time_in_datetime < $grace_period_total) {
                                                     $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 } else {
                                                     $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 }
                                             }else{
                                                 $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                 $total_work_datetime = new DateTime($total_works);
                                                 $total_work = $total_work_datetime->format('H:i:s');
                                             }
 
                                             $upAtt = mysqli_query($conn, "UPDATE attendances SET `time_out` = '$TimeDTR', `early_out` = '$undertime', `overtime` = '$overtimes', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                             header("Location ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                                            }else{
                                             header("Location: ../../dtr_admin.php?error=No attendance found!");
                                            }
                                         }//Close bracket Friday

                                         else if($day_of_week === 'Saturday'){
                                            $attquery = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                            
                                            if($attquery && mysqli_num_rows($attquery) > 0){
                                               $attrow = $attquery->fetch_assoc();
                                               $fetch_timein = $attrow['time_in'];
 
                                               $time_in_datetime = new DateTime($fetch_timein);
                                               $time_out_datetime = new DateTime($TimeDTR);
 
                                               $lunchbreak_start = new DateTime('12:00:00');
                                               $lunchbreak_end = new DateTime('13:00:00');  
 
                                               $SchedTimeIn = new DateTime($col_saturday_timein);
                                               $SchedTimeOut = new DateTime($col_saturday_timeout);
 
 
                                                 // Check if there's an approved undertime record for the employee and date
                                                 $checkUT = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `status` = 'Approved'");
 
                                                 if ($checkUT && mysqli_num_rows($checkUT) > 0) {
                                                     $rowOt = $checkUT->fetch_assoc();
                                                     $Undertime = $rowOt['total_undertime']; // This will be the initial value of $undertime
 
                                                     // Check if $time_out_datetime is earlier than the scheduled time out
                                                     if ($time_out_datetime < $SchedTimeOut) {
                                                         $early_interval = $SchedTimeOut->diff($time_out_datetime);
                                                         $undertime = $early_interval->format('%H:%I:%S'); // Use uppercase H and I for hours and minutes
                                                     } else {
                                                         $undertime = '00:00:00';
                                                     }
                                                 } else {
                                                     $undertime = '00:00:00';
                                                 }
 
                                                 // check if may naapproved na sa overtime table para kung nag-import ng csv ay hindi automatic mag-overtime
                                                 $checkOT = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `empid` = '$EmployeeId' AND `work_schedule` = '$DateDTR' AND `status` = 'Approved'");
                                                 if ($checkOT && mysqli_num_rows($checkOT) > 0) {
                                                     $rowOt = $checkOT->fetch_assoc();
                                                     $overtime = $rowOt['total_ot']; // Ito ang magiging value ng overtime
                                                 
                                                     if ($time_out_datetime > $SchedTimeOut) {
                                                         $intervals = $SchedTimeOut->diff($time_out_datetime);
                                                         $overtimes = $intervals->format('%H:%I:%S');
                                                     } else {
                                                         $overtimes = '00:00:00';
                                                     }
                                                 } else {
                                                     $overtimes = '00:00:00';
                                                 }
 
                                               if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                                 $grace_period_total = new DateTime($col_saturday_timein);
                                                 $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                 
                                                 if ($grace_period_minutes > 0) {
                                                     $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                     $grace_period_total->add($grace_period_interval);
                                                 }
                                                 // Check if $time_in is less than the sum of $col_saturday_timein and grace period
                                                 if ($time_in_datetime < $grace_period_total) {
                                                     $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 } else {
                                                     $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 }
                                             }else{
                                                 $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                 $total_work_datetime = new DateTime($total_works);
                                                 $total_work = $total_work_datetime->format('H:i:s');
                                             }
 
                                             $upAtt = mysqli_query($conn, "UPDATE attendances SET `time_out` = '$TimeDTR', `early_out` = '$undertime', `overtime` = '$overtimes', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                             header("Location ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                                            }else{
                                             header("Location: ../../dtr_admin.php?error=No attendance found!");
                                            }
                                         }//Close bracket Saturday


                                         else if($day_of_week === 'Sunday'){
                                            $attquery = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                            
                                            if($attquery && mysqli_num_rows($attquery) > 0){
                                               $attrow = $attquery->fetch_assoc();
                                               $fetch_timein = $attrow['time_in'];
 
                                               $time_in_datetime = new DateTime($fetch_timein);
                                               $time_out_datetime = new DateTime($TimeDTR);
 
                                               $lunchbreak_start = new DateTime('12:00:00');
                                               $lunchbreak_end = new DateTime('13:00:00');  
 
                                               $SchedTimeIn = new DateTime($col_sunday_timein);
                                               $SchedTimeOut = new DateTime($col_sunday_timeout);
 
 
                                                 // Check if there's an approved undertime record for the employee and date
                                                 $checkUT = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE `empid` = '$EmployeeId' AND `date` = '$DateDTR' AND `status` = 'Approved'");
 
                                                 if ($checkUT && mysqli_num_rows($checkUT) > 0) {
                                                     $rowOt = $checkUT->fetch_assoc();
                                                     $Undertime = $rowOt['total_undertime']; // This will be the initial value of $undertime
 
                                                     // Check if $time_out_datetime is earlier than the scheduled time out
                                                     if ($time_out_datetime < $SchedTimeOut) {
                                                         $early_interval = $SchedTimeOut->diff($time_out_datetime);
                                                         $undertime = $early_interval->format('%H:%I:%S'); // Use uppercase H and I for hours and minutes
                                                     } else {
                                                         $undertime = '00:00:00';
                                                     }
                                                 } else {
                                                     $undertime = '00:00:00';
                                                 }
 
                                                 // check if may naapproved na sa overtime table para kung nag-import ng csv ay hindi automatic mag-overtime
                                                 $checkOT = mysqli_query($conn, "SELECT * FROM overtime_tb WHERE `empid` = '$EmployeeId' AND `work_schedule` = '$DateDTR' AND `status` = 'Approved'");
                                                 if ($checkOT && mysqli_num_rows($checkOT) > 0) {
                                                     $rowOt = $checkOT->fetch_assoc();
                                                     $overtime = $rowOt['total_ot']; // Ito ang magiging value ng overtime
                                                 
                                                     if ($time_out_datetime > $SchedTimeOut) {
                                                         $intervals = $SchedTimeOut->diff($time_out_datetime);
                                                         $overtimes = $intervals->format('%H:%I:%S');
                                                     } else {
                                                         $overtimes = '00:00:00';
                                                     }
                                                 } else {
                                                     $overtimes = '00:00:00';
                                                 }
 
                                               if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start){
                                                 $grace_period_total = new DateTime($col_sunday_timein);
                                                 $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
                                 
                                                 if ($grace_period_minutes > 0) {
                                                     $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                     $grace_period_total->add($grace_period_interval);
                                                 }
                                                 // Check if $time_in is less than the sum of $col_sunday_timein and grace period
                                                 if ($time_in_datetime < $grace_period_total) {
                                                     $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 } else {
                                                     $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                     // Subtract 1 hour from total work
                                                     $total_work_datetime = new DateTime($total_works);
                                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                                     $total_work = $total_work_datetime->format('H:i:s');
                                                 }
                                             }else{
                                                 $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                 $total_work_datetime = new DateTime($total_works);
                                                 $total_work = $total_work_datetime->format('H:i:s');
                                             }
 
                                             $upAtt = mysqli_query($conn, "UPDATE attendances SET `time_out` = '$TimeDTR', `early_out` = '$undertime', `overtime` = '$overtimes', `total_work` = '$total_work' WHERE `empid` = '$EmployeeId' AND `time_out` = '00:00:00' AND `date` = '$DateDTR' AND `time_in` != '00:00:00'");
                                             header("Location ../../dtr_admin.php?msg=Correction for $DateDTR success!");
                                            }else{
                                             header("Location: ../../dtr_admin.php?error=No attendance found!");
                                            }
                                         }//Close bracket Sunday
                                        
                                    }
                                }
    }
}


?>