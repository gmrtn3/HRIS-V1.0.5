<?php
session_start();
include 'config.php';
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



    
    
    <link rel="stylesheet" href="css/attendance_visor.css"/>
    
    <link rel="stylesheet" href="css/styles.css">

<link rel="stylesheet" href="css/try.css">

    <title>Attendance</title>
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
        background-color: #f4f4f4;
    }

    .pagination{
        margin-right: 63px !important;
        
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
        margin-top: 20px;
    }

    
    
    #order-listing_next{
        margin-right: 18px !important;
        margin-bottom: -17px !important;

    }
    
</style>




<div class="main-panel mt-5">
    <div class="">
        <div class="card">
            <div class="card-body">

                    <div class="row">
                       <div class="col-6">
                          <h2 style="margin-top: -10px;">Attendance</h2>
                      </div>
                     </div> <!--ROW END-->

                            <div class="drop_down_contain">
                                <div class="child_container">
                                    <div class="select-employee">
                                    <?php
                                            include 'config.php';
                                            $sql = "SELECT `empid`, CONCAT(`fname`, ' ',`lname`) AS `full_name` FROM employee_tb";
                                            $result = mysqli_query($conn, $sql);

                                            $empid = isset($_GET['empid']) ? $_GET['empid'] : '';
                                            $options = "";
                                            $options .= "<option value='All Employee'" . ($empid == 'All Employee' ? ' selected' : '') . ">All Employee</option>";
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $emp_id = $row['empid'];
                                                $emp_name = $row['full_name'];
                                                $selected = ($empid == $emp_id) ? ' selected' : '';
                                                $options .= "<option value='$emp_id' $selected>$emp_id - $emp_name</option>";
                                                
                                            }
                                        ?>
                                            <label for="schedule_name">Employee</label>
                                            <select name="schedule_name" id="sel_employee">
                                                <option value disabled selected>Select Employee</option>
                                                <?php echo $options; ?>
                                            </select>
                                    </div>

                                    <div class="select-status">
                                        <?php
                                            $status = isset($_GET['status']) ? $_GET['status'] : '';
                                        ?>
                                        <label for="select1">Status</label>
                                        <select id="sel_stats" name="status">
                                        <option class="select-color" value="" default>Select Status</option>
                                        <option class="select-color" value="">All Status</option>
                                        <option class="select-color" value="Present" <?php if ($status == 'Present') echo 'selected'; ?>>Present</option>
                                        <option class="select-color" value="Absent" <?php if ($status == 'Absent') echo 'selected'; ?>>Absent</option>
                                        <option class="select-color" value="On-Leave" <?php if ($status == 'On-Leave') echo 'selected'; ?>>On-Leave</option>
                                        <option class="select-color" value="LWOP" <?php if ($status == 'LWOP') echo 'selected'; ?>>LWOP</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="second_child_container">
                                <?php
                                        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                                        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
                                    ?>
                                    <div class="date-group">
                                        <label for="start-date">Date Range</label>
                                        <input class="date_size" type="date" id="startdate" name="date_from" value="<?php echo $dateFrom; ?>">
                                    </div>
                                    <div class="date-group">
                                        <label for="end-date"></label>
                                        <input class="date_size2" type="date" id="enddate" name="date_to" value="<?php echo $dateTo; ?>">
                                    </div>
                                </div>
                                <div class="third_child_container"> 
                                <button id="go_btn" class="btn_go filter_btn" style="" onclick="filterAttData()">Apply Filter</button>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-12 mt-3">
                                    <div class="table-responsive">
                                    <table id="order-listing" class="table">
                                            <thead>
                                                <tr>
                                                    <th style="display:none;">ID</th>
                                                    <th>Status</th>
                                                    <th>Employee ID</th>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                    <th>Time in</th>
                                                    <th>Time out</th>
                                                    <th>Late</th>
                                                    <th>Undertime</th>
                                                    <th>Overtime</th>
                                                    <th>Total Work</th>
                                                    <th>Total Rest</th>
                                                </tr>
                                            </thead>
                                            <?php
                                            include '../config.php';

                                            $empid = $_GET['empid'] ?? '';
                                            $status = $_GET['status'] ?? '';
                                            $dateFrom = $_GET['date_from'] ?? '';
                                            $dateTo = $_GET['date_to'] ?? '';
                                            
                                            $aprrover_ID = $_SESSION['empid'];
                                            date_default_timezone_set('Asia/Manila');
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
                                                    INNER JOIN employee_tb ON attendances.empid = employee_tb.empid
                                                    INNER JOIN approver_tb ON approver_tb.empid = attendances.empid
                                                    WHERE
                                                    approver_tb.approver_empid = '$aprrover_ID'
                                                    AND DATE(attendances.date) = CURDATE()"; // Modify the query to filter by the current date

                                                    if (!empty($empid) && $empid != 'All Employee') {
                                                        $query .= " AND employee_tb.empid = '$empid'";
                                                    }

                                                    if (!empty($status) && $status != 'All Employee') {
                                                        $query .= " AND attendances.status = '$status'";
                                                    }

                                                    if (!empty($dateFrom) && !empty($dateTo)) {
                                                        $query .= " AND attendances.date BETWEEN '$dateFrom' AND '$dateTo'";
                                                    }

                                            $result = mysqli_query($conn, $query);
                                            while($row = mysqli_fetch_assoc($result)){
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
                                                    <td style="display:none;"><?php echo $row['id']?></td>
                                                    <td><?php echo $row['status']?></td>
                                                    <td><?php $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                                    $empid = $row['empid'];
                                                    if (!empty($cmpny_code)) {
                                                        echo $cmpny_code . " - " . $empid;
                                                    } else {
                                                        echo $empid;
                                                    } ?></td>
                                                    <td><?php echo $row['full_name']?></td>
                                                    <td><?php echo date('Y-m-d (l)', strtotime($row['date'])) ?></td>
                                                    <td><?php echo $row['time_in']?></td>
                                                    <td><?php echo $row['time_out']?></td>
                                                    <td><?php echo $row['late']?></td>
                                                    <td><?php echo $row['early_out']?></td>
                                                    <td><?php echo $row['overtime']?></td>
                                                    <td><?php echo $row['total_work']?></td>
                                                    <td><?php echo $row['total_rest']?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </table>
                                     </div>
                                 </div>
                             </div>
                                            
            </div>
        </div>
    </div>
</div>                

<!----------------------------Script sa pagfilter ng data table------------------------->
<script>
function filterAttData() {
  var empid = document.getElementById('sel_employee').value;
  var status = document.getElementById('sel_stats').value;
  var dateFrom = document.getElementById('startdate').value;
  var dateTo = document.getElementById('enddate').value;

  var url = 'attendance_visor.php?empid=' + empid + '&status=' + status + '&date_from=' + dateFrom + '&date_to=' + dateTo;
  window.location.href = url;
}

</script>
<!----------------------------Script sa pagfilter ng data table------------------------->



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
                                    $('#empName').val(data[0]);
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