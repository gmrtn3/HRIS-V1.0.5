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
    <title>Create Cutoff</title>


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

    <link rel="stylesheet" href="css/cutOff.css">
    <link rel="stylesheet" href="css/gnrate_payroll.css">
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- <script type="text/javascript" src="js/multi-select-dd.js"></script> -->
    <!-- para sa font ng net pay -->
    <link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Barlow&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
    </style>
  


</head>
<body>

<header>
    <?php 
        include 'header.php';
    ?>
</header>

<style>
    .multiselect-dropdown{
        width: 400px !important;
        margin-left: 20px !important;
        padding: 10px !important;
    }
     .multiselect-dropdown-all-selector label{
        background-color: white;
     }
    .multiselect-dropdown-all-selector{
       
        display: flex !important;
        flex-direction: row !important;
    }

    .multiselect-dropdown-list div{
   
        display: flex !important;
        flex-direction: row !important;
    }

    .multiselect-dropdown-list div label{
        background-color: white;
    }
    .placeholder{
        display: none !important;
        cursor: default !important;
        background-color: #fff !important;
        color: #fff !important;
        display:none !important;
        
    }

    .multiselect-dropdown-list-wrapper span.placeholder{
        display: none !important;
        cursor: default !important;
        background-color: #fff !important;
        color: #fff !important;
        display:none !important; 
    }
    .multiselect-dropdown{
        height: 40px !important; 
        width: 100% !important;
        margin-left: 0 !important;
        border: #CED4DA 1px solid !important;

    }
    
    #multi_option{
	        max-width: 100%;
	        width: 100%;
        }
        #multi_options{
	        max-width: 100%;
	        width: 100%;
        }

        .dropdown{
            background-color: inherit !important;
            border: none !important;
        }
        #notificationDropdown:hover{
            background-color: inherit !important;
        }
</style>
<!-- Modal -->
<div class="modal fade" id="modal_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Create New Cutoff</h1>

       

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <form action="Data Controller/Payroll/Save_cutOff.php" method="post">
        <div class="modal-body">
            
            <!-- <div class="row mt-1">
                    <div class="col-6"  style="border: 1px solid #D1D1D1; padding: 10px;">
                        Company : 
                    </div>
                    <div class="col-6"  style="border: 1px solid #D1D1D1; padding: 10px;">
                        Slastech Solutions INC.
                    </div>
            </div> END row1 -->
            <!---------------- BREAK -------------->

            <div class="row" style=" border: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-6 mt-2">
                        Type : 
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <select id="" required name="name_type" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                                <option selected value='Standard'>Standard</option>
                            </select>
                        </div> <!-- Second mb-3 end-->
                    </div> <!-- col-6 end-->
            </div> <!--END row2 -->
            <!---------------- BREAK -------------->

            <div class="row" style=" border-bottom: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; border-left: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-6 mt-2">
                        Frequency : 
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <select id="frequency" required name="name_frequency" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                                <option selected value='Monthly'>Monthly</option>
                                <option value='Semi-Month'>Semi-Month</option>
                                <option value='Weekly'>Weekly</option>
                            </select>
                        </div> <!-- Second mb-3 end-->
                    </div> <!-- col-6 end-->
            </div> <!--END row3 -->
            <!---------------- BREAK -------------->

            <div class="row" style=" border-bottom: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; border-left: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-3 mt-3">
                        Month : 
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <select id="" required name="name_Month" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                                <option disabled selected value=''>Pick a Month</option>
                                <option value='January'>January</option>
                                <option value='February'>February</option>
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
                        </div> <!-- Second mb-3 end-->
                    </div> <!-- col-6 end-->

                    <div class="col-3 mt-3">
                        Year : 
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <select id="" required name="name_year" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                                <option disabled selected value=''>Pick a Year</option>
                                <option value='2023'>2023</option>
                            </select>
                        </div> <!-- Second mb-3 end-->
                    </div> <!-- col-6 end-->
            </div> <!--END row4 -->
            <!---------------- BREAK -------------->

            <div class="row" style=" border-bottom: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; border-left: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-6 mt-2">
                        Start Date : 
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="date" required name="name_strDate" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                        </div>
                    </div> <!-- col-6 end-->
            </div> <!--END row5 -->
            <!---------------- BREAK -------------->
            <div class="row" style=" border-bottom: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; border-left: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-6 mt-2">
                        End Date : 
                    </div>
                    <div class="col-6">
                        <div class="input-group mb-3">
                            <input type="date" required  name="name_endDate" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                        </div>
                    </div> <!-- col-6 end-->
            </div> <!--END row6 -->
            <!---------------- BREAK -------------->
            <div class="row" style=" border-bottom: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; border-left: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-6 mt-2">
                        Cut Off Number : 
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <select id="cutoff" required name="name_cutoffNum" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                                <option selected value='1'>1</option>
                            </select>
                        </div> <!-- Second mb-3 end-->
                    </div> <!-- col-6 end-->
            </div> <!--END row3 -->
            <!---------------- BREAK -------------->
            <div class="row" style=" border-bottom: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; border-left: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-6 mt-2">
                        Department : 
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                        <?php
                            include 'config.php';

                            $sqls = "SELECT * FROM dept_tb";

                            $results = mysqli_query($conn, $sqls);

                            $option = "";
                            while ($rows = mysqli_fetch_assoc($results)) {
                                $option .= "<option value='" . $rows['col_ID'] . "'>" . $rows['col_deptname'] . "</option> ";
                            }
                        ?>
                            <select name="department" id="departmentDropdown" class="form-select">
                            <option value selected disabled>Select Department</option>
                            <option value='All'>All</option>
                            <?php echo $option ?>
                        </select>

                        </div>  <!--mb-3 end--->
                    </div> <!-- col-6 end-->
            </div> <!--END row3 -->
            
             <!-- <p>Selected Department ID: <span id="selectedDepartment"><?php echo @$selectedDepartment ?></span></p> -->   

            <div class="row" style=" border-bottom: 1px solid #D1D1D1; border-right: 1px solid #D1D1D1; border-left: 1px solid #D1D1D1; padding-top: 10px;">
                    <div class="col-6 mt-2">
                        Employees : 
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                           
                        <div id="employeeDropdown">
                            <select class="approver-dd dd-hide" name="name_empId[]" id="multi_option" multiple placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;">
                            </select>
                        </div>

                        </div>  <!--mb-3 end--->
                    </div> <!-- col-6 end-->
            </div> <!--END row3 -->
            <!---------------- BREAK -------------->

        </div> <!--END Modal-Body -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save</button>
        </div>
    </form>
    </div> <!--END Modal-Content -->
  </div><!--END Modal-Dialog -->
</div> <!--END Modal -->


<div class="container mt-5">
    <div class="card">
        <div class="card-body" style="background-color: #fff">

        <h3 class="mt-2">Cutoff List</h3>
        <button class="btn_Create mt-3" data-bs-toggle="modal" data-bs-target="#modal_create" style="margin-left: 15px;">
            Create New
        </button>
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

        <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="" aria-current="page" data-bs-toggle="tab" href="#Standard" style="text-decoration: none; color: black;" > <h4>Standard</h4> </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#Allowance">----</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#Loan">----</a>
                    </li> -->
                </ul>

    <div class="tab-content">
        <form action="gnrate_payroll_prac.php" method="post">
        <div class="tab-pane" id="Standard">
            <div class="scroll" style="max-height:500px; overflow: scroll;">
                <?php 
                include 'config.php';
                // Fetch data from the MySQL table
                $sql = "SELECT * FROM cutoff_tb WHERE col_type = 'Standard'";
                $result = mysqli_query($conn, $sql);
                // Display data in div elements
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="stndrd_div">';
                            echo '<div class="head">';
                                echo '<h3 class="ml-3 mt-4">'. $row["col_month"] .'</h3>';
                                echo '<h3 class="ml-3 mt-4">'. $row["col_year"] .'</h3>';
                                echo '<p class="tag">Preview</p>';
                            echo '</div>';
                            echo '<p class="type ml-3 mt-3">'. $row["col_type"] .'</p>';
                            echo '<div class="div">';
                                echo '<div class="head">';
                                    echo '<p class="c1 ml-3 mt-4">Cutoff No. :</p>';
                                    echo '<p class="c1 ml-2 mt-4">'. $row["col_cutOffNum"] .'</p>';
                                echo '</div>';
                                echo '<div class="head">';
                                    echo '<p class="c1 ml-3">Period :</p>';
                                    echo '<p class="c1 ml-2">'. $row["col_startDate"] . ' to '.'</p>';
                                    echo '<p class="c1 ml-2">'. $row["col_endDate"] .'</p>';
                                echo '</div>';
                                echo '<div class="head">';
                                    echo '<p class="c1 ml-3">Frequency :</p>';
                                    echo '<p class="c1 ml-2">'. $row["col_frequency"] .'</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '<div class="foot">';
                                echo '<button type="submit" name="name_btnview" value="'. $row["col_ID"] .'" class="btnq">[ View ]</button>';
                                echo '<button type="button" class="btnq btn-delete" data-bs-toggle="modal" data-bs-target="#staticBackdrop" data-id="' . $row["col_ID"] . '">[ Delete ]</button>';
                                echo '<button type="button" class="btnq btn-addEmp" data-bs-toggle="modal" data-bs-target="#modal_addEMp" data-id1="' . $row["col_ID"] . '">[ Add Employee ]</button>';
                            echo '</div>';
                        echo '</div>';
                    }
                } else {
                    // No data found
                }
                // Close connection
                mysqli_close($conn);  
                ?>
            </div>
        </div>
</form>



<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="actions/Payroll/delete.php" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="name_CutoffID" id="modal-input">
                    Are you sure you want to delete this cutoff?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="btn_delete_modal"  class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="modal_addEMp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Employee</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
     
            <div class="mb-3">
                <h4>Department: </h4>
                <?php
                            include 'config.php';

                            $sqls = "SELECT * FROM dept_tb";

                            $results = mysqli_query($conn, $sqls);

                            $option = "";
                            while ($rows = mysqli_fetch_assoc($results)) {
                                $option .= "<option value='" . $rows['col_ID'] . "'>" . $rows['col_deptname'] . "</option> ";
                            }
                        ?>
                        <select name="department" id="departmentDropdowns" class="form-select">
                            <option value selected disabled>Select Department</option>
                            <option value='All'>All</option>
                            <?php echo $option ?>
                        </select>
            </div>
                <!-- <p>Selected Department ID: <span id="selectedDepartment"><?php echo @$selectedDepartment ?></span></p> -->   

            <div class="mb-3">
                <h4>Select Employee: </h4>
                <form action="actions/Payroll/addEmp.php" method="post">
                <input type="hidden" name="name_AddEMp_CutoffID" id="ID_AddEMp_CutoffID">
                <div id="employeeDropdowns">
                            <select class="approver-dd dd-hide" name="name_empId[]" id="multi_options" multiple placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;">
                            </select>
                        </div>
                 
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="btn_addEmp_modal" class="btn btn-primary">Add</button>
      </div>
      </form>
    </div>
  </div>
</div>

                    <!-- <div class="tab-pane" id= "Allowance">
                        Allowance
                    </div>
                    <div class="tab-pane" id= "Loan">
                            Loan
                    </div> -->

                    

                    
                </div>
                
        </div>  <!-- End Card-Body -->
    </div> <!-- End Card -->
 </div> <!-- End Container -->


 <script>
      $(document).ready(function() {
    $('#departmentDropdown').change(function() {
        var selectedValue = $(this).val();
        
        // Send selectedValue to a PHP script via AJAX
        $.ajax({
            type: 'POST',
            url: 'update_selected_department.php', // Create this PHP file to handle the AJAX request
            data: { department: selectedValue },
            success: function(response) {
                $('#selectedDepartment').text(response); // Update the value in the <p> tag

                // Fetch employee options based on the selected department
                $.ajax({
                    type: 'POST',
                    url: 'create_cutoff_getEmp.php', // Create this PHP file to generate employee options
                    data: { department: response },
                    success: function(employeeOptions) {
                        // Update the employee dropdown with new options
                        $('#employeeDropdown').html(employeeOptions);
                        console.log('Employee options updated successfully.');

                        // Collect selected employee IDs
                        var selectedEmployeeIDs = $('#multi_option').val();
                        console.log('Selected Employee IDs:', selectedEmployeeIDs);

                        // Now submit the form with the selected employee IDs
                      
                    }
                });
            }
        });
    });
});
    </script>



<script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_option' 
	});
</script>

<script>
      $(document).ready(function() {
    $('#departmentDropdowns').change(function() {
        var selectedValue = $(this).val();
        
        // Send selectedValue to a PHP script via AJAX
        $.ajax({
            type: 'POST',
            url: 'update_selected_department.php', // Create this PHP file to handle the AJAX request
            data: { department: selectedValue },
            success: function(response) {
                $('#selectedDepartment').text(response); // Update the value in the <p> tag

                // Fetch employee options based on the selected department
                $.ajax({
                    type: 'POST',
                    url: 'get_employee_options.php', // Create this PHP file to generate employee options
                    data: { department: response },
                    success: function(employeeOptions) {
                        // Update the employee dropdown with new options
                        $('#employeeDropdowns').html(employeeOptions);
                        console.log('Employee options updated successfully.');

                        // Collect selected employee IDs
                        var selectedEmployeeIDs = $('#multi_options').val();
                        console.log('Selected Employee IDs:', selectedEmployeeIDs);

                        // Now submit the form with the selected employee IDs
                      
                    }
                });
            }
        });
    });
});
    </script>



<script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_options' 
	});
</script>



 <script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
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
      $('#scheduleform-container').addClass('move-content');
    } else {
      $('#scheduleform-container').removeClass('move-content');

      // Add class for transition
      $('#scheduleform-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#scheduleform-container').removeClass('move-content-transition');
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


<!-- Para sa pag kuha sa ID ng cuttoff para maka delete at add ng employee sa cutofff -->
<script>
    $(document).ready(function() {
  $('.btn-delete').click(function() {
      var id = $(this).data('id');
      $('#modal-input').val(id);
  });
});

$(document).ready(function() {
  $('.btn-addEmp').click(function() {
      var id = $(this).data('id1');
      $('#ID_AddEMp_CutoffID').val(id);
  });
});
</script>
<!-- Para sa pag kuha sa ID ng cuttoff para maka delete at add ng employee sa cutofff  END--> 
  
    
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
</body>
<script src="js/cutoff.js"></script>
</html>