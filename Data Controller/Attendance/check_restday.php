<?php //---------------------------------------------- Check for restday if there is an attendance -----------------------//
    //   //FOR GETTING THE SCHEDULE FOR THE DAY OF ATTENDANCE


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
    echo "No schedule found.";
}  // END ELSE SQL_EMPSCHED
//----------------------------------------------------- CHecking for tommorow day is restday or not ----------------------------------
//converting to date tommorow
$date_tommorow = new DateTime($array_date);
$date_tommorow->modify('+1 day');

//converting to day
$day_tommorow = $date_tommorow->format('Y-m-d');
$day_tommorow = strtotime($day_tommorow);


$day_tommorow = date("l", $day_tommorow);
$rest_day = 'no';
if($day_tommorow === 'Monday'){
    if($row_Sched['mon_timeout'] == NULL || $row_Sched['mon_timeout'] == ''){                                                                       
        $rest_day = 'yes';                          
    }
    else{
        $rest_day = 'no';
    }
    
}
else if($day_tommorow === 'Tuesday'){
    if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timeout'] == ''){                                                                       
        $rest_day = 'yes';                          
    }
    else{
        $rest_day = 'no';
    }          
}
else if($day_tommorow === 'Wednesday'){
    
    if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timeout'] == ''){                                                                       
        $rest_day = 'yes';                          
    }
    else{
        $rest_day = 'no';
    } 
}
else if($day_tommorow === 'Thursday'){

    if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timeout'] == ''){                                                                       
        $rest_day = 'yes';                          
    }
    else{
        $rest_day = 'no';
    }
    
}
else if($day_tommorow === 'Friday'){

        
    if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timeout'] == ''){                                                                       
        $rest_day = 'yes';                          
    }
    else{
        $rest_day = 'no';
    }
    
}
else if($day_tommorow === 'Saturday'){
            
    if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timeout'] == ''){                                                                       
        $rest_day = 'yes';                          
    }
    else{
        $rest_day = 'no';
    }
    
}
else if($day_tommorow === 'Sunday'){
    if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timeout'] == ''){                                                                       
        $rest_day = 'yes';                          
    }
    else{
        $rest_day = 'no';
    }
    
}

//------------------------------TO GET THE SCHDEULED TIMEOUT IF EMPLOYEE DID NOT TIME OUT --------------------------------



$date = strtotime($array_date);
$att_day_array = date("l", $date);

if($att_day_array === 'Monday'){
    if($row_Sched['mon_timeout'] === NULL || $row_Sched['mon_timeout'] === ''){                                                                       
                                  
    }else{
        $emp_timeout = $row_Sched['mon_timeout'];
        
    }
     
 }
 else if($att_day_array === 'Tuesday'){
    if($row_Sched['tues_timein'] === NULL || $row_Sched['tues_timeout'] === ''){                                                                       
                                  
    }else{
        $emp_timeout = $row_Sched['tues_timeout'];
        
    }                      
 }
 else if($att_day_array === 'Wednesday'){
     
    if($row_Sched['wed_timein'] === NULL || $row_Sched['wed_timeout'] === ''){                                                                       
                                  
    }else{
        $emp_timeout = $row_Sched['wed_timeout'];
        
    }  
 }
 else if($att_day_array === 'Thursday'){

    if($row_Sched['thurs_timein'] === NULL || $row_Sched['thurs_timeout'] === ''){                                                                       
                                  
    }else{
        $emp_timeout = $row_Sched['thurs_timeout'];
        
    }  
     
 }
 else if($att_day_array === 'Friday'){

         
    if($row_Sched['fri_timein'] === NULL || $row_Sched['fri_timeout'] === ''){                                                                       
                                  
    }else{
        $emp_timeout = $row_Sched['fri_timeout'];
        
    }  
     
 }
 else if($att_day_array === 'Saturday'){
            
    if($row_Sched['sat_timein'] === NULL || $row_Sched['sat_timeout'] === ''){                                                                       
                                  
    }else{
        $emp_timeout = $row_Sched['sat_timeout'];
        
    }  
    
 }
 else if($att_day_array === 'Sunday'){
    if($row_Sched['sun_timein'] === NULL || $row_Sched['sun_timeout'] === ''){                                                                       
                                  
    }else{
        $emp_timeout = $row_Sched['sun_timeout'];
        
    }  
     
 }

    //   $sql_empSched = mysqli_query($conn, " SELECT
    //     *  
    // FROM
    //     empschedule_tb
    // WHERE empid = $array_empid");
    // if(mysqli_num_rows($sql_empSched) > 0) {
    //     $row_empSched = mysqli_fetch_assoc($sql_empSched);
    //     //echo $row_empSched['empid'] . " " . $row_empSched['schedule_name'];
    //     $schedule_name = $row_empSched['schedule_name'];

    //         //para sa pag select sa schedule base sa schedule na fetch 
    //             $sql_sched = mysqli_query($conn, " SELECT
    //                 *  
    //             FROM
    //             `schedule_tb`
    //             WHERE `schedule_name` = '$schedule_name'");

    //             if(mysqli_num_rows($sql_sched) > 0) {
    //                 $row_Sched = mysqli_fetch_assoc($sql_sched);
    //                 //echo $row_Sched['mon_timein'];
    //             } else {
    //                 echo "No results found schedule.";
    //             } 
    //         //para sa pag select sa schedule base sa schedule na fetch (END)

    // } else {
    //     echo "No results found.";
    // }  // END ELSE SQL_EMPSCHED
    // $date_tommorowws = $date_tommorow->format('Y-m-d');
    // $dateee = strtotime($date_tommorowws);
    // $tommorow_day = date("l", $dateee);
    // $rest_day = 'no';
    // if($tommorow_day === 'Monday'){
    //     if($row_Sched['mon_timeout'] == NULL || $row_Sched['mon_timeout'] == ''){                                                                       
    //        $rest_day = 'yes';                          
    //     }
    //     else{
    //         $rest_day = 'no';
    //     }
        
    // }
    // else if($tommorow_day === 'Tuesday'){
    //     if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timeout'] == ''){                                                                       
    //         $rest_day = 'yes';                          
    //     }
    //     else{
    //         $rest_day = 'no';
    //     }          
    // }
    // else if($tommorow_day === 'Wednesday'){
        
    //     if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timeout'] == ''){                                                                       
    //         $rest_day = 'yes';                          
    //     }
    //     else{
    //         $rest_day = 'no';
    //     } 
    // }
    // else if($tommorow_day === 'Thursday'){

    //     if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timeout'] == ''){                                                                       
    //         $rest_day = 'yes';                          
    //     }
    //     else{
    //         $rest_day = 'no';
    //     }
        
    // }
    // else if($tommorow_day === 'Friday'){

            
    //     if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timeout'] == ''){                                                                       
    //         $rest_day = 'yes';                          
    //     }
    //     else{
    //         $rest_day = 'no';
    //     }
        
    // }
    // else if($tommorow_day === 'Saturday'){
                
    //     if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timeout'] == ''){                                                                       
    //         $rest_day = 'yes';                          
    //     }
    //     else{
    //         $rest_day = 'no';
    //     }
        
    // }
    // else if($tommorow_day === 'Sunday'){
    //     if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timeout'] == ''){                                                                       
    //         $rest_day = 'yes';                          
    //     }
    //     else{
    //         $rest_day = 'no';
    //     }
        
    // }



?>