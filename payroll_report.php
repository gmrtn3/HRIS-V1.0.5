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
<header>
    <?php
        include 'header.php';
    ?>
</header>
<style>
     table {
                display: block;
                overflow-x: hidden;
                white-space: nowrap;
                max-height: 350px;
                height: 350px;
                
                
            }
            tbody {
                display: table;
                width: 100%;
            }
            tr {
                width: 100% !important;
                display: table !important;
                table-layout: fixed !important;
            }
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
        margin-right: 20px !important;
        margin-bottom: -16px !important;

    }
</style>

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
                
                                <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                                    <table id="order-listing" class="table" style="width: 100%">
                                            <thead>
                                                <tr>
                                                    <th>Cut Off Month</th>
                                                    <th>Cut Off Start</th>
                                                    <th>Cut Off End</th>
                                                    <th>Cut Off Number</th>
                                                    <th>Date Generated</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <?php 
                                                include 'config.php';
                                                $check_prb = mysqli_query($conn, "SELECT * FROM payslip_report_tb GROUP BY `cutoff_ID`");
                                                if ($check_prb && mysqli_num_rows($check_prb) > 0) {
                                                    while ($prb_row = $check_prb->fetch_assoc()){                                                    
                                                ?>
                                                    <tr>
                                                        <td style="font-weight: 400;"><?php echo $prb_row['cutoff_month']; ?></td>
                                                        <td style="font-weight: 400;"><?php echo $prb_row['cutoff_startdate']; ?></td>
                                                        <td style="font-weight: 400;"><?php echo $prb_row['cutoff_enddate']; ?></td>
                                                        <td style="font-weight: 400;"><?php echo $prb_row['cutoff_num']; ?></td>
                                                        <td style="font-weight: 400;"><?php echo $prb_row['date_time']; ?></td>
                                                        <td>                
                                                            <form method="post" action="PayReport.php">
                                                                <input type="hidden" name="cutoffID" value="<?php echo $prb_row['cutoff_ID']; ?>">
                                                                <input type="hidden" name="startDate" value="<?php echo $prb_row['cutoff_startdate']; ?>">
                                                                <input type="hidden" name="endDate" value="<?php echo $prb_row['cutoff_enddate']; ?>">
                                                                <button type="submit" class="btn btn-primary view-report-btn">View Report</button>
                                                            </form>
                                                        </td>
                                                    </tr>

                                     </table>
                               </div>    

                               <!-- <div class="modal fade" id="ViewReport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" style="width: 2000px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Payroll Report</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                                <div class="modal-body" id="report-modal-body">
                                                    
                                                </div>
                                           </div>
                                        </div>
                                    </div> -->
                            
                    <div class="export-section">
                        <div class="export-sec">
                            <p class="export">Export Options:</p>
                            <form action="actions/Payroll/export_csv.php" method="POST" id="csv-export-form">
                                    <input type="hidden" name="cutoffID" value="<?php echo $prb_row['cutoff_ID']; ?>">
                                    <input type="hidden" name="startDate" value="<?php echo $prb_row['cutoff_startdate']; ?>">
                                    <input type="hidden" name="endDate" value="<?php echo $prb_row['cutoff_enddate']; ?>">
                                <button class="excel downloadcsv" id="export-csv-btn">CSV</button>
                            </form>
                            <p class="lbl_exprt_contnt">|</p>
                            <button class="pdf" onclick="makePDF()">PDF</button>
                        </div>
                    </div>
                     <?php
                      }
                   }
                ?>
            </div>
          </div>
        </div>
      </div>
   

<script>
$(document).ready(function () {
    $('.downloadcsv').click(function () {
        var employeeId = $("input[name='employeeId']").val();
        var minDate = $("input[name='minDate']").val();
        var maxDate = $("input[name='maxDate']").val();

        // Use window.location to trigger the download
        window.location.href = 'actions/Daily Time Records/download.php?employeeId=' + employeeId + '&minDate=' + minDate + '&maxDate=' + maxDate;
    });
});
</script>


<!---Script sa pagpindot ng view report button nakaspecific data--->
<!-- <script>
    $(document).ready(function () {
        $('.view-report-btn').click(function () {
            var cutoffID = $(this).data('cutoff-id');
            var startDate = $(this).data('start-date');
            var endDate = $(this).data('end-date');

            $.ajax({
                url: 'get_report_data.php', // I-create ang PHP script na ito para sa pagkuha ng mga data mula sa database
                method: 'POST',
                data: { cutoffID: cutoffID, startDate: startDate, endDate: endDate },
                success: function (response) {
                    $('#report-modal-body').html(response);
                }
            });
        });
    });
</script> -->

<!-------------------------------------------------TABLE END------------------------------------------->

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
<!--CSV Exporting-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script>
$(document).ready(function() {
    // Export button click event
    $('#export-csv-btn').click(function() {
        // Create a CSV content
        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Employee ID, Name , Month, StartDate, EndDate , CutOffNumber, WorkingDays, Overtime, Allowance, LeavePay, HolidayPay, TotalSalary, SSSDeduction, Philhealth, TinDeduction, PagibigDeduction, OtherDeduction, Late, Undertime, Absences, TotalDeduction, FinalTotalSalary,\n";

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
        var url = 'payroll_report.php?col_deptname=' + department + '&empid=' + employee + '&date=' + dateFrom + '&date=' + dateTo;
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