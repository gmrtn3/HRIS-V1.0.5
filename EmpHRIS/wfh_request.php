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
    <link rel="stylesheet" href="css/wfh.css"/>
    <link rel="stylesheet" href="css/styles.css">
    
    <link rel="stylesheet" href="css/wfh_requestResponsives.css">
    <title>Work From Home Request</title>
</head>
<body>
    <?php
        include 'header.php';
    ?>

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

<!--------------------------------------Modal For File wfh starts here---------------------------------------->
<div class="modal fade" id="file_wfh" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Work From Home Request</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
    <form action="Data Controller/Wfh Request/insert_wfh.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <div class="mb-3" style="display: none;">
            <label for="select_empid" class="form-label">Employee Name</label>
                <?php
                include 'config.php';
                ?>
             <input type="text" class="form-control" name="name_emp" value="<?php echo $_SESSION['empid'];?>" id="empid" readonly>
        </div>

        <div class="mb-3">
            <label for="choose_wfh" class="form-label">Date</label>
            <input type="date" name="wfh_date" id="date_wfh" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="choose_timerange" class="form-label">Time Range</label>
            <div class="input-group mb-3">
                <input type="time" name="time_from" id="from_time" class="form-control" required>
                <span class="input-group-text">-</span>
                <input type="time" name="time_to" id="to_time" class="form-control" required>
            </div>
        </div>

        <div class="mb-3 mt-2">
              <label for="description" class="form-label">Request Description</label>
              <textarea class="form-control" name="request_description" id="description_req" required></textarea>
        </div>

        <div class="mb-3">
             <input type="file" name="file_upload" id="inputfile" class="form-control">
        </div>


      </div><!--Modal Body Close Tag-->
      <div class="modal-footer">
        <button type="submit" name="add_wfh" class="btn btn-primary">Add</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>

    </div>
  </div>
</div>
<!--------------------------------------Modal For File wfh end here------------------------------------------>


<!------------------------------------------------View ng whole data Modal ---------------------------------------------------->
<div class="modal fade" id="view_wfh_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
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
                            <input type="text" name="wfh_empid_view" class="form-control" id="wfh_empid_view_id" readonly>
                        </div>
                        <div class="col-6">
                            <label for="company" class="form-label">WFH Date</label>
                            <input type="text" name="wfh_date_view" class="form-control" id="wfh_date_view_id" readonly>
                        </div>
                    </div>
                    
                    <div class="form-group">
                              <label for="time_range" class="form-label mt-1">Time Range</label>
                              <div class="input-group mb-3">
                              <input type="text" class="form-control" name="wfh_from" id="wfh_time_from_id" readonly>
                              <span class="input-group-text">-</span>
                              <input type="text" class="form-control" name="wfh_time_to" id="wfh_time_to_id" readonly>
                          </div>
                      </div>

                    <div class="mb-3">
                        <label for="text_area" class="form-label">Reason</label>
                        <textarea class="form-control" name="view_wfh_reason" id="view_wfh_reason_id" readonly></textarea>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="view_wfh_upload" class="form-control" id="view_wfh_upload_id" readonly>
                        <label class="input-group-text"  for="inputGroupFile02">Upload</label>
                    </div>

                  <div class="row" >
                        <div class="col-6">
                            <label for="" class="form-label">Date File</label>
                            <input type="text" name="view_datefile" class="form-control" id="view_datefile_id" readonly>
                        </div>
                        <div class="col-6">
                            <label for="" class="form-label">Status</label>
                            <input type="text" name="view_wfh_status" class="form-control" id="view_wfh_status_id" readonly>
                        </div>
                    </div>

                </div> <!---Modal Body End Tag-->
                <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
        </div>
    </div>
</div>
<!------------------------------------------------End ng View Modal ---------------------------------------------------->

<!-----------------------------------Modal For Download starts here------------------------------------------>
<div class="modal fade" id="download_wfh" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Download PDF File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actions/Wfh Request/download_wfh.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
            <input type="hidden" name="table_id_wfh" id="id_table_wfh">
            <input type="hidden" name="table_name_wfh" id="name_table_wfh">
            <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_download_wfh" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!-------------------------------------Modal For Download end here------------------------------------------>

<div class="main-panel mt-5" style="margin-left: 18.7%; position: absolute; top: 4.2%; border: none;">
    <div class=" mt-5">
        <div class="card" style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17); width:1500px; height:780px;">
            <div class="card-body" style="border: none; border-radius: 25px;">

<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <h2 style="font-size: 23px; font-weight: bold;">Work From Home Request List</h2>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                                <button type="button" style="background-color: black" class="file_wfh btn btn-primary" data-bs-toggle="modal" data-bs-target="#file_wfh">
                                    File Work From Home
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


        <div class="row">
            <div class="col-12 mt-5">
            <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                    <table id="order-listing" class="table" style="width: 100%">
                      <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th style="display: none;">Employee ID</th>
                                <th style="display: none;">Name</th>
                                <th>WFH Date</th>
                                <th style="display: none;">Start Time</th>
                                <th style="display: none;">End Time</th>
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
                                wfh_tb.id,
                                employee_tb.empid,
                                CONCAT (employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                wfh_tb.date,
                                wfh_tb.start_time,
                                wfh_tb.end_time,
                                wfh_tb.reason,
                                wfh_tb.file_attachment,
                                wfh_tb.wfh_action_taken,
                                wfh_tb.wfh_remarks,
                                wfh_tb.status,
                                wfh_tb.date_file
                            FROM
                                employee_tb
                            INNER JOIN wfh_tb ON employee_tb.empid = wfh_tb.empid WHERE wfh_tb.empid = $employeeid;";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)){
                            ?>
                            <tr>
                                <td style="display: none;"><?php echo $row['id']?></td>
                                <td style="display: none;"><?php echo $row['empid']?></td>
                                <td style="display: none;"><?php echo $row['full_name']?></td>
                                <td><?php echo $row['date']?></td>
                                <td style="display: none;"><?php echo $row['start_time']?></td>
                                <td style="display: none;"><?php echo $row['end_time']?></td>
                                <td style="display: none;"><?php echo $row['reason']?></td>
                                <?php if(!empty($row['file_attachment'])): ?>
                                <td>
                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download_wfh">Download</button>
                                </td>
                                <?php else: ?>
                                <td>None</td> <!-- Show an empty cell if there is no file attachment -->
                                <?php endif; ?>
                                <td><?php echo $row['wfh_action_taken']?></td>
                                <td><?php echo $row['wfh_remarks']?></td>
                                <td <?php if ($row['status'] == 'Approved') {echo 'style="color:green;"';} elseif ($row['status'] == 'Rejected') {echo 'style="color:red;"';} elseif ($row['status'] == 'Pending') {echo 'style="color:orange;"';} elseif ($row['status'] == 'Cancelled') {echo 'style="color:gray;"';} ?>><?php echo $row['status']; ?></td>
                                <td style="display: none;"><?php echo $row['date_file']?></td>
                                <td><a href="" class="btn btn-primary viewbtn" data-bs-toggle="modal" data-bs-target="#view_wfh_modal">View</a></td>
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
</div><!---Main Panel Close Tag-->


<!-- not allow past dates in wfh request -->
<script>
    // Get the input element
    const dateInput = document.getElementById('date_wfh');

    // Get the current date
    const currentDate = new Date();

    // Set the minimum selectable date to the current date
    currentDate.setHours(0, 0, 0, 0);
    dateInput.min = currentDate.toISOString().split('T')[0];
</script>

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


<!------------------------------------Script para sa whole view data ng modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.viewbtn').on('click', function(){
                 $('#view_wfh_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#wfh_empid_view_id').val(data[1]);
                   $('#wfh_date_view_id').val(data[3]);
                   $('#wfh_time_from_id').val(data[4]);
                   $('#wfh_time_to_id').val(data[5]);
                   $('#view_wfh_reason_id').val(data[6]);
                   $('#view_wfh_upload_id').val(data[7]);
                   var status = $tr.find('td:eq(8)').text();
                   $('#view_wfh_status_id').val(status);
                   $('#view_datefile_id').val(data[9]);
               });
             });
             </script>
<!---------------------------------End ng Script whole view data ng modal------------------------------------------>

<!------------------------------Script para lumabas download ang modal--------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                 $('#download_wfh').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table_wfh').val(data[0]);
                   $('#name_table_wfh').val(data[2]);
               });
             });
</script>
<!-------------------------------End ng Script para lumabas download ang modal------------------------------------->

<!--------------------Script para lumabas ang warning message na PDF File lang inaallow---------------------------->
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
<!--------------------End ng Script para lumabas ang warning message na PDF File lang inaallow--------------------->

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