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
include ("config.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <link rel="stylesheet" href="css/branch.css"/>
    <link rel="stylesheet" href="css/branchResponsive.css"/>

    <title>Branch</title>

</head>
<body>
<header>
<?php
include 'header.php';
?>
</header>

<style>
    html{
      overflow: hidden !important;
    }
    body{ 
      overflow: hidden !important;
      background-color: #f4f4f4 !important;
    }

    .card-body{
      box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);
      border-radius: 25px;
    }

    .content-wrapper{
          width: 80%;
    }

    .table {
         width: 99.7%;
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

    h2{
      font-size: 23px;
      font-weight: bold;
    }
    
    #order-listing_next{
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
    
</style>


<!------------------------------------------------------ADD NEW BRANCH MODAL-------------------------------------------------------->
<div class="modal fade" id="addnew_btn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Branch</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="Data Controller/Branch/insert.php" method="POST">
         <div class="modal-body">
            <div class="row">
                <div class="col-6">
                    <label for="branch_n" class="form-label">Branch Name</label>
                    <input type="text" name="branch_name" class="form-control" id="start_date" required>
                </div>
                <div class="col-6">
                    <label for="branch_a" class="form-label">Branch Address</label>
                    <input type="text" name="branch_address" class="form-control" id="end_date" required>
                </div>
            </div>

             <div class="row mt-2">
                <div class="col-6">
                     <label for="zip" class="form-label">Zip Code:</label>
                     <input type="number" name="zip_code" class="form-control" maxlength="4" id="zip_code_id" required >
                </div>
             <div class="col-6">
                     <label for="email" class="form-label">Email:</label>
                     <input type="email" name="email" class="form-control" id="email_id" required>
             </div>
                </div>

                  <div class="mb-3 mt-2">
                     <label for="tele_phone" class="form-label">Telephone:</label>
                     
                     <input type="text" id="telephone_id" name="telephone" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 10) this.value = this.value.slice(0, 10);" placeholder="">
                  </div>


      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="add_data" class="btn btn-primary">Add</button>
      </div>
      </form>


    </div>
  </div>
</div>
<!-------------------------------------------------END OF ADD NEW BRANCH MODAL-------------------------------------------------------->

<!-------------------------------------------------------------------EDIT BRANCH INFO MODAL-------------------------------------------------------->
<div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Position</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Branch Action/edit.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="update_id" id="update_id">
         <div class="row">
                <div class="col-6">
                    <label for="branch_n" class="form-label">Branch Name</label>
                    <input type="text" name="branch_name" class="form-control" id="update_branch_name" required>
                </div>
                <div class="col-6">
                    <label for="branch_a" class="form-label">Branch Address</label>
                    <input type="text" name="branch_address" class="form-control" id="update_branch_address" required>
                </div>
            </div>

             <div class="row mt-2">
                <div class="col-6">
                     <label for="zip" class="form-label">Zip Code:</label>
                     <input type="number" name="zip_code" class="form-control" id="update_branch_zip" required maxlength="4">
                </div>
             <div class="col-6">
                     <label for="email" class="form-label">Email:</label>
                     <input type="email" name="email" class="form-control" id="update_branch_email" required>
             </div>
                </div>

                  <div class="mb-3 mt-2">
                     <label for="tele_phone" class="form-label">Telephone:</label>
                     <input type="number" name="telephone" class="form-control" id="update_branch_telephone" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 10) this.value = this.value.slice(0, 10);">
                  </div>

      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="update_data" class="btn btn-primary">Update</button>
      </div>
      </form>


    </div>
  </div>
</div>
<!---------------------------------------------------END OF EDIT BRANCH INFO MODAL------------------------------------------------------------------->

<!-------------------------------------------------------------------DELETE BRANCH INFO MODAL-------------------------------------------------------->
<div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Branch Action/delete.php" method="POST">
      <div class="modal-body">

        <input type="hidden" name="delete_id" id="delete_id">
        <input type="hidden" name="designation" id="designate">

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
<!---------------------------------------------------END OF DELETE BRANCH INFO MODAL------------------------------------------------------------------->


<div class="main-panel" style="width: 100%; height: 100vh; position: absolute; top: 53px; margin-left: 16.7%;">
        <div class="content-wrapper mt-5" >
          <div class="card" style="width:1500px; height: 780px;">
            <div class="card-body">
            <div class="row">
                        <div class="col-6">
                            <h2>Branch</h2>
                        </div>
                        <div class="col-6 mt-2 text-end">
                        <!-- Button trigger modal -->
                        <button type="button" class="add_new_btn" data-bs-toggle="modal" data-bs-target="#addnew_btn" style="background-color: black; padding: 10px; height: 45px; width: 120px; border-radius: 10px;">
                        Add New
                        </button>
                        </div>
                    </div> <!--ROW END-->


<!-------------------------------------------------------MESSAGE ALERT------------------------------------------------------------------->
        <?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }

        ?>
<!------------------------------------------------------- END NG MESSAGE ALERT------------------------------------------------------------>


<!-------------------------------------------------------ERROR MESSAGE ALERT------------------------------------------------------------------->
<?php
    if (isset($_GET['error'])) {
        $err = $_GET['error'];
        echo '<div id="alert-message" class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        '.$err.'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
?>
<!------------------------------------------------------- END NG ERROR MESSAGE ALERT------------------------------------------------------------>

                        <!-- <div class="mt-3">
                                <label for="Select_emp" class="form-label">Filter by Branch:</label>
                                <?php // Eto ang Filter sa branch
                                    // include 'config.php';  

                                    // // Fetch all values of fname and lname from the database
                                    // $sql = "SELECT branch_name FROM branch_tb";
                                    // $result = mysqli_query($conn, $sql);

                                    // // Generate the dropdown list
                                    // echo "<select class='form-select form-select-m' aria-label='.form-select-sm example' name='name_emp' style='width: 350px;'>";
                                    // while ($row = mysqli_fetch_array($result)) {
                                    //     $branchname = $row['branch_name'];
                                    //     echo "<option value='$branch_name'>$branchname</option>";
                                    // }
                                    // echo "</select>";
                                ?>

                            </div> -->




            <div class="row" >
                <div class="col-12 mt-5">
                    <div class="table-responsive" style="overflow: hidden;">
                      <form action="View_branch.php" method="post">
                        <input type="hidden" id="id_branch_name" name="name_branch">
                        <input type="hidden" id="table_id_branch" name="branch_id">
                        <table id="order-listing" class="table" >
                        <thead style="background-color: #ececec;">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th >Branch Name</th>
                                <th>Branch Address</th>
                                <th>Zip Code</th>
                                <th>Email</th>
                                <th>Telephone</th>
                                <th>Designation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    <tbody>
                           <?php
                                include 'config.php';

                                // Query the department table to retrieve department names
                                $dept_query = "SELECT * FROM branch_tb";
                                $dept_result = mysqli_query($conn, $dept_query);

                                // Generate the HTML table header


                                // Loop over the departments and count the employees
                                while ($dept_row = mysqli_fetch_assoc($dept_result)) {
                                    $branch_id = $dept_row['id'];
                                    $branch_name = $dept_row['branch_name'];
                                    $branch_address = $dept_row['branch_address'];
                                    $branch_zip = $dept_row['zip_code'];
                                    $branch_email = $dept_row['email'];
                                    $branch_telephone = $dept_row['telephone'];
                                    $emp_query = "SELECT COUNT(*) as count FROM employee_tb WHERE empbranch = '$branch_id'";
                                    $emp_result = mysqli_query($conn, $emp_query);
                                    $emp_row = mysqli_fetch_assoc($emp_result);
                                    $emp_count = $emp_row['count'];

                                    // Generate the HTML table row
                                    echo "<tr>
                                            <td style= 'display: none;'>$branch_id</td>
                                            <td style='font-weight: 400'>$branch_name</td>
                                            <td style='font-weight: 400'>$branch_address</td>
                                            <td style='font-weight: 400'>$branch_zip</td>
                                            <td style='font-weight: 400'>$branch_email</td>
                                            <td style='font-weight: 400'>$branch_telephone</td>
                                            <td style='font-weight: 400'>$emp_count</td>
                                            <td>
                                                <button style='background-color: inherit;' type='submit'  name='view_data' class='link-dark editbtn border-0 viewbtn' title = 'View'><i class='fa-solid fa-eye fs-5 me-3'></i></button>
                                                <button style='background-color: inherit;' type='button' class='link-dark editbtn border-0' data-bs-toggle='modal' data-bs-target='#editmodal'><i class='fa-solid fa-pen-to-square fs-5 me-3' title='edit'></i></button> 
                                                <button style='background-color: inherit;' type='button' class='link-dark deletebtn border-0' data-bs-toggle='modal' data-bs-target='#deletemodal'><i class='fa-solid fa-trash fs-5 me-3 title='delete'></i></button> 
                                            </td>
                                        </tr>";
                                }

                                // Close the HTML table

                                // Close the database connection
                                mysqli_close($conn);
                            ?>
        
                         </tbody>
                      </table>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    


<!----------------------------------------------Script sa pagpop-up ng modal para maedit------------------------------------------------------->        
<script>
            $(document).ready(function (){
                $('.editbtn').on('click' , function(){
                    $('#editmodal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#update_id').val(data[0]);
                    $('#update_branch_name').val(data[1]);
                    $('#update_branch_address').val(data[2]);
                    $('#update_branch_zip').val(data[3]);
                    $('#update_branch_email').val(data[4]);
                    $('#update_branch_telephone').val(data[5]);

                    

                });
            });
        </script>
<!----------------------------------------------End ng Script sa pagpop-up ng modal para maedit------------------------------------------------------->

<!---------------------------------------------------Start Script for limiting the number input----------------------------------->
<script>
  $(document).ready(function() {
  $('#zip_code_id').on('input', function() {
    if ($(this).val().length > 4) {
      $(this).val($(this).val().slice(0, 4));
    }
  });
});



$(document).ready(function() {
  $('#update_branch_zip').on('input', function() {
    if ($(this).val().length > 4) {
      $(this).val($(this).val().slice(0, 4));
    }
  });
});


</script>
<!---------------------------------------------------End Script for limiting the number input----------------------------------->


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
                    $('#designate').val(data[6]);
                    

                });
            });
        </script>
<!---------------------------------------End Script sa pagpop-up ng modal para madelete--------------------------------------------->


<script>
            $(document).ready(function(){
                                    $('.viewbtn').on('click', function(){
                                        $('#IDview_deptMDL').modal('show');
                                        $tr = $(this).closest('tr');

                                        var data = $tr.children("td").map(function () {
                                            return $(this).text();
                                        }).get();

                                        console.log(data);
                                        //id_colId
                                        $('#table_id_branch').val(data[0]);
                                        $('#id_branch_name').val(data[1]);
                                    });
                                });
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