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
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">
    <link rel="stylesheet" href="css/myschedule.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <title>My Schedule - Employee</title>
</head>
<body>
<header>
     <?php
         include 'header.php';
     ?>
</header>

<style>
    .sidebars ul li{
        list-style: none;
        text-decoration:none;
        width: 287px;
        margin-left:-16px;
        line-height:30px;
       
    }

    .sidebars ul li .hoverable{
        height:55px;
    }

    .sidebars ul{
        height:100%;
    }

    .sidebars .first-ul{
        line-height:60px;
        height:100px;
    }

    .sidebars ul li ul li{
        width: 100%;
    }

    .table{
         width: 99.6%;
    }

    .content-wrapper{
         width: 85%
    }
</style>


<!------------------------------------Header and Button------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 15%;">
        <div class="content-wrapper mt-5">
          <div class="card" style="box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); width:1500px; height:800px; border-radius:20px;">
            <div class="card-body">
                <div class="row">
                        <div class="col-6">
                            <h2>Schedule</h2>
                        </div>
                        </div>  
<!------------------------------------Header, Dropdown and Button------------------------------------------------->




<!----------------------------------Syntax for Dropdown button------------------------------------------>
    <div class="official_panel">
            <div class="child_panel">
              <p class="empo_date_text">Date From</p>
              <input class="select_custom" type="date" name="" id="datestart" required>
            </div>
            <div class="child_panel">
              <div class="notif">
              <p class="empo_date_text">Date To</p>
              <p id="validate" class="validation">End date must beyond the start date</p>
            </div>
              <input class="select_custom" type="date" id="enddate" onchange="datefunct()" required>
            </div>
            <button class="btn_go" id="id_btngo">Go</button>
          </div>
<!------------------------------End Syntax for Dropdown button------------------------------------------------->
            

<!------------------------------------------Syntax ng Table-------------------------------------------------->
        <div class="row" >
            <div class="col-12 mt-2">
                    <div class="table-responsive">
                    <table id="order-listing" class="table">
                        <thead>
                            <tr>
                                <th style="display: none;">ID</th>
                                <th style="display: none;">Employee ID</th>
                                <th>Work Date</th>
                                <th>Work Day</th>
                                <th>Start Time</th> 
                                <th>End Time</th>
                                <th>Work Setup</th>
                                <th>Working Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                    $conn = mysqli_connect("localhost","root","","hris_db");

                    $query = "SELECT empschedule_tb.id, empschedule_tb.empid, empschedule_tb.sched_from, empschedule_tb.sched_to, empschedule_tb.schedule_name, schedule_tb.mon_timein, schedule_tb.mon_timeout,
                    schedule_tb.tues_timein,
                    schedule_tb.tues_timeout,
                    schedule_tb.wed_timein,
                    schedule_tb.wed_timeout,
                    schedule_tb.thurs_timein,
                    schedule_tb.thurs_timeout,
                    schedule_tb.fri_timein,
                    schedule_tb.fri_timeout,
                    schedule_tb.sat_timein,
                    schedule_tb.sat_timeout,
                    schedule_tb.sun_timein,
                    schedule_tb.sun_timeout
                    FROM
                    empschedule_tb
                    INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name;";

                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $schedFrom = $row['sched_from'];
                        $schedTo = $row['sched_to'];
                        $dateRange = "";
                        $currDate = $schedFrom;
                        while ($currDate <= $schedTo) {
                            $date = $currDate;
                            $dayOfWeek = date("l", strtotime($date));
                            $startTime = '';
                            $endTime = '';
                            //$breakTime = $row['break_time']; // fetch break time from database
                            switch ($dayOfWeek) {
                              case 'Monday':
                                $startTime = $row['mon_timein'];
                                $endTime = $row['mon_timeout'];
                                break;
                              case 'Tuesday':
                                $startTime = $row['tues_timein'];
                                $endTime = $row['tues_timeout'];
                                break;
                              case 'Wednesday':
                                $startTime = $row['wed_timein'];
                                $endTime = $row['wed_timeout'];
                                break;
                              case 'Thursday':
                                $startTime = $row['thurs_timein'];
                                $endTime = $row['thurs_timeout'];
                                break;
                              case 'Friday':
                                $startTime = $row['fri_timein'];
                                $endTime = $row['fri_timeout'];
                                break;
                              case 'Saturday':
                                $startTime = $row['sat_timein'];
                                $endTime = $row['sat_timeout'];
                                break;
                              case 'Sunday':
                                $startTime = $row['sun_timein'];
                                $endTime = $row['sun_timeout'];
                                break;
                            }
                            $dateRange .= $date . " ";

                                // Calculate working hours
                                $workingHours = "";
                                if (!empty($startTime) && !empty($endTime)) {
                                    $startTimestamp = strtotime($startTime);
                                    $endTimestamp = strtotime($endTime);
                                    $workingSeconds = $endTimestamp - $startTimestamp - 3600; // subtract 1 hour for lunchtime
                                    $workingHours = number_format($workingSeconds / 3600, 2); // format as 0.00
                                }
                            ?>
                        <tr>
                            <td style="display: none;"><?php echo $row['id']?></td>
                            <td style="display: none;"><?php echo $row['empid']?></td>
                            <td><?php echo $date ?></td>
                            <td><?php echo $dayOfWeek ?></td>
                            <td><?php echo $startTime?></td> 
                            <td><?php echo $endTime?></td>
                            <td><?php echo $row['schedule_name']?></td>  
                            <td><?php echo $workingHours?></td> 
                        </tr>
                        <?php
                            $currDate = date('Y-m-d', strtotime($currDate . ' +1 day'));
                        }
                    } 
                    ?>
                        </tbody>
                    </table>


<!------------------------------------End Syntax ng Table------------------------------------------------->    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- End custom js for this page-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="bootstrap js/template.js"></script>
<!-- Custom js for this page-->
<script src="bootstrap js/data-table.js"></script>
<!-- End custom js for this page-->

</body>
</html>