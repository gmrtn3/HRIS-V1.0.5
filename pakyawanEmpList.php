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
    // $result = mysqli_query($conn, "SELECT * FROM settings_company_tb");
    // $rows = mysqli_fetch_assoc($result); 
    
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
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Bootstrap JavaScript library -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript" src="js/multi-select-dd.js"></script>

<link rel="stylesheet" href="skydash/style.css">

<script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>


<link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css"> 
    <title>Employee List</title>
</head>
<body>
    <header>
        <?php include("header.php")?>
    </header>
    <style>
    .email-col {
        width: 25% !important; /* adjust the width as needed */
    }
    #order-listing th.email-col,
    #order-listing td.email-col {
        text-align: left; /* optional, aligns text to the left */
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
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }

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
        width: 100% !important;
    }
    table {
                display: block;
                overflow-x: auto;
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
            th, td {
                text-align: left !important;
                width: 14.28% !important;
            }
</style>

        <!-- <div class="empList-create-search">
            <a href="empListForm.php" class="empList-btn" title="Create New">Create New</a>
        </div> -->


        <!-- View Modal -->
<div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Work Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
            <input type="hidden" name="empid" id="empid" class="pakyawan_empid">
            <!-- <input type="text" name="unitType" id="unitType" class="form-control" readonly> -->
            <table id="unitDetailsTable" class="table table-responsive table-borderless" style="height: 15em">
            <thead style="border-bottom: 1px solid black;">
            <tr>
                <th>Unit Type</th>
                <th>Unit Quantity</th>
                <th>Unit Rate</th>
            </tr>
            </thead>
            <tbody id="unitDetailsBody"></tbody>
        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit Work Type</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm" action="Data Controller/Pakyawan/edit_pakyawan_work.php" method="POST">
          <input type="hidden" name="empid" id="empid" value="">
          <div class="form-group">
            <label for="pakyawan">Pakyawan Work Type</label>
            <?php
                    $server = "localhost";
                    $user = "root";
                    $pass = "";
                    $database = "hris_db";

                    $conn = mysqli_connect($server, $user, $pass, $database);
                    $sql = "SELECT * FROM piece_rate_tb";
                    $result = mysqli_query($conn, $sql);

                    $options = "";
                    $piece_rate = array();

                    while ($row = mysqli_fetch_assoc($result)) {                
                            // $options .= "<option value='" . $row['id'] . "' style='display:flex; font-size: 16px; font-style:normal;'>".$row['unit_type']."</option>";
                            $piece_rate_id = $row['id'];
                            $piece_rate_unit_type = $row['unit_type'];

                            $piece_rate[] = array('piece_rate_id' => $piece_rate_id,
                                              'piece_rate_unit_type' => $piece_rate_unit_type);
                        }

                        foreach($piece_rate as $rate_piece){
                          $work_sql = "SELECT * FROM employee_pakyawan_work_tb";


                        }

                    
                ?>
            <select class="pakyawan-dd form-control" name="piece_rate_id[]" id="piece_rate_id" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2" style="width: 380px;" disabled>
              <?php echo $options ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="editForm" class="btn btn-primary">Update</button>
      </div>
    </div>
  </div>
</div>

<div class="empList-container">
        <div class="empList-title mb-3">
            <h1>Pakyawan Employee List</h1>

        </div>


<form method="post" action="">
        <div class="status-filter d-flex flex-row align-items-center pl-5 justify-content-between" >
         
    
        <div class="form-group d-flex flex-row align-items-center" style="width: 50%; " >
          <div style="width: 40%; ">
              <label for="" style="margin-bottom: 1%">Filter Status</label><br>
              <?php
              // Default filter status when the form is first loaded
              $status_filter = "Active";
              if (isset($_POST['status_filter'])) {
                  $status_filter = $_POST['status_filter'];
              }
              ?>
              <select name="status_filter" id="status-filter-select" class="form-control" style="color: black">
                  <option value="all"<?php if ($status_filter === 'all') echo ' selected'; ?>>All</option>
                  <option value="Active"<?php if ($status_filter === 'Active') echo ' selected'; ?>>Active</option>
                  <option value="Inactive"<?php if ($status_filter === 'Inactive') echo ' selected'; ?>>Inactive</option>
              </select>
            </div>
            <button type="submit" class="ml-5 btn btn-primary h-25 mt-4" style="">Go</button>
        </div>
       
       
        <a href="empListForm" class="mr-5 btn btn-primary " style="text-decoration: none;">Create Employee</a>
    </form>
</div>





        
        
        <div style="width: 95%; margin:auto; margin-top: 3em;">
        <table id="order-listing" class="table" style="width: 100%">
        <a href=""></a>
    <thead>
        <th>Employee Code</th>
        <th>Employee ID</th>
        <th>Name</th>
        <th>Classification</th>
        <th>Contact No.</th>
        <th>Status</th>
        <th>Work Type Action</th>  

    </thead>

    <tbody id="myTable">
      <?php
    $conn = mysqli_connect("localhost", "root", "", "hris_db");

        // Check if the form is submitted and the status_filter value is set
        if (isset($_POST['status_filter'])) {
            $status_filter = $_POST['status_filter'];

            // Check if the selected filter is "Active" or "Inactive"
            if ($status_filter === "Active" || $status_filter === "Inactive") {
                $query = "SELECT employee_tb.*, classification_tb.classification FROM employee_tb
                          INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id
                          WHERE employee_tb.classification = 3 AND employee_tb.status = '$status_filter'";
            } else {
                // If the selected filter is "All" or not set, show all employees
                $query = "SELECT employee_tb.*, classification_tb.classification FROM employee_tb
                          INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id
                          WHERE employee_tb.classification = 3";
            }
        } else {
            // Default filter status when the form is first loaded
            $status_filter = "Active";
            $query = "SELECT employee_tb.*, classification_tb.classification FROM employee_tb
                      INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id
                      WHERE employee_tb.classification = 3 AND employee_tb.status = '$status_filter'";
        }

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
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

                echo "<tr class='lh-1'>";
              
                echo "<td style='font-weight: 400;'>".$cmpny_row['company_code_name']."</td>";
                echo "<td style='font-weight: 400;'>" . $row["empid"] . "</td>";
                echo "<td style='font-weight: 400;'>" . $row["fname"] . " " . $row["lname"] . "</td>";
                echo "<td style='font-weight: 400;'>" . $row["classification"] . "</td>";
                echo "<td style='font-weight: 400;'>" . $row["contact"] . "</td>";

                if ($row["status"] == "Active") {
                    echo "<td style='font-weight: 400; color: green;'>" . $row["status"] . "</td>";
                } else {
                    echo "<td style='font-weight: 400; color: red;'>" . $row["status"] . "</td>";
                }

                echo "<td style='font-weight: 400;'><button class='mr-2 viewModal' style='border:none; background-color: inherit' data-bs-toggle='modal' data-bs-target='#viewModal'> <span class='fa-solid fa-eye' style='font-size: 1.3em'></span> </button>";

                echo "<button style='border:none; background-color: inherit;' class='mr-1'> <a href='edit_pakyawan_work?empid=".$row['empid']."' class='fa-solid fa-edit' style='font-size: 1.3em; color: black'></a> </button>";

                echo "</td>";
                echo "</tr>";
            }
        }
?>
    </tbody>
</table>
        </div>
    </div>

    <script>
    function applyFilter() {
        var selectedStatus = document.getElementById("status-filter-select").value;
        var tableRows = document.querySelectorAll("#myTable tr");

        for (var i = 0; i < tableRows.length; i++) {
            var row = tableRows[i];
            row.style.display = "table-row";

            if (selectedStatus !== "all") {
                var statusCell = row.cells[4]; // Assuming the status cell is in the fifth column
                var statusValue = statusCell.textContent.trim(); // Get the actual status value without extra spaces

                if (statusValue !== selectedStatus) {
                    row.style.display = "none";
                }
            }
        }
    }
</script>







<!-- view modal -->
    <script>
$(document).ready(function() {
  $('.viewModal').on('click', function() {
    $('#viewModal').modal('show');

    $tr = $(this).closest('tr');
var empid = $tr.find("td:eq(1)").text(); // Index 1 refers to the second <td> element


    $.ajax({
      type: 'POST',
      url: 'Data Controller/Pakyawan/view_pakyawan_piece_rate.php',
      data: {empid: empid},
      success: function(response) {
        console.log(response); // Check the response in the browser console
        var unitDetails = JSON.parse(response);
        var unitDetailsBody = $('#unitDetailsBody');
        unitDetailsBody.empty();
        unitDetails.forEach(function(details) {
          var row = $('<tr>');
          row.append($('<td style="font-weight:400">').text(details.unit_type));
          row.append($('<td style="font-weight:400">').text(details.unit_quantity));
          row.append($('<td style="font-weight:400">').text('₱' + details.unit_rate));
          unitDetailsBody.append(row);
        });
      },
      error: function(xhr, status, error) {
        console.log(xhr.responseText); // Log any error messages to the console
      }
    });

    $('#empid').val(empid);
  });
});

        </script>


<!-- <script>
    function sendData() {
  // Retrieve the input value
  var inputValue = document.getElementByClass("pakyawan_empid").value;

  // Create a FormData object and append the input value
  var formData = new FormData();
  formData.append("inputValue", inputValue);

  // Send the data using fetch
  fetch("Data Controller/Pakyawan/view_pakyawan_piece_rate.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    // Handle the response from the PHP file
    console.log(response);
  })
  .catch(error => {
    // Handle any errors
    console.error(error);
  });
}
</script> -->


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