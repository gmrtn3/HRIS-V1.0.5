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
    <title>Generate Payroll</title>
   

    <!-- Para sa datatables -->

    
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

    <link rel="stylesheet" href="css/gnrate_payroll.css">
</head>
<body>
  
<header>
    <?php 
        include 'header.php';
    ?>
</header>

<style>
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
</style>

<!---------------------------------------- MAIN CONTAINER START ------------------------------------------->
<div class="container mt-5"  >
                    <div class="card"  >
                        <div class="card-body" style="background-color: #fff;">
                            <h2 class="head_text">Generate Payroll</h2>
                             
                           

                            <!-- <div class="row">
                    
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="Select_dept" class="form-label">Select Employee :</label> -->
                                        <?php
                                            // include 'config.php';

                                            // // Fetch all values of fname and lname from the database
                                            // $sql = "SELECT fname, lname, empid FROM employee_tb";
                                            // $result = mysqli_query($conn, $sql);

                                            // // Generate the dropdown list
                                            // echo "<select class='form-select form-select-m' aria-label='.form-select-sm example' name='name_emp' id='select_emp' style=' height: 50px; width: 400px; cursor: pointer;'>";
                                            
                                            // while ($row = mysqli_fetch_array($result)) {
                                            //     $emp_id = $row['empid'];
                                            //     $name = $row['empid'] . ' - ' . $row['fname'] . ' ' . $row['lname'];
                                            //     echo "<option value='$emp_id'>$name</option>";
                                                
                                            // }
                                            // echo "<option value='All Employee'>All Employee</option>";
                                            // echo "</select>";
                                        ?>
                                    <!-- </div>
                                </div>   -->

                                                    <!----------------------BREAK--------------------------> 
                                <!-- <div class="col-4"> -->
                                    <!-- <div class="mb-3">
                                        <label for="Select_dept" class="form-label">Select Month :</label>
                                                <select class='form-select form-select-m' aria-label='.form-select-sm example' name="select_date" id="select_date_id" style=' height: 50px; width: 400px; cursor: pointer;'>
                                                    <option value='January'>January</option>
                                                    <option value='Febuary'>Febuary</option>
                                                    <option value='March'>March</option>
                                                    <option value='April'>April</option>
                                                    <option value='May'>May</option>
                                                    <option value='June'>June</option>
                                                    <option value='July'>July</option>
                                                    <option value='August'>August</option>
                                                    <option value='September'>September</option>
                                                    <option value='October'>October</option>
                                                    <option value='November'>November</option>
                                                    <option value='December'>December</option>
                                                </select>
                                    </div>  -->
                                    <!-- <div class="mb-3">
                                        <label for="Select_dept" class="form-label">Select Department :</label>
                                        <?php
                                            // include 'config.php';

                                            // // Fetch all values of col_deptname from the database
                                            // $sql = "SELECT col_ID, col_deptname FROM dept_tb";
                                            // $result = mysqli_query($conn, $sql);

                                            // // Store all values in an array
                                            // $dept_options = array();
                                            // while($row = mysqli_fetch_array($result)){
                                            //     $dept_options[] = $row['col_deptname'];
                                            // }

                                            // // Generate the dropdown list
                                            // echo "<select class='form-select form-select-m' aria-label='.form-select-sm example' name='select_coldeptname' id='id_select_dept' style=' height: 50px; width: 400px; cursor: pointer;'>";
                                            
                                            // foreach ($dept_options as $dept_option){
                                            //     echo "<option value='$dept_option'>$dept_option</option>";
                                            // }
                                            // echo "<option value='All Department'>All Department</option>";
                                            // echo "</select>";
                                        ?>
                                    </div>  -->
                                <!-- </div> -->
                                                    <!----------------------BREAK--------------------------> 

                                <!-- <div class="col-4 mt-4">
                                    <button type="button" class="btn btn-primary" name="btnGO" id="id_btnGo" style="--bs-btn-padding-y: 5px; --bs-btn-padding-x: 20px; --bs-btn-font-size: .75rem;" onclick="filterGenerate()">
                                        GO
                                    </button>
                                </div> -->
                                                    
                            <!-- </div> -->
                                             <!--------------------------------------BREAK-------------------------------------------> 

                             

                                            <!--------------------------------------BREAK-------------------------------------------> 

                                        <?php
                                            include 'config.php';

                                            //select data db
                                            $cutOffID = $_POST['name_btnview'];

                                            $result_cutOff= mysqli_query($conn, "SELECT
                                                *  
                                            FROM
                                                `cutoff_tb`
                                            WHERE col_ID = $cutOffID");
                                            if(mysqli_num_rows($result_cutOff) > 0) {
                                            $row_cutoff= mysqli_fetch_assoc($result_cutOff);
                                            $col_month = $row_cutoff['col_month'];
                                            $col_year = $row_cutoff['col_year'];
                                            $str_date =  $row_cutoff['col_startDate'];
                                            $end_date =  $row_cutoff['col_endDate'];
                                            $cut_off_freq =  $row_cutoff['col_frequency'];
                                            $cut_off_num =  $row_cutoff['col_cutOffNum'];
                                                echo '<input type="text" name="name_cutOff_str"  value="'. $str_date .'" style="display: none;">';
                                                echo '<input type="text" name="name_cutOff_end"  value="'. $end_date .'" style="display: none;">';
                                                echo '<input type="text" name="name_cutOff_freq"  value="'. $cut_off_freq .'" style="display: none;">';
                                                echo '<input type="text" name="name_cutOff_num"  value="'. $cut_off_num .'" style="display: none;">';
                                            } else {
                                                echo "No results found.";
                                            }

                                        ?>
                                           
                                    <div class=" p-3 mb-2 " style= "background-color: #F2F2F2;">  <!-- PARA SA RANGE MONTHS LABEL -->
                                    <div class="input-group flex-nowrap">
                                    <h3 style= "font-size: 20px; font-weight: bold; font-family: 'Nunito', sans-serif; ">Payslip Information For </h3>
                                        <h3 style= "color: #747BDA; font-size: 20px; font-weight: bold; font-family: 'Nunito', sans-serif; margin-left: 15px;  margin-right: 15px;"> <?php echo $col_month; ?> </h3>        
                                        <h3 style= "font-size: 20px; font-weight: bold; font-family: 'Nunito', sans-serif; "> <?php echo $col_year;?></h3>      
                                    </div>
                                                                     
                                    </div> <!-- PARA SA RANGE MONTHS LABEL END-->




                                    <div class="table-responsive" style = "overflow-y: scroll;  max-height: 500px;">
                                        <form action="gnrate_payroll_view.php" method="post">
                                        <input id="employeeID" name="Name_employeeID" type="text" style= "display:none;">  
                                        <input type="hidden" name="name_cutoffID" value="<?php echo $_POST['name_btnview']; ?>">       
                                            <table id="order-listing" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Employee ID</th>
                                                        <th>Employee Name</th>
                                                        <th>Type</th> 
                                                        <th>Cut off Start</th>
                                                        <th>Cut off End</th>
                                                        <th>Action</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                <?php 
                                         include 'config.php';

                                         //select data db
                                         $cutOffID = $_POST['name_btnview'];

                                         $result_cutOff= mysqli_query($conn, "SELECT
                                             *  
                                         FROM
                                             `cutoff_tb`
                                         WHERE col_ID = $cutOffID");
                                         if(mysqli_num_rows($result_cutOff) > 0) {
                                         $row_cutoff= mysqli_fetch_assoc($result_cutOff);
                                         $col_month = $row_cutoff['col_month'];
                                         $col_year = $row_cutoff['col_year'];
                                         $str_date =  $row_cutoff['col_startDate'];
                                         $end_date =  $row_cutoff['col_endDate'];
                                         $cut_off_freq =  $row_cutoff['col_frequency'];
                                         $cut_off_num =  $row_cutoff['col_cutOffNum'];
                                             echo '<input type="text" name="name_cutOff_str"  value="'. $str_date .'" style="display: none;">';
                                             echo '<input type="text" name="name_cutOff_end"  value="'. $end_date .'" style="display: none;">';
                                             echo '<input type="text" name="name_cutOff_freq"  value="'. $cut_off_freq .'" style="display: none;">';
                                             echo '<input type="text" name="name_cutOff_num"  value="'. $cut_off_num .'" style="display: none;">';
                                         } else {
                                             echo "No results found.";
                                         }
         
                                        $sql = "SELECT
                                                    employee_tb.`empid`,
                                                    CONCAT(
                                                        employee_tb.`fname`,
                                                        ' ',
                                                        employee_tb.`lname`
                                                    ) AS `full_name`,
                                                    cutoff_tb.col_type,
                                                    cutoff_tb.col_startDate,
                                                    cutoff_tb.col_enddate
                                                    
                                                FROM
                                                    employee_tb
                                                INNER JOIN empcutoff_tb ON employee_tb.empid = empcutoff_tb.emp_ID
                                                INNER JOIN cutoff_tb ON empcutoff_tb.cutOff_ID = cutoff_tb.col_ID
                                                INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                WHERE empcutoff_tb.cutOff_ID = $cutOffID";

                                    $result = $conn->query($sql);

                                        while($row = $result->fetch_assoc()){
                                            echo "<tr>
                                                <td>" . $row['empid'] . "</td>
                                                <td>" . $row['full_name'] . "</td>
                                                <td>" . $row['col_type'] . "</td>
                                                <td>" . $row['col_startDate'] . "</td>
                                                <td>" . $row['col_enddate'] . "</td>
                                                
                                                <td>
                                                <button type='submit' name='name_btnView' class='border-light viewbtn' title='View' style='border:none; background-color: inherit;'>
                                                    <i class='fa-regular fa-eye' style='font-size: 25px;'></i>
                                                </button>
                                            </td>
                                            
                                            </tr>"; 
                                        }
                                    ?>  
                             
                                                </tbody>
                                            </table>
                                        </form>
                                    </div> <!--table-responsive END-->
                           
                            

                        </div> <!--  END CARD BODY -->
                    </div> <!--  END CARD -->
            </div> <!--  END MAIN PANEL -->


<script> //FOR VIEW GET EMP ID PUT INTO MODAL PAYROLL
            $(document).ready(function(){
                                    $('.viewbtn').on('click', function(){
                                        $('#view_payslip').modal('show');
                                        $tr = $(this).closest('tr');

                                        var data = $tr.children("td").map(function () {
                                            return $(this).text();
                                        }).get();

                                        console.log(data);
                                        //id_colId
                                        $('#employeeID').val(data[0]);
                                    });
                                });
            //FOR VIEW GET EMP ID PUT INTO MODAL PAYROLL END
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