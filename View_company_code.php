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
    <link rel="stylesheet" href="css/departmentEmployeeResponsive.css">

    <title>Employee Information</title>
</head>
<style>
    
    .pagination{
        margin-right: 80px !important;
        
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

    .card{
      box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);
      width: 1500px;
      height: 780px;
      margin-left: 3%;

    }

    h2{
      font-size: 23px;
      font-weight: bold;
    }
    
    #order-listing_next{
        margin-right: 30px !important;
        margin-bottom: -7px !important;

    }
</style>


<body>
   
        <header>
            <?php include 'header.php';
            ?>
</header>
    
<div class="container mt-5 xxl" style="position:absolute; width: 100%; right: 288px; bottom: 60px; height: 80%; border-radius: 25px;">
    <div class="">
        <div class="card border-light">
            
<a href=""></a>
    

            <?php

                    if(isset($_POST['view_data'])){

                        $emp_deptID = $_POST['id'];
                        $emp_dept_name = $_POST['company_code'];
                        
                        
                            echo "
                            <div class=''>
                            <div class='row' style='margin-top: 20px; margin-left:5px; margin-right:5px;'>
                                <div class='col-6'>

                                    <h2 class=''>";
                                    echo $emp_dept_name;
                                    echo"
                                    </h2>
                                </div> <!--first col-6 end-->
                                <div class='col-6 text-end'>
                                    <a href='companyCode' class='btn btn-outline-danger'>Go back</a>
                                </div> <!--sec col-6 end-->
                            </div> <!--row end-->

                            </div> <!-- card-header end-->
                            <div class='card-body'>
                                <div class='table my-3 table-responsive '>
                                    <table id='order-listing' class='table table-sortable table-hover caption-top'>
                                        <thead style='color: black;
                                                    font-size: 19px;
                                                    background-color: #ececec;'>
                                            <tr> 
                                                    <th> Employee ID </th>  
                                                    <th> Employee FullName  </th>
                                                    <th> Employee Code </th>                   
                                            </tr>
                                        </thead>
                                        <tbody>";
                                                include 'config.php';

                                                // Query the department table to retrieve department names
                                                $dept_query = "SELECT company_code_tb.id,
                                                                    employee_tb.empid,
                                                                    CONCAT(
                                                                        employee_tb.`fname`,
                                                                        ' ',
                                                                        employee_tb.`lname`
                                                                        ) AS `full_name`,
                                                                        company_code_tb.company_code
                                                                FROM employee_tb 
                                                                INNER JOIN company_code_tb
                                                                ON employee_tb.company_code = company_code_tb.id
                                                                WHERE employee_tb.company_code = '$emp_deptID'";

                                                $result = mysqli_query($conn, $dept_query);

                                                // Generate the HTML table header

                                                // Loop over the departments and count the employees
                                                while ($row = mysqli_fetch_array($result)) {
                                                    //$fullname = $row['fname'] . ' ' . $row['lname'];

                                                    // Generate the HTML table row
                                                    echo "<tr>
                                                        <td>" . $row['empid'] . "</td>
                                                        <td>" . $row['full_name']. "</td>
                                                        <td>" . $row['company_code'] . "</td>

                                                        </tr>";
                                                }

                                                // Close the HTML table

                                                // Close the database connection
                                                mysqli_close($conn);
                            echo "          
                                        </tbody>   
                                    </table>        
                                </div> <!--table my-3 end-->  
                            </div> <!--card Body END-->
                                ";
                    }
                ?>
        </div> <!-- card end-->
    </div> <!-- jumbotron end-->
</div> <!-- container end-->


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