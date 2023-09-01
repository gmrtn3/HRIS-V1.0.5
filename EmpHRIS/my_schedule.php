<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php"); 
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

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/myschedule.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/my_scheduleResponsive.css">
    <title>My Schedule - Employee</title>
</head>
<body>
<header>
     <?php
         include 'header.php';
     ?>
</header>

<style>
    html{
        background-color: #f4f4f4 !important;
        overflow: hidden;
       
    }
    body{
        overflow: hidden;
        background-color: #F4F4F4 !important;
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
        margin-top: 20px;
        margin-right: -44px !important;
        margin-bottom: -15.5px !important;
        
    }


    #order-listing_previous{
        margin-top: 20px;
        margin-left: 12px !important;
    }

    /* Search Bar */
    #order-listing_filter label input{
        width: 278px;
        font-size: 17px;
        
    }

    /* Sorting Button Color */
    .dataTables_wrapper .dataTable thead .sorting:before, .dataTables_wrapper .dataTable thead .sorting_asc:before, .dataTables_wrapper .dataTable thead .sorting_desc:before, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:before, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:before {
        
        right: 1.2em;
        bottom: 0;
        color: #c0c1c2 !important;
        opacity: 1;
    } 

    .dataTables_wrapper .dataTable thead .sorting:after, .dataTables_wrapper .dataTable thead .sorting_asc:after, .dataTables_wrapper .dataTable thead .sorting_desc:after, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:after, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:after {
   
        right: 1.2em;
        top: 0;
        color: #c0c1c2 !important;
        opacity: 1;
    }



</style>


<!------------------------------------Header and Button------------------------------------------------->
    <div class="main-panel mt-5">
        <div class=" mt-1">
          <div class="card">
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
    <input class="select_custom" type="date" name="date_from" id="datestart" required>
  </div>
  <div class="child_panel">
    
      <p class="empo_date_text">Date To</p>
    
    <input class="select_custom" type="date" name="date_to" id="enddate" onchange="datefunct()" required>
  </div>
  <button class="btn_go" id="id_btngo" onclick="filterDates()">Apply Filter</button>
</div>
<!------------------------------End Syntax for Dropdown button------------------------------------------------->
<script>
  function filterDates() {
    var dateFrom = document.getElementById('datestart').value;
    var dateTo = document.getElementById('enddate').value;

    var url = 'my_schedule.php?date_from=' + dateFrom + '&date_to=' + dateTo;
    window.location.href = url;
  }
</script>

<!------------------------------------------Syntax ng Table-------------------------------------------------->
            <div class="row" >
                <div class="col-12 mt-2">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table id="order-listing" class="table" style="width: 100%; max-height: 590px;">
                        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
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

                                $dateFrom = $week_dates[0]; // set the default start date to the first date in the week
                                $dateTo = end($week_dates); // set the default end date to the last date in the week

                                // Check if the filter dates are submitted
                                if (isset($_GET['date_from']) && isset($_GET['date_to'])) {
                                $dateFrom = $_GET['date_from'];
                                $dateTo = $_GET['date_to'];
                                }

                                $query = "SELECT empschedule_tb.id, employee_tb.empid, empschedule_tb.sched_from, empschedule_tb.sched_to, empschedule_tb.schedule_name, schedule_tb.mon_timein, schedule_tb.mon_timeout,
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
                                        AND empschedule_tb.sched_from <= '$dateTo'
                                        AND empschedule_tb.sched_to >= '$dateFrom';";

                                $result = mysqli_query($conn, $query);
                                
                                $todayDate = date('Y-m-d'); // Get the current date

                                while ($row = mysqli_fetch_assoc($result)) {
                                $schedFrom = $row['sched_from'];
                                $schedTo = $row['sched_to'];
                                $dateRange = "";
                                $currDate = $dateFrom; // start from the selected start date

                                while ($currDate <= $schedTo && $currDate <= $dateTo) {
                                    if ($currDate >= $schedFrom) {
                                    $date = $currDate;
                                    $dayOfWeek = date("l", strtotime($date));
                                    $startTime = '';
                                    $endTime = '';

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
                                    $lunchbreak_start = '12:00:00';
                                    $lunchbreak_end = '13:00:00';

                                    if (($startTime == 'NULL') && ($endTime == 'NULL')) {
                                        $startTime = '-';
                                        $endTime = '-';
                                        $row['schedule_name'] = 'Restday';
                                        $workingHours = '0.00';
                                    } else if (!empty($startTime) && !empty($endTime)) {
                                        $startTimestamp = strtotime($startTime);
                                        $endTimestamp = strtotime($endTime);

                                        // Check if $startTime is less than $lunchbreak_start, then subtract 3600 seconds
                                        if ($startTimestamp < strtotime($lunchbreak_start)) {
                                            $workingSeconds = $endTimestamp - $startTimestamp - 3600; // subtract 1 hour for lunchtime
                                        } else {
                                            $workingSeconds = $endTimestamp - $startTimestamp; // no need to subtract lunchtime
                                        }

                                        if ($endTimestamp > strtotime($lunchbreak_start)){
                                            $workingSeconds = $endTimestamp - $startTimestamp - 3600; // subtract 1 hour for lunchtime
                                        }else {
                                            $workingSeconds = $endTimestamp - $startTimestamp; // no need to subtract lunchtime
                                        }
                                        $workingSeconds = abs($workingSeconds); // Get the absolute value
                                        $workingHours = number_format($workingSeconds / 3600, 2); // format as 0.00
                                    }
                                    ?>
                                    <tr  <?php if($date == $todayDate){ echo "style='background-color: #CCCCFF;' "; }else{ echo "background-color: inherit";} ?>>
                                        <td style="display: none;"><?php echo $row['id'] ?></td>
                                        <td style="display: none;"><?php echo $row['empid'] ?></td>
                                        <td style="font-weight: 400" ><?php echo $date ?></td>
                                        <td style="font-weight: 400" id="date_today"><?php echo $dayOfWeek ?></td>
                                        <?php if (($startTime === null || $startTime === '') && ($endTime === null || $endTime === '')) : ?>
                                        <td style='font-weight: 400'>-</td>
                                        <td style='font-weight: 400'>-</td>
                                        <td style='font-weight: 400'>Restday</td>
                                        <td style='font-weight: 400'>0.00</td>
                                        <?php else : ?>
                                        <td style='font-weight: 400'><?php echo !is_null($startTime) ? date("h:i A", strtotime($startTime)) : '-' ?></td>
                                        <td style='font-weight: 400'><?php echo !is_null($endTime) ? date("h:i A", strtotime($endTime)) : '-' ?></td>
                                        <td style='font-weight: 400'><?php echo $row['schedule_name'] ?></td>
                                        <td style='font-weight: 400'><?php echo $workingHours ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php
                                    }
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
</html>