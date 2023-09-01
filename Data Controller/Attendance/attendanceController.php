<?php

// Load the database configuration file
$server = "localhost";
$user = "root";
$pass ="";
$database = "hris_db";

$db = mysqli_connect($server, $user, $pass, $database);


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
                $total_rest = '';

                
                  // Check if empid exists in the employee_tb
                $empQuery = "SELECT * FROM employee_tb WHERE empid = '$empid'";
                $empResult = $db->query($empQuery);
                if(mysqli_num_rows($empResult) < 1) {
                    // echo '<script>alert("Error: Unable to insert data for non-existing Employee ID because the Employee ID does not exist in the database.")</script>';
                    echo "<script>window.location.href = '../../attendance?noEmpid';</script>";
                    exit;
                }else  {
                
                    

    
                $conn = mysqli_connect("localhost", "root", "", "hris_db");
                $sql = "SELECT * FROM empschedule_tb WHERE empid = $empid";
                $resulta = mysqli_query($conn, $sql);
                if(mysqli_num_rows($resulta) > 0){
                    $row1 = mysqli_fetch_assoc($resulta);

                    

                    $stmt = "SELECT 
                    DATE_SUB(DATE(NOW()), INTERVAL WEEKDAY(NOW()) DAY) AS monday_date,
                    DATE_ADD(DATE(NOW()), INTERVAL (1 - WEEKDAY(NOW())) DAY) AS tuesday_date,
                    DATE_ADD(DATE(NOW()), INTERVAL (2 - WEEKDAY(NOW())) DAY) AS wednesday_date,
                    DATE_ADD(DATE(NOW()), INTERVAL (3 - WEEKDAY(NOW())) DAY) AS thursday_date,
                    DATE_ADD(DATE(NOW()), INTERVAL (4 - WEEKDAY(NOW())) DAY) AS friday_date,
                    DATE_ADD(DATE(NOW()), INTERVAL (5 - WEEKDAY(NOW())) DAY) AS saturday_date,
                    DATE_ADD(DATE(NOW()), INTERVAL (6 - WEEKDAY(NOW())) DAY) AS sunday_date,
                    mon_timein,
                    mon_timeout,
                    tues_timein,
                    tues_timeout,
                    wed_timein,
                    wed_timeout,
                    thurs_timein,
                    thurs_timeout,
                    fri_timein,
                    fri_timeout,
                    sat_timein,
                    sat_timeout,
                    sun_timein,
                    sun_timeout,
                    grace_period,
                    sched_ot
                FROM schedule_tb
                WHERE schedule_name = '".$row1['schedule_name']."'";
              

                } else{
                    // echo '<script> alert("Employee has no schedule!"); </script>';
                    header("Location: ../../attendance.php?noSchedule");
                    exit;
                }

                $result = mysqli_query($conn, $stmt);
                while($time = mysqli_fetch_assoc($result)){

                $grace_period = $time['grace_period'];  
                
                $sched_ot = $time['sched_ot'];

                  
                
                // $monday  = $time['monday_date'];
                // $tuesday = $time['tuesday_date'];

                $monday = date('l', strtotime(strtr($time['monday_date'], '/', '-')));
                

                $monday_timein = $time['mon_timein'];
                $monday_timeout = $time['mon_timeout'];
                
                
                $tuesday = date('l', strtotime(strtr($time['tuesday_date'], '/', '-'))); 
              
                

                $tuesday_timein = $time['tues_timein'];
                $tuesday_timeout = $time['tues_timeout'];

                $wednesday = date('l', strtotime(strtr($time['wednesday_date'], '/', '-')));  
               

                $wednesday_timein = $time['wed_timein'];
                $wednesday_timeout = $time['wed_timeout'];

                $thursday = date('l', strtotime(strtr($time['thursday_date'], '/', '-')));   
              

                $thursday_timein = $time['thurs_timein'];
                $thursday_timeout = $time['thurs_timeout'];

                $friday = date('l', strtotime(strtr($time['friday_date'], '/', '-')));     
              

                $friday_timein = $time['fri_timein'];
                $friday_timeout = $time['fri_timeout'];

                $saturday = date('l', strtotime(strtr($time['saturday_date'], '/', '-')));    
              

                $saturday_timein = $time['sat_timein'];
                $saturday_timeout = $time['sat_timeout'];

                $sunday = date('l', strtotime(strtr($time['sunday_date'], '/', '-')));    
              

                $sunday_timein = $time['sun_timein'];
                $sunday_timeout = $time['sun_timeout'];

                $currentTimestamp = time();
                $currentDate = date('Y-m-d', $currentTimestamp); 

                // Get the current day of the week
                $currentDayOfWeek = date('l', $currentTimestamp);

                // echo "<br> <br>",$date;
                // echo "<br>", $currentDate ,"<br>" ;
                
                if($date !== $currentDate){
                    // echo '<script>alert("Error: Unable to insert a past or future date.")</script>';
                    echo "<script>window.location.href = '../../attendance?wrongDate';</script>";
                    exit;
                }else{
                    
                
                // Now $currentDayOfWeek holds the full textual representation of the current day of the week (e.g., "Monday", "Tuesday", etc.).
                
                // To display it, you can simply echo the value:
                // echo "Today is " . $currentDayOfWeek;

                // echo " gsada ".$mondays;
                // if ($day_of_weekss == "Monday") {
                //     // Check if the employee is late
                //     $grace_period_total = new DateTime($time['mon_timein']);
                //     $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                    
                //     if ($grace_period_minutes > 0) {
                //         $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                //         $grace_period_total->add($grace_period_interval);
                //     }
                    
                //     $time_in_datetime = new DateTime($time_in);
                    
                    // if ($grace_period_minutes > 0 && $grace_period_total < $time_in_datetime) {
                    //     // Calculate the amount of late

                    //     // $late = $time_in_datetime->diff($grace_period_total)->format('%H:%I:%S');
                    //     $late = $time_in_datetime->format('%H:%I:%S');
                    // } else {
                    //     // Calculate the amount of late as difference between time_in and mon_timein
                    //     $monday_time_in_datetime = new DateTime($time['mon_timein']);
                    //     $late = $time_in_datetime->diff($monday_time_in_datetime)->format('%H:%I:%S');
                    // }
                    // if ($grace_period_total < $time_in_datetime) { //if may late
                        // Calculate the amount of late
                        //$late = $time_in_datetime->diff($grace_period_total)->format('%H:%I:%S');

                    //     $day_timeIN_db = new DateTime($time['mon_timein']);
                    //     if($day_timeIN_db === "" || $day_timeIN_db === NULL){
                    //         $late = "00:00:00";
                    //     }else{
                    //         $late = $time_in_datetime->diff($day_timeIN_db)->format('%H:%I:%S');
                    //     }
                        
                    // }
                    

                    // Calculate the total work hours
                  
                    // if ($time_out) {
                    //     // Convert time_in and time_out to DateTime objects
                    //     $time_in_datetime = new DateTime($time_in);
                    //     $time_out_datetime = new DateTime($time_out);
                    
                    //     // Check if the employee's time_in is past the scheduled time_in
                    //     $actual_time_in = max($time_in_datetime, new DateTime($time['mon_timein']));
                    
                    //     // Check if the time_out is before the scheduled time_out
                    //     $actual_time_out = min($time_out_datetime, new DateTime($time['mon_timeout']));
                    
                    //     // Calculate the total work hours
                    //     $interval = $actual_time_out->diff($actual_time_in);
                    //     $total_work = $interval->format('%H:%I:%S');
                    
                    //     // Subtract lunch break (1 hour) from the total work duration if necessary
                    //     if (
                    //         $actual_time_in < $actual_time_out &&
                    //         $actual_time_in->format('H:i') < '12:00' &&
                    //         $actual_time_out->format('H:i') > '13:00'
                    //     ) {
                    //         $total_work_datetime = new DateTime($total_work);
                    //         $total_work_datetime->sub(new DateInterval('PT1H'));
                    //         $total_work = $total_work_datetime->format('H:i:s');
                    //     }
                    
                    //     // Add the grace period to the total work duration if applicable
                    //     $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                    //     if ($actual_time_in > new DateTime($time['mon_timein'])) { // Check if actual time_in is greater than scheduled time_in
                    //         $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                    //         $total_work_datetime = new DateTime($total_work);
                    //         $total_work_datetime->add($grace_period_interval);
                    //         $total_work = $total_work_datetime->format('H:i:s');
                    //     }
                    // } else {
                    //     $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                    // }
                    
                    
                    
                    
                    
                    // if ($time_out > $time['mon_timeout']) {
                    //     // Calculate overtime
                    //     $total_work_time = new DateTime($total_work);
                    //     $scheduled_times = new DateTime('08:00:00');
                    //     $intervals = $total_work_time->diff($scheduled_times);
                    //     $overtime = $intervals->format('%h:%i:%s');
                    // } else {
                    //     $overtime = '00:00:00';
                    // }

                    // if($time_out < $time['mon_timeout']){
                    //     $time_out_datetime = new DateTime('08:00:00');
                    //     $scheduled_outs = new DateTime($total_work);
                    //     $early_interval = $scheduled_outs->diff($time_out_datetime);
                    //     $early_out = $early_interval->format('%h:%i:%s');
                    // } else { 
                    //     $early_out = '00:00:00';
                    // }

                    // if($time_in < '00:00:00'){
                    //     $early_out = '00:00:00';
                    //     $total_work = '00:00:00';
                    //     $total_rest = '08:00:00';
                    // }

                    if ($currentDayOfWeek == $monday) {
                        // echo "it is monday hehe";
                        // Check if the employee is late
                        $grace_period_total = new DateTime($time['mon_timein']);
                        $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                        
                        if ($grace_period_minutes > 0) {
                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                            $grace_period_total->add($grace_period_interval);
                        }
                        
                        // Get the minutes from mon_timein and grace_period
                        $mon_timein_minutes = (int)date('i', strtotime($time['mon_timein']));
                        $grace_period_minutes = isset($time['grace_period']) ? (int)$time['grace_period'] : 0;

                        // Convert time_in to DateTime object
                        $time_in_datetime = new DateTime($time_in);

                        // Calculate the late time
                        $late_minutes = (int)$time_in_datetime->format('i') - $grace_period_minutes;

                        if ($late_minutes >= 0) {
                            // Calculate the amount of late
                            $late = (new DateTime($time_in))->diff(new DateTime($time['mon_timein']))->format('%H:%I:%S');
                        } else {
                            // Set the late time to 00:00:00
                            $late = '00:00:00';
                        }         
                        
                       
                        $half_day = strtotime('13:00:00');
                        $og_time_formatteds = date('H:i', $half_day);
                        
                        // Convert time strings to DateTime objects for accurate comparison
                        $time_in_obj = DateTime::createFromFormat("H:i", $time_in);
                        $og_time_obj = DateTime::createFromFormat("H:i", $og_time_formatteds);
                        
                        //for ot
                        $time_out_obj = DateTime::createFromFormat("H:i", $time_out);
                        $sched_timeout = DateTime::createFromFormat("H:i", $monday_timeout);
    
    
                        
                        if ($time_in_obj >= $og_time_obj) {
                            // echo "<br> The $time_in is greater than or equal to $og_time_formatteds<br>";
                        
                            $grace_period_minutes = intval($grace_period_minutes);
                            $og_time = strtotime('13:00:00');
                            $grace_period_total = $og_time + ($grace_period_minutes * 60);
                        
                            $grace_period_time = date('H:i', $grace_period_total);
                        
                            // Convert grace period string to DateTime object
                            $grace_period_obj = DateTime::createFromFormat("H:i", $grace_period_time);
                        
                            // Compare DateTime objects
                            if ($time_in_obj > $grace_period_obj) {
                                $late = $time_in_obj->diff($og_time_obj)->format('%H:%I:%S');
                                // echo "The $time_in is greater than $grace_period_time. Late by $late";
                            } else {
                                // echo "Inside the statement, but not exceeding grace period <br>";
                            }
    
                            if($time_out > $monday_timeout){
                                 $overtime = $time_out_obj->diff($sched_timeout)->format('%H:%I:%S');
                                //  echo "you are OT <br>";
                                
                                //  echo $time_out ,"<br>";
                                
    
                            }else{
                                 $overtime = '00:00:00';
                            }
    
                        } else {
                            // echo "Outside the statement";
                        }
                        
   

                        
                        if ($time_out) {
                            // Convert time_in and time_out to DateTime objects
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);
                        
                            // Check if the employee's time_in is past the scheduled time_in
                            $actual_time_in = max($time_in_datetime, new DateTime($time['mon_timein']));
                        
                            // Check if the time_in minutes are less than the grace_period
                            $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0;
                            $minutes_in_time_in = intval($actual_time_in->format('i'));

                                $sched_ot_total = new DateTime($time['mon_timeout']);
                                $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                if ($sched_ot_time > 0) {
                                    $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                    $sched_ot_total->add($sched_ot_interval);
                                }
                            
                            if ($minutes_in_time_in <= $grace_period_minutes) {
                                // Calculate the total work hours from the scheduled time_in to time_out
                                $interval = $time_out_datetime->diff(new DateTime($time['mon_timein']));
                                $late = '00:00:00';
                                // var_dump($time_out_datetime);
                                
                            } else {
                                // Calculate the total work hours from actual time_in to time_out
                                $interval = $time_out_datetime->diff($time_in_datetime); 
                            }

                            if ($time_out >= $time['mon_timeout']) {
                                // Calculate overtime
                                $total_work_time = new DateTime($total_work);
                                $mon_timein = new DateTime($time['mon_timein']);
                                $sched_ot_total = new DateTime($time['mon_timeout']);
                                $mon_timeout = new DateTime($time['mon_timeout']);
                                
                                $time_in_datetime = new DateTime($time_in);
                                // $time_out_datetime = new DateTime($time_out);
    
    
                                $time_out_obj = DateTime::createFromFormat('H:i', $time_out);
                                
                                $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                if ($sched_ot_time > 0) {
                                    $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                    $sched_ot_total->add($sched_ot_interval);
                                }

    
                                // Get the minutes from $time_out
                                
    
                                if ($time_out_obj >  $sched_ot_total) {
                                    // $total_hehe = $sched_ot_minutes + $time_out_minutes;
                                    if($minutes_in_time_in <= $grace_period_minutes){
                                        $interval = $mon_timein->diff($time_out_datetime); 
    
                                        $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
         
                                        // Subtract 1 hour (3600 seconds) for lunch break
                                         // $time_out_datetime->sub(new DateInterval('PT1H'));
         
                                         $get_overtime = $mon_timeout->diff($time_out_datetime);
         
                                         // Format the overtime as 'H:i:s'
                                         $overtime = $get_overtime->format('%H:%I:%S');
                                        //  var_dump($total_work ,'eto yung sa may late pero pasok sa grace period then nag ot');
                                    }else{
                                   $interval = $time_in_datetime->diff($time_out_datetime); 
    
                                   $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                   $total_work_datetime->sub(new DateInterval('PT1H'));
                                   $total_work = $total_work_datetime->format('H:i:s');
    
                                   // Subtract 1 hour (3600 seconds) for lunch break
                                    // $time_out_datetime->sub(new DateInterval('PT1H'));
    
                                    $get_overtime = $mon_timeout->diff($time_out_datetime);
    
                                    // Format the overtime as 'H:i:s'
                                    $overtime = $get_overtime->format('%H:%I:%S');

                                    // var_dump($total_work, 'eto yung sa may late pero hindi pasok sa grace period then nag ot');
                                   
                                    }
                                }else{
                                     // Calculate the interval between $mon_timein and $mon_timeout
                                $scheduled_interval = $mon_timein->diff($mon_timeout);
    
                                // Convert the interval to a timestamp
                                $scheduled_timestamp = $mon_timein->getTimestamp() + $scheduled_interval->format('%s');
    
                                // Subtract one hour (3600 seconds) for the lunch break
                                $scheduled_timestamp -= 3600;
    
                                // Create a new DateTime object with the updated timestamp
                                $scheduled_time = new DateTime();
                                $scheduled_time->setTimestamp($scheduled_timestamp);
    
                                // Format the scheduled time as a string
                                $total_work = $scheduled_time->format('H:i');
                                $overtime = '00:00:00';
                                   
                                // var_dump($total_work, 'hehe');
                                } 
                                              
                            } else {
                                $overtime = '00:00:00';
                            }

                            // if($time_in_datetime >= $  )                           
                               
                              // if($time_in_datetime >= $  )
                     
                         // Subtract lunch break (1 hour) from the total work duration
                         $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                         $total_work_datetime->sub(new DateInterval('PT1H'));
                         $total_work = $total_work_datetime->format('H:i:s');

                        
                         $get_sched_ot = $time['sched_ot'];
                         $get_mon_timeout = $time['mon_timeout'];
                         $get_mon_timein = $time['mon_timein'];

                         $convert_mon_timeout = new DateTime($get_mon_timeout);
                         $convert_time_in = new DateTime($time_in);

                         $convert_time_out = new DateTime($time_out);


                         // Convert $get_sched_ot to minutes
                         $sched_ot_minutes = (int) $get_sched_ot;
                                                 
                         // Convert $get_mon_timeout to a DateTime object
                         $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_mon_timeout);
                                                 
                         // Add $sched_ot_minutes to $mon_timeout_datetime
                         $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                                                 
                         // Format the resulting time as 'H:i'
                         $result_sched_ot = $mon_timeout_datetime->format('H:i');

                        

                            if ($minutes_in_time_in <= $grace_period_minutes && $time_out <= $result_sched_ot ){
                                
                                $convert_mon_timeout = new DateTime($get_mon_timeout);
                                $convert_time_in = new DateTime($get_mon_timein);
                                
                                $total_work_interval = $convert_time_in->diff($convert_mon_timeout);
                                
                                // Create a new DateInterval representing one hour
                                $one_hour_interval = new DateInterval('PT1H');
                                
                                // Subtract one hour from the $convert_mon_timeout DateTime object
                                $convert_mon_timeout->sub($one_hour_interval);
                                
                                // Calculate the updated total work time interval after subtracting an hour
                                $total_work_interval = $convert_time_in->diff($convert_mon_timeout);
                                
                                $total_work = $total_work_interval->format('%H:%I:%S');
                                $overtime = '00:00:00';
                                
                                // echo $total_work;
                                
                                    
                            }else{
                                echo "<script> alert('Theres an error to your csv file.); </script>";
                            }

                        } else {
                            $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                        }

    
                        if($time_in < '00:00:00'){
                            $early_out = '00:00:00';
                            $total_work = '00:00:00';
                            $total_rest = '08:00:00';
                        }

                        $get_sched_ot = $time['sched_ot'];
                        $get_mon_timeout = $time['mon_timeout'];
                        $get_mon_timein = $time['mon_timein'];
                        
                        $convert_mon_timeout = new DateTime($get_mon_timeout);
                        $convert_time_in = new DateTime($time_in);
                        
                        // Check if time_out is set
                        if (!empty($time_out)) {
                            $convert_time_out = new DateTime($time_out);
                            
                            // Calculate the interval between time_in and time_out
                            $interval = $convert_time_in->diff($convert_time_out);
                        
                            // Subtract lunch break (1 hour) from the total work duration
                            $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                            $total_work_datetime->sub(new DateInterval('PT1H'));
                            $total_work = $total_work_datetime->format('H:i:s');
                        
                            // Convert $get_sched_ot to minutes
                            $sched_ot_minutes = (int) $get_sched_ot;
                        
                            // Convert $get_mon_timeout to a DateTime object
                            $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_mon_timeout);
                        
                            // Add $sched_ot_minutes to $mon_timeout_datetime
                            $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                        
                            // Format the resulting time as 'H:i'
                            $result_sched_ot = $mon_timeout_datetime->format('H:i');
                            
                            // Handle any further logic for the case when time_out is set
                        } else {
                            // Handle the case when time_out is not set (e.g., user only provided time_in)
                            // You can set defaults or handle it accordingly
                            $total_work = '00:00:00';
                            $result_sched_ot = '00:00';
                            $early_out = '00:00:00';
                            // Handle any further logic for this case
                        }
   
                        if(!empty($time_in)){
                        //   echo "hindi gumana";
                        }else{
                           // Convert wed_timein and time_out to DateTime objects
                               $convert_wed_timein = new DateTime($get_mon_timein);
                               $convert_time_out = new DateTime($time_out);
   
                               // Subtract an hour from time_out
                               $convert_time_out->sub(new DateInterval('PT1H'));
   
                               // Calculate the interval between wed_timein and time_out
                               $interval = $convert_wed_timein->diff($convert_time_out);
   
                               // Format the interval as 'H:i:s'
                               $interval_formatted = $interval->format('%H:%I:%S');
   
                               // Assign the formatted interval to $early_out
                               $early_out = $interval_formatted;
   
                           $total_work = '00:00:00';
                           $late = '00:00:00';
                        //    echo "walang time_in";
                        }
   
                        if($time_out < $time['mon_timeout']){
                           $get_timeout = $time['mon_timeout'];
                           $time_out_datetime = new DateTime($time_out);
                           $mon_timeout_datetime = new DateTime($get_timeout);
                           
                           // Check if $time_out is earlier than $wed_timeout
                           if ($time_out_datetime < $mon_timeout_datetime) {
                               // Calculate the difference between $time_out and $wed_timeout
                               $early_interval = $time_out_datetime->diff($mon_timeout_datetime);
                           
                               // Format the early out interval as 'H:i:s'
                               $early_out = $early_interval->format('%H:%I:%S');
                           
                               // Get the current time
                               $current_time = new DateTime();
                               
                               // Check if the current time is after 12:00 PM (noon)
                               $noon_time = new DateTime('12:00:00');
                               if ($current_time > $noon_time) {
                                   // Convert total work interval to a DateTime object
                                   $total_work_datetime = new DateTime($total_work);
                                   
                                   // Subtract an hour from the total work time
                                   $total_work_datetime->sub(new DateInterval('PT1H'));
                                   
                                   // Format the updated total work time
                                   $total_work = $total_work_datetime->format('H:i:s');
                               }
                           } else {
                               $early_out = '00:00:00';
                           }
                           
                        //    echo $early_out; // Display the calculated early out time
                        //    echo $total_work; // Display the calculated total work time
   
                           //  echo $total_work;
                        } else { 
                            $early_out = '00:00:00';
                        }
   
                        

                }elseif($currentDayOfWeek == $tuesday){
                    // echo "it is tuesday";
                        // Check if the employee is late
                        $grace_period_total = new DateTime($time['tues_timein']);
                        $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                        
                        if ($grace_period_minutes > 0) {
                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                            $grace_period_total->add($grace_period_interval);
                        }
                        
                        // Get the minutes from tues_timein and grace_period
                        $tues_timein_minutes = (int)date('i', strtotime($time['tues_timein']));
                        $grace_period_minutes = isset($time['grace_period']) ? (int)$time['grace_period'] : 0;

                        // Convert time_in to DateTime object
                        $time_in_datetime = new DateTime($time_in);

                        // Calculate the late time
                        $late_minutes = (int)$time_in_datetime->format('i') - $grace_period_minutes;

                        if ($late_minutes >= 0) {
                            // Calculate the amount of late
                            $late = (new DateTime($time_in))->diff(new DateTime($time['tues_timein']))->format('%H:%I:%S');
                        } else {
                            // Set the late time to 00:00:00
                            $late = '00:00:00';
                        }                  
                        
                         
                       $half_day = strtotime('13:00:00');
                       $og_time_formatteds = date('H:i', $half_day);
                       
                       // Convert time strings to DateTime objects for accurate comparison
                       $time_in_obj = DateTime::createFromFormat("H:i", $time_in);
                       $og_time_obj = DateTime::createFromFormat("H:i", $og_time_formatteds);
                       
                       //for ot
                       $time_out_obj = DateTime::createFromFormat("H:i", $time_out);
                       $sched_timeout = DateTime::createFromFormat("H:i", $tuesday_timeout);
   
   
                       
                       if ($time_in_obj >= $og_time_obj) {
                        //    echo "<br> The $time_in is greater than or equal to $og_time_formatteds<br>";
                       
                           $grace_period_minutes = intval($grace_period_minutes);
                           $og_time = strtotime('13:00:00');
                           $grace_period_total = $og_time + ($grace_period_minutes * 60);
                       
                           $grace_period_time = date('H:i', $grace_period_total);
                       
                           // Convert grace period string to DateTime object
                           $grace_period_obj = DateTime::createFromFormat("H:i", $grace_period_time);
                       
                           // Compare DateTime objects
                           if ($time_in_obj > $grace_period_obj) {
                               $late = $time_in_obj->diff($og_time_obj)->format('%H:%I:%S');
                            //    echo "The $time_in is greater than $grace_period_time. Late by $late";
                           } else {
                            //    echo "Inside the statement, but not exceeding grace period <br>";
                           }
   
                           if($time_out > $tuesday_timeout){
                                $overtime = $time_out_obj->diff($sched_timeout)->format('%H:%I:%S');
                                // echo "you are OT <br>";
                               
                                // echo $time_out ,"<br>";
                               
   
                           }else{
                                $overtime = '00:00:00';
                           }
   
                       } else {
                        //    echo "Outside the statement";
                       }
                       

                        if ($time_out) {
                            // Convert time_in and time_out to DateTime objects
                            $time_in_datetime = new DateTime($time_in);
                            $time_out_datetime = new DateTime($time_out);
                        
                            // Check if the employee's time_in is past the scheduled time_in
                            $actual_time_in = max($time_in_datetime, new DateTime($time['tues_timein']));
                        
                            // Check if the time_in minutes are less than the grace_period
                            $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0;
                            $minutes_in_time_in = intval($actual_time_in->format('i'));

                                $sched_ot_total = new DateTime($time['tues_timeout']);
                                $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                if ($sched_ot_time > 0) {
                                    $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                    $sched_ot_total->add($sched_ot_interval);
                                }
                            
                            if ($minutes_in_time_in <= $grace_period_minutes) {
                                // Calculate the total work hours from the scheduled time_in to time_out
                                $interval = $time_out_datetime->diff(new DateTime($time['tues_timein']));
                                $late = '00:00:00';
                                // var_dump($time_out_datetime);
                                
                            } else {
                                // Calculate the total work hours from actual time_in to time_out
                                $interval = $time_out_datetime->diff($time_in_datetime); 
                            }

                            if ($time_out >= $time['tues_timeout']) {
                                // Calculate overtime
                                $total_work_time = new DateTime($total_work);
                                $tues_timein = new DateTime($time['tues_timein']);
                                $sched_ot_total = new DateTime($time['tues_timeout']);
                                $tues_timeout = new DateTime($time['tues_timeout']);
                                
                                $time_in_datetime = new DateTime($time_in);
                                // $time_out_datetime = new DateTime($time_out);
    
    
                                $time_out_obj = DateTime::createFromFormat('H:i', $time_out);
                                
                                $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                if ($sched_ot_time > 0) {
                                    $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                    $sched_ot_total->add($sched_ot_interval);
                                }

    
                                // Get the minutes from $time_out
                                
    
                                if ($time_out_obj >  $sched_ot_total) {
                                    // $total_hehe = $sched_ot_minutes + $time_out_minutes;
                                    if($minutes_in_time_in <= $grace_period_minutes){
                                        $interval = $tues_timein->diff($time_out_datetime); 
    
                                        $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_work = $total_work_datetime->format('H:i:s');
         
                                        // Subtract 1 hour (3600 seconds) for lunch break
                                         // $time_out_datetime->sub(new DateInterval('PT1H'));
         
                                         $get_overtime = $tues_timeout->diff($time_out_datetime);
         
                                         // Format the overtime as 'H:i:s'
                                         $overtime = $get_overtime->format('%H:%I:%S');
                                        //  var_dump($total_work ,'eto yung sa may late pero pasok sa grace period then nag ot');
                                    }else{
                                   $interval = $time_in_datetime->diff($time_out_datetime); 
    
                                   $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                   $total_work_datetime->sub(new DateInterval('PT1H'));
                                   $total_work = $total_work_datetime->format('H:i:s');
    
                                   // Subtract 1 hour (3600 seconds) for lunch break
                                    // $time_out_datetime->sub(new DateInterval('PT1H'));
    
                                    $get_overtime = $tues_timeout->diff($time_out_datetime);
    
                                    // Format the overtime as 'H:i:s'
                                    $overtime = $get_overtime->format('%H:%I:%S');

                                    // var_dump($total_work, 'eto yung sa may late pero hindi pasok sa grace period then nag ot');
                                   
                                    }
                                }else{
                                     // Calculate the interval between $mon_timein and $tues_timeout
                                $scheduled_interval = $tues_timein->diff($tues_timeout);
    
                                // Convert the interval to a timestamp
                                $scheduled_timestamp = $tues_timein->getTimestamp() + $scheduled_interval->format('%s');
    
                                // Subtract one hour (3600 seconds) for the lunch break
                                $scheduled_timestamp -= 3600;
    
                                // Create a new DateTime object with the updated timestamp
                                $scheduled_time = new DateTime();
                                $scheduled_time->setTimestamp($scheduled_timestamp);
    
                                // Format the scheduled time as a string
                                $total_work = $scheduled_time->format('H:i');
                                $overtime = '00:00:00';
                                   
                                // var_dump($total_work, 'hehe');
                                } 
                                              
                            } else {
                                $overtime = '00:00:00';
                            }

                            // if($time_in_datetime >= $  )
                        
                            // Subtract lunch break (1 hour) from the total work duration
                         $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                         $total_work_datetime->sub(new DateInterval('PT1H'));
                         $total_work = $total_work_datetime->format('H:i:s');

                        
                         $get_sched_ot = $time['sched_ot'];
                         $get_tues_timeout = $time['tues_timeout'];
                         $get_tues_timein = $time['tues_timein'];

                         $convert_tues_timeout = new DateTime($get_tues_timeout);
                         $convert_time_in = new DateTime($time_in);

                         $convert_time_out = new DateTime($time_out);


                         // Convert $get_sched_ot to minutes
                         $sched_ot_minutes = (int) $get_sched_ot;
                                                 
                         // Convert $get_tues_timeout to a DateTime object
                         $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_tues_timeout);
                                                 
                         // Add $sched_ot_minutes to $mon_timeout_datetime
                         $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                                                 
                         // Format the resulting time as 'H:i'
                         $result_sched_ot = $mon_timeout_datetime->format('H:i');
                           
                               

                            if ($minutes_in_time_in <= $grace_period_minutes && $time_out <= $result_sched_ot ){

                                // if($time_out < $get_tues_timeout){
                                //     $convert_tues_timeout = new DateTime($get_tues_timeout);
                                // $convert_time_in = new DateTime($time_in);
                                
                                // $total_work_interval = $convert_time_in->diff($convert_tues_timeout);
                                
                                // // Create a new DateInterval representing one hour
                                // $one_hour_interval = new DateInterval('PT1H');
                                
                                // // Subtract one hour from the $convert_mon_timeout DateTime object
                                // $convert_tues_timeout->sub($one_hour_interval);
                                
                                // // Calculate the updated total work time interval after subtracting an hour
                                // $total_work_interval = $convert_time_in->diff($convert_tues_timeout);
                                
                                // $total_work = $total_work_interval->format('%H:%I:%S');
                                // $overtime = '00:00:00';
                                // }else{
                                
                                $convert_tues_timeout = new DateTime($get_tues_timeout);
                                $convert_time_in = new DateTime($get_tues_timein);
                                
                                $total_work_interval = $convert_time_in->diff($convert_tues_timeout);
                                
                                // Create a new DateInterval representing one hour
                                $one_hour_interval = new DateInterval('PT1H');
                                
                                // Subtract one hour from the $convert_mon_timeout DateTime object
                                $convert_tues_timeout->sub($one_hour_interval);
                                
                                // Calculate the updated total work time interval after subtracting an hour
                                $total_work_interval = $convert_time_in->diff($convert_tues_timeout);
                                
                                $total_work = $total_work_interval->format('%H:%I:%S');
                                $overtime = '00:00:00';
                                // }
                                
                                // echo $total_work;
                                
                                    
                            }else{
                                echo "<script> alert('Theres an error to your csv file.); </script>";
                            }

                        } else {
                            $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                        }

                        if($time_in < '00:00:00'){
                            $early_out = '00:00:00';
                            $total_work = '00:00:00';
                            $total_rest = '08:00:00';
                        }

                        $get_sched_ot = $time['sched_ot'];
                        $get_week_timeout = $time['tues_timeout'];
                        $get_week_timein = $time['tues_timein'];
                        
                        $convert_week_timeout = new DateTime($get_week_timeout);
                        $convert_time_in = new DateTime($time_in);
                        
                        // Check if time_out is set
                        if (!empty($time_out)) {
                            $convert_time_out = new DateTime($time_out);
                            
                            // Calculate the interval between time_in and time_out
                            $interval = $convert_time_in->diff($convert_time_out);
                        
                            // Subtract lunch break (1 hour) from the total work duration
                            $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                            $total_work_datetime->sub(new DateInterval('PT1H'));
                            $total_work = $total_work_datetime->format('H:i:s');
                        
                            // Convert $get_sched_ot to minutes
                            $sched_ot_minutes = (int) $get_sched_ot;
                        
                            // Convert $get_week_timeout to a DateTime object
                            $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_week_timeout);
                        
                            // Add $sched_ot_minutes to $mon_timeout_datetime
                            $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                        
                            // Format the resulting time as 'H:i'
                            $result_sched_ot = $mon_timeout_datetime->format('H:i');
                            
                            // Handle any further logic for the case when time_out is set
                        } else {
                            // Handle the case when time_out is not set (e.g., user only provided time_in)
                            // You can set defaults or handle it accordingly
                            $total_work = '00:00:00';
                            $result_sched_ot = '00:00';
                            $early_out = '00:00:00';
                            // Handle any further logic for this case
                        }
   
                        if(!empty($time_in)){
                        //   echo "hindi gumana";
                        }else{
                           // Convert wed_timein and time_out to DateTime objects
                               $convert_wed_timein = new DateTime($get_week_timein);
                               $convert_time_out = new DateTime($time_out);
   
                               // Subtract an hour from time_out
                               $convert_time_out->sub(new DateInterval('PT1H'));
   
                               // Calculate the interval between wed_timein and time_out
                               $interval = $convert_wed_timein->diff($convert_time_out);
   
                               // Format the interval as 'H:i:s'
                               $interval_formatted = $interval->format('%H:%I:%S');
   
                               // Assign the formatted interval to $early_out
                               $early_out = $interval_formatted;
   
                           $total_work = '00:00:00';
                           $late = '00:00:00';
                        //    echo "walang time_in";
                        }
   
                        if($time_out < $time['tues_timeout']){
                           $get_timeout = $time['tues_timeout'];
                           $time_out_datetime = new DateTime($time_out);
                           $week_timeout_datetime = new DateTime($get_timeout);
                           
                           // Check if $time_out is earlier than $wed_timeout
                           if ($time_out_datetime < $week_timeout_datetime) {
                               // Calculate the difference between $time_out and $wed_timeout
                               $early_interval = $time_out_datetime->diff($week_timeout_datetime);
                           
                               // Format the early out interval as 'H:i:s'
                               $early_out = $early_interval->format('%H:%I:%S');
                           
                               // Get the current time
                               $current_time = new DateTime();
                               
                               // Check if the current time is after 12:00 PM (noon)
                               $noon_time = new DateTime('12:00:00');
                               if ($current_time > $noon_time) {
                                   // Convert total work interval to a DateTime object
                                   $total_work_datetime = new DateTime($total_work);
                                   
                                   // Subtract an hour from the total work time
                                   $total_work_datetime->sub(new DateInterval('PT1H'));
                                   
                                   // Format the updated total work time
                                   $total_work = $total_work_datetime->format('H:i:s');
                               }
                           } else {
                               $early_out = '00:00:00';
                           }
                           
                        //    echo $early_out; // Display the calculated early out time
                        //    echo $total_work; // Display the calculated total work time
   
                           //  echo $total_work;
                        } else { 
                            $early_out = '00:00:00';
                        }
                }elseif($currentDayOfWeek == $wednesday){
                     // Check if the employee is late
                    //  echo "it is wednesday";
                     $grace_period_total = new DateTime($time['wed_timein']);
                     $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                     
                     if ($grace_period_minutes > 0) {
                         $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                         $grace_period_total->add($grace_period_interval);
                     }
                     
                     // Get the minutes from wed_timein and grace_period
                     $wed_timein_minutes = (int)date('i', strtotime($time['wed_timein']));
                     $my_time_in = (int)date('i', strtotime($time_in));
                     $grace_period_minutes = isset($time['grace_period']) ? (int)$time['grace_period'] : 0;

                     // Convert time_in to DateTime object
                     $time_in_datetime = new DateTime($time_in);

                     // Calculate the late time
                     $late_minutes = (int)$time_in_datetime->format('i') - $grace_period_minutes;

                     if ($late_minutes >= 0) {
                         // Calculate the amount of late
                         $late = (new DateTime($time_in))->diff(new DateTime($time['wed_timein']))->format('%H:%I:%S');
                     } else {
                         // Set the late time to 00:00:00
                         $late = '00:00:00';
                     }                  

                     $half_day = strtotime('13:00:00');
                       $og_time_formatteds = date('H:i', $half_day);
                       
                       // Convert time strings to DateTime objects for accurate comparison
                       $time_in_obj = DateTime::createFromFormat("H:i", $time_in);
                       $og_time_obj = DateTime::createFromFormat("H:i", $og_time_formatteds);
                       
                       //for ot
                       $time_out_obj = DateTime::createFromFormat("H:i", $time_out);
                       $sched_timeout = DateTime::createFromFormat("H:i", $wednesday_timeout);
   
   
                       
                       if ($time_in_obj >= $og_time_obj) {
                        //    echo "<br> The $time_in is greater than or equal to $og_time_formatteds<br>";
                       
                           $grace_period_minutes = intval($grace_period_minutes);
                           $og_time = strtotime('13:00:00');
                           $grace_period_total = $og_time + ($grace_period_minutes * 60);
                       
                           $grace_period_time = date('H:i', $grace_period_total);
                       
                           // Convert grace period string to DateTime object
                           $grace_period_obj = DateTime::createFromFormat("H:i", $grace_period_time);
                       
                           // Compare DateTime objects
                           if ($time_in_obj > $grace_period_obj) {
                               $late = $time_in_obj->diff($og_time_obj)->format('%H:%I:%S');
                            //    echo "The $time_in is greater than $grace_period_time. Late by $late";
                           } else {
                            //    echo "Inside the statement, but not exceeding grace period <br>";
                           }
   
                           if($time_out > $wednesday_timeout){
                                $overtime = $time_out_obj->diff($sched_timeout)->format('%H:%I:%S');
                                // echo "you are OT <br>";
                               
                                // echo $time_out ,"<br>";
                               
   
                           }else{
                                $overtime = '00:00:00';
                           }
   
                       } else {
                        //    echo "Outside the statement";
                       }

                     if ($time_out) {
                         // Convert time_in and time_out to DateTime objects
                         $time_in_datetime = new DateTime($time_in);
                         $time_out_datetime = new DateTime($time_out);
                     
                         // Check if the employee's time_in is past the scheduled time_in
                         $actual_time_in = max($time_in_datetime, new DateTime($time['wed_timein']));
                     
                         // Check if the time_in minutes are less than the grace_period
                         $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0;
                         $minutes_in_time_in = intval($actual_time_in->format('i'));

                             $sched_ot_total = new DateTime($time['wed_timeout']);
                             $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
 
                             if ($sched_ot_time > 0) {
                                 $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                 $sched_ot_total->add($sched_ot_interval);
                             }
                         
                         if ($minutes_in_time_in <= $grace_period_minutes) {
                             // Calculate the total work hours from the scheduled time_in to time_out
                             $interval = $time_out_datetime->diff(new DateTime($time['wed_timein']));
                             $late = '00:00:00';
                             // var_dump($time_out_datetime);
                             
                         } else {
                             // Calculate the total work hours from actual time_in to time_out
                             $interval = $time_out_datetime->diff($time_in_datetime); 
                         }

                         if ($time_out >= $time['wed_timeout']) {
                             // Calculate overtime
                             $total_work_time = new DateTime($total_work);
                             $wed_timein = new DateTime($time['wed_timein']);
                             $sched_ot_total = new DateTime($time['wed_timeout']);
                             $wed_timeout = new DateTime($time['wed_timeout']);
                             
                             $time_in_datetime = new DateTime($time_in);
                             // $time_out_datetime = new DateTime($time_out);
 
 
                             $time_out_obj = DateTime::createFromFormat('H:i', $time_out);
                             
                             $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
 
                             if ($sched_ot_time > 0) {
                                 $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                 $sched_ot_total->add($sched_ot_interval);
                             }

 
                             // Get the minutes from $time_out
                             
 
                             if ($time_out_obj >  $sched_ot_total) {
                                 // $total_hehe = $sched_ot_minutes + $time_out_minutes;
                                 if($minutes_in_time_in <= $grace_period_minutes){
                                     $interval = $wed_timein->diff($time_out_datetime); 
 
                                     $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                     $total_work_datetime->sub(new DateInterval('PT1H'));
                                     $total_work = $total_work_datetime->format('H:i:s');
      
                                     // Subtract 1 hour (3600 seconds) for lunch break
                                      // $time_out_datetime->sub(new DateInterval('PT1H'));
      
                                      $get_overtime = $wed_timeout->diff($time_out_datetime);
      
                                      // Format the overtime as 'H:i:s'
                                      $overtime = $get_overtime->format('%H:%I:%S');
                                     //  var_dump($total_work ,'eto yung sa may late pero pasok sa grace period then nag ot');
                                    //  echo "hoho";
                                 }else{
                                $interval = $time_in_datetime->diff($time_out_datetime); 
 
                                $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                $total_work = $total_work_datetime->format('H:i:s');
 
                                // Subtract 1 hour (3600 seconds) for lunch break
                                 // $time_out_datetime->sub(new DateInterval('PT1H'));
 
                                 $get_overtime = $wed_timeout->diff($time_out_datetime);
 
                                 // Format the overtime as 'H:i:s'
                                 $overtime = $get_overtime->format('%H:%I:%S');

                                 // var_dump($total_work, 'eto yung sa may late pero hindi pasok sa grace period then nag ot');
                                
                                 }
                             }else{
                                  // Calculate the interval between $mon_timein and $wed_timeout
                             $scheduled_interval = $wed_timein->diff($wed_timeout);
 
                             // Convert the interval to a timestamp
                             $scheduled_timestamp = $wed_timein->getTimestamp() + $scheduled_interval->format('%s');
 
                             // Subtract one hour (3600 seconds) for the lunch break
                             $scheduled_timestamp -= 3600;
 
                             // Create a new DateTime object with the updated timestamp
                             $scheduled_time = new DateTime();
                             $scheduled_time->setTimestamp($scheduled_timestamp);
 
                             // Format the scheduled time as a string
                             $total_work = $scheduled_time->format('H:i');
                             $overtime = '00:00:00';

                            //  echo "haha";
                                
                             // var_dump($total_work, 'hehe');
                             } 
                                           
                         } else {
                             $overtime = '00:00:00';
                         }

                         // if($time_in_datetime >= $  )
                     
                         // Subtract lunch break (1 hour) from the total work duration
                         $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                         $total_work_datetime->sub(new DateInterval('PT1H'));
                         $total_work = $total_work_datetime->format('H:i:s');

                        
                         $get_sched_ot = $time['sched_ot'];
                         $get_wed_timeout = $time['wed_timeout'];
                         $get_wed_timein = $time['wed_timein'];

                         $convert_wed_timeout = new DateTime($get_wed_timeout);
                         $convert_time_in = new DateTime($time_in);

                         $convert_time_out = new DateTime($time_out);


                         // Convert $get_sched_ot to minutes
                         $sched_ot_minutes = (int) $get_sched_ot;
                                                 
                         // Convert $get_wed_timeout to a DateTime object
                         $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_wed_timeout);
                                                 
                         // Add $sched_ot_minutes to $mon_timeout_datetime
                         $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                                                 
                         // Format the resulting time as 'H:i'
                         $result_sched_ot = $mon_timeout_datetime->format('H:i');

                        
                            

                         if ($minutes_in_time_in <= $grace_period_minutes && $time_out <= $result_sched_ot ){
                             
                             $convert_wed_timeout = new DateTime($get_wed_timeout);
                             $convert_time_in = new DateTime($get_wed_timein);
                             $convert_time_out = new DateTime($time_out);
                             
                             $total_work_interval = $convert_time_in->diff($convert_time_out);
                             
                             // Create a new DateInterval representing one hour
                             $one_hour_interval = new DateInterval('PT1H');
                             
                             // Subtract one hour from the $convert_mon_timeout DateTime object
                             $convert_wed_timeout->sub($one_hour_interval);
                             
                             // Calculate the updated total work time interval after subtracting an hour
                             $total_work_interval = $convert_time_in->diff($convert_time_out);
                             
                             $total_work = $total_work_interval->format('%H:%I:%S');
                             $overtime = '00:00:00';
                             
                             
                            //  echo $total_work;
                             
                                 
                         }else{
                             echo "<script> alert('Theres an error to your csv file.); </script>";
                         }

                     } else {
                         $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                     }

                    

 
                   
                     if($time_in < '00:00:00'){
                         $early_out = '00:00:00';
                         $total_work = '00:00:00';
                         $total_rest = '08:00:00';
                     }

                     
                     $get_sched_ot = $time['sched_ot'];
                     $get_wed_timeout = $time['wed_timeout'];
                     $get_wed_timein = $time['wed_timein'];
                     
                     $convert_wed_timeout = new DateTime($get_wed_timeout);
                     $convert_time_in = new DateTime($time_in);
                     
                     // Check if time_out is set
                     if (!empty($time_out)) {
                         $convert_time_out = new DateTime($time_out);
                         
                         // Calculate the interval between time_in and time_out
                         $interval = $convert_time_in->diff($convert_time_out);
                     
                         // Subtract lunch break (1 hour) from the total work duration
                         $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                         $total_work_datetime->sub(new DateInterval('PT1H'));
                         $total_work = $total_work_datetime->format('H:i:s');
                     
                         // Convert $get_sched_ot to minutes
                         $sched_ot_minutes = (int) $get_sched_ot;
                     
                         // Convert $get_wed_timeout to a DateTime object
                         $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_wed_timeout);
                     
                         // Add $sched_ot_minutes to $mon_timeout_datetime
                         $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                     
                         // Format the resulting time as 'H:i'
                         $result_sched_ot = $mon_timeout_datetime->format('H:i');
                         
                         // Handle any further logic for the case when time_out is set
                     } else {
                         // Handle the case when time_out is not set (e.g., user only provided time_in)
                         // You can set defaults or handle it accordingly
                         $total_work = '00:00:00';
                         $result_sched_ot = '00:00';
                         $early_out = '00:00:00';
                         // Handle any further logic for this case
                     }

                     if(!empty($time_in)){
                    //    echo "hindi gumana";
                     }else{
                        // Convert wed_timein and time_out to DateTime objects
                            $convert_wed_timein = new DateTime($get_wed_timein);
                            $convert_time_out = new DateTime($time_out);

                            // Subtract an hour from time_out
                            $convert_time_out->sub(new DateInterval('PT1H'));

                            // Calculate the interval between wed_timein and time_out
                            $interval = $convert_wed_timein->diff($convert_time_out);

                            // Format the interval as 'H:i:s'
                            $interval_formatted = $interval->format('%H:%I:%S');

                            // Assign the formatted interval to $early_out
                            $early_out = $interval_formatted;

                        $total_work = '00:00:00';
                        $late = '00:00:00';
                        // echo "walang time_in";
                     }

                     if($time_out < $time['wed_timeout']){
                        $get_timeout = $time['wed_timeout'];
                        $time_out_datetime = new DateTime($time_out);
                        $wed_timeout_datetime = new DateTime($get_timeout);
                        
                        // Check if $time_out is earlier than $wed_timeout
                        if ($time_out_datetime < $wed_timeout_datetime) {
                            // Calculate the difference between $time_out and $wed_timeout
                            $early_interval = $time_out_datetime->diff($wed_timeout_datetime);
                        
                            // Format the early out interval as 'H:i:s'
                            $early_out = $early_interval->format('%H:%I:%S');
                        
                            // Get the current time
                            $current_time = new DateTime();
                            
                            // Check if the current time is after 12:00 PM (noon)
                            $noon_time = new DateTime('12:00:00');
                            if ($current_time > $noon_time) {
                                // Convert total work interval to a DateTime object
                                $total_work_datetime = new DateTime($total_work);
                                
                                // Subtract an hour from the total work time
                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                
                                // Format the updated total work time
                                $total_work = $total_work_datetime->format('H:i:s');
                            }
                        } else {
                            $early_out = '00:00:00';
                        }
                        
                        // echo $early_out; // Display the calculated early out time
                        // echo $total_work; // Display the calculated total work time

                        //  echo $total_work;
                     } else { 
                         $early_out = '00:00:00';
                     }



                    
                     
                }elseif($currentDayOfWeek == $thursday){
                    // echo "it is thursday";
                       // Check if the employee is late
                       $grace_period_total = new DateTime($time['thurs_timein']);
                       $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                       
                       if ($grace_period_minutes > 0) {
                           $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                           $grace_period_total->add($grace_period_interval);
                       }
                       
                       // Get the minutes from thurs_timein and grace_period
                       $thurs_timein_minutes = (int)date('i', strtotime($time['thurs_timein']));
                       $grace_period_minutes = isset($time['grace_period']) ? (int)$time['grace_period'] : 0;
  
                       // Convert time_in to DateTime object
                       $time_in_datetime = new DateTime($time_in);
  
                       // Calculate the late time
                       $late_minutes = (int)$time_in_datetime->format('i') - $grace_period_minutes;
  
                       if ($late_minutes >= 0) {
                           // Calculate the amount of late
                           $late = (new DateTime($time_in))->diff(new DateTime($time['thurs_timein']))->format('%H:%I:%S');
                       } else {
                           // Set the late time to 00:00:00
                           $late = '00:00:00';
                       }    
                       
                       $half_day = strtotime('13:00:00');
                       $og_time_formatteds = date('H:i', $half_day);
                       
                       // Convert time strings to DateTime objects for accurate comparison
                       $time_in_obj = DateTime::createFromFormat("H:i", $time_in);
                       $og_time_obj = DateTime::createFromFormat("H:i", $og_time_formatteds);
                       
                       //for ot
                       $time_out_obj = DateTime::createFromFormat("H:i", $time_out);
                       $sched_timeout = DateTime::createFromFormat("H:i", $thursday_timeout);
   
   
                       
                       if ($time_in_obj >= $og_time_obj) {
                        //    echo "<br> The $time_in is greater than or equal to $og_time_formatteds<br>";
                       
                           $grace_period_minutes = intval($grace_period_minutes);
                           $og_time = strtotime('13:00:00');
                           $grace_period_total = $og_time + ($grace_period_minutes * 60);
                       
                           $grace_period_time = date('H:i', $grace_period_total);
                       
                           // Convert grace period string to DateTime object
                           $grace_period_obj = DateTime::createFromFormat("H:i", $grace_period_time);
                       
                           // Compare DateTime objects
                           if ($time_in_obj > $grace_period_obj) {
                               $late = $time_in_obj->diff($og_time_obj)->format('%H:%I:%S');
                            //    echo "The $time_in is greater than $grace_period_time. Late by $late";
                           } else {
                            //    echo "Inside the statement, but not exceeding grace period <br>";
                           }
   
                           if($time_out > $thursday_timeout){
                                $overtime = $time_out_obj->diff($sched_timeout)->format('%H:%I:%S');
                                // echo "you are OT <br>";
                               
                                // echo $time_out ,"<br>";
                               
   
                           }else{
                                $overtime = '00:00:00';
                           }
   
                       } else {
                        //    echo "Outside the statement";
                       }
                       
  
                       if ($time_out) {
                           // Convert time_in and time_out to DateTime objects
                           $time_in_datetime = new DateTime($time_in);
                           $time_out_datetime = new DateTime($time_out);
                       
                           // Check if the employee's time_in is past the scheduled time_in
                           $actual_time_in = max($time_in_datetime, new DateTime($time['thurs_timein']));
                       
                           // Check if the time_in minutes are less than the grace_period
                           $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0;
                           $minutes_in_time_in = intval($actual_time_in->format('i'));
  
                               $sched_ot_total = new DateTime($time['thurs_timeout']);
                               $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
   
                               if ($sched_ot_time > 0) {
                                   $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                   $sched_ot_total->add($sched_ot_interval);
                               }
                           
                           if ($minutes_in_time_in <= $grace_period_minutes) {
                               // Calculate the total work hours from the scheduled time_in to time_out
                               $interval = $time_out_datetime->diff(new DateTime($time['thurs_timein']));
                               $late = '00:00:00';
                               // var_dump($time_out_datetime);
                               
                           } else {
                               // Calculate the total work hours from actual time_in to time_out
                               $interval = $time_out_datetime->diff($time_in_datetime); 
                           }
  
                           if ($time_out >= $time['thurs_timeout']) {
                               // Calculate overtime
                               $total_work_time = new DateTime($total_work);
                               $thurs_timein = new DateTime($time['thurs_timein']);
                               $sched_ot_total = new DateTime($time['thurs_timeout']);
                               $thurs_timeout = new DateTime($time['thurs_timeout']);
                               
                               $time_in_datetime = new DateTime($time_in);
                               // $time_out_datetime = new DateTime($time_out);
   
   
                               $time_out_obj = DateTime::createFromFormat('H:i', $time_out);
                               
                               $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available
   
                               if ($sched_ot_time > 0) {
                                   $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                                   $sched_ot_total->add($sched_ot_interval);
                               }
  
   
                               // Get the minutes from $time_out
                               
   
                               if ($time_out_obj >  $sched_ot_total) {
                                   // $total_hehe = $sched_ot_minutes + $time_out_minutes;
                                   if($minutes_in_time_in <= $grace_period_minutes){
                                       $interval = $thurs_timein->diff($time_out_datetime); 
   
                                       $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                       $total_work_datetime->sub(new DateInterval('PT1H'));
                                       $total_work = $total_work_datetime->format('H:i:s');
        
                                       // Subtract 1 hour (3600 seconds) for lunch break
                                        // $time_out_datetime->sub(new DateInterval('PT1H'));
        
                                        $get_overtime = $thurs_timeout->diff($time_out_datetime);
        
                                        // Format the overtime as 'H:i:s'
                                        $overtime = $get_overtime->format('%H:%I:%S');
                                       //  var_dump($total_work ,'eto yung sa may late pero pasok sa grace period then nag ot');
                                   }else{
                                  $interval = $time_in_datetime->diff($time_out_datetime); 
   
                                  $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                  $total_work_datetime->sub(new DateInterval('PT1H'));
                                  $total_work = $total_work_datetime->format('H:i:s');
   
                                  // Subtract 1 hour (3600 seconds) for lunch break
                                   // $time_out_datetime->sub(new DateInterval('PT1H'));
   
                                   $get_overtime = $thurs_timeout->diff($time_out_datetime);
   
                                   // Format the overtime as 'H:i:s'
                                   $overtime = $get_overtime->format('%H:%I:%S');
  
                                   // var_dump($total_work, 'eto yung sa may late pero hindi pasok sa grace period then nag ot');
                                  
                                   }
                               }else{
                                    // Calculate the interval between $mon_timein and $thurs_timeout
                               $scheduled_interval = $thurs_timein->diff($thurs_timeout);
   
                               // Convert the interval to a timestamp
                               $scheduled_timestamp = $thurs_timein->getTimestamp() + $scheduled_interval->format('%s');
   
                               // Subtract one hour (3600 seconds) for the lunch break
                               $scheduled_timestamp -= 3600;
   
                               // Create a new DateTime object with the updated timestamp
                               $scheduled_time = new DateTime();
                               $scheduled_time->setTimestamp($scheduled_timestamp);
   
                               // Format the scheduled time as a string
                               $total_work = $scheduled_time->format('H:i');
                               $overtime = '00:00:00';
                                  
                               // var_dump($total_work, 'hehe');
                               } 
                                             
                           } else {
                               $overtime = '00:00:00';
                           }
  
                           // if($time_in_datetime >= $  )
                       
                           // Subtract lunch break (1 hour) from the total work duration
                           $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                           $total_work_datetime->sub(new DateInterval('PT1H'));
                           $total_work = $total_work_datetime->format('H:i:s');
  
                          
                           $get_sched_ot = $time['sched_ot'];
                           $get_thurs_timeout = $time['thurs_timeout'];
                           $get_thurs_timein = $time['thurs_timein'];
  
                           $convert_thurs_timeout = new DateTime($get_thurs_timeout);
                           $convert_time_in = new DateTime($time_in);
                           $convert_time_out = new DateTime($time_out);
  
  
                           // Convert $get_sched_ot to minutes
                           $sched_ot_minutes = (int) $get_sched_ot;
                                                   
                           // Convert $get_thurs_timeout to a DateTime object
                           $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_thurs_timeout);
                                                   
                           // Add $sched_ot_minutes to $mon_timeout_datetime
                           $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                                                   
                           // Format the resulting time as 'H:i'
                           $result_sched_ot = $mon_timeout_datetime->format('H:i');
  
                          
                              
  
                           if ($minutes_in_time_in <= $grace_period_minutes && $time_out <= $result_sched_ot ){
                               
                               $convert_thurs_timeout = new DateTime($get_thurs_timeout);
                               $convert_time_in = new DateTime($get_thurs_timein);
                               
                               $total_work_interval = $convert_time_in->diff($convert_thurs_timeout);
                               
                               // Create a new DateInterval representing one hour
                               $one_hour_interval = new DateInterval('PT1H');
                               
                               // Subtract one hour from the $convert_mon_timeout DateTime object
                               $convert_thurs_timeout->sub($one_hour_interval);
                               
                               // Calculate the updated total work time interval after subtracting an hour
                               $total_work_interval = $convert_time_in->diff($convert_thurs_timeout);
                               
                               $total_work = $total_work_interval->format('%H:%I:%S');
                               $overtime = '00:00:00';
                               
                            //    echo $total_work;
                               
                                   
                           }else{
                               echo "<script> alert('Theres an error to your csv file.); </script>";
                           }
  
                       } else {
                           $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                       }
  
   
                       if($time_in < '00:00:00'){
                        $early_out = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '08:00:00';
                    }

                    $get_sched_ot = $time['sched_ot'];
                    $get_week_timeout = $time['thurs_timeout'];
                    $get_week_timein = $time['thurs_timein'];
                    
                    $convert_week_timeout = new DateTime($get_week_timeout);
                    $convert_time_in = new DateTime($time_in);
                    
                    // Check if time_out is set
                    if (!empty($time_out)) {
                        $convert_time_out = new DateTime($time_out);
                        
                        // Calculate the interval between time_in and time_out
                        $interval = $convert_time_in->diff($convert_time_out);
                    
                        // Subtract lunch break (1 hour) from the total work duration
                        $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                        $total_work_datetime->sub(new DateInterval('PT1H'));
                        $total_work = $total_work_datetime->format('H:i:s');
                    
                        // Convert $get_sched_ot to minutes
                        $sched_ot_minutes = (int) $get_sched_ot;
                    
                        // Convert $get_week_timeout to a DateTime object
                        $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_week_timeout);
                    
                        // Add $sched_ot_minutes to $mon_timeout_datetime
                        $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                    
                        // Format the resulting time as 'H:i'
                        $result_sched_ot = $mon_timeout_datetime->format('H:i');
                        
                        // Handle any further logic for the case when time_out is set
                    } else {
                        // Handle the case when time_out is not set (e.g., user only provided time_in)
                        // You can set defaults or handle it accordingly
                        $total_work = '00:00:00';
                        $result_sched_ot = '00:00';
                        $early_out = '00:00:00';
                        // Handle any further logic for this case
                    }

                    if(!empty($time_in)){
                    //   echo "hindi gumana";
                    }else{
                       // Convert wed_timein and time_out to DateTime objects
                           $convert_week_timein = new DateTime($get_week_timein);
                           $convert_time_out = new DateTime($time_out);

                           // Subtract an hour from time_out
                           $convert_time_out->sub(new DateInterval('PT1H'));

                           // Calculate the interval between wed_timein and time_out
                           $interval = $convert_week_timein->diff($convert_time_out);

                           // Format the interval as 'H:i:s'
                           $interval_formatted = $interval->format('%H:%I:%S');

                           // Assign the formatted interval to $early_out
                           $early_out = $interval_formatted;

                       $total_work = '00:00:00';
                       $late = '00:00:00';
                    //    echo "walang time_in";
                    }

                    if($time_out < $time['thurs_timeout']){
                       $get_timeout = $time['thurs_timeout'];
                       $time_out_datetime = new DateTime($time_out);
                       $week_timeout_datetime = new DateTime($get_timeout);
                       
                       // Check if $time_out is earlier than $wed_timeout
                       if ($time_out_datetime < $week_timeout_datetime) {
                           // Calculate the difference between $time_out and $wed_timeout
                           $early_interval = $time_out_datetime->diff($week_timeout_datetime);
                       
                           // Format the early out interval as 'H:i:s'
                           $early_out = $early_interval->format('%H:%I:%S');
                       
                           // Get the current time
                           $current_time = new DateTime();
                           
                           // Check if the current time is after 12:00 PM (noon)
                           $noon_time = new DateTime('12:00:00');
                           if ($current_time > $noon_time) {
                               // Convert total work interval to a DateTime object
                               $total_work_datetime = new DateTime($total_work);
                               
                               // Subtract an hour from the total work time
                               $total_work_datetime->sub(new DateInterval('PT1H'));
                               
                               // Format the updated total work time
                               $total_work = $total_work_datetime->format('H:i:s');
                           }
                       } else {
                           $early_out = '00:00:00';
                       }
                       
                    //    echo $early_out; // Display the calculated early out time
                    //    echo $total_work; // Display the calculated total work time

                       //  echo $total_work;
                    } else { 
                        $early_out = '00:00:00';
                    }

            }elseif($currentDayOfWeek == $friday){
                // echo "it is friday";
                // Check if the employee is late
                $grace_period_total = new DateTime($time['fri_timein']);
                $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                
                if ($grace_period_minutes > 0) {
                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                    $grace_period_total->add($grace_period_interval);
                }
                
                // Get the minutes from fri_timein and grace_period
                $fri_timein = (int)date('i', strtotime($time['fri_timein']));
                $grace_period_minutes = isset($time['grace_period']) ? (int)$time['grace_period'] : 0;

                // Convert time_in to DateTime object
                $time_in_datetime = new DateTime($time_in);

                // Calculate the late time
                $late_minutes = (int)$time_in_datetime->format('i') - $grace_period_minutes;

                if ($late_minutes >= 0) {
                    // Calculate the amount of late
                    $late = (new DateTime($time_in))->diff(new DateTime($time['fri_timein']))->format('%H:%I:%S');
                } else {
                    // Set the late time to 00:00:00
                    $late = '00:00:00';
                } 
                
                $half_day = strtotime('13:00:00');
                $og_time_formatteds = date('H:i', $half_day);
                
                // Convert time strings to DateTime objects for accurate comparison
                $time_in_obj = DateTime::createFromFormat("H:i", $time_in);
                $og_time_obj = DateTime::createFromFormat("H:i", $og_time_formatteds);
                
                //for ot
                $time_out_obj = DateTime::createFromFormat("H:i", $time_out);
                $sched_timeout = DateTime::createFromFormat("H:i", $friday_timeout);


                
                if ($time_in_obj >= $og_time_obj) {
                    // echo "<br> The $time_in is greater than or equal to $og_time_formatteds<br>";
                
                    $grace_period_minutes = intval($grace_period_minutes);
                    $og_time = strtotime('13:00:00');
                    $grace_period_total = $og_time + ($grace_period_minutes * 60);
                
                    $grace_period_time = date('H:i', $grace_period_total);
                
                    // Convert grace period string to DateTime object
                    $grace_period_obj = DateTime::createFromFormat("H:i", $grace_period_time);
                
                    // Compare DateTime objects
                    if ($time_in_obj > $grace_period_obj) {
                        $late = $time_in_obj->diff($og_time_obj)->format('%H:%I:%S');
                        // echo "The $time_in is greater than $grace_period_time. Late by $late";
                    } else {
                        // echo "Inside the statement, but not exceeding grace period <br>";
                    }

                    if($time_out > $friday_timeout){
                         $overtime = $time_out_obj->diff($sched_timeout)->format('%H:%I:%S');
                        //  echo "you are OT <br>";
                        
                        //  echo $time_out ,"<br>";
                        

                    }else{
                         $overtime = '00:00:00';
                    }

                } else {
                    // echo "Outside the statement";
                }

                

                if ($time_out) {
                    // Convert time_in and time_out to DateTime objects
                    $time_in_datetime = new DateTime($time_in);
                    $time_out_datetime = new DateTime($time_out);
                
                    // Check if the employee's time_in is past the scheduled time_in
                    $actual_time_in = max($time_in_datetime, new DateTime($time['fri_timein']));
                
                    // Check if the time_in minutes are less than the grace_period
                    $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0;
                    $minutes_in_time_in = intval($actual_time_in->format('i'));

                        $sched_ot_total = new DateTime($time['fri_timeout']);
                        $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available

                        if ($sched_ot_time > 0) {
                            $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                            $sched_ot_total->add($sched_ot_interval);
                        }
                    
                    if ($minutes_in_time_in <= $grace_period_minutes) {
                        // Calculate the total work hours from the scheduled time_in to time_out
                        $interval = $time_out_datetime->diff(new DateTime($time['fri_timein']));
                        $late = '00:00:00';
                        // var_dump($time_out_datetime);
                        
                    } else {
                        // Calculate the total work hours from actual time_in to time_out
                        $interval = $time_out_datetime->diff($time_in_datetime); 
                    }

                    if ($time_out >= $time['fri_timeout']) {
                        // Calculate overtime
                        $total_work_time = new DateTime($total_work);
                        $fri_timein = new DateTime($time['fri_timein']);
                        $sched_ot_total = new DateTime($time['fri_timeout']);
                        $fri_timeout = new DateTime($time['fri_timeout']);
                        
                        $time_in_datetime = new DateTime($time_in);
                        // $time_out_datetime = new DateTime($time_out);


                        $time_out_obj = DateTime::createFromFormat('H:i', $time_out);
                        
                        $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available

                        if ($sched_ot_time > 0) {
                            $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                            $sched_ot_total->add($sched_ot_interval);
                        }


                        // Get the minutes from $time_out
                        

                        if ($time_out_obj >  $sched_ot_total) {
                            // $total_hehe = $sched_ot_minutes + $time_out_minutes;
                            if($minutes_in_time_in <= $grace_period_minutes){
                                $interval = $fri_timein->diff($time_out_datetime); 

                                $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                $total_work = $total_work_datetime->format('H:i:s');
 
                                // Subtract 1 hour (3600 seconds) for lunch break
                                 // $time_out_datetime->sub(new DateInterval('PT1H'));
 
                                 $get_overtime = $fri_timeout->diff($time_out_datetime);
 
                                 // Format the overtime as 'H:i:s'
                                 $overtime = $get_overtime->format('%H:%I:%S');
                                //  var_dump($total_work ,'eto yung sa may late pero pasok sa grace period then nag ot');
                            }else{
                           $interval = $time_in_datetime->diff($time_out_datetime); 

                           $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                           $total_work_datetime->sub(new DateInterval('PT1H'));
                           $total_work = $total_work_datetime->format('H:i:s');

                           // Subtract 1 hour (3600 seconds) for lunch break
                            // $time_out_datetime->sub(new DateInterval('PT1H'));

                            $get_overtime = $fri_timeout->diff($time_out_datetime);

                            // Format the overtime as 'H:i:s'
                            $overtime = $get_overtime->format('%H:%I:%S');

                            // var_dump($total_work, 'eto yung sa may late pero hindi pasok sa grace period then nag ot');
                           
                            }
                        }else{
                             // Calculate the interval between $mon_timein and $fri_timeout
                        $scheduled_interval = $fri_timein->diff($fri_timeout);

                        // Convert the interval to a timestamp
                        $scheduled_timestamp = $fri_timein->getTimestamp() + $scheduled_interval->format('%s');

                        // Subtract one hour (3600 seconds) for the lunch break
                        $scheduled_timestamp -= 3600;

                        // Create a new DateTime object with the updated timestamp
                        $scheduled_time = new DateTime();
                        $scheduled_time->setTimestamp($scheduled_timestamp);

                        // Format the scheduled time as a string
                        $total_work = $scheduled_time->format('H:i');
                        $overtime = '00:00:00';
                           
                        // var_dump($total_work, 'hehe');
                        } 
                                      
                    } else {
                        $overtime = '00:00:00';
                    }

                    // if($time_in_datetime >= $  )
                
                    // Subtract lunch break (1 hour) from the total work duration
                    $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                    $total_work_datetime->sub(new DateInterval('PT1H'));
                    $total_work = $total_work_datetime->format('H:i:s');

                   
                    $get_sched_ot = $time['sched_ot'];
                    $get_fri_timeout = $time['fri_timeout'];
                    $get_fri_timein = $time['fri_timein'];

                    $convert_fri_timeout = new DateTime($get_fri_timeout);
                    $convert_time_in = new DateTime($time_in);
                    $convert_time_out = new DateTime($time_out);


                    // Convert $get_sched_ot to minutes
                    $sched_ot_minutes = (int) $get_sched_ot;
                                            
                    // Convert $get_fri_timeout to a DateTime object
                    $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_fri_timeout);
                                            
                    // Add $sched_ot_minutes to $mon_timeout_datetime
                    $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                                            
                    // Format the resulting time as 'H:i'
                    $result_sched_ot = $mon_timeout_datetime->format('H:i');

                   
                       

                    if ($minutes_in_time_in <= $grace_period_minutes && $time_out <= $result_sched_ot ){
                        
                        $convert_fri_timeout = new DateTime($get_fri_timeout);
                        $convert_time_in = new DateTime($get_fri_timein);
                        
                        $total_work_interval = $convert_time_in->diff($convert_fri_timeout);
                        
                        // Create a new DateInterval representing one hour
                        $one_hour_interval = new DateInterval('PT1H');
                        
                        // Subtract one hour from the $convert_mon_timeout DateTime object
                        $convert_fri_timeout->sub($one_hour_interval);
                        
                        // Calculate the updated total work time interval after subtracting an hour
                        $total_work_interval = $convert_time_in->diff($convert_fri_timeout);
                        
                        $total_work = $total_work_interval->format('%H:%I:%S');
                        $overtime = '00:00:00';
                        
                     //    echo $total_work;
                        
                            
                    }else{
                        echo "<script> alert('Theres an error to your csv file.); </script>";
                    }

                } else {
                    $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                }


                if($time_in < '00:00:00'){
                    $early_out = '00:00:00';
                    $total_work = '00:00:00';
                    $total_rest = '08:00:00';
                }

                $get_sched_ot = $time['sched_ot'];
                $get_week_timeout = $time['fri_timeout'];
                $get_week_timein = $time['fri_timein'];
                
                $convert_week_timeout = new DateTime($get_week_timeout);
                $convert_time_in = new DateTime($time_in);
                
                // Check if time_out is set
                if (!empty($time_out)) {
                    $convert_time_out = new DateTime($time_out);
                    
                    // Calculate the interval between time_in and time_out
                    $interval = $convert_time_in->diff($convert_time_out);
                
                    // Subtract lunch break (1 hour) from the total work duration
                    $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                    $total_work_datetime->sub(new DateInterval('PT1H'));
                    $total_work = $total_work_datetime->format('H:i:s');
                
                    // Convert $get_sched_ot to minutes
                    $sched_ot_minutes = (int) $get_sched_ot;
                
                    // Convert $get_week_timeout to a DateTime object
                    $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_week_timeout);
                
                    // Add $sched_ot_minutes to $mon_timeout_datetime
                    $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                
                    // Format the resulting time as 'H:i'
                    $result_sched_ot = $mon_timeout_datetime->format('H:i');
                    
                    // Handle any further logic for the case when time_out is set
                } else {
                    // Handle the case when time_out is not set (e.g., user only provided time_in)
                    // You can set defaults or handle it accordingly
                    $total_work = '00:00:00';
                    $result_sched_ot = '00:00';
                    $early_out = '00:00:00';
                    // Handle any further logic for this case
                }

                if(!empty($time_in)){
                //   echo "hindi gumana";
                }else{
                   // Convert wed_timein and time_out to DateTime objects
                       $convert_week_timein = new DateTime($get_week_timein);
                       $convert_time_out = new DateTime($time_out);

                       // Subtract an hour from time_out
                       $convert_time_out->sub(new DateInterval('PT1H'));

                       // Calculate the interval between wed_timein and time_out
                       $interval = $convert_week_timein->diff($convert_time_out);

                       // Format the interval as 'H:i:s'
                       $interval_formatted = $interval->format('%H:%I:%S');

                       // Assign the formatted interval to $early_out
                       $early_out = $interval_formatted;

                   $total_work = '00:00:00';
                   $late = '00:00:00';
                //    echo "walang time_in";
                }

                if($time_out < $time['fri_timeout']){
                   $get_timeout = $time['fri_timeout'];
                   $time_out_datetime = new DateTime($time_out);
                   $week_timeout_datetime = new DateTime($get_timeout);
                   
                   // Check if $time_out is earlier than $wed_timeout
                   if ($time_out_datetime < $week_timeout_datetime) {
                       // Calculate the difference between $time_out and $wed_timeout
                       $early_interval = $time_out_datetime->diff($week_timeout_datetime);
                   
                       // Format the early out interval as 'H:i:s'
                       $early_out = $early_interval->format('%H:%I:%S');
                   
                       // Get the current time
                       $current_time = new DateTime();
                       
                       // Check if the current time is after 12:00 PM (noon)
                       $noon_time = new DateTime('12:00:00');
                       if ($current_time > $noon_time) {
                           // Convert total work interval to a DateTime object
                           $total_work_datetime = new DateTime($total_work);
                           
                           // Subtract an hour from the total work time
                           $total_work_datetime->sub(new DateInterval('PT1H'));
                           
                           // Format the updated total work time
                           $total_work = $total_work_datetime->format('H:i:s');
                       }
                   } else {
                       $early_out = '00:00:00';
                   }
                   
                //    echo $early_out; // Display the calculated early out time
                //    echo $total_work; // Display the calculated total work time

                   //  echo $total_work;
                } else { 
                    $early_out = '00:00:00';
                }
            }elseif($currentDayOfWeek == $saturday){
                // echo "it is saturday";
                // Check if the employee is late
                $grace_period_total = new DateTime($time['sat_timein']);
                $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                
                if ($grace_period_minutes > 0) {
                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                    $grace_period_total->add($grace_period_interval);
                }
                
                // Get the minutes from sat_timein and grace_period
                $sat_timein = (int)date('i', strtotime($time['sat_timein']));
                $grace_period_minutes = isset($time['grace_period']) ? (int)$time['grace_period'] : 0;

                // Convert time_in to DateTime object
                $time_in_datetime = new DateTime($time_in);

                // Calculate the late time
                $late_minutes = (int)$time_in_datetime->format('i') - $grace_period_minutes;

                if ($late_minutes >= 0) {
                    // Calculate the amount of late
                    $late = (new DateTime($time_in))->diff(new DateTime($time['sat_timein']))->format('%H:%I:%S');
                } else {
                    // Set the late time to 00:00:00
                    $late = '00:00:00';
                }  

                $half_day = strtotime('13:00:00');
                $og_time_formatteds = date('H:i', $half_day);
                
                // Convert time strings to DateTime objects for accurate comparison
                $time_in_obj = DateTime::createFromFormat("H:i", $time_in);
                $og_time_obj = DateTime::createFromFormat("H:i", $og_time_formatteds);
                
                //for ot
                $time_out_obj = DateTime::createFromFormat("H:i", $time_out);
                $sched_timeout = DateTime::createFromFormat("H:i", $saturday_timeout);


                
                if ($time_in_obj >= $og_time_obj) {
                    // echo "<br> The $time_in is greater than or equal to $og_time_formatteds<br>";
                
                    $grace_period_minutes = intval($grace_period_minutes);
                    $og_time = strtotime('13:00:00');
                    $grace_period_total = $og_time + ($grace_period_minutes * 60);
                
                    $grace_period_time = date('H:i', $grace_period_total);
                
                    // Convert grace period string to DateTime object
                    $grace_period_obj = DateTime::createFromFormat("H:i", $grace_period_time);
                
                    // Compare DateTime objects
                    if ($time_in_obj > $grace_period_obj) {
                        $late = $time_in_obj->diff($og_time_obj)->format('%H:%I:%S');
                        // echo "The $time_in is greater than $grace_period_time. Late by $late";
                    } else {
                        // echo "Inside the statement, but not exceeding grace period <br>";
                    }

                    if($time_out > $saturday_timeout){
                         $overtime = $time_out_obj->diff($sched_timeout)->format('%H:%I:%S');
                        //  echo "you are OT <br>";
                        // 
                        //  echo $time_out ,"<br>";
                        

                    }else{
                         $overtime = '00:00:00';
                    }

                } else {
                    // echo "Outside the statement";
                }
                
                

                if ($time_out) {
                    // Convert time_in and time_out to DateTime objects
                    $time_in_datetime = new DateTime($time_in);
                    $time_out_datetime = new DateTime($time_out);
                
                    // Check if the employee's time_in is past the scheduled time_in
                    $actual_time_in = max($time_in_datetime, new DateTime($time['sat_timein']));
                
                    // Check if the time_in minutes are less than the grace_period
                    $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0;
                    $minutes_in_time_in = intval($actual_time_in->format('i'));

                        $sched_ot_total = new DateTime($time['sat_timeout']);
                        $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available

                        if ($sched_ot_time > 0) {
                            $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                            $sched_ot_total->add($sched_ot_interval);
                        }
                    
                    if ($minutes_in_time_in <= $grace_period_minutes) {
                        // Calculate the total work hours from the scheduled time_in to time_out
                        $interval = $time_out_datetime->diff(new DateTime($time['sat_timein']));
                        $late = '00:00:00';
                        // var_dump($time_out_datetime);
                        
                    } else {
                        // Calculate the total work hours from actual time_in to time_out
                        $interval = $time_out_datetime->diff($time_in_datetime); 
                    }

                    if ($time_out >= $time['sat_timeout']) {
                        // Calculate overtime
                        $total_work_time = new DateTime($total_work);
                        $sat_timein = new DateTime($time['sat_timein']);
                        $sched_ot_total = new DateTime($time['sat_timeout']);
                        $sat_timeout = new DateTime($time['sat_timeout']);
                        
                        $time_in_datetime = new DateTime($time_in);
                        // $time_out_datetime = new DateTime($time_out);


                        $time_out_obj = DateTime::createFromFormat('H:i', $time_out);
                        
                        $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available

                        if ($sched_ot_time > 0) {
                            $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                            $sched_ot_total->add($sched_ot_interval);
                        }


                        // Get the minutes from $time_out
                        

                        if ($time_out_obj >  $sched_ot_total) {
                            // $total_hehe = $sched_ot_minutes + $time_out_minutes;
                            if($minutes_in_time_in <= $grace_period_minutes){
                                $interval = $sat_timein->diff($time_out_datetime); 

                                $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                $total_work = $total_work_datetime->format('H:i:s');
 
                                // Subtract 1 hour (3600 seconds) for lunch break
                                 // $time_out_datetime->sub(new DateInterval('PT1H'));
 
                                 $get_overtime = $sat_timeout->diff($time_out_datetime);
 
                                 // Format the overtime as 'H:i:s'
                                 $overtime = $get_overtime->format('%H:%I:%S');
                                //  var_dump($total_work ,'eto yung sa may late pero pasok sa grace period then nag ot');
                            }else{
                           $interval = $time_in_datetime->diff($time_out_datetime); 

                           $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                           $total_work_datetime->sub(new DateInterval('PT1H'));
                           $total_work = $total_work_datetime->format('H:i:s');

                           // Subtract 1 hour (3600 seconds) for lunch break
                            // $time_out_datetime->sub(new DateInterval('PT1H'));

                            $get_overtime = $sat_timeout->diff($time_out_datetime);

                            // Format the overtime as 'H:i:s'
                            $overtime = $get_overtime->format('%H:%I:%S');

                            // var_dump($total_work, 'eto yung sa may late pero hindi pasok sa grace period then nag ot');
                           
                            }
                        }else{
                             // Calculate the interval between $mon_timein and $sat_timeout
                        $scheduled_interval = $sat_timein->diff($sat_timeout);

                        // Convert the interval to a timestamp
                        $scheduled_timestamp = $sat_timein->getTimestamp() + $scheduled_interval->format('%s');

                        // Subtract one hour (3600 seconds) for the lunch break
                        $scheduled_timestamp -= 3600;

                        // Create a new DateTime object with the updated timestamp
                        $scheduled_time = new DateTime();
                        $scheduled_time->setTimestamp($scheduled_timestamp);

                        // Format the scheduled time as a string
                        $total_work = $scheduled_time->format('H:i');
                        $overtime = '00:00:00';
                           
                        // var_dump($total_work, 'hehe');
                        } 
                                      
                    } else {
                        $overtime = '00:00:00';
                    }

                    // if($time_in_datetime >= $  )
                
                    // Subtract lunch break (1 hour) from the total work duration
                    $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                    $total_work_datetime->sub(new DateInterval('PT1H'));
                    $total_work = $total_work_datetime->format('H:i:s');

                   
                    $get_sched_ot = $time['sched_ot'];
                    $get_sat_timeout = $time['sat_timeout'];
                    $get_sat_timein = $time['sat_timein'];

                    $convert_sat_timeout = new DateTime($get_sat_timeout);
                    $convert_time_in = new DateTime($time_in);
                    $convert_time_out = new DateTime($time_out);


                    // Convert $get_sched_ot to minutes
                    $sched_ot_minutes = (int) $get_sched_ot;
                                            
                    // Convert $get_sat_timeout to a DateTime object
                    $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_sat_timeout);
                                            
                    // Add $sched_ot_minutes to $mon_timeout_datetime
                    $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                                            
                    // Format the resulting time as 'H:i'
                    $result_sched_ot = $mon_timeout_datetime->format('H:i');

                   
                       

                    if ($minutes_in_time_in <= $grace_period_minutes && $time_out <= $result_sched_ot ){
                        
                        $convert_sat_timeout = new DateTime($get_sat_timeout);
                        $convert_time_in = new DateTime($get_sat_timein);
                        
                        $total_work_interval = $convert_time_in->diff($convert_sat_timeout);
                        
                        // Create a new DateInterval representing one hour
                        $one_hour_interval = new DateInterval('PT1H');
                        
                        // Subtract one hour from the $convert_mon_timeout DateTime object
                        $convert_sat_timeout->sub($one_hour_interval);
                        
                        // Calculate the updated total work time interval after subtracting an hour
                        $total_work_interval = $convert_time_in->diff($convert_sat_timeout);
                        
                        $total_work = $total_work_interval->format('%H:%I:%S');
                        $overtime = '00:00:00';
                        
                     //    echo $total_work;
                        
                            
                    }else{
                        echo "<script> alert('Theres an error to your csv file.); </script>";
                    }

                } else {
                    $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                }


                
                if($time_in < '00:00:00'){
                    $early_out = '00:00:00';
                    $total_work = '00:00:00';
                    $total_rest = '08:00:00';
                }

                $get_sched_ot = $time['sched_ot'];
                $get_week_timeout = $time['sat_timeout'];
                $get_week_timein = $time['sat_timein'];
                
                $convert_week_timeout = new DateTime($get_week_timeout);
                $convert_time_in = new DateTime($time_in);
                
                // Check if time_out is set
                if (!empty($time_out)) {
                    $convert_time_out = new DateTime($time_out);
                    
                    // Calculate the interval between time_in and time_out
                    $interval = $convert_time_in->diff($convert_time_out);
                
                    // Subtract lunch break (1 hour) from the total work duration
                    $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                    $total_work_datetime->sub(new DateInterval('PT1H'));
                    $total_work = $total_work_datetime->format('H:i:s');
                
                    // Convert $get_sched_ot to minutes
                    $sched_ot_minutes = (int) $get_sched_ot;
                
                    // Convert $get_week_timeout to a DateTime object
                    $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_week_timeout);
                
                    // Add $sched_ot_minutes to $mon_timeout_datetime
                    $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                
                    // Format the resulting time as 'H:i'
                    $result_sched_ot = $mon_timeout_datetime->format('H:i');
                    
                    // Handle any further logic for the case when time_out is set
                } else {
                    // Handle the case when time_out is not set (e.g., user only provided time_in)
                    // You can set defaults or handle it accordingly
                    $total_work = '00:00:00';
                    $result_sched_ot = '00:00';
                    $early_out = '00:00:00';
                    // Handle any further logic for this case
                }

                if(!empty($time_in)){
                //   echo "hindi gumana";
                }else{
                   // Convert wed_timein and time_out to DateTime objects
                       $convert_week_timein = new DateTime($get_week_timein);
                       $convert_time_out = new DateTime($time_out);

                       // Subtract an hour from time_out
                       $convert_time_out->sub(new DateInterval('PT1H'));

                       // Calculate the interval between wed_timein and time_out
                       $interval = $convert_week_timein->diff($convert_time_out);

                       // Format the interval as 'H:i:s'
                       $interval_formatted = $interval->format('%H:%I:%S');

                       // Assign the formatted interval to $early_out
                       $early_out = $interval_formatted;

                   $total_work = '00:00:00';
                   $late = '00:00:00';
                //    echo "walang time_in";
                }

                if($time_out < $time['sat_timeout']){
                   $get_timeout = $time['sat_timeout'];
                   $time_out_datetime = new DateTime($time_out);
                   $week_timeout_datetime = new DateTime($get_timeout);
                   
                   // Check if $time_out is earlier than $wed_timeout
                   if ($time_out_datetime < $week_timeout_datetime) {
                       // Calculate the difference between $time_out and $wed_timeout
                       $early_interval = $time_out_datetime->diff($week_timeout_datetime);
                   
                       // Format the early out interval as 'H:i:s'
                       $early_out = $early_interval->format('%H:%I:%S');
                   
                       // Get the current time
                       $current_time = new DateTime();
                       
                       // Check if the current time is after 12:00 PM (noon)
                       $noon_time = new DateTime('12:00:00');
                       if ($current_time > $noon_time) {
                           // Convert total work interval to a DateTime object
                           $total_work_datetime = new DateTime($total_work);
                           
                           // Subtract an hour from the total work time
                           $total_work_datetime->sub(new DateInterval('PT1H'));
                           
                           // Format the updated total work time
                           $total_work = $total_work_datetime->format('H:i:s');
                       }
                   } else {
                       $early_out = '00:00:00';
                   }
                   
                //    echo $early_out; // Display the calculated early out time
                //    echo $total_work; // Display the calculated total work time

                   //  echo $total_work;
                } else { 
                    $early_out = '00:00:00';
                }
             }elseif($currentDayOfWeek == $sunday){
                // echo "it is sunday";
                // Check if the employee is late
                $grace_period_total = new DateTime($time['sun_timein']);
                $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0; // Retrieve grace period from $time array or set to 0 if not available
                
                if ($grace_period_minutes > 0) {
                    $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                    $grace_period_total->add($grace_period_interval);
                }
                
                // Get the minutes from sun_timein and grace_period
                $sun_timein = (int)date('i', strtotime($time['sun_timein']));
                $grace_period_minutes = isset($time['grace_period']) ? (int)$time['grace_period'] : 0;

                // Convert time_in to DateTime object
                $time_in_datetime = new DateTime($time_in);

                // Calculate the late time
                $late_minutes = (int)$time_in_datetime->format('i') - $grace_period_minutes;

                if ($late_minutes >= 0) {
                    // Calculate the amount of late
                    $late = (new DateTime($time_in))->diff(new DateTime($time['sun_timein']))->format('%H:%I:%S');
                } else {
                    // Set the late time to 00:00:00
                    $late = '00:00:00';
                }             
                
                $half_day = strtotime('13:00:00');
                $og_time_formatteds = date('H:i', $half_day);
                
                // Convert time strings to DateTime objects for accurate comparison
                $time_in_obj = DateTime::createFromFormat("H:i", $time_in);
                $og_time_obj = DateTime::createFromFormat("H:i", $og_time_formatteds);
                
                //for ot
                $time_out_obj = DateTime::createFromFormat("H:i", $time_out);
                $sched_timeout = DateTime::createFromFormat("H:i", $sunday_timeout);


                
                if ($time_in_obj >= $og_time_obj) {
                    // echo "<br> The $time_in is greater than or equal to $og_time_formatteds<br>";
                
                    $grace_period_minutes = intval($grace_period_minutes);
                    $og_time = strtotime('13:00:00');
                    $grace_period_total = $og_time + ($grace_period_minutes * 60);
                
                    $grace_period_time = date('H:i', $grace_period_total);
                
                    // Convert grace period string to DateTime object
                    $grace_period_obj = DateTime::createFromFormat("H:i", $grace_period_time);
                
                    // Compare DateTime objects
                    if ($time_in_obj > $grace_period_obj) {
                        $late = $time_in_obj->diff($og_time_obj)->format('%H:%I:%S');
                        // echo "The $time_in is greater than $grace_period_time. Late by $late";
                    } else {
                        // echo "Inside the statement, but not exceeding grace period <br>";
                    }

                    if($time_out > $sunday_timeout){
                         $overtime = $time_out_obj->diff($sched_timeout)->format('%H:%I:%S');
                        //  echo "you are OT <br>";
                        
                        //  echo $time_out ,"<br>";
                        

                    }else{
                         $overtime = '00:00:00';
                    }

                } else {
                    // echo "Outside the statement";
                }

                

                if ($time_out) {
                    // Convert time_in and time_out to DateTime objects
                    $time_in_datetime = new DateTime($time_in);
                    $time_out_datetime = new DateTime($time_out);
                
                    // Check if the employee's time_in is past the scheduled time_in
                    $actual_time_in = max($time_in_datetime, new DateTime($time['sun_timein']));
                
                    // Check if the time_in minutes are less than the grace_period
                    $grace_period_minutes = isset($time['grace_period']) ? $time['grace_period'] : 0;
                    $minutes_in_time_in = intval($actual_time_in->format('i'));

                        $sched_ot_total = new DateTime($time['sun_timeout']);
                        $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available

                        if ($sched_ot_time > 0) {
                            $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                            $sched_ot_total->add($sched_ot_interval);
                        }
                    
                    if ($minutes_in_time_in <= $grace_period_minutes) {
                        // Calculate the total work hours from the scheduled time_in to time_out
                        $interval = $time_out_datetime->diff(new DateTime($time['sun_timein']));
                        $late = '00:00:00';
                        // var_dump($time_out_datetime);
                        
                    } else {
                        // Calculate the total work hours from actual time_in to time_out
                        $interval = $time_out_datetime->diff($time_in_datetime); 
                    }

                    if ($time_out >= $time['sun_timeout']) {
                        // Calculate overtime
                        $total_work_time = new DateTime($total_work);
                        $sun_timein = new DateTime($time['sun_timein']);
                        $sched_ot_total = new DateTime($time['sun_timeout']);
                        $sun_timeout = new DateTime($time['sun_timeout']);
                        
                        $time_in_datetime = new DateTime($time_in);
                        // $time_out_datetime = new DateTime($time_out);


                        $time_out_obj = DateTime::createFromFormat('H:i', $time_out);
                        
                        $sched_ot_time = isset($time['sched_ot']) ? $time['sched_ot'] : 0; // Retrieve grace period from $time array or set to 0 if not available

                        if ($sched_ot_time > 0) {
                            $sched_ot_interval = new DateInterval('PT' . $sched_ot_time . 'M');
                            $sched_ot_total->add($sched_ot_interval);
                        }


                        // Get the minutes from $time_out
                        

                        if ($time_out_obj >  $sched_ot_total) {
                            // $total_hehe = $sched_ot_minutes + $time_out_minutes;
                            if($minutes_in_time_in <= $grace_period_minutes){
                                $interval = $sun_timein->diff($time_out_datetime); 

                                $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                $total_work = $total_work_datetime->format('H:i:s');
 
                                // Subtract 1 hour (3600 seconds) for lunch break
                                 // $time_out_datetime->sub(new DateInterval('PT1H'));
 
                                 $get_overtime = $sun_timeout->diff($time_out_datetime);
 
                                 // Format the overtime as 'H:i:s'
                                 $overtime = $get_overtime->format('%H:%I:%S');
                                //  var_dump($total_work ,'eto yung sa may late pero pasok sa grace period then nag ot');
                            }else{
                           $interval = $time_in_datetime->diff($time_out_datetime); 

                           $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                           $total_work_datetime->sub(new DateInterval('PT1H'));
                           $total_work = $total_work_datetime->format('H:i:s');

                           // Subtract 1 hour (3600 seconds) for lunch break
                            // $time_out_datetime->sub(new DateInterval('PT1H'));

                            $get_overtime = $sun_timeout->diff($time_out_datetime);

                            // Format the overtime as 'H:i:s'
                            $overtime = $get_overtime->format('%H:%I:%S');

                            // var_dump($total_work, 'eto yung sa may late pero hindi pasok sa grace period then nag ot');
                           
                            }
                        }else{
                             // Calculate the interval between $mon_timein and $sun_timeout
                        $scheduled_interval = $sun_timein->diff($sun_timeout);

                        // Convert the interval to a timestamp
                        $scheduled_timestamp = $sun_timein->getTimestamp() + $scheduled_interval->format('%s');

                        // Subtract one hour (3600 seconds) for the lunch break
                        $scheduled_timestamp -= 3600;

                        // Create a new DateTime object with the updated timestamp
                        $scheduled_time = new DateTime();
                        $scheduled_time->setTimestamp($scheduled_timestamp);

                        // Format the scheduled time as a string
                        $total_work = $scheduled_time->format('H:i');
                        $overtime = '00:00:00';
                           
                        // var_dump($total_work, 'hehe');
                        } 
                                      
                    } else {
                        $overtime = '00:00:00';
                    }

                    // if($time_in_datetime >= $  )
                
                    // Subtract lunch break (1 hour) from the total work duration
                    $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                    $total_work_datetime->sub(new DateInterval('PT1H'));
                    $total_work = $total_work_datetime->format('H:i:s');

                   
                    $get_sched_ot = $time['sched_ot'];
                    $get_sun_timeout = $time['sun_timeout'];
                    $get_sun_timein = $time['sun_timein'];

                    $convert_sun_timeout = new DateTime($get_sun_timeout);
                    $convert_time_in = new DateTime($time_in);
                    $convert_time_out = new DateTime($time_out);


                    // Convert $get_sched_ot to minutes
                    $sched_ot_minutes = (int) $get_sched_ot;
                                            
                    // Convert $get_sun_timeout to a DateTime object
                    $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_sun_timeout);
                                            
                    // Add $sched_ot_minutes to $mon_timeout_datetime
                    $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                                            
                    // Format the resulting time as 'H:i'
                    $result_sched_ot = $mon_timeout_datetime->format('H:i');

                   
                       

                    if ($minutes_in_time_in <= $grace_period_minutes && $time_out <= $result_sched_ot ){
                        
                        $convert_sun_timeout = new DateTime($get_sun_timeout);
                        $convert_time_in = new DateTime($get_sun_timein);
                        
                        $total_work_interval = $convert_time_in->diff($convert_sun_timeout);
                        
                        // Create a new DateInterval representing one hour
                        $one_hour_interval = new DateInterval('PT1H');
                        
                        // Subtract one hour from the $convert_mon_timeout DateTime object
                        $convert_sun_timeout->sub($one_hour_interval);
                        
                        // Calculate the updated total work time interval after subtracting an hour
                        $total_work_interval = $convert_time_in->diff($convert_sun_timeout);
                        
                        $total_work = $total_work_interval->format('%H:%I:%S');
                        $overtime = '00:00:00';
                        
                     //    echo $total_work;
                        
                            
                    }else{
                        echo "<script> alert('Theres an error to your csv file.); </script>";
                    }

                } else {
                    $total_work = '00:00:00'; // Set total work to 0:00 if no time_out
                }


               
                if($time_in < '00:00:00'){
                    $early_out = '00:00:00';
                    $total_work = '00:00:00';
                    $total_rest = '08:00:00';
                }

                $get_sched_ot = $time['sched_ot'];
                $get_week_timeout = $time['sun_timeout'];
                $get_week_timein = $time['sun_timein'];
                
                $convert_week_timeout = new DateTime($get_week_timeout);
                $convert_time_in = new DateTime($time_in);
                
                // Check if time_out is set
                if (!empty($time_out)) {
                    $convert_time_out = new DateTime($time_out);
                    
                    // Calculate the interval between time_in and time_out
                    $interval = $convert_time_in->diff($convert_time_out);
                
                    // Subtract lunch break (1 hour) from the total work duration
                    $total_work_datetime = new DateTime($interval->format('%H:%I:%S'));
                    $total_work_datetime->sub(new DateInterval('PT1H'));
                    $total_work = $total_work_datetime->format('H:i:s');
                
                    // Convert $get_sched_ot to minutes
                    $sched_ot_minutes = (int) $get_sched_ot;
                
                    // Convert $get_week_timeout to a DateTime object
                    $mon_timeout_datetime = DateTime::createFromFormat('H:i', $get_week_timeout);
                
                    // Add $sched_ot_minutes to $mon_timeout_datetime
                    $mon_timeout_datetime->add(new DateInterval('PT' . $sched_ot_minutes . 'M'));
                
                    // Format the resulting time as 'H:i'
                    $result_sched_ot = $mon_timeout_datetime->format('H:i');
                    
                    // Handle any further logic for the case when time_out is set
                } else {
                    // Handle the case when time_out is not set (e.g., user only provided time_in)
                    // You can set defaults or handle it accordingly
                    $total_work = '00:00:00';
                    $result_sched_ot = '00:00';
                    $early_out = '00:00:00';
                    // Handle any further logic for this case
                }

                if(!empty($time_in)){
                //   echo "hindi gumana";
                }else{
                   // Convert wed_timein and time_out to DateTime objects
                       $convert_week_timein = new DateTime($get_week_timein);
                       $convert_time_out = new DateTime($time_out);

                       // Subtract an hour from time_out
                       $convert_time_out->sub(new DateInterval('PT1H'));

                       // Calculate the interval between wed_timein and time_out
                       $interval = $convert_week_timein->diff($convert_time_out);

                       // Format the interval as 'H:i:s'
                       $interval_formatted = $interval->format('%H:%I:%S');

                       // Assign the formatted interval to $early_out
                       $early_out = $interval_formatted;

                   $total_work = '00:00:00';
                   $late = '00:00:00';
                //    echo "walang time_in";
                }

                if($time_out < $time['sun_timeout']){
                   $get_timeout = $time['sun_timeout'];
                   $time_out_datetime = new DateTime($time_out);
                   $week_timeout_datetime = new DateTime($get_timeout);
                   
                   // Check if $time_out is earlier than $wed_timeout
                   if ($time_out_datetime < $week_timeout_datetime) {
                       // Calculate the difference between $time_out and $wed_timeout
                       $early_interval = $time_out_datetime->diff($week_timeout_datetime);
                   
                       // Format the early out interval as 'H:i:s'
                       $early_out = $early_interval->format('%H:%I:%S');
                   
                       // Get the current time
                       $current_time = new DateTime();
                       
                       // Check if the current time is after 12:00 PM (noon)
                       $noon_time = new DateTime('12:00:00');
                       if ($current_time > $noon_time) {
                           // Convert total work interval to a DateTime object
                           $total_work_datetime = new DateTime($total_work);
                           
                           // Subtract an hour from the total work time
                           $total_work_datetime->sub(new DateInterval('PT1H'));
                           
                           // Format the updated total work time
                           $total_work = $total_work_datetime->format('H:i:s');
                       }
                   } else {
                       $early_out = '00:00:00';
                   }
                   
                //    echo $early_out; // Display the calculated early out time
                //    echo $total_work; // Display the calculated total work time

                   //  echo $total_work;
                } else { 
                    $early_out = '00:00:00';
                }
                    
        } 

        // echo $tuesday_timeout , $time_out;
        
        // echo $time_out;
        // echo "<br>";

        // echo $thursday;

        // if($currentDayOfWeek === $wednesday){
        //     echo $thursday;
        // } else{
        //     echo $thursday;
        // }
               // Assuming $db is your database connection object

// Check if empid exists in the employee_tb
$empQuery = "SELECT * FROM employee_tb WHERE empid = ?";
$empStmt = $db->prepare($empQuery);
$empStmt->bind_param("s", $empid);
$empStmt->execute();
$empResult = $empStmt->get_result();

if ($empResult->num_rows < 1) {
    echo '<script>alert("Error: Unable to insert data for non-existing Employee ID because the Employee ID does not exist in the database.")</script>';
    echo "<script>window.location.href = '../../attendance.php';</script>";
    exit;
} else {
    $prevQuery = "SELECT id FROM attendances WHERE empid = ?";
    $prevStmt = $db->prepare($prevQuery);
    $prevStmt->bind_param("s", $empid);
    $prevStmt->execute();
    $prevResult = $prevStmt->get_result();

    // ... your other code ...
    $empids = explode(",", $empid);
    $dates = explode(",", $date);

    for ($i = 0; $i < count($empids); $i++) {
        $currentEmpid = $empids[$i];
        $currentDate = $dates[$i];
        
        // Assuming you have executed the query to fetch attendance records for the current empid and date
        $recordQuery = "SELECT * FROM attendances WHERE empid = ? AND date = ?";
        $recordStmt = $db->prepare($recordQuery);
        $recordStmt->bind_param("ss", $currentEmpid, $currentDate);
        $recordStmt->execute();
        $recordResult = $recordStmt->get_result();
        
        if ($recordResult->num_rows > 0) {

            // Update the existing record
            $updateQuery = "UPDATE attendances SET
                status = ?, time_in = ?, time_out = ?, late = ?, early_out = ?, overtime = ?, total_work = ?, total_rest = ?
                WHERE empid = ? AND date = ?";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->bind_param("ssssssssss", $status, $time_in, $time_out, $late, $early_out, $overtime, $total_work, $total_rest, $currentEmpid, $currentDate);
            $updateStmt->execute();
            // echo "<br>data update";
        } else {
            // Insert a new record
            $insertQuery = "INSERT INTO attendances (status, empid, date, time_in, time_out, late, early_out, overtime, total_work, total_rest)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->bind_param("ssssssssss", $status, $currentEmpid, $currentDate, $time_in, $time_out, $late, $early_out, $overtime, $total_work, $total_rest);
            $insertStmt->execute();

            // echo "<br>data insert";
        }

        // ... your other code ...
    }
}


                }
                    
            }       
        }              
    }
}

            
          // Close opened CSV file
          fclose($csvFile);
            
}
            }
        
    

     
if (isset($_SESSION['alert_msg'])) {
    echo '<script>alert("'.$_SESSION['alert_msg'].'");</script>';
    unset($_SESSION['alert_msg']);
}
// Redirect to the listing page
header("Location: ../../attendance.php");


