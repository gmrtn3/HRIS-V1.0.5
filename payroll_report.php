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
    <link rel="stylesheet" href="css/payroll_report.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/payroll_reportResponsive.css">
    <title>Payroll Report</title>
</head>
<body>
    <style>
         .pagination{
        margin-right: 64px !important;
        
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
        margin-right: 15px !important;
        margin-bottom: -16px !important;

    }
    </style>
<header>
    <?php
        include 'header.php';
    ?>
</header>

<div class="modal fade" id="detailedPayslip" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Payslip</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <div class="table">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Working Days</th>
                            <th>Overtime</th>
                            <th>Allowances</th>
                            <th>Leave Pay</th>
                            <th>Holiday Pay</th>
                            <th>Total Salary</th>
                            <th>SSS</th>
                            <th>Philhealth</th>
                            <th>TIN</th>
                            <th>Pag-ibig</th>
                            <th>Other</th>
                            <th>Late</th>
                            <th>Undertime</th>
                            <th>Absences</th>
                            <th>Total Deduction</th>
                        </tr>
                    </thead>
                        <tr>
                            <td id="workdays"></td>
                            <td id="overtimedata"></td>
                            <td id="allowancedata"></td>
                            <td id="leavedata"></td>
                            <td id="holidaydata"></td>
                            <td id="salarytotal"></td>
                            <td id="sssdata"></td>
                            <td id="phildata"></td>
                            <td id="tindata"></td>
                            <td id="pagibigdata"></td>
                            <td id="otherdata"></td>
                            <td id="latedata"></td>
                            <td id="undertimedata"></td>
                            <td id="absentdata"></td>
                            <td id="deduction"></td>
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
                                    <p style="font-size: 25px; padding: 10px">Payroll Report</p>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                                <!-- <button type="button" class="add_off_btn" data-bs-toggle="modal" data-bs-target="#file_off_btn">
                                    File Official Business
                                    </button> -->
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
                    <p class="demm-text">Month From</p>
                    <input class="select-btn" type="date" name="date_from" id="datestart" required>
                </div>
                <div class="input-container">
                    <div class="notif">
                    <p class="demm-text">Month To</p>
                    </div>
                    <input class="select-btn" type="date" name="date_to" id="enddate" onchange="datefunct()" required>
                </div>
                <button id="arrowBtn" onclick="filterAttReport()"> &rarr; Apply Filter</button>
 </div> <!--Container Select-->
<!----------------------------------------select button and text input--------------------------------------->
                
                    <div class="table-responsive" id="table-responsiveness">
                        <table id="order-listing" class="table mt-2">
                            <thead>
                                    <tr>  
                                        <th style="display: none;">ID</th>                                        
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Month</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Cut off Number</th>
                                        <th style="display: none;">Working Days</th>
                                        <th style="display: none;">Overtime</th>
                                        <th style="display: none;">Allowances</th>
                                        <th style="display: none;">Leave Pay</th>
                                        <th style="display: none;">Holiday Pay</th>
                                        <th style="display: none;">Total Salary</th>
                                        <th style="display: none;">SSS</th>
                                        <th style="display: none;">Philhealth</th>
                                        <th style="display: none;">TIN</th>
                                        <th style="display: none;">Pag-ibig</th>
                                        <th style="display: none;">Other</th>
                                        <th style="display: none;">Late</th>
                                        <th style="display: none;">Undertime</th>
                                        <th style="display: none;">Absences</th>
                                        <th style="display: none;">Total Deduction</th>
                                        <th>Salary Final Total</th>
                                        <th>Action</th>

                                    </tr>
                            </thead>
                                    <?php
                                        include 'config.php';
                                        date_default_timezone_set('Asia/Manila'); 
                                        // Get the current month and year
                                        $currentMonth = date('m');
                                        $currentYear = date('Y');

                                        $sql = "SELECT
                                                payslip_report_tb.id,
                                                employee_tb.empid,
                                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                                payslip_report_tb.cutoff_month,
                                                payslip_report_tb.cutoff_startdate,
                                                payslip_report_tb.cutoff_enddate,
                                                payslip_report_tb.cutoff_num,
                                                payslip_report_tb.working_days,
                                                payslip_report_tb.basic_hours,
                                                payslip_report_tb.basic_amount_pay,
                                                payslip_report_tb.overtime_hours,
                                                payslip_report_tb.overtime_amount,
                                                payslip_report_tb.allowances,
                                                payslip_report_tb.paid_leaves,
                                                payslip_report_tb.holiday_pay,
                                                payslip_report_tb.total_earnings,
                                                payslip_report_tb.sss_contri,
                                                payslip_report_tb.philhealth_contri,
                                                payslip_report_tb.tin_contri,
                                                payslip_report_tb.pagibig_contri,
                                                payslip_report_tb.other_contri,
                                                payslip_report_tb.tardiness_deduct,
                                                payslip_report_tb.undertime_deduct,
                                                payslip_report_tb.lwop_deduct,
                                                payslip_report_tb.total_deduction,
                                                payslip_report_tb.net_pay
                                            FROM
                                                payslip_report_tb
                                            INNER JOIN
                                                employee_tb ON employee_tb.empid = payslip_report_tb.empid";

                                    $result = mysqli_query($conn, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $cmpny_empid = $row['empid'];

                                        $sql = "SELECT employee_tb.company_code, 
                                                employee_tb.empid, 
                                                assigned_company_code_tb.company_code_id, 
                                                assigned_company_code_tb.empid, 
                                                company_code_tb.id, 
                                                company_code_tb.company_code AS company_code_name 
                                                FROM assigned_company_code_tb 
                                                INNER JOIN company_code_tb ON assigned_company_code_tb.company_code_id = company_code_tb.id 
                                                INNER JOIN employee_tb ON assigned_company_code_tb.empid = employee_tb.empid 
                                                WHERE assigned_company_code_tb.empid = '$cmpny_empid' ";
                                                
                                                $cmpny_result = mysqli_query($conn, $sql); // Corrected parameter order
                                                $cmpny_row = mysqli_fetch_assoc($cmpny_result);
 
                                    ?>
                                    <tr>
                                        <td style="display: none;"><?php echo $row['id']?></td>
                                        <td style='font-weight: 400'><?php $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                        $empid = $row['empid'];
                                        if (!empty($cmpny_code)) {
                                            echo $cmpny_code . " - " . $empid;
                                        } else {
                                            echo $empid;
                                        }  ?></td>
                                        <td><?php echo $row['full_name']?></td>
                                        <td><?php echo $row['cutoff_month']?></td>
                                        <td><?php echo $row['cutoff_startdate']?></td>
                                        <td><?php echo $row['cutoff_enddate']?></td>
                                        <td><?php echo $row['cutoff_num']?></td>
                                        <td style="display: none;"><?php echo $row['working_days']?></td>
                                        <td style="display: none;"><?php echo $row['overtime_amount']?></td>
                                        <td style="display: none;"><?php echo $row['allowances']?></td>
                                        <td style="display: none;"><?php echo $row['paid_leaves']?></td>
                                        <td style="display: none;"><?php echo $row['holiday_pay']?></td>
                                        <td style="display: none;"><?php echo $row['total_earnings']?></td>
                                        <td style="display: none;"><?php echo $row['sss_contri']?></td>
                                        <td style="display: none;"><?php echo $row['philhealth_contri']?></td>
                                        <td style="display: none;"><?php echo $row['tin_contri']?></td>
                                        <td style="display: none;"><?php echo $row['pagibig_contri']?></td>
                                        <td style="display: none;"><?php echo $row['other_contri']?></td>
                                        <td style="display: none;"><?php echo $row['tardiness_deduct']?></td>
                                        <td style="display: none;"><?php echo $row['undertime_deduct']?></td>
                                        <td style="display: none;"><?php echo $row['lwop_deduct']?></td>
                                        <td style="display: none;"><?php echo $row['total_deduction']?></td>
                                        <td><?php echo $row['net_pay']?></td>
                                        <td><button type="button" class="btn btn-primary slipdetailed" data-bs-toggle="modal" data-bs-target="#detailedPayslip">
                                        View
                                        </button></td>
                                    </tr>
                                    <?php
                                      }
                                    ?>
                          </table>    
                    </div>    
                            
                    <div class="export-section">
                        <div class="export-sec">
                            <p class="export">Export Options:</p>
                            <button class="excel" id="export-csv-btn">CSV</button>
                            <p class="lbl_exprt_contnt">|</p>
                            <button class="pdf" onclick="makePDF()">PDF</button>
                        </div>
                    </div>

            </div>
          </div>
        </div>
      </div>
   
      
<!-------------------------------------------------TABLE END------------------------------------------->

<!--CSV Exporting-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script>
$(document).ready(function() {
    // Export button click event
    $('#export-csv-btn').click(function() {
        console.log("hehe");
        // Create a CSV content
        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Employee ID, Name , Month, StartDate, EndDate , CutOffNumber, WorkingDays, Overtime, Allowance, LeavePay, HolidayPay, TotalSalary, SSSDeduction, Philhealth, TinDeduction, PagibigDeduction, OtherDeduction, Late, Undertime, Absences, TotalDeduction, FinalTotalSalary\n";

        // Loop through table rows and append data
        $('#order-listing tbody tr').each(function() {
            var EmployeeID = $(this).find('td:nth-child(1)').text();
            var Name = $(this).find('td:nth-child(2)').text();
            var Month = $(this).find('td:nth-child(3)').text();
            var StartDate = $(this).find('td:nth-child(4)').text();
            var EndDate = $(this).find('td:nth-child(5)').text();
            var CutOffNumber = $(this).find('td:nth-child(6)').text();
            var WorkingDays = $(this).find('td:nth-child(7)').text();
            var Overtime = $(this).find('td:nth-child(8)').text();
            var Allowance = $(this).find('td:nth-child(9)').text();
            var LeavePay = $(this).find('td:nth-child(10)').text();
            var HolidayPay = $(this).find('td:nth-child(11)').text();
            var TotalSalary = $(this).find('td:nth-child(12)').text();
            var SSSDeduction = $(this).find('td:nth-child(13)').text();
            var Philhealth = $(this).find('td:nth-child(14)').text();
            var TinDeduction = $(this).find('td:nth-child(15)').text();
            var PagibigDeduction = $(this).find('td:nth-child(16)').text();
            var OtherDeduction = $(this).find('td:nth-child(17)').text();
            var Late = $(this).find('td:nth-child(18)').text();
            var Undertime = $(this).find('td:nth-child(19)').text();
            var Absences = $(this).find('td:nth-child(20)').text();
            var TotalDeduction = $(this).find('td:nth-child(21)').text();
            var FinalTotalSalary = $(this).find('td:nth-child(22)').text();
            csvContent += EmployeeID + "," + Name + "," + Month + ","  + StartDate + ","  + EndDate + ","  + CutOffNumber + ","  + WorkingDays + "," + Overtime + "," + Allowance + "," + LeavePay + "," + HolidayPay + "," + TotalSalary + "," + SSSDeduction + "," + Philhealth + "," + TinDeduction + "," + PagibigDeduction + "," + OtherDeduction + "," + Late + "," + Undertime + "," + Absences + "," + TotalDeduction + "," + FinalTotalSalary + "," + other +"\n";
        });

        // Create a CSV blob and trigger a download
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Payroll Reports.csv");
        document.body.appendChild(link);
        link.click();
    });
});
</script> -->
<script>
$(document).ready(function() {
    // Export button click event
    $('#export-csv-btn').click(function() {
        // Create a CSV content
        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Employee ID, Name , Month, Start Date, End Date, Cuttoff Number, Working Days, Overtime, Allowance, Leave Pay, Holiday Pay, Total Salary, SSS, Philhealth, Tin,Pag-ibig, Other, Late, Undertime, Absences, Total Deduction, Net Pay\n";

        // Loop through table rows and append data
        $('#order-listing tbody tr').each(function() {
            var EmployeeID = $(this).find('td:nth-child(2)').text();
            var Name = $(this).find('td:nth-child(3)').text();
            var Month = $(this).find('td:nth-child(4)').text();
            var StartDate = $(this).find('td:nth-child(5)').text();
            var EndDate = $(this).find('td:nth-child(6)').text();
            var CutOffNumber = $(this).find('td:nth-child(7)').text();
            var WorkingDays = $(this).find('td:nth-child(8)').text();
            var Overtime = $(this).find('td:nth-child(9)').text();
            var Allowance = $(this).find('td:nth-child(10)').text();
            var LeavePay = $(this).find('td:nth-child(11)').text();
            var HolidayPay = $(this).find('td:nth-child(12)').text();
            var TotalSalary = $(this).find('td:nth-child(13)').text();
            var SSSDeduction = $(this).find('td:nth-child(14)').text();
            var Philhealth = $(this).find('td:nth-child(15)').text();
            var TinDeduction = $(this).find('td:nth-child(16)').text();
            var PagibigDeduction = $(this).find('td:nth-child(17)').text();
            var OtherDeduction = $(this).find('td:nth-child(18)').text();
            var Late = $(this).find('td:nth-child(19)').text();
            var Undertime = $(this).find('td:nth-child(20)').text();
            var Absences = $(this).find('td:nth-child(21)').text();
            var TotalDeduction = $(this).find('td:nth-child(22)').text();
            var FinalTotalSalary = $(this).find('td:nth-child(23)').text().replace("₱ ", "");
            csvContent += EmployeeID + "," + Name + "," + Month + ","  + StartDate + ","  + EndDate + ","  + CutOffNumber + ","  + WorkingDays + "," + Overtime + "," + Allowance + "," + LeavePay + "," + HolidayPay + "," + TotalSalary + "," + SSSDeduction + "," + Philhealth + "," + TinDeduction + "," + PagibigDeduction + "," + OtherDeduction + "," + Late + "," + Undertime + "," + Absences + "," + TotalDeduction + "," + FinalTotalSalary+"\n";
        });

        // Create a CSV blob and trigger a download
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Payroll Reports.csv");
        document.body.appendChild(link);
        link.click();
    });
});
</script>

<script>
$(document).ready(function(){
    $('.slipdetailed').on('click', function(){
        $('#detailedPayslip').modal('show');
        $tr = $(this).closest('tr');
        var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        $('#workdays').text(data[7]);
        $('#overtimedata').text(data[8]);
        $('#allowancedata').text(data[9]);
        $('#leavedata').text(data[10]);
        $('#holidaydata').text(data[11]);
        $('#salarytotal').text(data[12]);
        $('#sssdata').text(data[13]);
        $('#phildata').text(data[14]);
        $('#tindata').text(data[15]);
        $('#pagibigdata').text(data[16]);
        $('#otherdata').text(data[17]);
        $('#latedata').text(data[18]);
        $('#undertimedata').text(data[19]);
        $('#absentdata').text(data[20]);
        $('#deduction').text(data[21]);
    });
});
</script>

<!--PDF Exporting-->
<script>
window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;

function makePDF() {
    html2canvas(document.querySelector("#order-listing"), {
        allowTaint: true,
        useCORS: true,
        scale: 0.7
    }).then(canvas => {
        var img = canvas.toDataURL("Payroll Attendance Report");
        
        // Set the PDF to landscape mode
        var doc = new jsPDF({
            orientation: 'landscape'
        });

        doc.setFont('Arial');
        doc.getFontSize(11);
        doc.addImage(img, 'PNG', 10, 10, 0,0);
        doc.save("Payroll Report.pdf");
    });
}
</script>


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
        var url = 'dtRecords.php?col_deptname=' + department + '&empid=' + employee + '&date=' + dateFrom + '&date=' + dateTo;
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