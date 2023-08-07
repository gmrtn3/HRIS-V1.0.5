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

    
                $conn = mysqli_connect("localhost", "root", "", "hris_db");
                $sql = "SELECT * FROM empschedule_tb WHERE empid = $empid";
                $resulta = mysqli_query($conn, $sql);
                if(mysqli_num_rows($resulta) > 0){
                    $row1 = mysqli_fetch_assoc($resulta);
                
                    $stmt = "SELECT 
                        CAST(monday AS DATE) AS monday_date,
                        CAST(tuesday AS DATE) AS tuesday_date,
                        CAST(wednesday AS DATE) AS wednesday_date,
                        CAST(thursday AS DATE) AS thursday_date,
                        CAST(friday AS DATE) AS friday_date,
                        CAST(saturday AS DATE) AS saturday_date,
                        CAST(sunday AS DATE) AS sunday_date,
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
                        sun_timeout
                    FROM schedule_tb
                    WHERE schedule_name ='".$row1['schedule_name']."'";

                } else{
                    echo 'no found';
                }

                  // Check if empid exists in the employee_tb
                  $empQuery = "SELECT id FROM employee_tb WHERE empid = '$empid'";
                  $empResult = $db->query($empQuery);
  
                  if ($empResult->num_rows == 0) {
                      // Store alert message in session
                      $_SESSION['alert_msg'] = "The employee with empid $empid does not exist in the database.";
  
                      // Redirect to the listing page
                      header("Location: ../../attendance.php?status=err");
                      exit();
                  }
                


                $result = mysqli_query($conn, $stmt);
                while($time = mysqli_fetch_assoc($result)){

            


                $monday = strtr($time['monday_date'], '/' , '-');
                $mondays = date('Y-m-d', strtotime($date));

                $monday_timein = $time['mon_timein'];
                $monday_timeout = $time['mon_timeout'];
                
                
                $tuesday = strtr($time['tuesday_date'], '/', '-');   
                $tuesdays = date('Y-m-d', strtotime($date));

                $tuesday_timein = $time['tues_timein'];
                $tuesday_timeout = $time['tues_timeout'];

                $wednesday = strtr($time['wednesday_date'], '/', '-');   
                $wednesdays = date('Y-m-d', strtotime($date));

                $wednesday_timein = $time['wed_timein'];
                $wednesday_timeout = $time['wed_timeout'];

                $thursday = strtr($time['thursday_date'], '/', '-');   
                $thursdays = date('Y-m-d', strtotime($date));

                $thursday_timein = $time['thurs_timein'];
                $thursday_timeout = $time['thurs_timeout'];

                $friday = strtr($time['friday_date'], '/', '-');   
                $fridays = date('Y-m-d', strtotime($date));

                $friday_timein = $time['fri_timein'];
                $friday_timeout = $time['fri_timeout'];

                $saturday = strtr($time['saturday_date'], '/', '-');   
                $saturdays = date('Y-m-d', strtotime($date));

                $saturday_timein = $time['sat_timein'];
                $saturday_timeout = $time['sat_timeout'];

                $sunday = strtr($time['sunday_date'], '/', '-');   
                $sundays = date('Y-m-d', strtotime($date));

                $sunday_timein = $time['sun_timein'];
                $sunday_timeout = $time['sun_timeout'];

                

                // echo " gsada ".$mondays;

                if($date == $mondays){               
                        // Check if the employee is late
                    if($time_in > $time['mon_timein'] ){
                        // Calculate the amount of late
                        $monday_time_in_datetime = new DateTime($time_in);
                        $monday_scheduled_time = new DateTime($time['mon_timein']);
                        $monday_interval = $monday_time_in_datetime->diff($monday_scheduled_time);
                        $late = $monday_interval->format('%h:%i:%s');
                    }

                    // Calculate the total work hours
                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                    $total_work = date('H:i:s', $total_work);

                    if ($time_out > $time['mon_timeout']) {
                        // Calculate overtime
                        $total_work_time = new DateTime($total_work);
                        $scheduled_times = new DateTime('08:00:00');
                        $intervals = $total_work_time->diff($scheduled_times);
                        $overtime = $intervals->format('%h:%i:%s');
                    } else {
                        $overtime = '00:00:00';
                    }

                    if($time_out < $time['mon_timeout']){
                        $time_out_datetime = new DateTime('08:00:00');
                        $scheduled_outs = new DateTime($total_work);
                        $early_interval = $scheduled_outs->diff($time_out_datetime);
                        $early_out = $early_interval->format('%h:%i:%s');
                    } else { 
                        $early_out = '00:00:00';
                    }

                    if($time_in < '00:00:00'){
                        $early_out = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '08:00:00';
                    }
                }elseif($date == $tuesdays){
                        // Check if the employee is late
                        if($time_in > $time['tues_timein'] ){
                            // Calculate the amount of late
                            $tuesday_time_in_datetime = new DateTime($time_in);
                            $tuesday_scheduled_time = new DateTime($time['tues_timein']);
                            $tuesday_interval = $tuesday_time_in_datetime->diff($tuesday_scheduled_time);
                            $late = $tuesday_interval->format('%h:%i:%s');
                        }else{
                            $late = '00:00:00';
                        }
                        
                        // Calculate the total work hours
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
    
                        if ($time_out > $time['tues_timeout']) {
                            // Calculate overtime
                            $total_work_time = new DateTime($total_work);
                            $scheduled_times = new DateTime('08:00:00');
                            $intervals = $total_work_time->diff($scheduled_times);
                            $overtime = $intervals->format('%h:%i:%s');
    
                        } else {
                            $overtime = '00:00:00';
                        }
    
                        if($time_out < $time['tues_timeout']){
                            $time_out_datetime = new DateTime('08:00:00');
                            $scheduled_outs = new DateTime($total_work);
                            $early_interval = $scheduled_outs->diff($time_out_datetime);
                            $early_out = $early_interval->format('%h:%i:%s');
                        } else { 
                            $early_out = '00:00:00';
                        }
    
                        if($time_in < '00:00:00'){
                            $early_out = '00:00:00';
                            $total_work = '00:00:00';
                            $total_rest = '08:00:00';
                        } else{
                            $total_rest = '00:00:00';
                        }
                }elseif($date == $wednesdays){
                        // Check if the employee is late
                    if($time_in > $time['wed_timein'] ){
                        // Calculate the amount of late
                        $wednesday_time_in_datetime = new DateTime($time_in);
                        $wednesday_scheduled_time = new DateTime($time['wed_timein']);
                        $wednesday_interval = $wednesday_time_in_datetime->diff($wednesday_scheduled_time);
                        $late = $wednesday_interval->format('%h:%i:%s');
                    }else{
                        $late = '00:00:00';
                    }
                    
                    // Calculate the total work hours
                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                    $total_work = date('H:i:s', $total_work);

                    if ($time_out > $time['wed_timeout']) {
                        // Calculate overtime
                        $total_work_time = new DateTime($total_work);
                        $scheduled_times = new DateTime('08:00:00');
                        $intervals = $total_work_time->diff($scheduled_times);
                        $overtime = $intervals->format('%h:%i:%s');

                    } else {
                        $overtime = '00:00:00';
                    }

                    if($time_out < $time['wed_timeout']){
                        $time_out_datetime = new DateTime('08:00:00');
                        $scheduled_outs = new DateTime($total_work);
                        $early_interval = $scheduled_outs->diff($time_out_datetime);
                        $early_out = $early_interval->format('%h:%i:%s');
                    } else { 
                        $early_out = '00:00:00';
                    }

                    if($time_in < '00:00:00'){
                        $early_out = '00:00:00';
                        $total_work = '00:00:00';
                        $total_rest = '08:00:00';
                    } else{
                        $total_rest = '00:00:00';
                    }
                }elseif($date == $thursdays){
                    // Check if the employee is late
                if($time_in > $time['thurs_timein'] ){
                    // Calculate the amount of late
                    $thursday_time_in_datetime = new DateTime($time_in);
                    $thursday_scheduled_time = new DateTime($time['thurs_timein']);
                    $thursday_interval = $thursday_time_in_datetime->diff($thursday_scheduled_time);
                    $late = $thursday_interval->format('%h:%i:%s');
                }else{
                    $late = '00:00:00';
                }
                
                // Calculate the total work hours
                $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                $total_work = date('H:i:s', $total_work);

                if ($time_out > $time['thurs_timeout']) {
                    // Calculate overtime
                    $total_work_time = new DateTime($total_work);
                    $scheduled_times = new DateTime('08:00:00');
                    $intervals = $total_work_time->diff($scheduled_times);
                    $overtime = $intervals->format('%h:%i:%s');

                } else {
                    $overtime = '00:00:00';
                }

                if($time_out < $time['thurs_timeout']){
                    $time_out_datetime = new DateTime($time_out);
                    $scheduled_outs = new DateTime($total_work);
                    $early_interval = $scheduled_outs->diff($time_out_datetime);
                    $early_out = $early_interval->format('%h:%i:%s');
                } else { 
                    $early_out = '00:00:00';
                }

                if($time_in < '00:00:00'){
                    $early_out = '00:00:00';
                    $total_work = '00:00:00';
                    $total_rest = '08:00:00';
                } else{
                    $total_rest = '00:00:00';
                }
            }elseif($date == $fridays){
                    // Check if the employee is late
                if($time_in > $time['fri_timein'] ){
                    // Calculate the amount of late
                    $friday_time_in_datetime = new DateTime($time_in);
                    $friday_scheduled_time = new DateTime($time['fri_timein']);
                    $friday_interval = $friday_time_in_datetime->diff($friday_scheduled_time);
                    $late = $friday_interval->format('%h:%i:%s');
                }else{
                    $late = '00:00:00';
                }
                
                // Calculate the total work hours
                $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                $total_work = date('H:i:s', $total_work);

                if ($time_out > $time['fri_timeout']) {
                    // Calculate overtime
                    $total_work_time = new DateTime($total_work);
                    $scheduled_times = new DateTime('08:00:00');
                    $intervals = $total_work_time->diff($scheduled_times);
                    $overtime = $intervals->format('%h:%i:%s');

                } else {
                    $overtime = '00:00:00';
                }

                if($time_out < $time['fri_timeout']){
                    $time_out_datetime = new DateTime('08:00:00');
                    $scheduled_outs = new DateTime($total_work);
                    $early_interval = $scheduled_outs->diff($time_out_datetime);
                    $early_out = $early_interval->format('%h:%i:%s');
                } else { 
                    $early_out = '00:00:00';
                }

                if($time_in < '00:00:00'){
                    $early_out = '00:00:00';
                    $total_work = '00:00:00';
                    $total_rest = '08:00:00';
                } else{
                    $total_rest = '00:00:00';
                }
            }elseif($date == $saturdays){
                    // Check if the employee is late
                if($time_in > $time['sat_timein'] ){
                    // Calculate the amount of late
                    $saturday_time_in_datetime = new DateTime($time_in);
                    $saturday_scheduled_time = new DateTime($time['sat_timein']);
                    $saturday_interval = $saturday_time_in_datetime->diff($saturday_scheduled_time);
                    $late = $saturday_interval->format('%h:%i:%s');
                }else{
                    $late = '00:00:00';
                }
                
                // Calculate the total work hours
                $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                $total_work = date('H:i:s', $total_work);

                if ($time_out > $time['sat_timeout']) {
                    // Calculate overtime
                    $total_work_time = new DateTime($total_work);
                    $scheduled_times = new DateTime('08:00:00');
                    $intervals = $total_work_time->diff($scheduled_times);
                    $overtime = $intervals->format('%h:%i:%s');

                } else {
                    $overtime = '00:00:00';
                }

                if($time_out < $time['sat_timeout']){
                    $time_out_datetime = new DateTime('08:00:00');
                    $scheduled_outs = new DateTime($total_work);
                    $early_interval = $scheduled_outs->diff($time_out_datetime);
                    $early_out = $early_interval->format('%h:%i:%s');
                } else { 
                    $early_out = '00:00:00';
                }

                if($time_in < '00:00:00'){
                    $early_out = '00:00:00';
                    $total_work = '00:00:00';
                    $total_rest = '08:00:00';
                } else{
                    $total_rest = '00:00:00';
                }
             }else{
                    if($date == $sundays){
                    // Check if the employee is late
                if($time_in > $time['sun_timein'] ){
                    // Calculate the amount of late
                    $sunday_time_in_datetime = new DateTime($time_in);
                    $sunday_scheduled_time = new DateTime($time['sun_timein']);
                    $sunday_interval = $sunday_time_in_datetime->diff($sunday_scheduled_time);
                    $late = $sunday_interval->format('%h:%i:%s');
                }else{
                    $late = '00:00:00';
                }
                
                // Calculate the total work hours
                $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                $total_work = date('H:i:s', $total_work);

                if ($time_out > $time['sun_timeout']) {
                    // Calculate overtime
                    $total_work_time = new DateTime($total_work);
                    $scheduled_times = new DateTime('08:00:00');
                    $intervals = $total_work_time->diff($scheduled_times);
                    $overtime = $intervals->format('%h:%i:%s');

                } else {
                    $overtime = '00:00:00';
                }

                if($time_out < $time['sun_timeout']){
                    $time_out_datetime = new DateTime('08:00:00');
                    $scheduled_outs = new DateTime($total_work);
                    $early_interval = $scheduled_outs->diff($time_out_datetime);
                    $early_out = $early_interval->format('%h:%i:%s');
                } else { 
                    $early_out = '00:00:00';
                }

                if($time_in < '00:00:00'){
                    $early_out = '00:00:00';
                    $total_work = '00:00:00';
                    $total_rest = '08:00:00';
                } else{
                    $total_rest = '00:00:00';
                }
            }

        }

                $empid = $line[1];
                $prevQuery = "SELECT id FROM attendances WHERE empid = '".$line[1]."'";
                $prevResult = $db->query($prevQuery);
                
                if($prevResult->num_rows > 0){
                    // Check if empid exists in the employee_tb
                    $empQuery = "SELECT id FROM employee_tb WHERE empid = '".$empid."' ";
                    $empResult = $db->query($empQuery);
                
                    if($empResult->num_rows == 0){
                        // Store alert message in session
                        $_SESSION['alert_msg'] = "The employee with empid ".$empid." does not exist in the database.";
                    } else {
                        // Insert member data in the database
                        $db->query("INSERT INTO attendances (status, empid, date, time_in, time_out, late, early_out, overtime,total_work, total_rest)
                                    VALUES ('".$status."', '".$empid."', '".$date."', '".$time_in."', '".$time_out."','".$late."','".$early_out."','".$overtime."','".$total_work."','".$total_rest."')");
                    }
                } else {
                    // Insert member data in the database
                    $db->query("INSERT INTO attendances (status, empid, date, time_in, time_out, late, early_out, overtime,total_work, total_rest)
                                VALUES ('".$status."', '".$empid."', '".$date."', '".$time_in."', '".$time_out."','".$late."','".$early_out."','".$overtime."','".$total_work."','".$total_rest."')");
                
                    // Check if empid exists in the employee_tb
                    $empQuery = "SELECT id FROM employee_tb WHERE empid = '".$empid."' ";
                    $empResult = $db->query($empQuery);
                
                    if($empResult->num_rows == 0){
                        // Store alert message in session
                        $_SESSION['alert_msg'] = "The employee with empid ".$empid." does not exist in the database.";
                    }
                }        
            }
        }

            
          // Close opened CSV file
          fclose($csvFile);
            
          
      }else{
          
      }
  }else{
     
  }
}
     
if (isset($_SESSION['alert_msg'])) {
    echo '<script>alert("'.$_SESSION['alert_msg'].'");</script>';
    unset($_SESSION['alert_msg']);
}
// Redirect to the listing page
header("Location: ../../attendance.php".$qstring);