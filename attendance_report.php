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
    <link rel="stylesheet" href="css/attendance_report.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/attendance_reportResponsives.css">

    <title>Employee     Attendance Report</title>
</head>
<body>
<header>
    <?php
        include 'header.php';
        require_once "./SpecialFolders/Databases/Database.php";
    ?>
</header>

<style>
    .pagination{
        margin-right: 73px !important;


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
        margin-right: 24px !important;
        margin-bottom: -15.5px !important;

    }

    .card-body{
        width: 99.8%;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);
    }
</style>


<!------------------------------------------------- Header ------------------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 16.8%; position: absolute; top: 1.3%;">
        <div class="content-wrapper mt-4">
          <div class="card mt-3" style="width: 1500px; height: 780px; box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17); border-radius: 25px;">
            <div class="card-body">
                <div class="pnl_home">
                    <!-- <a href="dashboard.php">Home</a>
                    <p class="header_slash">\</p> -->
                    <h2 style="font-size: 23px; font-weight: bold;">Employee Attendance Report</h2>
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
                        echo "<select class='select-btn form-select-m' id='depts' aria-label='.form-select-sm example' name='name_emp''>";
                        echo "<option value='none' default>Select Department</option>"; // Add a default option
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

                            // Fetch all values of empid and date from the database
                            $sql = "SELECT employee_tb.empid AS employee_id,
                                    CONCAT(employee_tb.`fname`, ' ' ,employee_tb.`lname`) AS fullname
                                    FROM employee_tb ";
                            $result = mysqli_query($conn, $sql);

                            $emp_db = new DatabaseShit();

                            $emp_q = $emp_db->getConnection()->prepare($sql);
                            $emp_q->execute();

                            $emp_res = $emp_q->fetchAll(PDO::FETCH_ASSOC);

                            // Generate the dropdown list
                            echo "<select class='select-btn form-select-m' aria-label='.form-select-sm example' id='emp_id' name='name_emp''>";
                            echo "<option value='none' default>Select Employee</option>"; // Add a default option
                            // while ($row = mysqli_fetch_array($result)) {
                            // $employee_id = $row['employee_id'];
                            // echo "<option value='$employee_id'>$employee_id</option>"; // Set the value to emp_id|date
                            // }
                            foreach($emp_res as $r){
                                echo "<option value='$r[employee_id]'>$r[fullname]</option>"; // Set the value to emp_id|date
                            }
                            echo "</select>";
                        ?>
                </div>

                <div class="input-container">
                <p class="demm-text">Month From</p>
                <input class="select-btn" type="date" name="startdate" id="startdate" required>
                </div>
                <div class="input-container">
                <div class="notif">
                <p class="demm-text">Month To</p>
                <!-- <p id="validate" class="validation" style="display:none;">End date must beyond the start date</p> -->
                </div>
                <input class="select-btn" type="date" id="enddate" name="enddate" onchange="datefunct()" required>
                </div>
                <button id="arrowBtn" onclick="applyFilter()">Apply Filter</button>
            </div>
<!----------------------------------------select button and text input--------------------------------------->


<script>
    function applyFilter(){
        var dept_name = document.getElementById('depts').value
        var emp_id = document.getElementById('emp_id').value
        var start_time = document.getElementById('startdate').value
        var end_time = document.getElementById('enddate').value


        url = `attendance_report.php?`

        if(dept_name !== "none"){
            url += `deptname=${dept_name}`
        }
        if(emp_id !== "none"){
            url += `&empid=${emp_id}`
        }
        if(start_time !== "" && end_time !== ""){
            console.log(`start_time: ${start_time}, end_time: ${end_time}`)
            url += `&startdate=${start_time}&enddate=${end_time}`
        }

        window.location.href = url
    }
</script>



<!-------------------------------------------------TABLE START------------------------------------------->

                <div class="row">
                    <div class="col-12 mt-5 tablet-col">
                        <div class="table-responsive" style="overflow: hidden;">
                            <table id="order-listing" class="table" style="width: 100%;">
                                <thead style="background-color: #ececec;">
                                        <tr>
                                            <th style="display: none;">ID</th>
                                            <th>Employee ID</th>
                                            <th>Name</th>
                                            <th>Overtime hours</th>
                                            <th>Absences</th>
                                            <th>Late</th>
                                            <th>Undertime</th>
                                            <th>Total Work Hours</th>
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
                                                CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                                attendances.date, attendances.time_in,
                                                attendances.time_out,
                                                SUM(CASE WHEN attendances.status = 'Absent' THEN 1 ELSE 0 END) AS absent_count,
                                                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.overtime)))AS total_overtime,
                                                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.late))) AS total_late,
                                                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.early_out))) AS total_early_out,
                                                SEC_TO_TIME(SUM(TIME_TO_SEC(attendances.total_work))) AS total_work
                                                FROM attendances
                                                INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                ";

                                            if(isset($_GET["startdate"]) && isset($_GET["enddate"])){
                                                $start_date = $_GET["startdate"];
                                                $end_date = $_GET["enddate"];

                                                $query .= "WHERE attendances.date BETWEEN '$start_date' AND '$end_date'";
                                                // echo $query;
                                            }
                                            else{
                                                $query .= "WHERE MONTH(attendances.date) = $currentMonth
                                                            AND YEAR(attendances.date) = $currentYear
                                                            ";
                                            }
                                            if(isset($_GET["deptname"])){
                                                $dept_name = $_GET["deptname"];
                                                $query .= "AND dept_tb.col_deptname = '$dept_name'
                                                ";
                                            }
                                            if(isset($_GET["empid"])){
                                                $emp_id = $_GET["empid"];
                                                $query .= "AND employee_tb.empid = $emp_id
                                                ";
                                            }
                                            // if(isset($_GET["startdate"]) && isset($_GET["enddate"])){
                                            //     $start_date = $_GET["startdate"];
                                            //     $end_date = $_GET["enddate"];

                                            //     $query .= "AND ";
                                            // }

                                            $query .= "GROUP BY employee_tb.empid;";


                                            //Enough sql, time for PDO

                                            //Initiate PDO Database Connection:

                                            $db = new DatabaseShit();

                                            $q = $db->getConnection()->prepare($query);
                                            $q->execute();

                                            $res = $q->fetchAll(PDO::FETCH_ASSOC);

                                            // var_dump($res);

                                            foreach($res as $r)
                                            {
                                                echo "
                                                <tr>
                                                    <td>$r[empid]</td>
                                                    <td>$r[full_name]</td>
                                                    <td>$r[total_overtime]</td>
                                                    <td>$r[absent_count]</td>
                                                    <td>$r[total_late]</td>
                                                    <td></td>
                                                    <td>$r[total_work]</td>
                                                </tr>
                                                ";
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
                            <button class="excel" onclick="exportExcel()">Excel</button>
                            <p class="lbl_exprt_contnt">|</p>
                            <button class="pdf" onclick="exportPDF()">PDF</button>
                        </div>
                    </div>

            </div>
         </div>
     </div>
 </div><!---Main Panel Close Tag--->
<!-------------------------------------------------TABLE END------------------------------------------->

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
