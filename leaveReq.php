<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: login.php"); 
} else {
    // Check if the user's role is not "admin"
    if($_SESSION['role'] != 'admin'){
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

    <link rel="stylesheet" href="css/leavereq.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/leaveReqResponsive.css">
    <title>Leave Request</title>
</head>
<body>

<header>
    <?php include 'header.php';
    ?>
</header>

    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">

                <div class="row">
                        <div class="col-6">
                            <p style="font-size: 25px; padding: 10px">Leave Request</p>
                        </div>
                 </div>  

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

                <!----------------------------------Syntax for Dropdown button------------------------------------------>
                <div class="leave_panel">
                    <div class="leave_content">
                        <p class="leave_status">Status</p>
                            <?php
                                 $status = isset($_GET['col_status']) ? $_GET['col_status'] : '';
                            ?>
                        <select class='dropdown_leave' name="col_status" id="select_status" aria-label=".form-select-sm example">
                                    <option value="All Status" <?php if($status =='All Status') echo 'selected';?> default>All Status</option>
                                    <option value="Pending" <?php if($status =='Pending') echo 'selected';?>>Pending</option>
                                    <option value="Approved" <?php if($status == 'Approved') echo 'selected';?>>Approved</option>
                                    <option value="Rejected" <?php if($status == 'Rejected') echo 'selected';?>>Rejected</option>
                                    <option value="Rejected" <?php if($status == 'Cancelled') echo 'selected';?>>Cancelled</option>
                        </select>
                </div>

                    <div class="leave_content">
                        <p class="leave_status">Date From</p>
                            <?php
                                $dateFrom = isset($_GET['col_strDate']) ? ($_GET['col_strDate']) : '';
                            ?>
                        <input class="dropdown_leave" type="date" name="datestart" id="id_inpt_strdate" value="<?php echo $dateFrom; ?>" required>
                    </div>

                    <div class="leave_content">
                         <div class="notif">
                          <p class="leave_status">Date To</p>
                          <?php
                                $dateTo = isset($_GET['col_endDate']) ? ($_GET['col_endDate']) : '';
                            ?>
                     </div>

                    <input class="dropdown_leave" type="date" name="col_endDate" id="id_inpt_enddate" value="<?php echo $dateTo; ?>" required>
                    </div>
                    <button class="leave_filter" id="id_btngo" onclick="filterLeave()">&rarr; Apply Filter</button>
                </div>
                <!------------------------------End Syntax for Dropdown button------------------------------------------------->

                <form action="actions/Leave Request/action.php" method="post">
                    <input id="id_ID_tb" name="name_ID_tb" type="text" style="display: none;">  <!--received the id of selected data in datatble and pass to calss action-->   
                       <input id="id_IDemp_tb" name="name_empID_tb" type="text" style="display: none;"> <!--received the employee_id of selected data in datatble and pass to calss action-->  
                            <div class="table-responsive" id="table-responsiveness">
                               <table id="order-listing" class="table mt-2">
                                    <thead>
                                        <tr>
                                            <th style="display: none;" scope="col">ID</th>
                                            <th scope="col">Employee ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Leave Type</th>
                                            <th style="col" >Credits</th>
                                            <th scope="col">Leave Date</th>
                                            <th scope="col">Leave End</th>
                                            <th scope="col">Date Filled</th>
                                            <th scope="col">Approver</th>
                                            <th scope="col">File Attachment</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                        <tbody id="table-body">
                                        <?php
                                            include 'config.php';

                                            $status = $_GET['col_status'] ?? '';
                                            $dateFrom = $_GET['col_strDate'] ?? '';
                                            $dateTo = $_GET['col_endDate'] ?? '';

                                            $sql = "SELECT
                                                        applyleave_tb.col_ID,
                                                        applyleave_tb.col_req_emp,
                                                        CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name,
                                                        applyleave_tb.col_LeaveType,
                                                        applyleave_tb.col_credit,
                                                        applyleave_tb.col_file,
                                                        applyleave_tb.col_strDate,
                                                        applyleave_tb.col_endDate,
                                                        applyleave_tb._datetime,
                                                        applyleave_tb.col_dt_action,
                                                        applyleave_tb.col_approver,
                                                        applyleave_tb.col_status
                                                    FROM
                                                        applyleave_tb
                                                    INNER JOIN employee_tb ON applyleave_tb.col_req_emp = employee_tb.empid";
                                                        if (!empty($status) && $status != 'All Status') {
                                                            $sql .= " WHERE applyleave_tb.col_status = '$status'";
                                                        }

                                                        if (!empty($dateFrom) && !empty($dateTo)) {
                                                            if ($status != 'All Status') {
                                                                $sql .= " AND";
                                                            } else {
                                                                $sql .= " WHERE";
                                                            }
                                                            $sql .= " (applyleave_tb.col_strDate >= '$dateFrom' AND applyleave_tb.col_endDate <= '$dateTo')";
                                                        } elseif (!empty($dateFrom)) {
                                                            if ($status != 'All Status') {
                                                                $sql .= " AND";
                                                            } else {
                                                                $sql .= " WHERE";
                                                            }
                                                            $sql .= " applyleave_tb.col_strDate = '$dateFrom'";
                                                        } elseif (!empty($dateTo)) {
                                                            if ($status != 'All Status') {
                                                                $sql .= " AND";
                                                            } else {
                                                                $sql .= " WHERE";
                                                            }
                                                            $sql .= " applyleave_tb.col_endDate = '$dateTo'";
                                                        }
                                            $sql .= " ORDER BY applyleave_tb._datetime DESC";

                                            $result = $conn->query($sql);

                                                    //read data
                                                    while($row = $result->fetch_assoc()){
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
                                        


                                                        $approver = $row['col_approver'];
                                                        if ($approver === ''){
                                                            $approver_fullname = 'none';
                                                        }
                                                        else{
                                                            $result_approver = mysqli_query($conn, " SELECT
                                                            *  
                                                        FROM
                                                            employee_tb
                                                        WHERE empid = $approver");
                                                        if(mysqli_num_rows($result_approver) > 0) {
                                                            $row_approver = mysqli_fetch_assoc($result_approver);
                                                            //echo $row__leaveINFO['col_vctionCrdt'];
                                                            $approver_fullname = $row_approver['fname'] . " " . $row_approver['lname'];
                                                        } else {
                                                            $approver_fullname = 'Something Went Wrong';
                                                        } 
                                                        }
                                                        echo "<tr>
                                                                <td style='display: none;'>" . $row['col_ID'] . "</td>
                                                                <td>";
                                                                $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                                                $empid = $row['col_req_emp'];
                                                                if (!empty($cmpny_code)) {
                                                                    echo $cmpny_code . " - " . $empid;
                                                                } else {
                                                                    echo $empid;
                                                                }
                                                                echo "</td>
                                                                <td scope='row'>
                                                                    <button type='submit' name='view_data' class='viewbtn' title='View' style='border: none; background: transparent;
                                                                        text-transform: capitalize; text-decoration: underline; cursor: pointer; color: #787BDB; font-size: 19px;'>
                                                                        " . $row['full_name'] . "
                                                                    </button>
                                                                </td>
                                                                <td>" . $row['col_LeaveType'] . "</td>
                                                                <td>" . $row['col_credit'] . "</td>
                                                                <td>" . $row['col_strDate'] . "</td>
                                                                <td>" . $row['col_endDate'] . "</td>
                                                                <td>" . $row['_datetime'] . "</td>
                                                                <td>" . $approver_fullname . "</td>
                                                                <td>";

                                                                if($row['col_file'] === "") {
                                                                    echo "No file attached";
                                                                } else {
                                                                    echo "<div class='row'>
                                                                        <div class='col-12'>
                                                                            <button type='button' class='border-0 btn_view_file' title='View' data-bs-toggle='modal' data-bs-target='#id_view_file' style='background: transparent;'>
                                                                                <img src='icons/view_file.png' alt='...'>
                                                                            </button>
                                                                        </div>
                                                                    </div>";
                                                                }
                                                                
                                                                echo "</td>
                                                                
                                                                <td" . ($row['col_status'] === 'Approved' ? " style='color: blue;'" :
                                                                            ($row['col_status'] === 'Rejected' ? " style='color: red;'" :
                                                                                ($row['col_status'] === 'Cancelled' ? " style='color: orange;'" :
                                                                                    ($row['col_status'] === 'Pending' ? " style='color: green;'" :
                                                                                    "") 
                                                                                )
                                                                            )
                                                                        ) . ">" . $row['col_status'] . "
                                                                </td>
                                                            </tr>";
                                                    }
                                                ?>  
                                        </tbody>   
                                </table>
                            </div>
                        </form>
                
                        <div class="modal fade" id="id_view_file" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form action="leave_req_fileReason.php" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">View File</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                            <input name="name_ID_tb" id="id_table" type="text" style="display:none;">
                                            <input name="name_empID_tb" id="id_EMPID" type="text" style="display:none;">
                                            <h3> Are you sure you want to view the valid reason uploaded as file?</h3>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" name="btn_yes_modal" class="btn btn-primary btn-lg">YES</button>
                                            </div>
                                        </div> <!---- Modal-content end---->
                                </form>    
                            </div><!---- Modal-dialog end---->
                        </div> <!---- Modal end---->
                    <!---- Modal for View button for file reason END---->
          </div>
       </div>
    </div>
</div>

<!---------------------Script sa pagfilter ng data----------------------------------->
<script>
    function filterLeave() {
        var status = document.getElementById('select_status').value;
        var dateFrom = document.getElementById('id_inpt_strdate').value;
        var dateTo = document.getElementById('id_inpt_enddate').value;

        // Build the URL with selected filters
        var url = 'leaveReq.php?col_status=' + status + '&col_strDate=' + dateFrom + '&col_endDate=' + dateTo;
        window.location.href = url;
    }
</script>
<!---------------------Script sa pagfilter ng data----------------------------------->


                
<script>
  document.getElementById('formFileMultiple').addEventListener('change', function(event) {
    var fileInput = event.target;
    var file = fileInput.files[0];
    if (file.type !== 'application/pdf') {
      alert('Please select a PDF file.');
      fileInput.value = ''; // Clear the file input field
    }
  });
</script>

                <!---------------------------break --------------------------->
<script> //FOR VIEW TRANSFER 
            $(document).ready(function(){
                                    $('.viewbtn').on('click', function(){
                                        $('#id_modal_empreqLeave').modal('show');
                                        $tr = $(this).closest('tr');

                                        var data = $tr.children("td").map(function () {
                                            return $(this).text();
                                        }).get();

                                        console.log(data);
                                        //id_colId
                                        $('#id_ID_tb').val(data[0]);
                                        $('#id_IDemp_tb').val(data[1]);
                                    });
                                });
            //FOR VIEW TRANSFER MODAL END
</script>
                <!---------------------------break --------------------------->

                <!---------------------------break --------------------------->
<script> //FOR VIEW FILE REASON  modal
            $(document).ready(function(){
                                    $('.btn_view_file').on('click', function(){
                                        $('#id_view_file').modal('show');
                                        $tr = $(this).closest('tr');

                                        var data = $tr.children("td").map(function () {
                                            return $(this).text();
                                        }).get();

                                        console.log(data);
                                        
                                        $('#id_table').val(data[0]);
                                        $('#id_EMPID').val(data[2]);
                                    });
                                });
            //FOR VIEW FILE REASON modal END
</script>
                <!---------------------------break --------------------------->


<script>
//     $(document).ready(function() {
//     // listen to changes on the selection box
//     $('#limit-select').change(function() {
//         // get the selected value
//         var limit = $(this).val();

//         // get all table rows
//         var rows = $('#table-body tr');

//         // hide all rows
//         rows.hide();

//         // show only the first "limit" number of rows
//         rows.slice(0, limit).show();
//     });
// });

// </script>


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