

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
        }else {
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

</style>

    <div class="empList-container">
        <div class="empList-title">
            <h1>Employee List</h1>
        </div>
        <div class="empList-create-search">
            <a href="empListForm.php" class="empList-btn" title="Create New">Create New</a>
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
        </style>
        
        
        <div style="width: 95%; margin:auto; margin-top: 30px;">
        <table id="order-listing" class="table" style="width: 100%">
    <thead>
        <th>Employee ID</th>
        <th>Name</th>
        <th class="email-col">Email</th>
        <th>Classification</th>
        <th>Contact No.</th>
        <th>Employee Type</th>
        <th>Employee Status</th>
        <th>Employee Details</th>
        <th>Action</th>
    </thead>
    <tbody id="myTable">
        <?php
        $conn = mysqli_connect("localhost", "root", "", "hris_db");
        $stmt = "SELECT * FROM employee_tb
                 INNER JOIN classification_tb
                 ON employee_tb.classification = classification_tb.id 
                 WHERE employee_tb.classification != 3";
        $result = $conn->query($stmt);

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
                echo "<td style='font-weight: 400;'>";

                $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                echo $cmpny_code !== null ? $cmpny_code . " - " . $row["empid"] : $row["empid"];

                echo "</td>";
                echo "<td style='font-weight: 400;'>" . $row["fname"] . " " . $row["lname"] . "</td>";
                echo "<td style='font-weight: 400;' class='email-col'>" . $row["email"] . "</td>";
                echo "<td style='font-weight: 400;'>" . $row["classification"] . "</td>";
                echo "<td style='font-weight: 400;'>" . $row["contact"] . "</td>";
                echo "<td style='font-weight: 400;'>" . $row["role"] . " </td>";

                if ($row["status"] == "Active") {
                    echo "<td style='font-weight: 400; color: green;'>" . $row["status"] . "</td>";
                } else {
                    echo "<td style='font-weight: 400; color: red;'>" . $row["status"] . "</td>";
                }

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
                    'bank_number' => 'Bank Number'
                );

                // Check if any of the columns (except 'user_profile') have null, empty, or 0 values
                $incomplete = false;
                $details = '';
                foreach ($row as $key => $value) {
                    if ($key !== 'user_profile' && (empty($value) || is_null($value) || $value === "0" || $value === 0)) {
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
                echo "<button class='tb-view' style='text-decoration:none; border:none;background-color:inherit; outline:none;'><a href='editempListForm.php?empid=$row[empid]' style='color:gray; text-decoration:none;'>View</a></button>";
                echo "</td>";
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