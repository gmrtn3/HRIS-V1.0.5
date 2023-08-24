<?php 
session_start();
//    $empid = $_SESSION['empid'];
   if (!isset($_SESSION['username'])) {
    header("Location: login.php");
} else {
    // Check if the user's role is not "admin"
    if ($_SESSION['role'] != 'admin') {
        // If the user's role is not "admin", log them out and redirect to the logout page
        session_unset();
        session_destroy();
        header("Location: logout.php");
        exit();
    } else{
        include 'config.php';
        $userId = $_SESSION['empid'];
       
        $iconResult = mysqli_query($conn, "SELECT id, emp_img_url, empid FROM employee_tb WHERE empid = '$userId'");
        $iconRow = mysqli_fetch_assoc($iconResult);

        if ($iconRow) {
            $image_url = $iconRow['emp_img_url'];
        } else {
            // Handle the case when the user ID is not found in the database
            $image_url = '../img/user.jpg'; // Set a default image or handle the situation accordingly
        }
    
    }
}
?>
<!DOCTYPE html>
<!-- PLEASE READ**: THIS IS FOR DEVELOPER THAT TRYING TO DEBUG.  -->
<!-- IF YOU SEE A "@" IN THE VARIABLE, THEN TRY TO DELETE IT AND DEBUG TO ACHIEVE YOUR OBJECTIVE. I PUT IT IN THE VARIABLE SINCE MY OBJECTIVE IS CORRECT AND WELL FUNCTION BUT IT ALWAYAS SAY UNDEFINED. -->
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
    <title>View Payroll Summary</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/gnrate_payroll.css">
    <link rel="stylesheet" href="css/gnratepayrollVIEW.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap');
    </style>


    

</head>
<body>

<header>
    <?php 
        include 'header.php';
    ?>
</header>

<style>
    .nav-tabs{
        display: flex !important; 
        flex-direction: row !important;
    }
    .pagination{
        margin-right: 63px !important;
        
    }

    .pagination li a{
        color: #c37700;
    }

        .page-item.active .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-page .page-link, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button a, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-page a {
        z-index: 3;
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    
    
    #order-listing_next{
        margin-right:14px !important;
        margin-bottom: -16px !important;

    }

</style>

<div class="container mt-5">
    <div class="card">
        <div class="card-body" style="background-color: #fff">
            


                        <!--------------------------------------- Break ----------------------------------------->

                        <div class="d-flex flex-column" style="width: 500px;">
                            <ul class="nav nav-tabs flex-column">
                                <div>
                                    <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" data-bs-toggle="tab" href="#Payslip">Payslip Details</a>
                                </li>
                                </div>
                                <div>
                                    <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#Allowance">Allowance</a>
                                </li>
                                </div>
                                
                                <div>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#Loan">Loan Details</a>
                                </li>
                                </div>
                               
                            </ul>
                        </div>



                <div class="tab-content">
                    <div class="tab-pane active" id= "Payslip">
                        <div class="table-responsive">
                            <!-- <form action="departmentEmployee.php" method="post">          -->
                                <table id="order-listing" class="table">
                                    <thead>
                                        <tr>
                                            <th>Salary Rate</th>
                                            <th>Total Late</th>
                                            <th>Total Undertime</th> 
                                            <th>Basic Hours</th>
                                            <th>Basic Pay</th>
                                            <th>Basic OT Pay</th> 
                                            <th>SSS</th> 
                                            <th>Philhealth</th>
                                            <th>Pagibig</th>
                                            <th>Tin</th>
                                            <th>Net Pay</th> 
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                            <?php 
                                                include 'config.php';

                                                if(isset($_POST['name_btnView'])){
                                                    $emp_ID = $_POST['Name_employeeID'];
                                                    $str_date = $_POST['name_cutOff_str'];
                                                    $end_date = $_POST['name_cutOff_end'];
                                                    // $freq_date = $_POST['name_cutOff_freq'];
                                                    // $freq_date = $_POST['name_cutOff_num'];


                                                 
                                                    $query_settings_salary = "SELECT * FROM settings_company_tb";
                                                    $result_settings_salary = mysqli_query($conn, $query_settings_salary);

                                                    $row_settings_salary = mysqli_fetch_assoc($result_settings_salary);



                                                    //para sa pag select sa empschedule base sa empid 
                                                    $sql_empSched = mysqli_query($conn, " SELECT
                                                                                                    *  
                                                                                                FROM
                                                                                                    empschedule_tb
                                                                                                WHERE empid = $emp_ID");
                                                                                                //need pa ma fetch sa between sa dates na naselect na month sa dropdown
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
                                                                                                            //need pa ma fetch sa between sa dates na naselect na month sa dropdown
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
                                                    //para sa pag select sa empschedule base sa empid (END)
                            
                                                        //Para sa mga range of dates per day to compute the late and undertime deduction
                                                            // -----------------------BREAK MONDAY START----------------------------//
                                                            if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){
                                                                       
                                                                $MON_timeIN = '00:00:00';
                                                                $MON_timeOUT = '01:00:00';
                                                                
                                                                $MOn_total_work = strtotime($MON_timeOUT) - strtotime($MON_timeIN) - 7200;
                                                                $MOn_total_work = date('H:i:s', $MOn_total_work);
                                                                //echo " MON_NULL " .  $MOn_total_work;

                                                               
                                                            }else{
                                                                    $MON_timeIN = $row_Sched['mon_timein'];
                                                                    $MON_timeOUT = $row_Sched['mon_timeout'];
                                                                    
                                                                    // $MOn_total_work = strtotime($MON_timeOUT) - strtotime($MON_timeIN) - 7200;
                                                                    // $MOn_total_work = date('H:i:s', $MOn_total_work);
                                                                    //echo " MON " .  $MOn_total_work;


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

                                                                       $MOn_total_work = ($mon_total_minutes_timeout - $mon_total_minutes_timein) - 1;
                                                                                                                                    
                                                            }
                                                            // -----------------------BREAK MONDAY START----------------------------//

                                                            // -----------------------BREAK Tuesday START----------------------------//
                                                            
                                                           

                                                                if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                                                                       
                                                                    $tue_timeIN = '00:00:00';
                                                                    $tue_timeout = '01:00:00';
                                                                    
                                                                    $Tue_total_work = strtotime($tue_timeout) - strtotime($tue_timeIN) - 7200;
                                                                    $Tue_total_work = date('H:i:s', $Tue_total_work);
                                                                    //echo " TUE_NULL " .  $Tue_total_work;

                                                                    
                                                                }else{
                                                                        $tue_timeIN = $row_Sched['tues_timein'];
                                                                        $tue_timeout = $row_Sched['tues_timeout'];
                                                                        
                                                                        // $Tue_total_work = strtotime($tue_timeout) - strtotime($tue_timeIN) - 7200;
                                                                        // $Tue_total_work = date('H:i:s', $Tue_total_work);
                                                                        //echo " tue " .  $Tue_total_work;

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

                                                                        $Tue_total_work = ($tue_total_minutes_timeout - $tue_total_minutes_timein) - 1;

                                                                    
                                                                }
                                                            // -----------------------BREAK Tuesday END----------------------------//

                                                             // -----------------------BREAK WEDNESDAY START----------------------------//
                                                             
                                                                    if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                                                                            
                                                                        $wed_timeIN = '00:00:00';
                                                                        $wed_timeout = '01:00:00';
                                                                        
                                                                        $wed_total_work = strtotime($wed_timeout) - strtotime($wed_timeIN) - 7200;
                                                                        $wed_total_work = date('H:i:s', $wed_total_work);
                                                                        //echo " WED_NULL " .  $wed_total_work;

                                                                       
                                                                    }else{

                                                                        $wed_timeIN = $row_Sched['wed_timein'];
                                                                        $wed_timeout = $row_Sched['wed_timeout'];
                                                                        
                                                                        // $wed_total_work = strtotime($wed_timeout) - strtotime($wed_timeIN) - 7200;
                                                                        // $wed_total_work = date('H:i:s', $wed_total_work);
                                                                        //echo " WED_ " .  $wed_total_work;

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
                                                                    }

                                                                    
                                                            // -----------------------BREAK WEDNESDAY END----------------------------//

                                                            // -----------------------BREAK THURSDAY START----------------------------//
                                                                if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){
                                                                        
                                                                    $thurs_timeIN = '00:00:00';
                                                                    $thurs_timeout = '01:00:00';
                                                                    
                                                                    $thurs_total_work = strtotime($thurs_timeout) - strtotime($thurs_timeIN) - 7200;
                                                                    $thurs_total_work = date('H:i:s', $thurs_total_work);
                                                                    //echo " Thurs_NULL " .  $thurs_total_work;

                                                                    
                                                                }else{


                                                                        $thurs_timeIN = $row_Sched['thurs_timein'];
                                                                        $thurs_timeout = $row_Sched['thurs_timeout'];

                                                                        // $thurs_total_work = strtotime($thurs_timeout) - strtotime($thurs_timeIN) - 7200;
                                                                        // $thurs_total_work = date('H:i:s', $thurs_total_work);
                                                                        // //echo " THURS " .  $thurs_total_work;

                                                                        // Your date string
                                                                        //$dateString = '2023-07-20 10:30:00';

                                                                        // Create a DateTime object from the string
                                                                       
                                                                        // Your time string
                                                                        //$timeString = '09:30';
                                                                        
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

                                                                        $thurs_total_work = ($thurs_total_minutes_timeout - $thurs_total_minutes_timein) - 1;
                                                                        
                                                                        
                                                                        
                                                                       
                                                                        

                                                                        
                                                                }
                                                            // -----------------------BREAK THURSDAY END----------------------------//


                                                            // -----------------------BREAK FRIDAY START----------------------------//
                                                        
                                                            if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){
                                                                       
                                                                $fri_timeIN = '00:00:00';
                                                                $fri_timeout = '01:00:00';
                                                                
                                                                $fri_total_work = strtotime($fri_timeout) - strtotime($fri_timeIN) - 7200;
                                                                $fri_total_work = date('H:i:s', $fri_total_work);
                                                                //echo " fri_NULL " .  $fri_total_work;

                                                                
                                                            }else{
                                                                    $fri_timeIN = $row_Sched['fri_timein'];
                                                                    $fri_timeout = $row_Sched['fri_timeout'];
                                                                    
                                                                    // $fri_total_work = strtotime($fri_timeout) - strtotime($fri_timeIN) - 7200;
                                                                    // $fri_total_work = date('H:i:s', $fri_total_work);
                                                                    //echo " fri " .  $fri_total_work;

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


                                                            // -----------------------BREAK FRIDAY END----------------------------//

                                                            
                                                            // -----------------------BREAK Saturday START----------------------------//
                                                            if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                                                                       
                                                                $sat_timeIN = '00:00:00';
                                                                $sat_timeout = '01:00:00';
                                                                
                                                                $sat_total_work = strtotime($sat_timeout) - strtotime($sat_timeIN) - 7200;
                                                                $sat_total_work = date('H:i:s', $sat_total_work);
                                                                //echo " SAT_NULL " .  $sat_total_work;

                                                                
                                                            }else{
                                                                   
                                                                    $sat_timeIN = $row_Sched['sat_timein'];
                                                                    $sat_timeout = $row_Sched['sat_timeout'];
                                                                    
                                                                    // $sat_total_work = strtotime($sat_timeout) - strtotime($sat_timeIN) - 7200;
                                                                    // $sat_total_work = date('H:i:s', $sat_total_work);
                                                                    //echo " SAT " .  $sat_total_work;


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

                                                            // -----------------------BREAK Saturday END----------------------------//
                                                    
                                                            // -----------------------BREAK SUNDAY START----------------------------//
                                                            if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                                                                       
                                                                $sun_timeIN = '00:00:00';
                                                                $sun_timeout = '01:00:00';
                                                                
                                                                $sun_total_work = strtotime($sun_timeout) - strtotime($sun_timeIN) - 7200;
                                                                $sun_total_work = date('H:i:s', $sun_total_work);
                                                                //echo " SUN_NULL " .  $sun_total_work;

                                                                
                                                            }else{
                                                                    $sun_timeIN = $row_Sched['sun_timein'];
                                                                    $sun_timeout = $row_Sched['sun_timeout'];

                                                                    
                                                                    // $sun_total_work = strtotime($sun_timeout) - strtotime($sun_timeIN) - 7200;
                                                                    // $sun_total_work = date('H:i:s', $sun_total_work);
                                                                    //echo " SUN " .  $sun_total_work;

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

                                                            // -----------------------BREAK SUNDAY END----------------------------//


                                                         


                                                             //para sa pag select sa schedule base sa schedule na fetch 
                                                                $sql_attndces = mysqli_query($conn, " SELECT
                                                                            `drate`, `otrate`, `status`,
                                                                            CONCAT(
                                                                                    employee_tb.`fname`,
                                                                                    ' ',
                                                                                    employee_tb.`lname`
                                                                                ) AS `full_name`,
                                                                                empsss,
                                                                                emptin,
                                                                                emppagibig,
                                                                                empphilhealth
                                                                    FROM employee_tb
                                                                   
                                                                    WHERE empid = $emp_ID;
                                                                ");
                                                                //need pa ma fetch sa between sa dates na naselect na month sa dropdown
                                                                if(mysqli_num_rows($sql_attndces) > 0) 
                                                                {
                                                                    $row_emp = mysqli_fetch_assoc($sql_attndces);
                                                                       // Fetch all rows from attendances_tb
                                                                    $query = "SELECT * FROM attendances WHERE `status` = 'Present' AND empid = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'";
                                                                    $result = $conn->query($query);

                                                                    // Check if any rows are fetched
                                                                    if ($result->num_rows > 0) 
                                                                    {
                                                                       
                                                                        $datesArray = array(); // Array to store the dates
                                                                       
                                                                        // Loop through each row
                                                                        while($row = $result->fetch_assoc()) 
                                                                        {
                                                                            $_late = $row["late"];
                                                                            // $UT = $row["early_out"];
                                                                            // $OT = $row["overtime"];
                                                                            // $status = $row["status"];
                                                                            $Date = $row["date"];             
                                                                            //$day_of_week = date('l', strtotime($Date));
                                                                            //echo '<br>' .  $Date . ' ' . $day_of_week . ' ' .  $_late;

                                                                            //$datesArray[] = array('late' => $_late, 'date' => $Date, 'underTime' => $UT, 'OT' => $OT); // Append the fetched date and late and Undertime and Overtime value to the array
                                                                            $datesArray[] = array('late' => $_late, 'date' => $Date);
                                                                            //echo '<br>' . $_late;
                                                                        } //end while
                                                                       
                                                                        foreach ($datesArray as $date_att) 
                                                                            {
                                                                                
                                                                            $day_of_week = date('l', strtotime($date_att['date']));
                                                                            //echo '<br>' . $date_att['date'] . ' ' . $day_of_week . ' ' . $date_att['late'] .' ' . $date_att['underTime'] . ' ' . $date_att['OT'];
                                                                            //echo '<br>' . $_late;
                                                                           
                                                                            
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
                                                                                
                                                                            } else if($day_of_week === 'Tuesday'){
                                                                                if($Tue_total_work === '00:00:00'){
                                                                                    $Tue_TO_DEDUCT_LATE = 0;
                                                                                    // $Tue_TO_DEDUCT_UT = 0;
                                                                                    // $Tue_ToADD_OT = 0;
                                                                                }else{
                                                                                
                                                                                    
                                                                                    $tue_emp_dailyRate =  $row_emp['drate'];
                                                                                    $tue_emp_OtRate = $row_emp['otrate'];

                                                                                    $tue_total_work_hours = (int)substr($Tue_total_work, 0, 2);
                                                                                    $tue_hour_rate =  $tue_emp_dailyRate / $tue_total_work_hours;
                                                                                    $tue_minute_rate = $tue_hour_rate / 60; 

                                                                                    $tue_timeString = $date_att['late'];
                                                                                    // $tue_timeString_UT = $date_att['underTime'];
                                                                                    // $tue_timeString_OT = $date_att['OT'];

                                                                                    $tue_time = DateTime::createFromFormat('H:i:s', $tue_timeString);// Convert time string to DateTime object
                                                                                    // $tue_time_UT = DateTime::createFromFormat('H:i:s', $tue_timeString_UT);// Convert time string to DateTime object
                                                                                    // $tue_time_OT = DateTime::createFromFormat('H:i:s', $tue_timeString_OT);// Convert time string to DateTime object


                                                                                     //For latee
                                                                                     $tue_lateH = $tue_time->format('H');// Extract minutes from DateTime object
                                                                                     $tue_lateM = $tue_time->format('i');// Extract minutes from DateTime object
                                                                                     $tue_totalMinutes = intval($tue_lateM);// Convert minutes to integer
                                                                                     $tue_totalhours = intval($tue_lateH);// Convert minutes to integer
                                                                                     @$tue_LATE_hours += $tue_totalhours * $tue_hour_rate;//minutes to deduct
                                                                                     @$tue_LATE_minutes += $tue_totalMinutes * $tue_minute_rate;//minutes to deduct
                                                                                     @$Tue_TO_DEDUCT_LATE =  @$tue_LATE_hours +  @$tue_LATE_minutes;


                                                                                    //for Undertime
                                                                                    // $tue_hour= $tue_time_UT->format('H');// Extract Hour from DateTime object
                                                                                    // $tue_totalHour = intval($tue_hour);
                                                                                    // @$Tue_TO_DEDUCT_UT += $tue_totalHour * $tue_hour_rate;


                                                                                    //for Overtime
                                                                                    // $tue_hour_OT = $tue_time_OT->format('H');// Extract Hour from DateTime object
                                                                                    // $tue_totalHour_OT = intval($tue_hour_OT);
                                                                                    // @$Tue_ToADD_OT += $tue_emp_OtRate *  $tue_totalHour_OT;

                                                                                    // echo $lateH = $time->format('H');// Extract minutes from DateTime object
                                                                                    // echo $lateM = $time->format('i');// Extract minutes from DateTime object

                                                                                    // $minutes = $lateH + $lateM;


                                                                                    // $hour= $time_UT->format('H');// Extract Hour from DateTime object
                                                                                    // $hour_OT = $time_OT->format('H');// Extract Hour from DateTime object
                                                                                    // $totalMinutes = intval($minutes);// Convert minutes to integer
                                                                                    // $totalHour = intval($hour);
                                                                                    // $totalHour_OT = intval($hour_OT);
                                                                                    // @$Tue_ToADD_OT += $emp_OtRate *  $totalHour_OT;
                                                                                    // @$Tue_TO_DEDUCT_LATE += $totalMinutes * $minute_rate;
                                                                                    // @$Tue_TO_DEDUCT_UT += $totalHour * $hour_rate;
                                                                                }
                                                                            } else if($day_of_week === 'Wednesday'){
                                                                                if($wed_total_work === '00:00:00'){
                                                                                    $WED_TO_DEDUCT_LATE = 0;
                                                                                    // $WED_TO_DEDUCT_UT =  0;
                                                                                    // $WED_ToADD_OT = 0;
                                                                                }else{
                                                                                    
                                                                                    
                                                                                    $weds_emp_dailyRate =  $row_emp['drate'];
                                                                                    $weds_emp_OtRate = $row_emp['otrate'];

                                                                                    $weds_total_work_hours = (int)substr($wed_total_work, 0, 2);
                                                                                    $weds_hour_rate =  $weds_emp_dailyRate / $weds_total_work_hours;
                                                                                    $weds_minute_rate = $weds_hour_rate / 60; 

                                                                                    
                                                                                    $weds_timeString =$date_att['late'];
                                                                                    // $weds_timeString_UT = $date_att['underTime'];
                                                                                    // $weds_timeString_OT = $date_att['OT'];

                                                                                    $weds_time = DateTime::createFromFormat('H:i:s', $weds_timeString);// Convert time string to DateTime object
                                                                                    // $weds_time_UT = DateTime::createFromFormat('H:i:s', $weds_timeString_UT);// Convert time string to DateTime object
                                                                                    // $weds_time_OT = DateTime::createFromFormat('H:i:s', $weds_timeString_OT);// Convert time string to DateTime object


                                                                                   //For latee
                                                                                   $weds_lateH = $weds_time->format('H');// Extract minutes from DateTime object
                                                                                   $weds_lateM = $weds_time->format('i');// Extract minutes from DateTime object
                                                                                   $weds_totalMinutes = intval($weds_lateM);// Convert minutes to integer
                                                                                   $weds_totalhours = intval($weds_lateH);// Convert minutes to integer
                                                                                   @$weds_TO_DEDUCT_LATE_hours += $weds_totalhours * $weds_hour_rate;//minutes to deduct
                                                                                   @$weds_TO_DEDUCT_LATE_minutes += $weds_totalMinutes * $weds_minute_rate;//minutes to deduct
                                                                                   @$WED_TO_DEDUCT_LATE =  @$weds_TO_DEDUCT_LATE_hours +  @$weds_TO_DEDUCT_LATE_minutes;


                                                                                  //for Undertime
                                                                                //   $weds_hour= $weds_time_UT->format('H');// Extract Hour from DateTime object
                                                                                //   $weds_totalHour = intval($weds_hour);
                                                                                //   @$WED_TO_DEDUCT_UT += $weds_totalHour * $weds_hour_rate;


                                                                                  //for Overtime
                                                                                //   $weds_hour_OT = $weds_time_OT->format('H');// Extract Hour from DateTime object
                                                                                //   $weds_totalHour_OT = intval($weds_hour_OT);
                                                                                //   @$WED_ToADD_OT += $weds_emp_OtRate *  $weds_totalHour_OT;

                                                                                    // $minutes = $time->format('i');// Extract minutes from DateTime object
                                                                                    // $hour= $time_UT->format('H');// Extract Hour from DateTime object
                                                                                    // $hour_OT = $time_OT->format('H');// Extract Hour from DateTime object
                                                                                    // $totalMinutes = intval($minutes);// Convert minutes to integer
                                                                                    // $totalHour = intval($hour);
                                                                                    // $totalHour_OT = intval($hour_OT);
                                                                                    // @$WED_ToADD_OT += $emp_OtRate *  $totalHour_OT; 
                                                                                    // @$WED_TO_DEDUCT_LATE += $totalMinutes * $minute_rate;
                                                                                    // @$WED_TO_DEDUCT_UT += $totalHour * $hour_rate;
                                                                                }
                                                                            } else if($day_of_week === 'Thursday'){
                                                                                if($thurs_total_work === '00:00:00'){
                                                                                    $Thurs_TO_DEDUCT_LATE = 0;
                                                                                    // $Thurs_TO_DEDUCT_UT = 0;
                                                                                    // $Thurs_ToADD_OT = 0;
                                                                                }else{
                                                                                
                                                                                    $thurs_emp_dailyRate =  $row_emp['drate'];
                                                                                    $thurs_emp_OtRate = $row_emp['otrate'];

                                                                                    $thurs_total_work_hours = (int)substr($thurs_total_work, 0, 2);
                                                                                    $thurs_hour_rate =  $thurs_emp_dailyRate / $thurs_total_work_hours;
                                                                                    $thurs_minute_rate = $thurs_hour_rate / 60; 

                                                                                    $thurs_timeString =$date_att['late'];
                                                                                    // $thurs_timeString_UT = $date_att['underTime'];
                                                                                    // $thurs_timeString_OT = $date_att['OT'];

                                                                                    $thurs_time = DateTime::createFromFormat('H:i:s', $thurs_timeString);// Convert time string to DateTime object
                                                                                    // $thurs_time_UT = DateTime::createFromFormat('H:i:s', $thurs_timeString_UT);// Convert time string to DateTime object
                                                                                    // $thurs_time_OT = DateTime::createFromFormat('H:i:s', $thurs_timeString_OT);// Convert time string to DateTime object

                                                                                     //For latee
                                                                                   $thurs_lateH = $thurs_time->format('H');// Extract minutes from DateTime object
                                                                                   $thurs_lateM = $thurs_time->format('i');// Extract minutes from DateTime object
                                                                                   $thurs_totalMinutes = intval($thurs_lateM);// Convert minutes to integer
                                                                                   $thurs_totalhours = intval($thurs_lateH);// Convert minutes to integer
                                                                                   @$thurs_TO_DEDUCT_LATE_hours += $thurs_totalhours * $thurs_hour_rate;//minutes to deduct
                                                                                   @$thurs_TO_DEDUCT_LATE_minutes += $thurs_totalMinutes * $thurs_minute_rate;//minutes to deduct
                                                                                   @$Thurs_TO_DEDUCT_LATE =  @$thurs_TO_DEDUCT_LATE_hours +  @$thurs_TO_DEDUCT_LATE_minutes;


                                                                                  //for Undertime
                                                                                //   $thurs_hour= $thurs_time_UT->format('H');// Extract Hour from DateTime object
                                                                                //   $thurs_totalHour = intval($thurs_hour);
                                                                                //   @$Thurs_TO_DEDUCT_UT += $thurs_totalHour * $thurs_hour_rate;


                                                                                  //for Overtime
                                                                                //   $thurs_hour_OT = $thurs_time_OT->format('H');// Extract Hour from DateTime object
                                                                                //   $thurs_totalHour_OT = intval($thurs_hour_OT);
                                                                                //   @$Thurs_ToADD_OT += $thurs_emp_OtRate *  $thurs_totalHour_OT;

                                                                                    // $minutes = $time->format('i');// Extract minutes from DateTime object
                                                                                    // $hour= $time_UT->format('H');// Extract Hour from DateTime object
                                                                                    // $hour_OT = $time_OT->format('H');// Extract Hour from DateTime object
                                                                                    // $totalMinutes = intval($minutes);// Convert minutes to integer
                                                                                    // $totalHour = intval($hour);
                                                                                    // $totalHour_OT = intval($hour_OT);
                                                                                    // @$Thurs_ToADD_OT += $emp_OtRate *  $totalHour_OT;
                                                                                    // @$Thurs_TO_DEDUCT_LATE += $totalMinutes * $minute_rate;
                                                                                    // @$Thurs_TO_DEDUCT_UT += $totalHour * $hour_rate;
                                                                                    
                                                                                }
                                                                            } else if($day_of_week === 'Friday'){
                                                                                if($fri_total_work === '00:00:00'){
                                                                                    $Fri_TO_DEDUCT_LATE = 0;
                                                                                    // $Fri_TO_DEDUCT_UT = 0;
                                                                                    // $Fri_ToADD_OT = 0;
                                                                                }else{
                                                                                
                                                                                    $fri_emp_dailyRate =  $row_emp['drate'];
                                                                                    $fri_emp_OtRate = $row_emp['otrate'];

                                                                                    $fri_total_work_hours = (int)substr($fri_total_work, 0, 2);
                                                                                    $fri_hour_rate =  $fri_emp_dailyRate / $fri_total_work_hours;
                                                                                    $fri_minute_rate = $fri_hour_rate / 60; 

                                                                                    $fri_timeString =$date_att['late'];
                                                                                    // $fri_timeString_UT = $date_att['underTime'];
                                                                                    // $fri_timeString_OT = $date_att['OT'];

                                                                                    $fri_time = DateTime::createFromFormat('H:i:s', $fri_timeString);// Convert time string

                                                                                    
                                                                                    // $fri_time_UT = DateTime::createFromFormat('H:i:s', $fri_timeString_UT);// Convert time string to DateTime object
                                                                                    // $fri_time_OT = DateTime::createFromFormat('H:i:s', $fri_timeString_OT);// Convert time string to DateTime object

                                                                                      //For latee
                                                                                   $fri_lateH = $fri_time->format('H');// Extract minutes from DateTime object
                                                                                   $fri_lateM = $fri_time->format('i');// Extract minutes from DateTime object
                                                                                   $fri_totalMinutes = intval($fri_lateM);// Convert minutes to integer
                                                                                   $fri_totalhours = intval($fri_lateH);// Convert minutes to integer
                                                                                   @$fri_TO_DEDUCT_LATE_hours += $fri_totalhours * $fri_hour_rate;//minutes to deduct
                                                                                   @$fri_TO_DEDUCT_LATE_minutes += $fri_totalMinutes * $fri_minute_rate;//minutes to deduct
                                                                                   @$Fri_TO_DEDUCT_LATE =  @$fri_TO_DEDUCT_LATE_hours +  @$fri_TO_DEDUCT_LATE_minutes;


                                                                                  //for Undertime
                                                                                //   $fri_hour= $fri_time_UT->format('H');// Extract Hour from DateTime object
                                                                                //   $fri_totalHour = intval($fri_hour);
                                                                                //   @$Fri_TO_DEDUCT_UT += $fri_totalHour * $fri_hour_rate;


                                                                                  //for Overtime
                                                                                //   $fri_hour_OT = $fri_time_OT->format('H');// Extract Hour from DateTime object
                                                                                //   $fri_totalHour_OT = intval($fri_hour_OT);
                                                                                //   @$Fri_ToADD_OT += $emp_OtRate *  $totalHour_OT;

                                                                                    // $minutes = $time->format('i');// Extract minutes from DateTime object
                                                                                    // $hour= $time_UT->format('H');// Extract Hour from DateTime object
                                                                                    // $hour_OT = $time_OT->format('H');// Extract Hour from DateTime object
                                                                                    // $totalMinutes = intval($minutes);// Convert minutes to integer
                                                                                    // $totalHour = intval($hour);
                                                                                    // $totalHour_OT = intval($hour_OT);
                                                                                    // @$Fri_ToADD_OT += $emp_OtRate *  $totalHour_OT;
                                                                                    // @$Fri_TO_DEDUCT_LATE += $totalMinutes * $minute_rate;
                                                                                    // @$Fri_TO_DEDUCT_UT += $totalHour * $hour_rate; 
                                                                                }
                                                                            } else if($day_of_week === 'Saturday'){
                                                                                if($sat_total_work === '00:00:00'){
                                                                                    $SAT_TO_DEDUCT_LATE = 0;
                                                                                    // $SAT_TO_DEDUCT_UT = 0;
                                                                                    // $SAT_ToADD_OT = 0;
                                                                                }else{
                                                                                
                                                                                    $sat_emp_dailyRate =  $row_emp['drate'];
                                                                                    $sat_emp_OtRate = $row_emp['otrate'];

                                                                                    $sat_total_work_hours = (int)substr($sat_total_work, 0, 2);
                                                                                    $sat_hour_rate =  $sat_emp_dailyRate / $sat_total_work_hours;
                                                                                    $sat_minute_rate = $sat_hour_rate / 60; 

                                                                                    $sat_timeString =$date_att['late'];
                                                                                    // $sat_timeString_UT = $date_att['underTime'];
                                                                                    // $sat_timeString_OT = $date_att['OT'];

                                                                                    $sat_time = DateTime::createFromFormat('H:i:s', $sat_timeString);// Convert time string to DateTime object
                                                                                    // $sat_time_UT = DateTime::createFromFormat('H:i:s', $sat_timeString_UT);// Convert time string to DateTime object
                                                                                    // $sat_time_OT = DateTime::createFromFormat('H:i:s', $sat_timeString_OT);// Convert time string to DateTime object


                                                                                       //For latee
                                                                                   $sat_lateH = $sat_time->format('H');// Extract minutes from DateTime object
                                                                                   $sat_lateM = $sat_time->format('i');// Extract minutes from DateTime object
                                                                                   $sat_totalMinutes = intval($sat_lateM);// Convert minutes to integer
                                                                                   $sat_totalhours = intval($sat_lateH);// Convert minutes to integer
                                                                                   @$sat_TO_DEDUCT_LATE_hours += $sat_totalhours * $sat_hour_rate;//minutes to deduct
                                                                                   @$sat_TO_DEDUCT_LATE_minutes += $sat_totalMinutes * $sat_minute_rate;//minutes to deduct
                                                                                   @$SAT_TO_DEDUCT_LATE =  @$sat_TO_DEDUCT_LATE_hours +  @$sat_TO_DEDUCT_LATE_minutes;


                                                                                  //for Undertime
                                                                                //   $sat_hour= $sat_time_UT->format('H');// Extract Hour from DateTime object
                                                                                //   $sat_totalHour = intval($sat_hour);
                                                                                //   @$SAT_TO_DEDUCT_UT += $sat_totalHour * $sat_hour_rate;


                                                                                  //for Overtime
                                                                                //   $sat_hour_OT = $sat_time_OT->format('H');// Extract Hour from DateTime object
                                                                                //   $sat_totalHour_OT = intval($sat_hour_OT);
                                                                                //   @$SAT_ToADD_OT += $sat_emp_OtRate *  $sat_totalHour_OT;

                                                                                    // $minutes = $time->format('i');// Extract minutes from DateTime object
                                                                                    // $hour= $time_UT->format('H');// Extract Hour from DateTime object
                                                                                    // $hour_OT = $time_OT->format('H');// Extract Hour from DateTime object
                                                                                    // $totalMinutes = intval($minutes);// Convert minutes to integer
                                                                                    // $totalHour = intval($hour);
                                                                                    // $totalHour_OT = intval($hour_OT);
                                                                                    // @$SAT_ToADD_OT += $emp_OtRate *  $totalHour_OT;
                                                                                    // @$SAT_TO_DEDUCT_LATE += $totalMinutes * $minute_rate;
                                                                                    // @$SAT_TO_DEDUCT_UT += $totalHour * $hour_rate; 
                                                                                }
                                                                            } else if($day_of_week === 'Sunday'){
                                                                                if($sun_total_work === '00:00:00'){
                                                                                    $Sun_TO_DEDUCT_LATE = 0;
                                                                                    // $Sun_TO_DEDUCT_UT = 0; 
                                                                                    // $Sun_ToADD_OT = 0;
                                                                                }else{
                                                                                
                                                                                    $sun_emp_dailyRate =  $row_emp['drate'];
                                                                                    $sun_emp_OtRate = $row_emp['otrate'];

                                                                                    $sun_total_work_hours = (int)substr($sun_total_work, 0, 2);
                                                                                    $sun_hour_rate =  $sun_emp_dailyRate / $sun_total_work_hours;
                                                                                    $sun_minute_rate = $sun_hour_rate / 60; 

                                                                                    $sun_timeString =$date_att['late'];
                                                                                    // $sun_timeString_UT = $date_att['underTime'];
                                                                                    // $sun_timeString_OT = $date_att['OT'];

                                                                                    $sun_time = DateTime::createFromFormat('H:i:s', $sun_timeString);// Convert time string to DateTime object
                                                                                    // $sun_time_UT = DateTime::createFromFormat('H:i:s', $sun_timeString_UT);// Convert time string to DateTime object
                                                                                    // $sun_time_OT = DateTime::createFromFormat('H:i:s', $sun_timeString_OT);// Convert time string to DateTime object


                                                                                        //For latee
                                                                                   $sun_lateH = $sun_time->format('H');// Extract minutes from DateTime object
                                                                                   $sun_lateM = $sun_time->format('i');// Extract minutes from DateTime object
                                                                                   $sun_totalMinutes = intval($sun_lateM);// Convert minutes to integer
                                                                                   $sun_totalhours = intval($sun_lateH);// Convert minutes to integer
                                                                                   @$sun_TO_DEDUCT_LATE_hours += $sun_totalhours * $sun_hour_rate;//minutes to deduct
                                                                                   @$sun_TO_DEDUCT_LATE_minutes += $sun_totalMinutes * $sun_minute_rate;//minutes to deduct
                                                                                   @$Sun_TO_DEDUCT_LATE =  @$sun_TO_DEDUCT_LATE_hours +  @$sun_TO_DEDUCT_LATE_minutes;


                                                                                  //for Undertime
                                                                                //   $sun_hour= $sun_time_UT->format('H');// Extract Hour from DateTime object
                                                                                //   $sun_totalHour = intval($sun_hour);
                                                                                //   @$Sun_TO_DEDUCT_UT += $sun_totalHour * $sun_hour_rate;


                                                                                  //for Overtime
                                                                                //   $sun_hour_OT = $sun_time_OT->format('H');// Extract Hour from DateTime object
                                                                                //   $sun_totalHour_OT = intval($sun_hour_OT);
                                                                                //   @$Sun_ToADD_OT += $sun_emp_OtRate *  $sun_totalHour_OT;

                                                                                    // $minutes = $time->format('i');// Extract minutes from DateTime object
                                                                                    // $hour= $time_UT->format('H');// Extract Hour from DateTime object
                                                                                    // $hour_OT = $time_OT->format('H');// Extract Hour from DateTime object
                                                                                    // $totalMinutes = intval($minutes);// Convert minutes to integer
                                                                                    // $totalHour = intval($hour);
                                                                                    // $totalHour_OT = intval($hour_OT);
                                                                                    // @$Sun_ToADD_OT += $emp_OtRate *  $totalHour_OT;
                                                                                    // @$Sun_TO_DEDUCT_LATE += $totalMinutes * $minute_rate;
                                                                                    // @$Sun_TO_DEDUCT_UT += $totalHour * $hour_rate; 
                                                                                }
                                                                            }
                                                                        
                                                                        }//end for each
                                                                    }
                                                                       
                                                                     else {
                                                                        echo "You cannot generate a payslip for employee with no attendance";
                                                                    }
                                                                    
                                                                    
                                                                } else {
                                                                    echo "No results found ";
                                                                } //END SQL ATTNDCES

                                                                
                                                               
                                                                 //Computation of total additional of OVERTIME

                                                                 $sql_OT = "SELECT * FROM `overtime_tb` WHERE `empid` = '$emp_ID' AND `status` = 'Approved' AND `work_schedule` BETWEEN '$str_date' AND '$end_date'";
                                                                 $result = $conn->query($sql_OT);
                                                                 
                                                                 if ($result->num_rows > 0) {
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
                                                                 //Computation of total addtional of OVERTIME END
                                                                    
                                                                

                                                                 $sql_UT = "SELECT * FROM `undertime_tb` WHERE `empid` = '$emp_ID' AND `status` = 'Approved' AND `date` BETWEEN '$str_date' AND '$end_date'";
                                                                 $result = $conn->query($sql_UT);
                                                                 
                                                                 if ($result->num_rows > 0) {
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

                                                                 //Computation of total deduction of LATE AND UNDERTIME (END)

                                                                     //Computation of total add Overtime
                                                                //  $TOTAL_ADD_OT = @$MONDAY_ToADD_OT + @$Tue_ToADD_OT + @$WED_ToADD_OT + @$Thurs_ToADD_OT + @$Fri_ToADD_OT + @$SAT_ToADD_OT + @$Sun_ToADD_OT;
                                                                     //Computation of total add Overtime (END)


                                                        //para sa pag select sa attendances at employee para sa modal ng payslip
                                                        if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                            
                                                            $sql_attendanaaa = mysqli_query($conn, " SELECT
                                                            employee_tb.`empbsalary` AS Salary_of_Month,
                                                            employee_tb.`sss_amount`,
                                                            employee_tb.`tin_amount`,
                                                            employee_tb.`pagibig_amount`,
                                                            employee_tb.`philhealth_amount`,
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
                                                                    ' Hour/s'
                                                                ) AS total_hoursOT,
                                                            COUNT(attendances.`status`) AS Number_of_days_work
                                                            FROM
                                                            employee_tb
                                                            INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                            WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND employee_tb.empid = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'");
    
                                                        }else{
                                                            $sql_attendanaaa = mysqli_query($conn, " SELECT
                                                            SUM(employee_tb.`drate`) AS Salary_of_Month,
                                                            employee_tb.`sss_amount`,
                                                            employee_tb.`tin_amount`,
                                                            employee_tb.`pagibig_amount`,
                                                            employee_tb.`philhealth_amount`,
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
                                                                    ' Hour/s'
                                                                ) AS total_hoursOT,
                                                            COUNT(attendances.`status`) AS Number_of_days_work
                                                            FROM
                                                            employee_tb
                                                            INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                            WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND employee_tb.empid = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'");
    
                                                        }
                                                        

                                                        
                                                        if(mysqli_num_rows($sql_attendanaaa) > 0) {
                                                        $row_atteeee= mysqli_fetch_assoc($sql_attendanaaa);
                                                        } else {
                                                        echo "No results found schedule."; 
                                                        } 



                                                                     
                                                //Montly allowance
                                                $result_allowance = mysqli_query($conn, " SELECT
                                                    SUM(allowance_amount) AS total_sum_addAllowance
                                                FROM 
                                                `allowancededuct_tb` 
                                                WHERE `id_emp`=  '$emp_ID'");
                                                $row_addAllowance = mysqli_fetch_assoc($result_allowance);

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
                                    
                                    // Print the dates
                                    @$working_days = 0;
                                    foreach ($dates as $date) {

                                        $day_of_week_allowance = date('l', strtotime($date));//convert the each date to day
                                        // echo $date . " = " . $day_of_week ."<br> <br>";

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

                                    if ($_POST['name_cutOff_freq'] === 'Monthly'){

                                        $allowance = ($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / $working_days;
                                        $allowance = number_format($allowance, 2); //convert into two decimal only
                                        $allowance = str_replace(',', '', $allowance); // Remove comma

                                    } 
                                    else if ($_POST['name_cutOff_freq'] === 'Semi-Month'){

                                    $allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 2) / $working_days;
                                    $allowance = number_format($allowance, 2); //convert into two decimal only
                                    $allowance = str_replace(',', '', $allowance); // Remove comma
                                                                            
                                    }
                                    else if ($_POST['name_cutOff_freq'] === 'Weekly'){

                                        $allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 4) / $working_days;
                                        $allowance = number_format($allowance, 2); //convert into two decimal only
                                        $allowance = str_replace(',', '', $allowance); // Remove comma
                                               
                                    }


//0---------------------------------------------------PARA SA HOLIDAY RATE-----------------------------------------------------

//CHECK IF REGULAR NA SIYA OR HINDI
$result_EMP_classification = mysqli_query($conn, " SELECT
    employee_tb.classification,
    classification_tb.classification AS  employee_classification

FROM 
    `employee_tb` 
INNER JOIN 
    `classification_tb` 
ON
    employee_tb.classification = classification_tb.id
WHERE employee_tb.empid=  '$emp_ID'");
$row_emp_classification = mysqli_fetch_assoc($result_EMP_classification);



if($row_emp_classification['employee_classification'] != 'Internship/OJT'){
            //--------------------------------------REGULAR HOLIDAY-------------------------------------------------
    //CHECK lahat ng attendance niya if may holiday


$sql_att_all = "SELECT
                *
            FROM 
                `attendances` 
            WHERE 
                (`status` = 'Present' 
            OR 
                `status` = 'On-Leave') 
            AND 
                `empid` = '$emp_ID'
            AND 
                `date` 
            BETWEEN  
                '$str_date' 
            AND  
                '$end_date'";
            
            $result = $conn->query($sql_att_all);
                                                                 
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
                            LIMIT 1
                            
                            ");

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
                                                          

                           }// END OF IF STATEMENT OF "$validation_eligible_holiday === 'YES'"

                            


                    //-----------------------END COMPUTATION FOR HOLIDAY PAY IF  $validation_eligible_holiday = 'YES'--------------------

                        }

                    } // end FOr each
                } //end $sql_att_all
                //--------------------------------------REGULAR HOLIDAY END -------------------------------------------------



              
} // end FOR ALL NOT INTERN CLASSIFICATION

// echo "<br> HOliday pay: " .  $double_pay_holiday;

// echo "<br> HOliday OT pay: " .   $totalOT_pay_holiday;

// echo "<br> holiday restday: " .  $double_pay_holiday_restday;
// echo "<br> HOliday OT pay RESTDAY: " .    $totalOT_pay_holiday_restday;


@$holiday_rate_with_dpay = $double_pay_holiday + $double_pay_holiday_restday;
@$holiday_rate_with_dpay_OT = $totalOT_pay_holiday + $totalOT_pay_holiday_restday;

//--------------------------------------------------- PARA SA HOLIDAY RATE  EEEENNNNNNDDDDDD -----------------------------------------------------


    include 'Data Controller/Payroll/check_holiday_toDEduct.php'; //Para mag check ilan ang date ng may holiday para ma minus sa salary at d magdoble ang salary
    // echo "<br> To deduct Salary:" . $value_of_drate_holiday;
    // echo "<br> To deduct OT rate:" . $value_of_drate_holiday_OT;
    // echo "<br> HOliday dates number: " .  $num_days_holiday;
    // echo "<br> Total hours OT" . $OTHOUR;   


    $row_holiday_to_deduct_holiday = $row_emp['drate'] * $num_days_holiday; // dito ako nahinto dapat mabawasan ko sa mga date daily mga pinasok na holiday

//PARA SA PAG GET NG TOTAL UNDERTIME NG EMPLOYEE 
                        $UT_time = "0 hour/s 0 minutes/s";
                        $result_table_UT = mysqli_query($conn, " SELECT
                           CONCAT(
                                FLOOR(
                                    SUM(TIME_TO_SEC(total_undertime)) / 3600
                                    ),
                                    ' hour/s ',
                                FLOOR(
                                    (
                                    SUM(TIME_TO_SEC(total_undertime)) % 3600
                                    ) / 60
                                ),
                                    ' minute/s'
                            ) AS total_hours_minutesUndertime
                        FROM 
                            `undertime_tb` 
                        WHERE `empid` = '$emp_ID' AND `date` BETWEEN  '$str_date' AND  '$end_date' AND `status` = 'Approved'");

                        if(mysqli_num_rows($result_table_UT) > 0) {
                            $row_table_UT = mysqli_fetch_assoc($result_table_UT);
                            $UT_time = $row_table_UT['total_hours_minutesUndertime'];                           
                            
                        }
//PARA SA PAG GET NG TOTAL UNDERTIME NG EMPLOYEE END 
                                                if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                        $sql = "SELECT
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
                                                                ' hour/s ',
                                                                FLOOR(
                                                                    (
                                                                        SUM(TIME_TO_SEC(attendances.late)) % 3600
                                                                    ) / 60
                                                                ),
                                                                ' minute/s'
                                                            ) AS total_hours_minutesLATE,
                                                        CONCAT(
                                                                FLOOR(
                                                                    SUM(TIME_TO_SEC(attendances.early_out)) / 3600
                                                                ),
                                                                ' hour/s ',
                                                                FLOOR(
                                                                    (
                                                                        SUM(TIME_TO_SEC(attendances.early_out)) % 3600
                                                                    ) / 60
                                                                ),
                                                                ' minute/s'
                                                            ) AS total_hours_minutesUndertime,
                                                        CONCAT(
                                                                FLOOR(
                                                                    SUM(TIME_TO_SEC(attendances.total_work)) / 3600
                                                                    
                                                                ),
                                                                ' hour/s ',
                                                                FLOOR(
                                                                    (
                                                                        SUM(TIME_TO_SEC(attendances.total_work)) % 3600
                                                                       
                                                                    ) / 60
                                                                ),
                                                                ' minute/s'
                                                            ) AS total_hours_minutestotalHours
                                                    FROM
                                                        employee_tb
                                                    INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                    WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave')  AND employee_tb.empid = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'
                                                            ";

                                                    $sql_absent_count = "SELECT 
                                                                            COUNT(`status`) as Absent_count
                                                                         FROM attendances
                                                                         WHERE (`status` = 'Absent' OR `status` = 'LWOP')  AND empid = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'";

                                                    $result_absent_count = mysqli_query($conn, $sql_absent_count);
                                                    $row_absent_count = mysqli_fetch_assoc($result_absent_count);
                                                    $number_of_absent =  $row_absent_count['Absent_count'];
                                                     


                                                }else{
                                                        $sql = "SELECT
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
                                                                ' hour/s ',
                                                                FLOOR(
                                                                    (
                                                                        SUM(TIME_TO_SEC(attendances.late)) % 3600
                                                                    ) / 60
                                                                ),
                                                                ' minute/s'
                                                            ) AS total_hours_minutesLATE,
                                                        CONCAT(
                                                                FLOOR(
                                                                    SUM(TIME_TO_SEC(attendances.early_out)) / 3600
                                                                ),
                                                                ' hour/s ',
                                                                FLOOR(
                                                                    (
                                                                        SUM(TIME_TO_SEC(attendances.early_out)) % 3600
                                                                    ) / 60
                                                                ),
                                                                ' minute/s'
                                                            ) AS total_hours_minutesUndertime,
                                                        CONCAT(
                                                                FLOOR(
                                                                    SUM(TIME_TO_SEC(attendances.total_work)) / 3600
                                                                ),
                                                                ' hour/s ',
                                                                FLOOR(
                                                                    (
                                                                        SUM(TIME_TO_SEC(attendances.total_work)) % 3600
                                                                    ) / 60
                                                                ),
                                                                ' minute/s'
                                                            ) AS total_hours_minutestotalHours
                                                    FROM
                                                        employee_tb
                                                    INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                                    WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave')  AND employee_tb.empid = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'
                                                            ";
                                                }
                                                    
                                                $result = $conn->query($sql);

                                                

                                                //read data
                                                while($row = $result->fetch_assoc()){

                                                    if ($_POST['name_cutOff_freq'] === 'Monthly'){

                                                        @$salary_of_month = $row['Salary_of_Month'];
                                                        if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                            @$salary_of_month = $salary_of_month - ($row_emp['drate'] * $number_of_absent);
                                                        }
                                                        
                                                        $sss = $row['sss_amount'];
                                                        $philHealth = $row['philhealth_amount'];
                                                        $pagibig_amount = $row['pagibig_amount'];
                                                        $tin_amount = $row['tin_amount'];

                                                        $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;
                
                                                    } 
                                                    else if ($_POST['name_cutOff_freq'] === 'Semi-Month'){
                
                                                        @$salary_of_month = ($row['Salary_of_Month']) / 2;

                                                        if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                            @$salary_of_month = $salary_of_month - ($row_emp['drate'] * $number_of_absent);
                                                        }

                                                        $sss = $row['sss_amount'] / 2;      
                                                        $philHealth = $row['philhealth_amount'] / 2;       
                                                        $pagibig_amount = $row['pagibig_amount'] / 2;
                                                        $tin_amount = $row['tin_amount'] / 2;
                                                        $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;              
                                                    }
                                                    else if ($_POST['name_cutOff_freq'] === 'Weekly'){
                
                                                        @$salary_of_month = ($row['Salary_of_Month']) / 4;
                                                        
                                                        if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                            @$salary_of_month = $salary_of_month - ($row_emp['drate'] * $number_of_absent);
                                                        }

                                                        $sss = $row['sss_amount'] / 4;
                                                        $philHealth = $row['philhealth_amount'] / 4; 
                                                        $pagibig_amount = $row['pagibig_amount'] / 4;
                                                        $tin_amount = $row['tin_amount'] / 4; 
                                                        $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;
                                                    }
                                                    
                                                


                                                   @$cutoff_OT = ($time_OT_TOTAL);
                                                    echo "<tr>
                                                            <td>" .  $salary_of_month  . "</td>
                                                            <td>" . $row['total_hours_minutesLATE'] . "</td>
                                                            <td>" .   $UT_time  . "</td>
                                                            <td>" . $row['total_hours_minutestotalHours'] . "</td>
                                                            <td>" .  ($salary_of_month ) - $UT_LATE_DEDUCT_TOTAL. "</td>
                                                            <td>".  $cutoff_OT  . "</td>
                                                            <td>". $sss ." </td>
                                                            <td>". $philHealth ."</td>
                                                            <td>". $pagibig_amount ."</td>
                                                            <td>". $tin_amount ."</td>
                                                            <td> " . ((($salary_of_month) - $UT_LATE_DEDUCT_TOTAL) +  $cutoff_OT) - $total_government_deduct  . " </td>
                                                        </tr>"; 
                                                }

                                                } //END IF ISSET

                                                          
                                            ?>  
                                              
                                    </tbody>
                                </table>
                                                <!-- </form> -->
                            </div> <!--table-responsive END-->
                        </div> <!--tabpane-1 END -->
                        <?php
                            $other_allowance = 0;
                            $result_allowaneOther = mysqli_query($conn, " SELECT
                                SUM(allowance_amount) AS total_sum
                            FROM 
                                `allowancededuct_tb` 
                            WHERE `id_emp` = '$emp_ID'");
    
                            if(mysqli_num_rows($result_allowaneOther) > 0) {
                                $row_allowaneOther = mysqli_fetch_assoc($result_allowaneOther);
                                $other_allowance = $row_allowaneOther['total_sum'];                                         
                            }
                            else{
                                $other_allowance = 0;
                            }

                        ?>
                                        <!-------------------------------------- break ALLOWANCE START  -------------------------------------------->
                        <div class="tab-pane" id= "Allowance">
                            <div class="table-responsive" style = "overflow-y: scroll;  max-height: 500px;">
                                            <form action="gnrate_payroll_view.php" method="post">
                                            <input id="employeeID" name="Name_employeeID" type="text" style= "display:none;">         
                                                <table id="order-listing" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Transportation Allowance</th>
                                                            <th>Meal Allowance</th> 
                                                            <th>Internet Allowance</th>
                                                            <th>Other Allowances</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                
                                                    <?php 
                                                        include 'config.php';
                                                    //select table for employee_tb allowance

                                                      

                                                    //select table for employee_tb allowance

                                                        $sql = "SELECT
                                                                    empid,
                                                                    emptranspo,
                                                                    empmeal,
                                                                    empinternet
                                                                FROM
                                                                    employee_tb

                                                                WHERE empid = $emp_ID";

                                                        $result = $conn->query($sql);

                                                        //read data
                                                        while($row = $result->fetch_assoc()){
                                                            echo "<tr>
                                                                    <td>" . ($row['emptranspo'] === "" ? "0" : $row['emptranspo']) . "</td>
                                                                    <td>" . ($row['empmeal'] === "" ? "0" : $row['empmeal']) . "</td>
                                                                    <td>" . ($row['empinternet'] === "" ? "0" : $row['empinternet']) . "</td>
                                                                    <td>" . ($other_allowance === 0 ? "0" : $other_allowance) . "</td>
                                                                </tr>"; 
                                                        }
                                                    ?>

                                
                                        </tbody>
                                    </table>
                                </form>
                            </div> <!--table-responsive END-->
                         </div> <!--tabpane-2 END-->
                                        <!--------------- break ------------->
                        <div class="tab-pane" id= "Loan">
                        
                            <div class="table-responsive table-bordered" style = "overflow-y: scroll;  max-height: 500px;">
                                            <form action="gnrate_payroll_view.php" method="post">
                                            <input id="employeeID" name="Name_employeeID" type="text" style= "display:none;">         
                                                <table id="order-listing" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Code</th>
                                                            <th>Payable Amount</th> 
                                                            <th>Amortization</th>
                                                            <th>Payable Balance</th> 
                                                            <th>CutOff</th> 
                                                            <th>Applied Cutoff</th> 
                                                            <th>Loan Status</th>
                                                            <th>Loan Date</th> 
                                                            <th>Date Applied</th> 
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    <?php 
                                                        include 'config.php';
                                                        //select data db


                                                        $sql = "SELECT
                                                                    *
                                                                FROM
                                                                    payroll_loan_tb
                                                            
                                                                WHERE empid = $emp_ID
                                                                
                                                            ";
                                                    $result = $conn->query($sql);
                                                
                                                        //read data
                                                        while($row = $result->fetch_assoc()){
                                                            echo "<tr>
                                                                    <td>" . $row['loan_type'] . "</td>
                                                                    <td>" . $row['payable_amount'] . "</td>   
                                                                    <td>" . $row['amortization'] . "</td>       
                                                                    <td>" . $row['col_BAL_amount'] . "</td>       
                                                                    <td>" . $row['cutoff_no'] . "</td>   
                                                                    <td>" . $row['applied_cutoff'] . "</td>  
                                                                    <td>" . $row['loan_status'] . "</td> 
                                                                    <td>" . $row['loan_date'] . "</td>     
                                                                    <td>" . $row['timestamp'] . "</td>                                          
                                                                </tr>"; 
                                                        }
                                                    ?>  
                                
                                        </tbody>
                                    </table>
                                </form>
                            </div> <!--table-responsive END-->
                            
                        </div> <!--tabpane-3 END-->
                                        <!--------------- break ------------->
                </div> <!--tab content END-->
              
                <div class="text-right mr-5 mt-3">
                    <a style="margin-right: 10px; font-size: 20px;"href="cutoff.php">Back</a>
                    <?php 
                    $sql_validss = mysqli_query($conn, " SELECT
                            *                 
                        FROM
                            employee_tb
                        INNER JOIN attendances ON employee_tb.empid = attendances.empid
                        WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave') AND employee_tb.empid = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'");

                        if(mysqli_num_rows($sql_validss) > 0) {
                            $row_valid= mysqli_fetch_assoc($sql_validss);
                            echo '<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Preview</button>';
                        } else {
                            echo '<button type="button" class="btn btn-outline-primary" style="cursor: no-drop;" disabled data-bs-toggle="modal" data-bs-target="#staticBackdrop">Preview</button>';
                        } 
                       
                    ?>
                   
                </div>
                <?php            
                                            //government deduction
                                            $result_governDeduct = mysqli_query($conn, " SELECT
                                            SUM(govern_amount) AS total_sum_othe_deduct 
                                                FROM 
                                                `governdeduct_tb`
                                                WHERE `id_emp`=  '$emp_ID'");
                                                $row_governDeduct = mysqli_fetch_assoc($result_governDeduct);     
                                                
                                                
                                                //for allowance deduction dapat fixed pero every LWOP or Leave or Absent
                                                $query_deduct_onLeave = "   SELECT COUNT(`status`) AS onLeaveCount FROM attendances 
                                                                            WHERE `status` = 'On-Leave' 
                                                                            AND `empid` = $emp_ID 
                                                                            AND `date` 
                                                                            BETWEEN  '$str_date' AND  '$end_date'";
                                                $result_deduct_onLeave = mysqli_query($conn, $query_deduct_onLeave);


                                                if(mysqli_num_rows($result_deduct_onLeave) > 0){
                                                    $row_deduct_onLeave = mysqli_fetch_assoc($result_deduct_onLeave);

                                                    $number_ofLeave_attStatus =  $row_deduct_onLeave['onLeaveCount'];
                                                }else{
                                                    $number_ofLeave_attStatus = 0;
                                                }


                                                //for counting number of LWOP niya para sa deduction info for payslip
                                                $query_deduct_LWOP = "   SELECT COUNT(`status`) AS onLWOPCount FROM attendances 
                                                                            WHERE `status` = 'LWOP' 
                                                                            AND `empid` = $emp_ID 
                                                                            AND `date` 
                                                                            BETWEEN  '$str_date' AND  '$end_date'";
                                                $result_deduct_LWOp = mysqli_query($conn, $query_deduct_LWOP);


                                                if(mysqli_num_rows($result_deduct_LWOp) > 0){
                                                    $row_deduct_LWOP = mysqli_fetch_assoc($result_deduct_LWOp);

                                                    $number_LWOP_attStatus =  $row_deduct_LWOP['onLWOPCount'];
                                                }else{
                                                    $number_LWOP_attStatus = 0;
                                                }


                                                $select_holiday_not_timein = "SELECT COUNT(`date`) as num_holiday_not_timein FROM attendances WHERE `status` = 'Present' AND time_in = '00:00:00' AND time_out = '00:00:00' AND `empid` = $emp_ID AND `date` BETWEEN  '$str_date' AND  '$end_date'";
                                                $result_holiday_not_present = mysqli_query($conn, $select_holiday_not_timein);
                                                if(mysqli_num_rows($result_holiday_not_present) > 0){
                                                    $row_holiday_not_present = mysqli_fetch_assoc($result_holiday_not_present);
                                                    $num_holiday_not_timein = $row_emp['drate'] * $row_holiday_not_present['num_holiday_not_timein']; // for holiday paid pero d pumasok ang employee pero bayad
                                                }else{
                                                    $num_holiday_not_timein = $row_emp['drate'] * 0;
                                                }

            ?>

                <!-- Modal PAYSLIP -->
                <div class="modal fade"  id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" style=" margin-top: 60px;">
                        <form action="generate-pdf.php" method="post">
                            
                            <div class="modal-content" id ="id_modal-pdf" >
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">PAYSLIP</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body"  style="height: 700px;">
                                <input type="hidden" name="name_cutOff_freq" id="id_cutOff_freq" value="<?php echo $_POST['name_cutOff_freq'];?>">
                                <input type="hidden" name="name_cutOff_num" id="id_cutOff_num" value="<?php echo $_POST['name_cutOff_num'];?>"> 
                                <?php 
                                    $_POST['name_cutOff_freq'];
                                    if ($_POST['name_cutOff_freq'] === 'Monthly'){
                                        $cutoFF_divide_allowance = ($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / $working_days;
                                        $cutoFF_divide_allowance = number_format($cutoFF_divide_allowance, 2); //convert into two decimal only
                                        $cutoFF_divide_allowance = str_replace(',', '', $cutoFF_divide_allowance); // Remove comma
                                        //echo $cutoFF_divide;
        
                                        $cutOff_SSS_deduct = $row_atteeee['sss_amount'];
                                        $cutOff_philhealth_deduct = $row_atteeee['philhealth_amount'];
                                        $cutOff_tin_deduct = $row_atteeee['tin_amount'];
                                        $cutOff_pagibig_deduct = $row_atteeee['pagibig_amount'];
                                        $cutoff_deductGovern =  $row_governDeduct['total_sum_othe_deduct'];
                                    }
                                    else if ($_POST['name_cutOff_freq'] === 'Semi-Month'){

                                        $cutoFF_divide_allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 2) / $working_days;
                                        $cutoFF_divide_allowance = number_format($cutoFF_divide_allowance, 2); //convert into two decimal only
                                        $cutoFF_divide_allowance = str_replace(',', '', $cutoFF_divide_allowance); // Remove comma
                                        //echo $cutoFF_divide;


                                        $first_cutOFf = '1';
                                        $last_cutoff ='2';
                                        $cutOff_SSS_deduct = $row_atteeee['sss_amount'] / 2;
                                        $cutOff_philhealth_deduct = $row_atteeee['philhealth_amount'] / 2;
                                        $cutOff_tin_deduct = $row_atteeee['tin_amount'] / 2;
                                        $cutOff_pagibig_deduct = $row_atteeee['pagibig_amount'] / 2;
                                        $cutoff_deductGovern =  $row_governDeduct['total_sum_othe_deduct'] / 2;

                                    }
                                    else if ($_POST['name_cutOff_freq'] === 'Weekly'){
                                        $cutoFF_divide_allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 4) / $working_days;
                                        $cutoFF_divide_allowance = number_format($cutoFF_divide_allowance, 2); //convert into two decimal only
                                        $cutoFF_divide_allowance = str_replace(',', '', $cutoFF_divide_allowance); // Remove comma

                                        //echo $cutoFF_divide;
                                        $first_cutOFf = '1';
                                        $last_cutoff ='4';
                                        $cutOff_SSS_deduct = $row_atteeee['sss_amount'] / 4;
                                        $cutOff_philhealth_deduct = $row_atteeee['philhealth_amount'] / 4;
                                        $cutOff_tin_deduct = $row_atteeee['tin_amount'] / 4;
                                        $cutOff_pagibig_deduct = $row_atteeee['pagibig_amount'] / 4;
                                        $cutoff_deductGovern =  $row_governDeduct['total_sum_othe_deduct'] / 4;
                                    }
                            


                                    if ($_POST['name_cutOff_freq'] === 'Monthly'){

                                        @$salary_of_month =  $row_atteeee['Salary_of_Month'];
                                            if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                @$salary_of_month = $salary_of_month - $value_of_drate_holiday - ($row_emp['drate'] * $number_of_absent);
                                                
                                                
                                            }

                                    } 
                                    else if ($_POST['name_cutOff_freq'] === 'Semi-Month'){

                                        @$salary_of_month =  ($row_atteeee['Salary_of_Month']) / 2;

                                        if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                            @$salary_of_month = $salary_of_month - $value_of_drate_holiday - ($row_emp['drate'] * $number_of_absent);
                                            
                                        }

                                    }
                                    else if ($_POST['name_cutOff_freq'] === 'Weekly'){

                                        @$salary_of_month = ($row_atteeee['Salary_of_Month']) / 4;
                                        if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                            @$salary_of_month = $salary_of_month - $value_of_drate_holiday - ($row_emp['drate'] * $number_of_absent);
                                            
                                        }

                                    }
                                   
                            
                                ?>

                                    <div class="header_view">
                                        <img src="icons/logo_hris.png" width="70px" alt="">
                                        <p class="lbl_cnfdntial">CONFIDENTIAL SLIP</p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="comp_name">Slash Tech Solutions Inc.</p>
                                        <p class="lbl_payPeriod">Pay Period :</p>
                                        <p class="dt_mdl_from"><?php echo $str_date; ?></p>
                                            <input type="text" name="col_strCutoff" value="<?php echo $str_date; ?>" style="display: none;">
                                        <p class="lbl_to">TO</p>
                                        <p class="dt_mdl_TO"><?php echo $end_date; ?></p>
                                            <input type="text" name="col_endCutoff" value="<?php echo $end_date; ?>" style="display: none;">

                                        <p class="lbl_stats">Employee Status :</p>
                                        <p class="p_statss"><?php echo $row_emp['status']; ?></p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="emp_no">EMPLOYEE NO.   :</p>
                                        <p class="p_empid"> <?php echo $emp_ID ?></p>
                                        <p class="p_payout">Payout        :</p>
                                        <p class="dt_pyout"><?php
                                                                date_default_timezone_set('Asia/Manila');
                                                                $current_date = date('Y / m / d');
                                                                echo $current_date;
                                                            ?>
                                        </p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="emp_name">EMPLOYEE NAME  :</p>
                                        <p class="p_emp_name" id="id_p_emp_name"> <?php echo $row_emp['full_name']; ?> </p>
                                        
                                    </div>

                                    <div class="headbody">
                                    <div class="headbdy_pnl1">
                                        <p class="lbl_sss"> </p>
                                        <p class="p_sss"></p>
                                        <p class="lbl_tin"></p>
                                        <p class="p_tin"></p>
                                    </div>

                                    <div class="headbdy_pnl2">
                                        <p class="lbl_phl"></p>
                                        <P class="p_phl"></P>
                                    </div>

                                    <div class="headbdy_pnl3">
                                        <p class="lbl_pgibg"></p>
                                        <P class="p_pgibg"></P>
                                    </div>

                                    </div>

                                    <div class="headbody2">
                                    <div class="headbdy_pnl1">
                                        <p class="lbl_earnings">Earnings</p>
                                        <p class="lbl_Hours">Hours</p>
                                        <p class="lbl_Amount">Amount</p>
                                    </div>

                                    <div class="headbdy_pnl2">
                                        <p class="lbl_deduct">Deduction</p>
                                        <p class="lbl_Amount2">Amount</p>
                                    </div>

                                    <div class="headbdy_pnl3">
                                        <p class="lbl_Balance">NET PAY</p>
                                    </div>

                                    </div>

                                    <div class="headbody3">
                                    <div class="headbdy_pnl11">
                                        <div class="div_mdlcontnt_left">
                                            <p class="lbl_bsc_pay">Basic Pay</p>
                                            <p class="p_Thrs"><?php echo $row_atteeee['total_hoursWORK']; ?></p>
                                            <p class="p_Tamount"><?php echo $salary_of_month - ($row_emp['drate'] * $number_ofLeave_attStatus); ?></p>
                                               

                                        </div>
                                        <?php
                                       
                                       $select_basic_OT = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(total_ot))) AS total_time_sum FROM overtime_tb WHERE `empid` = '$emp_ID' AND `work_schedule` BETWEEN  '$str_date' AND  '$end_date' AND `status` = 'Approved'";
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
                                   
                                                

                                                
                                    
                                        
                                        
                                        ?>

                                        <div class="div_mdlcontnt_left1">
                                            <p class="lbl_bsc_pay">Overtime Pay</p>
                                            <p class="p_Thrs"><?php echo  @$basic_OT_hours;?></p>
                                            <p class="p_Tamount"><?php echo $cutoff_OT + $totalOT_pay_holiday + $totalOT_pay_holiday_restday; ?></p>
                                                

                                        </div>

                                        <div class="div_mdlcontnt_left2">
                                            <p class="lbl_bsc_pay">Allowance</p>
                                            <p class="p_Thrs"></p>
                                            <?
                                                
                                                
                                                // if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                //     $number_ofLeave_attStatus 
                                                //     $final_allowance_deduct_holiday = 0;

                                                //     // @$final_allowance_deduct_holiday = $final_allowance_deduct_holiday - ($row_emp['drate'] * $number_of_absent);
                                                // }
                                                // else{
                                                //     $final_allowance_deduct_holiday = $cutoFF_divide_allowance * $num_days_holiday;
                                                // }

                                                
                                            ?>
                                            <p class="p_Tamount"><?php echo $formatted_value = ($cutoFF_divide_allowance * $row_atteeee['Number_of_days_work']);?></p>
                                               
                                            

                                        </div>


                                        <div class="div_mdlcontnt_left3">
                                            <p class="lbl_bsc_pay">PAID LEAVES</p>
                                            <p class="p_Thrs"></p>
                                            <p class="p_Tamount"><?php echo $row_emp['drate'] * $number_ofLeave_attStatus; ?></p>
                                                

                                        </div>

                                        <div class="div_mdlcontnt_left4">
                                            <p class="lbl_bsc_pay">HOLIDAY PAY</p>
                                            <p class="p_Thrs"></p>
                                            <p class="p_Tamount"><?php echo @$holiday_rate_with_dpay + $num_holiday_not_timein; ?></p>
                                                

                                        </div>


                                        <!-- <div class="div_mdlcontnt_left5">
                                            <p class="lbl_bsc_pay">HOLIDAY OT PAY</p>
                                            <p class="p_Thrs"></p>
                                            <p class="p_Tamount"><?php //echo $totalOT_pay_holiday + $totalOT_pay_holiday_restday;?></p>
                                                

                                        </div> -->
                                    
                                    </div>

                                    <div class="headbdy_pnl22">
                                        <div class="div_mdlcontnt_mid">
                                            <div class="div_mdlcontnt_mid_left">
                                                <p class="lbl_sss_se">SSS SE CONTRI</p>
                                                <p class="lbl_philhlt_c">PHILHEALTH CONTRI</p>
                                                <p class="lbl_sss_se">TIN CONTRI</p>
                                                <p class="lbl_philhlt_c">PAGIBIG CONTRI</p>
                                                <p class="lbl_hdmf">OTHER CONTRI</p>
                                                <p class="lbl_hdmf">Tardiness</p>
                                                <p class="lbl_hdmf">Undertime</p>
                                                <p class="lbl_hdmf">LWOP</p>
                                                <p  style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    <?php
                                                    if ($_POST['name_cutOff_freq'] === 'Monthly'){
                                                        //FOR EVERY CUTOFF DEDUCTIONS
                                                        $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved'";
                                                        $result = $conn->query($query);

                                                        // Check if any rows are fetched 
                                                        if ($result->num_rows > 0) 
                                                        {
                                                            while($row = $result->fetch_assoc()) 
                                                            {
                                                                echo "<br>" . $loan_type = $row["loan_type"]; 
                                                                        
                                                            } //end While
                                                        }
                                                    }else{
                                                        if( $_POST['name_cutOff_num'] === $first_cutOFf)
                                                        {

                                                    $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'First Cutoff'";
                                                            $result = $conn->query($query);
        
                                                            // Check if any rows are fetched 
                                                            if ($result->num_rows > 0) 
                                                            {
                                                                    //$loan_Unpaid_array = array(); // Array to store the dates
                                                                    //$row_L = mysqli_fetch_assoc($result);
                                                                    while($row = $result->fetch_assoc()) 
                                                                    {
                                                                        //echo $loan_ID = $row["applied_cutoff"];
                                                                        echo $loan_type = $row["loan_type"];
                                                                        //$loan_Unpaid_array[] = array('col_ID' => $loan_ID);         
                                                                    } //end while 
                                                                    
                                                            }else{
                                                                echo '';
                                                            }
                                                            }else if($_POST['name_cutOff_num'] === $last_cutoff)
                                                            {
                                                                $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Last Cutoff'";
                                                                $result = $conn->query($query);
        
                                                                // Check if any rows are fetched 
                                                                if ($result->num_rows > 0) 
                                                                {
                                                                    //$loan_Unpaid_array = array(); // Array to store the dates
                                                                    //$row_L = mysqli_fetch_assoc($result);
                                                                    while($row = $result->fetch_assoc()) 
                                                                    {
                                                                        echo $loan_type = $row["loan_type"];   
                                                                        //$loan_Unpaid_array[] = array('col_ID' => $loan_ID);         
                                                                    } //end while 
                                                                    
                                                            
                                                                }else{
                                                                echo '';
                                                                }
                                                            }
                                                            //FOR EVERY CUTOFF DEDUCTIONS
                                                            $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Every Cutoff'";
                                                            $result = $conn->query($query);
    
                                                            // Check if any rows are fetched 
                                                            if ($result->num_rows > 0) 
                                                            {
                                                                while($row = $result->fetch_assoc()) 
                                                                {
                                                                    echo "<br>" . $loan_type = $row["loan_type"]; 
                                                                            
                                                                } //end While
                                                            }
                                                        
                                                    }
                                                    
                                                    
                                                    ?>
                                                </p>
                                            
                                            </div>
                                            <div class="div_mdlcontnt_mid_right">
                                                <p class="lbl_sss_se"><?php echo $cutOff_SSS_deduct; ?></p>
                                                <p class="lbl_philhlt_c"><?php echo $cutOff_philhealth_deduct; ?></p>
                                                <p class="lbl_sss_se"><?php echo $cutOff_tin_deduct ?></p>
                                                <p class="lbl_philhlt_c"><?php echo $cutOff_pagibig_deduct ?></p>
                                                <p class="lbl_philhlt_c"><?php if($cutoff_deductGovern === NULL ){echo '0';}else{echo $cutoff_deductGovern;}?></p>
                                                <p class="lbl_philhlt_c"><?php echo $Late_rate_to_deduct ?></p>
                                                <p class="lbl_philhlt_c"><?php echo $Undertime_rate_to_deduct ?></p>
                                                <p class="lbl_philhlt_c"><?php echo $row_emp['drate'] * $number_LWOP_attStatus ?></p>
                                                <p style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    <?php
                                                        if ($_POST['name_cutOff_freq'] === 'Monthly'){
                                                            //FOR EVERY CUTOFF DEDUCTIONS
                                                            $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved'";
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
                                                            if( $_POST['name_cutOff_num'] === $first_cutOFf)
                                                                {

                                                                    $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'First Cutoff'";
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
                                                            else if($_POST['name_cutOff_num'] === $last_cutoff)
                                                                {
                                                                    $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Last Cutoff'";
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
                                                            $query = "SELECT * FROM payroll_loan_tb WHERE empid = $emp_ID AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Every Cutoff'";
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
                                                    
                                                        
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="name_empID" id="id_empID" value="<?php  echo $emp_ID ?>">
                                    <input type="hidden" name="name_numworks" id="id_numworks" value="<?php  echo $row_atteeee['Number_of_days_work']?>">
                                    <input type="hidden" name="name_cutoffID" id="id_cutoffID" value="<?php  echo $_POST['name_cutoffID']?>">
                                    
                                    <div class="headbdy_pnl33">
                                        <div class="div_mdlcontnt_right">
                                            <!-- NETPAY VALUE -->
                                        <p class="p_balance">
                                            <?php  echo "₱ " . (($salary_of_month) + $formatted_value + $cutoff_OT + @$holiday_rate_with_dpay + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein)
                                                - ($cutOff_SSS_deduct + $cutOff_philhealth_deduct +  $cutOff_tin_deduct +  $cutOff_pagibig_deduct +  $cutoff_deductGovern +  $UT_LATE_DEDUCT_TOTAL  + $total_deductionLOAN);
                                            ?>
                                        </p>
                                        
                                        </div>
                                    </div>
                                    </div>

                                    <div class="headbody2">
                                    <div class="headbdy_pnl1">
                                        <p class="lbl_earnings">Total Earnings :</p> 
                                        <p class="lbl_Hours"><?php echo $salary_of_month + $holiday_rate_with_dpay + $formatted_value  + $cutoff_OT + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein; ?></p>
                                    </div>
                                

                                    

                                

                                    <div class="headbdy_pnl2">
                                        <p class="lbl_deduct">Total Deduction : </p>
                                        <p class="lbl_Amount2"><?php echo $cutOff_SSS_deduct + $cutOff_philhealth_deduct +  $cutOff_tin_deduct +  $cutOff_pagibig_deduct +  $cutoff_deductGovern +  $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN;?></p>
                                    </div>

                                    <div class="headbdy_pnl3">
                                        <!-- <p class="lbl_deduct">Net Total : </p> -->
                                        <p class="lbl_Balance"><?php //echo ($row_atteeee['Salary_of_Month'] + ( $row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) + $TOTAL_ADD_OT)
                                        // - ( $row_atteeee['sss_amount'] + $row_atteeee['philhealth_amount'] +  $row_atteeee['tin_amount'] +  $row_atteeee['pagibig_amount'] +  $row_governDeduct['total_sum_othe_deduct'] +  $UT_LATE_DEDUCT_TOTAL );
                                        ?></p>
                                    </div>

                                    </div>

                                </div>
                                <!-- <div class="input-group mb-3">
                                    <h5 style="margin-left: 30px ; margin-top : 10px; ">NET SALARY: </h5>
                                    <span class="input-group-text" style=" margin-top : 5px;">23123</span>
                                </div> -->
                            
                            
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="id_btn_close" data-bs-dismiss="modal">Close</button>
                                    <button type="button" name="btn_download_pdf" class="btn btn-primary" id="download-pdf">Print</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div><!--  End Modal -->

        </div> <!--  End card-body -->
    </div> <!--  End card -->
</div><!--  End Container -->








<script type="text/javascript">
    $("body").on("click", "#download-pdf", function () {
        let emp_ID = document.getElementById('id_empID').value;
        let name_cutOff_freq = document.getElementById('id_cutOff_freq').value;
        let name_cutOff_num = document.getElementById('id_cutOff_num').value;
        let name_numworks = document.getElementById('id_numworks').value;
        let name_cutoffID = document.getElementById('id_cutoffID').value;
        document.getElementById('id_btn_close').style.display="none";
        document.getElementById('download-pdf').style.display="none";
        

        var emp_fullname = document.getElementById("id_p_emp_name");
        var fullname = emp_fullname.textContent;

        // Create a new Date object
var currentDate = new Date();

// Get the current date and time in the Philippines (Manila) timezone
var options = {
  timeZone: "Asia/Manila",
  year: "numeric",
  month: "numeric",
  day: "numeric",
  hour: "numeric",
  minute: "numeric",
  second: "numeric"
};

var currentDateTime = currentDate.toLocaleString("en-PH", options);



        html2canvas($('#id_modal-pdf')[0], {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download(fullname + "_" + currentDateTime  +".pdf");
                pdfMake.createPdf(docDefinition).getBase64(function (pdfData) {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var response = this.responseText;
                            console.log(response);
                            if (response != "") {
                                // Redirect to generate_payslip.php
                                window.location.href = "generatePayslip.php?msg=Successfully Generated the Payslip";
                            } else {
                                // Response is not "Done"
                                console.log(response);
                            }
                        }
                    };
                    xhr.open("POST", "generate-pdf.php", true);
                    var formData = new FormData();
                    formData.append("pdfData", pdfData);
                    formData.append("emp_ID", emp_ID);
                    formData.append("name_cutOff_freq", name_cutOff_freq);
                    formData.append("name_cutOff_num", name_cutOff_num);
                    formData.append("name_numworks", name_numworks);
                    formData.append("name_cutoffID", name_cutoffID);
                    xhr.send(formData);
                    document.getElementById('id_btn_close').style.display="";
                    document.getElementById('download-pdf').style.display="";
                });
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
      $('#dashboard-container').addClass('move-content');
    } else {
      $('#dashboard-container').removeClass('move-content');

      // Add class for transition
      $('#dashboard-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#dashboard-container').removeClass('move-content-transition');
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 390) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 500) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script src="path/to/mpdf/autoload.php"></script>




<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>





    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    
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
<!-- <script src="js/gnratePyroll.js"></script> -->
    
</html>