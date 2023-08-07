<?php
session_start();
include '../../config.php';
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';

   //----------------------------------------------BREAK(FOR Approving)-----------------------------------------------------
     if(isset($_POST['name_approved'])){

        $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
        $approver = $_SESSION["empid"];
       
//Para sa pag select ng mga data galing sa APPLYLEAVE TABLE
        $result = mysqli_query($conn, "SELECT * FROM applyleave_tb WHERE col_ID=  $IDLEAVE_TABLE");
        $row = mysqli_fetch_assoc($result);
        //echo $row['col_strDate'];
        $str_date = $row['col_strDate']; //ginamit ko para e set sa firsthalf at secondhalf na e insert sa attendance
        $end_date = $row['col_endDate'];
       
//Para sa pag select ng mga data galing sa APPLYLEAVE TABLE (END)

//Para sa pag select ng mga data galing sa LEAVE INFO TABLE
        $employee_ID = $_SESSION["ID_empId"]; //employee ID
        $result_leaveINFO = mysqli_query($conn, "SELECT * FROM leaveinfo_tb WHERE col_empID = $employee_ID");
        if(mysqli_num_rows($result_leaveINFO) > 0) {
            $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
            //echo $row__leaveINFO['col_vctionCrdt'];
          } else {
            echo "No results found.";
          }
//Para sa pag select ng mga data galing sa LEAVE INFO TABLE (END)

if($row['col_status'] === 'Approved' ){
    header("Location: ../../leavereq.php?error=You cannot APPROVED a request that is already APPROVED");
}
else if($row['col_status'] === 'Rejected' ){
    header("Location: ../../leavereq.php?error=You cannot APPROVED a request that is already REJECTED");
}
else if($row['col_status'] === 'Cancelled'){
    header("Location: ../../leavereq.php?error=You cannot REJECT a request that is already CANCELLED");
    }
else{

//--------------------------------------------PARA SA PAG MINUS NG CREDITS IF firsthalf HALfDAY-------------------------------------------------

    if($row['col_PAID_LEAVE'] === 'With Pay'){ //para sa pag minus ng credits if WITH PAY ANG LEAVE

        if($row['col_LeavePeriod'] === 'First Half'){
            $day_of_week = date('l', strtotime($str_date)); //convert sa date para gawin anong day

            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB)
                $result_empSched = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid`=  $employee_ID");
                $row_empSched = mysqli_fetch_assoc($result_empSched);
                //echo $row_empSched['schedule_name'];
                $empSched_name = $row_empSched['schedule_name'];
            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB) (END)

            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule)
                $result_Sched = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name`=  '$empSched_name'");
                $row_Sched = mysqli_fetch_assoc($result_Sched);
                //echo $row_Sched['schedule_name'];
            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule) (END)


                    // -----------------------MONDAY START----------------------------//
                    //para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )
                    if($day_of_week === 'Monday'){
                        
                        $time_in = $row_Sched['mon_timein'];
                        $time_out = $row_Sched['mon_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Tuesday')
                    {
                        
                        $time_in = $row_Sched['tues_timein'];
                        $time_out = $row_Sched['tues_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Wednesday')
                    {
                        
                        $time_in = $row_Sched['wed_timein'];
                        $time_out = $row_Sched['wed_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Thursday')
                    {
                        
                        $time_in = $row_Sched['thurs_timein'];
                        $time_out = $row_Sched['thurs_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Friday')
                    {
                        
                        $time_in = $row_Sched['fri_timein'];
                        $time_out = $row_Sched['fri_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Saturday')
                    {
                        
                        $time_in = $row_Sched['sat_timein'];
                        $time_out = $row_Sched['sat_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Sunday')
                    {
                        
                        $time_in = $row_Sched['sun_timein'];
                        $time_out = $row_Sched['sun_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
//para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )

// ----------------------------------------------------BREAK MODIFY HERE 4-15-2023-----------------------------------------------------

            if($row['col_LeaveType'] == 'Vacation Leave'){
                $minusVacationCredits0 = $row__leaveINFO['col_vctionCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
                $sql_minusvacationCredits0 ="UPDATE leaveinfo_tb SET col_vctionCrdt= $minusVacationCredits0 WHERE col_empID = $employee_ID";
                $query_run_minusCredits0 = mysqli_query($conn, $sql_minusvacationCredits0);
            }
            elseif($row['col_LeaveType'] == 'Bereavement Leave'){
                $minusBrvmntCredits0 = $row__leaveINFO['col_brvmntCrdt'] - 0.5; //para mag minus sa credits sa IF Bereavement
                $sql_minusBrvmntCredits0 ="UPDATE leaveinfo_tb SET col_brvmntCrdt= $minusBrvmntCredits0 WHERE col_empID = $employee_ID";
                $query_run_BrvmntminusCredits0 = mysqli_query($conn, $sql_minusBrvmntCredits0);
            }
            elseif($row['col_LeaveType'] == 'Sick Leave'){
                $minusSickCredits0 = $row__leaveINFO['col_sickCrdt'] - 0.5; //para mag minus sa credits sa IF Sick
                $sql_minusSickCredits0 ="UPDATE leaveinfo_tb SET col_sickCrdt= $minusSickCredits0 WHERE col_empID = $employee_ID";
                $query_run_SickminusCredits0 = mysqli_query($conn, $sql_minusSickCredits0);
            }
        
        
               //pra sa pag update ng action taken at status to approved
        
                    // Get the current date and time
                    $now = new DateTime();
                    $now->setTimezone(new DateTimeZone('Asia/Manila'));
                    $currentDateTime = $now->format('Y-m-d H:i:s');
                    $Status = $_SESSION["col_status"];
                    $reason = $_POST["name_approvedtResn"]; 
                    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];


                    $sql1 = "INSERT into attendances(`status`, `empid`,`date`, `time_in`, `time_out`, `total_work` ) 
                                VALUES('On-Leave','$employee_ID', '$str_date',  '$time_in', '$time_out', '$total_work')";
                                  if(mysqli_query($conn,$sql1))
                                  {
                                    $result_att = mysqli_query($conn, "SELECT * FROM `attendances` WHERE `time_in` = '00:00:00'");
                                    $row_att = mysqli_fetch_assoc($result_att);

                                    if ($row_att['time_in'] === '00:00:00'){
                                        $sql = "DELETE FROM `attendances` WHERE `time_in` = '00:00:00'";
                                        $result = mysqli_query($conn, $sql);
                                        if ($result) {
                                            header("Location: ../../leavereq.php?error=You cannot approve a request that is on restday");
                                        }
                                        else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }


                                       
                                    }else{
                                        $sql2 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
                                        VALUES('$IDLEAVE_TABLE','$reason', 'Approved')";
                                          if(mysqli_query($conn,$sql2))
                                          {
                                            $sql3 ="UPDATE applyleave_tb SET col_status= 'Approved', col_dt_action= '$currentDateTime', col_approver = '$approver' WHERE col_ID = $IDLEAVE_TABLE";
                                            $query_run = mysqli_query($conn, $sql3);
                                
                                                if($query_run)
                                                {          
                                                    header("Location: ../../leavereq.php?msg=Approved Successfully");
                                                } 
                                                else
                                                {
                                                    echo '<script> alert("Data Not Updated"); </script>';
                                                }
                                          } 
                                          else
                                          { 
                                            echo '<script> alert("Data Not Updated"); </script>';
                                          }
                                    }
                                  } else
                                  { 
                                    echo '<script> alert("Data Not Updated"); </script>';
                                    echo 'NOT UPDATED';
                                  }
        
                    
        } //-----------------------------------PARA SA PAG MINUS NG CREDITS IF HALDAY firsthalf end----------------------------------------------
        //--------------------------------------------PARA SA PAG MINUS NG CREDITS IF Second HALfDAY-------------------------------------------------
        
        else if($row['col_LeavePeriod'] === 'Second Half'){

            $day_of_week = date('l', strtotime($str_date)); //convert sa date para gawin anong day


            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB)
                $result_empSched = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid`=  $employee_ID");
                $row_empSched = mysqli_fetch_assoc($result_empSched);
                //echo $row_empSched['schedule_name'];
                $empSched_name = $row_empSched['schedule_name'];
 
            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB) (END)


            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule)
                $result_Sched = mysqli_query($conn, " SELECT * FROM `schedule_tb` WHERE `schedule_name`=  '$empSched_name'");
                $row_Sched = mysqli_fetch_assoc($result_Sched);
                //echo $row_Sched['schedule_name'];
            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule) (END)



//para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )
                    // -----------------------BREAK MONDAY START----------------------------//
                    if($day_of_week === 'Monday'){
                        
                        $time_in = $row_Sched['mon_timein'];
                        $time_out = $row_Sched['mon_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Tuesday')
                    {
                        
                        $time_in = $row_Sched['tues_timein'];
                        $time_out = $row_Sched['tues_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Wednesday')
                    {
                        
                        $time_in = $row_Sched['wed_timein'];
                        $time_out = $row_Sched['wed_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Thursday')
                    {
                        
                        $time_in = $row_Sched['thurs_timein'];
                        $time_out = $row_Sched['thurs_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Friday')
                    {
                        
                        $time_in = $row_Sched['fri_timein'];
                        $time_out = $row_Sched['fri_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Saturday')
                    {
                        
                        $time_in = $row_Sched['sat_timein'];
                        $time_out = $row_Sched['sat_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
                    else if ($day_of_week === 'Sunday')
                    {
                        
                        $time_in = $row_Sched['sun_timein'];
                        $time_out = $row_Sched['sun_timeout'];
    
                        $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                        $total_work = date('H:i:s', $total_work);
                    }
//para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )




// ----------------------------------------------------BREAK MODIFY HERE 4-15-2023-----------------------------------------------------
            if($row['col_LeaveType'] == 'Vacation Leave'){
                $minusVacationCredits0 = $row__leaveINFO['col_vctionCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
                $sql_minusvacationCredits0 ="UPDATE leaveinfo_tb SET col_vctionCrdt= $minusVacationCredits0 WHERE col_empID = $employee_ID";
                $query_run_minusCredits0 = mysqli_query($conn, $sql_minusvacationCredits0);
            }
            elseif($row['col_LeaveType'] == 'Bereavement Leave'){
                $minusBrvmntCredits0 = $row__leaveINFO['col_brvmntCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
                $sql_minusBrvmntCredits0 ="UPDATE leaveinfo_tb SET col_brvmntCrdt= $minusBrvmntCredits0 WHERE col_empID = $employee_ID";
                $query_run_BrvmntminusCredits0 = mysqli_query($conn, $sql_minusBrvmntCredits0);
            }
            elseif($row['col_LeaveType'] == 'Sick Leave'){
                $minusSickCredits0 = $row__leaveINFO['col_sickCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
                $sql_minusSickCredits0 ="UPDATE leaveinfo_tb SET col_sickCrdt= $minusSickCredits0 WHERE col_empID = $employee_ID";
                $query_run_SickminusCredits0 = mysqli_query($conn, $sql_minusSickCredits0);
            }
               //pra sa pag update ng action taken at status to approved
        
                    // Get the current date and time
                    $now = new DateTime();
                    $now->setTimezone(new DateTimeZone('Asia/Manila'));
                    $currentDateTime = $now->format('Y-m-d H:i:s');
                    $Status = $_SESSION["col_status"];
                    $reason = $_POST["name_approvedtResn"]; 
                    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
        
                    $sql1 = "INSERT into attendances(`status`, `empid`,`date`, `time_in`, `time_out`, `total_work` ) 
                                VALUES('On-Leave','$employee_ID', '$str_date',  '$time_in', '$time_out', '$total_work')";
                                  if(mysqli_query($conn,$sql1))
                                  {
                                    $result_att = mysqli_query($conn, " SELECT
                                        *  
                                    FROM
                                    `attendances`
                                    WHERE `time_in`=  '00:00:00'");
                                    $row_att = mysqli_fetch_assoc($result_att);

                                    if ($row_att['time_in'] === '00:00:00'){
                                        $sql = "DELETE FROM `attendances` WHERE `time_in` = '00:00:00'";
                                        $result = mysqli_query($conn, $sql);
                                        if ($result) {
                                            header("Location: ../../leavereq.php?error=You cannot approve a request that is on restday");
                                        }
                                        else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }
                                       
                                    }else{
                                        $sql2 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
                                        VALUES('$IDLEAVE_TABLE','$reason', 'Approved')";
                                          if(mysqli_query($conn,$sql2))
                                          {
                                            $sql3 ="UPDATE applyleave_tb SET col_status= 'Approved', col_dt_action= '$currentDateTime', col_approver = '$approver' WHERE col_ID = $IDLEAVE_TABLE";
                                            $query_run = mysqli_query($conn, $sql3);
                                
                                
                                                if($query_run)
                                                {          
                    
                                                
                                                } 
                                                else
                                                {
                                                    echo '<script> alert("Data Not Updated"); </script>';
                                                }
                                          } 
                                          else
                                          { 
                                            echo '<script> alert("Data Not Updated"); </script>';
                                          }
                                    }


                                    

                                  } else
                                  { 
                                    echo '<script> alert("Data Not Updated"); </script>';
                                    echo 'NOT UPDATED';
                                  }
        } //-----------------------------------PARA SA PAG MINUS NG CREDITS IF HALDAY secondhalf end----------------------------------------------
        else{
            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB)
                $result_empSched = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid`=  $employee_ID");
                $row_empSched = mysqli_fetch_assoc($result_empSched);
                //echo $row_empSched['schedule_name'];
                $empSched_name = $row_empSched['schedule_name'];
 
            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB) (END)


            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule)
                $result_Sched = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name`=  '$empSched_name'");
                $row_Sched = mysqli_fetch_assoc($result_Sched);
                //echo $row_Sched['schedule_name'];
            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule) (END)





        //------------------------------------  CODE FOR UPDATING LEAVE REQUEST ACTION DATETIME, STATUS and MINUS LEAVE INFO CRDITS FUllday----------------------------------
            //para sa pag update from pending to approved and action time
                //PARA SA PAG UPDATE NG CREDITS SA APPLY TB
                $date1 = new DateTime($row['col_strDate']);
                $date2 = new DateTime($row['col_endDate']);
               
                $interval = $date1->diff($date2);

                $numberOfDays = $interval->days + 1;
                echo "The number of days between the two dates is: " . $numberOfDays;

                if($row['col_LeaveType'] == 'Vacation Leave'){
                    echo "minus " . $minusVacationCredits = $row__leaveINFO['col_vctionCrdt'] - $numberOfDays; //dito ako naputol
                    $sql_minusvacationCredits ="UPDATE leaveinfo_tb SET col_vctionCrdt= $minusVacationCredits WHERE col_empID = $employee_ID";
                    $query_run_minusCredits = mysqli_query($conn, $sql_minusvacationCredits);
                }
                elseif($row['col_LeaveType'] == 'Bereavement Leave'){
                    echo $minusBrvmntCredits = $row__leaveINFO['col_brvmntCrdt'] - $numberOfDays; //para mag minus sa credits sa IF Vacation
                    $sql_minusBrvmntCredits ="UPDATE leaveinfo_tb SET col_brvmntCrdt= $minusBrvmntCredits WHERE col_empID = $employee_ID";
                    $query_run_BrvmntminusCredits = mysqli_query($conn, $sql_minusBrvmntCredits);
                }
                elseif($row['col_LeaveType'] == 'Sick Leave'){
                    echo $minusSickCredits = $row__leaveINFO['col_sickCrdt'] - $numberOfDays; //para mag minus sa credits sa IF Vacation
                    $sql_minusSickCredits ="UPDATE leaveinfo_tb SET col_sickCrdt= $minusSickCredits WHERE col_empID = $employee_ID";
                    $query_run_SickminusCredits = mysqli_query($conn, $sql_minusSickCredits);
                }
               
               
                 //PARA SA PAG UPDATE NG CREDITS SA APPLY TB (END)
                
            //------------------------------------BREAK----------------------------------
            //pra sa pag update ng action taken at status to approved
        
                    // Get the current date and time
                    $now = new DateTime();
                    $now->setTimezone(new DateTimeZone('Asia/Manila'));
                    $currentDateTime = $now->format('Y-m-d H:i:s');
                    $Status = $_SESSION["col_status"];
                    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
        
                    $reason = $_POST["name_approvedtResn"]; 



                        // Create an array of dates between start date and end date
                        $date_range = array();
                        $current_date = strtotime($row['col_strDate']);
                        $end_date = strtotime($row['col_endDate']);
                        while ($current_date <= $end_date) {
                            $date_range[] = date('Y-m-d', $current_date);
                            $current_date = strtotime('+1 day', $current_date);
                        }

                        // Insert data into database
                        foreach ($date_range as $date) 
                        {

                            $day_of_week = date('l', strtotime($date));//convert the each date to day

                            if($day_of_week === 'Monday'){

                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);

                                    
                                }else{
                                    $time_in = $row_Sched['mon_timein']; 
                                    $time_out = $row_Sched['mon_timeout'];

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                }

                            
                            }
                            
                            if ($day_of_week === 'Tuesday')
                            {
                                
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    
                                }else{
                                    $time_in = $row_Sched['tues_timein']; 
                                    $time_out = $row_Sched['tues_timeout'];

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                }

                            
                            }
                            if ($day_of_week === 'Wednesday')
                            {
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    
                                }else{
                                    $time_in = $row_Sched['wed_timein']; 
                                    $time_out = $row_Sched['wed_timeout'];

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                }
                            
                                
                            
                            }
                            if ($day_of_week === 'Thursday')
                            {
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    
                                }else{
                                    $time_in = $row_Sched['thurs_timein'];
                                    $time_out = $row_Sched['thurs_timeout'];

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                }
                                
                            
                            }
                            if ($day_of_week === 'Friday')
                            {
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    
                                }else{
                                    $time_in = $row_Sched['fri_timein'];
                                    $time_out = $row_Sched['fri_timeout'];

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                }
                                
                            
                            }
                            if ($day_of_week === 'Saturday')
                            {

                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    
                                }else{
                                    $time_in = $row_Sched['sat_timein'];
                                    $time_out = $row_Sched['sat_timeout'];

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                }
                                
                                
                            }
                            if ($day_of_week === 'Sunday')
                            {

                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    
                                }else{
                                    $time_in = $row_Sched['sun_timein'];
                                    $time_out = $row_Sched['sun_timeout'];

                                    $total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = date('H:i:s', $total_work);
                                }
                                
                            
                            }




                            $sql3 = "INSERT INTO attendances (`status`, `empid`,`date`, `time_in`, `time_out`, `total_work`) VALUES (?,?,?,?,?,?)";
                            $stmt = $conn->prepare($sql3);
                            $status = 'On-Leave';
                            // $timein = '08:00';
                            // $timeout = '17:00';
                            // $total = '08:00';
                            $stmt->bind_param("ssssss", $status, $employee_ID, $date,  $time_in, $time_out, $total_work);
                            $result = $stmt->execute();
                            if (!$result) {
                                echo "Error: " . $stmt->error;
                                break;
                            }
                            $stmt->close();
                        }

                        // Check for successful insertion
                        if ($result) {

                            $Not_countLEave = mysqli_query($conn, "SELECT COUNT(*) AS leave_notValid FROM attendances WHERE `time_in` = '11:11:11';");

                            if ($Not_countLEave) {
                            $row_notCounted = mysqli_fetch_assoc($Not_countLEave);
                            
                            
                            

                            $count = $row_notCounted['leave_notValid'];
                            //PARA IBALIK ANG NAWALA NA SCHEDULE IF WALA SIYA SCHEDULE SA ARAW NA ITO
                                if($row['col_LeaveType'] == 'Vacation Leave'){
                                  $updated_credit = $minusVacationCredits + $count;

                                    $sql ="UPDATE leaveinfo_tb SET col_vctionCrdt = $updated_credit WHERE col_empID = $employee_ID";
                                    $query_run = mysqli_query($conn, $sql);
                                }
                                elseif($row['col_LeaveType'] == 'Bereavement Leave'){

                                    $updated_credit = $minusBrvmntCredits + $count;

                                    $sql ="UPDATE leaveinfo_tb SET col_brvmntCrdt = $updated_credit WHERE col_empID = $employee_ID";
                                    $query_run = mysqli_query($conn, $sql);
                                
                                }
                                elseif($row['col_LeaveType'] == 'Sick Leave'){

                                    $updated_credit = $minusSickCredits + $count;

                                    $sql ="UPDATE leaveinfo_tb SET col_sickCrdt = $updated_credit WHERE col_empID = $employee_ID";
                                    $query_run = mysqli_query($conn, $sql);
                                    
                                }
                            //PARA IBALIK ANG NAWALA NA SCHEDULE IF WALA SIYA SCHEDULE SA ARAW NA ITO (END)

                                    $sql = "DELETE FROM `attendances` WHERE `time_in` = '11:11:11'";
                                    $result = mysqli_query($conn, $sql);
                                    if ($result) {

                                        $sql1 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
                                        VALUES('$IDLEAVE_TABLE','$reason', 'Approved')";
                                        if(mysqli_query($conn,$sql1))
                                        {
                                            $sql ="UPDATE applyleave_tb SET col_status= 'Approved', col_dt_action= '$currentDateTime', col_approver = '$approver' WHERE col_ID = $IDLEAVE_TABLE";
                                            $query_run = mysqli_query($conn, $sql);
                                
                                
                                                if($query_run){
                    
                    
                                                    header("Location: ../../leavereq.php?msg=Approved Successfully");
                                                
                                                
                                                }
                                                else{
                                                    echo '<script> alert("Data Not Updated"); </script>';
                                                }
                                        }
                                        else{
                                            echo '<script> alert("Data Not Updated"); </script>';
                                        }


                                        
                                    }
                                    else {
                                        echo "Failed: " . mysqli_error($conn);
                                    } //end delete
                                     
                            } //end count
                            else {
                            // Handle the query error
                            echo "Error executing the query: " . mysqli_error($conn);
                            }
                                   

                        
                        }else{
                            echo "not inserted";
                        }





        
                    
                   
        //------------------------------------CODE FOR UPDATING LEAVE REQUEST ACTION DATETIME, STATUS and MINUS LEAVE INFO CRDITS END----------------------------------
        }


    }else{ //para sa pag approved ng request if WITHOUT PAY (hindi mababawasan ang leave credits)
         //pra sa pag update ng action taken at status to approved
        //  $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
        
        //  // Get the current date and time
        //  $now = new DateTime();
        //  $now->setTimezone(new DateTimeZone('Asia/Manila'));
        //  $currentDateTime = $now->format('Y-m-d H:i:s');
        //  $Status = $_SESSION["col_status"];
        //  $reason = $_POST["name_approvedtResn"]; 

         

        //  $sql1 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
        //  VALUES('$IDLEAVE_TABLE','$reason', 'Approved')";
        //    if(mysqli_query($conn,$sql1))
        //    {
        //      $sql2 ="UPDATE applyleave_tb SET col_status= 'Approved', col_dt_action= '$currentDateTime', col_approver = '$approver' WHERE col_ID = $IDLEAVE_TABLE";
        //      $query_run = mysqli_query($conn, $sql2);
 
 
        //          if($query_run){
        //              header("Location: ../../leavereq.php?msg=Approved Successfully");
        //          }
        //          else{
        //              echo '<script> alert("Data Not Updated"); </script>';
        //          }

        //    }
        //    else{
        //      echo '<script> alert("Data Not Updated"); </script>';
        //    }



        if($row['col_LeavePeriod'] === 'First Half'){
            $day_of_week = date('l', strtotime($str_date)); //convert sa date para gawin anong day


            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB)
                $result_empSched = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid`=  $employee_ID");
                $row_empSched = mysqli_fetch_assoc($result_empSched);
                //echo $row_empSched['schedule_name'];
                $empSched_name = $row_empSched['schedule_name'];
 
            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB) (END)


            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule)
                $result_Sched = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name`=  '$empSched_name'");
                $row_Sched = mysqli_fetch_assoc($result_Sched);
                //echo $row_Sched['schedule_name'];
               


            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule) (END)



//para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )
                    // -----------------------BREAK MONDAY START----------------------------//
                    if($day_of_week === 'Monday'){

                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            //$total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    
                    }
                    
                    if ($day_of_week === 'Tuesday')
                    {
                        
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    
                    }
                    if ($day_of_week === 'Wednesday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }
                    
                        
                    
                    }
                    if ($day_of_week === 'Thursday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }
                        
                    
                    }
                    if ($day_of_week === 'Friday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    }
                    if ($day_of_week === 'Saturday')
                    {

                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    }
                    if ($day_of_week === 'Sunday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                             $total_work = '00:00:00';
                        }

                    }
                

//para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )




// ----------------------------------------------------BREAK MODIFY HERE 4-15-2023-----------------------------------------------------






            // if($row['col_LeaveType'] == 'Vacation Leave'){
            //     $minusVacationCredits0 = $row__leaveINFO['col_vctionCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
            //     $sql_minusvacationCredits0 ="UPDATE leaveinfo_tb SET col_vctionCrdt= $minusVacationCredits0 WHERE col_empID = $employee_ID";
            //     $query_run_minusCredits0 = mysqli_query($conn, $sql_minusvacationCredits0);
            // }
            // elseif($row['col_LeaveType'] == 'Bereavement Leave'){
            //     $minusBrvmntCredits0 = $row__leaveINFO['col_brvmntCrdt'] - 0.5; //para mag minus sa credits sa IF Bereavement
            //     $sql_minusBrvmntCredits0 ="UPDATE leaveinfo_tb SET col_brvmntCrdt= $minusBrvmntCredits0 WHERE col_empID = $employee_ID";
            //     $query_run_BrvmntminusCredits0 = mysqli_query($conn, $sql_minusBrvmntCredits0);
            // }
            // elseif($row['col_LeaveType'] == 'Sick Leave'){
            //     $minusSickCredits0 = $row__leaveINFO['col_sickCrdt'] - 0.5; //para mag minus sa credits sa IF Sick
            //     $sql_minusSickCredits0 ="UPDATE leaveinfo_tb SET col_sickCrdt= $minusSickCredits0 WHERE col_empID = $employee_ID";
            //     $query_run_SickminusCredits0 = mysqli_query($conn, $sql_minusSickCredits0);
            // }
        
        
               //pra sa pag update ng action taken at status to approved
        
                    // Get the current date and time
                    $now = new DateTime();
                    $now->setTimezone(new DateTimeZone('Asia/Manila'));
                    $currentDateTime = $now->format('Y-m-d H:i:s');
                    $Status = $_SESSION["col_status"];
                    $reason = $_POST["name_approvedtResn"]; 
                    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];


                    $sql1 = "INSERT into attendances(`status`, `empid`,`date`, `time_in`, `time_out`, `total_work` ) 
                                VALUES('LWOP','$employee_ID', '$str_date',  '$time_in', '$time_out', '$total_work')";
                                  if(mysqli_query($conn,$sql1))
                                  {
                                    $result_att = mysqli_query($conn, " SELECT
                                        *  
                                    FROM
                                    `attendances`
                                    WHERE `time_in`=  '11:11:11'");
                                    $row_att = mysqli_fetch_assoc($result_att);

                                    if ($row_att['time_in'] === '11:11:11'){
                                        $sql = "DELETE FROM `attendances` WHERE `time_in` = '11:11:11'";
                                        $result = mysqli_query($conn, $sql);
                                        if ($result) {
                                            header("Location: ../../leavereq.php?error=You cannot approve a request that is on restday");
                                        }
                                        else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }


                                       
                                    }else{
                                        $sql2 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
                                        VALUES('$IDLEAVE_TABLE','$reason', 'Approved')";
                                          if(mysqli_query($conn,$sql2))
                                          {
                                            $sql3 ="UPDATE applyleave_tb SET col_status= 'Approved', col_dt_action= '$currentDateTime', col_approver = '$approver' WHERE col_ID = $IDLEAVE_TABLE";
                                            $query_run = mysqli_query($conn, $sql3);
                                
                                
                                                if($query_run)
                                                {          
                    
                                                    header("Location: ../../leavereq.php?msg=Approved Successfully");
                                                       
                                                    
                                                } 
                                                else
                                                {
                                                    echo '<script> alert("Data Not Updated"); </script>';
                                                }
                            
                                          } 
                                          else
                                          { 
                                            echo '<script> alert("Data Not Updated"); </script>';
                                          }
                                    }


                                    

                                  } else
                                  { 
                                    echo '<script> alert("Data Not Updated"); </script>';
                                    echo 'NOT UPDATED';
                                  }
        
                    
        } //-----------------------------------PARA SA PAG MINUS NG CREDITS IF HALDAY firsthalf end----------------------------------------------
        else if($row['col_LeavePeriod'] === 'Second Half'){

            $day_of_week = date('l', strtotime($str_date)); //convert sa date para gawin anong day


            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB)
                $result_empSched = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid`=  $employee_ID");
                $row_empSched = mysqli_fetch_assoc($result_empSched);
                //echo $row_empSched['schedule_name'];
                $empSched_name = $row_empSched['schedule_name'];
 
            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB) (END)


            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule)
                $result_Sched = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name`=  '$empSched_name'");
                $row_Sched = mysqli_fetch_assoc($result_Sched);
                //echo $row_Sched['schedule_name'];
            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule) (END)



//para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )
                    // -----------------------BREAK MONDAY START----------------------------//
                    if($day_of_week === 'Monday'){

                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            //$total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    
                    }
                    
                    if ($day_of_week === 'Tuesday')
                    {
                        
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    
                    }
                    if ($day_of_week === 'Wednesday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }
                    
                        
                    
                    }
                    if ($day_of_week === 'Thursday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }
                        
                    
                    }
                    if ($day_of_week === 'Friday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    }
                    if ($day_of_week === 'Saturday')
                    {

                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                            $total_work = '00:00:00';
                        }

                    }
                    if ($day_of_week === 'Sunday')
                    {
                        error_reporting(E_ERROR | E_PARSE);
                        if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timeout'] == NULL){
                            
                            $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                            $time_out = '11:11:11';

                            $total_work = '00:00:00';

                            
                        }else{
                            $time_in = '00:00:00';
                            $time_out = '00:00:00';

                             $total_work = '00:00:00';
                        }

                    }
                

//para sa pag kuha ng time in at time out ng schedule ng employee (PAra ma insert sa pag leave )




// ----------------------------------------------------BREAK MODIFY HERE 4-15-2023-----------------------------------------------------





            // if($row['col_LeaveType'] == 'Vacation Leave'){
            //     $minusVacationCredits0 = $row__leaveINFO['col_vctionCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
            //     $sql_minusvacationCredits0 ="UPDATE leaveinfo_tb SET col_vctionCrdt= $minusVacationCredits0 WHERE col_empID = $employee_ID";
            //     $query_run_minusCredits0 = mysqli_query($conn, $sql_minusvacationCredits0);
            // }
            // elseif($row['col_LeaveType'] == 'Bereavement Leave'){
            //     $minusBrvmntCredits0 = $row__leaveINFO['col_brvmntCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
            //     $sql_minusBrvmntCredits0 ="UPDATE leaveinfo_tb SET col_brvmntCrdt= $minusBrvmntCredits0 WHERE col_empID = $employee_ID";
            //     $query_run_BrvmntminusCredits0 = mysqli_query($conn, $sql_minusBrvmntCredits0);
            // }
            // elseif($row['col_LeaveType'] == 'Sick Leave'){
            //     $minusSickCredits0 = $row__leaveINFO['col_sickCrdt'] - 0.5; //para mag minus sa credits sa IF Vacation
            //     $sql_minusSickCredits0 ="UPDATE leaveinfo_tb SET col_sickCrdt= $minusSickCredits0 WHERE col_empID = $employee_ID";
            //     $query_run_SickminusCredits0 = mysqli_query($conn, $sql_minusSickCredits0);
            // }
        
        
               //pra sa pag update ng action taken at status to approved
        
                    // Get the current date and time
                    $now = new DateTime();
                    $now->setTimezone(new DateTimeZone('Asia/Manila'));
                    $currentDateTime = $now->format('Y-m-d H:i:s');
                    $Status = $_SESSION["col_status"];
                    $reason = $_POST["name_approvedtResn"]; 
                    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
        
                    $sql1 = "INSERT into attendances(`status`, `empid`,`date`, `time_in`, `time_out`, `total_work` ) 
                                VALUES('LWOP','$employee_ID', '$str_date',  '$time_in', '$time_out', '$total_work')";
                                  if(mysqli_query($conn,$sql1))
                                  {
                                    $result_att = mysqli_query($conn, " SELECT
                                        *  
                                    FROM
                                    `attendances`
                                    WHERE `time_in`=  '11:11:11'");
                                    $row_att = mysqli_fetch_assoc($result_att);

                                    if ($row_att['time_in'] === '11:11:11'){
                                        $sql = "DELETE FROM `attendances` WHERE `time_in` = '11:11:11'";
                                        $result = mysqli_query($conn, $sql);
                                        if ($result) {
                                            header("Location: ../../leavereq.php?error=You cannot approve a request that is on restday");
                                        }
                                        else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }


                                       
                                    }else{
                                        $sql2 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
                                        VALUES('$IDLEAVE_TABLE','$reason', 'Approved')";
                                          if(mysqli_query($conn,$sql2))
                                          {
                                            $sql3 ="UPDATE applyleave_tb SET col_status= 'Approved', col_dt_action= '$currentDateTime', col_approver = '$approver' WHERE col_ID = $IDLEAVE_TABLE";
                                            $query_run = mysqli_query($conn, $sql3);
                                
                                
                                                if($query_run)
                                                {          
                    
                                                    header("Location: ../../leavereq.php?msg=Approved Successfully");
                                                        
                                                    
                                                } 
                                                else
                                                {
                                                    echo '<script> alert("Data Not Updated"); </script>';
                                                }
                            
                                          } 
                                          else
                                          { 
                                            echo '<script> alert("Data Not Updated"); </script>';
                                          }
                                    }


                                    

                                  } else
                                  { 
                                    echo '<script> alert("Data Not Updated"); </script>';
                                    echo 'NOT UPDATED';
                                  }
        } //-----------------------------------PARA SA PAG MINUS NG CREDITS IF HALDAY secondhalf end----------------------------------------------
         else{ 

         //--------------------------------FULLDAY WITHOUT PAY------------------------------------


            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB)
                $result_empSched = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid`=  $employee_ID");
                $row_empSched = mysqli_fetch_assoc($result_empSched);
                //echo $row_empSched['schedule_name'];
                $empSched_name = $row_empSched['schedule_name'];
 
            //Para sa pag select ng mga data galing sa empSched TABLE (Para kunin ang data na nasa schedule TB) (END)


            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule)
                $result_Sched = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name`=  '$empSched_name'");
                $row_Sched = mysqli_fetch_assoc($result_Sched);
                //echo $row_Sched['schedule_name'];
            //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule) (END)

            //------------------------------------BREAK----------------------------------
            //pra sa pag update ng action taken at status to approved
        
                    // Get the current date and time
                    $now = new DateTime();
                    $now->setTimezone(new DateTimeZone('Asia/Manila'));
                    $currentDateTime = $now->format('Y-m-d H:i:s');
                    $Status = $_SESSION["col_status"];
                    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
        
                    $reason = $_POST["name_approvedtResn"]; 



                        // Create an array of dates between start date and end date
                        $date_range = array();
                        $current_date = strtotime($row['col_strDate']);
                        $end_date = strtotime($row['col_endDate']);
                        while ($current_date <= $end_date) {
                            $date_range[] = date('Y-m-d', $current_date);
                            $current_date = strtotime('+1 day', $current_date);
                        }

                        // Insert data into database
                        foreach ($date_range as $date) 
                        {

                            $day_of_week = date('l', strtotime($date));//convert the each date to day

                            if($day_of_week === 'Monday'){

                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    //$total_work = strtotime($time_out) - strtotime($time_in) - 7200;
                                    $total_work = '00:00:00';

                                    
                                }else{
                                    $time_in = '00:00:00';
                                    $time_out = '00:00:00';

                                    $total_work = '00:00:00';
                                }

                            
                            }
                            
                            if ($day_of_week === 'Tuesday')
                            {
                                
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    $total_work = '00:00:00';

                                    
                                }else{
                                    $time_in = '00:00:00';
                                    $time_out = '00:00:00';

                                    $total_work = '00:00:00';
                                }

                            
                            }
                            if ($day_of_week === 'Wednesday')
                            {
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    $total_work = '00:00:00';

                                    
                                }else{
                                    $time_in = '00:00:00';
                                    $time_out = '00:00:00';

                                    $total_work = '00:00:00';
                                }
                            
                                
                            
                            }
                            if ($day_of_week === 'Thursday')
                            {
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    $total_work = '00:00:00';

                                    
                                }else{
                                    $time_in = '00:00:00';
                                    $time_out = '00:00:00';

                                    $total_work = '00:00:00';
                                }
                                
                            
                            }
                            if ($day_of_week === 'Friday')
                            {
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    $total_work = '00:00:00';

                                    
                                }else{
                                    $time_in = '00:00:00';
                                    $time_out = '00:00:00';

                                    $total_work = '00:00:00';
                                }

                            }
                            if ($day_of_week === 'Saturday')
                            {

                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    $total_work = '00:00:00';

                                    
                                }else{
                                    $time_in = '00:00:00';
                                    $time_out = '00:00:00';

                                    $total_work = '00:00:00';
                                }
  
                            }
                            if ($day_of_week === 'Sunday')
                            {
                                error_reporting(E_ERROR | E_PARSE);
                                if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timeout'] == NULL){
                                    
                                    $time_in = '11:11:11'; //para sa where clause if NULL ang schedule sa araw na ito ay e delete niya sa baba
                                    $time_out = '11:11:11';

                                    $total_work = '00:00:00';

                                    
                                }else{
                                    $time_in = '00:00:00';
                                    $time_out = '00:00:00';

                                     $total_work = '00:00:00';
                                }

                            }

                            $sql3 = "INSERT INTO attendances (`status`, `empid`,`date`, `time_in`, `time_out`, `total_work`) VALUES (?,?,?,?,?,?)";
                            $stmt = $conn->prepare($sql3);
                            $status = 'LWOP';
                            // $timein = '08:00';
                            // $timeout = '17:00';
                            // $total = '08:00';
                            $stmt->bind_param("ssssss", $status, $employee_ID, $date,  $time_in, $time_out, $total_work);
                            $result = $stmt->execute();
                            if (!$result) {
                                echo "Error: " . $stmt->error;
                                break;
                            }
                            $stmt->close();
                        }

                        // Check for successful insertion
                        if ($result) {

                            $sql = "DELETE FROM `attendances` WHERE `time_in` = '11:11:11'";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {

                                $sql1 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
                                VALUES('$IDLEAVE_TABLE','$reason', 'Approved')";
                                  if(mysqli_query($conn,$sql1))
                                  {
                                    $sql ="UPDATE applyleave_tb SET col_status= 'Approved', col_dt_action= '$currentDateTime', col_approver = '$approver' WHERE col_ID = $IDLEAVE_TABLE";
                                    $query_run = mysqli_query($conn, $sql);
                        
                        
                                        if($query_run){
            
            
                                            header("Location: ../../leavereq.php?msg=Approved Successfully");
                                                    
                                           
                                        }
                                        else{
                                            echo '<script> alert("Data Not Updated"); </script>';
                                        }
                                  }
                                  else{
                                    echo '<script> alert("Data Not Updated"); </script>';
                                  }


                                
                            }
                            else {
                                echo "Failed: " . mysqli_error($conn);
                            }
                        
                        }else{
                            echo "not inserted";
                        }
        //------------------------------------CODE FOR UPDATING LEAVE REQUEST ACTION DATETIME, STATUS and MINUS LEAVE INFO CRDITS END----------------------------------
     }
   } // end without pay
 }
}    
//----------------------------------------------------------------------------BREAK(FOR Approving END)-----------------------------------------------------
/*
    //----------------------------------------------BREAK(FOR REJECTING)-----------------------------------------------------
    if(isset($_POST['name_rejected'])){
        $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
//Para sa pag select ng mga data galing sa APPLYLEAVE TABLE
        $result = mysqli_query($conn, " SELECT
                                            *  
                                        FROM
                                            applyleave_tb
                                        WHERE col_ID=  $IDLEAVE_TABLE");
        $row = mysqli_fetch_assoc($result);
        //echo $row['col_LeaveType'];
//Para sa pag select ng mga data galing sa APPLYLEAVE TABLE (END)

if($row['col_status'] === 'Approved' ){
    header("Location: leavereq.php?msg=You cannot REJECT a request that is already APPROVED");
}
else if($row['col_status'] === 'Rejected' ){
    header("Location: leavereq.php?msg=You cannot REJECT a request that is already REJECTED");
}
else{


        //para sa pag update from pending to approved and action time
          // Get the current date and time
          $now = new DateTime();
          $now->setTimezone(new DateTimeZone('Asia/Manila'));
          $currentDateTime1 = $now->format('Y-m-d H:i:s');

          //get the session for ID in applyleave selected employee
          $Status = $_SESSION["col_status"];
          $Applyleave_ID = $_SESSION["ID_applyleave"];

          $sql ="UPDATE applyleave_tb SET  col_status= 'Rejected', col_dt_action= '$currentDateTime1' WHERE col_ID = $Applyleave_ID";
          $query_run = mysqli_query($conn, $sql);


          if($query_run){
              header("Location: leavereq.php?msg=Rejected Successfully");
          }
          else{
              echo '<script> alert("Data Not Updated"); </script>';
          }
}

    
    }

    */
?>