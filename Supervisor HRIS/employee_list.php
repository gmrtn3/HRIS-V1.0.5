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
    <link rel="stylesheet" href="css/employee_list.css">

    <title>Employee List</title>
</head>
<body>
<header>
     <?php
         include 'header.php';
     ?>
</header>

<style>
    html{
        overflow: hidden;
    }

    .table{
         width: 100% !important;
         
    }

    .content-wrapper{
         width: 85%
    }
    
    .pagination{
        margin-right: 55px !important;

    }
 
    .table{
         width: 99.6%;
    }

    
    .content-wrapper{
         width: 85%
    }
</style>




<div class="main-panel mt-5">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">

                                            <div class="row">
                                                    <div class="col-6">
                                                        <h2>Employee List</h2>
                                                    </div>
                                                </div> <!--ROW END-->


                                        <div class="row">
                                            <div class="col-12 mt-3">
                                                <div class="table-responsive">
                                                    <table id="order-listing" class="table">
                                                        <thead>
                                                            <tr>
                                                                <th style="display:none;">ID</th>
                                                                <th>Employee ID</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Contact</th>
                                                                <th>Department</th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                        include 'config.php';
                                                        $aprrover_ID = $_SESSION['empid'];
                                                        $query = "SELECT employee_tb.id,
                                                        CONCAT(
                                                            employee_tb.`fname`,
                                                            ' ',
                                                            employee_tb.`lname`
                                                        ) AS `full_name`,
                                                        employee_tb.empid,
                                                        employee_tb.address,
                                                        employee_tb.contact,
                                                        employee_tb.department_name,
                                                        employee_tb.email,
                                                        dept_tb.col_deptname
                                                        FROM employee_tb 
                                                        INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                                        INNER JOIN approver_tb ON approver_tb.empid = employee_tb.empid
                                                        WHERE
                                                            approver_tb.approver_empid = $aprrover_ID";
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
                                                                <td style="display:none;"><?php echo['id']?></td>
                                                                <td><?php  $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                                                $empid = $row['empid'];
                                                                if (!empty($cmpny_code)) {
                                                                    echo $cmpny_code . " - " . $empid;
                                                                } else {
                                                                    echo $empid;
                                                                }?></td>
                                                                <td><?php echo $row['full_name']?></td>
                                                                <td><?php echo $row['email']?></td>
                                                                <td><?php echo $row['contact']?></td>
                                                                <td><?php echo $row['col_deptname']?></td>
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