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
    <link rel="stylesheet" href="css/styles.css">
    <title>Employee Request</title>


   
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
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <?php 
        include 'header.php';
    ?>
</header>

<style>
    html{
        background-color: #f4f4f4;
    }
     .pagination{
        margin-right: 63px !important;
        /* margin-top: px !important; */
        /* border: black 1px solid; */

        
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

<!-------------------------------------- BODY START CONTENT ----------------------------------------------->
    <div class="request-container" style="position: absolute; left: 17.5%; top: 13%; width: 80%; height: 83%; background-color: #fff; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); border-radius: 0.8em;">
        <div class="container-title p-3" >
            <h2>Employee Request</h2>
            <div class="d-flex flex-row p-2">
                <?php
                    $server = "localhost";
                    $user = "root";
                    $pass ="";
                    $database = "hris_db";

                    $conn = mysqli_connect($server, $user, $pass, $database);
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
                <div class="">
                    <label for="emp" class="form-label">Select Employee
                        <select name="empname" id="sel_employee" class='form-select form-select-m' aria-label='.form-select-sm example' style=' height: 50px; width: 400px; cursor: pointer;'>
                            <option value disabled selected>Select Employee</option>
                            <?php echo $options; ?>
                        </select>
                    </label>
                </div>

                <div class="ml-5">
                    <?php
                        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                    ?>
                    <label for="id_strdate" class="form-label">Date Range :
                        <form class="form-floating">
                            <input type="date" class="form-control" name="date_from" id="id_inpt_strdate" style=' height: 50px; width: 400px;cursor: pointer;' value="<?php echo $dateFrom; ?>">
                            <label for="id_inpt_strdates">Start Date :</label>
                        </form>
                    </label>
                </div>

            </div>  
            <div class="d-flex flex-row p-2">
                <div>
                    <?php
                        $status = isset($_GET['status']) ? $_GET['status'] : '';
                    ?>
                    <label for="Select_dept" class="form-label">Select Status</label>
                    <select id="sel_stats" class='form-select form-select-m' aria-label='.form-select-sm example' style=' height: 50px; width: 400px; cursor: pointer;'>
                        <option value="">All Status</option>
                        <option value='Pending <?php if ($status == 'Pending') echo 'selected'; ?>'>Pending</option>
                        <option value='Approved <?php if ($status == 'Approved') echo 'selected'; ?>'>Approved</option>
                        <option value='Declined <?php if ($status == 'Declined') echo 'selected'; ?>'>Declined</option>
                        <option value='Cancelled <?php if ($status == 'Cancelled') echo 'selected'; ?>'>Cancelled</option>
                    </select>
                </div>
                <div class="ml-5">
                    <?php
                        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
                    ?>
                    <form class="form-floating" style="margin-top: 1.7em">
                        <input  type="date" class="form-control" name="date_to" id="id_inpt_enddate" style='  height: 50px; width: 400px; cursor: pointer;' value="<?php echo $dateTo; ?>">
                        <label for="id_inpt_enddates">End Date :</label>
                    </form>
                </div>

                <button type="button " class="btn btn-primary ml-5 mt-4" style="--bs-btn-padding-y: 5px; --bs-btn-padding-x: 20px; --bs-btn-font-size: .75rem; width: 9em; height: 4em" onclick="filterRequest()">
                GO
                </button>

            </div> 

        </div>
        <div class="p-4 mb-2 bg-secondary text-white ml-4 mr-4">List of all Request</div>

        <form action="actions/Employee List/empreq.php" method="post">
            <div class="table-responsive mt-3" style="width: 98%; margin:auto">
                <input type="hidden" name="name_reqType" id="id_reqType">
                <table id="order-listing" class="table" style="width: 100%;" >
                    <thead >   
                        <th style="display: none;"> ID </th>  
                        <th> Employee ID </th>
                        <th> Name </th>
                        <th> Positon </th> 
                        <th> Department </th>
                        <th> Date Filed </th>
                        <th> Request Type </th>
                        <th> Status </th>
                        <!-- <th>Name</th>                                             -->
                    </thead>
                        <tbody>
                            <?php
                                include 'config.php';

                                $empid = isset($_GET['empid']) ? $_GET['empid'] : '';
                                $status = isset($_GET['status']) ? $_GET['status'] : '';
                                $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                                $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

                                $sql = "
                                        SELECT
                                            CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                            positionn_tb.position AS Position,
                                            dept_tb.col_deptname AS Department,
                                            request_data.col_ID AS col_ID,
                                            request_data.col_req_emp AS col_req_emp,
                                            request_data.datefiled AS datefiled,
                                            request_data.col_status AS col_status,
                                            request_data.request_type AS request_type
                                        FROM employee_tb
                                        INNER JOIN (
                                            SELECT
                                                applyleave_tb.col_ID,
                                                applyleave_tb.col_req_emp,
                                                applyleave_tb.col_strDate AS datefiled,
                                                applyleave_tb.col_status,
                                                'Leave Request' AS request_type
                                            FROM applyleave_tb

                                            UNION

                                            SELECT
                                                overtime_tb.id AS col_ID,
                                                overtime_tb.empid AS col_req_emp,
                                                overtime_tb.work_schedule AS datefiled,
                                                overtime_tb.status AS col_status,
                                                'OverTime Request' AS request_type
                                            FROM overtime_tb

                                            UNION

                                            SELECT
                                                undertime_tb.id AS col_ID,
                                                undertime_tb.empid AS col_req_emp,
                                                undertime_tb.date AS datefiled,
                                                undertime_tb.status AS col_status,
                                                'Undertime Request' AS request_type
                                            FROM undertime_tb

                                            UNION

                                            SELECT
                                                wfh_tb.id AS col_ID,
                                                wfh_tb.empid AS col_req_emp,
                                                wfh_tb.date AS datefiled,
                                                wfh_tb.status AS col_status,
                                                'WFH Request' AS request_type
                                            FROM wfh_tb

                                            UNION

                                            SELECT
                                                emp_official_tb.id AS col_ID,
                                                emp_official_tb.employee_id AS col_req_emp,
                                                emp_official_tb.str_date AS datefiled,
                                                emp_official_tb.status AS col_status,
                                                'Official Business' AS request_type
                                            FROM emp_official_tb

                                            UNION

                                            SELECT
                                                emp_dtr_tb.id AS col_ID,
                                                emp_dtr_tb.empid AS col_req_emp,
                                                emp_dtr_tb.date AS datefiled,
                                                emp_dtr_tb.status AS col_status,
                                                'DTR Request' AS request_type
                                            FROM emp_dtr_tb
                                        ) AS request_data ON employee_tb.empid = request_data.col_req_emp
                                        INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                        INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID";

                                if (!empty($empid) && $empid != 'All Employee') {
                                    $sql .= " AND employee_tb.empid = '$empid'";
                                    }
                                        
                                if (!empty($status) && $status != 'All Status') {
                                    $sql .= " AND request_data.col_status = '$status'";
                                    }
                                        
                                if (!empty($dateFrom) && !empty($dateTo)) {
                                    $sql .= " AND request_data.datefiled BETWEEN'$dateFrom' AND '$dateTo'";
                                    }
                                    
                                $result = $conn->query($sql);
                                    
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                            $cmpny_empid = $row['col_req_emp'];

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
                            
                                                    $status = $row['col_status'];
                                                    $color = '';
                                                    if ($status == 'Pending') {
                                                        $color = 'orange';
                                                    } elseif ($status == 'Approved') {
                                                        $color = 'green';
                                                    } elseif ($status == 'Rejected') {
                                                        $color = 'red';
                                                    } elseif ($status == 'Cancelled') {
                                                        $color = 'gray';
                                                    }
                                            echo "<tr>";

                                            echo "<td style='display: none;'>" . $row['col_ID'] . "</td>";

                                            echo "<td style='font-weight: 400'>";
                                            $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                            $empid = $row['col_req_emp'];
                                            if (!empty($cmpny_code)) {
                                                echo $cmpny_code . " - " . $empid;
                                            } else {
                                                echo $empid;
                                            }
                                            echo "</td>";
                                            
                                            echo "<td scope='row'>
                                                    <button type='submit' name='view_data' class='viewbtn' title='View' style='border: none; background: transparent;
                                                        text-transform: capitalize; text-decoration: underline; cursor: pointer; color: #787BDB; font-size: 19px;'>
                                                        " . $row['full_name'] . "
                                                    </button>
                                                    </td>";

                                            echo "<td style='font-weight: 400'>" . $row['Position'] . "</td>";
                                            echo "<td style='font-weight: 400'>" . $row['Department'] . "</td>";
                                            echo "<td style='font-weight: 400'>" . $row['datefiled'] . "</td>";
                                            echo "<td style='font-weight: 400'>" . $row['request_type'] . "</td>";
                                            echo "<td style='font-weight: 400; color: $color;'>$status</td>";
                                            // echo "<td style='font-weight: 400'>" . $row['full_name'] . "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                ?>
                        </tbody>
                </table>
            </div>
        </form>    
        <div class="mt-3 p-3">
            <p class="fs-5">Export Options: <button style="border:none; background-color: inherit; color: green" id="export-csv-btn">CSV</button> | <button style="border:none; background-color: inherit; color: red" onclick="makePDF()">PDF</button></p>
        </div>
    </div>

    <script>
    function filterRequest() {
        var empid = document.getElementById('sel_employee').value;
        var status = document.getElementById('sel_stats').value;
        var dateFrom = document.getElementById('id_inpt_strdate').value;
        var dateTo = document.getElementById('id_inpt_enddate').value;

        var url = 'employeeRequest.php?empid=' + empid + '&status=' + status + '&date_from=' + dateFrom + '&date_to=' + dateTo;
        window.location.href = url;
    }
    </script>

    


    
<!-------------------------------------- BODY END CONTENT ------------------------------------------------>
<script>

window.html2canvas = html2canvas;
window.jsPDF = window.jspdf.jsPDF;

function makePDF() {
    html2canvas(document.querySelector("#order-listing"), {
        allowTaint: true,
        useCORS: true,
        scale: 0.7
    }).then(canvas => {
        var img = canvas.toDataURL("Employee Request Report");
        
        // Set the PDF to landscape mode
        var doc = new jsPDF({
            orientation: 'landscape'
        });

        doc.setFont('Arial');
        doc.getFontSize(11);
        doc.addImage(img, 'PNG', 10, 10, 0,0);
        doc.save("Employee Request Report.pdf");
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
        csvContent += "Employee ID, Name , Position, Department, Date Filed, Request Type, Status\n";

        // Loop through table rows and append data
        $('#order-listing tbody tr').each(function() {
            var empid = $(this).find('td:nth-child(2)').text();
            var name = $(this).find('td:nth-child(9)').text();
            var ot = $(this).find('td:nth-child(4)').text();
            var absent = $(this).find('td:nth-child(5)').text();
            var late = $(this).find('td:nth-child(6)').text();
            var under = $(this).find('td:nth-child(7)').text();
            var total_work = $(this).find('td:nth-child(8)').text();
            csvContent += empid + "," + name + "," + ot + ","  + absent + ","  + late + ","  + under + ","  + total_work +"\n";
        });

        // Create a CSV blob and trigger a download
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "Employee Requests.csv");
        document.body.appendChild(link);
        link.click();
    });
});
</script>


<script> 
        $(document).ready(function(){
            $('.viewbtn').on('click', function(){
            $('#update_deptMDL').modal('show');
        $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function () {
            return $(this).text();
        }).get();

        console.log(data);
                                    //id_colId
        $('#id_reqType').val(data[6]);
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