<?php
    //VALIDATE IF THE RULE FOR HOLIDAY IS DAY BEFORE HOLIDAY MUST PRESENT or DAY AFTER HOLIDAY MUST PRESENT or DAY BEFORE AND AFTER HOLIDAY MUST PRESENT or DEFAULT THAT HAS NO REQUIREMENTS FOR HOLIDAY"
      
    $validation_eligible_holiday = '';

                            
    $date_before = new DateTime($valid_holiday);
    $date_before->modify('-1 day');
    $date_before = $date_before->format('Y-m-d');
    
    $date_after = new DateTime($valid_holiday);
    $date_after->modify('+1 day');
    $date_after = $date_after->format('Y-m-d');
    
    if($row_company_settings['holiday_pay'] === 'Holiday Before'){


        $day_of_BEFORE_ = date('l', strtotime($date_before)); //convert the each date to day

        // PARA SA PAG CHECK NG SCHEDULE NG DAY BEFORE AND AFTER IF RESTDAY BA OR HINDI
        if($day_of_BEFORE_ === 'Monday'){
            // -----------------------BREAK MONDAY START----------------------------//
            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){

                //if restday ang before day
                $validation_eligible_holiday = 'YES';

            }else{

                //if HINDI restday ang before day
                $result_check_day = mysqli_query($conn, " SELECT
                    *
                FROM 
                    `attendances` 
                WHERE `empid` =  '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

            
                if(mysqli_num_rows($result_check_day) > 0) {
                    $row_check_day = mysqli_fetch_assoc($result_check_day);

                    $validation_eligible_holiday = 'YES';
                }

            }
       }
          
           // -----------------------BREAK MONDAY START----------------------------//

           // -----------------------BREAK Tuesday START----------------------------//

        else if($day_of_BEFORE_ === 'Tuesday'){
            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                       
                 //if restday ang before day
                $validation_eligible_holiday = 'YES';

            }else{

                //if HINDI restday ang before day
                $result_check_day = mysqli_query($conn, " SELECT
                    *
                FROM 
                    `attendances` 
                WHERE `empid` =  '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

            
                if(mysqli_num_rows($result_check_day) > 0) {
                    $row_check_day = mysqli_fetch_assoc($result_check_day);

                    $validation_eligible_holiday = 'YES';
                }

            }
        }

              
           // -----------------------BREAK Tuesday END----------------------------//

           // -----------------------BREAK WEDNESDAY START----------------------------//
           else if($day_of_BEFORE_ === 'Wednesday'){
               if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                  
                    //if restday ang before day
                    $validation_eligible_holiday = 'YES';

               }else {

                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }

               }
           }
                 

                   
           // -----------------------BREAK WEDNESDAY END----------------------------//

           // -----------------------BREAK THURSDAY START----------------------------//

            else if($day_of_BEFORE_ === 'Thursday'){
               if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                            
                   
                    //if restday ang before day
                    $validation_eligible_holiday = 'YES';

               }else{

                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
                
               }
            }
               
           // -----------------------BREAK THURSDAY END----------------------------//


           // -----------------------BREAK FRIDAY START----------------------------//

            else if($day_of_BEFORE_ === 'Friday'){
               if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){

                    //if restday ang before day
                    $validation_eligible_holiday = 'YES';
                

               }else{


                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
               }
           }
           


           // -----------------------BREAK FRIDAY END----------------------------//


           // -----------------------BREAK Saturday START----------------------------//

            else if($day_of_BEFORE_ === 'Saturday'){
               if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                
                   //if restday ang before day
                   $validation_eligible_holiday = 'YES';

               }else{

                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                            *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                    
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
               }
           }
          

           // -----------------------BREAK Saturday END----------------------------//

           // -----------------------BREAK SUNDAY START----------------------------//
            else if($day_of_BEFORE_ === 'Sunday'){
               if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                   
                //if restday ang before day
                $validation_eligible_holiday = 'YES';

               }else{
                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                            *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                    
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
               }
           }
          

           // -----------------------BREAK SUNDAY END----------------------------//




     
        
        
    } //if Holiday before END
    else if($row_company_settings['holiday_pay'] === 'Holiday After'){


        $date_after_ = date('l', strtotime($date_after)); //convert the each date to day

        // PARA SA PAG CHECK NG SCHEDULE NG DAY BEFORE AND AFTER IF RESTDAY BA OR HINDI
        if($date_after_ === 'Monday'){
            // -----------------------BREAK MONDAY START----------------------------//
            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){

                //if restday ang before day
                $validation_eligible_holiday = 'YES';

            }else{
                //if HINDI restday ang before day
                $result_check_day = mysqli_query($conn, " SELECT
                    *
                FROM 
                    `attendances` 
                WHERE `empid` =  '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

            
                if(mysqli_num_rows($result_check_day) > 0) {
                    $row_check_day = mysqli_fetch_assoc($result_check_day);

                    $validation_eligible_holiday = 'YES';
                }

            }
       }
          
           // -----------------------BREAK MONDAY START----------------------------//

           // -----------------------BREAK Tuesday START----------------------------//

        else if($date_after_ === 'Tuesday'){
            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                       
                 //if restday ang before day
                $validation_eligible_holiday = 'YES';

            }else{

                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }

            }
        }

              
           // -----------------------BREAK Tuesday END----------------------------//

           // -----------------------BREAK WEDNESDAY START----------------------------//
           else if($date_after_ === 'Wednesday'){
               if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                  
                    //if restday ang before day
                    $validation_eligible_holiday = 'YES';

               }else {
                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM date_after
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }

               }
           }
                 

                   
           // -----------------------BREAK WEDNESDAY END----------------------------//

           // -----------------------BREAK THURSDAY START----------------------------//

            else if($date_after_ === 'Thursday'){
               if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                            
                   
                    //if restday ang before day
                    $validation_eligible_holiday = 'YES';

               }else{
                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
                    
               }
            }
               
           // -----------------------BREAK THURSDAY END----------------------------//


           // -----------------------BREAK FRIDAY START----------------------------//

            else if($date_after_ === 'Friday'){
               if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){

                    //if restday ang before day
                    $validation_eligible_holiday = 'YES';
                

               }else{

                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
               }
           }
           


           // -----------------------BREAK FRIDAY END----------------------------//


           // -----------------------BREAK Saturday START----------------------------//

            else if($date_after_ === 'Saturday'){
               if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                
                   //if restday ang before day
                   $validation_eligible_holiday = 'YES';

               }else{

                    //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
               }
           }
          

           // -----------------------BREAK Saturday END----------------------------//

           // -----------------------BREAK SUNDAY START----------------------------//
            else if($date_after_ === 'Sunday'){
               if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                   
                //if restday ang before day
                $validation_eligible_holiday = 'YES';

               }else{
                   //if HINDI restday ang before day
                    $result_check_day = mysqli_query($conn, " SELECT
                        *
                    FROM 
                        `attendances` 
                    WHERE `empid` =  '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day) > 0) {
                        $row_check_day = mysqli_fetch_assoc($result_check_day);

                        $validation_eligible_holiday = 'YES';
                    }
               }
           }
          

           // -----------------------BREAK SUNDAY END----------------------------//
        
        


    } // if Holiday After END
    else if($row_company_settings['holiday_pay'] === 'Holiday Before and After'){

                            
//-------------------------------------------------------------------------- FOR DAY BEFORE HOLIDAY -------------------------------------------------------------------------

            $day_of_BEFORE_ = date('l', strtotime($date_before)); //convert the each date to day

        // PARA SA PAG CHECK NG SCHEDULE NG DAY BEFORE AND AFTER IF RESTDAY BA OR HINDI
        if($day_of_BEFORE_ === 'Monday'){
            // -----------------------BREAK MONDAY START----------------------------//
            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){

                //if restday ang before day
                $present_before_holiday = 'Valid';

            }else{

                //if HINDI restday ang before day
                $result_check_day_before = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

       
                if(mysqli_num_rows($result_check_day_before) > 0) {
                    $row_check_day_before = mysqli_fetch_assoc($result_check_day_before);

                    $present_before_holiday = 'Valid';

                }
                else{

                    $present_before_holiday = 'Not Valid';
                                                                  
                }

            }
       }
          
           // -----------------------BREAK MONDAY START----------------------------//

           // -----------------------BREAK Tuesday START----------------------------//

        else if($day_of_BEFORE_ === 'Tuesday'){
            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                       
                 //if restday ang before day
                 $present_before_holiday = 'Valid';

            }else{

                //if HINDI restday ang before day
                $result_check_day_before = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

       
                if(mysqli_num_rows($result_check_day_before) > 0) {
                    $row_check_day_before = mysqli_fetch_assoc($result_check_day_before);

                    $present_before_holiday = 'Valid';

                }
                else{

                    $present_before_holiday = 'Not Valid';
                                                                  
                }

            }
        }

              
           // -----------------------BREAK Tuesday END----------------------------//

           // -----------------------BREAK WEDNESDAY START----------------------------//
           else if($day_of_BEFORE_ === 'Wednesday'){
               if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                  
                    //if restday ang before day
                    $present_before_holiday = 'Valid';

               }else {

                    //if HINDI restday ang before day
                    $result_check_day_before = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

        
                    if(mysqli_num_rows($result_check_day_before) > 0) {
                        $row_check_day_before = mysqli_fetch_assoc($result_check_day_before);

                        $present_before_holiday = 'Valid';

                    }
                    else{

                        $present_before_holiday = 'Not Valid';
                                                                        
                    }

               }
           }
                 

                   
           // -----------------------BREAK WEDNESDAY END----------------------------//

           // -----------------------BREAK THURSDAY START----------------------------//

            else if($day_of_BEFORE_ === 'Thursday'){
               if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                            
                   
                    //if restday ang before day
                    $present_before_holiday = 'Valid';

               }else{

                    //if HINDI restday ang before day
                   $result_check_day_before = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

       
                   if(mysqli_num_rows($result_check_day_before) > 0) {
                       $row_check_day_before = mysqli_fetch_assoc($result_check_day_before);

                       $present_before_holiday = 'Valid';

                   }
                   else{

                       $present_before_holiday = 'Not Valid';
                                                                     
                   }
               }
            }
               
           // -----------------------BREAK THURSDAY END----------------------------//


           // -----------------------BREAK FRIDAY START----------------------------//

            else if($day_of_BEFORE_ === 'Friday'){
               if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){

                    //if restday ang before day
                    $present_before_holiday = 'Valid';
                

               }else{

                   //if HINDI restday ang before day
                   $result_check_day_before = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

       
                   if(mysqli_num_rows($result_check_day_before) > 0) {
                       $row_check_day_before = mysqli_fetch_assoc($result_check_day_before);

                       $present_before_holiday = 'Valid';

                   }
                   else{

                       $present_before_holiday = 'Not Valid';

                   }
               }
           }
           


           // -----------------------BREAK FRIDAY END----------------------------//


           // -----------------------BREAK Saturday START----------------------------//

            else if($day_of_BEFORE_ === 'Saturday'){
               if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                
                   //if restday ang before day
                   $present_before_holiday = 'Valid';

               }else{

                    //if HINDI restday ang before day
                    $result_check_day_before = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

       
                    if(mysqli_num_rows($result_check_day_before) > 0) {
                        $row_check_day_before = mysqli_fetch_assoc($result_check_day_before);

                        $present_before_holiday = 'Valid';

                    }
                    else{

                        $present_before_holiday = 'Not Valid';
                        
                    }
               }
           }
          

           // -----------------------BREAK Saturday END----------------------------//

           // -----------------------BREAK SUNDAY START----------------------------//
            else if($day_of_BEFORE_ === 'Sunday'){
               if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                   
                //if restday ang before day
                $present_before_holiday = 'Valid';

               }else{

                    //if HINDI restday ang before day
                    $result_check_day_before = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_before' AND (`status` = 'Present' OR `status` = 'On-Leave')");

       
                    if(mysqli_num_rows($result_check_day_before) > 0) {
                        $row_check_day_before = mysqli_fetch_assoc($result_check_day_before);

                        $present_before_holiday = 'Valid';

                    }
                    else{

                        $present_before_holiday = 'Not Valid';
                        
                    }
               }
           } // -----------------------BREAK SUNDAY END----------------------------//
            
      

//-------------------------------------------------------------------------- FOR DAY AFTER HOLIDAY -------------------------------------------------------------------------


        $date_after_ = date('l', strtotime($date_after)); //convert the each date to day

        // PARA SA PAG CHECK NG SCHEDULE NG DAY BEFORE AND AFTER IF RESTDAY BA OR HINDI
        if($date_after_ === 'Monday'){
            // -----------------------BREAK MONDAY START----------------------------//
            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){

                //if restday ang after day
                $present_after_holiday = 'Valid';


            }else{
                //if HINDI restday ang after day
                $result_check_day_after = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                if(mysqli_num_rows($result_check_day_after) > 0) {
                    $row_check_day_after = mysqli_fetch_assoc($result_check_day_after);

                    $present_after_holiday = 'Valid';

                }
                else{
                    $present_after_holiday = 'Not Valid';
                }

            }
       }
          
           // -----------------------BREAK MONDAY START----------------------------//

           // -----------------------BREAK Tuesday START----------------------------//

        else if($date_after_ === 'Tuesday'){
            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                       
                  //if restday ang after day
                $present_after_holiday = 'Valid';


            }else{

                    //if HINDI restday ang after day
                    $result_check_day_after = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day_after) > 0) {
                        $row_check_day_after = mysqli_fetch_assoc($result_check_day_after);

                        $present_after_holiday = 'Valid';

                    }
                    else{
                        $present_after_holiday = 'Not Valid';
                    }

            }
        }

              
           // -----------------------BREAK Tuesday END----------------------------//

           // -----------------------BREAK WEDNESDAY START----------------------------//
           else if($date_after_ === 'Wednesday'){
               if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                  
                     //if restday ang after day
                $present_after_holiday = 'Valid';


               }else {
                    //if HINDI restday ang after day
                    $result_check_day_after = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day_after) > 0) {
                        $row_check_day_after = mysqli_fetch_assoc($result_check_day_after);

                        $present_after_holiday = 'Valid';

                    }
                    else{
                        $present_after_holiday = 'Not Valid';
                    }

               }
           }
                 

                   
           // -----------------------BREAK WEDNESDAY END----------------------------//

           // -----------------------BREAK THURSDAY START----------------------------//

            else if($date_after_ === 'Thursday'){
               if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                            
                   
                     //if restday ang after day
                $present_after_holiday = 'Valid';


               }else{
                    //if HINDI restday ang after day
                    $result_check_day_after = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day_after) > 0) {
                        $row_check_day_after = mysqli_fetch_assoc($result_check_day_after);

                        $present_after_holiday = 'Valid';

                    }
                    else{
                        $present_after_holiday = 'Not Valid';
                    }
                    
               }
            }
               
           // -----------------------BREAK THURSDAY END----------------------------//


           // -----------------------BREAK FRIDAY START----------------------------//

            else if($date_after_ === 'Friday'){
               if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){

                    //if restday ang after day
                $present_after_holiday = 'Valid';

                

               }else{

                    //if HINDI restday ang after day
                    $result_check_day_after = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day_after) > 0) {
                        $row_check_day_after = mysqli_fetch_assoc($result_check_day_after);

                        $present_after_holiday = 'Valid';

                    }
                    else{
                        $present_after_holiday = 'Not Valid';
                    }
               }
           }
           


           // -----------------------BREAK FRIDAY END----------------------------//


           // -----------------------BREAK Saturday START----------------------------//

            else if($date_after_ === 'Saturday'){
               if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                
                    //if restday ang after day
                $present_after_holiday = 'Valid';


               }else{

                    //if HINDI restday ang after day
                    $result_check_day_after = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day_after) > 0) {
                        $row_check_day_after = mysqli_fetch_assoc($result_check_day_after);

                        $present_after_holiday = 'Valid';

                    }
                    else{
                        $present_after_holiday = 'Not Valid';
                    }
               }
           }
          

           // -----------------------BREAK Saturday END----------------------------//

           // -----------------------BREAK SUNDAY START----------------------------//
            else if($date_after_ === 'Sunday'){
               if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                   
                //if restday ang after day
                $present_after_holiday = 'Valid';

               }else{

                   //if HINDI restday ang after day
                   $result_check_day_after = mysqli_query($conn, " SELECT * FROM `attendances` WHERE `empid` = '$EmployeeID' AND `date` = '$date_after' AND (`status` = 'Present' OR `status` = 'On-Leave')");

                
                    if(mysqli_num_rows($result_check_day_after) > 0) {
                        $row_check_day_after = mysqli_fetch_assoc($result_check_day_after);

                        $present_after_holiday = 'Valid';

                    }
                    else{
                        $present_after_holiday = 'Not Valid';
                    }
               }
           }
          

           // -----------------------BREAK SUNDAY END----------------------------//

        

        //check if day before and after holiday is present
        if($present_before_holiday === 'Valid' &&  $present_after_holiday === 'Valid'){
            $validation_eligible_holiday = 'YES';
        }else{
            $validation_eligible_holiday = 'NO';
        }



    } // if Holiday before And  After 
    else{
        $validation_eligible_holiday = 'YES';
    }
?>