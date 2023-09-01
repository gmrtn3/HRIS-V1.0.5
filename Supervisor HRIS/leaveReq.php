<?php
session_start();
// if(!isset($_SESSION['username'])){
//     header("Location: ../login.php"); 
// } else {
//     // Check if the user's role is not "Supervisor"
//     if($_SESSION['role'] != 'Supervisor'){
//         // If the user's role is not "Supervisor", log them out and redirect to the logout page
//         session_unset();
//         session_destroy();
//         header("Location: logout.php");
//         exit();
//     }
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<!-- <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap4.min.css"> -->

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


<link rel="stylesheet" href="css/try.css">
<link rel="stylesheet" href="css/leavereq.css"/>
<link rel="stylesheet" href="css/styles.css">

    <title>Leave Request</title>
</head>
<body>

<header>
    <?php include 'header.php';
    ?>
</header>

<style>
    
    html{
        background-color: #f4f4f4 !important;
        overflow: hidden;
    }
    

    body{
        overflow: hidden;
        background-color: #f4f4f4;
    }

    .pagination{
        margin-right: 63px !important;
        
    }
    .sorting_asc{
        color: black !important;
    }

    .pagination li a{
        color: #c37700;
    }

    .page-item.active .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button .page-link, .jsgrid .jsgrid-pager .active.jsgrid-pager-page .page-link, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-nav-button a, .jsgrid .jsgrid-pager .jsgrid-pager-nav-button .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-nav-button a, .page-item.active .jsgrid .jsgrid-pager .jsgrid-pager-page a, .jsgrid .jsgrid-pager .jsgrid-pager-page .page-item.active a, .jsgrid .jsgrid-pager .active.jsgrid-pager-page a {
        z-index: 3;
        color: #fff;
        background-color: #000;
        margin-top: 20px;
    }

    
    
    #order-listing_next{
        margin-right: 20px !important;
        margin-bottom: -17px !important;

    }

    thead th:nth-child(1){
    width: 8% !important;
   }

   tr td:nth-child(1){
    width: 8% !important;
   }

   thead th:nth-child(2){
    width: 6.5% !important;
   }

   tr td:nth-child(2){
    width: 6.5% !important;
   }
   
   thead th:nth-child(3){
    width: 15% !important;
   }

   tr td:nth-child(3){
    width: 15% !important;
   }

   thead th:nth-child(4){
    width: 10% !important;
   }

   tr td:nth-child(4){
    width: 10% !important;
   }
   
   thead th:nth-child(5){
    width: 8% !important;
   }

   tr td:nth-child(5){
    width: 8% !important;
   }
   thead th:nth-child(6){
    width: 8% !important;
   }

   tr td:nth-child(6){
    width: 8% !important;
   }

   thead th:nth-child(7){
    width: 8% !important;
   }

   tr td:nth-child(7){
    width: 8% !important;
   }

    thead th:nth-child(8){
    width: 8% !important;
   }

   tr td:nth-child(8){
    width: 8% !important;
   }
   thead th:nth-child(9){
    width: 8% !important;
   }

   tr td:nth-child(9){
    width: 8% !important;
   }
   thead th:nth-child(10){
    width: 8% !important;
   }

   tr td:nth-child(10){
    width: 8% !important;
   }

   table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
    max-height: 100%;
    height: 320px;
    
    /* border: black 1px solid; */
                
    }
         


</style>


    <div class="container-xxl mt-5 ">
        <div class="">

            <div class="card border-light">
                
                <div class="card-body">
                <h2>Leave Request</h2>
                    <div class="row">
                        <div class="col-6">
                            <?php
                                 $status = isset($_GET['col_status']) ? $_GET['col_status'] : '';
                            ?>
                            <div class="mb-3">
                                <label for="select_status" class="form-label">Select Status</label>
                                <select class="form-select form-select-m" name="col_status" id="select_status" aria-label=".form-select-sm example">
                                    <option value="All Status" <?php if($status =='All Status') echo 'selected';?> default>All Status</option>
                                    <option value="Pending" <?php if($status =='Pending') echo 'selected';?>>Pending</option>
                                    <option value="Approved" <?php if($status == 'Approved') echo 'selected';?>>Approved</option>
                                    <option value="Rejected" <?php if($status == 'Rejected') echo 'selected';?>>Rejected</option>
                                    <option value="Rejected" <?php if($status == 'Cancelled') echo 'selected';?>>Cancelled</option>
                                </select>
                            </div> <!-- First mb-3 end-->
                          
                            

                        </div> <!-- first col- 6 end-->
                        <div class="col-6">
                        <div class="date_range">
                            <label for="id_strdate" class="form-label">Date Range :</label>
                            <div class="mb-1">
                                    <?php
                                        $dateFrom = isset($_GET['col_strDate']) ? ($_GET['col_strDate']) : '';
                                    ?>
                                <form class="form-floating">
                                    <input type="date" class="form-control" name="datestart" id="id_inpt_strdate" value="<?php echo $dateFrom; ?>">
                                    <label for="id_inpt_strdate">Start Date :</label>
                                </form>
                            </div> <!-- Second mb-3 end-->
                        </div> <!-- second col- 6 end-->
                    </div><!--row end-->
                    
            <!----------------------------------Break------------------------------------->

                    <div class="row">
                        
                        <div class="col-6">                         
                            <!-- for employee dropdown tinangal ko -->
                        </div> <!-- first col- 6 end-->
                        <div class="col-6">
                        <div class="date_button mb-1 mt-3">
                            <?php
                                $dateTo = isset($_GET['col_endDate']) ? ($_GET['col_endDate']) : '';
                            ?>
                            <form class="form-floating">
                                <input type="date" class="form-control" name="col_endDate" id="id_inpt_enddate" value="<?php echo $dateTo; ?>">
                                <label for="id_inpt_enddate">End Date :</label>
                            </form>
                            <div class="forbutton">
                            <button class="btn_go" id="id_btngo" onclick="filterLeave()">Apply Filter</button>
                         </div> <!-- forbutton -->
                        </div> <!-- Second mb-3 end-->
                        </div> <!-- second col- 6 end-->
                     </div> <!-- date range end -->
                    </div><!--row end-->

            <!----------------------------------Break------------------------------------->

            <!----------------------------------Break------------------------------------->


             <!-- ------------------para sa message na sucessful START -------------------->
        <?php

            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                '.$msg.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }


            ?>
            <!-------------------- para sa message na sucessful ENd --------------------->


            <!-- ------------------para sa message na error START -------------------->
            <?php
            if (isset($_GET['error'])) {
                $error = $_GET['error'];
                echo '<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                '.$error.'
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            }

?>
<!-------------------- para sa message na error ENd --------------------->

        <!----------------------------------Break------------------------------------->   
        
        
                    <!-- <div id="data_table" class="table table-responsive "  > -->
                        <form action="actions/Leave Request/action.php" method="post">
                        <input id="id_ID_tb" name="name_ID_tb" type="hidden">  <!--received the id of selected data in datatble and pass to calss action-->   
                        <input id="id_IDemp_tb" name="name_empID_tb" type="hidden"> <!--received the employee_id of selected data in datatble and pass to calss action-->  
                        <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                                    <table id="order-listing" class="table" style="width: 100%">

                                    <thead>
                                        <tr>
                                            <th scope="col" style = "display: none;">ID</th>
                                            <th scope="col">Employee ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Leave Type</th>
                                            <th scope="col" >Credits</th>
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
                                                    $aprrover_ID = $_SESSION['empid'];

                                                    $status = $_GET['col_status'] ?? '';
                                                    $dateFrom = $_GET['col_strDate'] ?? '';
                                                    $dateTo = $_GET['col_endDate'] ?? '';

                                                    if (isset($_GET['id'])) {
                                                        $employee_id = $_GET['id'];
                                                            $sql = "SELECT
                                                                applyleave_tb.col_ID,
                                                                applyleave_tb.`col_req_emp`,
                                                                CONCAT(
                                                                    employee_tb.`fname`,
                                                                    ' ',
                                                                    employee_tb.`lname`
                                                                ) AS `full_name`,
                                                                applyleave_tb.`col_LeaveType`,
                                                                applyleave_tb.`col_credit`,
                                                                applyleave_tb.`col_strDate`,
                                                                applyleave_tb.`col_endDate`,
                                                                applyleave_tb.`_datetime`,
                                                                applyleave_tb.`col_file`,
                                                                applyleave_tb.`col_dt_action`,
                                                                applyleave_tb.`col_approver`,
                                                                applyleave_tb.`col_status`
                                                            FROM
                                                                applyleave_tb
                                                            INNER JOIN employee_tb ON applyleave_tb.col_req_emp = employee_tb.empid
                                                            INNER JOIN approver_tb ON approver_tb.empid = applyleave_tb.col_req_emp
                                                            WHERE
                                                                approver_tb.approver_empid = '$aprrover_ID' AND applyleave_tb.col_ID = '$employee_id'";
                                                                if (!empty($status) && $status != 'All Status') {
                                                                    $sql .= " AND applyleave_tb.col_status = '$status'";
                                                                }

                                                                if (!empty($dateFrom) && !empty($dateTo)) {
                                                                    $sql .= " AND (applyleave_tb.col_strDate >= '$dateFrom' AND applyleave_tb.col_endDate <= '$dateTo')";
                                                                } elseif (!empty($dateFrom)) {
                                                                    $sql .= " AND applyleave_tb.col_strDate = '$dateFrom'";
                                                                } elseif (!empty($dateTo)) {
                                                                    $sql .= " AND applyleave_tb.col_endDate = '$dateTo'";
                                                                }

                                                                $sql .= " ORDER BY applyleave_tb._datetime DESC";
                                                    } else {
                                                        $sql = "SELECT
                                                        applyleave_tb.col_ID,
                                                        applyleave_tb.`col_req_emp`,
                                                        CONCAT(
                                                            employee_tb.`fname`,
                                                            ' ',
                                                            employee_tb.`lname`
                                                        ) AS `full_name`,
                                                        applyleave_tb.`col_LeaveType`,
                                                        applyleave_tb.`col_credit`,
                                                        applyleave_tb.`col_strDate`,
                                                        applyleave_tb.`col_endDate`,
                                                        applyleave_tb.`_datetime`,
                                                        applyleave_tb.`col_file`,
                                                        applyleave_tb.`col_dt_action`,
                                                        applyleave_tb.`col_approver`,
                                                        applyleave_tb.`col_status`
                                                        FROM
                                                            applyleave_tb
                                                        INNER JOIN employee_tb ON applyleave_tb.col_req_emp = employee_tb.empid
                                                        INNER JOIN approver_tb ON approver_tb.empid = applyleave_tb.col_req_emp
                                                        WHERE
                                                        approver_tb.approver_empid = $aprrover_ID";
                                                        if (!empty($status) && $status != 'All Status') {
                                                            $sql .= " AND applyleave_tb.col_status = '$status'";
                                                        }

                                                        if (!empty($dateFrom) && !empty($dateTo)) {
                                                            $sql .= " AND (applyleave_tb.col_strDate >= '$dateFrom' AND applyleave_tb.col_endDate <= '$dateTo')";
                                                        } elseif (!empty($dateFrom)) {
                                                            $sql .= " AND applyleave_tb.col_strDate = '$dateFrom'";
                                                        } elseif (!empty($dateTo)) {
                                                            $sql .= " AND applyleave_tb.col_endDate = '$dateTo'";
                                                        }

                                                        $sql .= " ORDER BY applyleave_tb._datetime DESC";
                                                    }
                                                    
                                                    $result = $conn->query($sql);

                                                    //read data
                                                    while($row = $result->fetch_assoc()){

                                                        $cmpny_empid = $row['col_req_emp'];

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
                                                                <td>" . ($row['col_credit'] == 0 ? "LWOP" : $row['col_credit']) . "</td>
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
                                                                                <p class='btn btn-primary pl-3 pr-3 pt-2 pb-2'> Download</p>
                                                                            </button>
                                                                        </div>
                                                                    </div>";
                                                                }
                                                                
                                                                echo "</td>
                                                                <td" . ($row['col_status'] === 'Approved' ? " style='color: green;'" :
                                                                            ($row['col_status'] === 'Rejected' ? " style='color: red;'" :
                                                                                ($row['col_status'] === 'Cancelled' ? " style='color: gray;'" :
                                                                                    ($row['col_status'] === 'Pending' ? " style='color: orange;'" :
                                                                                    "") 
                                                                                )
                                                                            )
                                                                        ) . ">" . $row['col_status'] . "</td>
                                                            </tr>";

                                                    }
                                                ?>  
                                        </tbody>   
                                </table>
                  
                        </form>
                    </div> <!--table my-3 end-->   
                <!----------------------------------Break------------------------------------->

                   <!---- Modal for View button for file reason ---->
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
                </div> <!--card-body end-->

            </div> <!--Card end-->
                                                </div>
        </div>  <!--jummbotron end--> 
    </div> <!--container end-->

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
                                    $('#empName').val(data[0]);
                                });
                            });
            
    </script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script> -->

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
<script src="js/leavereq.js"></script>
</html>