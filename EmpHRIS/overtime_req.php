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
    <link rel="stylesheet" href="css/overtime.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/overtime_reqResponsive.css">

    <title>Overtime - Employee</title>
</head>
<body>
<header>
     <?php
         include 'header.php';
     ?>
</header>

<style>
   
   html{
        background-color: #f4f4f4 !important;
        overflow: hidden;
       
    }
    body{
        overflow: hidden;
        background-color: #F4F4F4 !important;
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
    .file_overtime:hover{
    box-shadow: 10px 10px 8px #888888 !important;
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
<div class="modal fade" id="file_overtime" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Overtime Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    
                    <form action="Data Controller/Overtime Request/ot_insert.php" method="POST" enctype="multipart/form-data">
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
                            </div>
                            
                            <div class="mb-3">
                                <label for="company" class="form-label">Date</label>
                                <input type="date" name="date_choose" class="form-control" id="date_id" required onchange="checkSchedule()">
                            </div>

                            <div class="mb-3">
                                <label for="schedule" class="form-label">Work Schedule</label>
                                <input type="text" class="form-control" name="schedule" id="schedule_id" readonly>
                            </div>
                            
                            <div class="row" >
                                <div class="col-6">
                                    <label for="start" class="form-label">Start Time</label>
                                    <input type="time" name="time_start" class="form-control" id="start_time_id" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="end" class="form-label">End Time</label>
                                    <input type="time" name="time_end" class="form-control" id="end_time_id" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="time_from" class="form-label mt-1">Time Range</label>
                                <div class="input-group mb-3">
                                    <input type="time" class="form-control" name="time_from" id="time_from_id">
                                    <span class="input-group-text">-</span>
                                    <input type="time" class="form-control" name="time_to" id="time_to_id" onchange="min_hours()" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ot_hours" class="form-label">Overtime Hours</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="total_overtime" id="ot_id" readonly>
                                    <span class="input-group-text">hrs</span>
                                </div>
                            </div>

                            <div class="mb-3 mt-2">
                                <label for="text_area" class="form-label">Reason</label>
                                <textarea class="form-control" name="file_reason" id="view_reason" required></textarea>
                            </div>

                                <div class="input-group mb-3">
                                    <input type="file" name="file_upload" class="form-control" id="inputfile">
                                </div>
                            </div> <!---Modal body close tag-->

                            <div class="modal-footer">
                            <button type="submit" name="add_overtime" id="overtime_add" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                    </form> 

             </div>
        </div>
     </div>
<!--------------------------------------Modal End Here----------------------------------------------->

<!---------------------------------------Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download_ot" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Overtime Request/download_ot.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table">
        <input type="hidden" name="table_name" id="name_table">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_download_ot" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------Download Modal End Here --------------------------------------->

<!------------------------------------------------View ng whole data Modal ---------------------------------------------------->

<div class="modal fade" id="view_ot_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Employee Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
                <div class="modal-body">
                        <div class="mb-3">
                            <label for="company" class="form-label">Overtime Date</label>
                            <input type="text" name="view_date_choose" class="form-control" id="view_date_id" readonly>
                        </div>
                    
                    <div class="row mt-2">
                       <div class="col-6">
                            <label for="start" class="form-label">Time In</label>
                            <input type="text" name="view_time_start" class="form-control" id="view_start_time_id" readonly>
                        </div>
                        <div class="col-6">
                           <label for="end" class="form-label">Time Out</label>
                           <input type="text" name="view_time_end" class="form-control" id="view_end_time_id" readonly>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="ot_hours" class="form-label">Overtime Hours</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="view_total_overtime" id="view_ot_id" readonly>
                            <span class="input-group-text">hrs</span>
                       </div>
                    </div>

                    <div class="mb-3">
                            <label for="text_area" class="form-label">Your Reason</label>
                            <textarea class="form-control" name="view_reason" id="view_reason_id" readonly></textarea>
                    </div>


                  <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label mt-1">Action Taken</label>
                            <input type="text" name="datefile_viewing" class="form-control" id="view_action_taken" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label mt-1">Status</label>
                            <input type="text" name="view_status" class="form-control" id="view_status_id" readonly>
                        </div>
                    </div>

                    <div class="mt-2">
                            <label for="text_area" class="form-label">Approver Remarks</label>
                            <textarea class="form-control" name="view_approve_marks" id="view_approve_marks_id" readonly></textarea>
                    </div>

                </div> <!---Modal Body End Tag-->
        </div>
    </div>
</div>

<!------------------------------------------------End ng View Modal ---------------------------------------------------->





<!------------------------------------Main Panel of data table------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 18.7%; position: absolute; top: 4.2%;">
        <div class=" mt-5">
          <div class="card" style= "width:1500px; height:780px;  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body" style="border-radius:25px;">  
<!------------------------------------Main Panel of data table------------------------------------------------->


<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <h2 style="font-size: 23px; font-weight: bold;">Overtime Request List</h2>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                                <button type="button" style="background-color: black" class="file_overtime btn btn-primary" data-bs-toggle="modal" data-bs-target="#file_overtime">
                                    File Overtime
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
               

<!------------------------------------------Syntax ng Table-------------------------------------------------->
<form action="" method="POST">
        <div class="row" >
            <div class="col-12 mt-5">
                <!-- <input style="display: none;" type="text" id="input_id" name="input"> -->
                <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                    <table id="order-listing" class="table" style="width: 100%">
                            <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th style="display: none;">Employee ID</th>
                                <th style="display: none;">Name</th>
                                <th>OT Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>OT Hours</th>
                                <th style="display: none;">Reason</th>
                                <th>File Attachment</th>
                                <th>Action Taken</th>
                                <th style="display: none;">Remarks</th>
                                <th>Status</th>
                                <th style="display: none;">Date Filed</th>
                                <th>View Details</th>
                            </tr>
                        </thead>
                         <?php
                         include 'config.php';
                         $employeeid = $_SESSION['empid'];
                         
                         $query = "SELECT
                         overtime_tb.id,
                         employee_tb.empid,
                         CONCAT (employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                         overtime_tb.work_schedule,
                         overtime_tb.time_in,
                         overtime_tb.time_out,
                         overtime_tb.ot_hours,
                         overtime_tb.total_ot,
                         overtime_tb.reason,
                         overtime_tb.file_attachment,
                         overtime_tb.ot_action_taken,
                         overtime_tb.ot_remarks,
                         overtime_tb.status,
                         overtime_tb.date_filed
                         FROM
                            employee_tb
                         INNER JOIN overtime_tb ON employee_tb.empid = overtime_tb.empid WHERE overtime_tb.empid = $employeeid;";
                         $result = mysqli_query($conn, $query);
                         while ($row = mysqli_fetch_assoc($result)){  

                         ?>
                            <tr>
                                <td style="display: none;"><?php echo $row['id']?></td>
                                <td style="display: none;"><?php echo $row['empid']?></td>
                                <td style="display: none;"><?php echo $row['full_name']?></td>
                                <td><?php echo $row['work_schedule']?></td>
                                <td><?php echo date('h:i A', strtotime($row['time_in'])) ?></td>
                                <td><?php echo date('h:i A', strtotime($row['time_out'])) ?></td>
                                <td><?php echo $row['total_ot']?></td>
                                <td style="display: none;"><?php echo $row['reason']?></td>
                                <?php if(!empty($row['file_attachment'])): ?>
                                <td>
                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download_ot">Download</button>
                                </td>
                                <?php else: ?>
                                <td>No file attach</td> <!-- Show an empty cell if there is no file attachment -->
                                <?php endif; ?>
                                <td><?php echo $row['ot_action_taken']?></td>
                                <td style="display: none;"><?php echo $row['ot_remarks']?></td>
                                <td <?php if ($row['status'] == 'Approved') {echo 'style="color:green;"';} elseif ($row['status'] == 'Rejected') {echo 'style="color:red;"';} 
                                elseif ($row['status'] == 'Pending') {echo 'style="color:orange;"';} elseif ($row['status'] == 'Cancelled') {echo 'style="color:gray;"';} ?>><?php echo $row['status']; ?>
                                 </td>
                                <td style="display: none;"><?php echo $row['date_filed']?></td>
                                <td><a href="" class="btn btn-primary viewbtn" data-bs-toggle="modal" data-bs-target="#view_ot_modal">View</a></td>
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
</div>


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



<!------------------------------------Script for Checking date if may nabago------------------------------------------------->               
<script>
function checkSchedule() {
    var date = document.getElementById("date_id").value;
    var addButton = document.getElementById("overtime_add");

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.error){
                alert(response.message);
                document.getElementById("schedule_id").value = '';
                document.getElementById("start_time_id").value = '';
                document.getElementById("end_time_id").value = '';
                document.getElementById("time_from_id").value = '';
                addButton.disabled = true; // disable the Add button
            }else{
                document.getElementById("schedule_id").value = response.schedule;
                document.getElementById("start_time_id").value = response.start_time;
                document.getElementById("end_time_id").value = response.end_time;
                document.getElementById("time_from_id").value = response.end_time;
                addButton.disabled = false; // enable the Add button
            }
        }
    };
    xhttp.open("POST", "actions/Overtime Request/check_schedule.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("date=" + date);
}
</script>
<!------------------------------------End Script for Checking date if may nabago-------------------------------------------------> 

<!------------------------------------Script para lumabas ang modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                 $('#download_ot').modal('show');
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

<!------------------------------------Script para sa whole view data ng modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.viewbtn').on('click', function(){
                 $('#view_ot_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_date_id').val(data[3]);
                   $('#view_start_time_id').val(data[4]);
                   $('#view_end_time_id').val(data[5]);
                   $('#view_ot_id').val(data[6]);
                   $('#view_reason_id').val(data[7]);
                   var status = $tr.find('td:eq(11)').text();
                   $('#view_status_id').val(status);
                   $('#view_action_taken').val(data[9]);  
                   $('#view_approve_marks_id').val(data[10]);     
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

<script src="js/overtime.js"></script>

</body>
</html>