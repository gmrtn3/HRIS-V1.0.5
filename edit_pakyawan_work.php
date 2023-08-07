<?php
    session_start();
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
        } else {
            include 'config.php';
            include 'user-image.php';
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

<script type="text/javascript" src="js/multi-select-dd.js"></script>


<link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" href="css/pakyawan_generate.css">
    <link rel="stylesheet" href="css/gnratepayrollVIEW.css">
    <title>Employee List</title>
</head>
<body>


    
    <header>
       <!-- include -->
       <?php include 'header.php';?>
    </header>
    
    <style>
        .placeholder{
        display: none !important;
        cursor: default !important;
        background-color: #fff !important;
        color: #fff !important;
        display:none !important;
        
    }

    .multiselect-dropdown-list-wrapper span.placeholder{
        display: none !important;
        cursor: default !important;
        background-color: #fff !important;
        color: #fff !important;
        display:none !important; 
    }
    .multiselect-dropdown{
        height: 40px !important;
        width: 95% !important;
    }
    </style>

  
    <!-- Modal -->
<div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="form-group ml-3">

    <?php 
    $empid = $_GET['empid'];
    ?>
    <form action="actions/Pakyawan/edit_work_base" method="POST">

    <?php 
        $sql = "SELECT status FROM employee_tb WHERE empid = $empid";
        $resulta = mysqli_query($conn, $sql);
        $empRow = mysqli_fetch_assoc($resulta);
    ?>

    <div class="emp-stats d-flex flex-row mb-3" >
                                        
        <h4 style="margin-top: 11px">Employee Status: </h4>
        <input type="text" name="status" id="status" value="<?php if(isset($empRow['status']) && !empty($empRow['status'])) { echo $empRow['status']; } else { echo 'Inactive'; }?>" style="font-weight: 400;width: 65px; border: none; margin-top: 1px; margin-left: 4px; font-weight: 500; outline: none; color: <?php echo ($empRow['status'] === 'Active') ? 'green' : 'red'; ?>;" readonly>


        <span onclick="changeWord()" class="fa-solid fa-rotate" style="cursor: pointer; margin-top: 9px;"></span>

        <script>
            function changeWord() {
                var statusInput = document.getElementById("status");
                if (statusInput.value === "Inactive") {
                    statusInput.value = "Active";
                    statusInput.style.color = "green";
                } else {
                    statusInput.value = "Inactive";
                    statusInput.style.color = "red";
                }
            }
        </script>
      </div>
        <input type="hidden" name="empid" id="" value="<?php echo $empid ?>">
        <?php
        include 'config.php';

        $sql = "SELECT * FROM piece_rate_tb";
        $result = mysqli_query($conn, $sql);

        $options = "";
        while ($row = mysqli_fetch_assoc($result)) {
            $options .= "<option value='" . $row['id'] . "' style='display:flex; font-size: 16px; font-style:normal;'>".$row['unit_type']."</option>";
        }

        $query = "SELECT * FROM employee_pakyawan_work_tb WHERE empid = $empid";
        $workResult = $conn->query($query);

        if ($workResult->num_rows > 0) {
            $array_work = array();
            $selected_piece_rate_ids = array(); // Array to store selected piece_rate_ids

            while ($workRow = $workResult->fetch_assoc()) {
                $workEmpid = $workRow['empid'];
                $piece_rate_id = $workRow['piece_rate_id'];
                $array_work[] = array('empid' => $workEmpid, 'piece_rate_id' => $piece_rate_id);
                $selected_piece_rate_ids[] = $piece_rate_id;
            }
        }
        ?>
        <label for="">Edit Employee Work Base</label>
        <select name="piece_rate_id[]" class="form-control" id="pakyawan_work_edit" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2">
            <?php 
            foreach ($array_work as $work_base) {
                $base_work_empid = $work_base['empid'];
                $base_piece_rate_id = $work_base['piece_rate_id'];

                $query = "SELECT * FROM employee_pakyawan_work_tb
                          INNER JOIN piece_rate_tb ON employee_pakyawan_work_tb.piece_rate_id = piece_rate_tb.id WHERE employee_pakyawan_work_tb.empid = $base_work_empid AND employee_pakyawan_work_tb.piece_rate_id = $base_piece_rate_id";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $row_work = mysqli_fetch_assoc($result);

                    $selected = in_array($base_piece_rate_id, $selected_piece_rate_ids) ? 'selected' : '';
                    echo '<option value="'.$base_piece_rate_id.'" '.$selected.'>'.$row_work['unit_type'].'  ';
                }
            }
            echo $options;
            ?>
        </select>
    
</div>


      </div>
      <div class="modal-footer">
        <a href="pakyawanEmpList" style="text-decoration: none; color: black" class="mr-1">Close</a>
        <button type="submit" class="btn btn-primary">Update</button>
        </form>
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

<script>
        // Get the select element
        var select = document.getElementById("pakyawan_work_edit");
        // Create an empty object to store the unique values
        var uniqueValues = {};

        // Loop through each option
        for (var i = 0; i < select.options.length; i++) {
            var option = select.options[i];
            // Check if the value is already present in the uniqueValues object
            if (uniqueValues[option.value]) {
            // Duplicate value found, remove the option
            select.remove(i);
            i--; // Decrement the counter to account for the removed option
            } else {
            // Unique value, store it in the uniqueValues object
            uniqueValues[option.value] = true;
            }
        }
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