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
    }
}
include 'config.php';

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>


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


    <link rel="stylesheet" href="css/gnrate_payroll.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dtRecordsResponsives.css">
    <link rel="stylesheet" href="css/gnratepayrollVIEW.css">
    <title>Payroll</title>
</head>
<body>
<header>
    <?php
        include 'header.php';
    ?>
</header>

<style>
/* Style the tabs */
.tab {
  display: flex;
  flex-direction: row;
}

.first button,
.second button,
.third button {
  background-color: inherit;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
}

/* Apply background color to active tab button */
.first.active button,
.second.active button,
.third.active button {
  background-color: #ccc;
}

/* Change color on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create a container for the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border-top: none;
}

.pagination{
        margin-right: 74px !important;
        
    }
    .sorting_asc{
        color: black !important;
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
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
</style>

 <!-- Modal -->
 <div class="modal fade" id="Payrollbootstrap" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Payroll</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>   
            <div class="modal-body">
            <!-- <h4 id="employeeName"></h4> -->
            <input type="hidden" id="checktable" name="EmployeeID">
            <div class="tab">
                <div class="first active">
                    <button class="tablinks" id="tabbutton" onclick="openTab(event, 'Table1')">Payslip Details</button>
                </div>

                <div class="second">
                    <button class="tablinks" onclick="openTab(event, 'Table2')">Deduction</button>
                </div>

                <div class="third">
                    <button class="tablinks" onclick="openTab(event, 'Table3')">Loan Details</button>
                </div>
            </div>

                    <div id="Table1" class="tabcontent" style="display: block;">
                        <div class="table-responsive" id="table-responsiveness">
                            <table class="table">
                                <thead style="background-color: #D8D8F5;">
                                <tr>
                                    <th>Fixed Salary Rate</th>
                                    <th style="display: none;">Basic Pay</th>
                                    <th>Actual Working Days</th>
                                    <th style="display: none;">Daily Wage</th>
                                    <th>Overtime</th>
                                    <th>OverTime Pay</th>
                                    <th>Holiday Pay</th>
                                    <th>Number of Leave</th>
                                    <th>Leave Pay</th>
                                    <th><?php echo $newTranspoLabel; ?></th>
                                    <th><?php echo $newMealLabel; ?></th> 
                                    <th><?php echo $newInternetLabel; ?></th>
                                    <th>Other Allowances</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                                <tr>
                                    <td style="font-weight: 400;" id="salaryRate"></td>
                                    <td style="font-weight: 400; display: none;" id="basicpay"></td>
                                    <td style="font-weight: 400;" id="acDays"></td>
                                    <td style="font-weight: 400; display: none;" id="drates"></td>
                                    <td style="font-weight: 400;" id="ot_shours"></td>
                                    <td style="font-weight: 400;" id="overtime"></td>
                                    <td style="font-weight: 400;" id="holiPay"></td>
                                    <td style="font-weight: 400;" id="leaveDate"></td>
                                    <td style="font-weight: 400;" id="leavePay"></td>
                                    <td style="font-weight: 400;" id="transport"></td>
                                    <td style="font-weight: 400;" id="meal"></td>
                                    <td style="font-weight: 400;" id="internet"></td>
                                    <td style="font-weight: 400;" id="other"></td>
                                    <td style="font-weight: 400;" id="addtotal"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div id="Table2" class="tabcontent">
                        <div class="table-responsive" id="table-responsiveness">
                            <table class="table">
                                <thead style="background-color: #D8D8F5;">
                                <tr>
                                    <th>Absent</th>
                                    <th>Absent Deduction</th>
                                    <th>Late</th>
                                    <th>Late Deduction</th>
                                    <th>Undertime</th> 
                                    <th>Undertime Deduction</th>
                                    <th>LWOP</th>
                                    <th>LWOP Deduction</th>
                                    <th style="display: none;">Basic Hours</th>                                    
                                    <th>SSS</th> 
                                    <th>Philhealth</th>
                                    <th>Pagibig</th>
                                    <th>Tin</th>
                                    <th>Other Government</th>
                                    <th>Total Deduction</th>
                                </tr>
                            </thead>
                                <tr>
                                    <td style="font-weight: 400; color: red;" id="absence"></td>
                                    <td style="font-weight: 400; color: red;" id="absencededucts"></td>
                                    <td style="font-weight: 400; color: red;" id="late"></td>
                                    <td style="font-weight: 400; color: red;" id="lateDeduct"></td>
                                    <td style="font-weight: 400; color: red;" id="undertime"></td>
                                    <td style="font-weight: 400; color: red;" id="utDeduct"></td>
                                    <td style="font-weight: 400; color: red;" id="lwopnumber"></td>
                                    <td style="font-weight: 400; color: red;" id="lwopkaltas"></td>
                                    <td style="display: none; font-weight: 400; color: red;" id="basichours"></td>
                                    <td style="font-weight: 400; color: red;" id="sss"></td>
                                    <td style="font-weight: 400; color: red;" id="philhealth"></td>
                                    <td style="font-weight: 400; color: red;" id="pagibig"></td>
                                    <td style="font-weight: 400; color: red;" id="tin"></td>
                                    <td style="font-weight: 400; color: red;" id="otherContributions"></td>
                                    <td style="font-weight: 400; color: red;" id="total_Deductions"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div id="Table3" class="tabcontent">
                        <div class="table-responsive" id="table-responsiveness">
                            <table class="table">
                                <thead style="background-color: #D8D8F5;">
                                <tr>
                                    <th>Loan Type</th>
                                    <th>Payable Amount</th>
                                    <th>Amortization</th>
                                    <th>Balance Amount</th>
                                    <th>Cut Off Number</th>
                                    <th>Applied Cut Off</th>
                                    <th>Loan Status</th>
                                    <th>Loan Date</th>
                                    <th>Time Stamp</th>
                                </tr>
                            </thead>
                                <tr>
                                    <td id="loantype"></td>
                                    <td id="payable"></td>
                                    <td id="amortization"></td>
                                    <td id="balance"></td>
                                    <td id="cutoffnum"></td>
                                    <td id="applied"></td>
                                    <td id="loanstatus"></td>
                                    <td id="loandate"></td>
                                    <td id="timestamp"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
            </div> <!---modal body--->
        </div>
    </div>
</div> <!---Modal End--->


<!------------------------------------------------- Header ------------------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">

                <div class="row">
                    <div class="col-6">
                        <p style="font-size: 25px; padding: 10px">Generate Payroll</p>
                    </div>
                    <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printAllButton">
                            View Payslip
                        </button>
                    </div>
                </div>
<!------------------------------------------------- End Of Header -------------------------------------------> 


<!-- Modal -->
<div class="modal fade" id="printAllButton" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="content_modal">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Payslip</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="generate_all_payslip.php" method="post">
      <div class="modal-body">
       <h4>Employee's Payslip</h4>
       <input type="hidden" name="name_btnview" value="<?php echo isset($_POST['name_btnview']) ? $_POST['name_btnview'] : ''; ?>">
      </div>
      <div class="modal-footer">
        <button type="submit" name="printAll" class="btn btn-primary" id="printAllButton">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!----------------------------------------select button and text input--------------------------------------->
<div class="container-select">
            <div class="input-container">
              <p class="demm-text">Select Department</p>
              <?php
                include('config.php');

                $sql = "SELECT col_ID, col_deptname FROM dept_tb";
                $result = mysqli_query($conn, $sql);
                
                $Department = isset($_GET['department_name']) ? ($_GET['department_name']) : '';

                $options = "";
                $options .= "<option class='select-btn form-select-m' aria-label='.form-select-sm example' value='All Department'" .($Department == 'All Department' ? ' selected' : '').">All Department</option>";
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = ($Department == $row['col_ID']) ? 'selected' : '';
                    $options .= "<option value='" . $row['col_ID'] . "' " . $selected . ">" . $row['col_deptname'] . "</option>";
                }
                ?>
                  <select class="select-btn form-select-m" aria-label=".form-select-sm example" name="department" id="select_department" style="padding: 10px;">
                      <option value="" disabled selected>Select Department</option>
                      <?php echo $options; ?>
                  </select>
            </div>
                
            <div class="input-container">
                <p class="demm-text">Select Employee</p>
                  <label for="employee"></label>
                    <select  class="select-btn form-select-m" aria-label=".form-select-sm example" name="employee" id="select_employee" style="padding: 10px;" disabled>
                        <option value="" disabled selected>Select Employee</option>
                    </select>
              </div>

                <div class="input-container">
                    <p class="demm-text">Date From</p>
                    <input class="select-btn" type="date" name="date_from" id="datestart" required>
                </div>
                <div class="input-container">
                    <div class="notif">
                    <p class="demm-text">Date To</p>
                    </div>
                    <input class="select-btn" type="date" name="date_to" id="enddate" onchange="datefunct()" required>
                </div>
                <button id="arrowBtn" onclick="filterAttReport()"> &rarr; Apply Filter</button>
 </div> <!--Container Select-->
<!----------------------------------------select button and text input--------------------------------------->



<!-------------------------------------------------TABLE START------------------------------------------->
                        <div class="table-responsive mt-4" id="table-responsiveness">
                             <table id="order-listing" class="table">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Cut Off Start</th>
                                            <th>Cut Off End</th>
                                            <th>Cut Off Number</th>
                                            <th style="display: none;">Salary Rate</th>
                                            <th style="display: none;">Total Late</th>
                                            <th style="display: none;">Total Undertime</th> 
                                            <th style="display: none;">Basic Hours</th>
                                            <th style="display: none;">Basic Pay</th>
                                            <th style="display: none;">Basic OT Pay</th> 
                                            <th style="display: none;">SSS</th> 
                                            <th style="display: none;">Philhealth</th>
                                            <th style="display: none;">Pagibig</th>
                                            <th style="display: none;">Tin</th>
                                            <th>Net Pay</th>
                                            <th style="display: none;">Transportation Allowance</th>
                                            <th style="display: none;">Meal Allowance</th> 
                                            <th style="display: none;">Internet Allowance</th>
                                            <th style="display: none;">Other Allowances</th>
                                            <th style="display: none;">Loan Type</th>
                                            <th style="display: none;">Payable Amount</th>
                                            <th style="display: none;">Amortization</th>
                                            <th style="display: none;">Balance Amount</th>
                                            <th style="display: none;">Cut Off Number</th>
                                            <th style="display: none;">Applied Cut Off</th>
                                            <th style="display: none;">Loan Status</th>
                                            <th style="display: none;">Loan Date</th>
                                            <th style="display: none;">Time Stamp</th>
                                            <th style="display: none;">Total Work</th>
                                            <th style="display: none;">Employee Salary</th>
                                            <th style="display: none;">OT Total hours</th>
                                            <th style="display: none;">OT Amount</th>
                                            <th style="display: none;">Allowance</th>
                                            <th style="display: none;">Leaves</th>
                                            <th style="display: none;">Other Deduction</th>
                                            <th style="display: none;">Holiday Payment</th>
                                            <th style="display: none;">Late Deduction</th>
                                            <th style="display: none;">UT Deduction</th>
                                            <th style="display: none;">LWOP Deduct</th>
                                            <th style="display: none;">Payslip Netpay</th>
                                            <th style="display: none;">Total Earnings</th>
                                            <th style="display: none;">Total Deduction</th>
                                            <th style="display: none;">Employee Status</th>
                                            <th style="display: none;">Frequency</th>
                                            <th>Actual Working Days</th>
                                            <th style="display: none;">Cut off ID</th>
                                            <th style="display: none;">Daily Wage</th>
                                            <th style="display: none;">Leave with Pay</th>
                                            <th style="display: none;">Absent</th>
                                            <th style="display: none;">Absent Deduction</th>
                                            <th style="display: none;">Number of LWOP</th>
                                            <th style="display: none;">Pay Rule</th>
                                            <th style="display: none;">Transport</th>
                                            <th style="display: none;">Meal</th>
                                            <th style="display: none;">Internet</th>
                                            <th style="display: none;">Other Allowances</th>
                                            <th style="display: none;">Total Late</th>
                                            <th style="display: none;">Undertime Hours</th>
                                            <th style="display: none;">Total LWOP</th>
                                            <th style="display: none;">Total Government</th>
                                            <th style="display: none;">Actual Deduction</th>
                                            <th>View Details</th>
                                            <th>Print</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    include 'config.php';

                                    if(isset($_POST['name_btnview'])){

                                    $cutOffID = $_POST['name_btnview'];
                                    $Getcutoff = "SELECT * FROM cutoff_tb WHERE `col_ID` = '$cutOffID'";
                                    $Getrun = mysqli_query($conn, $Getcutoff);
                                    $Cutoffrow = mysqli_fetch_assoc($Getrun);
                                    $cutoffType = $Cutoffrow['col_type'];
                                    $cutoffMonth = $Cutoffrow['col_month'];
                                    $cutoffYear = $Cutoffrow['col_year'];
                                    $cutoffNumber = $Cutoffrow['col_cutOffNum'];
                                    $str_date = $Cutoffrow['col_startDate'];
                                    $end_date = $Cutoffrow['col_endDate'];
                                    $Frequency = $Cutoffrow['col_frequency'];

                                    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                                    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
                                    $department = $_GET['department_name'] ?? '';
                                    $employee = $_GET['empid'] ?? '';

                                    $CheckEmpid = "SELECT * FROM empcutoff_tb WHERE `cutOff_ID` = '$cutOffID'";
                                    $runEmpid = mysqli_query($conn, $CheckEmpid);
                                    while($row = mysqli_fetch_assoc($runEmpid)){
                                        $EmployeeID = $row['emp_ID'];
                                    
                                        $query_settings_salary = "SELECT * FROM settings_company_tb";
                                        $result_settings_salary = mysqli_query($conn, $query_settings_salary);

                                        $row_settings_salary = mysqli_fetch_assoc($result_settings_salary);

                                        $sql_empSched = mysqli_query($conn, "SELECT *  FROM empschedule_tb WHERE `empid` = '$EmployeeID'");
                                        //need pa ma fetch sa between sa dates na naselect na month sa dropdown
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

                                        //-----------Syntax sa pagget ng attendance kasama ang daily rate------//
                                        $sql_attndces = mysqli_query($conn, "SELECT 
                                            *, 
                                            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name
                                        FROM employee_tb
                                        WHERE empid = $EmployeeID
                                        ");

                                        if(mysqli_num_rows($sql_attndces) > 0){
                                           $row_emp = mysqli_fetch_assoc($sql_attndces);
                                           $EmpDrate = $row_emp['drate'];
                                           $EmpOTrate = $row_emp['otrate'];
                                           $EmpStatus = $row_emp['status'];

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
                                                }//Tuesday

                                                else if($day_of_week === 'Wednesday'){
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
                                                }//Wednesday

                                                else if($day_of_week === 'Thursday'){
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
                                                }//Thursday

                                                else if($day_of_week === 'Friday'){
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
                                                }//Friday

                                                else if($day_of_week === 'Saturday'){
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
                                                }//Saturday

                                                else if($day_of_week === 'Sunday'){
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
                                                }//Sunday

                                           }//foreach close tag

                                        } else {
                                            echo "You cannot generate a payslip for employee with no attendance";
                                        }
                                      } else {
                                        echo "No results found ";
                                      }
                                      //-----------------Syntax end sa attendance-----------------//

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
                                        
                                        //-------------Computation sa pagdeduct ng undertime kung may naapprove na ut request------//
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

                                                // echo '<br>' . $UT_LATE_DEDUCT_TOTAL;
                                            //-------------End Computation sa pagdeduct ng undertime kung may naapprove na ut request------//

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
                                                $Totalallowance = $row_atteeee['Total_allowanceStandard'];
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

                                    
                                    // if ($Frequency === 'Monthly'){
                                    //     $allowance = ($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / $working_days;
                                    //     $allowance = number_format($allowance, 2); //convert into two decimal only
                                    //     $allowance = str_replace(',', '', $allowance); // Remove comma

                                    // } 
                                    // else if ($Frequency === 'Semi-Month'){
                                    //     $allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 2) / $working_days;
                                    //     $allowance = number_format($allowance, 2); //convert into two decimal only
                                    //     $allowance = str_replace(',', '', $allowance); // Remove comma

                                    //     $first_cutOFf = '1';
                                    //     $last_cutoff = '2';                              
                                    // }
                                    // else if ($Frequency === 'Weekly'){
                                    //     $allowance = (($row_atteeee['Total_allowanceStandard'] + $row_addAllowance['total_sum_addAllowance']) / 4) / $working_days;
                                    //     $allowance = number_format($allowance, 2); //convert into two decimal only
                                    //     $allowance = str_replace(',', '', $allowance); // Remove comma

                                    //     $first_cutOFf = '1';
                                    //     $last_cutoff ='4';    
                                    // }

                                    
                                    
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
                               //PARA SA PAG GET NG TOTAL UNDERTIME NG EMPLOYEE 
                                // $UT_time = "0H:0M";
                                // $result_table_UT = mysqli_query($conn, " SELECT
                                //     CONCAT(
                                //         FLOOR(
                                //             SUM(TIME_TO_SEC(total_undertime)) / 3600
                                //             ),
                                //             'H:',
                                //         FLOOR(
                                //             (
                                //             SUM(TIME_TO_SEC(total_undertime)) % 3600
                                //             ) / 60
                                //         ),
                                //             'M'
                                //     ) AS total_hours_minutesUndertime
                                // FROM 
                                //     `undertime_tb` 
                                // WHERE `empid` = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date' AND `status` = 'Approved'");
        
                                // if(mysqli_num_rows($result_table_UT) > 0) {
                                //    $row_table_UT = mysqli_fetch_assoc($result_table_UT);
                                //     $UT_time = $row_table_UT['total_hours_minutesUndertime'];  
    
                                // }

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

                               //PARA SA PAG GET NG TOTAL UNDERTIME NG EMPLOYEE END 

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
                                dept_tb.col_ID,
                                dept_tb.col_deptname,
                                employee_tb.department_name,
                                employee_tb.empid,
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
                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
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
                                dept_tb.col_ID,
                                dept_tb.col_deptname,
                                employee_tb.department_name,
                                employee_tb.empid,
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
                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                LEFT JOIN allowancededuct_tb ON employee_tb.empid = allowancededuct_tb.id_emp
                                LEFT JOIN payroll_loan_tb ON employee_tb.empid = payroll_loan_tb.empid

                            WHERE (attendances.status = 'Present' OR attendances.status = 'On-Leave')  AND employee_tb.empid = '$EmployeeID' AND `date` BETWEEN  '$str_date' AND  '$end_date'";
                        }
                        if (!empty($department) && $department != 'All Department') {
                            $sql .= " AND dept_tb.col_deptname = '$department'";
                        }

                        if (!empty($employee) && $employee != 'All Employee') {
                            $sql .= " AND employee_tb.empid = '$employee'";
                        }

                        if (!empty($dateFrom) && !empty($dateTo)) {
                            $sql .= " AND attendances.date BETWEEN '$dateFrom' AND '$dateTo'";
                        }
                        $result = $conn->query($sql);
               
                      
                                    
                                    
                                        //read data
                                        while($row = $result->fetch_assoc()){
                                            $empLate = $row['total_hours_minutesLATE'];
                                            if ($Frequency === 'Monthly'){

                                                $Empsalary = $row['Salary_of_Month'];
                                                @$salary_of_month = $row['Salary_of_Month'];
                                                if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                    @$salary_of_month = $salary_of_month - ($row_emp['drate'] * $number_of_absent);

                                                    $absenceDeduct = $EmpDrate * $number_of_absent;
                                                }
                                                
                                                $sss = $row['sss_amount'];
                                                $philHealth = $row['philhealth_amount'];
                                                $pagibig_amount = $row['pagibig_amount'];
                                                $tin_amount = $row['tin_amount'];

                                                $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;

                                            } 
                                            else if ($Frequency === 'Semi-Month'){
                                                $Empsalary = $row['Salary_of_Month'] / 2;
                                                @$salary_of_month = ($row['Salary_of_Month']) / 2;

                                                if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                    @$salary_of_month = $salary_of_month - ($row_emp['drate'] * $number_of_absent);

                                                    $absenceDeduct = $EmpDrate * $number_of_absent;
                                                }

                                                $sss = $row['sss_amount'] / 2;      
                                                $philHealth = $row['philhealth_amount'] / 2;       
                                                $pagibig_amount = $row['pagibig_amount'] / 2;
                                                $tin_amount = $row['tin_amount'] / 2;
                                                $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;              
                                            }
                                            else if ($Frequency === 'Weekly'){
                                                $Empsalary = $row['Salary_of_Month'] / 4;
                                                @$salary_of_month = ($row['Salary_of_Month']) / 4;
                                                
                                                if($row_settings_salary['col_salary_settings'] === 'Fixed Salary'){
                                                    @$salary_of_month = $salary_of_month - ($row_emp['drate'] * $number_of_absent);

                                                    $absenceDeduct = $EmpDrate * $number_of_absent;
                                                }

                                                $sss = $row['sss_amount'] / 4;
                                                $philHealth = $row['philhealth_amount'] / 4; 
                                                $pagibig_amount = $row['pagibig_amount'] / 4;
                                                $tin_amount = $row['tin_amount'] / 4; 
                                                $total_government_deduct = $sss + $philHealth + $pagibig_amount + $tin_amount;
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

                                                    $GovernmentBenefit = $sss + $philHealth + $pagibig_amount + $tin_amount + $row_governDeduct['total_sum_othe_deduct'];
                                                } else if ($Frequency === 'Semi-Month') {
                                                    $cutoff_deductGovern = $row_governDeduct['total_sum_othe_deduct'] / 2;

                                                    $GovernmentBenefit = $sss + $philHealth + $pagibig_amount + $tin_amount + $row_governDeduct['total_sum_othe_deduct']/2;
                                                } else if ($Frequency === 'Weekly') {
                                                    $cutoff_deductGovern = $row_governDeduct['total_sum_othe_deduct'] / 4;

                                                    $GovernmentBenefit = $sss + $philHealth + $pagibig_amount + $tin_amount + $row_governDeduct['total_sum_othe_deduct']/4;
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


                                            //Calculation sa payslip netpay modal
                                            // $PayslipNetpay = "₱ " . (($salary_of_month) + $Total_allowances + $OTamount + @$holiday_rate_with_dpay + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein)
                                            // - ($sss + $philHealth + $tin_amount + $pagibig_amount + $cutoff_deductGovern + $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN);
                                            $PayslipNetpay = "₱ " . number_format(($salary_of_month + $Total_allowances + $OTamount + @$holiday_rate_with_dpay + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein)
                                            - ($sss + $philHealth + $tin_amount + $pagibig_amount + $cutoff_deductGovern + $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN), 2);


                                            // echo $EmpDrate * $TotalworkDays + $Total_allowances + $OTamount + @$holiday_rate_with_dpay + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein - $sss - $philHealth -  $tin_amount - $pagibig_amount - $cutoff_deductGovern - $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN;

                                            //calculation ng total earning
                                            $totalEarn = $Empsalary + $holiday_rate_with_dpay + $Total_allowances  + $cutoff_OT + @$holiday_rate_with_dpay_OT + $num_holiday_not_timein;

                                            //deduction sa payslip
                                            $totalDeduct = $sss + $philHealth +  $tin_amount +  $pagibig_amount +  $cutoff_deductGovern +  $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN + $absenceDeduct;

                                            //Deduction sa paginsert sa payslip report tb
                                            $totalDeductions = $UT_LATE_DEDUCT_TOTAL + $total_deductionLOAN + $absenceDeduct;
                                            
                                        ?>
                                        <tr>
                                            <td><?php echo $EmployeeID ?></td>
                                            <td><?php echo $row_emp['full_name']?></td>
                                            <td><?php echo $cutoffMonth ?></td>
                                            <td><?php echo $cutoffYear ?></td>
                                            <td><?php echo $str_date ?></td>
                                            <td><?php echo $end_date ?></td>
                                            <td><?php echo $cutoffNumber?></td>
                                            <td style="display: none;"><?php echo number_format(($Empsalary),2); ?></td>
                                            <td style="display: none;"><?php echo $row['total_hours_minutesLATE'] ?></td>
                                            <td style="display: none;"><?php echo (empty($UT_time) || $UT_time === null) ? "0H:0M" : $UT_time; ?></td>
                                            <td style="display: none;"><?php echo $row['total_hours_minutestotalHours'] ?></td>
                                            <td style="display: none;"><?php echo ($salary_of_month ) - $UT_LATE_DEDUCT_TOTAL ?></td>
                                            <td style="display: none;"><?php echo $cutoff_OT ?></td>
                                            <td style="display: none;"><?php echo $sss ?></td>
                                            <td style="display: none;"><?php echo $philHealth ?></td>
                                            <td style="display: none;"><?php echo $pagibig_amount ?></td>
                                            <td style="display: none;"><?php echo $tin_amount  ?></td>
                                            <td><?php echo $PayslipNetpay?></td>
                                            <td style="display: none;"><?php echo $row['emptranspo'] === "" ? "0" : $row['emptranspo']?></td>
                                            <td style="display: none;"><?php echo $row['empmeal'] === "" ? "0" : $row['empmeal']?></td>
                                            <td style="display: none;"><?php echo $row['empinternet'] === "" ? "0" : $row['empinternet']?></td>
                                            <td style="display: none;"><?php echo empty($row['total_sum']) || $row['total_sum'] === null ? "0" : $row['total_sum']; ?></td>
                                            <td style="display: none;"><?php echo $row['loan_type'] ?></td>
                                            <td style="display: none;"><?php echo $row['payable_amount'] ?></td>
                                            <td style="display: none;"><?php echo $row['amortization'] ?></td>
                                            <td style="display: none;"><?php echo $row['col_BAL_amount'] ?></td>
                                            <td style="display: none;"><?php echo $row['cutoff_no'] ?></td>
                                            <td style="display: none;"><?php echo $row['applied_cutoff'] ?></td>
                                            <td style="display: none;"><?php echo $row['loan_status'] ?></td>
                                            <td style="display: none;"><?php echo $row['loan_date'] ?></td>
                                            <td style="display: none;"><?php echo $row['timestamp'] ?></td>
                                            <td style="display: none;"><?php echo $Totalwork ?></td>
                                            <td style="display: none;"><?php echo number_format(($SalaryEmp),2);?></td>
                                            <td style="display: none;"><?php echo $basic_OT_hours ?></td>
                                            <td style="display: none;"><?php echo number_format($OTamount, 2)?></td>
                                            <td style="display: none;"><?php echo $Total_allowances ?></td>
                                            <td style="display: none;"><?php echo number_format($PaidLeaves, 2)?></td>
                                            <td style="display: none;"><?php echo number_format($HolidayPayment, 2)?></td>
                                            <td style="display: none;"><?php echo $cutoff_deductGovern?></td>
                                            <td style="display: none;"><?php echo $Late_rate_to_deduct?></td>
                                            <td style="display: none;"><?php echo $Undertime_rate_to_deduct ?></td>
                                            <td style="display: none;"><?php echo number_format($LWOPdeduct, 2)?></td>
                                            <td style="display: none;"><?php echo $PayslipNetpay?></td>
                                            <td style="display: none;"><?php echo number_format(($totalEarn),2);?></td>
                                            <td style="display: none;"><?php echo number_format(($totalDeduct),2);?></td>
                                            <td style="display: none;"><?php echo $EmpStatus ?></td>
                                            <td style="display: none;"><?php echo $Frequency ?></td>
                                            <td><?php echo $TotalworkDays?></td>
                                            <td style="display: none;"><?php echo $cutOffID?></td>
                                            <td style="display: none;"><?php echo $EmpDrate?></td>
                                            <td style="display: none;"><?php echo $number_ofLeave_attStatus?></td>
                                            <td style="display: none;"><?php echo $number_of_absent?></td>
                                            <td style="display: none;"><?php echo number_format($absenceDeduct, 2)?></td>
                                            <td style="display: none;"><?php echo $number_LWOP_attStatus?></td>
                                            <td style="display: none;"><?php echo $row_settings_salary['col_salary_settings']?></td>
                                            <td style="display: none;"><?php echo $Transport?></td>
                                            <td style="display: none;"><?php echo $Meal?></td>
                                            <td style="display: none;"><?php echo $Internet?></td>
                                            <td style="display: none;"><?php echo $Otherallowance?></td>
                                            <td style="display: none;"><?php echo $empLate?></td>
                                            <td style="display: none;"><?php echo $UT_time?></td>
                                            <td style="display: none;"><?php echo $number_LWOP_attStatus?></td>
                                            <td style="display: none;"><?php echo number_format(($GovernmentBenefit),2) ?></td>
                                            <td style="display: none;"><?php echo number_format(($totalDeductions),2)?></td>
                                            <td><button type="button" class="btn btn-primary payrolldetails" data-bs-toggle="modal" data-bs-target="#Payrollbootstrap">View</button></td>
                                            <td><button type="button" class="btn btn-success textempID" data-bs-toggle="modal" data-bs-target="#viewPayslip">Payslip</button></td>
                                        </tr>
                                    <?php
                                     }
                                  } //While loop employeeID
                                }//END ng isset
                                ?>
                                </table>
                         </div>
                                 
                        <!-- Modal Payslip-->
                        <div class="modal fade" id="viewPayslip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                  <div class="modal-content" >
                                <form action="generate-pdf.php" method="post">
                                  <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">PAYSLIP</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>

                                <div class="modal-body" id="modal-body" style="height: 667px;">
                                <input type="hidden" name="cuttoff_id" id="id_cutoff_id">
                                <input type="hidden" id="rulePay">
                                <input type="hidden" name="employee_empid" id="id_employeeid">
                                <input type="hidden" name="table_frequency" id="id_table_frequency">
                                <input type="hidden" name="monthcut" id="cutoffmonth">
                                <input type="hidden" name="col_strCutoff" id="cutoffstarts">
                                <input type="hidden" name="col_endCutoff" id="cutoffends">
                                <input type="hidden" name="table_cutoffnum" id="id_table_cutoffnum"> 
                                <input type="hidden" name="employee_workdays" id="id_workdays">
                                <input type="hidden" id="emptotalworks">
                                <input type="hidden" id="empAmounts">
                                <input type="hidden" id="empOThour">
                                <input type="hidden" id="OTamounts">
                                <input type="hidden" id="transportss">
                                <input type="hidden" id="meals">
                                <input type="hidden" id="internets">
                                <input type="hidden" id="otherallow">
                                <input type="hidden" id="allowanceAmounts">
                                <input type="hidden" id="leavecount">
                                <input type="hidden" id="leaveAmounts">
                                <input type="hidden" id="holidayAmounts">
                                <input type="hidden" id="totalEarns">
                                <input type="hidden" id="absentcount">
                                <input type="hidden" id="absenceDeductions">
                                <input type="hidden" id="deduct_SSS">
                                <input type="hidden" id="deduct_phil">
                                <input type="hidden" id="deduct_TIN">
                                <input type="hidden" id="deduct_Pagibig">
                                <input type="hidden" id="deduct_Other">
                                <input type="hidden" id="lateNumber">
                                <input type="hidden" id="deduct_Late">
                                <input type="hidden" id="countUT">
                                <input type="hidden" id="deduct_UT">
                                <input type="hidden" id="numberLWOP">
                                <input type="hidden" id="deduct_LWOP">
                                <input type="hidden" id="totalDeductions">
                                <input type="hidden" id="netpayslips">
                                <input type="hidden" id="totalgovernbenefit">

                                    <div class="header_view">
                                        <img src="icons/logo_hris.png" width="70px" alt="">
                                        <p class="lbl_cnfdntial">CONFIDENTIAL SLIP</p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="comp_name">Slash Tech Solutions Inc.</p>
   
                                        <p class="lbl_payPeriod">Pay Period :</p>
                                        <p class="dt_mdl_from" id="cutoffstart" name="col_strCutoff"></p>
                                            
                                        <p class="lbl_to">TO</p>
                                        <p class="dt_mdl_TO" id="cutoffend" name="col_endCutoff"></p>


                                        <p class="lbl_stats">Employee Status :</p>
                                        <p class="p_statss" id="empstatus"></p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="emp_no">EMPLOYEE NO.   :</p>
                                        <p class="p_empid" id="employeeID" name="nameEmployee_Id"></p>
                                        <p class="p_payout">Payout        :</p>
                                        <p class="dt_pyout">
                                            <?php
                                                date_default_timezone_set('Asia/Manila');
                                                $current_date = date('Y / m / d');
                                                echo $current_date;
                                            ?>
                                        </p>
                                    </div>

                                    <div class="div1_mdl">
                                        <p class="emp_name">EMPLOYEE NAME  :</p>
                                        <p class="p_emp_name" id="id_p_emp_name"></p>
                                    </div>

                                    <div class="headbody">
                                        
                                        <div class="headbdy_pnl1">
                                            <p class="lbl_sss"></p>
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

                                    </div><!----headbody--->

                                    <div class="headbody2">

                                        <div class="headbdy_pnl1">
                                            <p class="lbl_earnings">Earnings</p>
                                            <p class="lbl_Hours">Hours</p>
                                            <p class="lbl_Amount">Amount</p>
                                        </div>

                                        <div class="headbdy_pnl2">
                                        <p class="lbl_earnings">Deduction</p>
                                            <p class="lbl_Hours">Hours</p>
                                            <p class="lbl_Amount">Amount</p>
                                        </div>

                                        <div class="headbdy_pnl3">
                                            <p class="lbl_Balance">NET PAY</p>
                                        </div>

                                 </div><!---headbody2-->

                                    <div class="headbody3">
                                        <div class="headbdy_pnl11">

                                            <div class="div_mdlcontnt_left">
                                                <p class="lbl_bsc_pay">Basic Pay</p>
                                                <p class="p_Thrs" id="empTotalwork" name="basic_total_work"></p>
                                                <p class="p_Tamount" id="empAmount" name="basic_salary_amount"></p>

                                            </div>

                                             <div class="div_mdlcontnt_left1">
                                                <p class="lbl_bsc_pay">Overtime Pay</p>
                                                <p class="p_Thrs" id="empOThours" name="overtime_hours_name"></p>
                                                <p class="p_Tamount" id="OTamount" name="overtime_amount_name"></p>
                                            </div>

                                            <div class="div_mdlcontnt_left2">
                                                <p class="lbl_bsc_pay">Allowance</p>
                                                <p class="p_Thrs"></p>
                                                <p class="p_Tamount" id="allowanceAmount" name="allowance_total_name"></p>
                                            </div>

                                            <div class="div_mdlcontnt_left3">
                                                <p class="lbl_bsc_pay">PAID LEAVES</p>
                                                <p class="p_Thrs"></p>
                                                <p class="p_Tamount" id="leaveAmount" name="paid_leave_name"></p>
                                            </div>

                                            <div class="div_mdlcontnt_left4">
                                                <p class="lbl_bsc_pay">HOLIDAY PAY</p>
                                                <p class="p_Thrs"></p>
                                                <p class="p_Tamount" id="holidayAmount" name="holiday_pay_name"></p>
                                            </div>

                                            <!-- <div class="div_mdlcontnt_left5">
                                                <p class="lbl_bsc_pay">HOLIDAY OT PAY</p>
                                                <p class="p_Thrs"></p>
                                                <p class="p_Tamount"><?php //echo $totalOT_pay_holiday + $totalOT_pay_holiday_restday;?></p>
                                            </div> -->

                                       </div><!--headbdy_pnl11-->
                                        
                                            <div class="headbdy_pnl22">
                                                <div class="div_mdlcontnt_mid">
                                                    <div class="div_mdlcontnt_mid_left">
                                                        <p class="lbl_hdmf">Tardiness</p>
                                                        <p class="lbl_hdmf">Undertime</p>
                                                        <p class="lbl_hdmf">LWOP</p>
                                                        <p class="lbl_sss_se">SSS SE CONTRI</p>
                                                        <p class="lbl_philhlt_c">PHILHEALTH CONTRI</p>
                                                        <p class="lbl_sss_se">TIN CONTRI</p>
                                                        <p class="lbl_philhlt_c">PAGIBIG CONTRI</p>
                                                        <p class="lbl_hdmf">OTHER CONTRI</p>

                                                        <p  style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    </div>    

                                                    <div class="hourcontent_mid">
                                                        <p class="latehour" id="latehour"></p>
                                                        <p class="utHour" id="underhour"></p>
                                                    </div>
                        
                                                    <div class="div_mdlcontnt_mid_right">
                                                    <p class="lbl_philhlt_c" id="deductLate" name="late_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductUT" name="undertime_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductLWOP" name="lwop_kaltas"></p>
                                                        <p class="lbl_sss_se" id="deductSSS" name="sss_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductphil" name="phil_kaltas"></p>
                                                        <p class="lbl_sss_se" id="deductTIN" name="tin_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductPagibig" name="pagibig_kaltas"></p>
                                                        <p class="lbl_philhlt_c" id="deductOther" name="other_kaltas"></p>
                                                        <p style = "margin-top : -10px;" class="lbl_advnc_p">
                                                    </div> 
                                                </div>
                                            </div>  <!---headbdy_pnl22--->
                                            
                                             <div class="headbdy_pnl33">
                                                <div class="div_mdlcontnt_right">
                                                <!-- NETPAY VALUE -->
                                                    <p class="p_balance" id="netpayslip" name="netpay_name">
                                                    </p>
                                                </div>
                                            </div><!--headbdy_pnl33--->
                                            

                                    </div><!--headbody3-->  



                                    <div class="headbody2">
                                        <div class="headbdy_pnl1">
                                            <p class="lbl_earnings">Total Earnings :</p>
                                            <p class="lbl_Hours" id="totalEarn" name="overall_earn">
                                        </div>

                                        <div class="headbdy_pnl2">
                                                <p class="lbl_deduct">Total Deduction : </p>
                                                <p class="lbl_Amount2" id="totalDeduction" name="overall_deduction_name"></p>
                                        </div>

                                        <div class="headbdy_pnl3">
                                        <!-- <p class="lbl_deduct">Net Total : </p> -->
                                        <p class="lbl_Balance"></p>
                                    </div>
                                    </div> <!---headbody2---->

                                </div><!----modal body---->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="pdfPrint" onclick="makePDF()">Print</button>
                                    <button type="button" class="btn btn-secondary" id="id_btn_close" data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



                      </div>
                  </div>
               </div>
            </div>
         </div>
   
<!---------------Script para sa pagpindot ng print all button at mapaginsert papuntang insert payslip--------------------->
<script>
document.getElementById("pdfPrint").addEventListener("click", function () {

    const dataToSend = {
                table_cutoff_id: document.getElementById("id_cutoff_id").value,
                table_pay_rule: document.getElementById("rulePay").value,
                table_employeeId: document.getElementById("id_employeeid").value,
                table_frequency: document.getElementById("id_table_frequency").value,
                table_cutmonth: document.getElementById("cutoffmonth").value,
                table_cutoffstart: document.getElementById("cutoffstarts").value,
                table_cutoffend: document.getElementById("cutoffends").value,
                table_cutoffnum: document.getElementById("id_table_cutoffnum").value,
                table_id_workdays: document.getElementById("id_workdays").value,
                // table_fullname: document.getElementById("id_p_emp_name").value,
                table_basictotalwork: document.getElementById("emptotalworks").value,
                table_basicempAmount: document.getElementById("empAmounts").value,
                table_othours: document.getElementById("empOThour").value,
                table_otamount: document.getElementById("OTamounts").value,
                table_transport: document.getElementById("transportss").value,
                table_meals: document.getElementById("meals").value,
                table_internett: document.getElementById("internets").value,
                table_otherAllowance: document.getElementById("otherallow").value,
                table_allowanceAmount: document.getElementById("allowanceAmounts").value,
                table_leave_number: document.getElementById("leavecount").value,
                table_leaveAmount: document.getElementById("leaveAmounts").value,
                table_holidayAmount: document.getElementById("holidayAmounts").value,
                table_totalEarn: document.getElementById("totalEarns").value,
                table_absentnumber: document.getElementById("absentcount").value,
                table_absentdeducts: document.getElementById("absenceDeductions").value,
                table_deductSSS: document.getElementById("deduct_SSS").value,
                table_deductphil: document.getElementById("deduct_phil").value,
                table_deductTIN: document.getElementById("deduct_TIN").value,
                table_deductPagibig: document.getElementById("deduct_Pagibig").value,
                table_deductOther: document.getElementById("deduct_Other").value,
                table_governmenttotal: document.getElementById("totalgovernbenefit").value,
                table_countLate: document.getElementById("lateNumber").value,
                table_deductLate: document.getElementById("deduct_Late").value,
                table_countUT: document.getElementById("countUT").value,
                table_deductUT: document.getElementById("deduct_UT").value,
                table_numberLWOP: document.getElementById("numberLWOP").value,
                table_deductLWOP: document.getElementById("deduct_LWOP").value,
                table_totalDeduction: document.getElementById("totalDeductions").value,
                table_netpayslip:document.getElementById("netpayslips").value
                
                
    };

    fetch("solo_payslip.php", {
        method: "POST",
        body: JSON.stringify(dataToSend),
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .then(data => {
        // Handle response if needed
        console.log(data);
    })
    .catch(error => {
        console.error("Error:", error);
    });
});

</script>

<!---------------Script para sa pagpindot ng print all button at mapaginsert papuntang insert payslip--------------------->      

<!-----------------------------------Script sa pagprint ng data payslip sa modal------------------------------------------->
<!-- <script>
window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;
function makePDF() {
  var employeeId = document.getElementById('id_employeeid').value;
  var empName = document.getElementById('id_p_emp_name').textContent;
  var Cutoff_Frequency = document.getElementById('id_table_frequency').value;
  var Cutoff_Numbers = document.getElementById('id_table_cutoffnum').value;
  var employee_workdays = document.getElementById('id_workdays').value;
  var cutoff_Id = document.getElementById('id_cutoff_id').value;

  html2canvas(document.querySelector("#modal-body"), {
    allowTaint: true,
    useCORS: true,
    scale: 1
  }).then(canvas => {
    var img = canvas.toDataURL("Payslip PDF");
        var doc = new jsPDF();
        doc.setFont('Arial');
        doc.getFontSize(11);
        doc.addImage(img, 'PNG', 7, 13, 195,105);
        var pdfFileName = empName + " - Payslip";
       doc.save(pdfFileName);

    // AJAX request to generate the PDF
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var response = xhr.responseText;
        if (response === "Done") {
          // PDF generated successfully
          window.location.href = "generatePayslip.php?msg=Successfully Generated the Payslip&pdfFile=" + encodeURIComponent(pdfFileName);
        } else {
          // PDF generation failed
          console.log(response);
        }
      }
    };
    xhr.open("POST", "generate-pdf.php", true);
    var formData = new FormData();
    formData.append("pdfData", img);
    formData.append("employeeId", employeeId);
    formData.append("Cutoff_Frequency", Cutoff_Frequency);
    formData.append("Cutoff_Numbers", Cutoff_Numbers);
    formData.append("employee_workdays", employee_workdays);
    formData.append("cutoff_Id", cutoff_Id);
    xhr.send(formData); // Send the FormData object directly
  });
}
</script> -->

<script type="text/javascript">
    $("body").on("click", "#pdfPrint", function () {
        let employeeId = document.getElementById('id_employeeid').value;
        let Cutoff_Frequency = document.getElementById('id_table_frequency').value;
        let Cutoff_Numbers = document.getElementById('id_table_cutoffnum').value;
        let employee_workdays = document.getElementById('id_workdays').value;
        let cutoff_Id = document.getElementById('id_cutoff_id').value;
        document.getElementById('id_btn_close').style.display="none";
        document.getElementById('pdfPrint').style.display="none";
        

        var emp_fullname = document.getElementById("id_p_emp_name");
        var fullname = emp_fullname.textContent;
        var currentDate = new Date();
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
        html2canvas($('#modal-body')[0], {
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
                            if (response === "Done") {
                                window.location.href = "generatePayslip.php?msg=Successfully Generated the Payslip";
                            } else {
                                console.log(response);
                            }
                        }
                    };
                    xhr.open("POST", "generate-pdf.php", true);
                    var formData = new FormData();
                    formData.append("pdfData", pdfData);
                    formData.append("employeeId", employeeId);
                    formData.append("Cutoff_Frequency", Cutoff_Frequency);
                    formData.append("Cutoff_Numbers", Cutoff_Numbers);
                    formData.append("employee_workdays", employee_workdays);
                    formData.append("cutoff_Id", cutoff_Id);
                    xhr.send(formData);
                    document.getElementById('id_btn_close').style.display="";
                    document.getElementById('download-pdf').style.display="";
                });
            }
        });
    });
</script>
<!-----------------------------------Script sa pagprint ng data payslip sa modal------------------------------------------->


<!------------------------------------Script para sa whole view data ng modal------------------------------------------------->
<script>
$(document).ready(function(){
    $('.payrolldetails').on('click', function(){
        $('#Payrollbootstrap').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        $('#checktable').val(data[0]);
        $('#employeeName').text(data[1]);
        $('#salaryRate').text(data[7]);
        $('#acDays').text(data[47]);
        $('#drates').text(data[49]);
        $('#ot_shours').text(data[33]);
        $('#overtime').text(data[34]);
        $('#holiPay').text(data[37]);
        $('#leaveDate').text(data[50]);
        $('#leavePay').text(data[36]);
        $('#transport').text(data[55]);
        $('#meal').text(data[56]);
        $('#internet').text(data[57]);
        $('#other').text(data[58]);
        $('#addtotal').text(data[43]);
        $('#absence').text(data[51]);
        $('#absencededucts').text(data[52]);

        $('#late').text(data[8]);
        $('#lateDeduct').text(data[39]);
        $('#undertime').text(data[9]);
        $('#utDeduct').text(data[40]);
        $('#basichours').text(data[10]);
        $('#basicpay').text(data[11]);
        $('#lwopnumber').text(data[53]);
        $('#lwopkaltas').text(data[41]);
        $('#sss').text(data[13]);
        $('#philhealth').text(data[14]);
        $('#pagibig').text(data[15]);
        $('#tin').text(data[16]);
        $('#otherContributions').text(data[38]);
        $('#total_Deductions').text(data[44]);



        //table3
        $('#loantype').text(data[22]);
        $('#payable').text(data[23]);
        $('#amortization').text(data[24]);
        $('#balance').text(data[25]);
        $('#cutoffnum').text(data[26]);
        $('#applied').text(data[27]);
        $('#loanstatus').text(data[28]);
        $('#loandate').text(data[29]);
        $('#timestamp').text(data[30]);
    });
});
</script>
<!---------------------------------End ng Script whole view data ng modal------------------------------------------>



<!--------------------------------- Script para sa payslip data ng modal------------------------------------------>
<script>
$(document).ready(function(){
    $('.textempID').on('click', function(){
        $('#viewPayslip').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        var employeeID = data[0];
        var empName = data[1];
        var cutoffMonth = data[2];
        var cutstart = data[4];
        var cutend = data[5];
        var CutoffNumber = data[6];
        var dSSS = data[13];
        var Philhealth = data[14];
        var Pagibig = data[15];
        var Tin = data[16];
        var Governtotal = data[62];
        var totalWork = data[31];
        var Amount = data[7];
        var OThours = data[33];
        var OTAmount = data[34];
        var totalAllowance = data[35];
        var total_Leave = data[50];
        var Paidleaves = data[36];
        var Payholiday = data[37];
        var otherDeduct = data[38];
        var countLate = data[59];
        var Latededuction = data[39];
        var UTHours = data[60];
        var UTDeduction = data[40];
        var LWOPcount = data[61];
        var LWOPDeduction = data[41];
        var Netpayslip = data[42];
        var EarnTotal = data[43];
        var DeductTotal = data[63];
        var EmployeeStats = data[45];
        var cutoffFrequency = data[46];
        var Totalworkingdays = data[47];
        var CuttoffID = data[48];
        var Payrules = data[54];
        var Transports = data[55];
        var Meals = data[56];
        var InterNet = data[57];
        var other_allowances = data[58];
        var totalAbsence = data[51];
        var Absent_deductions = data[52];

        // Set the value of the <p> tag

        
        $('#employeeID').text(employeeID);
        $('#id_p_emp_name').text(empName);
        $('#cutoffstart').text(cutstart);
        $('#cutoffend').text(cutend);
        $('#empTotalwork').text(totalWork);
        $('#empAmount').text(Amount);
        $('#empOThours').text(OThours);
        $('#OTamount').text(OTAmount);
        $('#allowanceAmount').text(totalAllowance);
        $('#leaveAmount').text(Paidleaves);
        $('#holidayAmount').text(Payholiday);
        $('#deductSSS').text(dSSS);
        $('#deductphil').text(Philhealth);
        $('#deductTIN').text(Tin);
        $('#deductPagibig').text(Pagibig);
        $('#deductOther').text(otherDeduct);
        $('#deductLate').text(Latededuction);
        $('#latehour').text(countLate);
        $('#deductUT').text(UTDeduction);
        $('#underhour').text(UTHours);
        $('#deductLWOP').text(LWOPDeduction);
        $('#netpayslip').text(Netpayslip);
        $('#totalEarn').text(EarnTotal);
        $('#totalDeduction').text(DeductTotal);
        $('#empstatus').text(EmployeeStats);


        //input hidden value para maipasa ko sa ajax
        $('#rulePay').val(Payrules);
        $('#id_table_frequency').val(cutoffFrequency);
        $('#id_table_cutoffnum').val(CutoffNumber);
        $('#id_employeeid').val(employeeID);
        $('#cutoffmonth').val(cutoffMonth);
        $('#cutoffstarts').val(cutstart);
        $('#cutoffends').val(cutend);
        $('#emptotalworks').val(totalWork);
        $('#empAmounts').val(Amount);
        $('#empOThour').val(OThours);
        $('#OTamounts').val(OTAmount);
        $('#transportss').val(Transports);
        $('#meals').val(Meals);
        $('#internets').val(InterNet);
        $('#otherallow').val(other_allowances);
        $('#allowanceAmounts').val(totalAllowance);
        $('#leavecount').val(total_Leave);
        $('#leaveAmounts').val(Paidleaves);
        $('#holidayAmounts').val(Payholiday);
        $('#totalEarns').val(EarnTotal);
        $('#absentcount').val(totalAbsence);
        $('#absenceDeductions').val(Absent_deductions);
        $('#deduct_SSS').val(dSSS);
        $('#deduct_phil').val(Philhealth);
        $('#deduct_TIN').val(Tin);
        $('#deduct_Pagibig').val(Pagibig);
        $('#deduct_Other').val(otherDeduct);
        $('#totalgovernbenefit').val(Governtotal);
        $('#lateNumber').val(countLate);
        $('#deduct_Late').val(Latededuction);
        $('#countUT').val(UTHours);
        $('#deduct_UT').val(UTDeduction);
        $('#numberLWOP').val(LWOPcount);
        $('#deduct_LWOP').val(LWOPDeduction);
        $('#totalDeductions').val(DeductTotal);
        $('#netpayslips').val(Netpayslip);
        $('#id_workdays').val(Totalworkingdays);
        $('#id_cutoff_id').val(CuttoffID);



    });
});
</script>
<!---------------------------------End Script para sa payslip data ng modal------------------------------------------>



<!----------------------Script sa tab button------------------------------------->
<script>
var activeButton = document.querySelector(".tab .active button");

function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].parentNode.classList.remove("active"); // Remove active class from all buttons' parent divs
  }
  
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.parentElement.classList.add("active"); // Add active class to the clicked button's parent
  
  if (activeButton) {
    activeButton.parentElement.classList.remove("active"); // Remove active class from the previously active button's parent
  }
  

  activeButton = evt.currentTarget; // Update the active button reference
}
</script>
<!----------------------End Script sa tab button------------------------------------->


<!----------------------Script sa dropdown chain--------------------------->        
<script>
// Kapag nagbago ang pagpili sa select department dropdown
document.getElementById("select_department").addEventListener("change", function() {
    var departmentID = this.value; // Kunin ang value ng selected department

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var employees = JSON.parse(this.responseText);
            var employeeDropdown = document.getElementById("select_employee");
            employeeDropdown.innerHTML = ""; // I-clear ang current options

            // I-update ang employee dropdown base sa mga nakuha na empleyado
            if (departmentID == "All Department") {
                // Kapag "All Department" ang napili, ipakita ang "All Employee" kasama ang detalye ng bawat empleyado
                var allEmployeeOption = document.createElement("option");
                allEmployeeOption.value = "All Employee";
                allEmployeeOption.text = "All Employee";
                employeeDropdown.appendChild(allEmployeeOption);

                employees.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    employeeDropdown.appendChild(option);
                });
            } else {
                // Kapag ibang department ang napili, ipakita ang mga empleyado base sa department
                employees.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    employeeDropdown.appendChild(option);
                });
            }

            // I-enable ang employee dropdown
            employeeDropdown.disabled = false;
        }
    };
    xhttp.open("GET", "get_employees.php?departmentID=" + departmentID, true);
    xhttp.send();
});

function filterAttReport() {
        var department = document.getElementById('select_department').value;
        var employee = document.getElementById('select_employee').value;
        var dateFrom = document.getElementById('datestart').value;
        var dateTo = document.getElementById('enddate').value;
        var url = 'gnrate_payroll_prac.php?col_deptname=' + department + '&empid=' + employee + '&date_from=' + dateFrom + '&date_to=' + dateTo;
        window.location.href = url;
    }
</script>
<!----------------------Script sa dropdown chain--------------------------->      

<!------------------------------------------------MESSAGE FUNCTION START------------------------------------------->
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
</script>
<!------------------------------------------------MESSAGE FUNCTION END------------------------------------------->
<script>
    setTimeout(function() {
        var alert = document.querySelector('.alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 4000);
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
        $(document).ready(function(){
                $('.sched-update').on('click', function(){
                                    $('#schedUpdate').modal('show');
                                    $tr = $(this).closest('tr');

                                    var data = $tr.children("td").map(function () {
                                        return $(this).text();
                                    }).get();

                                    console.log(data);
                                    //id_colId
                                    $('#empid').val(data[8]);
                                    $('#sched_from').val(data[5]);
                                    $('#sched_to').val(data[6]);
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