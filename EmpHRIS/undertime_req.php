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
    <link rel="stylesheet" href="css/undertime.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/undertime_reqResponsives.css">

    <title>Undertime Request - Employee</title>
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
<div class="modal fade" id="file_undertime" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Undertime Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    
                    <form action="Data Controller/Undertime Request/under_request.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3" style="display: none;">
                                <label for="Select_emp" class="form-label">Employee Name</label>
                                <?php
                                    include 'config.php';
                                ?>
                                <input type="text" class="form-control" name="name_emp" value="<?php echo $_SESSION['empid'];?>" id="empid" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="company" class="form-label">Date</label>
                                <input type="date" name="date_undertime" class="form-control" id="date_id_undertime" required onchange="checkSchedule()" required>
                            </div>

                            <div class="form-group">
                                <label for="time_range" class="form-label mt-1">Time Range</label>
                                <div class="input-group mb-3">
                                    <input type="time" class="form-control" name="under_time_to" id="under_time_to_id" onchange="undertime_hours(); validateUndertimeInputs();" required>
                                    <span class="input-group-text">-</span>
                                    <input type="time" class="form-control" name="under_time_from" id="under_time_from_id" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ot_hours" class="form-label">Undertime Hours</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="total_undertime" id="under_id" readonly>
                                    <span class="input-group-text">hrs</span>
                                </div>
                            </div>

                            <div class="mb-3 mt-2">
                                <label for="text_area" class="form-label">Reason</label>
                                <textarea class="form-control" name="undertime_reason" id="view_under_reason" required></textarea>
                            </div>

                                <div class="input-group mb-3">
                                    <input type="file" name="file_upload" class="form-control" id="inputfile" >
                                    <label class="input-group-text"  for="inputGroupFile02">Upload</label>
                                </div>
                            </div> <!---Modal body close tag-->

                            <div class="modal-footer">
                            <button type="submit" name="add_undertime" id="undertime_add" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                    </form> 

             </div>
        </div>
     </div>
<!--------------------------------------Modal End Here----------------------------------------------->

<!---------------------------------------Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download_undertime" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Undertime Request/download_undertime.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id_undertime" id="id_table_undertime">
        <input type="hidden" name="table_name_undertime" id="name_table_undertime">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_download_undertime" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!--------------------------------------------------Download Modal End Here---------------------------------------------------->

<!------------------------------------------------View ng whole data Modal ---------------------------------------------------->
<div class="modal fade" id="view_undertime_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="text" name="undertime_empid_view" class="form-control" id="undertime_empid_view_id" readonly>
                        </div>
                        <div class="col-6">
                            <label for="company" class="form-label">OT Date</label>
                            <input type="text" name="undertime_date_view" class="form-control" id="undertime_date_view_id" readonly>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                       <div class="col-6">
                            <label for="start" class="form-label">Start Time</label>
                            <input type="text" name="view_undertime_start" class="form-control" id="view_undertime_start_id" readonly>
                        </div>
                        <div class="col-6">
                           <label for="end" class="form-label">End Time</label>
                           <input type="text" name="view_undertime_end" class="form-control" id="view_undertime_end_id" readonly>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="ot_hours" class="form-label">Undertime Hours</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="view_total_undertime" id="view_total_undertime_id" readonly>
                            <span class="input-group-text">hrs</span>
                       </div>
                    </div>

                    <div class="mb-3">
                        <label for="text_area" class="form-label">Reason</label>
                        <textarea class="form-control" name="view_undertime_reason" id="view_undertime_reason_id" readonly></textarea>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="view_undertime_upload" class="form-control" id="view_undertime_upload_id" readonly>
                        <label class="input-group-text"  for="inputGroupFile02">Upload</label>
                    </div>

                  <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label">Date File</label>
                            <input type="text" name="view_datefiled" class="form-control" id="view_datefiled_id" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Status</label>
                            <input type="text" name="view_undertime_status" class="form-control" id="view_undertime_status_id" readonly>
                        </div>
                    </div>

                </div> <!---Modal Body End Tag-->
        </div>
    </div>
</div>
<!------------------------------------------------End ng View Modal ---------------------------------------------------->





<!------------------------------------Main Panel of data table------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 18.7%; position: absolute; top: 4.2%;">
        <div class=" mt-5">
          <div class="card" style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17); width:1500px; height: 780px;">
            <div class="card-body" style="border-radius: 25px;">  
<!------------------------------------Main Panel of data table------------------------------------------------->


<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <h2 style="font-size: 23px; font-weight: bold;">Undertime Request List</h2>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                                <button type="button" style="background-color: black" class="file_undertime btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#file_undertime">
                                    File Undertime
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
            <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                    <table id="order-listing" class="table" style="width: 100%">
                        <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th style="display: none;">Employee ID</th>
                                <th style="display: none;">Name</th>
                                <th>Undertime Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Undertime Hours</th>
                                <th style="display: none;">Reason</th>
                                <th>File Attachment</th>
                                <th>Action Taken</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th style="display: none;">Date Filed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                         <?php
                         include 'config.php';
                         $employeeid = $_SESSION['empid'];

                         $query = "SELECT
                         undertime_tb.id,
                         employee_tb.empid,
                         CONCAT (employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                         undertime_tb.date,
                         undertime_tb.start_time,
                         undertime_tb.end_time,
                         undertime_tb.total_undertime,
                         undertime_tb.file_attachment,
                         undertime_tb.reason,
                         undertime_tb.ut_action_taken,
                         undertime_tb.ut_remarks,
                         undertime_tb.status,
                         undertime_tb.date_file
                         FROM
                            employee_tb
                         INNER JOIN undertime_tb ON employee_tb.empid = undertime_tb.empid WHERE undertime_tb.empid = $employeeid;";
                         $result = mysqli_query($conn, $query);
                         while ($row = mysqli_fetch_assoc($result)){  
                         ?>
                            <tr>
                                <td style="display: none;"><?php echo $row['id']?></td>
                                <td style="display: none;"><?php echo $row['empid']?></td>
                                <td style="display: none;"><?php echo $row['full_name']?></td>
                                <td><?php echo $row['date']?></td>
                                <td><?php echo date('h:i A', strtotime($row['end_time'])) ?></td>
                                <td><?php echo date('h:i A', strtotime($row['start_time'])) ?></td>
                                <td><?php echo $row['total_undertime']?></td>
                                <td style="display: none;"><?php echo $row['reason']?></td>
                                <?php if(!empty($row['file_attachment'])): ?>
                                <td>
                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download_undertime">Download</button>
                                </td>
                                <?php else: ?>
                                <td>No file attach</td> <!-- Show an empty cell if there is no file attachment -->
                                <?php endif; ?>
                                <td><?php echo $row['ut_action_taken'];?></td>
                                <td><?php echo $row['ut_remarks'];?></td>
                                <td <?php if ($row['status'] == 'Approved') {echo 'style="color:green;"';} elseif ($row['status'] == 'Rejected') {echo 'style="color:red;"';} elseif ($row['status'] == 'Pending') {echo 'style="color:orange;"';} elseif ($row['status'] == 'Cancelled') {echo 'style="color:gray;"';} ?>><?php echo $row['status']; ?>
                            </td>
                                <td style="display: none;"><?php echo $row['date_file']?></td>
                                <td><a href="" class="btn btn-primary viewbtn" data-bs-toggle="modal" data-bs-target="#view_undertime_modal">View</a></td>
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
    var date = document.getElementById("date_id_undertime").value;
    var addButton = document.getElementById("undertime_add");

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.error){
                alert(response.message);
                document.getElementById("under_time_from_id").value = '';
                addButton.disabled = true; // disable the Add button
            }else{
                document.getElementById("under_time_from_id").value = response.end_time;
                addButton.disabled = false; // enable the Add button
            }
        }
    };
    xhttp.open("POST", "actions/Undertime Request/schedule_check.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("date=" + date);
}
</script>
<!------------------------------------End Script for Checking date if may nabago-------------------------------------------------> 

<!------------------------------------Script para lumabas ang modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                 $('#download_undertime').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table_undertime').val(data[0]);
                   $('#name_table_undertime').val(data[2]);
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
                   $('#undertime_empid_view_id').val(data[1]);
                   $('#undertime_date_view_id').val(data[3]);
                   $('#view_undertime_start_id').val(data[4]);
                   $('#view_undertime_end_id').val(data[5]);
                   $('#view_total_undertime_id').val(data[6]);
                   $('#view_undertime_reason_id').val(data[7]);
                   $('#view_undertime_upload_id').val(data[8]);
                   var status = $tr.find('td:eq(9)').text();
                   $('#view_undertime_status_id').val(status);
                   $('#view_datefiled_id').val(data[10]);
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
<script src="js/undertime.js"></script>

</body>
</html>