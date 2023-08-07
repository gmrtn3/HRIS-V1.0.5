<?php

// Get the current date in the Philippines timezone
$timezone = new DateTimeZone('Asia/Manila');
$currentDate = new DateTime('now', $timezone);
$currentDate = $currentDate->format('Y-m-d');

$check = 'NO';
$_query_attendance = "SELECT * FROM attendances";
$result_attendance = mysqli_query($conn, $_query_attendance);
if(mysqli_num_rows($result_attendance) > 0){
    // para mag generate ng automatic absent feature    
    $check = 'YES';
}


// Loop until the last date matches the current date
while (true) {
    // Retrieve the last date from MySQL
    $sql = "SELECT MAX(`date`) AS last_date FROM attendances";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $lastDate = $row["last_date"];

    

    if ($lastDate >= $currentDate && $check === 'YES') {
        // Last date matches the current date, exit the loop
        break;
    }

    // Run code 


    $query = "SELECT * FROM `attendances` WHERE `status` =  'Present' OR `status` = 'On-Leave'";
    $result = $conn->query($query);
    
    // Check if any rows are fetched
    if ($result->num_rows > 0) 
    {
       
        $empAttendance = array(); // Array to store the dates
       
        // Loop through each row
        while($row = $result->fetch_assoc()) 
        {
            $empid = $row['empid'];
            $emp_timeout = $row['time_out'];
            $emp_date = $row['date'];
    
            $empAttendance[] = array('empid' => $empid, 'emp_timeout' => $emp_timeout, 'emp_date' => $emp_date);
        }
    
         
        foreach ($empAttendance as $emp_Att_reset) 
        {
            $array_empid = $emp_Att_reset['empid'];
            $array_timeout = $emp_Att_reset['emp_timeout'];
            $array_date = $emp_Att_reset['emp_date'];
    
    
            if ($array_timeout != '00:00:00') { // If employee has timed out
            
                $array_timeout = $emp_Att_reset['emp_timeout']; // 18:00:00
                $array_date = $emp_Att_reset['emp_date']; // 2023-06-19
            
                $combinedDateTimeATT = $array_date . ' ' . $array_timeout;
                $dateTime = new DateTime($combinedDateTimeATT);
            
                $dateTime->add(new DateInterval('PT6H'));
            
                $resetdateTime = $dateTime->format('Y-m-d H:i:s'); // Value: 2023-06-19 21:00:00
            
                date_default_timezone_set("Asia/Manila");
                $currentDateTime = date("Y-m-d H:i:s"); // Value: 2023-06-19 17:31:55
    
                $resetdateTime_new = new DateTime($resetdateTime); //10pm
                $currentDateTime_new = new DateTime($currentDateTime); //09pm
    
                $interval = $resetdateTime_new->diff($currentDateTime_new);
                $days = (int) $interval->format('%r%a'); // %r represents the sign (+/-) of the interval
                $hours = (int) $interval->format('%r%H');
                $minutes = (int) $interval->format('%r%i');
                $seconds = (int) $interval->format('%r%s');
    
    
               
                //need malagayn na ang countdown is 0 na tsaka siya mag insert
                if ($days >= 0 && $hours >= 0 && $minutes >= 0 && $seconds >= 0) {
                    $date_tommorow = new DateTime($array_date);
                    $date_tommorow->modify('+1 day');
    
    
                    $query = "SELECT * FROM `holiday_tb` WHERE `holiday_type` = 'Regular Holiday' AND `date_holiday` = '" . $date_tommorow->format('Y-m-d') . "'";
                    $result = $conn->query($query);
            
                    // Check if any rows are fetched
                    if ($result->num_rows > 0) {
                        // if merong Non- Working days for tommorow
                            $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                            $result = $conn->query($query);
                    
                            // Check if any rows are fetched
                            if ($result->num_rows > 0) {
                                // Nothing to do because attendance already exists for the next day
                            } else {
                                $sql = "INSERT into attendances(`status`, `empid`, `date`) 
                                        VALUES('Present', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "')";
                    
                                if (mysqli_query($conn, $sql)) {
                                    // Insert successful
                                }
                            }
                    } else {
                        
                            $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                            $result = $conn->query($query);
                    
                            // Check if any rows are fetched
                            if ($result->num_rows > 0) {
                                // Nothing to do because attendance already exists for the next day
                            } else {
                                $sql = "INSERT into attendances(`status`, `empid`, `date`) 
                                        VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "')";
                    
                                if (mysqli_query($conn, $sql)) {
                                    // Insert successful
                                }
                            }
                    }
            
                }
                
            }
            else
            {
                // if HINDI nag time out maabsent through scheduled timeout plus 6 hours
    
               
    
                //FOR GETTING THE SCHEDULE FOR THE DAY OF ATTENDANCE
                 $sql_empSched = mysqli_query($conn, " SELECT
                        *  
                    FROM
                        empschedule_tb
                    WHERE empid = $array_empid");
                    if(mysqli_num_rows($sql_empSched) > 0) {
                        $row_empSched = mysqli_fetch_assoc($sql_empSched);
                        //echo $row_empSched['empid'] . " " . $row_empSched['schedule_name'];
                        $schedule_name = $row_empSched['schedule_name'];
    
                            //para sa pag select sa schedule base sa schedule na fetch 
                                $sql_sched = mysqli_query($conn, " SELECT
                                    *  
                                FROM
                                `schedule_tb`
                                WHERE `schedule_name` = '$schedule_name'");
    
                                if(mysqli_num_rows($sql_sched) > 0) {
                                    $row_Sched = mysqli_fetch_assoc($sql_sched);
                                    //echo $row_Sched['mon_timein'];
                                } else {
                                    echo "No results found schedule.";
                                } 
                            //para sa pag select sa schedule base sa schedule na fetch (END)
    
                    } else {
                        echo "No results found.";
                    }  // END ELSE SQL_EMPSCHED
    
                    $date = strtotime($array_date);
                    $att_day_array = date("l", $date);
    
                    if($att_day_array === 'Monday'){
                        if($row_Sched['mon_timeout'] === NULL || $row_Sched['mon_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['mon_timeout'];
                            //$emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }
                         
                     }
                     else if($att_day_array === 'Tuesday'){
                        if($row_Sched['tues_timein'] === NULL || $row_Sched['tues_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['tues_timeout'];
                            //$emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }                      
                     }
                     else if($att_day_array === 'Wednesday'){
                         
                        if($row_Sched['wed_timein'] === NULL || $row_Sched['wed_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['wed_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                     }
                     else if($att_day_array === 'Thursday'){
    
                        if($row_Sched['thurs_timein'] === NULL || $row_Sched['thurs_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['thurs_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                         
                     }
                     else if($att_day_array === 'Friday'){
    
                             
                        if($row_Sched['fri_timein'] === NULL || $row_Sched['fri_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['fri_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                         
                     }
                     else if($att_day_array === 'Saturday'){
                                
                        if($row_Sched['sat_timein'] === NULL || $row_Sched['sat_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['sat_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                        
                     }
                     else if($att_day_array === 'Sunday'){
                        if($row_Sched['sun_timein'] === NULL || $row_Sched['sun_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['sun_timeout'];
                            //$emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                         
                     }
                     

    
                    $array_date = $emp_Att_reset['emp_date']; // 2023-06-19
            
                    $combinedDateTimeATT = $array_date . ' ' . $emp_timeout;
                    $dateTime = new DateTime($combinedDateTimeATT);
                
                    $dateTime->add(new DateInterval('PT6H'));
                
                    $resetdateTime = $dateTime->format('Y-m-d H:i:s'); // Value: 2023-06-19 21:00:00
                
                    date_default_timezone_set("Asia/Manila");
                    $currentDateTime = date("Y-m-d H:i:s"); // Value: 2023-06-19 17:31:55
    
                    $resetdateTime_new = new DateTime($resetdateTime); //10pm
                    $currentDateTime_new = new DateTime($currentDateTime); //09pm
    
                    $interval = $resetdateTime_new->diff($currentDateTime_new);
                    $days = (int) $interval->format('%r%a'); // %r represents the sign (+/-) of the interval
                    $hours = (int) $interval->format('%r%H');
                    $minutes = (int) $interval->format('%r%i');
                    $seconds = (int) $interval->format('%r%s');
    
                if ($days >= 0 && $hours >= 0 && $minutes >= 0 && $seconds >= 0)
                {
                        $date_tommorow = new DateTime($array_date);
                        $date_tommorow->modify('+1 day');


                        
                     include 'Data Controller/Attendance/check_restday.php';
                    

                     if($rest_day === 'no'){
                        $query = "SELECT * FROM `holiday_tb` WHERE `holiday_type` = 'Regular Holiday' AND `date_holiday` = '" . $date_tommorow->format('Y-m-d') . "'";
                        $result = $conn->query($query);
                
                        // Check if any rows are fetched
                        if ($result->num_rows > 0) {
                                $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                $result = $conn->query($query);
                        
                                // Check if any rows are fetched
                                if ($result->num_rows > 0) {
                                    // Nothing to do because attendance already exists for the next day
                                } else {
                                    $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                    VALUES('Present', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                        
                                    if (mysqli_query($conn, $sql)) {
                                       
                                    }
                                }
                        }
                        else
                        {
                                $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                $result = $conn->query($query);
                        
                                // Check if any rows are fetched
                                if ($result->num_rows > 0) {
                                    // Nothing to do because attendance already exists for the next day
                                } else {
                                    $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                            VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                        
                                    if (mysqli_query($conn, $sql)) {
                                        // $sql = "DELETE FROM `attendances` WHERE `time_out` = '01:01:01'";
                                        // $result = mysqli_query($conn, $sql);
                                    }
                                }
                            }   
                     }
                    else
                    { // if restday niya bukas
                        $query = "SELECT * FROM `holiday_tb` WHERE `holiday_type` = 'Regular Holiday' AND `date_holiday` = '" . $date_tommorow->format('Y-m-d') . "'";
                        $result = $conn->query($query);
                
                        // Check if any rows are fetched
                        if ($result->num_rows > 0)
                        {
                                $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                $result = $conn->query($query);
                        
                                // Check if any rows are fetched
                                if ($result->num_rows > 0) {
                                    // Nothing to do because attendance already exists for the next day
                                } else {
                                    $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                    VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                        
                                    if (mysqli_query($conn, $sql)) {
                                       
                                    }
                                }
                        }
                        else
                        {
                            $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                $result = $conn->query($query);
                        
                                // Check if any rows are fetched
                                if ($result->num_rows > 0) {
                                    // Nothing to do because attendance already exists for the next day
                                } else {
                                    $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                            VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                        
                                    if (mysqli_query($conn, $sql)) {
                                        // $sql = "DELETE FROM `attendances` WHERE `time_out` = '01:01:01'";
                                        // $result = mysqli_query($conn, $sql);
                                    }
                                }
                        }//end else if no holiday tomorrow
                    } //end else if restday bukas
    
                                              
                } //end cooldown of dates
    
            }//end if no timeout
        } //END FOR EACH
    } //END FIRST SQL
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
     //ELSE ABSENT OR LWOP MAG AUTO ABSENT PARIN EVERY 6HOURS
     $query = "SELECT * FROM `attendances` WHERE `status` !=  'Present' OR `status` != 'On-Leave'";
     $result = $conn->query($query);
    
     // Check if any rows are fetched
     if ($result->num_rows > 0) 
     {
         $empAttendance = array(); // Array to store the dates
    
         // Loop through each row
         while($row = $result->fetch_assoc()) 
         {
             $empid = $row['empid'];
             $emp_timeout = $row['time_out'];
             $emp_date = $row['date'];
    
             $empAttendance[] = array('empid' => $empid, 'emp_timeout' => $emp_timeout, 'emp_date' => $emp_date);
         }
    
         
         foreach ($empAttendance as $emp_Att_reset) 
         {
             $array_empid = $emp_Att_reset['empid'];
             $array_timeout = $emp_Att_reset['emp_timeout'];
             $array_date = $emp_Att_reset['emp_date'];
    
    
             if ($array_timeout != '00:00:00') {
                 // If employee has timed out
             
                 $array_timeout = $emp_Att_reset['emp_timeout']; // 18:00:00
                 $array_date = $emp_Att_reset['emp_date']; // 2023-06-19
             
                 $combinedDateTimeATT = $array_date . ' ' . $array_timeout;
                 $dateTime = new DateTime($combinedDateTimeATT);
             
                 $dateTime->add(new DateInterval('PT6H'));
             
                 $resetdateTime = $dateTime->format('Y-m-d H:i:s'); // Value: 2023-06-19 21:00:00
             
                 date_default_timezone_set("Asia/Manila");
                 $currentDateTime = date("Y-m-d H:i:s"); // Value: 2023-06-19 17:31:55
    
                 $resetdateTime_new = new DateTime($resetdateTime); //10pm
                 $currentDateTime_new = new DateTime($currentDateTime); //09pm
    
                 $interval = $resetdateTime_new->diff($currentDateTime_new);
                 $days = (int) $interval->format('%r%a'); // %r represents the sign (+/-) of the interval
                 $hours = (int) $interval->format('%r%H');
                 $minutes = (int) $interval->format('%r%i');
                 $seconds = (int) $interval->format('%r%s');
    
             
                 //need malagayn na ang countdown is 0 na tsaka siya mag insert
                 if ($days >= 0 && $hours >= 0 && $minutes >= 0 && $seconds >= 0) {
                     $date_tommorow = new DateTime($array_date);
                     $date_tommorow->modify('+1 day');
             
                    
                        $query = "SELECT * FROM `holiday_tb` WHERE `holiday_type` = 'Regular Holiday' AND `date_holiday` = '" . $date_tommorow->format('Y-m-d') . "'";
                        $result = $conn->query($query);
                
                        // Check if any rows are fetched
                        if ($result->num_rows > 0) {
                            // if merong Non- Working days for tommorow
                                $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                $result = $conn->query($query);
                        
                                // Check if any rows are fetched
                                if ($result->num_rows > 0) {
                                    // Nothing to do because attendance already exists for the next day
                                } else {
                                    $sql = "INSERT into attendances(`status`, `empid`, `date`) 
                                            VALUES('Present', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "')";
                        
                                    if (mysqli_query($conn, $sql)) {
                                        // Insert successful
                                    }
                                }
                        } else {
                            
                                $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                $result = $conn->query($query);
                        
                                // Check if any rows are fetched
                                if ($result->num_rows > 0) {
                                    // Nothing to do because attendance already exists for the next day
                                } else {
                                    $sql = "INSERT into attendances(`status`, `empid`, `date`) 
                                            VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "')";
                        
                                    if (mysqli_query($conn, $sql)) {
                                        // Insert successful
                                    }
                                }
                        }
    
                 }
                 
             }
             else
             {
                 // if HINDI nag time out maabsent through scheduled timeout plus 6 hours
    
             
    
                 //FOR GETTING THE SCHEDULE FOR THE DAY OF ATTENDANCE
                 $sql_empSched = mysqli_query($conn, " SELECT
                         *  
                     FROM
                         empschedule_tb
                     WHERE empid = $array_empid");
                     if(mysqli_num_rows($sql_empSched) > 0) {
                         $row_empSched = mysqli_fetch_assoc($sql_empSched);
                         //echo $row_empSched['empid'] . " " . $row_empSched['schedule_name'];
                         $schedule_name = $row_empSched['schedule_name'];
    
                             //para sa pag select sa schedule base sa schedule na fetch 
                                 $sql_sched = mysqli_query($conn, " SELECT
                                     *  
                                 FROM
                                 `schedule_tb`
                                 WHERE `schedule_name` = '$schedule_name'");
    
                                 if(mysqli_num_rows($sql_sched) > 0) {
                                     $row_Sched = mysqli_fetch_assoc($sql_sched);
                                     //echo $row_Sched['mon_timein'];
                                 } else {
                                     echo "No results found schedule.";
                                 } 
                             //para sa pag select sa schedule base sa schedule na fetch (END)
    
                     } else {
                         echo "No results found.";
                     }  // END ELSE SQL_EMPSCHED
    
                     $date = strtotime($array_date);
                     $att_day_array = date("l", $date);
    
                     if($att_day_array === 'Monday'){
                        if($row_Sched['mon_timeout'] === NULL || $row_Sched['mon_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['mon_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }
                         
                     }
                     else if($att_day_array === 'Tuesday'){
                        if($row_Sched['tues_timein'] === NULL || $row_Sched['tues_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['tues_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }                      
                     }
                     else if($att_day_array === 'Wednesday'){
                         
                        if($row_Sched['wed_timein'] === NULL || $row_Sched['wed_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['wed_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                     }
                     else if($att_day_array === 'Thursday'){
    
                        if($row_Sched['thurs_timein'] === NULL || $row_Sched['thurs_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['thurs_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                         
                     }
                     else if($att_day_array === 'Friday'){
    
                             
                        if($row_Sched['fri_timein'] === NULL || $row_Sched['fri_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['fri_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                         
                     }
                     else if($att_day_array === 'Saturday'){
                                
                        if(($row_Sched['sat_timein'] === NULL || $row_Sched['sat_timeout'] === '') || ($row_Sched['sat_timein'] === NULL || $row_Sched['sat_timeout'] === '')){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['sat_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                        
                     }
                     else if($att_day_array === 'Sunday'){
                        if($row_Sched['sun_timein'] === NULL || $row_Sched['sun_timeout'] === ''){                                                                       
                            $emp_timeout__ = '01:01:01';                          
                        }else{
                            $emp_timeout = $row_Sched['sun_timeout'];
                            // $emp_timeout = '00:00:00';
                            $emp_timeout__ = '00:00:00';
                        }  
                         
                     }
                     
    
                     $array_date = $emp_Att_reset['emp_date']; 
             
                     $combinedDateTimeATT = $array_date . ' ' . $emp_timeout;
                     $dateTime = new DateTime($combinedDateTimeATT);
                 
                     $dateTime->add(new DateInterval('PT6H'));
                 
                     $resetdateTime = $dateTime->format('Y-m-d H:i:s'); // Value: 2023-06-19 21:00:00
                     
                     date_default_timezone_set("Asia/Manila");
                     $currentDateTime = date("Y-m-d H:i:s"); // Value: 2023-06-19 17:31:55
     
                     $resetdateTime_new = new DateTime($resetdateTime); //10pm
                     $currentDateTime_new = new DateTime($currentDateTime); //09pm
     
                     $interval = $resetdateTime_new->diff($currentDateTime_new);
                     $days = (int) $interval->format('%r%a'); // %r represents the sign (+/-) of the interval
                     $hours = (int) $interval->format('%r%H');
                     $minutes = (int) $interval->format('%r%i');
                     $seconds = (int) $interval->format('%r%s');
    
                     if ($days >= 0 && $hours >= 0 && $minutes >= 0 && $seconds >= 0) {
                         $date_tommorow = new DateTime($array_date);
                         $date_tommorow->modify('+1 day');
                 
                         include 'Data Controller/Attendance/check_restday.php';
                    

                         if($rest_day === 'no'){
                            $query = "SELECT * FROM `holiday_tb` WHERE `holiday_type` = 'Regular Holiday' AND `date_holiday` = '" . $date_tommorow->format('Y-m-d') . "'";
                            $result = $conn->query($query);
                    
                            // Check if any rows are fetched
                            if ($result->num_rows > 0) {
                                    $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                    $result = $conn->query($query);
                            
                                    // Check if any rows are fetched
                                    if ($result->num_rows > 0) {
                                        // Nothing to do because attendance already exists for the next day
                                    } else {
                                        $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                        VALUES('Present', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                            
                                        if (mysqli_query($conn, $sql)) {
                                           
                                        }
                                    }
                            }
                            else
                            {
                                    $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                    $result = $conn->query($query);
                            
                                    // Check if any rows are fetched
                                    if ($result->num_rows > 0) {
                                        // Nothing to do because attendance already exists for the next day
                                    } else {
                                        $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                                VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                            
                                        if (mysqli_query($conn, $sql)) {
                                            // $sql = "DELETE FROM `attendances` WHERE `time_out` = '01:01:01'";
                                            // $result = mysqli_query($conn, $sql);
                                        }
                                    }
                                }   
                         }
                        else
                        { // if restday niya bukas
                            $query = "SELECT * FROM `holiday_tb` WHERE `holiday_type` = 'Regular Holiday' AND `date_holiday` = '" . $date_tommorow->format('Y-m-d') . "'";
                            $result = $conn->query($query);
                    
                            // Check if any rows are fetched
                            if ($result->num_rows > 0)
                            {
                                    $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                    $result = $conn->query($query);
                            
                                    // Check if any rows are fetched
                                    if ($result->num_rows > 0) {
                                        // Nothing to do because attendance already exists for the next day
                                    } else {
                                        $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                        VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                            
                                        if (mysqli_query($conn, $sql)) {
                                           
                                        }
                                    }
                            }
                            else
                            {
                                $query = "SELECT * FROM `attendances` WHERE `empid` = '$array_empid' AND `date` = '" . $date_tommorow->format('Y-m-d') . "'";
                                    $result = $conn->query($query);
                            
                                    // Check if any rows are fetched
                                    if ($result->num_rows > 0) {
                                        // Nothing to do because attendance already exists for the next day
                                    } else {
                                        $sql = "INSERT into attendances(`status`, `empid`, `date`, `time_out`) 
                                                VALUES('Absent', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "', '$emp_timeout__')";
                            
                                        if (mysqli_query($conn, $sql)) {
                                            // $sql = "DELETE FROM `attendances` WHERE `time_out` = '01:01:01'";
                                            // $result = mysqli_query($conn, $sql);
                                        }
                                    }
                            }//end else if no holiday tomorrow
                        } //end else if restday bukas                         
                    } //end cooldown of dates
    
             }//end if no timeout
         } //END FOR EACH
     } //END FIRST SQL ELSE (FOR ABSENT AND LWOP)

    // Wait for a certain period (e.g., 1 hour) before checking again
    sleep(1); // Sleep for 1 hour (3600 seconds)
} //end while loop




?>