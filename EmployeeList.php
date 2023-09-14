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


    $sqla = "SELECT * FROM employee_tb";
    $resultaa = mysqli_query($conn, $sqla);

    $rowaa = mysqli_fetch_assoc($resultaa);

    $status = $rowaa['status'];
    

 
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

    /* .toggle-circle {
      width: 1.3em;
      height: 1.3em;
      border-radius: 50%;
      border: 2px solid #ccc;
      cursor: pointer;
      background-color: <?= $status === 'Inactive' ? 'red' : 'green' ?>;
      transition: background-color 0.3s;
    } */
</style>

    <div class="empList-container">
        <div class="empList-title">
            <h1>Employee List</h1>
        </div>
        <div class="empList-create-search" style="">
            
    

        <form method="post" action="">
            <div class="status-filter d-flex flex-row align-items-center pl-5 justify-content-between" >
            
        
            <div class="form-group d-flex flex-row align-items-center" style="width: 100%; " >
            <div style="width:100%; ">
                <label for="" style="margin-bottom: 1%; font-size: 1em">Filter Status</label><br>
                <?php
                // Default filter status when the form is first loaded
                $status_filter = "Active";
                if (isset($_POST['status_filter'])) {
                    $status_filter = $_POST['status_filter'];
                }
                ?>
                <select name="status_filter" id="status-filter-select" class="form-control" style="color: black">
                    <option value="All"<?php if ($status_filter === 'All') echo ' selected'; ?>>All</option>
                    <option value="Active"<?php if ($status_filter === 'Active') echo ' selected'; ?>>Active</option>
                    <option value="Inactive"<?php if ($status_filter === 'Inactive') echo ' selected'; ?>>Inactive</option>
                </select>
                </div>
                <button type="submit" class="ml-5 btn btn-primary h-25 mt-4" style="">Go</button>
            </div>
        
        </form>
</div>
    
            <a href="empListForm.php" class="empList-btn mr-5" title="Create New">Create New</a>
        </div>
        

        <style>
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

            

            /* .toggle-circle {
      width: 1.3em;
      height: 1.3em;
      border-radius: 50%;
      border: 2px solid #ccc;
      cursor: pointer;
      background-color: <?= $status === 'Inactive' ? 'red' : 'green' ?>;
      transition: background-color 0.3s;
    } */
        </style>
<!-- Modal sa file ng employee -->
<div class="modal fade" id="empFile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Employee Files</h1>
            <div class="d-flex flex-row" style="margin-left: 10px;">
                <form id="myForm" action="Data Controller/Employee List/empfile.php" method="POST" enctype="multipart/form-data">  
                <input type="hidden" id="employeID" name="empoyeeId">
                <input type="file" class="" name="multipleFile[]" id="formFileMultiple" accept="*/*" multiple>
                <button type="submit" name="btn_save" style="width: 80px; background-color: blue; color: white; border: none; border-radius: 4px;">Save</button>
              </form>    
            </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="emp-modal-body">

      </div>
    </div>
  </div>
</div>

        
        <div style="width: 95%; margin:auto; margin-top: 30px;">
            <table id="order-listing" class="table" style="width: 100%">
                            <thead>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Classification</th>
                            <th>Position</th>
                            <th>Type</th>
                            <th>Documents</th>
                            <th>Status</th>
                            <th>Details</th>
                            <th>Action</th>
                            <th class="d-none">empid</th>
                        </thead>
                        <tbody id="myTable">
                            <?php
                            include 'config.php';

                        // Check if the form is submitted and the status_filter value is set
                        if (isset($_POST['status_filter'])) {
                            $status_filter = $_POST['status_filter'];

                            // Check if the selected filter is "Active" or "Inactive"
                            if ($status_filter === "Active" || $status_filter === "Inactive") {
                                $query = "SELECT employee_tb.*, dept_tb.col_ID, dept_tb.col_deptname, positionn_tb.id, positionn_tb.position, classification_tb.classification FROM employee_tb
                                            INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                            INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                        INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id
                                        AND employee_tb.status = '$status_filter'";
                            } elseif($status_filter === "All") {
                                // If the selected filter is "All" or not set, show all employees
                                $query = "SELECT * FROM employee_tb
                                            INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                            INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                        INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id";
                            }
                        } else {
                            // Default filter status when the form is first loaded
                            $status_filter = "Active";
                            $query = "SELECT employee_tb.*, dept_tb.col_ID, dept_tb.col_deptname, positionn_tb.id, positionn_tb.position, classification_tb.classification FROM employee_tb
                                            INNER JOIN positionn_tb ON employee_tb.empposition = positionn_tb.id
                                            INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID
                                    INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id
                                    AND employee_tb.status = '$status_filter'";
                        }
                    
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {

                                
                                    $cmpny_empid = $row['empid'];
                                    
                                    include 'config.php';
                                    
                                    $sqls = "SELECT * FROM employee_tb WHERE empid = $cmpny_empid
                                            ";
                                    $resulte = mysqli_query($conn, $sqls);

                                    $classification = $row['classification'];

                                    $position = $row['position'];

                                    $department = $row['col_deptname'];

                                    $rowe = mysqli_fetch_assoc($resulte);
                                    
                                    $status = $rowe['status'];

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

                                            // echo $cmpny_row['empid'];
                                

                                    echo "<tr class='lh-1'>";
                                    echo "<td style='font-weight: 400;'>";

                                    $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                    echo $cmpny_code !== null ? $cmpny_code . " - " . $row["empid"] : $row["empid"];

                                    echo "</td>";
                                    echo "<td style='font-weight: 400;'>" . $row["fname"] . " " . $row["lname"] . "</td>";
                                    echo "<td style='font-weight: 400;'>"; ?> <?php if( $classification == "Pakyawan") { echo ""; }else{  echo $department; } ?> <?php echo " </td>";
                                    echo "<td style='font-weight: 400;'>" . $row["classification"] . "</td>";
                                    echo "<td style='font-weight: 400;'>"; ?> <?php if( $classification == "Pakyawan") { echo ""; }else{  echo $position; } ?> <?php echo " </td>";
                                    echo "<td style='font-weight: 400;'>" . $row["role"] . " </td>";
                                    echo "<td>" . '<button class="btn btn-outline-danger btn-icon-text employeeFiles" data-bs-toggle="modal" data-bs-target="#empFile" data-emp-id="' . $cmpny_empid . '"><i class="ti-upload btn-icon-prepend"></i>file</button>' . "</td>";
                                    echo "<td>";
                                    ?> <?php
                                    if ($row['status'] == 'Active') {
                                        echo "<div class='form-check form-switch'>
                                                <input class='form-check-input ml-3 sched-update' type='checkbox' name='status' data-bs-toggle='modal' data-bs-target='#schedUpdate' id='sched-update' ";
                                        if ($row['status'] == 'Active') {
                                            echo "checked";
                                        }
                                        echo " data-active='1' style='background-color: green; border:black'> <!-- Add data-active attribute for tracking -->
                                                <span class='d-none'>" . $row['status'] . "</span>
                                            </div>
                                        </td>";
                                    } else {
                                        echo "<div class='form-check form-switch'>
                                                <input class='form-check-input ml-3 sched-update' type='checkbox' name='status' data-bs-toggle='modal' data-bs-target='#schedUpdate' id='sched-update' ";
                                        if ($row['status'] == 'Inactive') {
                                            echo "checked";
                                        }
                                        echo " data-active='1' style='background-color: red; border:black; rotate: 180deg'> <!-- Add data-active attribute for tracking -->
                                                <span class='d-none'>" . $row['status'] . "</span>
                                            </div>
                                        </td>";
                                            }


                                        ?> <?php
                                    
                                    // Custom mapping for data column names
                                    $columnMapping = array(
                                        'empsss' => 'SSS',
                                        'emptin' => 'TIN',
                                        'emppagibig' => 'Pagibig',
                                        'empphilhealth' => 'Philhealth',
                                        'emptranspo' => 'Transportation',
                                        'empmeal' => 'Meal',
                                        'empinternet' => 'Internet',
                                        'sss_amount' => 'SSS Amount',
                                        'tin_amount' => 'TIN Amount',
                                        'pagibig_amount' => 'Pagibig Amount',
                                        'philhealth_amount' => 'Philhealth Amount',
                                        'bank_name' => 'Bank Name',
                                        'bank_number' => 'Bank Number',
                                        'emp_img_url' => 'Image'
                                        
                                    );

                                    // Check if any of the columns (except 'user_profile') have null, empty, or 0 values
                                    $incomplete = false;
                                    $details = '';
                                    foreach ($row as $key => $value) {
                                        if (($key !== 'user_profile' && $key !== 'work_frequency') && (empty($value) || is_null($value) || $value === "0" || $value === 0)) {
                                            // Map the original column name to the new name using the $columnMapping array
                                            $columnDisplayName = isset($columnMapping[$key]) ? $columnMapping[$key] : $key;
                                            $details .= "<p><strong>$columnDisplayName:</strong> No value</p>";
                                            $incomplete = true;
                                        }
                                    }

                                    // Display the 'Employee Details' column based on the check
                                    if ($incomplete) {
                                        echo "<td style='font-weight: 400; color: red;'>
                                            <button class='btn-incomplete' style='border: none; text-decoration-line: underline; background-color: inherit; color: red;' data-details='$details'>Incomplete</button>
                                        </td>";
                                    } else {
                                        echo "<td style='font-weight: 400; color: blue;'>Complete</td>";
                                    }

                                    echo "<td class='tbody-btn' style='width:120px;'>";

                                    $empid = $row['empid'];
                                    $classification = $row['classification'];

                                    if ($classification != 'Pakyawan') {
                                        $redirectUrl = "editempListForm.php?empid=$empid&classification=$classification";
                                    } else {
                                        $redirectUrl = "edit_pakyawan_work.php?empid=$empid&classification=$classification";
                                    }

                                    echo "<button class='tb-view' style='text-decoration:none; border:none;background-color:inherit; outline:none;'>
                                    <a href='$redirectUrl' style='color:gray; text-decoration:none;'>View</a>
                                    </button>";

                                    echo "</td>";
                                    echo "<td class='d-none'>".$cmpny_empid."</td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Handle the case when there are no rows in the result
                                echo "<tr><td colspan='9' style='font-weight: 400; text-align: center;'>No employees found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>

        </div>
    </div>

     <!-- Modal for displaying incomplete details -->
     <div class="modal fade" id="incompleteModal" tabindex="-1" aria-labelledby="incompleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="incompleteModalLabel">Incomplete Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="incompleteDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for status -->
    <div class="modal fade" id="schedUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Employee Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center align-items-center">
                    <h4 class="fs-4">Are you sure you want to change it?</h4>
                </div>
                <form action="actions/Employee List/empStatus.php" method="POST">
                        <input class="d-none" type="text" name="empid" id="employID">
                        <input class="d-none" type="text" name="status" id="statuses">
                    
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="updatedata" class="btn btn-primary">Submit</button>
            </div>
            </div>
        </div>
    </div>
    </form>

<script>
    document.querySelectorAll('.sched-update').forEach(function (checkbox) {
        checkbox.addEventListener('click', function (event) {
            if (event.target.getAttribute('data-active') === '1') {
                event.preventDefault(); // Prevent the checkbox from toggling
                // Add code here to show the modal and handle confirmation
                var employID = event.target.closest('td').querySelector('[name="empid"]').value;
                var statuses = event.target.closest('td').querySelector('[name="status"]').value;
                document.getElementById('employID').value = employID;
                document.getElementById('statuses').value = statuses;
                var modal = new bootstrap.Modal(document.getElementById('schedUpdate'));
                modal.show();
            }
        });
    });
</script>

<script> 
$(document).ready(function() {
    $('.sched-update').on('click', function() {
        $('#schedUpdate').modal('show');
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function() {
            return $(this).text();
        }).get();

        console.log(data);
        //id_colId
        $('#employID').val(data[10].trim());
        $('#statuses').val(data[7].trim()); // Remove spaces using trim()
    });
});
</script>


<!--para sa modal ng pagclick sa file-->
<script> 
$(document).ready(function() {
    $('.employeeFiles').on('click', function() {
        $('#empFile').modal('show');
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function() {
            return $(this).text();
        }).get();

        console.log(data);
        $('#employeID').val(data[10]);
    });
});
</script>

<!--para maipasa ang data sa modal kapag na-click ang file button-->
<script>
$(document).ready(function () {
    $('.employeeFiles').click(function () {
        var employeeId = $(this).data('emp-id'); // Retrieve the empid value

        $.ajax({
            url: 'actions/Employee List/emplist_data.php',
            method: 'POST',
            data: { empId: employeeId }, // Pass empId in the data object
            success: function (response) {
                $('#emp-modal-body').html(response);
            }
        });
    });
});

</script>


    
    <script>
    function applyFilter() {
        var selectedStatus = document.getElementById("status-filter-select").value;
        var tableRows = document.querySelectorAll("#myTable tr");

        for (var i = 0; i < tableRows.length; i++) {
            var row = tableRows[i];
            row.style.display = "table-row";

            if (selectedStatus !== "all") {
                var statusCell = row.cells[5]; // Assuming the status cell is in the fifth column
                var statusValue = statusCell.textContent.trim(); // Get the actual status value without extra spaces

                if (statusValue !== selectedStatus) {
                    row.style.display = "none";
                }
            }
        }
    }
</script>


       <!-- Add the Bootstrap 5 JS and jQuery (required by Bootstrap) links before closing the <body> tag -->
       <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script>
        // Add an event listener for the 'Incomplete' button click
        $(document).on('click', '.btn-incomplete', function () {
            var details = $(this).data('details');

            // Update the modal content with the incomplete details
            $('#incompleteDetails').html(details);

            // Show the modal
            $('#incompleteModal').modal('show');
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