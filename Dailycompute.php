<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php"); 
} else {
    // Check if the user's role is not "admin"
    if($_SESSION['role'] != 'admin'){
        // If the user's role is not "admin", log them out and redirect to the logout page
        session_unset();
        session_destroy();
        header("Location: logout.php");
        exit();
    }else {
        include 'config.php';
        include 'user-image.php';
    }
}

include_once 'config.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">

        <!-- skydash -->

    <link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   

    <link rel="stylesheet" href="css/try.css">


    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Daily Report</title>
</head>
<body>
<header>
    <?php
        include 'header.php';
    ?>
</header>
<!-- Modal -->
<div class="modal fade" id="detailedPayslip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div class="table-responsive" id="table-responsiveness">
                <table class="table">
                  <thead>
                     <tr>
                        <th style="display: none;">Daily Rate</th>
                        <th style="display: none;">Hourly Rate</th>
                        <th>Overtime</th>
                        <th>Overtime Pay</th>
                        <th>Holiday Pay</th>
                        <th>Holiday OT Pay</th>
                        <th>Leave Pay</th>
                        <th>Number of Absence</th>
                        <th>Absence Deduction</th>
                        <th>Late Time</th>  
                        <th>Late Deduction</th>
                     </tr> 
                  </thead>
                     <tr>
                        <td style='font-weight: 400; display: none;' id="dailyrate"></td>
                        <td style='font-weight: 400; display: none;' id="hourlyrate"></td>
                        <td style='font-weight: 400' id="othour"></td>
                        <td style='font-weight: 400' id="otpay"></td>
                        <td style='font-weight: 400' id="holidayPay"></td>
                        <td style='font-weight: 400' id="holidayOTPay"></td>
                        <td style='font-weight: 400' id="LeavePay"></td>
                        <td style='font-weight: 400; color: red;' id="absences"></td>
                        <td style='font-weight: 400; color: red;' id="deductAbsences"></td>
                        <td style='font-weight: 400; color: red;' id="latehour"></td>
                        <td style='font-weight: 400; color: red;' id="latededuct"></td>
                     </tr>
                </table>
            </div>  
      </div>
    </div>
  </div>
</div>



    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">
                             <div class="row">
                                <div class="col-6">
                                    <p style="font-size: 25px; padding: 10px">Daily Computation</p>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                                <!-- <button type="button" class="add_off_btn" data-bs-toggle="modal" data-bs-target="#file_off_btn">
                                    File Official Business
                                    </button> -->
                                </div>
                            </div>

                
                            <div class="table-responsive" id="table-responsiveness">
                              <table id="order-listing" class="table mt-2">
                                  <thead>
                                      <tr>  
                                        <th style="display: none;">ID</th>                                        
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Salary Rate</th>
                                        <th style="display: none;">Hourly Rate</th>
                                        <th>Actual Working Days</th>
                                        <th style="display: none;">Overtime Hour</th>
                                        <th style="display: none;">Overtime Minute</th>
                                        <th style="display: none;">Overtime Total</th>
                                        <th style="display: none;">Overtime Pay</th>
                                        <th style="display: none;">Holiday Pay</th>
                                        <th style="display: none;">Holiday OT Pay</th>
                                        <th style="display: none;">Leave Pay</th>
                                        <th style="display: none;">Salary</th>
                                        <th style="display: none;">Number of Absence</th>
                                        <th style="display: none;">Absence Deduction</th>
                                        <th style="display: none;">Late Time</th>  
                                        <th style="display: none;">Late Deduction</th>
                                        <th style="display: none;">Salary Deduct</th>
                                        <th>Salary Final Total</th>
                                        <th>Action</th>
                                      </tr>
                                  </thead>
                                  <?php
                                  include 'config.php';
                                  date_default_timezone_set('Asia/Manila'); 

                                  $currentYear = date('Y');
                                  $currentMonth = date('m');
                                  $end_date = date('Y-m-d'); // Current day in Manila, Philippines

                                  $EmployeeQuery = "SELECT * FROM employee_tb";
                                  $EmployeeResult = mysqli_query($conn, $EmployeeQuery);

                                  while ($employeeRow = $EmployeeResult->fetch_assoc()) {
                                      $employeeId = $employeeRow['empid'];

                                      $CutoffQuery = "SELECT * FROM cutoff_tb";
                                      $CutoffResult = mysqli_query($conn, $CutoffQuery);
                                      $cutoffrow = $CutoffResult->fetch_assoc();
                                      $endDate = $cutoffrow['col_endDate'];
                                      
                                      $str_date = date('Y-m-d', strtotime('+1 day', strtotime($endDate))); //+1 day sa existing na end date sa cutoff para magjump ng another day

                                      $attendanceQuery = "SELECT * FROM attendances WHERE (`status` = 'Present' OR `status` = 'On-Leave') AND `empid` = '$employeeId' AND `date` BETWEEN '$str_date' AND '$end_date'";
                                      $attendanceResult = mysqli_query($conn, $attendanceQuery);

                                      $attendanceRow = $attendanceResult->fetch_assoc();
                                      if ($attendanceRow) {
                                          $EmployeeID = $attendanceRow['empid'];

                                          $cutcheck = mysqli_query($conn, "SELECT * FROM cutoff_tb WHERE '$end_date' BETWEEN `col_startDate` AND `col_endDate`");
                                          if ($cutcheck->num_rows > 0) {


                                          } else {
                                            $cutoffNumber = $cutoffrow['col_cutOffNum'];
                                            $Frequency = $cutoffrow['col_frequency'];
                                            $query_settings_salary = "SELECT * FROM settings_company_tb";
                                            $result_settings_salary = mysqli_query($conn, $query_settings_salary);
                                    
                                            $row_settings_salary = mysqli_fetch_assoc($result_settings_salary);
                                            $sql_empSched = mysqli_query($conn, "SELECT *  FROM empschedule_tb WHERE `empid` = '$EmployeeID'");
                                    
                                            if(mysqli_num_rows($sql_empSched) > 0) {
                                                $row_empSched = mysqli_fetch_assoc($sql_empSched);
                                                $schedule_name = $row_empSched['schedule_name'];
                                    
                                                $sql_sched = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedule_name'");
                                                if(mysqli_num_rows($sql_sched) > 0) {
                                                $row_Sched = mysqli_fetch_assoc($sql_sched);
                                                } else {
                                                echo "No results found schedule.";
                                                } 
                                            } else {
                                                echo "No results found.";
                                            } 

                                            // -----------------------SCHED MONDAY START----------------------------//
                                            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){                         
                                                $MON_timeIN = '00:00:00';
                                                $MON_timeOUT = '01:00:00';
                                                
                                                $MOn_total_work = strtotime($MON_timeOUT) - strtotime($MON_timeIN) - 7200;
                                                $MOn_total_work = date('H:i:s', $MOn_total_work);
                                                //echo " MON_NULL " .  $MOn_total_work;
                                            }else{
                                                $MON_timeIN = $row_Sched['mon_timein'];
                                                $MON_timeOUT = $row_Sched['mon_timeout'];

                                                // Create a DateTime object from the string
                                                $mon_timeIN_object = DateTime::createFromFormat('H:i', $MON_timeIN);
                                                $mon_timeIN_formatted = $mon_timeIN_object->format('H:i'); 
                                                list($mon_hours, $mon_minutes) = explode(':', $mon_timeIN_formatted);

                                                // Convert hours and minutes to total minutes
                                                $mon_total_minutes_timein = $mon_hours + $mon_minutes;

                                                $mon_timeout_object = DateTime::createFromFormat('H:i', $MON_timeOUT);
                                                $mon_timeout_formatted = $mon_timeout_object->format('H:i'); 
                                                list($mon_hourss, $mon_minutess) = explode(':', $mon_timeout_formatted);

                                                // Convert hours and minutes to total minutes
                                                $mon_total_minutes_timeout = $mon_hourss + $mon_minutess;

                                                $mon_total_minutes_timein = intval($mon_total_minutes_timein);
                                                $mon_total_minutes_timeout = intval($mon_total_minutes_timeout);
                                                
                                                if($mon_total_minutes_timeout > $mon_total_minutes_timein){
                                                    $MOn_total_work = ($mon_total_minutes_timeout - $mon_total_minutes_timein) - 1;
                                                }else{
                                                    $MOn_total_work = ($mon_total_minutes_timein - $mon_total_minutes_timeout) - 1;
                                                }
                                            }
                                            // echo $MOn_total_work;
                                            // -----------------------SCHED MONDAY END----------------------------//

                                            // -----------------------BREAK Tuesday START----------------------------//
                                            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                $tue_timeIN = '00:00:00';
                                                $tue_timeout = '01:00:00';
                                                
                                                $Tue_total_work = strtotime($tue_timeout) - strtotime($tue_timeIN) - 7200;
                                                $Tue_total_work = date('H:i:s', $Tue_total_work);
                                            }else{
                                                    $tue_timeIN = $row_Sched['tues_timein'];
                                                    $tue_timeout = $row_Sched['tues_timeout'];
                                                    
                                                    $tue_timeIN_object = DateTime::createFromFormat('H:i', $tue_timeIN);
                                                    $tue_timeIN_formatted = $tue_timeIN_object->format('H:i'); 
                                                    list($tue_hours, $tue_minutes) = explode(':', $tue_timeIN_formatted);

                                                    // Convert hours and minutes to total minutes
                                                    $tue_total_minutes_timein = $tue_hours + $tue_minutes;

                                                    $tue_timeout_object = DateTime::createFromFormat('H:i', $tue_timeout);
                                                    $tue_timeout_formatted = $tue_timeout_object->format('H:i'); 
                                                    list($tue_hourss, $tue_minutess) = explode(':', $tue_timeout_formatted);

                                                    // Convert hours and minutes to total minutes
                                                    $tue_total_minutes_timeout = $tue_hourss + $tue_minutess;

                                                    $tue_total_minutes_timein = intval($tue_total_minutes_timein);
                                                    $tue_total_minutes_timeout = intval($tue_total_minutes_timeout);

                                                    if($tue_total_minutes_timeout > $tue_total_minutes_timein){
                                                        $Tue_total_work = ($tue_total_minutes_timeout - $tue_total_minutes_timein) - 1;
                                                    }else{
                                                        $Tue_total_work = ($tue_total_minutes_timein - $tue_total_minutes_timeout) - 1;
                                                    }
                                            }
                                            // echo $Tue_total_work;
                                            // -----------------------SCHED Tuesday END----------------------------//

                                            // -----------------------BREAK WEDNESDAY START----------------------------//            
                                            if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                                                $wed_timeIN = '00:00:00';
                                                $wed_timeout = '01:00:00';
                                                
                                                $wed_total_work = strtotime($wed_timeout) - strtotime($wed_timeIN) - 7200;
                                                $wed_total_work = date('H:i:s', $wed_total_work);
                                            }else{
                                                $wed_timeIN = $row_Sched['wed_timein'];
                                                $wed_timeout = $row_Sched['wed_timeout'];

                                                $weds_timeIN_object = DateTime::createFromFormat('H:i', $wed_timeIN);
                                                $weds_timeIN_formatted = $weds_timeIN_object->format('H:i'); 
                                                list($weds_hours, $weds_minutes) = explode(':', $weds_timeIN_formatted);

                                                // Convert hours and minutes to total minutes
                                                $weds_total_minutes_timein = $weds_hours + $weds_minutes;

                                                $weds_timeout_object = DateTime::createFromFormat('H:i', $wed_timeout);
                                                $weds_timeout_formatted = $weds_timeout_object->format('H:i'); 
                                                list($weds_hourss, $weds_minutess) = explode(':', $weds_timeout_formatted);

                                                // Convert hours and minutes to total minutes
                                                $weds_total_minutes_timeout = $weds_hourss + $weds_minutess;

                                                $weds_total_minutes_timein = intval($weds_total_minutes_timein);
                                                $weds_total_minutes_timeout = intval($weds_total_minutes_timeout);

                                                $wed_total_work = ($weds_total_minutes_timeout - $weds_total_minutes_timein) - 1;

                                                if($weds_total_minutes_timeout > $weds_total_minutes_timein){
                                                    $wed_total_work = ($weds_total_minutes_timeout - $weds_total_minutes_timein) - 1;
                                                }else{
                                                    $wed_total_work = ($weds_total_minutes_timein - $weds_total_minutes_timeout) - 1;
                                                }
                                            }
                                            // echo $wed_total_work;
                                            // -----------------------SCHED WEDNESDAY END----------------------------//

                                            // -----------------------BREAK THURSDAY START----------------------------//
                                            if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                                       
                                                $thurs_timeIN = '00:00:00';
                                                $thurs_timeout = '01:00:00';
                                                
                                                $thurs_total_work = strtotime($thurs_timeout) - strtotime($thurs_timeIN) - 7200;
                                                $thurs_total_work = date('H:i:s', $thurs_total_work);                                         
                                            }else{
                                                $thurs_timeIN = $row_Sched['thurs_timein'];
                                                $thurs_timeout = $row_Sched['thurs_timeout'];
                                                
                                                // Create a DateTime object from the string
                                                $thurs_timeIN_object = DateTime::createFromFormat('H:i', $thurs_timeIN);
                                                $thurs_timeIN_formatted = $thurs_timeIN_object->format('H:i'); 
                                                list($thurs_hours, $thurs_minutes) = explode(':', $thurs_timeIN_formatted);

                                                // Convert hours and minutes to total minutes
                                                $thurs_total_minutes_timein = $thurs_hours + $thurs_minutes;


                                                $thurs_timeout_object = DateTime::createFromFormat('H:i', $thurs_timeout);
                                                $thurs_timeout_formatted = $thurs_timeout_object->format('H:i'); 
                                                list($thurs_hourss, $thurs_minutess) = explode(':', $thurs_timeout_formatted);

                                                // Convert hours and minutes to total minutes
                                                $thurs_total_minutes_timeout = $thurs_hourss + $thurs_minutess;

                                                $thurs_total_minutes_timein = intval($thurs_total_minutes_timein);
                                                $thurs_total_minutes_timeout = intval($thurs_total_minutes_timeout);

                                                if($thurs_total_minutes_timeout > $thurs_total_minutes_timein){
                                                    $thurs_total_work = ($thurs_total_minutes_timeout - $thurs_total_minutes_timein) - 1;
                                                }else{
                                                    $thurs_total_work = ($thurs_total_minutes_timein - $thurs_total_minutes_timeout) - 1;
                                                }      
                                            }
                                            // echo $thurs_total_work;
                                            // -----------------------SCHED THURSDAY END----------------------------//

                                            // -----------------------BREAK FRIDAY START----------------------------//                                                        
                                            if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){                                                                      
                                                $fri_timeIN = '00:00:00';
                                                $fri_timeout = '01:00:00';
                                                
                                                $fri_total_work = strtotime($fri_timeout) - strtotime($fri_timeIN) - 7200;
                                                $fri_total_work = date('H:i:s', $fri_total_work);                                      
                                            }else{
                                                $fri_timeIN = $row_Sched['fri_timein'];
                                                $fri_timeout = $row_Sched['fri_timeout'];

                                                // Create a DateTime object from the string
                                                $fri_timeIN_object = DateTime::createFromFormat('H:i', $fri_timeIN);
                                                $fri_timeIN_formatted = $fri_timeIN_object->format('H:i'); 
                                                list($fri_hours, $fri_minutes) = explode(':', $fri_timeIN_formatted);

                                                // Convert hours and minutes to total minutes
                                                $fri_total_minutes_timein = $fri_hours + $fri_minutes;


                                                $fri_timeout_object = DateTime::createFromFormat('H:i', $fri_timeout);
                                                $fri_timeout_formatted = $fri_timeout_object->format('H:i'); 
                                                list($fri_hourss, $fri_minutess) = explode(':', $fri_timeout_formatted);

                                                // Convert hours and minutes to total minutes
                                                $fri_total_minutes_timeout = $fri_hourss + $fri_minutess;

                                                $fri_total_minutes_timein = intval($fri_total_minutes_timein);
                                                $fri_total_minutes_timeout = intval($fri_total_minutes_timeout);

                                                    if($fri_total_minutes_timeout > $fri_total_minutes_timein){
                                                    $fri_total_work = ($fri_total_minutes_timeout - $fri_total_minutes_timein) - 1;
                                                }else{
                                                    $fri_total_work = ($fri_total_minutes_timein - $fri_total_minutes_timeout) - 1;
                                                }               
                                            }
                                            // echo $fri_total_work;
                                            // -----------------------SCHED FRIDAY END----------------------------//

                                            // -----------------------BREAK Saturday START----------------------------//
                                            if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){                                                                                                          
                                                $sat_timeIN = '00:00:00';
                                                $sat_timeout = '01:00:00';
                                                
                                                $sat_total_work = strtotime($sat_timeout) - strtotime($sat_timeIN) - 7200;
                                                $sat_total_work = date('H:i:s', $sat_total_work);

                                            }else{                                          
                                                $sat_timeIN = $row_Sched['sat_timein'];
                                                $sat_timeout = $row_Sched['sat_timeout'];

                                                // Create a DateTime object from the string
                                                $sat_timeIN_object = DateTime::createFromFormat('H:i', $sat_timeIN);
                                                $sat_timeIN_formatted = $sat_timeIN_object->format('H:i'); 
                                                list($sat_hours, $sat_minutes) = explode(':', $sat_timeIN_formatted);

                                                // Convert hours and minutes to total minutes
                                                $sat_total_minutes_timein = $sat_hours + $sat_minutes;


                                                $sat_timeout_object = DateTime::createFromFormat('H:i', $sat_timeout);
                                                $sat_timeout_formatted = $sat_timeout_object->format('H:i'); 
                                                list($sat_hourss, $sat_minutess) = explode(':', $sat_timeout_formatted);

                                                // Convert hours and minutes to total minutes
                                                $sat_total_minutes_timeout = $sat_hourss + $sat_minutess;

                                                $sat_total_minutes_timein = intval($sat_total_minutes_timein);
                                                $sat_total_minutes_timeout = intval($sat_total_minutes_timeout);
                                                
                                                if($sat_total_minutes_timeout > $sat_total_minutes_timein){
                                                    $sat_total_work = ($sat_total_minutes_timeout - $sat_total_minutes_timein) - 1;
                                                }else{
                                                    $sat_total_work = ($sat_total_minutes_timein - $sat_total_minutes_timeout) - 1;
                                                }        
                                            }
                                            // echo $sat_total_work;
                                            // -----------------------SCHED Saturday END----------------------------//

                                            // -----------------------BREAK SUNDAY START----------------------------//
                                            if ($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == '') {
                                                $sun_timeIN = '00:00:00';
                                                $sun_timeout = '01:00:00';
                                                
                                                $sun_total_work = strtotime($sun_timeout) - strtotime($sun_timeIN) - 7200;
                                                $sun_total_work = date('H:i:s', $sun_total_work);
                                            }else{
                                                $sun_timeIN = $row_Sched['sun_timein'];
                                                $sun_timeout = $row_Sched['sun_timeout'];

                                                // Create a DateTime object from the string
                                                $sun_timeIN_object = DateTime::createFromFormat('H:i', $sun_timeIN);
                                                $sun_timeIN_formatted = $sun_timeIN_object->format('H:i'); 
                                                list($sun_hours, $sun_minutes) = explode(':', $sun_timeIN_formatted);

                                                // Convert hours and minutes to total minutes
                                                $sun_total_minutes_timein = $sun_hours + $sun_minutes;

                                                $sun_timeout_object = DateTime::createFromFormat('H:i', $sun_timeout);
                                                $sun_timeout_formatted = $sun_timeout_object->format('H:i'); 
                                                list($sun_hourss, $sun_minutess) = explode(':', $sun_timeout_formatted);

                                                // Convert hours and minutes to total minutes
                                                $sun_total_minutes_timeout = $sun_hourss + $sun_minutess;

                                                $sun_total_minutes_timein = intval($sun_total_minutes_timein);
                                                $sun_total_minutes_timeout = intval($sun_total_minutes_timeout);


                                                if($sun_total_minutes_timeout > $sun_total_minutes_timein){
                                                    $sun_total_work = ($sun_total_minutes_timeout - $sun_total_minutes_timein) - 1;
                                                }else{
                                                    $sun_total_work = ($sun_total_minutes_timein - $sun_total_minutes_timeout) - 1;
                                                }                                                                  
                                                }
                                                //  echo $sun_total_work;
                                            // -----------------------SCHED SUNDAY END----------------------------//

                                            $sql_attndces = mysqli_query($conn, "SELECT *, 
                                            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name
                                            FROM employee_tb
                                            WHERE `empid` = '$EmployeeID'");
                                    
                                    if(mysqli_num_rows($sql_attndces) > 0){
                                        $row_emp = mysqli_fetch_assoc($sql_attndces);
                                        $EmpDrate = $row_emp['drate'];
                                        $EmpOTrate = $row_emp['otrate'];
                                        $EmpStatus = $row_emp['status'];
                                        $EmpFullName = $row_emp['full_name'];
                            
                                        $query = "SELECT * FROM attendances WHERE `status` = 'Present' AND `empid` = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date'";
                                        $result = mysqli_query($conn, $query);
                            
                                        if(mysqli_num_rows($result) > 0){
                            
                                            $datesArray = array(); // Array to store the dates
                            
                                            while($rowatt = mysqli_fetch_assoc($result)){
                                            $_late = $rowatt["late"];
                                            $Date = $rowatt["date"];
                                            $datesArray[] = array('late' => $_late, 'date' => $Date);
                                           }
                                           foreach ($datesArray as $date_att){
                                            $day_of_week = date('l', strtotime($date_att['date']));
                            
                                            if($day_of_week === 'Monday'){       
                                                if($MOn_total_work === '00:00:00'){
                                                    $MONDAY_TO_DEDUCT_LATE = 0;
                                                    // $MONDAY_TO_DEDUCT_UT = 0;
                                                    // $MONDAY_ToADD_OT = 0;
                                                }else{
                                                    $mon_emp_dailyRate =  $row_emp['drate'];
                                                    $mon_emp_OtRate = $row_emp['otrate'];

                                                    $Mon_total_work_hours = (int)substr($MOn_total_work, 0, 2);
                                                    $mon_hour_rate =  $mon_emp_dailyRate / $Mon_total_work_hours;
                                                    $MON_minute_rate = $mon_hour_rate / 60; 

                                                    $mon_timeString =$date_att['late'];
                                                    // $mon_timeString_UT = $date_att['underTime'];
                                                    // $mon_timeString_OT = $date_att['OT'];

                                                    $mon_time = DateTime::createFromFormat('H:i:s', $mon_timeString);// Convert time string to DateTime object
                                                    // $mon_time_UT = DateTime::createFromFormat('H:i:s', $mon_timeString_UT);// Convert time string to DateTime object
                                                    // $mon_time_OT = DateTime::createFromFormat('H:i:s', $mon_timeString_OT);// Convert time string to DateTime object

                                                    //For latee
                                                    $mon_lateH = $mon_time->format('H');// Extract minutes from DateTime object
                                                    $mon_lateM = $mon_time->format('i');// Extract minutes from DateTime object
                                                    $mon_totalMinutes = intval($mon_lateM);// Convert minutes to integer
                                                    $mon_totalhours = intval($mon_lateH);// Convert minutes to integer
                                                    @$MONDAY_TO_DEDUCT_LATE_hours += $mon_totalhours * $mon_hour_rate;//minutes to deduct
                                                    @$MONDAY_TO_DEDUCT_LATE_minutes += $mon_totalMinutes * $MON_minute_rate;//minutes to deduct
                                                    @$MONDAY_TO_DEDUCT_LATE =  @$MONDAY_TO_DEDUCT_LATE_hours +  @$MONDAY_TO_DEDUCT_LATE_minutes;

                                                    //for Undertime
                                                    // $mon_hour= $mon_time_UT->format('H');// Extract Hour from DateTime object
                                                    // $mon_totalHour = intval($mon_hour);
                                                    // @$MONDAY_TO_DEDUCT_UT += $mon_totalHour * $mon_hour_rate;


                                                    //for Overtime
                                                    // $mon_hour_OT = $mon_time_OT->format('H');// Extract Hour from DateTime object
                                                    // $mon_totalHour_OT = intval($mon_hour_OT);
                                                    // @$MONDAY_ToADD_OT += $mon_emp_OtRate *  $mon_totalHour_OT;
                                                    }                                                   
                                                }//Monday

                                                else if($day_of_week === 'Tuesday'){
                                                    if($Tue_total_work === '00:00:00'){
                                                        $Tue_TO_DEDUCT_LATE = 0;
                                                    }else{
                                                        $tue_emp_dailyRate =  $row_emp['drate'];
                                                        $tue_emp_OtRate = $row_emp['otrate'];

                                                        $tue_total_work_hours = (int)substr($Tue_total_work, 0, 2);
                                                        $tue_hour_rate =  $tue_emp_dailyRate / $tue_total_work_hours;
                                                        $tue_minute_rate = $tue_hour_rate / 60; 

                                                        $tue_timeString = $date_att['late'];

                                                        $tue_time = DateTime::createFromFormat('H:i:s', $tue_timeString);

                                                        $tue_lateH = $tue_time->format('H');
                                                        $tue_lateM = $tue_time->format('i');
                                                        $tue_totalMinutes = intval($tue_lateM);
                                                        $tue_totalhours = intval($tue_lateH);
                                                        @$tue_LATE_hours += $tue_totalhours * $tue_hour_rate;
                                                        @$tue_LATE_minutes += $tue_totalMinutes * $tue_minute_rate;
                                                        @$Tue_TO_DEDUCT_LATE =  @$tue_LATE_hours +  @$tue_LATE_minutes;
                                                    }
                                                }//Tuesday

                                                else if($day_of_week === 'Wednesday'){
                                                    if($wed_total_work === '00:00:00'){
                                                        $WED_TO_DEDUCT_LATE = 0;
                                                    }else{
                                                        $weds_emp_dailyRate =  $row_emp['drate'];
                                                        $weds_emp_OtRate = $row_emp['otrate'];

                                                        $weds_total_work_hours = (int)substr($wed_total_work, 0, 2);
                                                        $weds_hour_rate =  $weds_emp_dailyRate / $weds_total_work_hours;
                                                        $weds_minute_rate = $weds_hour_rate / 60; 

                                                        
                                                        $weds_timeString = $date_att['late'];

                                                        $weds_time = DateTime::createFromFormat('H:i:s', $weds_timeString);

                                                        $weds_lateH = $weds_time->format('H');
                                                        $weds_lateM = $weds_time->format('i');
                                                        $weds_totalMinutes = intval($weds_lateM);
                                                        $weds_totalhours = intval($weds_lateH);
                                                        @$weds_TO_DEDUCT_LATE_hours += $weds_totalhours * $weds_hour_rate;
                                                        @$weds_TO_DEDUCT_LATE_minutes += $weds_totalMinutes * $weds_minute_rate;
                                                        @$WED_TO_DEDUCT_LATE =  @$weds_TO_DEDUCT_LATE_hours +  @$weds_TO_DEDUCT_LATE_minutes;
                                                        }
                                                    }//Wednesday

                                                else if($day_of_week === 'Thursday'){
                                                    if($thurs_total_work === '00:00:00'){
                                                        $Thurs_TO_DEDUCT_LATE = 0;
                                                    }else{
                                                        $thurs_emp_dailyRate =  $row_emp['drate'];
                                                        $thurs_emp_OtRate = $row_emp['otrate'];

                                                        $thurs_total_work_hours = (int)substr($thurs_total_work, 0, 2);
                                                        $thurs_hour_rate =  $thurs_emp_dailyRate / $thurs_total_work_hours;
                                                        $thurs_minute_rate = $thurs_hour_rate / 60; 

                                                        $thurs_timeString = $date_att['late'];

                                                        $thurs_time = DateTime::createFromFormat('H:i:s', $thurs_timeString);

                                                        $thurs_lateH = $thurs_time->format('H');
                                                        $thurs_lateM = $thurs_time->format('i');
                                                        $thurs_totalMinutes = intval($thurs_lateM);
                                                        $thurs_totalhours = intval($thurs_lateH);
                                                        @$thurs_TO_DEDUCT_LATE_hours += $thurs_totalhours * $thurs_hour_rate;
                                                        @$thurs_TO_DEDUCT_LATE_minutes += $thurs_totalMinutes * $thurs_minute_rate;
                                                        @$Thurs_TO_DEDUCT_LATE =  @$thurs_TO_DEDUCT_LATE_hours +  @$thurs_TO_DEDUCT_LATE_minutes;
                                                        }
                                                    }//Thursday

                                                else if($day_of_week === 'Friday'){
                                                    if($fri_total_work === '00:00:00'){
                                                        $Fri_TO_DEDUCT_LATE = 0;
                                                    }else{
                                                        $fri_emp_dailyRate =  $row_emp['drate'];
                                                        $fri_emp_OtRate = $row_emp['otrate'];
                                                        $fri_total_work_hours = (int)substr($fri_total_work, 0, 2);
                                                        $fri_hour_rate =  $fri_emp_dailyRate / $fri_total_work_hours;
                                                        $fri_minute_rate = $fri_hour_rate / 60; 

                                                        $fri_timeString =$date_att['late'];

                                                        $fri_time = DateTime::createFromFormat('H:i:s', $fri_timeString);// Convert time string

                                                        $fri_lateH = $fri_time->format('H');
                                                        $fri_lateM = $fri_time->format('i');
                                                        $fri_totalMinutes = intval($fri_lateM);
                                                        $fri_totalhours = intval($fri_lateH);
                                                        @$fri_TO_DEDUCT_LATE_hours += $fri_totalhours * $fri_hour_rate;
                                                        @$fri_TO_DEDUCT_LATE_minutes += $fri_totalMinutes * $fri_minute_rate;
                                                        @$Fri_TO_DEDUCT_LATE =  @$fri_TO_DEDUCT_LATE_hours +  @$fri_TO_DEDUCT_LATE_minutes;
                                                        }
                                                    }//Friday

                                                else if($day_of_week === 'Saturday'){
                                                    if($sat_total_work === '00:00:00'){
                                                        $SAT_TO_DEDUCT_LATE = 0;
                                                    }else{
                                                        $sat_emp_dailyRate =  $row_emp['drate'];
                                                        $sat_emp_OtRate = $row_emp['otrate'];
                                                        $sat_total_work_hours = (int)substr($sat_total_work, 0, 2);
                                                        $sat_hour_rate =  $sat_emp_dailyRate / $sat_total_work_hours;
                                                        $sat_minute_rate = $sat_hour_rate / 60; 

                                                        $sat_timeString =$date_att['late'];

                                                        $sat_time = DateTime::createFromFormat('H:i:s', $sat_timeString);

                                                        $sat_lateH = $sat_time->format('H');
                                                        $sat_lateM = $sat_time->format('i');
                                                        $sat_totalMinutes = intval($sat_lateM);
                                                        $sat_totalhours = intval($sat_lateH);
                                                        @$sat_TO_DEDUCT_LATE_hours += $sat_totalhours * $sat_hour_rate;
                                                        @$sat_TO_DEDUCT_LATE_minutes += $sat_totalMinutes * $sat_minute_rate;
                                                        @$SAT_TO_DEDUCT_LATE =  @$sat_TO_DEDUCT_LATE_hours +  @$sat_TO_DEDUCT_LATE_minutes;
                                                        }
                                                    }//Saturday

                                                else if($day_of_week === 'Sunday'){
                                                    if($sun_total_work === '00:00:00'){
                                                        $Sun_TO_DEDUCT_LATE = 0;
                                                    }else{                                                  
                                                        $sun_emp_dailyRate =  $row_emp['drate'];
                                                        $sun_emp_OtRate = $row_emp['otrate'];
                                                        $sun_total_work_hours = (int)substr($sun_total_work, 0, 2);
                                                        $sun_hour_rate =  $sun_emp_dailyRate / $sun_total_work_hours;
                                                        $sun_minute_rate = $sun_hour_rate / 60; 

                                                        $sun_timeString =$date_att['late'];

                                                        $sun_time = DateTime::createFromFormat('H:i:s', $sun_timeString);

                                                        $sun_lateH = $sun_time->format('H');
                                                        $sun_lateM = $sun_time->format('i');
                                                        $sun_totalMinutes = intval($sun_lateM);
                                                        $sun_totalhours = intval($sun_lateH);
                                                        @$sun_TO_DEDUCT_LATE_hours += $sun_totalhours * $sun_hour_rate;
                                                        @$sun_TO_DEDUCT_LATE_minutes += $sun_totalMinutes * $sun_minute_rate;
                                                        @$Sun_TO_DEDUCT_LATE =  @$sun_TO_DEDUCT_LATE_hours +  @$sun_TO_DEDUCT_LATE_minutes;
                                                    }
                                                }//Sunday
                            
                                           }
                                        } else {
                                            echo "You cannot generate a payslip for employee with no attendance";
                                        }
                                    } else {
                                        echo "No results found ";
                                    }

                                    //------------Syntax sa pagcompute ng overtime kung may naapproved na ot request-------//
                                    $sql_OT = "SELECT * FROM `overtime_tb` WHERE `empid` = '$EmployeeID' AND `status` = 'Approved' AND `work_schedule` BETWEEN '$str_date' AND '$end_date'";
                                    $result = mysqli_query($conn, $sql_OT);

                                    if (mysqli_num_rows($result) > 0) {
                                        $OTArray = array(); // Array to store the OT
                                        while ($row_OT = $result->fetch_assoc()) {
                                            $OT_hours = $row_OT['total_ot'];
                                            $OT_day = $row_OT['work_schedule'];

                                            $OTArray[] = array('OT_hours' => $OT_hours, 'OT_day' => $OT_day);
                                        }
                                    
                                            $emp_OtRate = $row_emp['otrate']; 
                                            //$time_OT_TOTAL = 0; // Initialize the total variable
                                    
                                            foreach ($OTArray as $OT_data) {
                                                $Dates_OT = $OT_data['OT_day'];

                                                $query_selector_holiday_OT = "SELECT * FROM holiday_tb WHERE date_holiday = '$Dates_OT'";
                                                $result__selector_holiday_OT = mysqli_query($conn, $query_selector_holiday_OT);
                                                if(mysqli_num_rows($result__selector_holiday_OT) <= 0){
                                                    $time_OT = DateTime::createFromFormat('H:i:s', $OT_data['OT_hours']);
                                                    //for hour OT
                                                    $time_OT_hour = $time_OT->format('H'); 
                                                    $time_OT_hour = intval($time_OT_hour);
                                                    $time_OT_hour_rate = $emp_OtRate * $time_OT_hour;

                                                    // for minute OT
                                                    $time_OT_mins = $time_OT->format('i');                                         
                                                    $time_OT_mins = intval($time_OT_mins);
                                                    $time_OT_minute_rate = $emp_OtRate / 60;
                                                    $time_OT_minute_rate = $time_OT_minute_rate * $time_OT_mins;

                                                    // added all the converted time from OT hours and mins
                                                    @$time_OT_TOTAL += $time_OT_hour_rate + $time_OT_minute_rate;
                                                    @$time_OT_TOTAL = number_format($time_OT_TOTAL, 2);
                                                }
                                            }
                                        @$time_OT_TOTAL;//total of Overtime for cutoff
                                    }else{
                                        $time_OT_TOTAL = 0;
                                    }
                                    //------------End Syntax sa pagcompute ng overtime kung may naapproved na ot request-------//

                                    //-------------Syntax sa pagdeduct ng undertime kung may naapprove na ut request------//
                                    $sql_UT = "SELECT * FROM `undertime_tb` WHERE `empid` = '$EmployeeID' AND `status` = 'Approved' AND `date` BETWEEN '$str_date' AND '$end_date'";
                                    $result = mysqli_query($conn, $sql_UT);

                                    if (mysqli_num_rows($result) > 0) {
                                        $UTarray = array(); // Array to store the OT
                                        while ($row_UT = $result->fetch_assoc()) {
                                        //  $row_UT['date'];
                                        
                                            $day_of_week_UT = date('l', strtotime($row_UT['date']));//convert the each date to day
                                                if($day_of_week_UT === 'Monday'){
                                                $Mon_total_work_hours; //total working hour day
                                                @$mon_hour_rate =  $row_emp['drate'] / $Mon_total_work_hours;
                                                $mon_minute_rate = $mon_hour_rate / 60; 

                                                $time_UT_con = DateTime::createFromFormat('H:i:s', $row_UT['total_undertime']);
                                                $UT_hour = $time_UT_con->format('H');
                                                $UT_totalHour = intval($UT_hour);
                                                @$mon_TO_DEDUCT_UT += $UT_totalHour * $mon_hour_rate;

                                                }else if($day_of_week_UT === 'Tuesday'){
                                                $tue_total_work_hours; //total working hour day
                                                @$tue_hour_rate =  $row_emp['drate'] / @$tue_total_work_hours;
                                                $tue_minute_rate = $tue_hour_rate / 60; 

                                                $time_UT_con = DateTime::createFromFormat('H:i:s', $row_UT['total_undertime']);
                                                $UT_hour = $time_UT_con->format('H');
                                                $UT_totalHour = intval($UT_hour);
                                                @$tues_TO_DEDUCT_UT += $UT_totalHour * $tue_hour_rate;

                                                }else if($day_of_week_UT === 'Wednesday'){
                                                $weds_total_work_hours; //total working hour day
                                                @$weds_hour_rate =  $row_emp['drate'] / $weds_total_work_hours;
                                                $weds_minute_rate = $weds_hour_rate / 60; 

                                                $time_UT_con = DateTime::createFromFormat('H:i:s', $row_UT['total_undertime']);
                                                $UT_hour = $time_UT_con->format('H');
                                                $UT_totalHour = intval($UT_hour);
                                                @$weds_TO_DEDUCT_UT += $UT_totalHour * $weds_hour_rate;
                                                
                                                }else if($day_of_week_UT === 'Thursday'){
                                                $thurs_total_work_hours; //total working hour day
                                                @$thurs_hour_rate =  $row_emp['drate'] / $thurs_total_work_hours; 
                                                
                                                $time_UT_con = DateTime::createFromFormat('H:i:s', $row_UT['total_undertime']);
                                                $UT_hour = $time_UT_con->format('H');
                                                $UT_totalHour = intval($UT_hour);
                                                $UT_totalHour = $UT_totalHour * $thurs_hour_rate;

                                                $thurs_minute_rate = $thurs_hour_rate / 60; 
                                                $UT_min = $time_UT_con->format('i');
                                                $UT_totalmin = intval($UT_min);
                                                $UT_totalmin = $UT_totalmin * $thurs_minute_rate;
                                                @$thurs_TO_DEDUCT_UT += $UT_totalHour + $UT_totalmin;

                                                }else if($day_of_week_UT === 'Friday'){
                                                $fri_total_work_hours; //total working hour day
                                                $fri_hour_rate =  $row_emp['drate'] / $fri_total_work_hours;
                                                $fri_minute_rate = $fri_hour_rate / 60; 

                                                $time_UT_con = DateTime::createFromFormat('H:i:s', $row_UT['total_undertime']);
                                                $UT_hour = $time_UT_con->format('H');
                                                $UT_totalHour = intval($UT_hour);
                                                @$fri_TO_DEDUCT_UT += $UT_totalHour * $fri_hour_rate;

                                                }else if($day_of_week_UT === 'Saturday'){
                                                $sat_total_work_hours;  //total working hour day
                                                $sat_hour_rate =  $row_emp['drate'] / $sat_total_work_hours;
                                                $sat_minute_rate = $sat_hour_rate / 60; 

                                                $time_UT_con = DateTime::createFromFormat('H:i:s', $row_UT['total_undertime']);
                                                $UT_hour = $time_UT_con->format('H');
                                                $UT_totalHour = intval($UT_hour);
                                                @$sat_TO_DEDUCT_UT += $UT_totalHour * $sat_hour_rate;

                                                }else if($day_of_week_UT === 'Sunday'){
                                                $sun_total_work_hours; //total working hour day
                                                $sun_hour_rate =  $row_emp['drate'] / $sun_total_work_hours;
                                                $sun_minute_rate = $sun_hour_rate / 60; 

                                                $time_UT_con = DateTime::createFromFormat('H:i:s', $row_UT['total_undertime']);
                                                $UT_hour = $time_UT_con->format('H');
                                                $UT_totalHour = intval($UT_hour);
                                                @$sun_TO_DEDUCT_UT += $UT_totalHour * $sun_minute_rate;
                                                } 
                                            }
                                        }
                                            //  $value_UT_LATE = (@$MONDAY_TO_DEDUCT_LATE + @$Tue_TO_DEDUCT_LATE + @$WED_TO_DEDUCT_LATE +  @$Thurs_TO_DEDUCT_LATE + @$Fri_TO_DEDUCT_LATE + @$SAT_TO_DEDUCT_LATE + @$Sun_TO_DEDUCT_LATE) +  (@$MONDAY_TO_DEDUCT_UT +  @$Tue_TO_DEDUCT_UT + @$WED_TO_DEDUCT_UT  + @$Thurs_TO_DEDUCT_UT +  @$Fri_TO_DEDUCT_UT +  @$SAT_TO_DEDUCT_UT +  @$Sun_TO_DEDUCT_UT);

                                            //Late rate how much to deduct
                                            @$Late_rate_to_deduct = @$MONDAY_TO_DEDUCT_LATE + @$Tue_TO_DEDUCT_LATE + @$WED_TO_DEDUCT_LATE +  @$Thurs_TO_DEDUCT_LATE + @$Fri_TO_DEDUCT_LATE + @$SAT_TO_DEDUCT_LATE + @$Sun_TO_DEDUCT_LATE;
                                            $Late_rate_to_deduct = number_format($Late_rate_to_deduct, 2);

                                            //Undertime rate how much to deduct
                                            @$Undertime_rate_to_deduct = @$mon_TO_DEDUCT_UT + @$tues_TO_DEDUCT_UT + @$weds_TO_DEDUCT_UT +  @$thurs_TO_DEDUCT_UT + @$fri_TO_DEDUCT_UT + @$sat_TO_DEDUCT_UT + @$sun_TO_DEDUCT_UT;
                                            $Undertime_rate_to_deduct = number_format($Undertime_rate_to_deduct, 2);
                                            
                                            $value_UT_LATE = (@$Late_rate_to_deduct)  +  (@$Undertime_rate_to_deduct);

                                            $UT_LATE_DEDUCT_TOTAL = number_format($value_UT_LATE, 2); //convert into two decimal only
                                            $UT_LATE_DEDUCT_TOTAL = str_replace(',', '', $UT_LATE_DEDUCT_TOTAL); // Remove comma
                                            //-------------End Syntax sa pagdeduct ng undertime kung may naapprove na ut request------//

                                            //para sa pag select sa attendances at employee para sa modal ng payslip
                                            if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){ 
                                                $sql_attendanaaa = mysqli_query($conn, " SELECT
                                                employee_tb.`empbsalary` AS Salary_of_Month,
                                                employee_tb.`sss_amount`,
                                                employee_tb.`tin_amount`,
                                                employee_tb.`pagibig_amount`,
                                                employee_tb.`philhealth_amount`,
                                                employee_tb.`emptranspo`,
                                                employee_tb.`empmeal`,
                                                employee_tb.`empinternet`,
                                                employee_tb.`emptranspo` + employee_tb.`empmeal` + employee_tb.`empinternet`  AS Total_allowanceStandard,
                                                employee_tb.`sss_amount` + employee_tb.`tin_amount` + employee_tb.`pagibig_amount` + employee_tb.`philhealth_amount` AS Total_deduct_governStANDARD,

                                                CONCAT(
                                                        FLOOR(
                                                            SUM(TIME_TO_SEC(attendances.total_work)) / 3600
                                                            
                                                        ),
                                                        'H:',
                                                        FLOOR(
                                                            (
                                                                SUM(TIME_TO_SEC(attendances.total_work)) % 3600
                                                                
                                                            ) / 60
                                                        ),
                                                        'M'
                                                    ) AS total_hoursWORK,
                                                    
                                                CONCAT(
                                                        FLOOR(
                                                            SUM(TIME_TO_SEC(attendances.overtime)) / 3600
                                                        ),
                                                        'H'
                                                    ) AS total_hoursOT,
                                                COUNT(attendances.`status`) AS Number_of_days_work
                                                FROM
                                                employee_tb
                                                INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND employee_tb.empid = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date'");

                                            }else{
                                                $sql_attendanaaa = mysqli_query($conn, " SELECT
                                                SUM(employee_tb.`drate`) AS Salary_of_Month,
                                                employee_tb.`sss_amount`,
                                                employee_tb.`tin_amount`,
                                                employee_tb.`pagibig_amount`,
                                                employee_tb.`philhealth_amount`,
                                                employee_tb.`emptranspo`,
                                                employee_tb.`empmeal`,
                                                employee_tb.`empinternet`,
                                                employee_tb.`emptranspo` + employee_tb.`empmeal` + employee_tb.`empinternet`  AS Total_allowanceStandard,
                                                employee_tb.`sss_amount` + employee_tb.`tin_amount` + employee_tb.`pagibig_amount` + employee_tb.`philhealth_amount` AS Total_deduct_governStANDARD,

                                                CONCAT(
                                                        FLOOR(
                                                            SUM(TIME_TO_SEC(attendances.total_work)) / 3600                                                                      
                                                        ),
                                                        'H:',
                                                        FLOOR(
                                                            (
                                                                SUM(TIME_TO_SEC(attendances.total_work)) % 3600                                                     
                                                            ) / 60
                                                        ),
                                                        'M'
                                                    ) AS total_hoursWORK,
                                                    
                                                CONCAT(
                                                        FLOOR(
                                                            SUM(TIME_TO_SEC(attendances.overtime)) / 3600
                                                        ),
                                                        'H'
                                                    ) AS total_hoursOT,
                                                COUNT(attendances.`status`) AS Number_of_days_work
                                                FROM
                                                employee_tb
                                                INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND employee_tb.empid = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date'");
                                            }


                                            if(mysqli_num_rows($sql_attendanaaa) > 0) {
                                                $row_atteeee = mysqli_fetch_assoc($sql_attendanaaa);
                                                $Totalwork = $row_atteeee['total_hoursWORK'];
                                                $TotalworkDays = $row_atteeee['Number_of_days_work'];
                                                $salary = $row_atteeee['Salary_of_Month'];
                                                $Transport = $row_atteeee['emptranspo'];
                                                $Meal = $row_atteeee['empmeal'];
                                                $Internet = $row_atteeee['empinternet'];
                                                
                            
                                                } else {
                                                echo "No results found schedule."; 
                                                } 
                            
                                                //Montly allowance
                                                $result_allowance = mysqli_query($conn, " SELECT
                                                    SUM(allowance_amount) AS total_sum_addAllowance
                                                FROM 
                                                `allowancededuct_tb` 
                                                WHERE `id_emp`=  '$EmployeeID'");
                                                $row_addAllowance = mysqli_fetch_assoc($result_allowance);
                                                $Otherallowance = $row_addAllowance['total_sum_addAllowance'];
                            
                                                //FOR ALLOWANCE TO COMPUTE THE TOTAL WORKING DAYS 
                                                $startDate = new DateTime($str_date); //cutoff start date
                                                $endDate = new DateTime($end_date);  //cutoff end date
                                                
                                                // Create an empty array to store the dates
                                                $dates = array();
                                                
                                                // Clone the start date to avoid modifying it directly
                                                $currentDate = clone $startDate;
                                                
                                                // Loop until the current date reaches the end date
                                                while ($currentDate <= $endDate) {
                                                    $dates[] = $currentDate->format('Y-m-d');
                                                    $currentDate->modify('+1 day');
                                                
                                                }

                                                $sum = 0;
                                                @$working_days = 0;
                                        
                                                foreach ($dates as $date) {

                                                    $day_of_week_allowance = date('l', strtotime($date));//convert the each date to day
                                                    // echo $date . " = " . $day_of_week_allowance ."<br> <br>";
                                                    
                                                    if($day_of_week_allowance === 'Monday'){
                                                        // -----------------------BREAK MONDAY START----------------------------//
                                                        if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){
                                                            @$sum += 0; // used for allowance in payrolldd
                                                        }else{
                                                            @$sum += 1; // used for allowance in payrolldd
                                                        }
                                                    }
                                                        // -----------------------BREAK MONDAY START----------------------------//

                                                        // -----------------------BREAK Tuesday START----------------------------//

                                                        if($day_of_week_allowance === 'Tuesday'){
                                                            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                                @$sum += 0; // used for allowance in payrolldd
                                                            }else{
                                                                @$sum += 1; // used for allowance in payrolldd
                                                            }
                                                        }   
                                                        // -----------------------BREAK Tuesday END----------------------------//

                                                        // -----------------------BREAK WEDNESDAY START----------------------------//
                                                        if($day_of_week_allowance === 'Wednesday'){
                                                            if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                                                                @$sum += 0; // used for allowance in payrolldd
                                                            }else{
                                                                @$sum += 1; // used for allowance in payrolldd
                                                            }
                                                        }                                                 
                                                        // -----------------------BREAK WEDNESDAY END----------------------------//

                                                        // -----------------------BREAK THURSDAY START----------------------------//
                                                        if($day_of_week_allowance === 'Thursday'){
                                                            if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                            
                                                                @$sum += 0; // used for allowance in payrolldd
                                                            }else{
                                                                @$sum += 1; // used for allowance in payrolldd
                                                            }
                                                        }
                                                        // -----------------------BREAK THURSDAY END----------------------------//

                                                        // -----------------------BREAK FRIDAY START----------------------------//
                                                        if($day_of_week_allowance === 'Friday'){
                                                            if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){
                                                                @$sum += 0; // used for allowance in payrolldd
                                                            }else{
                                                                @$sum += 1; // used for allowance in payrolldd
                                                            }
                                                        }                                         
                                                        // -----------------------BREAK FRIDAY END----------------------------//

                                                        // -----------------------BREAK Saturday START----------------------------//
                                                        if($day_of_week_allowance === 'Saturday'){
                                                            if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                                                                @$sum += 0; // used for allowance in payrolldd
                                                            }else{
                                                                @$sum += 1; // used for allowance in payrolldd
                                                            }
                                                        }
                                                        // -----------------------BREAK Saturday END----------------------------//

                                                        // -----------------------BREAK SUNDAY START----------------------------//
                                                        if($day_of_week_allowance === 'Sunday'){
                                                            if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                                                                @$sum += 0; // used for allowance in payrolldd
                                                            }else{
                                                                @$sum += 1; // used for allowance in payrolldd
                                                            }
                                                        }
                                                        // -----------------------BREAK SUNDAY END----------------------------// 
                                                }//end foreach
                                                $working_days += $sum; //SUM OF WORKING DAYS IN SCHEDULE USED IN COMPUTING ALLOWANCE 

                                                if ($Frequency === 'Monthly'){
                                                    $allowance = ($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / $working_days;
                                                    $allowance = str_replace(',', '', $allowance); // Remove comma
                            
                                                    $Transport = $row_atteeee['emptranspo'];
                                                    $Meal = $row_atteeee['empmeal'];
                                                    $Internet = $row_atteeee['empinternet'];
                                                    $Otherallowance = $row_addAllowance['total_sum_addAllowance']; 
                            
                                                    
                            
                                                    $Total_allowances = $Transport + $Meal + $Internet + $Otherallowance;
                                                } 
                                                else if ($Frequency === 'Semi-Month'){
                                                    $allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 2) / $working_days;
                                                    $allowance = str_replace(',', '', $allowance); // Remove comma
                            
                                                    $first_cutOFf = '1';
                                                    $last_cutoff = '2';  
                                                    
                                                    $Transport = $row_atteeee['emptranspo'] / 2;
                                                    $Meal = $row_atteeee['empmeal'] / 2;
                                                    $Internet = $row_atteeee['empinternet'] / 2;
                                                    $Otherallowance = $row_addAllowance['total_sum_addAllowance'] / 2;
                                                    
                                                    $Total_allowances = $Transport + $Meal + $Internet + $Otherallowance;
                                                }
                                                else if ($Frequency === 'Weekly'){
                                                    $allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 4) / $working_days;
                                                    $allowance = str_replace(',', '', $allowance); // Remove comma
                            
                                                    $first_cutOFf = '1';
                                                    $last_cutoff ='4';    
                            
                                                    $Transport = $row_atteeee['emptranspo'] / 4;
                                                    $Meal = $row_atteeee['empmeal'] / 4;
                                                    $Internet = $row_atteeee['empinternet'] / 4;
                                                    $Otherallowance = $row_addAllowance['total_sum_addAllowance'] / 4;
                            
                                                    $Total_allowances = $Transport + $Meal + $Internet + $Otherallowance;
                                                }

                                                    //CHECK IF REGULAR NA SIYA OR HINDI PARA SA HOLIDAY RATE
                                                    $result_EMP_classification = mysqli_query($conn, " SELECT
                                                    employee_tb.classification,
                                                    classification_tb.classification AS  employee_classification

                                                    FROM 
                                                    `employee_tb` 
                                                    INNER JOIN 
                                                    `classification_tb` 
                                                    ON
                                                    employee_tb.classification = classification_tb.id
                                                    WHERE employee_tb.empid=  '$EmployeeID'");
                                                    $row_emp_classification = mysqli_fetch_assoc($result_EMP_classification);
                                                    $empclassy = $row_emp_classification['classification'];

                                                    if($row_emp_classification['employee_classification'] != 'Internship/OJT'){
                                                        //CHECK lahat ng attendance niya if may holiday
                                                        $sql_att_all = "SELECT * FROM 
                                                        `attendances` 
                                                        WHERE 
                                                        (`status` = 'Present' OR `status` = 'On-Leave') 
                                                        AND `empid` = '$EmployeeID' AND 
                                                        `date` 
                                                        BETWEEN  
                                                        '$str_date' 
                                                        AND  
                                                        '$end_date'";
                                
                                                        $result = mysqli_query($conn, $sql_att_all);
                                                        $rowatt = mysqli_fetch_assoc($result);
                                                        if ($result->num_rows > 0) {
                                
                                                            $att_array = array(); // Array to store the attendance
                                
                                                            while ($row_att_all = $result->fetch_assoc()) {
                                                                $date_att = $row_att_all['date'];
                                                                $att_time_in = $row_att_all['time_in'];
                                                                $att_time_out = $row_att_all['time_out'];
                                
                                                                $att_array[] = array('date_att' => $date_att);
                                                            }
                                
                                                            $double_pay_holiday = 0;
                                                            $totalOT_pay_holiday = 0;
                                                            $totalOT_pay_holiday_restday = 0;
                                                            $double_pay_holiday_restday = 0;
                                
                                                            foreach($att_array as $att_holiday_arrays){
                                                                $holiday_array = $att_holiday_arrays['date_att']; //dates in attendances
                                                            
                                                                
                                                                //check if may holiday sa attendance ng employee
                                                                $result_holiday = mysqli_query($conn, " SELECT
                                                                    *
                                                                FROM 
                                                                    `holiday_tb` 
                                                                WHERE date_holiday =  '$holiday_array' AND (`holiday_type` = 'Regular Holiday' OR `holiday_type` = 'Special Non-Working Holiday' OR `holiday_type` = 'Special Working Holiday')");
                                
                                                                if(mysqli_num_rows($result_holiday) > 0) {
                                                                    $row_holiday = mysqli_fetch_assoc($result_holiday);
                                
                                                                    // echo "<br>" . $valid_holiday = $row_holiday['date_holiday'];//holiday dates
                                                                    // echo "<br>" . $valid_holiday_type = $row_holiday['holiday_type']; //holiday types
                                                                    $valid_holiday = $row_holiday['date_holiday'];//holiday dates
                                                                    $valid_holiday_type = $row_holiday['holiday_type']; //holiday types
                                
                                                                    //check if ano rule naka apply para ma applicable ang holiday pay
                                                                    $result_company_settings = mysqli_query($conn, " SELECT
                                                                        *
                                                                    FROM 
                                                                        `settings_tb` 
                                                                    ORDER BY `_datetime` DESC
                                                                    LIMIT 1 ");
                                                                    $row_company_settings = mysqli_fetch_assoc($result_company_settings);
                                
                                                                    include 'Data Controller/Payroll/holiday_validation.php'; // para sa validation get ang rule ng holiday pay
                                                                    //-----------------------START COMPUTATION FOR HOLIDAY PAY IF  $validation_eligible_holiday = 'YES'--------------------
                                
                                                                if($validation_eligible_holiday === 'YES'){
                                                                            //select lahat ng date sa employee na may holiday
                                                                        if($valid_holiday_type === 'Regular Holiday') {
                                                                            include 'Data Controller/Payroll/regularPay.php'; // Para sa computation ng regular Holiday Pay
                                                                        }
                                                                        else if($valid_holiday_type === 'Special Non-Working Holiday' || $valid_holiday_type === 'Special Working Holiday'){
                                                                            include 'Data Controller/Payroll/specialPay.php'; // Para sa computation ng Special Holiday Pay
                                                                        } 
                                                                }
                                                            //-----------------------END COMPUTATION FOR HOLIDAY PAY IF  $validation_eligible_holiday = 'YES'--------------------
                                                                }
                                                            } // end Foreach
                                                        } //end $sql_att_all
                                                    }  //Close bracket if classification is not intern.

                                                    @$holiday_rate_with_dpay = $double_pay_holiday + $double_pay_holiday_restday;
                                                    @$holiday_rate_with_dpay_OT = $totalOT_pay_holiday + $totalOT_pay_holiday_restday;
                                
                                                    include 'Data Controller/Payroll/check_holiday_toDEduct.php'; //Para mag check ilan ang date ng may holiday para ma minus sa salary at d magdoble ang salary
                                
                                                    $row_holiday_to_deduct_holiday = $row_emp['drate'] * $num_days_holiday; // dito ako nahinto dapat mabawasan ko sa mga date daily mga pinasok na holiday 

                                                    $result_table_UT = mysqli_query($conn, "
                                                    SELECT
                                                        IFNULL(
                                                            CONCAT(
                                                                FLOOR(SUM(TIME_TO_SEC(total_undertime)) / 3600), 'H:',
                                                                FLOOR((SUM(TIME_TO_SEC(total_undertime)) % 3600) / 60), 'M'
                                                            ),
                                                            '0H:0M'
                                                        ) AS total_hours_minutesUndertime
                                                    FROM 
                                                        `undertime_tb` 
                                                    WHERE 
                                                        `empid` = '$EmployeeID' 
                                                        AND `date` BETWEEN '$str_date' AND '$end_date' 
                                                        AND `status` = 'Approved'
                                                ");
                                                
                                                $row_table_UT = mysqli_fetch_assoc($result_table_UT);
                                                $UT_time = $row_table_UT['total_hours_minutesUndertime'];
                                
                                                if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                    $sql = "SELECT
                                                    payroll_loan_tb.loan_type,
                                                    payroll_loan_tb.payable_amount,
                                                    payroll_loan_tb.amortization,
                                                    payroll_loan_tb.col_BAL_amount,
                                                    payroll_loan_tb.cutoff_no,
                                                    payroll_loan_tb.applied_cutoff,
                                                    payroll_loan_tb.loan_status,
                                                    payroll_loan_tb.loan_date,
                                                    payroll_loan_tb.timestamp,
                                                    SUM(allowancededuct_tb.allowance_amount) AS total_sum,
                                                    employee_tb.emptranspo,
                                                    employee_tb.empmeal,
                                                    employee_tb.empinternet,
                                                    employee_tb.`empbsalary` AS Salary_of_Month,
                                                    employee_tb.`sss_amount`,
                                                    employee_tb.`tin_amount`,
                                                    employee_tb.`pagibig_amount`,
                                                    employee_tb.`philhealth_amount`,
                                                    employee_tb.`emptranspo` + employee_tb.`empmeal` + employee_tb.`empmeal` AS Total_allowanceStandard,
                                                    employee_tb.`sss_amount` + employee_tb.`tin_amount` + employee_tb.`pagibig_amount` + employee_tb.`philhealth_amount` AS Total_deduct_governStANDARD,
                                                    CONCAT(
                                                            FLOOR( 
                                                                SUM(TIME_TO_SEC(attendances.late)) / 3600
                                                            ),
                                                            'H:',
                                                            FLOOR(
                                                                (
                                                                    SUM(TIME_TO_SEC(attendances.late)) % 3600
                                                                ) / 60
                                                            ),
                                                            'M'
                                                        ) AS total_hours_minutesLATE,
                                                    CONCAT(
                                                            FLOOR(
                                                                SUM(TIME_TO_SEC(attendances.early_out)) / 3600
                                                            ),
                                                            'H:',
                                                            FLOOR(
                                                                (
                                                                    SUM(TIME_TO_SEC(attendances.early_out)) % 3600
                                                                ) / 60
                                                            ),
                                                            'M'
                                                        ) AS total_hours_minutesUndertime,
                                                    CONCAT(
                                                            FLOOR(
                                                                SUM(TIME_TO_SEC(attendances.total_work)) / 3600
                                                                
                                                            ),
                                                            'H:',
                                                            FLOOR(
                                                                (
                                                                    SUM(TIME_TO_SEC(attendances.total_work)) % 3600
                                                                   
                                                                ) / 60
                                                            ),
                                                            'M'
                                                        ) AS total_hours_minutestotalHours
                                                FROM
                                                    employee_tb
                                                    INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                    LEFT JOIN allowancededuct_tb ON employee_tb.empid = allowancededuct_tb.id_emp
                                                    LEFT JOIN payroll_loan_tb ON employee_tb.empid = payroll_loan_tb.empid
                                
                                                WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave')  AND employee_tb.empid = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date'";
                                
                                                $sql_absent_count = "SELECT 
                                                                        COUNT(`status`) as Absent_count
                                                                     FROM attendances
                                                                     WHERE (`status` = 'Absent' OR `status` = 'LWOP')  AND `empid` = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date'";
                                
                                                $result_absent_count = mysqli_query($conn, $sql_absent_count);
                                                $row_absent_count = mysqli_fetch_assoc($result_absent_count);
                                                $number_of_absent =  $row_absent_count['Absent_count'];
                                                $absenceDeduct = $EmpDrate * $number_of_absent;
                                                
                                            }else{
                                                    $sql = "SELECT
                                                    payroll_loan_tb.loan_type,
                                                    payroll_loan_tb.payable_amount,
                                                    payroll_loan_tb.amortization,
                                                    payroll_loan_tb.col_BAL_amount,
                                                    payroll_loan_tb.cutoff_no,
                                                    payroll_loan_tb.applied_cutoff,
                                                    payroll_loan_tb.loan_status,
                                                    payroll_loan_tb.loan_date,
                                                    payroll_loan_tb.timestamp,
                                                    SUM(allowancededuct_tb.allowance_amount) AS total_sum,
                                                    employee_tb.emptranspo,
                                                    employee_tb.empmeal,
                                                    employee_tb.empinternet,
                                                    SUM(employee_tb.`drate`) AS Salary_of_Month,
                                                    employee_tb.`sss_amount`,
                                                    employee_tb.`tin_amount`,
                                                    employee_tb.`pagibig_amount`,
                                                    employee_tb.`philhealth_amount`,
                                                    employee_tb.`emptranspo` + employee_tb.`empmeal` + employee_tb.`empmeal` AS Total_allowanceStandard,
                                                    employee_tb.`sss_amount` + employee_tb.`tin_amount` + employee_tb.`pagibig_amount` + employee_tb.`philhealth_amount` AS Total_deduct_governStANDARD,
                                                    CONCAT(
                                                            FLOOR( 
                                                                SUM(TIME_TO_SEC(attendances.late)) / 3600
                                                            ),
                                                            'H:',
                                                            FLOOR(
                                                                (
                                                                    SUM(TIME_TO_SEC(attendances.late)) % 3600
                                                                ) / 60
                                                            ),
                                                            'M'
                                                        ) AS total_hours_minutesLATE,
                                                    CONCAT(
                                                            FLOOR(
                                                                SUM(TIME_TO_SEC(attendances.early_out)) / 3600
                                                            ),
                                                            'H:',
                                                            FLOOR(
                                                                (
                                                                    SUM(TIME_TO_SEC(attendances.early_out)) % 3600
                                                                ) / 60
                                                            ),
                                                            'M'
                                                        ) AS total_hours_minutesUndertime,
                                                    CONCAT(
                                                            FLOOR(
                                                                SUM(TIME_TO_SEC(attendances.total_work)) / 3600
                                                            ),
                                                            'H:',
                                                            FLOOR(
                                                                (
                                                                    SUM(TIME_TO_SEC(attendances.total_work)) % 3600
                                                                ) / 60
                                                            ),
                                                            'M'
                                                        ) AS total_hours_minutestotalHours
                                                FROM
                                                    employee_tb
                                                    INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                    LEFT JOIN allowancededuct_tb ON employee_tb.empid = allowancededuct_tb.id_emp
                                                    LEFT JOIN payroll_loan_tb ON employee_tb.empid = payroll_loan_tb.empid
                                
                                                WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave')  AND employee_tb.empid = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date'";
                                            }
                                                //deduct sa absent
                                                $absence_Deductions = $EmpDrate * $number_of_absent;
                                                $result = $conn->query($sql);
                                                while($row = $result->fetch_assoc()){
                                                    //Total Late   
                                                    $empLate = $row['total_hours_minutesLATE'];
                                                       
                                                       if ($Frequency === 'Monthly'){
                                   
                                                           @$salary_of_month = $salary;
                                                           if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                               @$salary_of_month = $salary_of_month - ($EmpDrate * $number_of_absent);
                                                           }
                                                           
                                                           $sss = $row_atteeee['sss_amount'];
                                                           $philHealth = $row_atteeee['philhealth_amount'];
                                                           $pagibig_amount = $row_atteeee['pagibig_amount'];
                                                           $tin_amount = $row_atteeee['tin_amount'];
                                       
                                                           $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;
                                   
                                                           $absence_Deducts = $EmpDrate * $number_of_absent;
                                       
                                                       } 
                                                       else if ($Frequency === 'Semi-Month'){
                                       
                                                           @$salary_of_month = ($salary) / 2;
                                       
                                                           if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                               @$salary_of_month = $salary_of_month - ($EmpDrate * $number_of_absent);
                                                           }
                                       
                                                           $sss = $row_atteeee['sss_amount'] / 2;      
                                                           $philHealth = $row_atteeee['philhealth_amount'] / 2;       
                                                           $pagibig_amount = $row_atteeee['pagibig_amount'] / 2;
                                                           $tin_amount = $row_atteeee['tin_amount'] / 2;
                                                           $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;   
                                                           
                                                           $absence_Deducts = $EmpDrate * $number_of_absent;
                                                       }
                                                       else if ($Frequency === 'Weekly'){
                                       
                                                           @$salary_of_month = ($salary) / 4;
                                                           
                                                           if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                               @$salary_of_month = $salary_of_month - ($EmpDrate* $number_of_absent);
                                                           }
                                       
                                                           $sss = $row_atteeee['sss_amount'] / 4;
                                                           $philHealth = $row_atteeee['philhealth_amount'] / 4; 
                                                           $pagibig_amount = $row_atteeee['pagibig_amount'] / 4;
                                                           $tin_amount = $row_atteeee['tin_amount'] / 4; 
                                                           $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;
                                   
                                                           $absence_Deducts = $EmpDrate * $number_of_absent;
                                                       }
                                                       @$cutoff_OT = ($time_OT_TOTAL);
                                                       
                                   
                                                       //government deduction
                                                       $result_governDeduct = mysqli_query($conn, "SELECT
                                                       SUM(govern_amount) AS total_sum_othe_deduct 
                                                           FROM 
                                                           `governdeduct_tb`
                                                           WHERE `id_emp`=  '$EmployeeID'");
                                                           $row_governDeduct = mysqli_fetch_assoc($result_governDeduct);
                                   
                                                               if ($Frequency === 'Monthly') {
                                                                   $cutoff_deductGovern = $row_governDeduct['total_sum_othe_deduct'];
                                                               } else if ($Frequency === 'Semi-Month') {
                                                                   $cutoff_deductGovern = $row_governDeduct['total_sum_othe_deduct'] / 2;
                                                               } else if ($Frequency === 'Weekly') {
                                                                   $cutoff_deductGovern = $row_governDeduct['total_sum_othe_deduct'] / 4;
                                                               } 
                                   
                                                           $query_deduct_onLeave = "SELECT COUNT(`status`) AS onLeaveCount FROM attendances 
                                                           WHERE `status` = 'On-Leave' 
                                                           AND `empid` = '$EmployeeID' 
                                                           AND `date` 
                                                           BETWEEN  '$str_date' AND  '$end_date'";
                                                           $result_deduct_onLeave = mysqli_query($conn, $query_deduct_onLeave);
                                   
                                                           if(mysqli_num_rows($result_deduct_onLeave) > 0){
                                                               $row_deduct_onLeave = mysqli_fetch_assoc($result_deduct_onLeave);
                                                               $number_ofLeave_attStatus =  $row_deduct_onLeave['onLeaveCount'];
                                                           }else{
                                                               $number_ofLeave_attStatus = 0;
                                                           }
                                   
                                                           //Calculation ng basic pay sa payslip
                                                           $SalaryEmp = $salary_of_month - ($EmpDrate * $number_ofLeave_attStatus);
                                   
                                                           //calculation ng paid leaves
                                                           $PaidLeaves = $EmpDrate * $number_ofLeave_attStatus;
                                   
                                                           //for counting number of LWOP niya para sa deduction info for payslip
                                                           $query_deduct_LWOP = "SELECT COUNT(`status`) AS onLWOPCount FROM attendances 
                                                                                       WHERE `status` = 'LWOP' 
                                                                                       AND `empid` = '$EmployeeID' 
                                                                                       AND `date` 
                                                                                       BETWEEN  '$str_date' AND  '$end_date'";
                                                           $result_deduct_LWOp = mysqli_query($conn, $query_deduct_LWOP);
                                   
                                   
                                                           if(mysqli_num_rows($result_deduct_LWOp) > 0){
                                                               $row_deduct_LWOP = mysqli_fetch_assoc($result_deduct_LWOp);
                                   
                                                               $number_LWOP_attStatus =  $row_deduct_LWOP['onLWOPCount'];
                                                           }else{
                                                               $number_LWOP_attStatus = 0;
                                                           }
                                                           $LWOPdeduct = $EmpDrate * $number_LWOP_attStatus;
                                   
                                                           $select_holiday_not_timein = "SELECT COUNT(`date`) as num_holiday_not_timein FROM attendances WHERE `status` = 'Present' AND time_in = '00:00:00' AND time_out = '00:00:00' AND `empid` = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND '$end_date'";
                                                           $result_holiday_not_present = mysqli_query($conn, $select_holiday_not_timein);
                                                           if(mysqli_num_rows($result_holiday_not_present) > 0){
                                                               $row_holiday_not_present = mysqli_fetch_assoc($result_holiday_not_present);
                                                               $num_holiday_not_timein = $row_emp['drate'] * $row_holiday_not_present['num_holiday_not_timein']; // for holiday paid pero d pumasok ang employee pero bayad
                                                           }else{
                                                               $num_holiday_not_timein = $row_emp['drate'] * 0;
                                                           }
                                   
                                                           //Calculation ng holiday pay
                                                           $HolidayPayment = @$holiday_rate_with_dpay + $num_holiday_not_timein;
                                   
                                                           //For total hours ng ot
                                                           $select_basic_OT = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(total_ot))) AS total_time_sum FROM overtime_tb WHERE `empid` = '$EmployeeID' AND `work_schedule` BETWEEN  '$str_date' AND  '$end_date' AND `status` = 'Approved'";
                                                           $result_basic_OT = mysqli_query($conn, $select_basic_OT);
                                   
                                                           if(mysqli_num_rows($result_basic_OT) > 0){
                                                               $row_basic_OT = mysqli_fetch_assoc($result_basic_OT);
                                                               $time = $row_basic_OT['total_time_sum'];
                                   
                                                               $timeArr = explode(':', $time);
                                                               @$hours = (int)$timeArr[0];
                                                               @$minutes = (int)$timeArr[1];
                                                               @$seconds = (int)$timeArr[2];
                                   
                                                                   $basic_OT_hours =  $hours . "H:" . $minutes . "M";
                                                           }
                                                           else{
                                                                   $basic_OT_hours = "01H:0M";
                                                           }
                                   
                                                           //calculation ng ot amount
                                                           $OTamount = $cutoff_OT + $totalOT_pay_holiday + $totalOT_pay_holiday_restday;
                                                                                                       
                                                           //calculation ng allowance
                                                           $formatted_value = $allowance * $row_atteeee['Number_of_days_work'];
                                   
                                                           if ($Frequency === 'Monthly'){
                                                               //FOR EVERY CUTOFF DEDUCTIONS
                                                               $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$EmployeeID' AND `loan_status` != 'PAID' AND `status` = 'Approved'";
                                                               $result = $conn->query($query);
                                       
                                                               $total_deductionLOAN = 0;
                                                               // Check if any rows are fetched 
                                                               if ($result->num_rows > 0) 
                                                               {
                                                                   while($row = $result->fetch_assoc()) 
                                                                   {
                                                                       echo "<br>" . $amortization =+ $row["amortization"]; 
                                                                       $total_deductionLOAN += $amortization;
                                                                   } //end While
                                                                   
                                                               }
                                                               
                                                           }else{
                                                               if($cutoffNumber === $first_cutOFf)
                                                                   {
                                                                       $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$EmployeeID' AND `loan_status` != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'First Cutoff'";
                                                                               $result = $conn->query($query);
                                       
                                                                               // Check if any rows are fetched 
                                                                               if ($result->num_rows > 0) 
                                                                               {
                                                                                       //$loan_Unpaid_array = array(); // Array to store the dates
                                                                                       //$row_L = mysqli_fetch_assoc($result);
                                                                                       while($row = $result->fetch_assoc()) 
                                                                                       {
                                                                                           //echo $loan_ID = $row["applied_cutoff"];
                                                                                           echo $amortization1 = $row["amortization"];
                                                                                           //$loan_Unpaid_array[] = array('col_ID' => $loan_ID);         
                                                                                       } //end while 
                                                                                       
                                                                               }else{
                                                                                   echo '';
                                                                               }
                                                                   }
                                                               else if($cutoffNumber === $last_cutoff)
                                                                   {
                                                                       $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$EmployeeID' AND `loan_status` != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Last Cutoff'";
                                                                       $result = $conn->query($query);
                                       
                                                                       // Check if any rows are fetched 
                                                                       if ($result->num_rows > 0) 
                                                                       {
                                                                           //$loan_Unpaid_array = array(); // Array to store the dates
                                                                           //$row_L = mysqli_fetch_assoc($result);
                                                                           while($row = $result->fetch_assoc()) 
                                                                           {
                                                                               echo $amortization2 = $row["amortization"];   
                                                                               //$loan_Unpaid_array[] = array('col_ID' => $loan_ID);         
                                                                           } //end while 
                                                                           
                                                                   
                                                                       }else{
                                                                       echo '';
                                                                       }
                                                                   }
                                                               //FOR EVERY CUTOFF DEDUCTIONS
                                                               $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$EmployeeID' AND `loan_status` != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Every Cutoff'";
                                                               $result = $conn->query($query);
                                       
                                                               // Check if any rows are fetched 
                                                               if ($result->num_rows > 0) 
                                                               {
                                                                   while($row = $result->fetch_assoc()) 
                                                                   {
                                                                       echo "<br>" . $amortization3 = $row["amortization"]; 
                                                                               
                                                                   } //end While
                                                               }
                                                           $total_deductionLOAN = @$amortization1 + @$amortization2 + @$amortization3;
                                                           }
                                   
                                                           $PayslipNetpay = "₱ " . (($salary_of_month) + $formatted_value + $cutoff_OT + @$holiday_rate_with_dpay + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein)
                                                           - ($sss + $philHealth + $tin_amount + $pagibig_amount + $cutoff_deductGovern + $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN);
                                   
                                                           //calculation ng total earning
                                                           $totalEarn = $salary_of_month + $holiday_rate_with_dpay + $formatted_value  + $cutoff_OT + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein;
                                   
                                                           //deduction sa payslip
                                                           $totalDeduct = $sss + $philHealth +  $tin_amount +  $pagibig_amount +  $cutoff_deductGovern +  $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN;
                                                        

                                              echo "<tr>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400'>$EmployeeID</td>";
                                              echo "<td style='font-weight: 400'>$EmpFullName</td>";
                                              echo "<td style='font-weight: 400'>$str_date</td>";
                                              echo "<td style='font-weight: 400'>$end_date</td>";
                                              echo "<td style='font-weight: 400;'>$salary_of_month</td>";
                                              echo "<td style='font-weight: 400; display: none;'>";
                                              if ($day_of_week === 'Monday') {
                                                  echo $mon_hour_rate;
                                              } elseif ($day_of_week === 'Tuesday') {
                                                  echo $tue_hour_rate;
                                              } elseif ($day_of_week === 'Wednesday') {
                                                  echo $weds_hour_rate;
                                              } elseif ($day_of_week === 'Thursday') {
                                                  echo $thurs_hour_rate;
                                              } elseif ($day_of_week === 'Friday') {
                                                  echo $fri_hour_rate;
                                              } elseif ($day_of_week === 'Saturday') {
                                                  echo $sat_hour_rate;
                                              } elseif ($day_of_week === 'Sunday') {
                                                  echo $sun_hour_rate;
                                              }
                                              echo "</td>";
                                              echo "<td style='font-weight: 400'>$TotalworkDays</td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400; display: none;'></td>";
                                              echo "<td style='font-weight: 400'></td>";
                                              echo "<td style='font-weight: 400'>
                                              <button class='btn btn-primary slipdetailed' data-bs-toggle='modal' data-bs-target='#detailedPayslip'>View</button></td>";
                                              echo "</tr>";
                                            }
                                          }
                                      }
                                  }
                                  ?>
                              </table>    
                          </div>

                        

            </div>
          </div>
        </div>
      </div>
   
      
<!-------------------------------------------------TABLE END------------------------------------------->
<script>
$(document).ready(function(){
    $('.slipdetailed').on('click', function(){
        $('#detailedPayslip').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        $('#dailyrate').text(data[6]);
        $('#hourlyrate').text(data[5]);
        $('#othour').text(data[8]);
        $('#otpay').text(data[11]);
        $('#holidayPay').text(data[12]);
        $('#holidayOTPay').text(data[13]);
        $('#LeavePay').text(data[14]);
        $('#absences').text(data[16]);
        $('#deductAbsences').text(data[17]);
        $('#latehour').text(data[18]);
        $('#latededuct').text(data[19]);
    });
});
</script>






<script>
 //HEADER RESPONSIVENESS SCRIPT
 
 
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
  $('.nav-link').on('click', function(e) {
    if ($(window).width() <= 390) {
      e.preventDefault();
      $(this).siblings('.sub-menu').slideToggle();
    }
  });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
  $('.nav-links').on('click', function(e) {
    if ($(window).width() <= 500) {
      e.preventDefault();
      $(this).siblings('.sub-menu').slideToggle();
    }
  });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>


    <script> 
     $('.header-dropdown-btn').click(function(){
        $('.header-dropdown .header-dropdown-menu').toggleClass("show-header-dd");
    });

//     $(document).ready(function() {
//     $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//     $('.dashboard-container').toggleClass('move-content');
  
//   });
// });
 $(document).ready(function() {
    var isHamburgerClicked = false;

    $('.navbar-toggler').click(function() {
    $('.nav-title').toggleClass('hide-title');
    // $('.dashboard-container').toggleClass('move-content');
    isHamburgerClicked = !isHamburgerClicked;

    if (isHamburgerClicked) {
      $('#schedule-list-container').addClass('move-content');
    } else {
      $('#schedule-list-container').removeClass('move-content');

      // Add class for transition
      $('#schedule-list-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#schedule-list-container').removeClass('move-content-transition');
      }, 800); // Adjust the timeout to match the transition duration
    }
  });
});
 

//     $(document).ready(function() {
//   $('.navbar-toggler').click(function() {
//     $('.nav-title').toggleClass('hide-title');
//   });
// });


    </script>


<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>

    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

           <!--skydash-->
    <script src="skydash/vendor.bundle.base.js"></script>
    <script src="skydash/off-canvas.js"></script>
    <script src="skydash/hoverable-collapse.js"></script>
    <script src="skydash/template.js"></script>
    <script src="skydash/settings.js"></script>
    <script src="skydash/todolist.js"></script>
     <script src="main.js"></script>
    <script src="bootstrap js/data-table.js"></script>


    

  
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
</html>