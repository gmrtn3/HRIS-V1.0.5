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

    include 'config.php';
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>


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
    <link rel="stylesheet" href="css/pakyawan_generate.css">
    <link rel="stylesheet" href="css/gnratepayrollVIEW.css">
    <title>Pakyawan Payroll</title>
</head>
<body>

<?php
    include 'header.php';
    ?>
    

  
  <?php 
      include 'config.php';
      
      $cutOffID = $_GET['id'];
      $pakyawan_empid = $_GET['pakyawan_empid'];

      // echo $cutOffID;

      $sql = "SELECT * FROM pakyawan_payroll_tb WHERE cutoff_id = $cutOffID AND pakyawan_empid = $pakyawan_empid ";

      $result = mysqli_query($conn, $sql);

      if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);

        $cutoff_id = $row['cutoff_id'];

        $query = "SELECT * FROM pakyawan_cutoff_tb 
                  INNER JOIN pakyawan_payroll_tb ON pakyawan_cutoff_tb.id = pakyawan_payroll_tb.cutoff_id
                  WHERE pakyawan_payroll_tb.pakyawan_empid = $pakyawan_empid";

        $queryResult = mysqli_query($conn, $query);

        $queryRow = mysqli_fetch_assoc($queryResult);

        $pakyawStart_cutoff =  $queryRow['start_date'];
        $pakyawEnd_cutoff =  $queryRow['end_date'];
        // echo $cutoff_id;

          // $cutoff_sql = "SELECT * FROM pakyawan_cutoff_tb WHERE `id` = $cutoff_id";
          // $cutoff_result = mysqli_query($conn, $cutoff_sql);
          // $cutoff_row = mysqli_fetch_assoc($cutoff_result);

          // $cutoff_start_date = $cutoff_row['start_date'];
          // $cutoff_end_date = $cutoff_row['end_date'];



          //calculation
          // $currentDate = date('Y-m-d'); // Get the current date
          // $dayOfWeek = date('N', strtotime($currentDate)); // Get the day of the week (1 = Monday, 7 = Sunday)
          
          // Calculate the start date and end date of the current week
          // $startDate = date('Y-m-d', strtotime('-' . ($dayOfWeek - 1) . ' days', strtotime($currentDate)));
          // $endDate = date('Y-m-d', strtotime('+' . (7 - $dayOfWeek) . ' days', strtotime($currentDate)));

          $sql = "SELECT SUM(pakyawan_based_work_tb.work_pay) AS cash_total, employee_tb.fname, employee_tb.empid, employee_tb.lname
          FROM pakyawan_based_work_tb
          INNER JOIN employee_tb ON pakyawan_based_work_tb.employee = employee_tb.empid
          WHERE pakyawan_based_work_tb.employee = $pakyawan_empid 
          AND `start_date` >= '$pakyawStart_cutoff' 
          AND `end_date` <= '$pakyawEnd_cutoff'";
          
          $result = mysqli_query($conn, $sql);

          $row = mysqli_fetch_assoc($result);

          

          $sqls = "SELECT * FROM pakyaw_cash_advance_tb WHERE empid = $pakyawan_empid AND `status` = 'Approved' AND `date` BETWEEN '$pakyawStart_cutoff' AND '$pakyawEnd_cutoff' ";

          $pakyaw_advanceResult = mysqli_query($conn, $sqls);

          $pakyaw_advanceRow = mysqli_fetch_assoc($pakyaw_advanceResult);


          @$cash_advance = $pakyaw_advanceRow['cash_advance'];
          

          $total = $row['cash_total'];

          $pakyawan_salary = $total - $cash_advance; 


      }
  ?>
  <script>
    <?php 
    include 'config.php';

    $sql="SELECT CONCAT(fname,' ',lname) AS full_name FROM employee_tb WHERE empid = $pakyawan_empid";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);
    ?>

    window.html2canvas = html2canvas;
    window.jsPDF = window.jspdf.jsPDF;

    function makePDF(){
      html2canvas(document.querySelector("#modal-body"),{
        allowTaint:true,
        useCORS:true,
        scale:1
      }).then(canvas =>{
        // document.body.appendChild(canvas)
        var img = canvas.toDataURL("Payslip PDF");
        var doc = new jsPDF();
        doc.setFont('Arial');
        doc.getFontSize(11);
        doc.addImage(img, 'PNG', 7, 13, 195,105);
       doc.save("<?php echo $row['full_name']?> -  Payslip");
      });

    }
</script>

    <!-- <div class="payslip-container" style="position: absolute; left: 18%; top: 13%; height: 83%; width: 79%; background-color: #fff;  box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); border-radius: 10px;">
    <button id="openModalButton" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
  Open Modal
</button>
    </div> -->
    <!-- Button to open the modal -->
<!-- Button to open the modal -->
<!-- <button id="openModalButton" type="button" class="btn btn-primary">
  Open Modal
</button> -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header" id="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">PAYSLIP</h5>
        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
      </div>

      <div class="modal-body" id="modal-body" >
        <div class="header_view">
          <img src="icons/logo_hris.png" width="70px" alt="">
          <p class="lbl_cnfdntial">CONFIDENTIAL SLIP</p>
        </div>
        <div class="modal-content-header mt-3" >

            <div class="modal-content-header-first d-flex flex-row" style="width: 100%">
              <div style="margin-right: 9%">
                <?php 
                  include 'config.php';
                  $sql="SELECT * FROM settings_company_tb";

                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_assoc($result);
                  
                ?>

                <p style="color: #656464"><?php echo $row['cmpny_name']?></p>
              </div>
              <div style="margin-right: 9%">
              <?php 
                  include 'config.php';
                  $sql="SELECT * FROM pakyawan_cutoff_tb WHERE id = $cutOffID";

                  $result = mysqli_query($conn, $sql);
                  $row = mysqli_fetch_assoc($result);
                  
                ?>

                <p style="color: #656464">Pay Period:<span style="color: #4B49AC;" class="ml-2"><?php echo $row['start_date']?></span> to  <span style="color: #4B49AC;"><?php echo $row['end_date'] ?></span></p>
              </div>
              <div>
                <?php
                    date_default_timezone_set('Asia/Manila');
                    $current_date = date('Y/m/d');
                    // echo $current_date;
                    ?>
                    <p style="color: #656464"> Payout: <span style="color: #4B49AC;" class="ml-2"><?php echo $current_date?></span></p>
              </div>
            </div>
            <div class="modal-content-header-first d-flex flex-row mt-2" style="width: 100%">
              <div style="margin-right: 6.5%">
                <?php
                 $cmpny_empid = $_GET['pakyawan_empid'];

                 $sql = "SELECT employee_tb.company_code, 
                         employee_tb.empid, 
                         assigned_company_code_tb.company_code_id, 
                         assigned_company_code_tb.empid, 
                         company_code_tb.id, 
                         company_code_tb.company_code AS company_code_name 
                         FROM assigned_company_code_tb 
                         INNER JOIN company_code_tb ON assigned_company_code_tb.company_code_id = company_code_tb.id 
                         INNER JOIN employee_tb ON assigned_company_code_tb.empid = employee_tb.empid 
                         WHERE assigned_company_code_tb.empid = '$cmpny_empid'  ";
                         
                         $cmpny_result = mysqli_query($conn, $sql); // Corrected parameter order
                         $cmpny_row = mysqli_fetch_assoc($cmpny_result); 
                ?>

                <p style="color: #656464">Employee No: <span style="color: #4B49AC;"><?php echo $cmpny_row['company_code_name']?> -</span> <span style="color: #4B49AC;"><?php echo $cmpny_row['empid'] ?></span></p>
              </div>
              <div >
                <?php
                   include 'config.php';
                   $sql="SELECT CONCAT(fname,' ',lname) AS full_name FROM employee_tb WHERE empid = $pakyawan_empid";
 
                   $result = mysqli_query($conn, $sql);
                   $row = mysqli_fetch_assoc($result);
                ?>

                <p style="color: #656464">Employee Name: <span style="color: #4B49AC; text-transform: uppercase;"><?php echo $row['full_name'] ?></span></p>
              </div>
            </div>
           
        </div>

        <!-- payslip body -->

        <div class="modals-container w-100 mt-3">
            <div class="content-header w-100" style="height: 2em; border-radius: 8px 8px 0 0; background-color: #ececec"></div>
            <div class="modals-content w-100 d-flex flex-row justify-content-between" style="border: #CED4DA 1px solid">
              <div class="modals-box" style="width: 36%; height: 30em; border-right: #CED4DA 1px solid; position: relative">
                <div class="box-header w-100 d-flex flex-row justify-content-between align-items-center pl-2 pr-3" style="height: 3em; border-bottom: #CED4DA 1px solid;">
                    <p style='color: #656464; font-weight: bold'>Unit Type</p>
                    <p style='color: #656464; font-weight: bold'>Unit Based Work</p>
                    <p style='color: #656464; font-weight: bold'>Amount</p>
                </div>
                <div class="box-content">
                    <?php

                      include 'config.php';

                      $sql = "SELECT * FROM pakyawan_based_work_tb 
                      INNER JOIN piece_rate_tb ON pakyawan_based_work_tb.unit_type = piece_rate_tb.id
                      WHERE pakyawan_based_work_tb.employee = $pakyawan_empid  
                      AND `start_date` >= '$pakyawStart_cutoff' 
                      AND `end_date` <= '$pakyawEnd_cutoff'";

                      $result = $conn->query($sql);


                      if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                          
                          $unit_rate = $row['unit_rate'];
                          $unit_work = $row['unit_work'];
                          
                          $amount = $unit_rate * $unit_work;


                          echo "
                          <div class='box-data w-100 mt-3 d-flex flex-row justify-content-between align-items-center pl-3 pr-3'>
                          <p style='color: #656464'>".$row['unit_type']."</p>
                          <p style='color: #656464'> ".$unit_work." Unit</p>
                          <p style='color: #656464' class='mr-4'>₱".$row['work_pay']."</p>
                          </div>";
                        }
                      }
                    ?>
                </div>
                <div class="box-footer w-100 d-flex flex-row justify-content-between align-items-center pl-3 pr-3" style="position: absolute; bottom: 0; height: 2.7em; border-top: #CED4DA 1px solid">
                      <p style='color: #656464'>Total Earning</p>
                      <p style='color: #656464' class="mr-4">₱<?php echo $total?></p>
                </div>
              </div>


              <div class="modals-box" style="width: 36%; height: 30em; border-right: #CED4DA 1px solid; position: relative">
                <div class="box-header w-100 d-flex flex-row justify-content-between align-items-center pl-3 pr-3" style="height: 3em; border-bottom: #CED4DA 1px solid;">
                    <p class="ml-3" style='color: #656464; font-weight: bold'>Deductions</p>
                    <p class="mr-3" style='color: #656464; font-weight: bold'>Amount</p>
                </div>
                <div class="box-content w-100 mt-3 d-flex flex-row justify-content-between align-items-center pl-3 pr-3">
                  <?php 
                    $currentDate = date('Y-m-d'); // Get the current date
                    $dayOfWeek = date('N', strtotime($currentDate)); // Get the day of the week (1 = Monday, 7 = Sunday)
                    
                    // Calculate the start date and end date of the current week
                    $startDate = date('Y-m-d', strtotime('-' . ($dayOfWeek - 1) . ' days', strtotime($currentDate)));
                    $endDate = date('Y-m-d', strtotime('+' . (7 - $dayOfWeek) . ' days', strtotime($currentDate)));
          
                    $sqls = "SELECT * FROM pakyaw_cash_advance_tb WHERE empid = $pakyawan_empid AND `status` = 'Approved' AND `date` BETWEEN '$startDate' AND '$endDate' ";
          
                    $pakyaw_advanceResult = mysqli_query($conn, $sqls);
          
                    $pakyaw_advanceRow = mysqli_fetch_assoc($pakyaw_advanceResult);

                    @$cash_advance = $pakyaw_advanceRow['cash_advance'];

                    if ($cash_advance === null) {
                      $cash_advance = 0;
                  }
        
                  ?>
                      <p class="ml-3" style='color: #656464'>Deductions</p>
                      <p class="mr-5" style='color: #656464'>₱ <?php echo @$cash_advance ?> </p>
                </div>
                <div class="box-footer w-100 d-flex flex-row justify-content-between align-items-center pl-3 pr-3" style="position: absolute; bottom: 0; height: 2.7em; border-top: #CED4DA 1px solid">
                      <p class="ml-3" style='color: #656464'>Total Deductions</p>
                      <p class="mr-3" style='color: #656464' class="mr-4">₱ <?php echo @$cash_advance ?></p>
                </div>
              </div>

              
              <div class="modals-box" style="width: 30%; height: 30em; ">
                <div class="box-header w-100 d-flex flex-row justify-content-center align-items-center" style="height: 3em; border-bottom: #CED4DA 1px solid;">
                      <p style='color: #656464; font-weight: bold'>Net Pay</p>
                </div>
                <div class="box-content w-100 mt-3 d-flex flex-row justify-content-center align-items-center">
                      <h3 class="d-flex justify-content-center align-items-center" style="color: #656464">₱ <?php echo $pakyawan_salary?></h3>
                </div>
              </div>
              
            </div>
        </div>

      </div>

            <div class="modal-footer">
              <a href="pakyawan_payroll" style="text-decoration:none; "  class="mr-3">Close</a>
              <button type="button" class="btn btn-primary" id="pdfPrint" onclick="makePDF()">Print</button>

          </div>
          
        </div>
      </div>
    </div>


      

<script>
  window.addEventListener('DOMContentLoaded', function () {
    var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
      backdrop: 'static'
    });
    
    myModal.show();
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