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

  




  // select only the latest date of attendance in each employee
  $query = "SELECT a.*
            FROM attendances a
            JOIN (
                SELECT empid, MAX(`date`) AS max_date
                FROM attendances
                GROUP BY empid
            ) max_dates
            ON a.empid = max_dates.empid AND a.date = max_dates.max_date;
            ";
  $result = $conn->query($query);
  
  // Check if any rows are fetched
  if ($result->num_rows > 0) 
  {
      $empAttendance = array(); 
     
      // Loop through each row
      while($row = $result->fetch_assoc()) 
      {
        //   echo "<br>" . $row['date'];

        $empid = $row['empid'];
        $emp_timeout = $row['time_out'];
        $emp_date = $row['date'];
        $emp_status = $row['status'];
  
        $empAttendance[] = array('empid' => $empid, 'emp_timeout' => $emp_timeout, 'emp_date' => $emp_date, 'emp_status' => $emp_status);
      }
      foreach ($empAttendance as $emp_Att_reset){
        $array_empid = $emp_Att_reset['empid'];
        $array_date = $emp_Att_reset['emp_date'];
        $array_timeout = $emp_Att_reset['emp_timeout'];

        
                include 'check_restday.php'; // class to check restday
            
              

                if($rest_day === 'yes') { // if next day is restday
                    //insert status "Restday"

                    $sql = "INSERT into attendances (`status`, `empid`, `date`) 
                    VALUES('Restday', '$array_empid', '" . $date_tommorow->format('Y-m-d') . "')";

                    $result = mysqli_query($conn, $sql);

                } else{ // if nextday is not restday


                     if ($array_timeout != '00:00:00') { //if may time-out

                        $combinedDateTimeATT = $array_date . ' ' . $array_timeout;
                        $dateTime = new DateTime($combinedDateTimeATT);
                    
                        $dateTime->add(new DateInterval('PT6H'));
                    
                        $resetdateTime = $dateTime->format('Y-m-d H:i:s'); 
                    
                        date_default_timezone_set("Asia/Manila");
                        $currentDateTime = date("Y-m-d H:i:s"); 
            
                        $resetdateTime_new = new DateTime($resetdateTime); 
                        $currentDateTime_new = new DateTime($currentDateTime); 
            
                        $interval = $resetdateTime_new->diff($currentDateTime_new);
                        $days = (int) $interval->format('%r%a'); // %r represents the sign (+/-) of the interval
                        $hours = (int) $interval->format('%r%H');
                        $minutes = (int) $interval->format('%r%i');
                        $seconds = (int) $interval->format('%r%s');

                     }else{ // else walang timeout

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
                     }


                    if ($days >= 0 && $hours >= 0 && $minutes >= 0 && $seconds >= 0) { // if ang added 6 hours ay lumagpas na sa current datetime mag iinsert na ito as absent
                        
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
                        
                                    $result = mysqli_query($conn, $sql);
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
                        
                                    $result = mysqli_query($conn, $sql);
                                }
                        }

                    }// end countdown

                }//end else if NOT restday
      }//end foreach
  
  }//end if may attendance ng present at On-Leave

  sleep(1); // Sleep for 1 SEC
}//end while
?>