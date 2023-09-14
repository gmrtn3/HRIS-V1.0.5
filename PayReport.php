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

// NiRetrieve ko ang decode JSON data galing empListForm.php para mapalitan din ang label ng allowance
$data = json_decode(file_get_contents('php://input'), true);

// Update session variables with the new labels (if they are set)
if (isset($data['newTranspoLabel'])) {
    $_SESSION['newTranspoLabel'] = $data['newTranspoLabel'];
}
if (isset($data['newMealLabel'])) {
    $_SESSION['newMealLabel'] = $data['newMealLabel'];
}
if (isset($data['newInternetLabel'])) {
    $_SESSION['newInternetLabel'] = $data['newInternetLabel'];
}

// Define default labels or use session data
$newTranspoLabel = isset($_SESSION['newTranspoLabel']) ? $_SESSION['newTranspoLabel'] : '';
$newMealLabel = isset($_SESSION['newMealLabel']) ? $_SESSION['newMealLabel'] : '';
$newInternetLabel = isset($_SESSION['newInternetLabel']) ? $_SESSION['newInternetLabel'] : '';
//End ng ajax para label
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
    <link rel="stylesheet" href="css/payroll_reportResponsive.css">
    <title>Payroll Report</title>
</head>
<body>
<header>
</header>


                        <div class="card">
                          <div class="card-body">
                             <div class="row">
                                <div class="col-6">
                                    <p style="font-size: 25px; padding: 10px">Payroll Report</p>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                    <a href="payroll_report.php" style="text-decoration: none;" class="btn btn-primary">Back</a>
                                </div>
                            </div>

                            <?php
                            if (isset($_POST['cutoffID']) && isset($_POST['startDate']) && isset($_POST['endDate'])) {
                            $cutoffID = $_POST['cutoffID'];
                            $startDate = $_POST['startDate'];
                            $endDate = $_POST['endDate'];

                            $Getprb = "SELECT payslip_report_tb.id,
                            payslip_report_tb.cutoff_ID,
                            payslip_report_tb.pay_rule,
                            payslip_report_tb.empid,
                            payslip_report_tb.col_frequency,
                            payslip_report_tb.cutoff_startdate, 
                            payslip_report_tb.cutoff_enddate, 
                            payslip_report_tb.working_days, 
                            payslip_report_tb.basic_hours, 
                            payslip_report_tb.basic_amount_pay, 
                            payslip_report_tb.overtime_hours, 
                            payslip_report_tb.overtime_amount, 
                            payslip_report_tb.transpo_allow, 
                            payslip_report_tb.meal_allow, 
                            payslip_report_tb.net_allowance, 
                            payslip_report_tb.add_allow, 
                            payslip_report_tb.allowances, 
                            payslip_report_tb.number_leave, 
                            payslip_report_tb.paid_leaves, 
                            payslip_report_tb.holiday_pay, 
                            payslip_report_tb.total_earnings, 
                            payslip_report_tb.absence, 
                            payslip_report_tb.absence_deduction, 
                            payslip_report_tb.sss_contri, 
                            payslip_report_tb.philhealth_contri, 
                            payslip_report_tb.tin_contri, 
                            payslip_report_tb.pagibig_contri, 
                            payslip_report_tb.other_contri, 
                            payslip_report_tb.totalGovern_tb,
                            payslip_report_tb.total_late, 
                            payslip_report_tb.tardiness_deduct, 
                            payslip_report_tb.ut_time, 
                            payslip_report_tb.undertime_deduct, 
                            payslip_report_tb.number_lwop, 
                            payslip_report_tb.lwop_deduct, 
                            payslip_report_tb.total_deduction, 
                            payslip_report_tb.net_pay,
                            payslip_report_tb.date_time,
                            employee_tb.empid,
                            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name FROM payslip_report_tb INNER JOIN
                            employee_tb ON employee_tb.empid = payslip_report_tb.empid WHERE cutoff_ID = '$cutoffID' AND cutoff_startdate = '$startDate' AND cutoff_enddate = '$endDate'";
                            $query_run = mysqli_query($conn, $Getprb);

                    while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                        <div class="report-container d-flex flex-row">
                                <div class="table-responsive" id="table-responsiveness" style="width: 500px;">
                                    <table class="table table-bordered">
                                        <thead style="background-color: #cecece">
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                        </thead>
                                            <tr>
                                                <td style="font-weight: 400"><?php echo $row['empid']?></td>
                                                <td style="font-weight: 400"><?php echo $row['full_name']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 400">Salary Total</td>
                                                <td style="font-weight: 400"><?php echo $row['total_earnings']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 400; color: red;">Salary Total Deduction</td>
                                                <td style="font-weight: 400; color: red;"><?php echo $row['total_deduction']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: 400">Benefit Sharing Deducted</td>
                                                <td style="font-weight: 400; color: red;"><?php echo $row['totalGovern_tb']?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-style: bold; font-weight: 400">Salary Final Total: </td>
                                                <td style="font-weight: 400"><?php echo $row['net_pay']?></td>
                                            </tr>
                                    </table>
                                </div>

                        <div class="table-responsive" id="table-responsiveness">
                                <table class="table table-bordered" style="width: 200%; overflow-x: auto;">
                                <thead style="background-color: #cecece">
                                        <th>Total Days</th>
                                        <th style="display: none;">Total Hours</th>
                                        <th style="display: none;">Overtime Hours</th>
                                        <th>Overtime Pay</th>
                                        <th><?php echo $newTranspoLabel; ?></th>
                                        <th><?php echo $newMealLabel; ?></th>
                                        <th><?php echo $newInternetLabel; ?></th>
                                        <th>Other</th>
                                        <th style="display: none;">Allowances</th>
                                        <th style="display: none;">Number of Leave</th>
                                        <th>Leave Pay</th>
                                        <th>Holiday Pay</th>
                                        <th style="display: none;">Absent</th>
                                        <th>Absent Deduction</th>
                                        <th>Late</th>
                                        <th>Late Deduction</th>
                                        <th>Undertime</th>
                                        <th>Undertime Deduction</th>
                                        <th style="display: none;">LWOP</th>
                                        <th>LWOP Deduction</th>
                                        <th>SSS</th>
                                        <th>Philhealth</th>
                                        <th>TIN</th>
                                        <th>Pag-Ibig</th>
                                        <th>Other Government</th>
                                </thead>     
                                    <tbody>
                                        <tr>
                                        <td style="font-weight: 400;"> <?php echo $row['working_days']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['basic_hours']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['overtime_hours']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['overtime_amount']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['transpo_allow']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['meal_allow']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['net_allowance']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['add_allow']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['allowances']?></td>
                                        <td style="font-weight: 400; display: none;"> <?php echo $row['number_leave']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['paid_leaves']?></td>
                                        <td style="font-weight: 400;"> <?php echo $row['holiday_pay']?></td>
                                        <td style="font-weight: 400; color: red; display: none;"> <?php echo $row['absence']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['absence_deduction']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['total_late']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['tardiness_deduct']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['ut_time']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['undertime_deduct']?></td>
                                        <td style="font-weight: 400; color: red; display: none;"> <?php echo $row['number_lwop']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['lwop_deduct']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['sss_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['philhealth_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['tin_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['pagibig_contri']?></td>
                                        <td style="font-weight: 400; color: red;"> <?php echo $row['other_contri']?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
          </div>
   




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