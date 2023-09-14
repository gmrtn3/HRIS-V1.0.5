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


    <link rel="stylesheet" href="css/dtr_ad.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/dtr_adminResponsive.css">
    <title>DTR Correction - Admin</title>
</head>
<body>
<header>
     <?php
         include 'header.php';
         include 'user-image.php';
     ?>
</header>

<style>
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



<!-- Modal -->
<div class="modal fade" id="manualDtr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">DTR Correction</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actions/DTR Correction/correction.php" method="post">
      <div class="modal-body">
            <div class="mb-3">
                  <label for="exampleInputDate" class="form-label">Employee ID</label>
                  <input name="employeeId" type="text" class="form-control" required>
              </div>

             <div class="mb-3">
                  <label for="exampleInputDate" class="form-label">Date</label>
                  <input name="dateDtr" type="date" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="exampleInputTime" class="form-label">Time</label>
                  <input name="timeDtr" type="time" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="disabledSelect" class="form-label">Type</label>
                  <select name="typeDtr" class="form-select" required>
                    <option value="" disabled>Select Type</option>
                    <option value="IN">IN</option>
                    <option value="OUT">OUT</option>
                  </select>
                </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yesCorrect" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!------------------------------------Header and Button------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">

                <div class="row">
                        <div class="col-6">
                            <p style="font-size: 25px; padding: 10px">DTR Correction</p>
                        </div>

                        <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                              <button type="button" class="manualDtr" data-bs-toggle="modal" data-bs-target="#manualDtr">
                               Manual Correction
                              </button>
                          </div>
                    </div>  
<!------------------------------------Header, Dropdown and Button------------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['error'])) {
            $err = $_GET['error'];
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            '.$err.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->

<!---------------------------------------View Modal Start Here -------------------------------------->
<!-- <div class="modal fade" id="view_dtr_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Reason</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
            <label for="text_area" class="form-label"></label>
            <textarea class="form-control" name="text_reason" id="view_reason1" readonly></textarea>
         </div>
      </div>

    </div>
  </div>
</div> -->
<!---------------------------------------View Modal End Here --------------------------------------->

<!---------------------------------------Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download_dtr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/DTR Correction/download_dtr.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table">
        <input type="hidden" name="table_name" id="name_table">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_dl" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------Download Modal End Here --------------------------------------->

<!----------------------------------Syntax for Dropdown button------------------------------------------>
    <div class="official_panel">
            <div class="child_panel">
              <p class="empo_date_text">Employee</p>
                     <?php
                        include 'config.php';

                        $sql = "SELECT `empid`, CONCAT(`fname`, ' ',`lname`) AS `full_name` FROM employee_tb";
                        $result = mysqli_query($conn, $sql);

                        $employeeID = isset($_GET['empid']) ? ($_GET['empid']): '';
                        echo "<select class='select_custom form-select-m' aria-label='.form-select-sm example' name='name_emp' id='sel_employee'>";
                        echo "<option value=''>Select Employee</option>";
                        echo "<option value='All Employee'" .($employeeID == 'All Employee' ? ' selected' : '').">All Employee</option>";
                        while ($row = mysqli_fetch_array($result)) {
                          $emp_id = $row['empid'];
                          $emp_name = $row['full_name'];
                          echo "<option value='$emp_id - $emp_name'" . ($employeeID == $emp_id . ' - ' . $emp_name ? ' selected' : '') . ">$emp_id - $emp_name</option>";
                      }
                      echo "</select>";
                      ?>
            </div>

            <div class="child_panel">
              <p class="empo_date_text">Date From</p>
              <input class="select_custom" type="date" name="date_from" id="datestart" value="<?php echo $_GET['date_from'] ?? ''; ?>" required>
            </div>
            <div class="child_panel">
              <div class="notif">
              <p class="empo_date_text">Date To</p>
            </div>
            <input class="select_custom" type="date" name="date_to" id="enddate" onchange="datefunct()" value="<?php echo $_GET['date_to'] ?? ''; ?>" required>
            </div>
            <button class="btn_go" id="id_btngo" onclick="filterDtr()">&rarr; Apply Filter</button>
          </div>
<!------------------------------End Syntax for Dropdown button------------------------------------------------->


<!----------------------------Script sa pagfilter ng data table------------------------->
<script>
    function filterDtr() {
        var employee = document.getElementById('sel_employee').value;
        var dateFrom = document.getElementById('datestart').value;
        var dateTo = document.getElementById('enddate').value;

        var url = 'dtr_admin.php?empid=' + employee + '&date_from=' + dateFrom + '&date_to=' + dateTo;
        window.location.href = url;
    }
</script>
<!----------------------------Script sa pagfilter ng data table------------------------->


<!----------------------------------Button for Approve and Reject All------------------------------------------>
              <div class="btn-section mt-3" >
                <form action="actions/DTR Correction/update_status.php" method="POST">
                <input type="hidden" name="Approve" value="approved">
                <button type="submit" name="approve_all" class="approve-btn">Approve All</button>
                </form>

                <form action="actions/DTR Correction/update_status.php" method="POST">
                <!-- <input type="hidden" name="status" value="rejected"> -->
                <button type="submit" name="reject_all" class="reject-btn">Reject All</button>
                </form>
              </div>
<!--------------------------------End Button for Approve and Reject All---------------------------------------->                 

<!------------------------------------------Syntax ng Table-------------------------------------------------->
                        <form action="actions/DTR Correction/approval.php" method="POST">
                              <input type="hidden" id="input_id" name="input" value="<?php echo $row['id']; ?>">
                              <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                                    <table id="order-listing" class="table" style="width: 100%">
                                      <thead >
                                          <tr>
                                              <th style="display: none;">ID</th>
                                              <th>Employee ID</th>
                                              <th>Name</th>
                                              <th>Date</th>
                                              <th>Time</th>
                                              <th>Type</th>
                                              <th>Status</th>
                                              <th>File Attachment</th>
                                              <th style="display: none;">Reason</th>
                                              <th style="display: none;">View Button</th>
                                              <th>Action</th>
                                          </tr>
                                      </thead>
                                      <?php 
                                          include 'config.php';

                                          $employee = $_GET['empid'] ?? '';
                                          $dateFrom = $_GET['date_from'] ?? '';
                                          $dateTo = $_GET['date_to'] ?? '';

                                          if (isset($_GET['id'])) {
                                            $employee_id = $_GET['id'];

                                            $query = "SELECT
                                            emp_dtr_tb.id,
                                            employee_tb.empid,
                                            CONCAT(
                                                employee_tb.`fname`,
                                                ' ',
                                                employee_tb.`lname`
                                            ) AS `full_name`,
                                            emp_dtr_tb.date,
                                            emp_dtr_tb.time,
                                            emp_dtr_tb.type,
                                            emp_dtr_tb.reason,
                                            emp_dtr_tb.file_attach,
                                            emp_dtr_tb.status
                                            FROM
                                                employee_tb
                                            INNER JOIN emp_dtr_tb ON employee_tb.empid = emp_dtr_tb.empid
                                            WHERE emp_dtr_tb.id = '$employee_id'";


                                            if (!empty($dateFrom) && !empty($dateTo)) {
                                              if ($employee != 'All Employee') {
                                                $query .= " AND";
                                              } else {
                                                $query .= " WHERE";
                                              }
                                              $query .= " (emp_dtr_tb.date BETWEEN '$dateFrom' AND '$dateTo')";
                                            }
                                          } else {
                                            $query = "SELECT
                                            emp_dtr_tb.id,
                                            employee_tb.empid,
                                            CONCAT(
                                                employee_tb.`fname`,
                                                ' ',
                                                employee_tb.`lname`
                                            ) AS `full_name`,
                                            emp_dtr_tb.date,
                                            emp_dtr_tb.time,
                                            emp_dtr_tb.type,
                                            emp_dtr_tb.reason,
                                            emp_dtr_tb.file_attach,
                                            emp_dtr_tb.status
                                            FROM
                                                employee_tb
                                            INNER JOIN emp_dtr_tb ON employee_tb.empid = emp_dtr_tb.empid";


                                            if (!empty($dateFrom) && !empty($dateTo)) {
                                              if ($employee != 'All Employee') {
                                                $query .= " AND";
                                              } else {
                                                $query .= " WHERE";
                                              }
                                              $query .= " (emp_dtr_tb.date BETWEEN '$dateFrom' AND '$dateTo')";
                                            }
                                          }

                                          $result = mysqli_query($conn, $query);
                                          while ($row = mysqli_fetch_assoc($result)) {
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
                                        <td class="unique_id" style="display: none;"><?php echo $row['id']?></td>
                                        <td><?php $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                            $empid = $row['empid'];
                                            if (!empty($cmpny_code)) {
                                                echo $cmpny_code . " - " . $empid;
                                            } else {
                                                echo $empid;
                                            } ?></td>
                                        <td><a href="" class="showbtn" data-bs-toggle="modal" data-bs-target="#viewmodal"><?php echo $row['full_name']?></a></td>
                                        <td><?php echo $row['date']?></td>
                                        <td><?php echo date('h:i A', strtotime($row['time'])) ?></td>
                                        <td><?php echo $row['type']?></td>
                                        <td <?php if ($row['status'] == 'Approved') {echo 'style="color:green;"';} elseif ($row['status'] == 'Rejected') {echo 'style="color:red;"';} elseif ($row['status'] == 'Pending') {echo 'style="color:Orange;"';} elseif ($row['status'] == 'Cancelled') {echo 'style="color:Gray;"';}?>><?php echo $row['status']; ?></td>
                                        <?php if(!empty($row['file_attach'])): ?>
                                        <td>
                                        <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download_dtr">Download</button>
                                        </td>
                                        <?php else: ?>
                                        <td>None</td> <!-- Show an empty cell if there is no file attachment -->
                                        <?php endif; ?>
                                        <td style="display: none;"><?php echo $row['reason']?></td>
                                        <td style="display: none;"><i class="fa-solid fa-eye fs-5 me-3 viewbtn" data-bs-toggle="modal" data-bs-target="#view_dtr_modal" style="cursor: pointer;"></i></td>
                                        <td>
                                        <?php if ($row['status'] === 'Approved' || $row['status'] === 'Rejected' || $row['status'] === 'Cancelled'): ?>
                                          <button type="submit" class="btn btn-outline-success viewbtn" name="approve_btn" style="display: none;" disabled>
                                            Approve
                                          </button>
                                          <button type="submit" class="btn btn-outline-danger viewbtn" name="reject_btn" style="display: none;" disabled>
                                            Reject
                                          </button>
                                        <?php else: ?>
                                          <button type="submit" class="btn btn-outline-success viewbtn" name="approve_btn">
                                            Approve
                                          </button>
                                          <button type="submit" class="btn btn-outline-danger viewbtn" name="reject_btn">
                                            Reject
                                          </button>
                                        <?php endif; ?>
                                        </td>
                                        </tr>
                          <?php
                               } 
                          ?>
                      </table>
                      </form>  
<!------------------------------------End Syntax ng Table------------------------------------------------->                      
                    </div>
                </div>
            </div>
        </div>
    </div>


<!------------------------------------------------View ng whole data Modal ---------------------------------------------------->

<div class="modal fade" id="viewmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
                <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label">Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" id="view_emp_id" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="employee_name" class="form-control" id="view_emp_name" readonly>
                        </div>
                </div>

                <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label">DATE</label>
                            <input type="date" name="employee_date" class="form-control" id="view_emp_date" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">TIME</label>
                            <input type="text" name="employee_time" class="form-control" id="view_emp_time" readonly>
                        </div>
                </div>

                <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label">TYPE</label>
                            <input type="text" name="employee_type" class="form-control" id="view_emp_type" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Status</label>
                            <input name="employee_r" id="view_employee_status" class="form-control" readonly></input>
                        </div>
                </div>

                <div class="mb-3">
                    <label for="floatingTextarea2" class="form-label">Reason</label>
                    <textarea name="text_reason" class="form-control"  id="floatingTextarea2" style="height: 100px" readonly></textarea>
                </div>

            </div>
        </div>
    </div>
</div>

<!------------------------------------------------End ng Modal ---------------------------------------------------->



<!------------------------------------Script para sa pag pop-up ng view modal------------------------------------------------->
<!-- <script>
     $(document).ready(function(){
               $('.viewbtn').on('click', function(){
                 $('#view_dtr_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_reason1').val(data[8]);
               });
             });
</script> -->
<!---------------------------------End ng Script para sa pag pop-up ng view modal------------------------------------------>


<!-------------------------------Script para matest kung naseselect ba ang I.D---------------------------------------->        
<script> 
            $(document).ready(function(){
               $('.viewbtn').on('click', function(){
                 $().modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#input_id').val(data[0]);
               });
             });
        </script>
<!-----------------------------End Script para matest kung naseselect ba ang I.D------------------------------------->

<!------------------------------------Script para sa whole view data ng modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.showbtn').on('click', function(){
                 $('#viewmodal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_emp_id').val(data[1]);
                   $('#view_emp_name').val(data[2]);
                   $('#view_emp_date').val(data[3]);
                   $('#view_emp_time').val(data[4]);
                   $('#view_emp_type').val(data[5]);
                   $('#view_employee_status').val(data[6]);
                   var status = $tr.find('td:eq(6)').text();
                   $('#view_employee_status').val(status);
                   $('#floatingTextarea2').val(data[8]);

               });
             });
             </script>
<!---------------------------------End ng Script whole view data ng modal------------------------------------------>

<!---------------------------- Script para lumabas ang warning message na PDF File lang inaallow------------------------------------------>
<script>
  document.getElementById('inputfile').addEventListener('change', function(event) {
    var fileInput = event.target;
    var file = fileInput.files[0];
    if (file.type !== 'application/pdf') {
      alert('Please select a PDF file.');
      fileInput.value = ''; // Clear the file input field
    }
  });
</script>
<!--------------------End ng Script para lumabas ang Script para lumabas ang warning message na PDF File lang inaallow--------------------->

<!------------------------------------Script para sa download modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                 $('#download').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table').val(data[0]);
                   $('#name_table').val(data[2]);
               });
             });
</script>
<!---------------------------------End ng Script para download modal------------------------------------------>

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
<script src="js/dtr_admin.js"></script>
</body>
</html>