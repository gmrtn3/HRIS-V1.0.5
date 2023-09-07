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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/official_emp.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/official_empResponsive.css">
    <title>Official Business - Employee</title>
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

      .card-body{
      width: 102%;
      box-shadow: 10px 10px 10px 8px #888888;
    }

    .table{
      width: 100%;
    }

    .content-wrapper{
      width: 90%
    }

    

    #order-listing_next{
        margin-top: 20px;
        margin-right: 4px !important;
        margin-bottom: -15.5px !important;
        
    }

    #order-listing_previous{
        margin-top: 20px;
        margin-left: 12px !important;
        
    }
    
    /* Search Bar */

    #order-listing_filter label input{
        width: 278px;
        font-size: 17px;
        
    }

    /* Sorting Button Color */
    .dataTables_wrapper .dataTable thead .sorting:before, .dataTables_wrapper .dataTable thead .sorting_asc:before, .dataTables_wrapper .dataTable thead .sorting_desc:before, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:before, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:before {
        
        right: 1.2em;
        bottom: 0;
        color: #c0c1c2 !important;
        opacity: 1;
    } 

    .dataTables_wrapper .dataTable thead .sorting:after, .dataTables_wrapper .dataTable thead .sorting_asc:after, .dataTables_wrapper .dataTable thead .sorting_desc:after, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:after, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:after {
   
        right: 1.2em;
        top: 0;
        color: #c0c1c2 !important;
        opacity: 1;
    }
    table {
                display: block;
                overflow-x: hidden;
                white-space: nowrap;
                max-height: 100%;
                height: 320px;
                /* border: black 1px solid; */
                
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
   <!------------------------------------Modal Start Here----------------------------------------------->
 <div class="modal fade" id="file_off_btn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Official Business Application</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    
                    <form action="Data Controller/Official Employee/official_conn.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                        <div class="mb-3" style="display: none;">
                            <label for="Select_emp" class="form-label">Employee Name</label>
                            <?php
                                include 'config.php';
                                    // Fetch all values of fname and lname from the database
                                    // $sql = "SELECT empid,
                                    //         CONCAT(
                                    //         `fname`,
                                    //         ' ',
                                    //         `lname`
                                    //         ) AS `full_name`
                                    //         FROM employee_tb";
                                    //         $result = mysqli_query($conn, $sql);
                                    //         $row = mysqli_fetch_assoc($result)
                                    // if(isset($_SESSION['empid'])){ // check if the empid key is set in the session array
                                    //     $empid = $_SESSION['empid'];
                                    // }else{
                                    //     $empid = 'Error'; // set default value to empty string if empid key is not set
                                    // }
                                    ?>
                            <input type="text" class="form-control" name="name_emp" value="<?php echo $_SESSION['empid'];?>" id="empid" readonly>
                        </div>  <!--mb-3 end--->
                            
                            <div class="mb-3">
                                    <label for="company" class="form-label">Company Name</label>
                                    <input type="text" name="company_name" class="form-control" id="location_id" required>
                                </div>


                            <div class="row">
                                <div class="col-6">
                                <label for="start" class="form-label">Start Date</label>
                                <input type="date" name="str_date" class="form-control" id="start_date" required>
                                </div>
                                <div class="col-6">
                                <label for="end" class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" id="end_date" onchange = "datevalidate()" required>
                                 </div>
                            </div>

                                <div class="row" >
                                    <div class="col-6">
                                    <label for="timer_start" class="form-label mt-2">Start Time</label>
                                    <input type="time" name="str_time" class="form-control" id="start_time" required>
                                    </div>
                                    <div class="col-6">
                                    <label for="timer_end" class="form-label mt-2">End Time</label>
                                    <input type="time" name="end_time" class="form-control" id="end_time" onchange = "timevalidate()" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label mt-2">Location</label>
                                    <input type="text" name="locate" class="form-control" id="location_id" required>
                                </div>

                                <div class="input-group mb-3">
                                    <input type="file" name="file_upload" class="form-control" id="inputfile">
                                </div>

                                <div class="mb-3">
                                <label for="text_area" class="form-label">Reason</label>
                                <textarea class="form-control" name="text_reason" id="view_reason" required></textarea>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="submit" name="savedata" id="submit-btn" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form> 

             </div>
        </div>
     </div>
<!--------------------------------------Modal End Here----------------------------------------------->


<!----------------------View employee Details---------------------------------->
<div class="modal fade" id="viewdetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
            <div class="mb-3">
                  <label for="" class="form-label">Company Name</label>
                  <input type="text" name="company_name" class="form-control" id="view_company_name" readonly>
              </div>

              <div class="mb-3">
                  <label for="" class="form-label">Location</label>
                  <input type="text" name="location_name" class="form-control" id="view_location_name" readonly>
              </div>

              <div class="row">
                        <div class="col-6">
                            <label for="" class="form-label">Start Date</label>
                            <input type="text" name="name_start_date" class="form-control" id="view_start_date" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">End Date</label>
                            <input type="text" name="name_end_date" class="form-control" id="view_end_date" readonly>
                        </div>
                </div>

                <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label mt-1">Start Time</label>
                            <input type="text" name="name_start_time" class="form-control" id="view_start_time" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label mt-1">End Time</label>
                            <input type="text" name="name_end_time" class="form-control" id="view_end_time" readonly>
                        </div>
                </div>

                <div class="mb-3">
                    <label for="floatingTextarea2" class="form-label mt-1">Your Reason</label>
                    <textarea name="name_your_reason" class="form-control"  id="view_your_reason" readonly></textarea>
                </div>

                <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label mt-1">Action Taken</label>
                            <input type="text" name="name_action" class="form-control" id="view_action" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label mt-1">Status</label>
                            <input name="name_status" id="view_status" class="form-control" readonly></input>
                        </div>
                </div>

                <div class="mb-3">
                    <label for="floatingTextarea2" class="form-label mt-1">Approver Remarks</label>
                    <textarea name="name_approver_marks" class="form-control"  id="view_approver_marks" readonly></textarea>
                </div>
      </div> <!----modal body--->

    </div>
  </div>
</div>
<!----------------------View employee Details---------------------------------->


<!---------------------------------------View reason Start Here -------------------------------------->
<!-- <div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

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
<!---------------------------------------View reason End Here --------------------------------------->

<!---------------------------------------Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Download PDF File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Official Business/download.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table">
        <input type="hidden" name="table_name" id="name_table">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_download" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------Download Modal End Here --------------------------------------->



<!---------------------------------------Main Panel Start Here --------------------------------------->
        <div class="main-panel mt-5" style="margin-left: 16.7%; position: absolute; top: 0.5%; background-color: #f4f4f4">
            <div class="content-wrapper mt-5" style="background-color: #f4f4f4;  " >
                <div class="card" style="background-color: #f4f4f4">
                    <div class="card-body" style="width:1500px; height:780px; border-radius: 25px; box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17); ">
<!---------------------------------------Main Panel End Here --------------------------------------->
                        
<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <h2 style="font-size: 23px; font-weight: bold;">Official Business</h2>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                                <button type="button" style="background-color: black" class="add_off_btn" data-bs-toggle="modal" data-bs-target="#file_off_btn">
                                    File Official Business
                                    </button>
                                </div>
                            </div> <!--ROW END-->
<!----------------------------------End Class ng header including the button for modal-------------------------------------------->


<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="removeErrorFromURL()"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['error'])) {
            $err = $_GET['error'];
            echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            '.$err.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" onclick="removeErrorFromURL()"></button>
          </div>';
        }
?>
<!------------------------------------End Message alert------------------------------------------------->


<!--------------------------------------------Syntax and Bootstrap class for table------------------------------------------------>
                        <div class="row">
                            <div class="col-12 mt-5">
                            <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                    <table id="order-listing" class="table" style="width: 100%">
                                    <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                            <tr>
                                                <th style="display: none;">ID</th>
                                                <th style="display:none">Employee ID</th>
                                                <th style="display:none">Name</th>
                                                <th>Company Name</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th style="display: none;">Location</th>
                                                <th>File Attachment</th>
                                                <th style="display: none;">Reason</th>
                                                <th style="display: none;">View Button</th>
                                                <th style="display: none;">Remarks</th>
                                                <th>Action Taken</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <?php 
                                            // $conn = mysqli_connect("localhost","root","","hris_db");
                                            include 'config.php';
                                            $employeeid = $_SESSION['empid'];

                                            $query = "SELECT
                                            emp_official_tb.id,
                                            employee_tb.empid,
                                            CONCAT(
                                                employee_tb.`fname`,
                                                ' ',
                                                employee_tb.`lname`
                                            ) AS `full_name`,
                                            emp_official_tb.company_name,
                                            emp_official_tb.str_date,
                                            emp_official_tb.end_date,
                                            emp_official_tb.start_time,
                                            emp_official_tb.end_time,
                                            emp_official_tb.location,
                                            emp_official_tb.file_upl,
                                            emp_official_tb.reason,
                                            emp_official_tb.remarks,
                                            emp_official_tb.action_taken,
                                            emp_official_tb.status
                                        FROM
                                            employee_tb
                                        INNER JOIN emp_official_tb ON employee_tb.empid = emp_official_tb.employee_id WHERE emp_official_tb.employee_id = $employeeid;";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <tr>
                                                <td style="display: none;"><?php echo $row['id'];?></td>
                                                <td style="display:none"><?php echo $row['empid'];?></td>
                                                <td style="display:none"><?php echo $row['full_name'];?></td>
                                                <td><a href="" class="obdetails" data-bs-toggle="modal" data-bs-target="#viewdetails"><?php echo $row['company_name'];?></a></td>
                                                <td><?php echo $row['str_date'];?></td>
                                                <td><?php echo $row['end_date'];?></td>
                                                <td><?php echo date('h:i A', strtotime($row['start_time'])) ?></td>
                                                <td><?php echo date('h:i A', strtotime($row['end_time'])) ?></td>
                                                <td style="display: none;"><?php echo $row['location'];?></td>
                                                <?php if(!empty($row['file_upl'])):?>
                                                <td>
                                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download">Download</button>
                                                </td>
                                                <?php else: ?>
                                                <td >No file attach</td> <!-- Show an empty cell if there is no file attachment -->
                                                <?php endif; ?>
                                                <td style="display: none;"><?php echo $row['reason'];?></td>
                                                <td style="display: none;">
                                                <a href="" class="btn btn-primary showbtn" data-bs-toggle="modal" data-bs-target="#viewmodal">View</a></td>
                                                <td style="display: none;"><?php echo $row['remarks'];?></td>
                                                <td><?php echo $row['action_taken'];?></td>   
                                                <td <?php if ($row['status'] == 'Approved') {echo 'style="color:green;"';} elseif ($row['status'] == 'Rejected') {echo 'style="color:red;"';} elseif ($row['status'] == 'Pending') {echo 'style="color:orange;"';} elseif ($row['status'] == 'Cancelled') {echo 'style="color:gray;"';} ?>><?php echo $row['status']; ?>
                                            </td>
                                            </tr>
                                                 <?php
                                                    } 
                                                  ?>
                                    </table>
                                </div>
                            </div>
                        </div><!-----Close tag of row class------->
<!------------------------------------------End Syntax and Bootstrap class for table---------------------------------------------->

                    </div><!------Main Panel Close Tag-------->
                </div>
            </div>
        </div>


<!------------------------------------Script para sa whole view data ng modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.obdetails').on('click', function(){
                 $('#viewdetails').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_company_name').val(data[3]);
                   $('#view_location_name').val(data[8]);
                   $('#view_start_date').val(data[4]);
                   $('#view_end_date').val(data[5]);
                   $('#view_start_time').val(data[6]);
                   $('#view_end_time').val(data[7]);
                   $('#view_your_reason').val(data[10]);
                   $('#view_action').val(data[13]);
                   $('#view_status').val(data[14]);
                   var status = $tr.find('td:eq(14)').text();
                   $('#view_approver_marks').val(data[12]);

               });
             });
             </script>
<!---------------------------------End ng Script whole view data ng modal------------------------------------------>
        
<!-----------------------------Script sa pagremove ng message sa link------------------------------------>
<script>
    function removeErrorFromURL() {
        var url = new URL(window.location.href);
        url.searchParams.delete('error');
        url.searchParams.delete('msg');
        window.history.replaceState({}, document.title, url);
    }
</script>
<!-----------------------------Script sa pagremove ng message sa link------------------------------------>



<!------------------------------------Script para lumabas ang modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.showbtn').on('click', function(){
                 $('#viewmodal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_reason1').val(data[10]);
               });
             });
</script>
<!---------------------------------End ng Script para lumabas ang modal------------------------------------------>

<!------------------------------------Script para lumabas download ang modal------------------------------------------------->
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
<!---------------------------------End ng Script para lumabas ang modal------------------------------------------>


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
<script src="js/official_emp.js"></script>
</body>
</html>