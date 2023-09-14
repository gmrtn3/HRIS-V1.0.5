
<?php
    session_start();
    if(!isset($_SESSION['username'])){
        header("Location: login.php"); 
    }
 
   include 'config.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">


    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css">
    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>



<!-- skydash -->

<link rel="stylesheet" href="skydash/feather.css">
    <link rel="stylesheet" href="skydash/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="skydash/vendor.bundle.base.css">

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/employee.css"> 
    <link rel="stylesheet" href="css/attendanceResponsived.css"> 

    <title>HRIS | Employee List</title>
</head>

<style>
   
   html{
        background-color: #f4f4f4 !important;
        overflow: hidden;
       
    }
    body{
        overflow: hidden;
        background-color: #F4F4F4 !important;
    }

    .pagination{
        margin-right: 30px !important;
        color: white !important;

        
    }

    .pagination li a{
        color: #c37700;
    }

        .page-item.active .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-page .page-link, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button a, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-page a {
        z-index: 3;
        color: #fff;
        background-color: #000;
        border-color: #000;
        margin-top: 20px;
    }

    
    
    #order-listing_next{
        margin-top: 20px;
        margin-right: 4px !important;
        margin-bottom: -15.5px !important;
        
    }

    #order-listing_previous{
        margin-top: 20px;
        margin-left: 12px !important;
    }
    
    
    

    /* Search Bar */

    #order-listing_filter label input{
       
        width: 278px;
        font-size: 17px;
        
    }

    /* Sorting Button Color */
    .dataTables_wrapper .dataTable thead .sorting:before, .dataTables_wrapper .dataTable thead .sorting_asc:before, .dataTables_wrapper .dataTable thead .sorting_desc:before, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:before, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:before {
        
        right: 1.2em;
        bottom: 0;
        color: #c0c1c2 !important;
        opacity: 1;
    } 

    .dataTables_wrapper .dataTable thead .sorting:after, .dataTables_wrapper .dataTable thead .sorting_asc:after, .dataTables_wrapper .dataTable thead .sorting_desc:after, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:after, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:after {
   
        right: 1.2em;
        top: 0;
        color: #c0c1c2 !important;
        opacity: 1;
    }


    .table-responsive{
        overflow-x: hidden !important;
        width: 98% !important;
        margin: auto !important;
        height: 530px !important;
        
        
    }
    
    /* Not working on CSS so use this */
    @media(max-width: 1350px){

    .table-responsive{
        width: 98% !important;
        overflow-x: scroll !important;
        padding: 1% !important;
        
    }
    
}

    /* table .thead-bg th{
    background-color: #abacad !important; 
   } */

</style>

    <header>
        <?php include("header.php")?>
    </header>

    <div class="attendance-container" style="background-color: #fff; left: 18.7%; position: absolute; top: 13.5%;  border-radius: 25px; box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17); width:1500px; height:780px;">
        <div class="attendance-content">
            <h2 style="font-size: 23px; font-weight: bold; padding: 20px;">Attendance</h2>
            <div class="attendance-date form-group">
                <label for="" style="margin-right: 18px; margin-top: 10px; margin-left: 40px;">Date Range</label>

                <?php
                    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
                    
                ?>

                <input type="date" class="form-control" name="date_from" id="start_date" style="width: 250px; height: 50px; margin-right: 30px;" value="<?php echo $dateFrom; ?>">
                <input type="date" class="form-control" name="date_to" id="end_date" style="width: 250px; height: 50px; " value="<?php echo $dateTo; ?>">

                <button id="applyfilt" onclick="filterDates()">Apply Filter</button> <!--margin-left: 60px;-->
            </div>



            <div class="table-responsive" style="padding: 5px; overflow-x: hidden;">
                <table id="order-listing" class="table" style="width: 100%; padding-right: 1%;">
                    <thead class="thead-bg">
                        <th>Status</th>
                        <th>Employee ID</th>
                        <th>Date</th>
                        <th>Time Entry</th>
                        <th>Time Out</th>
                        <th>Late</th>
                        <th>Undertime</th>
                        <th>Overtime</th>
                        <th>Total Work</th>
                        <th>Total Rest</th>
                        <th>Remarks</th>
                    </thead>
                    <tbody>
                        <?php 
                            include 'config.php';

                            $empid = $_SESSION['empid'];
                            // Check if the filter dates are submitted
                            if (isset($_GET['date_from']) && isset($_GET['date_to'])) {
                                $dateFrom = $_GET['date_from'];
                                $dateTo = $_GET['date_to'];
                            
                                // Add the date filter to the SQL query
                                $sql = "SELECT * FROM attendances WHERE empid = $empid AND date BETWEEN '$dateFrom' AND '$dateTo'";
                            } else {
                                // If no filter dates are provided, retrieve all data for the employee
                                $sql = "SELECT * FROM attendances WHERE empid = $empid";
                            }

                            $result = $conn->query($sql);
                            while($row = $result->fetch_assoc()){
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
                                    <td style="font-weight: 400;"><?php echo $row['status']; ?></td>
                                    <td class="empid-width" style="font-weight: 400;">
                        <?php
                        $cmpny_code = $cmpny_row['company_code_name'] ?? '';
                        echo $cmpny_code !== '' ? $cmpny_code . ' - ' . $row['empid'] : $row['empid'];
                        ?>
                    </td>
                                    <td style="font-weight: 400;"><?php echo $row['date']; ?></td>
                                            <!-------- td  for time out ----------->
                                    <td 
                                        <?php 
                                            if ($row['status'] === 'LWOP'){
                                                echo 'style="font-weight: 400; text-align: center;"';
                                            }
                                            else{
                                                if($row['time_in'] === '00:00:00')
                                                {
                                                    echo 'style="color: #FF5D5E;" ';
                                                }
                                                else
                                                {
                                                    echo 'style="font-weight: 400;"';
                                                }
                                                
                                            }
                                        ?>
                                    > <!--close td -->
                                        <?php 
                                            echo $row['time_in']; 
                                        ?>
                                    </td>
                                            <!-------- td  for time out ----------->
                                    <td  
                                        <?php 
                
                                            if ($row['status'] === 'LWOP'){
                                                echo 'style="font-weight: 400; text-align: center;"';
                                            }
                                            else{
                                                if($row['time_out'] === '00:00:00')
                                                {
                                                    echo 'style="color: #FF5D5E;" ';
                                                }
                                                else
                                                {
                                                    echo 'style="font-weight: 400;"';
                                                }
                                                
                                            }
                                           
                                        ?>
                                    > <!--close td -->
                                        <?php 
                                            echo $row['time_out']; 
                                        ?>
                                    </td>
                                    
                                    <td style="font-weight: 400; color:red;"><?php echo $row['late']; ?></td>
                                    <td style="font-weight: 400; color: blue"><?php echo $row['early_out']; ?></td>
                                    <td style="font-weight: 400; color: orange;"><?php echo $row['overtime']; ?></td>
                                    <td style="font-weight: 400; color:green;"><?php echo $row['total_work']; ?></td>
                                    <td style="font-weight: 400; color:gray;"><?php echo $row['total_rest']; ?></td>
                                    <td 
                                        <?php 
                                        if ($row['status'] === 'LWOP'){
                                            echo 'style="font-weight: 400; text-align: center;"';
                                        }
                                        else{
                                            if($row['time_in'] === '00:00:00' || $row['time_out'] === '00:00:00')
                                            {
                                                echo 'style="color: #FF5D5E;  text-align: center;"';
                                            } 
                                            else{
                                                echo 'style="font-weight: 400; text-align: center;"';
                                            }
                                            
                                        }
                                            
                                            
                                        ?> 
                                    > <!--close td -->
                                        <?php
                                            if($row['status'] === 'LWOP'){
                                                echo 'N/A';
                                            }else{
                                                if($row['time_in'] === '00:00:00')
                                                {
                                                    echo 'NO TIME IN';
                                                }
                                            else if($row['time_out'] === '00:00:00')
                                                {
                                                    echo 'NO TIME OUT';
                                                }
                                            else
                                                {
                                                    echo 'N/A';
                                                }
                                            }
                                            
                                         ?>
                                    </td>
                                </tr> 
                                <?php 
                            }
                        ?>
                    </tbody>       
                </table>
               
            </div>
        </div> 
    </div>

    <script>
  function filterDates() {
    var dateFrom = document.getElementById('start_date').value;
    var dateTo = document.getElementById('end_date').value;
    var applyfilt = document.getElementById('applyfilt');

    if(dateFrom == '' && dateTo ==''){
        var url = 'attendance.php';
        window.location.href = url;  
    }else{
        var url = 'attendance.php?date_from=' + dateFrom + '&date_to=' + dateTo;
        window.location.href = url;   
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
      $('#dashboard-container').addClass('move-content');
    } else {
      $('#dashboard-container').removeClass('move-content');

      // Add class for transition
      $('#dashboard-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#dashboard-container').removeClass('move-content-transition');
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 390) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 390) {
      $('#sidebar').toggleClass('active-sidebars');
    }
  });
});


$(document).ready(function() {
  // Toggle the submenu visibility on click (for mobile devices)
//   $('.nav-link').on('click', function(e) {
//     if ($(window).width() <= 500) {
//       e.preventDefault();
//       $(this).siblings('.sub-menu').slideToggle();
//     }
//   });

  // Hamburger button functionality
  $('.responsive-bars-btn').on('click', function() {
    if ($(window).width() <= 500) {
      $('#sidebar').toggleClass('active-sidebar');
    }
  });
});


</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>

    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>
    
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