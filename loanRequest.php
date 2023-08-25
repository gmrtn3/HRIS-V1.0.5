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
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Make sure the status and ID are provided in the form submission
        if (isset($_POST['status']) && isset($_POST['id'])) {
            $status = $_POST['status'];
            $id = $_POST['id'];
    
            // Assuming you have a database connection established already
            $conn = new mysqli('localhost', 'root', '', 'hris_db');
            if ($conn->connect_error) {
                die('Connection Failed: ' . $conn->connect_error);
            }
    
            // Prepare and execute the SQL update statement
            $stmt = $conn->prepare("UPDATE payroll_loan_tb SET `status` = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();
    
            // Close the statement and database connection
            $stmt->close();
            $conn->close();
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
    <title>HRIS | Employee List</title>
</head>
<body>

    <header>
        <?php include("header.php")?>
    </header>

    <style>
   

    .pagination{
        margin-right: 73px !important;
        
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
        border-color: #000;
    }

    
    
    #order-listing_next{
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
    table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                max-height: 450px;
                height: 450px;
                
                
            }
            thead{
                display: table;
                width: 100%;
            }
            tbody {
                display: table;
                width: 100%;
            }

            #table-responsiveness{
    overflow-y: hidden !important;
   }

   table thead th:nth-child(1){
    width: 15%;
   }

   table tbody tr td:nth-child(1){
    width: 15%;
   }


   table thead th:nth-child(2){
    width: 15%;
   }

   table tbody tr td:nth-child(2){
    width: 15%;
   }


   table thead th:nth-child(3){
    width: 10%;
   }

   table tbody tr td:nth-child(3){
    width: 10%;
   }

   table thead th:nth-child(4){
    width: 13%;
   }

   table tbody tr td:nth-child(4){
    width: 13%;
   }

   table thead th:nth-child(5){
    width: 13%;
   }

   table tbody tr td:nth-child(5){
    width: 13%;
   }

   .approveBtn{
    border: none; background-color: #70C170; height: 2.5em; width: 6.5em; color: white; border-radius: 0.3em
   }

   .rejectBtn{
    border: none; background-color: #ff4500; height: 2.5em; width: 6.5em; color: white; border-radius: 0.3em
   }
           
</style>

    <div class="gen-payslip">
        <div class="loanreq-container">
            <div class="loanreq-title">
                <h1>Payroll Loans Request</h1>
                <div></div>
            </div>
            <div class="loanreq-input">
                <!-- <button><a style="color:white; text-decoration:none; outline:none;" href="loanRequestForm.php">Create New</a></button> -->
                <a href="loanRequestForm.php" style="color:white; text-decoration:none; outline:none; background-color:#000; height: 3.3em; width: 7em; border-radius: 0.4em" class="d-flex justify-content-center align-items-center" > Create New</a>
                <!-- <input class="employeeList-search" type="text" placeholder="&#xF002; Search" style="font-family:Arial, FontAwesome" id="search"/> -->
            </div>

            <div class="table-responsive" id="table-responsiveness" style="width: 95%; height: 100%; margin:auto; margin-top: 30px; overflow-y: hidden;">
            <table id="order-listing" class="table table-responsive" style="width: 100%;">
                <thead style="background-color: #f4f4f4;">
                    <th>Name</th>
                    <th style="" >Loan Type</th>
                    <th>Loan Date</th>
                    <th>Date Files</th>
                    <th>Payable Amount</th>
                    <th>Amortization</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php 
                       $db = mysqli_connect("localhost", "root", "" , "hris_db");
                       $result = $db->query("SELECT payroll_loan_tb.id,
                                        payroll_loan_tb.loan_type,
                                        payroll_loan_tb.year,
                                        payroll_loan_tb.month,
                                        payroll_loan_tb.cutoff_no,
                                        payroll_loan_tb.remarks,
                                        payroll_loan_tb.loan_date,
                                        payroll_loan_tb.payable_amount,
                                        payroll_loan_tb.amortization,
                                        payroll_loan_tb.applied_cutoff,
                                        payroll_loan_tb.timestamp,
                                        payroll_loan_tb.status,
                                        CONCAT(
                                             employee_tb.`fname`,
                                             ' ',
                                             employee_tb.`lname`   
                                            ) AS `full_name` 
                                FROM payroll_loan_tb
                                INNER JOIN employee_tb ON employee_tb.empid = payroll_loan_tb.empid
                                ORDER BY loan_date ASC");
                        
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){ 
                               
                    ?>
                    <tr>  
                        <td style="font-weight: 400"><?php echo $row['full_name']?></td> 
                        <td style="font-weight: 400"><?php echo $row['loan_type']?></td>
                        <td style="font-weight: 400"><?php echo $row['loan_date']?></td>
                        <td style="font-weight: 400"><?php echo $row['timestamp']?></td>
                        <td style="font-weight: 400"><?php echo $row['payable_amount']?></td>
                        <td style="font-weight: 400"><?php echo $row['amortization']?></td>
                        <td style="font-weight: 400"><?php echo $row['payable_amount']?></td>
                        <td style="font-weight: 400; <?php echo ($row['status'] === 'Pending') ? 'color: red;' : 'color: green;' ?> <?php echo ($row['status'] === 'Rejected') ? 'color: orange;' : 'color: green;' ?>">
                            <?php echo $row['status'] ?>
                        </td>
                        <form method="POST" >
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <td>
                                <?php if ($row['status'] === 'Approved' || $row['status'] === 'Rejected' || $row['status'] === 'Cancelled'): ?>
                                    <button class="approveBtn" id="approveBtn" name="status" value="Approved" style="display: none;" disabled>
                                            Approve
                                          </button>
                                <button class="rejectBtn" id="rejectBtn" name="status" value="Rejected" style="display: none;" disabled>
                                            Reject
                                          </button>
                                          <?php else: ?>
                                <button class="approveBtn" id="approveBtn" name="status" value="Approved" >Approve</button>
                                <button class="rejectBtn" id="rejectBtn" name="status" value="Rejected" >Reject</button>
                                <?php endif; ?>
                            </td>
                        </form>
                    </tr>
                    <?php 
                            }
                        } else{
                            ?>
                          
                          <?php  
                        }     
                        ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

  
<script>
   function handleButtonClick(button){
    document.getElementById("approveBtn")
   }
</script>





    
<script type="text/javascript">
        $(document).ready(function(){
            $('#search').keyup(function(){
                search_table($(this).val());
            });

            function search_table(value){
                $('#myTable tr').each(function(){
                    var found = 'false';
                    $(this).each(function(){
                        if($(this).text().toLowerCase().indexOf(value.toLowerCase())>= 0){
                            found = 'true';
                        }
                    });
                    if(found == 'true'){
                        $(this).show();
                    }else{
                        $(this).hide();
                    }
                });
            }
        });

</script> 

<script>
// sched form modal

let Modal = document.getElementById('schedules-modal-update');

//get open modal
let modalBtn = document.getElementById('sched-update');

//get close button modal
let closeModal = document.getElementsByClassName('sched-update-close')[0];

//event listener
modalBtn.addEventListener('click', openModal);
closeModal.addEventListener('click', exitModal);
window.addEventListener('click', clickOutside);

//functions
function openModal(){
    Modal.style.display ='block';
}

function exitModal(){
    Modal.style.display ='none';
}

function clickOutside(e){
    if(e.target == Modal){
        Modal.style.display ='none';    
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
