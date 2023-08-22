<?php

$result_valid_holiday = mysqli_query($conn, " SELECT
                                    *
                                    FROM 
                                    `attendances` 
                                    WHERE `empid` =  '$EmployeeID' AND `date` = '$valid_holiday'");

                                    $row_emp_holiday_att = mysqli_fetch_assoc($result_valid_holiday);

                                    $emp_holiday_timeIN =  $row_emp_holiday_att['time_in']; //holiday date attedance timein
                                    $emp_holiday_timeOUT = $row_emp_holiday_att['time_out']; //holiday date attedance timeout


                                    if($emp_holiday_timeIN != '00:00:00' && $emp_holiday_timeOUT != '00:00:00'){ //if pumasok ang employee sa holiday
                                            $daily_rate = $row_emp['drate'];
                                           

                                    //-------------------------Para sa nag WORKED ang employee sa holdiday AT RESTDAY NIYA----------------------

                                    $day_of_validHoliday = date('l', strtotime($valid_holiday)); //convert the each date to day
                                    // echo $date . " = " . $day_of_week ."<br> <br>";

                                    if($day_of_validHoliday === 'Monday')
                                        {

                                            // -----------------------BREAK MONDAY START----------------------------//
                                            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == '')
                                            {
                                                                                              
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowance) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowance;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                                //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------



                                                     

                                            }
                                            else{

                                                $double_pay_holiday += ($daily_rate * 1.3) + $allowance; //if holiday and worked

                                                //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                $result_holiday_OT = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
    
                                                if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                    $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                    
    
                                                    $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                    $OT_hour = $time_OT_con->format('H');
                                                    $OT_totalHour = intval($OT_hour);
    
                                                    $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
    
                                                }
    
                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                       
                                        // -----------------------BREAK MONDAY START----------------------------//

                                        // -----------------------BREAK Tuesday START----------------------------//

                                    else if($day_of_validHoliday === 'Tuesday')
                                        {

                                            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == '')
                                            {
                                              
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowance) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowance;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else{

                                                $double_pay_holiday += ($daily_rate * 1.3) + $allowance; //if holiday and worked

                                                //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                $result_holiday_OT = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
    
                                                if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                    $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                    
    
                                                    $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                    $OT_hour = $time_OT_con->format('H');
                                                    $OT_totalHour = intval($OT_hour);
    
                                                    $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
    
                                                }
    
                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }

                                           
                                        // -----------------------BREAK Tuesday END----------------------------//

                                        // -----------------------BREAK WEDNESDAY START----------------------------//
                                    else if($day_of_validHoliday === 'Wednesday')
                                        {

                                            if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == '')
                                            {
                                                //if restday  at pumasok siya
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowance) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowance;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            
                                            }
                                            else
                                            {
                                               //if HINDI restday
                                                $double_pay_holiday += ($daily_rate * 1.3) + $allowance; //if holiday and worked

                                               //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                               $result_holiday_OT = mysqli_query($conn, " SELECT
                                                   *
                                               FROM 
                                                   `overtime_tb` 
                                               WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
   
                                               if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                   $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                   
   
                                                   $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                   $OT_hour = $time_OT_con->format('H');
                                                   $OT_totalHour = intval($OT_hour);
   
                                                   $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
   
                                               }
   
                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                            
                                        }
                                              

                                                
                                        // -----------------------BREAK WEDNESDAY END----------------------------//

                                        // -----------------------BREAK THURSDAY START----------------------------//

                                    else if($day_of_validHoliday === 'Thursday')
                                        {

                                            if($row_Sched['thurs_timeout'] === NULL || $row_Sched['thurs_timeout'] === '')
                                            {                                                            
                                                //IF restday at pumasok
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowance) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowance;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else 
                                            {
                                                 //if HINDI restday
                                                 $double_pay_holiday += ($daily_rate * 1.3) + $allowance; //if holiday and worked

                                                 //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                 $result_holiday_OT = mysqli_query($conn, " SELECT
                                                     *
                                                 FROM 
                                                     `overtime_tb` 
                                                 WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
     
                                                 if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                     $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                     
     
                                                     $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                     $OT_hour = $time_OT_con->format('H');
                                                     $OT_totalHour = intval($OT_hour);
     
                                                     $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
     
                                                 }
     
                                         //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                            
                                        // -----------------------BREAK THURSDAY END----------------------------//


                                        // -----------------------BREAK FRIDAY START----------------------------//

                                    else if($day_of_validHoliday === 'Friday')
                                        {

                                            if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == '')
                                            {
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowance) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowance;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else
                                            {
                                                 //if HINDI restday
                                                 $double_pay_holiday += ($daily_rate * 1.3) + $allowance; //if holiday and worked

                                                //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                $result_holiday_OT = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
    
                                                if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                    $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                    
    
                                                    $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                    $OT_hour = $time_OT_con->format('H');
                                                    $OT_totalHour = intval($OT_hour);
    
                                                    $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
    
                                                }
    
                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                        


                                        // -----------------------BREAK FRIDAY END----------------------------//


                                        // -----------------------BREAK Saturday START----------------------------//

                                    else if($day_of_validHoliday === 'Saturday')
                                        {

                                            if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == '')
                                            {
                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowance) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowance;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else
                                            {
                                                 //if HINDI restday
                                                 $double_pay_holiday += ($daily_rate * 1.3) + $allowance; //if holiday and worked

                                                 //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                 $result_holiday_OT = mysqli_query($conn, " SELECT
                                                     *
                                                 FROM 
                                                     `overtime_tb` 
                                                 WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
     
                                                 if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                     $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                     
     
                                                     $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                     $OT_hour = $time_OT_con->format('H');
                                                     $OT_totalHour = intval($OT_hour);
     
                                                     $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
     
                                                 }
     
                                         //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }

                                        }
                                       

                                        // -----------------------BREAK Saturday END----------------------------//

                                        // -----------------------BREAK SUNDAY START----------------------------//
                                    else if($day_of_validHoliday === 'Sunday')
                                        {

                                            if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == '')
                                            {

                                                $result_restDay_worked = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$valid_holiday' AND `status` = 'Present'");

                                            
                                                if(mysqli_num_rows($result_restDay_worked) > 0)
                                                {
                                                    $row_check_att_rest = mysqli_fetch_assoc($result_restDay_worked);

                                                    // $double_pay_holiday_ = ($daily_rate + $allowance) * 2;
                                                    // $double_pay_holiday_initial =  ($daily_rate * 2) * 1.3;


                                                    $double_pay_holiday_restday +=  ($daily_rate * 1.5) + $allowance;
                                                }

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY----------------------
                                                    $result_holiday_OT_restday = mysqli_query($conn, " SELECT
                                                    *
                                                FROM 
                                                    `overtime_tb` 
                                                WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                                                if(mysqli_num_rows($result_holiday_OT_restday) > 0) {
                                                    $row_holiday_OT_restday = mysqli_fetch_assoc($result_holiday_OT_restday);
                                                    

                                                    $time_OT_Restday = DateTime::createFromFormat('H:i:s', $row_holiday_OT_restday['total_ot']);
                                                    $OT_hour_restday = $time_OT_Restday->format('H');
                                                    $OT_totalHour_restday = intval($OT_hour_restday);

                                                    $totalOT_pay_holiday_restday += $emp_OtRate * 1.5 * 1.3 * $OT_totalHour_restday; //if has worked and overtime in Holiday

                                                }

                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday RESTDAY END----------------------
                                            }
                                            else
                                            {
                                                    //if HINDI restday
                                                    $double_pay_holiday += ($daily_rate * 1.3) + $allowance; //if holiday and worked

                                                    //-------------------------Para sa nag OVERTIME ang employee sa holdiday----------------------
                                                    $result_holiday_OT = mysqli_query($conn, " SELECT
                                                        *
                                                    FROM 
                                                        `overtime_tb` 
                                                    WHERE work_schedule =  '$valid_holiday' AND `empid` = '$EmployeeID'  AND `work_schedule` BETWEEN '$str_date' AND  '$end_date' AND `status` = 'Approved'");
        
                                                    if(mysqli_num_rows($result_holiday_OT) > 0) {
                                                        $row_holiday_OT = mysqli_fetch_assoc($result_holiday_OT);
                                                        
        
                                                        $time_OT_con = DateTime::createFromFormat('H:i:s', $row_holiday_OT['total_ot']);
                                                        $OT_hour = $time_OT_con->format('H');
                                                        $OT_totalHour = intval($OT_hour);
        
                                                        $totalOT_pay_holiday += $emp_OtRate * 1.3 * 1.3 * $OT_totalHour; //if has worked and overtime in Holiday
        
                                                    }
        
                                            //-------------------------Para sa nag OVERTIME ang employee sa holdiday END----------------------
                                            }
                                        }
                                       

                                        // -----------------------BREAK SUNDAY END----------------------------//

                                    //-------------------------Para sa nag WORKED ang employee sa holdiday AT RESTDAY NIYA END----------------------




                                    }// IF PUMASOK AT HINDI ABSENT

?>