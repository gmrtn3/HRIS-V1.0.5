<?php
// Load the database configuration file
include '../../config.php';

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
    'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $status   = $line[0];
                $empid  = $line[1];
                $date = $line[2];
                $time_in = $line[3];
                $time_out = $line[4];
                $late = '';
                $early_out = '';
                $overtime = '';  
                $total_work = '';


                $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$empid'");
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
                        
                        $day_of_week = date('l', strtotime($date)); // get the day of the week using the "l" format specifier 

                        if($day_of_week === 'Monday'){
                            if($time_in > $col_monday_timein){
                                $time_in_datetime = new DateTime($time_in);
                                $scheduled_time = new DateTime($col_monday_timein);
                                $interval = $time_in_datetime->diff($scheduled_time);
                                $tardiness = $interval->format('%h:%i:%s');

                            }                           
                            if($time_out < $col_monday_timeout){
                                $time_out_datetime1 = new DateTime($time_out);
                                $scheduled_outs = new DateTime($col_monday_timeout);
                                $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                $undertime = $early_interval->format('%h:%i:%s');

                            } else { 
                                $undertime = '00:00:00';
                            }
                            if ($time_out > $col_monday_timeout) {
                                $time_out_datetime = new DateTime($time_out);
                                $scheduled_timeout = new DateTime( $col_monday_timeout);
                                $intervals = $time_out_datetime->diff($scheduled_timeout);
                                $overtime = $intervals->format('%h:%i:%s');
                            } else {
                                $overtime = '00:00:00';
                            }

                            $lunchbreak_time = strtotime('12:00:00');
                            $time_in_att = strtotime($time_in);
                            $include_lunchbreak = ($time_in_att < $lunchbreak_time);
            
                            // Calculate the total work time
                            $total_work = strtotime($time_out) - strtotime($time_in);
                            if ($include_lunchbreak) {
                                $total_work -= 7200; // Subtract 1 hour (lunch break)
                            }
                            $total_work = date('H:i:s', $total_work);
                        } //Close bracket Monday

                        else if($day_of_week === 'Tuesday'){
                            if($time_in > $col_tuesday_timein){
                                $time_in_datetime = new DateTime($time_in);
                                $scheduled_time = new DateTime($col_tuesday_timein);
                                $interval = $time_in_datetime->diff($scheduled_time);
                                $tardiness = $interval->format('%h:%i:%s');

                            }                            
                            if($time_out < $col_tuesday_timeout){
                                $time_out_datetime1 = new DateTime($time_out);
                                $scheduled_outs = new DateTime($col_tuesday_timeout);
                                $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                $undertime = $early_interval->format('%h:%i:%s');

                            } else { 
                                $undertime = '00:00:00';
                            }
                            if ($time_out > $col_tuesday_timeout) {
                                $time_out_datetime = new DateTime($time_out);
                                $scheduled_timeout = new DateTime( $col_tuesday_timeout);
                                $intervals = $time_out_datetime->diff($scheduled_timeout);
                                $overtime = $intervals->format('%h:%i:%s');

                            } else {
                                $overtime = '00:00:00';
                            }

                            $lunchbreak_time = strtotime('12:00:00');
                            $time_in_att = strtotime($time_in);
                            $include_lunchbreak = ($time_in_att < $lunchbreak_time);
            
                            // Calculate the total work time
                            $total_work = strtotime($time_out) - strtotime($time_in);
                            if ($include_lunchbreak) {
                                $total_work -= 7200; // Subtract 1 hour (lunch break)
                            }
                            $total_work = date('H:i:s', $total_work);
                        } //Close bracket Tuesday

                                else if($day_of_week === 'Wednesday'){
                                    // Check if the employee is late
                                    if($time_in > $col_wednesday_timein){
                                        // Calculate the amount of late
                                        $time_in_datetime = new DateTime($time_in);
                                        $scheduled_time = new DateTime($col_wednesday_timein);
                                        $interval = $time_in_datetime->diff($scheduled_time);
                                        $tardiness = $interval->format('%h:%i:%s');

                                    }
                                    
                                    if($time_out < $col_wednesday_timeout){
                                        $time_out_datetime1 = new DateTime($time_out);
                                        $scheduled_outs = new DateTime($col_wednesday_timeout);
                                        $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                        $undertime = $early_interval->format('%h:%i:%s');

                                    } else { 
                                        $undertime = '00:00:00';
                                    }

                                    if ($time_out > $col_wednesday_timeout) {
                                        // Calculate overtime
                                        $time_out_datetime = new DateTime($time_out);
                                        $scheduled_timeout = new DateTime( $col_wednesday_timeout);
                                        $intervals = $time_out_datetime->diff($scheduled_timeout);
                                        $overtime = $intervals->format('%h:%i:%s');

                                    } else {
                                        $overtime = '00:00:00';
                                    }
                                    $lunchbreak_time = strtotime('12:00:00');
                                    $time_in_att = strtotime($time_in);
                                    $include_lunchbreak = ($time_in_att < $lunchbreak_time);
                    
                                    // Calculate the total work time
                                    $total_work = strtotime($time_out) - strtotime($time_in);
                                    if ($include_lunchbreak) {
                                        $total_work -= 7200; // Subtract 1 hour (lunch break)
                                    }
                                    $total_work = date('H:i:s', $total_work);
                                } //Close bracket Wednesday

                                    else if($day_of_week === 'Thursday'){
                                        // Check if the employee is late
                                        if($time_in > $col_thursday_timein){
                                            // Calculate the amount of late
                                            $time_in_datetime = new DateTime($time_in);
                                            $scheduled_time = new DateTime($col_thursday_timein);
                                            $interval = $time_in_datetime->diff($scheduled_time);
                                            $tardiness = $interval->format('%h:%i:%s');

                                        }
                                        
                                        if($time_out < $col_thursday_timeout){
                                            $time_out_datetime1 = new DateTime($time_out);
                                            $scheduled_outs = new DateTime($col_thursday_timeout);
                                            $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                            $undertime = $early_interval->format('%h:%i:%s');

                                        } else { 
                                            $undertime = '00:00:00';
                                        }

                                        if ($time_out > $col_thursday_timeout) {
                                            // Calculate overtime
                                            $time_out_datetime = new DateTime($time_out);
                                            $scheduled_timeout = new DateTime( $col_thursday_timeout);
                                            $intervals = $time_out_datetime->diff($scheduled_timeout);
                                            $overtime = $intervals->format('%h:%i:%s');

                                        } else {
                                            $overtime = '00:00:00';
                                        }
                                        $lunchbreak_time = strtotime('12:00:00');
                                        $time_in_att = strtotime($time_in);
                                        $include_lunchbreak = ($time_in_att < $lunchbreak_time);
                        
                                        // Calculate the total work time
                                        $total_work = strtotime($time_out) - strtotime($time_in);
                                        if ($include_lunchbreak) {
                                            $total_work -= 7200; // Subtract 1 hour (lunch break)
                                        }
                                        $total_work = date('H:i:s', $total_work);
                                    } //Close bracket Thursday

                                    else if($day_of_week === 'Friday'){
                                        // Check if the employee is late
                                        if($time_in > $col_friday_timein){
                                            // Calculate the amount of late
                                            $time_in_datetime = new DateTime($time_in);
                                            $scheduled_time = new DateTime($col_friday_timein);
                                            $interval = $time_in_datetime->diff($scheduled_time);
                                            $tardiness = $interval->format('%h:%i:%s');

                                        }
                                        
                                        if($time_out < $col_friday_timeout){
                                            $time_out_datetime1 = new DateTime($time_out);
                                            $scheduled_outs = new DateTime($col_friday_timeout);
                                            $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                            $undertime = $early_interval->format('%h:%i:%s');

                                        } else { 
                                            $undertime = '00:00:00';
                                        }

                                        if ($time_out > $col_friday_timeout) {
                                            // Calculate overtime
                                            $time_out_datetime = new DateTime($time_out);
                                            $scheduled_timeout = new DateTime( $col_friday_timeout);
                                            $intervals = $time_out_datetime->diff($scheduled_timeout);
                                            $overtime = $intervals->format('%h:%i:%s');

                                        } else {
                                            $overtime = '00:00:00';
                                        }
                                        $lunchbreak_time = strtotime('12:00:00');
                                        $time_in_att = strtotime($time_in);
                                        $include_lunchbreak = ($time_in_att < $lunchbreak_time);
                        
                                        // Calculate the total work time
                                        $total_work = strtotime($time_out) - strtotime($time_in);
                                        if ($include_lunchbreak) {
                                            $total_work -= 7200; // Subtract 1 hour (lunch break)
                                        }
                                        $total_work = date('H:i:s', $total_work);
                                    } //Close bracket Friday

                                    else if($day_of_week === 'Saturday'){
                                        // Check if the employee is late
                                        if($time_in > $col_saturday_timein){
                                            // Calculate the amount of late
                                            $time_in_datetime = new DateTime($time_in);
                                            $scheduled_time = new DateTime($col_saturday_timein);
                                            $interval = $time_in_datetime->diff($scheduled_time);
                                            $tardiness = $interval->format('%h:%i:%s');

                                        }
                                        
                                        if($time_out < $col_saturday_timeout){
                                            $time_out_datetime1 = new DateTime($time_out);
                                            $scheduled_outs = new DateTime($col_saturday_timeout);
                                            $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                            $undertime = $early_interval->format('%h:%i:%s');

                                        } else { 
                                            $undertime = '00:00:00';
                                        }

                                        if ($time_out > $col_saturday_timeout) {
                                            // Calculate overtime
                                            $time_out_datetime = new DateTime($time_out);
                                            $scheduled_timeout = new DateTime($col_saturday_timeout);
                                            $intervals = $time_out_datetime->diff($scheduled_timeout);
                                            $overtime = $intervals->format('%h:%i:%s');

                                        } else {
                                            $overtime = '00:00:00';
                                        }
                                        $lunchbreak_time = strtotime('12:00:00');
                                        $time_in_att = strtotime($time_in);
                                        $include_lunchbreak = ($time_in_att < $lunchbreak_time);
                        
                                        // Calculate the total work time
                                        $total_work = strtotime($time_out) - strtotime($time_in);
                                        if ($include_lunchbreak) {
                                            $total_work -= 7200; // Subtract 1 hour (lunch break)
                                        }
                                        $total_work = date('H:i:s', $total_work);
                                    } //Close bracket Saturday

                                    else if($day_of_week === 'Sunday'){
                                        // Check if the employee is late
                                        if($time_in > $col_sunday_timein){
                                            // Calculate the amount of late
                                            $time_in_datetime = new DateTime($time_in);
                                            $scheduled_time = new DateTime($col_sunday_timein);
                                            $interval = $time_in_datetime->diff($scheduled_time);
                                            $tardiness = $interval->format('%h:%i:%s');

                                        }
                                        
                                        if($time_out < $col_sunday_timeout){
                                            $time_out_datetime1 = new DateTime($time_out);
                                            $scheduled_outs = new DateTime($col_sunday_timeout);
                                            $early_interval = $scheduled_outs->diff($time_out_datetime1);
                                            $undertime = $early_interval->format('%h:%i:%s');

                                        } else { 
                                            $undertime = '00:00:00';
                                        }

                                        if ($time_out > $col_sunday_timeout) {
                                            // Calculate overtime
                                            $time_out_datetime = new DateTime($time_out);
                                            $scheduled_timeout = new DateTime($col_sunday_timeout);
                                            $intervals = $time_out_datetime->diff($scheduled_timeout);
                                            $overtime = $intervals->format('%h:%i:%s');

                                        } else {
                                            $overtime = '00:00:00';
                                        }
                                        $lunchbreak_time = strtotime('12:00:00');
                                        $time_in_att = strtotime($time_in);
                                        $include_lunchbreak = ($time_in_att < $lunchbreak_time);
                        
                                        // Calculate the total work time
                                        $total_work = strtotime($time_out) - strtotime($time_in);
                                        if ($include_lunchbreak) {
                                            $total_work -= 7200; // Subtract 1 hour (lunch break)
                                        }
                                        $total_work = date('H:i:s', $total_work);
                                    } //Close bracket sunday


                        
			            $result_emp = mysqli_query($conn, "SELECT * FROM employee_tb WHERE empid = '$empid'");
                        if(mysqli_num_rows($result_emp) == 0){
                            echo '<script>alert("Error: Unable to insert data for non-existing Employee ID because the Employee ID does not exist in the database.")</script>';
                            echo "<script>window.location.href = '../../dtRecords.php';</script>";
                            exit;
                        }else{
                            $prevQuery = "SELECT * FROM attendances WHERE empid = '".$line[1]."'";
                            $prevResult = $conn->query($prevQuery);
                        }
                        $query = "";
                        if($prevResult->num_rows > 0){
                            // Update member data in the database
                            $query = "UPDATE attendances SET status = 'Present',
                            empid = '".$empid."', date = '".$date."', time_in = '".$time_in."', time_out = '".$time_out."', late = '".$tardiness."', 
                            early_out = '".$undertime."', overtime = '".$overtime."', total_work = '".$total_work."' WHERE empid = '".$empid."' "; 
                        }else{
                            $query = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`) 
                             VALUES ('Present', '$empid', '$date', '$time_in', '$time_out', '$tardiness', '$undertime', '$overtime', '$total_work')";
                        }
                        //echo $query;

                        $conn->query($query);
          }
        }
      }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: ../../dtRecords.php".$qstring);