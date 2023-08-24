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

    <!-- Para sa datatables END -->

    <link rel="stylesheet" href="css/dept.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/departmentResponsive.css">

    <title>Company Code</title>
</head>
<body>
    
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
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
    table {
                display: block;
                overflow-x: hidden;
                white-space: nowrap;
                max-height: 450px;
                height: 450px;
                
                
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
</style>

<header>
<?php include 'header.php';
?>
</header>

    <!-- Modal -->
<div class="modal fade" id="add_deptMDL" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action = "Data Controller/Company Code/insertcode.php" method="POST">
        <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> <!-- Modal header END -->
        <div class="modal-body">
                
                    <div class="mb-3">
                        <label for="adddept" class="col-form-label fs-6">Name :</label>
                        
                        <div class="input-group mb-3">
                            <input type="text" name="company_code" class="form-control" id="id_Department" required >
                            <span class="input-group-text" id="basic-addon2">Company Code</span>
                        </div>
                    </div>
                
        </div> <!-- Modal Body END -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div> <!-- Modal footer END -->
        </div> <!-- Modal content END -->
    </form>
  </div> <!-- Modal DIALOg END -->
</div> <!-- Modal END -->


<!-------------------------------------------------------------------DELETE DEPT INFO MODAL-------------------------------------------------------->
<div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Company Code/delete.php" method="POST">
      <div class="modal-body">

        <input type="hidden" name="id" id="delete_id">
        <input type="hidden" name="company_code" id="designate">

        <h4>Do you want to delete?</h4>

      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
        <button type="submit" name="delete_data" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>


    </div>
  </div>
</div>
<!---------------------------------------------------END OF DELETE DEPT INFO MODAL------------------------------------------------------------------->


<div class="container mt-3">
    <div class="">

        <div class="card border-light" style=" box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17); position:absolute; right: 57px; bottom: 81px;  width: 1500px; height: 780px;" >
           
            <div class="card-body">

            <div class="row" style="">
                    <div class="col-6">
                        <h2 class="" style="font-size: 23px; font-weight: bold">Company Code</h2>
                    </div>
                    <div class="col-6 mt-1 text-end">
                        <!-- Button trigger modal -->
                        <button class="btn_dept" data-bs-toggle="modal" data-bs-target="#add_deptMDL" style="background-color: black; height:50px; border-radius: 10px; margin-top: -10px;">
                            Add Company Code
                        </button>
                    </div>
            </div> <!-- Row END -->

                   <!-- ------------------para sa message na sucessful START -------------------->
                   <?php

                        if (isset($_GET['msg'])) {
                            $msg = $_GET['msg'];
                            echo '<div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
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

                    
                                      
                        <form action="View_company_code" method="post">
                                <input id="id_deptname_tb" name="id" type="text" style="display: none;">
                                <input id="id_textdept" name="company_code" type="text" style="display: none;">
                                <div class="table-responsive mt-5" style=" overflow: hidden; width: 100%">
                        <table id="order-listing" class="table" style="width: 100%; " >
                            <thead style="background-color: #ececec" >

                                <tr> 
                                        <th style= 'display: none;'> ID  </th>  
                                        <th> Company Code </th>
                                        <th>Total Employee</th>
                                        <th>Action</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include 'config.php';

                                    // Query the department table to retrieve department names
                                    $dept_query = "SELECT id,company_code FROM company_code_tb";
                                    $dept_result = mysqli_query($conn, $dept_query);

                                    // Generate the HTML table header


                                    // Loop over the departments and count the employees
                                    while ($dept_row = mysqli_fetch_assoc($dept_result)) {
                                        $dept_id = $dept_row['id'];
                                        $dept_name = $dept_row['company_code'];
                                        $emp_query = "SELECT COUNT(*) as count FROM employee_tb WHERE company_code = '$dept_id'";
                                        $emp_result = mysqli_query($conn, $emp_query);
                                        $emp_row = mysqli_fetch_assoc($emp_result);
                                        $emp_count = $emp_row['count'];

                                        // Generate the HTML table row
                                        echo "<tr>
                                                <td style= 'display: none;'>$dept_id</td>
                                                <td>$dept_name</td>
                                                <td>$emp_count</td>
                                                <td>
                                                    <button type='submit' name='view_data' class= 'border-0 viewbtn' title = 'View' style=' background: transparent;'>
                                                    <i class='fa-solid fa-eye fs-5 me-3'></i>
                                                    </button>
                                                    <button type='button' class= 'border-0 editbtn' title = 'Edit' data-bs-toggle='modal' data-bs-target='#update_deptMDL' style=' background: transparent;'>
                                                    <i class='fa-solid fa-pen-to-square fs-5 me-3' title='edit'></i>
                                                    </button>
                                                    <button type='button' class= 'border-0 deletebtn' title = 'Delete' data-bs-toggle='modal' data-bs-target='#deletemodal' style=' background: transparent;'>
                                                    <i class='fa-solid fa-trash fs-5 me-3 title='delete'></i>
                                                        
                                                    </button> 
                                                </td>
                                            </tr>";
                                    }

                                    // Close the HTML table

                                    // Close the database connection
                                    mysqli_close($conn);
                                ?>
                            </tbody>
                        </form>   
                    </table>        
                </div> <!--table my-3 end-->   
                    <!-- Modal UPDATE DATA -->
                    <div class="modal fade" id="update_deptMDL" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action = "actions/Company Code/update.php" method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Code</h1>
                                        <input type="text" id="id_colId" name="id" style= "display: none;">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div> <!-- Modal header END -->
                                    <div class="modal-body">
                                    
                                        <div class="mb-3">
                                            <label for="adddept" class="col-form-label fs-6">Company Code :</label>
                                            
                                            <div class="input-group mb-3">
                                                <input type="text" id="id_Editdeptname" name="company_code" class="form-control" id="id_Department" required>
                                            </div>
                                        </div>
                                    
                                    </div> <!-- Modal Body END -->
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="updatedata" class="btn btn-primary">Save Changes</button>
                                    </div> <!-- Modal footer END -->
                                </div> <!-- Modal content END -->
                            </form>
                        </div> <!-- Modal DIALOg END -->
                    </div> <!-- Modal END -->



                    
        </div>  <!-- CARD END -->

    </div> <!-- Jumbptron End -->
</div> <!-- Container End -->

    






<!---------------------------------------Script sa pagpop-up ng modal para madelete--------------------------------------------->          
<script>
            $(document).ready(function (){
                $('.deletebtn').on('click' , function(){
                    $('#deletemodal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#delete_id').val(data[0]);
                    $('#designate').val(data[2]);
                    

                });
            });
        </script>
<!---------------------------------------End Script sa pagpop-up ng modal para madelete--------------------------------------------->

    <script> //FOR UPDATE TRANSFER MODAL 
        $(document).ready(function(){
                                $('.editbtn').on('click', function(){
                                    $('#update_deptMDL').modal('show');
                                    $tr = $(this).closest('tr');

                                    var data = $tr.children("td").map(function () {
                                        return $(this).text();
                                    }).get();

                                    console.log(data);
                                    //id_colId
                                    $('#id_colId').val(data[0]);
                                    $('#id_Editdeptname').val(data[1]);
                                });
                            });
            //FOR UPDATE TRANSFER MODAL END
    </script> 

    <script> //FOR VIEW TRANSFER MODAL 
            $(document).ready(function(){
                                    $('.viewbtn').on('click', function(){
                                        $('#IDview_deptMDL').modal('show');
                                        $tr = $(this).closest('tr');

                                        var data = $tr.children("td").map(function () {
                                            return $(this).text();
                                        }).get();

                                        console.log(data);
                                        //id_colId
                                        $('#id_textdept').val(data[1]);//deptname
                                        $('#id_deptname_tb').val(data[0]);//deptID
                                    });
                                });
            //FOR VIEW TRANSFER MODAL END
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


    

  
    <script src="js/dept.js"></script>
</body>

</html>