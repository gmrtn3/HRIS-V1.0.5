
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
    <!-- <link rel="stylesheet" href="skydash/full-calendar.css"> -->

    <link rel="stylesheet" href="skydash/style.css">

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="fullcalendar/lib/main.min.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="fullcalendar/lib/main.min.js"></script>

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/calendar.css">
    <title>HRIS | Calendar</title>
    </head>
<body>
    <header>
        <?php 
        include("header.php")
        ?>
    </header>

    <style>
       :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        } 

        .btn-info.text-light:hover,
        .btn-info.text-light:focus {
            background: #000;
        }
        table, tbody, td, tfoot, th, thead, tr {
            border-color: #ededed !important;
            border-style: solid;
            border-width: 1px !important;
        }
        #calendar{
          height: 75% !important;
        }
        .fc .fc-event{
          color: #000;
        }
    </style>
        
    <div class="calendar-container" style="position: absolute; left: 19%; top: 14.6%; width: 78%; height: 80%; background-color: #fff; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); border-radius: 0.6em;">
      <div class="container-fluidd p-3 py-4 h-100" id="page-container" style="height: 100%">
          <div class="row w-100" style="height: 80%">
              <div class="col-md-9">
                  <h2 class="fs-2 ">Calendar</h2>
                  <div id="calendar" ></div>
              </div>
              <div class="col-md-3">
                  <div class="cardt rounded-0 shadow">
                      <div class="card-header bg-gradient bg-primary text-light">
                          <h5 class="card-title">Schedule Form</h5>
                      </div>
                      <div class="card-body">
                          <div class="container-fluid">
                              <form action="Data Controller/Calendar/save_event.php" method="post" id="schedule-form">
                                  <input type="hidden" name="id" value="">
                                  <div class="form-group mb-2">
                                      <label for="title" class="control-label">Title</label>
                                      <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                                  </div>
                                  <div class="form-group mb-2">
                                      <label for="description" class="control-label">Description</label>
                                      <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description" required></textarea>
                                  </div>
                                  <div class="form-group mb-2">
                                      <label for="start_datetime" class="control-label">Start</label>
                                      <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
                                  </div>
                                  <div class="form-group mb-2">
                                      <label for="end_datetime" class="control-label">End</label>
                                      <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
                                  </div>
                              </form>
                          </div>
                      </div>
                      <div class="card-footer d-flex justify-content-center">
                          <div class="">
                              <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i class="fa fa-save"></i> Save</button>
                              <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancel</button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- Event Details Modal -->
      <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
          <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content rounded-0">
                  <div class="modal-header rounded-0">
                      <h5 class="modal-title">Schedule Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body rounded-0">
                      <div class="container-fluid">
                          <dl>
                              <dt class="text-muted">Title</dt>
                              <dd id="title" class="fw-bold fs-4"></dd>
                              <dt class="text-muted">Description</dt>
                              <dd id="description" class=""></dd>
                              <dt class="text-muted">Start</dt>
                              <dd id="start" class=""></dd>
                              <dt class="text-muted">End</dt>
                              <dd id="end" class=""></dd>
                          </dl>
                      </div>
                  </div>
                  <div class="modal-footer rounded-0">
                      <div class="text-end">
                          <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Edit</button>
                          <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Delete</button>
                          <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>

    <?php 
$schedules = $conn->query("SELECT * FROM `schedule_list`");
$sched_res = [];
foreach($schedules->fetch_all(MYSQLI_ASSOC) as $row){
    $row['sdate'] = date("F d, Y h:i A",strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A",strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}

?>
<?php 
if(isset($conn)) $conn->close();
?>




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
                                    $('#empid').val(data[10]);
                                    $('#sched_from').val(data[7]);
                                    $('#sched_to').val(data[8]);
                                    $('#empName').val(data[1]);
                                });
                            });
            
    </script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap4.min.js"></script>

    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

           <!--skydash-->
    <script src="skydash/vendor.bundle.base.js"></script>
    <script src="skydash/full-calendar.js"></script>
    <script src="skydash/moment-min.js"></script>
    <script src="skydash/off-canvas.js"></script>
    <script src="skydash/hoverable-collapse.js"></script>
    <script src="skydash/template.js"></script>
    <script src="skydash/settings.js"></script>
    <script src="skydash/todolist.js"></script>
     <script src="main.js"></script>
     <script src="skydash/calendar.js"></script>
    <script src="bootstrap js/data-table.js"></script>


    

  
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="./js/script.js"></script>
</html>