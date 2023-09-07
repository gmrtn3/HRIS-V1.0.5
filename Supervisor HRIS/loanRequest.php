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

     .pending {
        color: red;
    }

    .approved {
        color: green;
    }
           
           .pagination .page-item.active .page-link, .jsgrid .jsgrid-pager .page-item.active .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-page .page-link, .pagination .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item.active .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button a, .pagination .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item.active .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-page a, .pagination .page-item:hover .page-link, .jsgrid .jsgrid-pager .page-item:hover .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:hover .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-page:hover .page-link, .pagination .page-item:hover .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item:hover .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item:hover a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:hover a, .pagination .page-item:hover .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item:hover .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item:hover a, .jsgrid .jsgrid-pager .jsgrid-pager-page:hover a, .pagination .page-item:focus .page-link, .jsgrid .jsgrid-pager .page-item:focus .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:focus .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-page:focus .page-link, .pagination .page-item:focus .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item:focus .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item:focus a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:focus a, .pagination .page-item:focus .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item:focus .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item:focus a, .jsgrid .jsgrid-pager .jsgrid-pager-page:focus a, .pagination .page-item:active .page-link, .jsgrid .jsgrid-pager .page-item:active .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:active .page-link, .jsgrid .jsgrid-pager .jsgrid-pager-page:active .page-link, .pagination .page-item:active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .page-item:active .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item:active a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button:active a, .pagination .page-item:active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .page-item:active .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item:active a, .jsgrid .jsgrid-pager .jsgrid-pager-page:active a {
   background-color: #000 !important;
   color: white !important;
}
</style>

    <div class="gen-payslip">
        <div class="loanreq-container">
            <div class="loanreq-title">
                <h1>Payroll Loans Request</h1>
                
                <div></div>
            </div>
            <div class="loanreq-input">
                <a href="loanRequestForm" style=" height: 3em; display:flex; justify-content: center; align-items: center; padding: 1em; text-decoration: none; color: white; background-color:blue" >Create New</a>
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

                        $empid = $_SESSION['empid'];
                        // echo $empid;
                       include 'config.php';
                       $result = $conn->query("SELECT payroll_loan_tb.id,
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
                                WHERE payroll_loan_tb.empid = '$empid'
                                ORDER BY payroll_loan_tb.loan_date ASC
                                ");
                                
                        
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
                        <td style="font-weight: 400; <?php echo ($row['status'] === 'Pending') ? 'color: red;' : 'color: green;' ?>">
    <?php echo $row['status'] ?>
</td>
                        <td style="font-weight: 400; outline:none;"><button style="border: none; background-color:inherit; outline:none;"><a href="editLoanRequestForm.php?id=<?php echo $row['id']?>" style="text-decoration:none;">Edit</a></button></td>
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
