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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
        <!-- skydash -->

    <link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   

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
    .pagination{
        margin-right: 70px !important;

        
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
        margin-right: 22px !important;
        margin-bottom: -15.5px !important;

    }


    
</style>

<!------------------------------------------------- Header ------------------------------------------------------------->
    <div class="main-panel mt-5">
        <div class="content-wrapper mt-4" >
          <div class="card mt-3">
            <div class="card-body">
                <div class="pnl_home">
                    <!-- <a href="dashboard.php">Home</a>
                    <p class="header_slash">\</p> -->
                    <h2>Payroll Detailed Report</h2>
                </div>
<!------------------------------------------------- End Of Header -------------------------------------------> 

<!----------------------------------------select button and text input--------------------------------------->
<div class="container-select">
            <div class="input-container">
              <p class="demm-text">Department</p>
              <?php
                        include 'config.php';

                        // Fetch all values of empid and date from the database
                        $sql = "SELECT `col_deptname` FROM dept_tb";
                        $result = mysqli_query($conn, $sql);

                        // Generate the dropdown list
                        echo "<select class='select-btn form-select-m' aria-label='.form-select-sm example' name='name_emp''>";
                        echo "<option value='Select All Department' default>Select Department</option>"; // Add a default option
                        while ($row = mysqli_fetch_array($result)) {
                        $department = $row['col_deptname'];
                        echo "<option value='$department'>$department</option>"; // Set the value to emp_id|date
                        }
                        echo "</select>";
                      ?>
                </div>
                
                <div class="input-container">
                <p class="demm-text">Employee</p>
                        <?php
                            include 'config.php';

                            // Fetch all values of fname and lname from the database
                            $sql = "SELECT fname, lname, empid FROM employee_tb";
                            $result = mysqli_query($conn, $sql);

                            // Generate the dropdown list
                           echo "<select class='select-btn form-select-m' aria-label='.form-select-sm example' name='name_emp''>";
                            echo "<option value='' disabled selected>Select Employee</option>";
                                while ($row = mysqli_fetch_array($result)) {
                                    $emp_id = $row['empid'];
                                    $name = $row['empid'] . ' - ' . $row['fname'] . ' ' . $row['lname'];
                                    echo "<option value='$emp_id'>$name</option>";
                                }
                            echo "</select>";
                        ?>
                </div>

                <div class="input-container" >
                <p class="demm-text">Month From</p>
                <input class="select-btn" type="date" name="" id="datestart" required>
                </div>
                <div class="input-container">
                <div class="notif">
                <p class="demm-text">Month To</p>
                <!-- <p id="validate" class="validation" style="display:none;>End date must beyond the start date</p> -->
                </div>
                <input class="select-btn" type="date" id="enddate" onchange="datefunct()" required>
                </div>
                <button id="arrowBtn">Apply Filter</button>
            </div>
<!----------------------------------------select button and text input--------------------------------------->




<!-------------------------------------------------TABLE START------------------------------------------->

                <div class="row">
                    <div class="col-12 mt-5 ">
                        <div class="table-responsive">
                            <table id="order-listing" class="table">
                                <thead>
                                        <tr>  
                                            <th style="display: none;">ID</th>                                        
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Basic Pay</th>
                                            <th>Overtime</th>
                                            <th>Absences</th>
                                            <th>Late</th>
                                            <th>Undertime</th>
                                            <th style="display: none;">Total Work Hours</th>
                                            <th>SSS</th>
                                            <th>Philhealth</th>
                                            <th>Pag-ibig</th>
                                            <th>Other Deduction</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php
                                            include 'config.php';
                                            
                                            // Set the time zone to Manila, Philippines
                                            date_default_timezone_set('Asia/Manila');

                                            // Get the current month and year
                                            $currentMonth = date('m');
                                            $currentYear = date('Y');

                                            // Add JavaScript code to dynamically update the table
                                            echo '<script>';
                                            echo 'var currentDate = new Date();';
                                            echo 'var currentMonth = currentDate.getMonth() + 1;';
                                            echo 'var currentYear = currentDate.getFullYear();';
                                            echo 'if (' . $currentMonth . ' !== currentMonth || ' . $currentYear . ' !== currentYear) {';
                                            echo 'window.location.reload();'; // Reload the page if the month has changed
                                            echo '}';
                                            echo '</script>';

                                            $query = "SELECT attendances.id,
                                            employee_tb.empid,
                                            CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                            employee_tb.empbsalary,
                                            employee_tb.drate,
                                            employee_tb.sss_amount,
                                            employee_tb.philhealth_amount,
                                            employee_tb.pagibig_amount,
                                            (
                                            SELECT SUM(governdeduct_tb.govern_amount)
                                            FROM governdeduct_tb
                                            WHERE governdeduct_tb.id_emp = employee_tb.empid
                                            ) AS total_govern_amount,
                                            SUM(CASE WHEN attendances.status = 'Absent' THEN 1 ELSE 0 END) AS absent_count,
                                            SUM(CASE WHEN attendances.status = 'Present' OR attendances.status = 'On-Leave' THEN 1 ELSE 0 END) AS total_status,
                                            SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.overtime))) AS total_overtime,
                                            SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.late))) AS total_late,
                                            SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.early_out))) AS total_early_out,
                                            SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.total_work))) AS total_work 
                                          FROM employee_tb
                                          INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                          WHERE MONTH(attendances.date) = $currentMonth
                                          AND YEAR(attendances.date) = $currentYear
                                          GROUP BY employee_tb.empid, MONTH(attendances.date)";
                                
                                            $result = mysqli_query($conn, $query);

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

                                               $total_numwork = $row['total_status'];
                                               $daily_rate = $row['drate'];

                                               $basic_pay = $daily_rate * $total_numwork;
                                            ?>
                                                <tr>
                                                    <td style="display: none;"><?php echo $row['id'] ?></td>
                                                    <td style='font-weight: 400'><?php $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                                    $empid = $row['empid'];
                                                    if (!empty($cmpny_code)) {
                                                        echo $cmpny_code . " - " . $empid;
                                                    } else {
                                                        echo $empid;
                                                    }  ?></td>
                                                    <td style='font-weight: 400'><?php echo $row['full_name'] ?></td>
                                                    <td style='font-weight: 400'><?php echo $basic_pay?></td>
                                                    <td style='font-weight: 400'><?php echo $row['total_overtime'] ?></td>
                                                    <td style='font-weight: 400'><?php echo $row['absent_count'] ?></td>
                                                    <td style='font-weight: 400'><?php echo $row['total_late'] ?></td>
                                                    <td style='font-weight: 400'><?php echo $row['total_early_out'] ?></td>
                                                    <td style="display: none;"><?php echo $row['total_work'] ?></td>
                                                    <td style='font-weight: 400'><?php echo $row['sss_amount']?></td>
                                                    <td style='font-weight: 400'><?php echo $row['philhealth_amount']?></td>
                                                    <td style='font-weight: 400'><?php echo $row['pagibig_amount']?></td>
                                                    <td style='font-weight: 400'><?php echo $row['total_govern_amount']?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                </tbody>
                            </table>
                         </div>
                      </div>
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
 </div><!---Main Panel Close Tag--->
<!-------------------------------------------------TABLE END------------------------------------------->

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function() {
    // Export button click event
    $('#export-csv-btn').click(function() {
        // Create a CSV content
        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Employee ID, Name , Basic Pay, Overtime, Absences , Late, Undertime, SSS, Philhealth, Pag-ibig, Other Deduction\n";

        // Loop through table rows and append data
        $('#order-listing tbody tr').each(function() {
            var empid = $(this).find('td:nth-child(2)').text();
            var name = $(this).find('td:nth-child(3)').text();
            var basic = $(this).find('td:nth-child(4)').text();
            var ot = $(this).find('td:nth-child(5)').text();
            var absent = $(this).find('td:nth-child(6)').text();
            var late = $(this).find('td:nth-child(7)').text();
            var under = $(this).find('td:nth-child(8)').text();
            var sss = $(this).find('td:nth-child(10)').text();
            var ph = $(this).find('td:nth-child(11)').text();
            var pagibig = $(this).find('td:nth-child(12)').text();
            var other = $(this).find('td:nth-child(13)').text();
            csvContent += empid + "," + name + "," + basic + ","  + ot + ","  + absent + ","  + late + ","  + under + "," + sss + "," + ph + "," + pagibig + "," + other +"\n";
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