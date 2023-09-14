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

if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Employee data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
    $alertStyle = 'style="font-size: 20px;"'; // add this line to set the font-size
}

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


    <link rel="stylesheet" href="css/dtRecords.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dtRecordsResponsives.css">
    <title>Daily Time Records</title>
</head>
<body>
<header>
    <?php
        include 'header.php';
    ?>
</header>

<!-------------------------------------------- Modal Start Here ---------------------------------------------------------->
<div class="modal fade" id="upload_dtr_btn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">DTR Correction Application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Daily Time Records/import.php" method="post" enctype="multipart/form-data">
      <div class="modal-body">
         <div class="input-group mb-3">
                 <input type="file" name="file" class="form-control" id="inputGroupFile02">
                 <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
          </div>
      </div> <!--------Modal body div close tag--------->
      </form>
    </div>
  </div>
</div>
<!-------------------------------------------- Modal End Here ---------------------------------------------------------->


<!------------------------------------------------- Header ------------------------------------------------------------->
<div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">

                <div class="pnl_home">
                <p class="header_prgph_DTR" style="font-size: 25px; padding: 10px">Employee DTR Management</p>
                <div class="btn-section" style="margin-left:70px;">
                     <!-- Button trigger modal -->
                    <button class="up-btn" data-bs-toggle="modal" data-bs-target="#upload_dtr_btn">Upload DTR File</button>
                    <button class="down-btn" id="downloadBtn">Download CSV</button>
                </div>
                </div>
<!------------------------------------------------- End Of Header -------------------------------------------> 

<!---------------------------------------- Display status message ------------------------------------------->
<?php if(!empty($statusMsg)){ ?>
            <div class="col-xs-12 mt-2">
                <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
            </div>
            <?php } ?>
<!---------------------------------------End Display status message ------------------------------------------->

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
                        <div class="table-responsive" id="table-responsiveness">
                             <table id="order-listing" class="table mt-2">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Month</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Department</th>
                                        <th>Late</th>
                                        <th>Undertime</th>
                                        <th>Overtime</th>
                                        <th>Total Hours</th>
                                        <th>DT Records</th>
                                        <th>Download File</th>
                                    </tr>
                                </thead>
                                    <?php
                                    include 'config.php';

                                    $department = $_GET['department_name'] ?? '';
                                    $employee = $_GET['empid'] ?? '';
                                    $dateFrom = $_GET['date_from'] ?? '';
                                    $dateTo = $_GET['date_to'] ?? '';

                                    $query = "SELECT attendances.id,
                                        attendances.status,
                                        employee_tb.empid,
                                        CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS `full_name`,
                                        attendances.date,
                                        MIN(attendances.date) AS min_date, 
                                        MAX(attendances.date) AS max_date,
                                        dept_tb.col_deptname,
                                        empschedule_tb.schedule_name,
                                        attendances.time_in,
                                        attendances.time_out,
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
                                        attendances.early_out,
                                        attendances.overtime,
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
                                    ) AS total_hoursWORK
                                        FROM employee_tb
                                        INNER JOIN attendances ON employee_tb.empid = attendances.empid
                                        INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                        INNER JOIN empschedule_tb ON employee_tb.empid = empschedule_tb.empid 
                                        GROUP BY attendances.empid, YEAR(attendances.date), MONTH(attendances.date)";

                                        if (!empty($department) && $department != 'All Department') {
                                            $query .= " AND dept_tb.col_deptname = '$department'";
                                        }

                                        if (!empty($employee) && $employee != 'All Employee') {
                                            $query .= " AND employee_tb.empid = '$employee'";
                                        }

                                        if (!empty($dateFrom) && !empty($dateTo)) {
                                            $query .= " AND attendances.date BETWEEN '$dateFrom' AND '$dateTo'";
                                        }

                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $monthName = date("F", strtotime($row['min_date']));
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
                                            <td style="font-weight: 400;"><?php $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                            $empid = $row["empid"];
                                            if (!empty($cmpny_code)) {
                                                echo $cmpny_code . " - " . $empid;
                                            } else {
                                                echo $empid;
                                            } ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['full_name']; ?></td>
                                            <td><?php echo $monthName ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['min_date']; ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['max_date']; ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['col_deptname']; ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['total_hours_minutesLATE']; ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['early_out']; ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['overtime']; ?></td>
                                            <td style="font-weight: 400;"><?php echo $row['total_hoursWORK']; ?></td>
                                            <td style="font-weight: 400;">    <button class="btn btn-primary viewdtrecords" data-bs-toggle="modal" data-bs-target="#ViewdtrReport" 
                                                data-employee-id="<?php echo $row['empid']; ?>"
                                                data-min-date="<?php echo $row['min_date']; ?>"
                                                data-max-date="<?php echo $row['max_date']; ?>">View
                                            </button></td>
                                            <td style="font-weight: 400;">
                                                    <input type="hidden" name="employeeId" value="<?php echo $row['empid']; ?>">
                                                    <input type="hidden" name="minDate" value="<?php echo $row['min_date']; ?>">
                                                    <input type="hidden" name="maxDate" value="<?php echo $row['max_date']; ?>">
                                                    <button class="btn btn-secondary downloadcsv">Download</button>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                            </table>
                         </div>

                            <!-- Modal -->
                            <div class="modal fade" id="ViewdtrReport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Daily Time Records</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                        <div class="modal-body" id="dtr-modal-body">
    
                                        </div>

                                    </div>
                                </div>
                            </div>

                  </div>
               </div>
            </div>
         </div>
                      
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


<!---------------------Script sa pagview ng attendance record with specific employee----------------->         
<script>
$(document).ready(function () {
    $('.viewdtrecords').click(function () {
        var employeeId = $(this).data('employee-id');
        var minDate = $(this).data('min-date');
        var maxDate = $(this).data('max-date');

        $.ajax({
            url: 'get_dtr_data.php',
            method: 'POST',
            data: { employeeId: employeeId, minDate: minDate, maxDate: maxDate }, // Ipasa ang minDate at maxDate
            success: function (response) {
                $('#dtr-modal-body').html(response);
            }
        });
    });
});
</script>             
      
<!-------------------------------------------------TABLE END------------------------------------------->
<!-- CSV -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Export button click event
    $('#downloadBtn').click(function() {
        // Create a CSV content
        var csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Status , Employee ID, Date, Time in, Time out, Late, Undertime, Overtime, Total work";

        // Create a CSV blob and trigger a download
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Daily Time Records Template.csv");
        document.body.appendChild(link);
        link.click();
    });
});
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