<?php
      session_start();
      //    $empid = $_SESSION['empid'];
         if (!isset($_SESSION['username'])) {
          header("Location: ../login.php");
      } else {
          // Check if the user's role is not "admin"
          if ($_SESSION['role'] != 'Employee') {
              // If the user's role is not "admin", log them out and redirect to the logout page
              session_unset();
              session_destroy();
              header("Location: logout.php");
              exit();
          } 
          
      }
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>



<!-- skydash -->

<link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

    <!-- swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles2.css">
    <title>HRIS | Dashboard</title>

</head>
<body>
    <style>
        body {
            overflow-X: hidden;
        }
        .swiper-button-next:after, .swiper-button-prev:after {
            font-size: 1.2em;
            font-weight: 700;
        }

        .emp-dash2-shortcut a{
            text-decoration: none;
            font-size: 1em;        }
    </style>
    <header>
        <?php include("header.php")?>
    </header>

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['error'])) {
            $err = $_GET['error'];
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            '.$err.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->


<!--------------------------------------Modal For Time In Button---------------------------------------->
<div class="modal fade" id="timeIn" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="Data Controller/Time Button/time_in.php" method="POST">
      <div class="modal-body">
          <h4>Do you want to Time In?</h4>
          <h4 id="currentTime">Loading...</h4> 
      </div>
         <div class="modal-footer">
          <button type="submit" name="time_in" class="btn btn-primary">Yes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!--------------------------------------Modal For Time In Button---------------------------------------->


<!--------------------------------------Modal For Time Out Button---------------------------------------->
<div class="modal fade" id="timeOut" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="Data Controller/Time Button/time_out.php" method="POST">
      <div class="modal-body">
          <h4>Do you want to Time Out?</h4>
          <h4 id="outTime"></h4> 
      </div>
         <div class="modal-footer">
          <button type="submit" name="time_out" class="btn btn-primary">Yes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!--------------------------------------Modal For Time Out Button---------------------------------------->

    <div class="emp-dashboard-container">
            <div class="emp-dashboard-content">
                <div class="emp-dash-card">
                    <div class="dash-schedule-card">
                        <div class="container">
                    <?php
                        $employeeid = $_SESSION['empid'];
                        include 'config.php';
                        date_default_timezone_set("Asia/Manila"); // set the timezone to Manila
                        $current_date = date("Y-m-d"); // format the date as YYYY-MM-DD

                        $timein_attendance = "";
                        $timeout_attendance = "";
                        $attendance_query = mysqli_query($conn, "SELECT attendances.id,
                        attendances.status,
                        employee_tb.empid,
                        CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                        attendances.date,
                        attendances.time_in,
                        attendances.time_out,
                        attendances.late,
                        attendances.early_out,
                        attendances.overtime,
                        attendances.total_work,
                        attendances.total_rest
                        FROM attendances
                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid WHERE employee_tb.empid = '$employeeid' AND `date` = '$current_date'");
                        if(mysqli_num_rows($attendance_query) > 0) {
                            $row_attendances = mysqli_fetch_assoc($attendance_query);
                            $timein_attendance = $row_attendances['time_in'];
                            $timeout_attendance = $row_attendances['time_out']; 
                         }
                         else{
                            $timein_attendance = 'NO TIME IN';
                            $timeout_attendance = 'NO TIME OUT';
                         } 
                    ?>
                    
                    <div>
                        <span class="schedule-for">Schedule For:</span>
                        <span id="current_date"></span>
                    </div>
                    <?php 
                            $employeeid = $_SESSION['empid'];
                            include 'config.php';
                            date_default_timezone_set('Asia/Manila'); // set the timezone to Manila

                            $today = new DateTime(); // create a new DateTime object for today
                            $today->modify('this week'); // navigate to the beginning of the week

                            $week_dates = array(); // create an empty array to store the week dates

                            for ($i = 0; $i < 7; $i++) {
                                $week_dates[] = $today->format('Y-m-d'); // add the current date to the array
                                $today->modify('+1 day'); // navigate to the next day
                            }
                            
                            $query = "SELECT empschedule_tb.id, employee_tb.empid, empschedule_tb.sched_from, empschedule_tb.sched_to, empschedule_tb.schedule_name, 
                                    schedule_tb.mon_timein, schedule_tb.mon_timeout,
                                    schedule_tb.tues_timein, schedule_tb.tues_timeout,
                                    schedule_tb.wed_timein, schedule_tb.wed_timeout,
                                    schedule_tb.thurs_timein, schedule_tb.thurs_timeout,
                                    schedule_tb.fri_timein, schedule_tb.fri_timeout,
                                    schedule_tb.sat_timein, schedule_tb.sat_timeout,
                                    schedule_tb.sun_timein, schedule_tb.sun_timeout
                                    FROM empschedule_tb
                                    INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
                                    INNER JOIN employee_tb ON empschedule_tb.empid = employee_tb.empid 
                                    WHERE employee_tb.empid = '$employeeid'
                                    AND (sched_from <= CURDATE() AND sched_to >= CURDATE());";
                                    
                            $result = mysqli_query($conn, $query);

                            if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $time_in = '';
                                    $time_out = '';
                                    $day_of_week = date('D');
                                    switch ($day_of_week) {
                                        case 'Mon':
                                            $time_in =  date('h:i A', strtotime($row['mon_timein']));
                                            $time_out = date('h:i A', strtotime($row['mon_timeout']));
                                            break;
                                        case 'Tue':
                                            $time_in =  date('h:i A', strtotime($row['tues_timein']));
                                            $time_out = date('h:i A', strtotime($row['tues_timeout']));
                                            break;
                                        case 'Wed':
                                            $time_in =  date('h:i A', strtotime($row['wed_timein']));
                                            $time_out = date('h:i A', strtotime($row['wed_timeout']));
                                            break;
                                        case 'Thu':
                                            $time_in =  date('h:i A', strtotime($row['thurs_timein']));
                                            $time_out = date('h:i A', strtotime($row['thurs_timeout']));
                                            break;
                                        case 'Fri':
                                            $time_in =  date('h:i A', strtotime($row['fri_timein']));
                                            $time_out = date('h:i A', strtotime($row['fri_timeout']));
                                            break;
                                        case 'Sat':
                                            $time_in =  date('h:i A', strtotime($row['sat_timein']));
                                            $time_out = date('h:i A', strtotime($row['sat_timeout']));
                                            break;
                                        case 'Sun':
                                            $time_in =  date('h:i A', strtotime($row['sun_timein']));
                                            $time_out = date('h:i A', strtotime($row['sun_timeout']));
                                            break;
                                    }
                                    echo "<div style='text-align: right; margin-top: -22px; color: black;'> <strong>Schedule Time: </strong>" . $time_in . "-" . $time_out . "</div>";
                                    echo $row['schedule_name'];
                                    
                                }
                            } else {
                                echo "No schedule found for this week.";
                            }
                        ?>
                    <div class="progress-container">
                        <div>
                            <span id="current_time"></span>
                        </div>
                        <div class="steps">
                            <span class="circle"></span>
                            <div class="progress-bar">
                                <span class="indicator"></span>
                            </div>
                        </div>
                        <div class="buttons">
                            <div class="first-button">
                                <?php
                                    include 'config.php';
                                    $employeeid = $_SESSION['empid'];

                                    $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'");
                                    if(mysqli_num_rows($result_emp_sched) > 0) {
                                        $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                                        $schedID = $row_emp_sched['schedule_name'];

                                        $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
                                        if(mysqli_num_rows($result_sched_tb) > 0) {
                                            $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                                            $sched_name =  $row_sched_tb['schedule_name'];
                                            $monday_wfh = $row_sched_tb['mon_wfh'];
                                            $tuesday_wfh = $row_sched_tb['tues_wfh'];
                                            $wednesday_wfh = $row_sched_tb['wed_wfh'];
                                            $thursday_wfh = $row_sched_tb['thurs_wfh'];
                                            $friday_wfh = $row_sched_tb['fri_wfh'];
                                            $saturday_wfh = $row_sched_tb['sat_wfh'];
                                            $sunday_wfh = $row_sched_tb['sun_wfh'];

                                            $current_day = date('l'); // Kunin ang kasalukuyang araw ng linggo (Monday, Tuesday, etc.) sa Manila, Philippines
                                            $disable_button = true; // I-default ang button na ma-disable

                                            if ($current_day === 'Monday' && ($monday_wfh !== NULL && $monday_wfh !== '')) {
                                                $disable_button = false; 
                                            } elseif ($current_day === 'Tuesday' && ($tuesday_wfh !== NULL && $tuesday_wfh !== '')) {
                                                $disable_button = false; 
                                            } elseif ($current_day === 'Wednesday' && ($wednesday_wfh !== NULL && $wednesday_wfh !== '')) {
                                                $disable_button = false; 
                                            } elseif ($current_day === 'Thursday' && ($thursday_wfh !== NULL && $thursday_wfh !== '')) {
                                                $disable_button = false; 
                                            } elseif ($current_day === 'Friday' && ($friday_wfh !== NULL && $friday_wfh !== '')) {
                                                $disable_button = false; 
                                            } elseif ($current_day === 'Saturday' && ($saturday_wfh !== NULL && $saturday_wfh !== '')) {
                                                $disable_button = false; 
                                            } elseif ($current_day === 'Sunday' && ($sunday_wfh !== NULL && $sunday_wfh !== '')) {
                                                $disable_button = false; // Ma-enable ang button kapag ang current day ay Sunday at ang column na sun_wfh ay hindi NULL o empty
                                            } else {
                                                $current_date = date('Y-m-d');
                                                $CheckWFHDate = "SELECT * FROM wfh_tb WHERE `empid` = '$employeeid' AND `status` = 'Approved' AND `date` = '$current_date'";
                                                $WFHDateRun = mysqli_query($conn, $CheckWFHDate);
                                                $has_matching_date = mysqli_num_rows($WFHDateRun) > 0;
                                                // Pag enable ng time out button kung ang wfh status ay Approved at nakabase sa date
                                                if ($has_matching_date) {
                                                    $disable_button = false;
                                                } else {
                                                    $disable_button = true;
                                                }
                                            }

                                            $button_status = $disable_button ? 'disabled' : ''; // Set ang status ng button base sa $disable_button variable
                                            $button_cursor_style = $disable_button ? 'not-allowed' : ''; // Set ang style ng cursor base sa $disable_button variable

                                echo '<div class="button-panel">
                                   <button class="prev" name="button_time_in" id="prev_time_in" data-bs-toggle="modal" data-bs-target="#timeIn" ' . $button_status . ' style="cursor: ' . $button_cursor_style . '">Time In</button>
                                </div>';
                             }   
                            }
                        ?>
                                <div class="firstbtn_content">
                                <?php 
                                        if ($timein_attendance === '00:00:00') {
                                            echo "No time in";
                                        } else {
                                            echo $timein_attendance;
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="second-button">
                                    <?php
                                        $employeeid = $_SESSION['empid'];
                                        
                                        $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'");
                                        if(mysqli_num_rows($result_emp_sched) > 0) {
                                            $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                                            $schedID = $row_emp_sched['schedule_name'];

                                            $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
                                            if(mysqli_num_rows($result_sched_tb) > 0) {
                                                $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                                                $sched_name =  $row_sched_tb['schedule_name'];
                                                $monday_wfh = $row_sched_tb['mon_wfh'];
                                                $tuesday_wfh = $row_sched_tb['tues_wfh'];
                                                $wednesday_wfh = $row_sched_tb['wed_wfh'];
                                                $thursday_wfh = $row_sched_tb['thurs_wfh'];
                                                $friday_wfh = $row_sched_tb['fri_wfh'];
                                                $saturday_wfh = $row_sched_tb['sat_wfh'];
                                                $sunday_wfh = $row_sched_tb['sun_wfh'];

                                                $current_day = date('l'); // Kunin ang kasalukuyang araw ng linggo (Monday, Tuesday, etc.) sa Manila, Philippines
                                                $disable_button = true; // I-default ang button na ma-disable

                                                if ($current_day === 'Monday' && ($monday_wfh !== NULL && $monday_wfh !== '')) {
                                                    $disable_button = false; 
                                                } elseif ($current_day === 'Tuesday' && ($tuesday_wfh !== NULL && $tuesday_wfh !== '')) {
                                                    $disable_button = false; 
                                                } elseif ($current_day === 'Wednesday' && ($wednesday_wfh !== NULL && $wednesday_wfh !== '')) {
                                                    $disable_button = false; 
                                                } elseif ($current_day === 'Thursday' && ($thursday_wfh !== NULL && $thursday_wfh !== '')) {
                                                    $disable_button = false; 
                                                } elseif ($current_day === 'Friday' && ($friday_wfh !== NULL && $friday_wfh !== '')) {
                                                    $disable_button = false; 
                                                } elseif ($current_day === 'Saturday' && ($saturday_wfh !== NULL && $saturday_wfh !== '')) {
                                                    $disable_button = false; 
                                                } elseif ($current_day === 'Sunday' && ($sunday_wfh !== NULL && $sunday_wfh !== '')) {
                                                    $disable_button = false; // Ma-enable ang button kapag ang current day ay Sunday at ang column na sun_wfh ay hindi NULL o empty
                                                } else{
                                                    $current_date = date('Y-m-d');
                                                    $CheckWFHDate = "SELECT * FROM wfh_tb WHERE `empid` = '$employeeid' AND `status` = 'Approved' AND `date` = '$current_date'";
                                                    $WFHDateRun = mysqli_query($conn, $CheckWFHDate);
                                                    $has_matching_date = mysqli_num_rows($WFHDateRun) > 0;
                                                    // Pag enable ng time out button kung ang wfh status ay Approved at nakabase sa date
                                                    if ($has_matching_date) {
                                                        $disable_button = false;
                                                    } else {
                                                        $disable_button = true;
                                                    }
                                                }
                                                // Check kung may existing na time_in para kay employeeid sa attendances table
                                                $existing_time_in_query = "SELECT * FROM attendances WHERE empid = '$employeeid' AND time_in != '00:00:00'";
                                                $existing_time_in_result = mysqli_query($conn, $existing_time_in_query);
                                                $disable_time_out_button = (mysqli_num_rows($existing_time_in_result) === 0);

                                                $button_status = ($disable_button || $disable_time_out_button) ? 'disabled' : ''; // Set ang status ng button base sa mga kondisyon
                                                $button_cursor_style = ($disable_button || $disable_time_out_button) ? 'not-allowed' : ''; // Set ang style ng cursor base sa mga kondisyon

                                                echo'<div class="secondbtn_panel">
                                                <button class="next" name="button_time_out" id="next_time_out" data-bs-toggle="modal" data-bs-target="#timeOut"' . $button_status . ' style="cursor: ' . $button_cursor_style . '">Time Out</button>
                                                </div>';
                                            }
                                        }
                                    ?>

                                    <div class="secondbtn_content">
                                        <?php
                                        if ($timeout_attendance === '00:00:00') {
                                            echo "No time out";
                                        } else {
                                            echo $timeout_attendance;
                                        }
                                        ?>
                                    </div>
                                </div>

                        </div><!---Buttons close tag--->

                     </div>
                 </div>   
              </div>
                                    <div class="dash-schedule-content">
                                        <div style="">
                                            <?php
                                                $employeeid = $_SESSION['empid'];
                                                include 'config.php';
                                                date_default_timezone_set('Asia/Manila');
                                                $yesterday = date('Y-m-d', strtotime('-1 day')); // Get the date of yesterday

                                                $query = "SELECT attendances.id,
                                                        attendances.status,
                                                        employee_tb.empid,
                                                        CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                                        attendances.date,
                                                        attendances.time_in,
                                                        attendances.time_out,
                                                        attendances.late,
                                                        attendances.early_out,
                                                        attendances.overtime,
                                                        attendances.total_work,
                                                        attendances.total_rest
                                                        FROM attendances
                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid WHERE employee_tb.empid = '$employeeid'
                                                        AND DATE(attendances.date) = '$yesterday';"; // Modify the query to filter by yesterday's date

                                                $result = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($result); // Fetch a single row

                                                if ($row) {
                                                    $time_in = date('h:i A', strtotime($row['time_in'])); // Format time_in to AM/PM
                                                    $time_out = date('h:i A', strtotime($row['time_out'])); // Format time_out to AM/PM
                                                    ?>
                                                    <h1>Yesterday</h1>
                                                    <h5><?php echo $time_in . " - " . $time_out; ?></h5>

                                                    <?php
                                                } else {
                                                    echo "<h1>No Attendance</h1>";
                                                }
                                            ?>
                                        </div>
                                                                <div class="dash-barrier" style="margin-left: 70px;">
                                                                        <!---Barrier--->
                                                                </div>
                                                <div style="margin-right: 38px; width: 250px;">
                                                        <?php 
                                                        $employeeid = $_SESSION['empid'];
                                                        include 'config.php';

                                                        date_default_timezone_set('Asia/Manila'); // Set the timezone to Manila, Philippines
                                                        $today = date('Y-m-d'); // Get the current date
                                                        $tomorrow = date('Y-m-d', strtotime('+1 day')); // Get tomorrow's date

                                                        $query = "SELECT empschedule_tb.id, employee_tb.empid, empschedule_tb.sched_from, empschedule_tb.sched_to, empschedule_tb.schedule_name, schedule_tb.mon_timein, schedule_tb.mon_timeout,
                                                        schedule_tb.tues_timein, schedule_tb.tues_timeout,
                                                        schedule_tb.wed_timein, schedule_tb.wed_timeout,
                                                        schedule_tb.thurs_timein, schedule_tb.thurs_timeout,
                                                        schedule_tb.fri_timein, schedule_tb.fri_timeout,
                                                        schedule_tb.sat_timein, schedule_tb.sat_timeout,
                                                        schedule_tb.sun_timein, schedule_tb.sun_timeout
                                                        FROM
                                                        empschedule_tb
                                                        INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
                                                        INNER JOIN employee_tb ON empschedule_tb.empid = employee_tb.empid WHERE employee_tb.empid = '$employeeid'
                                                        AND empschedule_tb.sched_from <= '$tomorrow' AND empschedule_tb.sched_to >= '$tomorrow';";
                                                        $result = mysqli_query($conn, $query);
                                                        $row = mysqli_fetch_assoc($result);
                                                        if ($row) {
                                                            switch (date('l', strtotime($tomorrow))) {
                                                                case 'Monday':
                                                                    $time_in = $row['mon_timein'];
                                                                    $time_out = $row['mon_timeout'];
                                                                    break;
                                                                case 'Tuesday':
                                                                    $time_in = $row['tues_timein'];
                                                                    $time_out = $row['tues_timeout'];
                                                                    break;
                                                                case 'Wednesday':
                                                                    $time_in = $row['wed_timein'];
                                                                    $time_out = $row['wed_timeout'];
                                                                    break;
                                                                case 'Thursday':
                                                                    $time_in = $row['thurs_timein'];
                                                                    $time_out = $row['thurs_timeout'];
                                                                    break;
                                                                case 'Friday':
                                                                    $time_in = $row['fri_timein'];
                                                                    $time_out = $row['fri_timeout'];
                                                                    break;
                                                                case 'Saturday':
                                                                    $time_in = $row['sat_timein'];
                                                                    $time_out = $row['sat_timeout'];
                                                                    break;
                                                                case 'Sunday':
                                                                    $time_in = $row['sun_timein'];
                                                                    $time_out = $row['sun_timeout'];
                                                                    break;
                                                                }
                                                        ?>
                                                            <h1>Tomorrow</h1>
                                                            <h5><?php echo date("h:i A", strtotime($time_in)) . " - " . date("h:i A", strtotime($time_out));?></h5>
                                                        <?php
                                                        } else {
                                                            echo "<h1>No Schedule</h1>";
                                                        }
                                                    ?>
                                                </div>
                                    </div> <!--dash-schedule-content-->

                                                <div class="dash-employment-container">
                                                    <!---Barrier--->
                                                <div>
                                    <div class="dash-employment-content">
                                                <div class="dash-emp-icon" style="background: rgb(87,44,198); background: linear-gradient(36deg, rgba(87,44,198,1) 22%, rgba(0,212,255,1) 90%, rgba(2,0,36,1) 100%);">
                                                    <i class="fa-regular fa-clock"></i>
                                                </div>
                                            <div>
                                            <?php
                                                $employeeid = $_SESSION['empid'];
                                                include '../config.php';
                                                date_default_timezone_set('Asia/Manila');

                                                $currentMonth = date('Y-m');
                                                $query = "SELECT COUNT(*) AS late_count
                                                        FROM attendances
                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                        WHERE employee_tb.empid = '$employeeid'
                                                        AND DATE_FORMAT(attendances.date, '%Y-%m') = '$currentMonth'
                                                        AND attendances.late > 0"; // Modify the query to filter by the current month and check for late entries
                                                $result = mysqli_query($conn, $query);
                                                $row = mysqli_fetch_assoc($result);
                                                        
                                                if($row){
                                                    $lateCount = $row['late_count'];
                                                    
                                                ?>
                                                    <h5 style="margin-top: 10px;"><?php echo $lateCount; ?></h5>
                                                    <p style="margin-top: 1px;">Total Tardiness</p>
                                                <?php    
                                                }else{
                                                    // Handle case when no row is returned or row is empty
                                                    echo "<h5 style='margin-top: 10px;'> 0 </h5>
                                                    <p style='margin-top: 1px;'>Total Tardiness</p>";
                                                }
                                                ?>
                                                </div>
                                     </div> <!--dash-employment-content-->

                                     <div class="dash-employment-content">
                                                <div class="dash-emp-icon" style="background: rgb(34,193,195); background: linear-gradient(36deg, rgba(34,193,195,1) 0%, rgba(189,189,89,1) 35%, rgba(253,187,45,1) 100%);">
                                                    <i class="fa-solid fa-bed"></i>
                                                </div>
                                                    <div>
                                                        <?php
                                                        include '../config.php';
                                                        $employeeid = $_SESSION['empid'];
                                                        date_default_timezone_set('Asia/Manila');

                                                        $currentMonth = date('m');
                                                        $currentYear = date('Y');

                                                        // Modify the query to filter by the current month and year
                                                        $query = "SELECT COUNT(*) AS total_absent
                                                        FROM attendances
                                                        INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                        WHERE employee_tb.empid = '$employeeid'
                                                        AND MONTH(attendances.date) = '$currentMonth'
                                                        AND YEAR(attendances.date) = '$currentYear'
                                                        AND attendances.status = 'Absent';"; 

                                                        $result = mysqli_query($conn, $query);
                                                        $row = mysqli_fetch_assoc($result);

                                                        if($row){
                                                            $totalAbsent = $row['total_absent'];
                                                            ?>    
                                                        <h5 style="margin-top: 10px;"><?php echo $totalAbsent; ?></h5>
                                                        <p style="margin-top: 1px;">Total Absent</p>
                                                        <?php
                                                        }else{
                                                            // Handle case when no row is returned or row is empty
                                                               echo "<h5 style='margin-top: 10px;'> 0 </h5>
                                                               <p style='margin-top: 1px;'>Total Absent</p>";
                                                        }
                                                        ?>

                                                    </div>
                                     </div>

                                     <div class="dash-employment-content">
                                                <div class="dash-emp-icon" style="background: rgb(131,58,180);background: linear-gradient(36deg, rgba(131,58,180,1) 0%, rgba(253,29,29,1) 50%, rgba(252,176,69,1) 100%);">
                                                    <i class="fa-solid fa-plane-departure"></i>
                                                </div>
                                                    <div>
                                                        <?php
                                                        include '../config.php';

                                                        $employeeid = $_SESSION['empid'];
                                                        $query = "SELECT leaveinfo_tb.col_ID,
                                                                employee_tb.empid,
                                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                                                leaveinfo_tb.col_vctionCrdt,
                                                                leaveinfo_tb.col_sickCrdt,
                                                                leaveinfo_tb.col_brvmntCrdt
                                                                FROM leaveinfo_tb
                                                                INNER JOIN employee_tb ON leaveinfo_tb.col_empID = employee_tb.empid WHERE employee_tb.empid = '$employeeid';";

                                                        $result = mysqli_query($conn, $query);
                                                        $row = mysqli_fetch_assoc($result);
                                                        
                                                        if ($row) {
                                                            $totalvacation = $row['col_vctionCrdt'];
                                                            ?>
                                                            <h5 style="margin-top: 10px;"><?php echo $totalvacation;?></h5>
                                                            <p style="margin-top: 1px;">Vacation Leave Balance</p>
                                                        <?php } else {
                                                            // Handle case when no row is returned or row is empty
                                                            echo "<h5 style='margin-top: 10px;'> 0 </h5>
                                                            <p style='margin-top: 1px;'>Vacation Leave Balance</p>";
                                                        }
                                                        ?>
                                                    </div>
                                            </div>
                                     </div>

                                        <div> 
                                            <div class="dash-employment-content">
                                                <div class="dash-emp-icon" style="background: rgb(122,106,106); background: linear-gradient(65deg, rgba(122,106,106,1) 0%, rgba(230,230,47,1) 67%, rgba(253,187,45,1) 100%);">
                                                    <i class="fa-solid fa-stopwatch-20"></i>
                                                </div>
                                                    <div>
                                                        <?php
                                                            include '../config.php';
                                                            date_default_timezone_set('Asia/Manila');
                                                            $employeeid = $_SESSION['empid'];
                                                            $currentMonth = date('m');
                                                            $currentYear = date('Y');

                                                            $query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.overtime))) AS total_overtime 
                                                                    FROM attendances 
                                                                    INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                                    WHERE employee_tb.empid = '$employeeid' 
                                                                    AND MONTH(attendances.date) = '$currentMonth' 
                                                                    AND YEAR(attendances.date) = '$currentYear';";

                                                            $result = mysqli_query($conn, $query);

                                                            if ($result && mysqli_num_rows($result) > 0) {
                                                                $row = mysqli_fetch_assoc($result);
                                                                if ($row['total_overtime']) {
                                                                    [$hours, $minutes, $seconds] = explode(':', $row['total_overtime']);
                                                                } else {
                                                                    $hours = '00';
                                                                    $minutes = '00';
                                                                    $seconds = '00';
                                                                }
                                                            ?>
                                                                <h5 style="margin-top: 10px;"><?php echo $hours; ?>hr(s) <?php echo $minutes; ?>mn(s) <?php echo $seconds; ?>sec(s)</h5>
                                                                <p style="margin-top: 1px;">Total Overtime</p>
                                                            <?php
                                                            } else {
                                                                echo "<h5 style='margin-top: 10px;'>00hr(s) 00mn(s) 00sec(s)</h5>
                                                                    <p style='margin-top: 1px;'>Total Overtime</p>";
                                                            }
                                                            ?>
                                                    </div>
                                            </div>

                                            <div class="dash-employment-content">
                                                <div class="dash-emp-icon" style="background: rgb(122,106,106); background: linear-gradient(65deg, rgba(122,106,106,1) 0%, rgba(214,214,201,1) 67%, rgba(168,151,113,1) 100%);">
                                                    <i class="fa-solid fa-hourglass-half"></i>
                                                </div>
                                                <div>
                                                <?php
                                                        include '../config.php';
                                                        date_default_timezone_set('Asia/Manila');
                                                        $employeeid = $_SESSION['empid'];
                                                        $currentMonth = date('m');
                                                        $currentYear = date('Y');

                                                        $query = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.early_out))) AS total_early_out 
                                                                FROM attendances 
                                                                INNER JOIN employee_tb ON attendances.empid = employee_tb.empid 
                                                                WHERE employee_tb.empid = '$employeeid' 
                                                                AND MONTH(attendances.date) = '$currentMonth' 
                                                                AND YEAR(attendances.date) = '$currentYear';";

                                                        $result = mysqli_query($conn, $query);

                                                        if ($result && mysqli_num_rows($result) > 0) {
                                                            $row = mysqli_fetch_assoc($result);
                                                            if($row['total_early_out']){
                                                            [$hours, $minutes, $seconds] = explode(':', $row['total_early_out']);
                                                        }else{
                                                            $hours = '00';
                                                            $minutes = '00';
                                                            $seconds = '00';
                                                        }
                                                    ?>
                                                            <h5 style="margin-top: 10px;"><?php echo $hours; ?>hr(s) <?php echo $minutes; ?>mn(s) <?php echo $seconds; ?>sec(s)</h5>
                                                            <p style="margin-top: 1px;">Total Undertime</p>
                                                    <?php
                                                        } else {
                                                            echo "<h5 style='margin-top: 10px;'>00hr(s) 00mn(s) 00sec(s)</h5>
                                                                <p style='margin-top: 1px;'>Total Undertime</p>";
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            
                                            <div class="dash-employment-content">
                                                <div class="dash-emp-icon" style="background: rgb(246,164,164); background: linear-gradient(65deg, rgba(246,164,164,1) 0%, rgba(214,214,201,1) 67%, rgba(245,220,165,1) 100%);">
                                                    <i class="fa-solid fa-laptop-medical"></i>
                                                </div>
                                                <div>
                                                <?php
                                                        include '../config.php';

                                                        $employeeid = $_SESSION['empid'];
                                                        $query = "SELECT leaveinfo_tb.col_sickCrdt,
                                                                employee_tb.empid,
                                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                                                leaveinfo_tb.col_vctionCrdt,
                                                                leaveinfo_tb.col_sickCrdt,
                                                                leaveinfo_tb.col_brvmntCrdt
                                                                FROM leaveinfo_tb
                                                                INNER JOIN employee_tb ON leaveinfo_tb.col_empID = employee_tb.empid WHERE employee_tb.empid = '$employeeid';";

                                                        $result = mysqli_query($conn, $query);
                                                        $row = mysqli_fetch_assoc($result);
                                                        
                                                        if ($row) {
                                                            $sickcredit = $row['col_sickCrdt'];
                                                            ?>
                                                            <h5 style="margin-top: 10px;"><?php echo $sickcredit;?></h5>
                                                            <p style="margin-top: 1px;">Sick Leave Balance</p>
                                                        <?php } else {
                                                            // Handle case when no row is returned or row is empty
                                                            echo "<h5 style='margin-top: 10px;'> 0 </h5>
                                                            <p style='margin-top: 1px;'>Sick Leave Balance</p>";
                                                        }
                                                        ?>
                                                </div>
                                            </div> 
                                        </div> 
                                    </div>
                                </div>
                                <div class="emp-dash-card2">
                                    <div class="emp-dash2-announcement"> 

                                        <div class="emp-dash2-announcement-title">
                                            <h1 style="text-align: center;">Announcement</h1>
                                        </div>

                                        <div class="swiper" style="height: 80%">
                                            <div class="swiper-wrapper" >
                                                <?php
                                                    include 'config.php';

                                                    $query = "SELECT announcement_tb.id,
                                                                announcement_tb.announce_title,
                                                                employee_tb.empid,
                                                                CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                                announcement_tb.announce_date,
                                                                announcement_tb.description,
                                                                announcement_tb.file_attachment 
                                                            FROM announcement_tb 
                                                            INNER JOIN employee_tb ON announcement_tb.empid = employee_tb.empid;";
                                                    $result = mysqli_query($conn, $query);
                                                    $slideIndex = 0;

                                                    if (mysqli_num_rows($result) > 0) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            if ($slideIndex % 1 === 0) {
                                                                echo "<div class='swiper-slide pl-5 pr-5 pt-3'>";
                                                            }
                                                            ?>                          
                                                            <h4 class="mt-2 ml-2"><?php echo $row['announce_title'] ?></h4>
                                                            <p class="ml-2"><span style="color: #7F7FDD; font-style: Italic;"><?php echo $row['full_name'] ?></span> - <?php echo $row['announce_date'] ?></p>
                                                            <p class="ml-2"><?php echo $row['description'] ?></p>
                                                            <?php
                                                            if (($slideIndex + 1) % 1 === 0) {
                                                                echo "</div>";
                                                            }
                                                            $slideIndex++;
                                                        }
                                                        if ($slideIndex % 1 !== 0) {
                                                            echo "</div>";
                                                        }
                                                    } else {
                                                        echo "<div class='announcement-slide'>";
                                                        echo "<h1 style='text-align: center; margin-top:60px;'>No items on whiteboard</h1>";
                                                        echo "</div>";
                                                    }
                                                    ?>

                                                
                                            </div>
                                            <!-- If we need pagination -->
                                              <div class="swiper-pagination"></div>

                                            <!-- If we need navigation buttons -->
                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-button-next"></div>
                                    </div>
                                </div>
                                    

                                    <div class="emp-dash2-chart">
                                        <h3 class="d-flex align-items-center justify-content-center" style="font-size: 1.2em; height: 1.9em; background-color: #838383; color: #fff; font-weight: 400">Events and Holidays</h3>
                                        <div class="event-content">
                            <div class="first_content">
                                <?php
                                  date_default_timezone_set('Asia/Manila');

                                  // Get the current month's start and end dates
                                  $startDate = date('Y-m-d');
                                  $endDate = date('Y-m-t');
                                  
                                $query = "SELECT * FROM event_tb WHERE date_event BETWEEN '$startDate' AND '$endDate' ORDER BY `date_event` ASC";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $eventDate = date('Y-m-d', strtotime($row['date_event']));
                                    $eventDay = date('l', strtotime($row['date_event']));
                                ?>
                                <div class="son_first" style="background-color: #ECECEC;">
                                    <p ><?php echo '<strong style="font-size: 20px; margin-left: 10px;">' . $row['event_title'] . '</strong> ' . '<span style="float: right; margin-right: 10px;">' . $eventDate . '</span>'; ?></p>
                                    <p><?php echo '<span style="margin-left: 10px;">' . $row['event_type'] . '</span> ' . '<span style="float: right; margin-right: 10px;">' . $eventDay . '</span>'; ?></p>
                                    <p class="ml-2 fst-italic">Type: Event</p>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                            
                            <div class="holiday-content">
                            <div class="first_holiday_content">
                                <?php

                              

                                // // Display the start and end dates
                                // echo "Start date: " . $startDate . "<br>";
                                // echo "End date: " . $endDate;

                                $query = "SELECT * FROM holiday_tb WHERE holiday_type != 'Regular Working Day' AND `date_holiday` BETWEEN '$startDate' AND '$endDate' ORDER BY `date_holiday` ASC";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $holidayDate = date('Y-m-d', strtotime($row['date_holiday']));
                                    $holidayDay = date('l', strtotime($row['date_holiday']));
                                ?>
                                <div class="son_holiday" style="background-color: #ECECEC;">
                                    <p><?php echo '<strong style="font-size: 20px; margin-left: 10px;">' . $row['holiday_title'] . '</strong> ' . '<span style="float: right; margin-right: 10px;">' . $holidayDate . '</span>'; ?></p>
                                    <p><?php echo '<span style="margin-left: 10px;">' . $row['holiday_type'] . '</span> ' . '<span style="float: right; margin-right: 10px;">' . $holidayDay . '</span>'; ?></p>
                                    <p class="ml-2 fst-italic">Type: Holiday</p>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                                </div>
                                    </div>

                                    <div class="emp-dash2-shortcut">
                                        <div class="emp-dash2-shortcut-title">
                                            <h1>Shortcut Link</h1>
                                        </div>
                                        <div class="emp-dash2-shortcut-card">
                                            <div class="emp-dash2-shortcut-icon">
                                            <i class="fa-solid fa-chevron-right"></i>
                                            </div>
                                            <div style="margin-top: 5px;">
                                                <a href="attendance.php">View Attendance</a>
                                            </div>
                                        </div>
                                        <div class="emp-dash2-shortcut-card"> 
                                            <div class="emp-dash2-shortcut-icon">
                                            <i class="fa-solid fa-chevron-right"></i>
                                            </div>
                                            <div style="margin-top: 5px;">
                                                <a href="overtime_req.php">File Overtime</a>
                                            </div>
                                        </div>
                                        <div class="emp-dash2-shortcut-card">
                                            <div class="emp-dash2-shortcut-icon">
                                            <i class="fa-solid fa-chevron-right"></i>
                                            </div>
                                            <div style="margin-top: 5px;">
                                                <a href="leaveReq.php">Leave Request</a>
                                            </div>
                                        </div>
                                        <div class="emp-dash2-shortcut-card">
                                            <div class="emp-dash2-shortcut-icon">
                                            <i class="fa-solid fa-chevron-right"></i>
                                            </div>
                                            <div style="margin-top: 5px;">
                                                <a href="#">View Payslip</a>
                                            </div>
                                        </div>
                                        <div class="emp-dash2-shortcut-card">
                                            <div class="emp-dash2-shortcut-icon">
                                            <i class="fa-solid fa-chevron-right"></i>
                                            </div>
                                            <div style="margin-top: 5px;">
                                                <a href="my_schedule.php">View Schedule</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>


    <!-- swiper -->

    <script>
        const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
        });
    </script>


<!-----------------------Script sa graph--------------------------------->
<!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('barChart').getContext('2d');
            var data = {
                labels: ['Label 1', 'Label 2', 'Label 3', 'Label 4', 'Label 5'],
                datasets: [{
                    label: 'Data',
                    data: [10, 20, 15, 25, 30],
                    backgroundColor: 'rgba(0, 123, 255, 0.5)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }]
            };

            var options = {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };

            var barChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });
        });
    </script> -->
<!-----------------------Script sa graph--------------------------------->

<!------------------------Script sa function ng Previous and Next Button--------------------------------------->    
<script>
var currentSlide = 0;
var slides = document.getElementsByClassName("announcement-slide");

function showSlide(n) {
  for (var i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[n].style.display = "block";
  currentSlide = n;
}

function prevSlide() {
  if (currentSlide > 0) {
    showSlide(currentSlide - 1);
  }
}

function nextSlide() {
  if (currentSlide < slides.length - 1) {
    showSlide(currentSlide + 1);
  }
}

showSlide(0); // Show the first slide initially


var announceContent = document.querySelector('.emp-dash2-announcement-content');
var prevButton = document.querySelector('.previous');
var nextButton = document.querySelector('.next-step');

announceContent.onscroll = function() {
  var scrollPosition = announceContent.scrollTop;

  // I-adjust ang posisyon ng mga prev at next button base sa scroll position
  prevButton.style.top = scrollPosition + announceContent.offsetHeight - prevButton.offsetHeight + 'px';
  nextButton.style.top = scrollPosition + announceContent.offsetHeight - nextButton.offsetHeight + 'px';
};

</script>
<!------------------------End Script sa function ng Previous and Next Button--------------------------------------->
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
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script> -->


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

<script src="js/dashboard.js"></script>
</body>

</html>