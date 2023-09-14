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

    <link rel="stylesheet" href="css/styles.css"> 
    <title>HRIS | Generate Payslip</title>
</head>
<body>
    <style>
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:before {
        bottom: .5em;
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
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }

    .table-responsive{
        height: 400px !important;
        overflow-x: hidden !important;
    }
    
    .payslip-input {
        
    }
    .payslip-input select{
        width: 350px !important;
        height: 50px !important;
    }

    .payslip-input select:nth-child(3){
        margin-right: 20px !important;
    }

    
    </style>
    <header>
        <?php include("header.php")?>
    </header>

    <div class="gen-payslip">
        <div class="gen-payslip-input-container" style="margin-bottom:50px;">
            <div class="gen-payslip-title">
                <h1>Generate Payslip</h1>
            </div>
            <div class="payslip-input">
                <div>
                   <?php
                    include 'config.php';
                        $sql = "SELECT * FROM employee_tb";
                        $result = mysqli_query($conn, $sql);

                        $options = "";
                        $options .= "<option value='All Employee'>All Employee</option>";
                            while ($row = mysqli_fetch_assoc($result)) {
                            $options .= "<option value='".$row['empid']."'>".$row['empid']." - ".$row['fname']." ".$row['lname']."</option>";
                                }
                            ?>

                        <label for="schedule_name">Employee</label>
                        <select name="schedule_name" id="id_select_employee" class="form-control">
                            <option value disabled selected>Select Employee</option>
                            <?php echo $options; ?>
                        </select>
                </div>
                <div class="payslip-input2">
                    <label for="">Select Month/Year</label>
                    <select name="select_monthY" onchange="foryear()" id="id_month" class="form-control" style="width: 190px;">
                        <option value="" disabled selected>Month</option>
                        <option value="January">January</option>
                        <option value="February">February</option>
                        <option value="March">March</option>
                        <option value="April">April</option>
                        <option value="May">May</option>
                        <option value="June">June</option>
                        <option value="July">July</option>
                        <option value="August">August</option>
                        <option value="September">September</option>
                        <option value="October">October</option>
                        <option value="November">November</option>
                        <option value="December">December</option>
                    </select>

                    <select name="year" id="id_year" onchange="getCutoff(this.value)" class="form-control">
                        <option value="" disabled selected>Year</option>
                        <?php
                            $currentYear = date("Y");
                            for ($year = 2005; $year <= $currentYear; $year++) {
                                echo "<option value=\"$year\">$year</option>";
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <button class="btn gen-btn" id="id_generate" name="generate_name" style="background-color: black" onclick="filterPayslip()">Generate</button>
                </div>
            </div>
            <div class="row" style=" width: 73%; margin-left: 15px; margin-top: 20px">
                <div class="col-6">
                    <div class="input-group mb-3 pay1">
                        <?php
                            include 'config.php';
                            $sql = "SELECT col_ID, col_deptname FROM dept_tb";
                            $result = mysqli_query($conn, $sql);

                            $options = "";
                            $options .= "<option value='All Department'>All Department</option>";
                            while ($row = mysqli_fetch_assoc($result)) 
                            {
                                $options .= "<option value='".$row['col_deptname']."'>".$row['col_deptname']."</option>";
                            }
                        ?>
                        <label for="schedule_name" style="font-weight: bold">Department</label>
                        <select name="schedule_name" id="id_select_department" class="form-control" style="width: 350px;">
                            <option value disabled selected>Select Department</option>
                            <?php echo $options; ?>
                        </select>
                    </div>
                </div>
                <div class="col-6" >
    

                    <div class="input-group mb-3 pay2" style="margin-left: 70px;">
                        <label for="schedule_name" style="font-weight: bold">Cut off Number</label>
                        <select name="cutoffs" id="id_cutoffs" onchange="cutoff(this.value)" class="form-control">
                            <option value disabled selected>Select Cutoff</option>
                            <option value = "1">1</option>
                            <option value = "2" >2</option>
                            <option value = "3" >3</option>
                            <option value = "4" >4</option>
                        </select>
                    </div>      
                </div>

                
            </div>

<script>
function filterPayslip() {
    var employee = document.getElementById('id_select_employee').value;
    var department = document.getElementById('id_select_department').value;
    var month = document.getElementById('id_month').value;
    var year = document.getElementById('id_year').value;
    var cutoff = document.getElementById('id_cutoffs').value;

    var url = 'generatePayslip.php?empid=' + employee + '&col_deptname=' + department + '&col_month=' + month + '&col_year=' + year + '&col_cutOffNum=' + cutoff;
    window.location.href = url;
}
</script>       

            <!-- ------------------para sa message na sucessful START -------------------->
            <?php

            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                '.$msg.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }
            ?>
            <!-------------------- para sa message na sucessful ENd --------------------->


            <!----------------------para sa message na error START --------------------->
            <?php
                if (isset($_GET['error'])) {
                $error = $_GET['error'];
                echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                '.$error.'
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
            }

            ?>
            <!-------------------- para sa message na error ENd --------------------->
            <div class="att-date" style="margin-top: 18px;">
                <h1 id="" style="">Payslip for <span id="current-date" style="color: #C37700">10/01/2023 - 10/15/2023</span></h1>
            </div>
            
        </div>
        <div class="row" style="width: 95%; margin:auto;">
            <div class="col-12 ">
                <div class="table-responsive" style="margin-top:70px;">
                    <form action="" method="post">
                        <input type="hidden" name="name_payslip" id="payslipID" value="">
                        <table id="order-listing" class="table table-hover table-borderless" cellspacing="0" >
                            <thead style="background-color: #f4f4f4;">
                                <tr>
                                <th style="display: none;" class="th-sm">ID</th>
                                <th class="th-sm">Employee ID</th>
                                <th class="th-sm">Employee Name</th>
                                <th class="th-sm">Department</th>
                                <th class="th-sm">Bank Name</th>
                                <th class="th-sm">Bank Account</th>
                                <th class="th-sm">No. of Day</th>
                                <th class="th-sm">Cutoff</th>
                                <th class="th-sm">Cutoff Number</th>
                                <th class="th-sm">Generated DateTime</th>
                                <th class="th-sm">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php 
                                include 'config.php';

                                $employee = $_GET['empid'] ?? '';
                                $department = $_GET['department_name'] ?? '';
                                $month = $_GET['col_month'] ?? '';
                                $year = $_GET['col_year'] ?? '';
                                $cutoff = $_GET['col_cutOffNum'] ?? '';
                                
                                $sql = "SELECT
                                payslip_tb.col_ID,
                                payslip_tb.col_empid,
                                CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                employee_tb.bank_name,
                                employee_tb.bank_number,
                                payslip_tb.col_numDaysWork,
                                CONCAT(cutoff_tb.col_month, ' ', cutoff_tb.col_year) AS month_year,
                                dept_tb.col_deptname,
                                cutoff_tb.col_cutOffNum,
                                payslip_tb._datetime
                            FROM
                                payslip_tb
                            INNER JOIN employee_tb ON payslip_tb.col_empid = employee_tb.empid
                            INNER JOIN cutoff_tb ON payslip_tb.col_cutoffID = cutoff_tb.col_ID
                            INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                            ";

                                if (!empty($employee) && $employee != 'All Employee') {
                                    $sql .= " WHERE employee_tb.empid = '$employee'";
                                }
                    
                                if (!empty($department) && $department != 'All Department') {
                                    $sql .= " AND dept_tb.col_deptname = '$department'";
                                }
                                
                                if (!empty($month) && !empty($year)) {
                                    $sql .= " AND cutoff_tb.col_month = '$month' AND cutoff_tb.col_year = '$year'";
                                }
                                
                                if (!empty($cutoff)) {
                                    $sql .= " AND cutoff_tb.col_cutOffNum = '$cutoff'";
                                }
                                
                                $sql .= "ORDER BY payslip_tb._datetime DESC";
                    
                                            
                                $result = $conn->query($sql);

                                // read data
                                while($row = $result->fetch_assoc()){
                                    $cmpny_empid = $row['col_empid'];

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
                    
                                    echo "<tr>
                                            <td style='display: none;'>" . $row['col_ID'] . "</td>
                                            <td>";
                                            $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                            $empid = $row['col_empid'];
                                            if (!empty($cmpny_code)) {
                                                echo $cmpny_code . " - " . $empid;
                                            } else {
                                                echo $empid;
                                            }
                                            echo "</td>
                                            <td>" . $row['full_name'] . "</td> 
                                            <td>" . $row['col_deptname'] . "</td>   
                                            <td>" . $row['bank_name'] . "</td>       
                                            <td>" . $row['bank_number'] . "</td>
                                            <td>" . $row['col_numDaysWork'] . "</td>   
                                            <td>" . $row['month_year'] . "</td>
                                            <td>" . $row['col_cutOffNum'] . "</td> 
                                            <td>" . $row['_datetime'] . "</td> 
                                            <td>
                                                <a class='viewrequest' href='Data Controller/Payslip/getPayslipdata.php?id=". $row['col_ID'] ."'><i class='fa-solid fa-eye fs-5 me-3'></i></a>
                                            </td>                                      
                                        </tr>"; 
                                }
                            ?>

                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            
        </div>
   

<!-- <script> 
        $(document).ready(function(){
        $('.viewrequest').on('click', function(){
        $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        console.log(data);
        $('#payslipID').val(data[0]);                  
    });
});
</script>      -->


<script>
function cutoff(value) {
    // Make an AJAX request to the server
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            // Handle the response from the server
                var response = this.responseText;
                
                document.getElementById('current-date').innerHTML = response;

                    // if (response === "exist") {
                    //     //console.log("Value exists in the database");
                                                
                    // } else {
                    //     //console.log("Value does not exist in the database");
                                                
                    // }
            }
        };
            xhttp.open("GET", "Data Controller/Payslip/getCutoffdata.php?value=" + encodeURIComponent(value), true);
            xhttp.send();
    }


function sortTable(columnIndex) {
    var table, rows, switching, i, x, y, shouldSwitch, direction, switchCount = 0;
    table = document.getElementById("myTable");
    switching = true;
    direction = "asc";
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[columnIndex];
            y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
            if (columnIndex === 0) {
                if (direction === "asc") {
                    if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (direction === "desc") {
                    if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                        shouldSwitch = true;
                        break;
                    }
                }
            } else {
                if (direction === "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (direction === "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchCount++;
        } else {
            if (switchCount === 0 && direction === "asc") {
                direction = "desc";
                switching = true;
            }
        }
    }
}

$(document).ready(function () {
  $('#dtDynamicVerticalScrollExample').DataTable({
    "scrollY": "50vh",
    "scrollCollapse": true,
  });
  $('.dataTables_length').addClass('bs-select');
});
</script>    

<script>
// sched form modal

let Modal = document.getElementById('schedules-modal-update');

//get open modal
let modalBtn = document.getElementById('sched-update');

//get close button modal
let closeModal = document.getElementsByClassName('sched-update-close')[0];

//event listener
modalBtn.addEventListener('click', openModal);
closeModal.addEventListener('click', exitModal);
window.addEventListener('click', clickOutside);

//functions
function openModal(){
    Modal.style.display ='block';
}

function exitModal(){
    Modal.style.display ='none';
}

function clickOutside(e){
    if(e.target == Modal){
        Modal.style.display ='none';    
    }
}
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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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