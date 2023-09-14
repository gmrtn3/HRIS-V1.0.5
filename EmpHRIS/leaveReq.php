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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

<style>
    
    html{
        background-color: #f4f4f4 !important;
        overflow: hidden;
    }
    

    body{
        overflow: hidden;
        background-color: #f4f4f4;
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

    #order-listing_paginate{
        margin-left: 498px;
    }
    
    #order-listing_next{
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }

    /* Search Bar */

    #order-listing_filter label input{
        
        width: 278px;
        font-size: 17px;
        
    }

    .dataTables_length{
        margin-top: 30px;
    }

    .dataTables_filter{
        margin-top: 15px;
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

    .red-text{
        color: red;
    }
    .green-text{
        color: green;
    }
</style>


<div class="container-xxl mt-5 " style="margin-left: 18.05%; position: absolute; top: 8.9%;">
        <div class="">

            <div class="card border-light" style="box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.2), 0 2px 5px 0 rgba(0, 0, 0, 0.17); width: 1500px; height: 780px;">
                <div class="">
                    

<!-------------------------------------------------------------- BREAK  for add  leave Type modal start------------------------------------------------------------------------------->

                        <div class="modal fade" id="id_addLeaveType" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="add_LeaveType.php" method="post">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Add New Leave Type</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="input-group">
                                                <span class="input-group-text">Leave Type Name:</span>
                                                <input type="text" name="name_typeName" aria-label="Leave Type" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="name_btnAddLeaveType" class="btn btn-primary">Add Leave Type</button>
                                        </div>
                                    </div>
                                </form>    
                            </div>
                        </div>

<!-------------------------------------------------------------- BREAK  for add  leave Type modal END------------------------------------------------------------------------------->




<!-------------------------------------------------------------- BREAK  for add  credits modal start------------------------------------------------------------------------------->

                    <!-- Modal -->
                        <div class="modal fade" id="id_apply_leave" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog">
                            <form action="actions/Leave Request/insert.php" method="post" enctype="multipart/form-data">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create: Apply Leave</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        

                                                
                                                    
                                            <!------------------------------ BREAK -------------------------------------->

                                        <div class="row">
                                        <input type="hidden" name='name_emp' readonly value="<?php  echo $_SESSION['empid']; ?>">
                                            <?php 
                                                include 'config.php';

                                                $empid = $_SESSION['empid'];
                                            
                                                $sqls = "SELECT * FROM leaveinfo_tb WHERE col_empID = $empid";
                                            
                                                $results = mysqli_query($conn, $sqls);
                                                $rows = mysqli_fetch_assoc($results);
                                            ?>
                                                <div class="mb-3" id="credits" style="display:none">
                                                    <p class="d-flex justify-content-end align-items-center mr-4" style="font-size: 1em">Balance Credits: <span id="showLeave" class="ml-2 red-text green-text" > </span> </p>
                                                </div>
                                                <div class="col-6">
                                                    
                                                    <div class="mb-3">
                                                        <label for="Select_dept" class="form-label">Leave Type :</label>
                                                        <select class='form-select form-select-m' onchange="leavetype()" id="leavetype_id" name="name_LeaveT" aria-label='.form-select-sm example' style=' cursor: pointer;'>
                                                            <option selected disabled value=''>Select</option>
                                                            <option value='Vacation Leave'>Vacation Leave</option>
                                                            <option value='Sick Leave'>Sick Leave</option>
                                                            <option value='Bereavement Leave'>Bereavement Leave</option>
                                                        </select>
                                                       

                                                        <?php
                                                        /*
                                                            include 'config.php';

                                                            // Fetch all values of fname and lname from the database
                                                            $sql = "SELECT col_Leave_name FROM leavetype_tb";
                                                            $result = mysqli_query($conn, $sql);

                                                            // Generate the dropdown list
                                                            echo "<select class='form-select form-select-m' aria-label='.form-select-sm example' name='name_emp'>";
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                $Leave_id = $row['col_ID'];
                                                                $Leave_name = $row['col_Leave_name'];
                                                                echo "<option value='$Leave_id'>$Leave_name</option>";
                                                            }
                                                            echo "</select>";
                                                            */
                                                        ?>

                                                    </div> <!-- First mb-3 end-->
                                                </div> <!-- First col-6 end-->
                                                

                                                <!---------------------------------- BREAK ------------------------------>

                                                <div class="col-6">
                                                    <div class="mb-3">
                                                            <label for="Select_dept" class="form-label">Leave Period :</label>
                                                            <select style id="id_leavePeriod" disabled name="name_LeaveP" onchange="halfdaysides()" class='form-select form-select-m' aria-label='.form-select-sm example' style='cursor: pointer;'>
                                                                <option disabled selected value=''>Select</option>
                                                                <option value='Full Day'>Full Day</option>
                                                                <option value='Half Day'>Half Day</option> 
                                                            </select>
                                                    </div> <!-- Second mb-3 end-->
                                                </div> <!-- Second col-6 end-->
                                        </div>  <!-- Row end-->


                                            <!---------------------------------- BREAK ------------------------------>


                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group mb-3" id="id_chckfirsthalf" style="display: none;">
                                                        <div class="input-group-text">
                                                            <input class="form-check-input mt-0" type="checkbox" name="firstHalf" value="First Half" aria-label="Checkbox for following text input">
                                                        </div>
                                                        <input type="text" class="form-control" aria-label="Text input with checkbox" readonly value="First Half">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="input-group mb-3" id="id_chckSecondhalf" style="display: none;">
                                                        <div class="input-group-text">
                                                            <input class="form-check-input mt-0" type="checkbox" name="secondHalf" value="Second Half" aria-label="Checkbox for following text input">
                                                        </div>
                                                        <input type="text" class="form-control" aria-label="Text input with checkbox" readonly value="Second Half">
                                                    </div>
                                                </div>
                                            </div>


                                             <!---------------------------------- BREAK ------------------------------>


                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="mb-1">
                                                            <label for="id_inpt_strdate">Start Date :</label>
                                                            <input type="date" onchange =" strvalidate() "   name="name_STRdate" class="form-control" id="id_inpt_strdate" style='cursor: pointer;' disabled required>
                                                        </div> <!-- Second mb-3 end-->
                                                    </div>  <!-- col-6 end-->
                                                    <div class="col-6">
                                                        <div class="mb-1">
                                                            <label for="id_inpt_enddate">End Date :</label>
                                                            <input type="date" onchange =" endvalidate()" name="name_ENDdate" class="form-control" id="id_inpt_enddate"  style='  cursor: pointer;' required disabled>
                                                        </div> <!-- Second mb-3 end-->
                                                    </div> <!-- col-6 end-->
                                                </div> <!-- row end-->
                                                <span id="error-msg" style="color: red;"></span>

                                                <div class="form-floating mt-3">
                                                    <textarea class="form-control" name="name_txtRSN" placeholder="Leave a comment here" id="id_reason" style=" max-width: 100% ; min-width: 100% ; max-height: 150px ; min-height: 150px ;"></textarea>
                                                    <label for="id_reason">Reason</label>
                                                </div>

                                                <!---------------------------------- BREAK ------------------------------>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="input-group mt-3" id="id_wthPay">
                                                            <div class="input-group-text">
                                                                <input class="form-check-input mt-0" type="checkbox" name="name_wthPay" value="With Pay" id="checkbox_wthPay">
                                                            </div>
                                                            <input type="text" id="chnge_val" class="form-control" aria-label="Text input with checkbox" readonly value="Leave Without Pay" style= "background-color: red; color: #ffffff;">
                                                        </div>
                                                    </div>
                                                </div>
                                               

                                                 <!---------------------------------- BREAK ------------------------------>

                                                <div class="mt-3">
                                                    <label for="formFileMultiple" class="form-label fs-4">Attach File :</label>
                                                    <input class="form-control" name="name_file" type="file" id="formFileMultiple">
                                                </div>

                                        </div>  <!-- end body-->
                                        <div class="modal-footer">
                                        
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" name="name_btnApply" id="id_btnsubmit"  class="btn btn-primary" disabled>Apply Leave</button>
                                        </div>  <!-- end modal footer -->
                                    </div> <!-- end Modal content -->
                                </form>
                            </div> <!-- end Modal dialog -->
                        </div><!-- end Modal -->


        <!-------------------------------------------------------------- BREAK modal end ----------------------------------------------------------------->
                    
                </div> 
                <div class="card-body" style="border-radius: 25px; ">
                <div class="row">
                        <div class="col-6">
                            <h2 class="" style="font-size: 23px; font-weight: bold;">Leave Request</h2>
                        </div>
                        <div class="col-6 text-end mt-3">
                            <button class="btn_applyL" data-bs-toggle="modal" data-bs-target="#id_apply_leave" style="height: 50px; box-shadow: none; font-size: 18px;">
                                Apply Leave
                            </button>
                          
                        </div>
                        
                    </div> <!-- row end -->


                    <!-- <div class="row">
                        <div class="col-6">
                        <div class="mb-3">
                                <label for="Select_dept" class="form-label">Select Status</label>
                                        <select class='form-select form-select-m' aria-label='.form-select-sm example' style=' height: 50px; width: 400px; cursor: pointer;'>
                                            <option value='Pending'>Pending</option>
                                            <option value='Approved'>Approved</option>
                                            <option value='Declined'>Declined</option>
                                        </select>
                            </div>
                          
                            

                        </div>
                        <div class="col-6">
                            <label for="id_strdate" class="form-label">Date Range :</label>
                            <div class="mb-1">
                                <form class="form-floating">
                                    <input type="date" class="form-control" id="id_inpt_strdate" style=' height: 50px; width: 400px;cursor: pointer;' >
                                    <label for="id_inpt_strdate">Start Date :</label>
                                </form>
                            </div>
                        </div>
                    </div> -->
                    
            <!----------------------------------Break------------------------------------->

                    <!-- <div class="row">
                        <div class="col-6">                         
                        
                        </div>
                        <div class="col-6">
                            <div class="mb-1 mt-3">
                                <form class="form-floating">
                                    <input type="date" class="form-control" id="id_inpt_enddate" style=' height: 50px; width: 400px; cursor: pointer;' >
                                    <label for="id_inpt_enddate">End Date :</label>
                                </form>
                            </div>
                        </div>
                    </div> -->

            <!----------------------------------Break------------------------------------->

                    <!-- <div class="pnl_utop p-3 mb-2 bg-body-tertiary">
                        <h3 style= "font-size: 20px; font-weight: bold; font-family: 'Nunito', sans-serif; ">List of Employee Leave Request</h3>
                    </div> -->
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
        
        <div class="form-group d-flex flex-row justify-content-between" style="width: 60%">
                <label for="" style="margin-right: 18px; margin-top: 10px; margin-left: 40px;">Date Range</label>

                <?php
                    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
                    
                ?>
                
                    <input type="date" class="form-control" name="date_from" id="start_date" style="width: 250px; height: 50px; margin-right: 30px;" value="<?php echo $dateFrom; ?>">
                    <input type="date" class="form-control" name="date_to" id="end_date" style="width: 250px; height: 50px; " value="<?php echo $dateTo; ?>">

                    <button id="applyfilt" onclick="filterDates()" class="btn btn-primary" style="width: 20%; font-size: 1.2em; font-weight: 400">Apply Filter</button> <!--margin-left: 60px;-->
               
            </div>

            <script>
                function filterDates() {
                    var dateFrom = document.getElementById('start_date').value;
                    var dateTo = document.getElementById('end_date').value;
                    var applyfilt = document.getElementById('applyfilt');

                    if(dateFrom == '' && dateTo ==''){
                        var url = 'attendance.php';
                        window.location.href = url;  
                    }else{
                        var url = 'attendance.php?date_from=' + dateFrom + '&date_to=' + dateTo;
                        window.location.href = url;   
                    }
                }
                </script>
                

                    <div id="data_table" class="table table-responsive "  style="height: 400px; overflow-y: auto;">
                        <form action="actions/Leave Request/action.php" method="post">
                        <input id="id_ID_tb" name="name_ID_tb" type="text" style="display: none;">  <!--received the id of selected data in datatble and pass to calss action-->   
                        <input id="id_IDemp_tb" name="name_empID_tb" type="text" style="display: none;"> <!--received the employee_id of selected data in datatble and pass to calss action-->  
                        <table id="order-listing" class="table table-sortable table-hover caption-top " >
                                <caption>List of Employee Leave Request</caption>
                                    <thead style="background-color: #ececec;">
                                        <tr>
                                            <th style="color: black;" scope="col">ID</th>
                                            <th style="color: black;" scope="col">Employee ID</th>
                                            <th style="color: black;" scope="col">Name</th>
                                            <th style="color: black;" scope="col">Leave Type</th>
                                            <th style="color: black;" scope="col">Leave Date</th>
                                            <th style="color: black;" scope="col">Date Filled</th>
                                            <th style="color: black;" scope="col">Action Taken</th>
                                            <th style="color: black;" scope="col">Approver</th>
                                            <th style="color: black;" scope="col">File Reason</th>
                                            <th style="color: black;" scope="col">Status</th>
                                        </tr>
                                    </thead>
                                        <tbody id="table-body">
                                            <?php 
                                                    include 'config.php';
                                                    //select data db

                                                    $empid = $_SESSION['empid'];

                                                    $sql = "SELECT
                                                                applyleave_tb.col_ID,
                                                                applyleave_tb.`col_req_emp`,
                                                                CONCAT(
                                                                    employee_tb.`fname`,
                                                                    ' ',
                                                                    employee_tb.`lname`
                                                                ) AS `full_name`,
                                                                applyleave_tb.`col_LeaveType`,
                                                                applyleave_tb.`col_file`,
                                                                applyleave_tb.`col_strDate`,
                                                                applyleave_tb.`_datetime`,
                                                                applyleave_tb.`col_dt_action`,
                                                                applyleave_tb.`col_approver`,
                                                                applyleave_tb.`col_status`
                                                            FROM
                                                                applyleave_tb
                                                            INNER JOIN employee_tb ON applyleave_tb.col_req_emp = employee_tb.empid WHERE applyleave_tb.col_req_emp = $empid
                                                            ORDER BY applyleave_tb.`_datetime` DESC";
                                                    $result = $conn->query($sql);

                                                    //read data
                                                    while($row = $result->fetch_assoc()){
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
                                                                <td>" . $row['col_ID'] . "</td>
                                                                <td>" . $row['col_req_emp'] . "</td>
                                                                <td scope='row'>
                                                                    <button type='submit' name='view_data' class='viewbtn' title='View' style='border: none; background: transparent;
                                                                        text-transform: capitalize; text-decoration: underline; cursor: pointer; color: #787BDB; font-size: 19px;'>
                                                                        " . $row['full_name'] . "
                                                                    </button>
                                                                </td>
                                                                <td>" . $row['col_LeaveType'] . "</td>
                                                                <td>" . $row['col_strDate'] . "</td>
                                                                <td>" . $row['_datetime'] . "</td>
                                                                <td>" . $row['col_dt_action'] . "</td>
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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script> -->
                

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

<script> //script in leave request nilipat ko kasi d gumagana pag nasa folder(leaveReq.js) ng js siya nilagay

function leavetype() {
    let leavetype_id = document.getElementById("leavetype_id").value;
    var selectedLeaveType = document.getElementById('leavetype_id').value;
    let credits = document.getElementById("credits");
    var balanceCredits = 0;

    if (leavetype_id === 'Vacation Leave') {
        document.getElementById("id_leavePeriod").disabled = false;
        credits.style.display = "block";
        balanceCredits = <?php echo $rows['col_vctionCrdt']; ?>;
        console.log("this is vacation leave");
    }
    else if (leavetype_id === 'Sick Leave') {
        document.getElementById("id_leavePeriod").disabled = false;
        credits.style.display = "block";
        balanceCredits = <?php echo $rows['col_sickCrdt']; ?>;
        console.log("this is sick leave");
    }
    else if (leavetype_id === 'Bereavement Leave') {
        document.getElementById("id_leavePeriod").disabled = false;
        credits.style.display = "block";
        balanceCredits = <?php echo $rows['col_brvmntCrdt']; ?>;
        console.log("this is bereavement leave");
    }
    else {
        document.getElementById("id_leavePeriod").disabled = true;
        credits.style.display = "none"; // Hide the credits paragraph
    }

    // Update the balance credits display
    document.getElementById('showLeave').textContent = balanceCredits;

    // Apply red-text class if balance credits are 0
    if (balanceCredits === 0) {
        document.getElementById('showLeave').classList.add('red-text');
        document.getElementById('showLeave').classList.remove('green-text');
    } else {
        document.getElementById('showLeave').classList.remove('red-text');
        document.getElementById('showLeave').classList.add('green-text');
    }
}


  function endvalidate() {
    let id_inpt_strTime1 = new Date(document.getElementById("id_inpt_strdate").value);
    let id_inpt_endTime1 = new Date(document.getElementById("id_inpt_enddate").value);
    let id_leavePeriod = document.getElementById("id_leavePeriod");
    let leavePeriodText = id_leavePeriod.options[id_leavePeriod.selectedIndex].text;
  
    if (leavePeriodText === 'Half Day') {
            if (id_inpt_strTime1.getTime() !== id_inpt_endTime1.getTime()) {
              alert("For half-day leaves, the start and end dates must be the same.");
              document.getElementById("id_btnsubmit").style.cursor = "no-drop";
              document.getElementById("id_btnsubmit").disabled = true;
            } else {
              if (id_inpt_strTime1.getTime() > id_inpt_endTime1.getTime()) {
                alert("Please set the End Date not before the Start Date");
                document.getElementById("id_btnsubmit").style.cursor = "no-drop";
                document.getElementById("id_btnsubmit").disabled = true;
              } else {
                document.getElementById("id_btnsubmit").style.cursor = "pointer";
                document.getElementById("id_btnsubmit").disabled = false;
              }
            }
    } 
    // else { //if fullday
    //           if (id_inpt_strTime1.getTime() === id_inpt_endTime1.getTime()) {
    //             alert("For Full-day leaves, the start and end dates must NOT be the same.");
    //             document.getElementById("id_btnsubmit").style.cursor = "no-drop";
    //             document.getElementById("id_btnsubmit").disabled = true;
    //           }else{
    //         //else
    //         if (id_inpt_strTime1.getTime() > id_inpt_endTime1.getTime()) {
    //           alert("Please set the End Date not before the Start Date");
    //           document.getElementById("id_btnsubmit").style.cursor = "no-drop";
    //           document.getElementById("id_btnsubmit").disabled = true;
    //         } else {
    //           document.getElementById("id_btnsubmit").style.cursor = "pointer";
    //           document.getElementById("id_btnsubmit").disabled = false;
    //         }
    //           }
      
    // }
  }



    //PARA ISA LANG MA CHECK SA FIRST AND SECOND HALF AND UNLOCK THE STARTDATE
        function halfdaysides(){

          const firstHalfCheckbox = document.querySelector('input[name="firstHalf"]');
          const secondHalfCheckbox = document.querySelector('input[name="secondHalf"]');

          let id_leavePeriod = document.getElementById('id_leavePeriod').value;

          if (id_leavePeriod === 'Full Day') {
            document.getElementById("id_inpt_strdate").disabled = false;
            document.getElementById('id_chckfirsthalf').style.display = "none";
            document.getElementById('id_chckSecondhalf').style.display = "none";
            document.getElementById('id_inpt_strdate').value= "";
            document.getElementById('id_inpt_enddate').value= "";

            firstHalfCheckbox.checked = this.checked;
            secondHalfCheckbox.checked = this.checked;
            
          }
          else if(id_leavePeriod === 'Half Day'){
            document.getElementById("id_inpt_strdate").disabled = true;
            document.getElementById('id_chckfirsthalf').style.display = "flex";
            document.getElementById('id_chckSecondhalf').style.display = "flex";
            document.getElementById('id_inpt_strdate').value= "";
            document.getElementById('id_inpt_enddate').value= "";
          }
        }
        


        const firstHalfCheckbox = document.querySelector('input[name="firstHalf"]');
        const secondHalfCheckbox = document.querySelector('input[name="secondHalf"]');
        firstHalfCheckbox.addEventListener('click', function() {
            secondHalfCheckbox.checked = !this.checked;
            document.getElementById("id_inpt_strdate").disabled = false;
        });
        secondHalfCheckbox.addEventListener('click', function() {
            firstHalfCheckbox.checked = !this.checked;
            document.getElementById("id_inpt_strdate").disabled = false;
        });


    //PARA ISA LANG MA CHECK SA FIRST AND SECOND HALF (END)



//PARA MAG CHANGE SA TEXT NG CHECKBOX TO PAY AND WITHOUT PAY

    var checkbox = document.getElementById('checkbox_wthPay');

    checkbox.addEventListener('change', function() {

        var inputValue = document.getElementById('chnge_val');

        if (this.checked) {
            inputValue.value = 'Leave With Pay';
            inputValue.style.color = '#ffffff';
            inputValue.style.backgroundColor = 'green';
        } else {
            inputValue.value = 'Leave Without Pay';
            inputValue.style.color = '#ffffff';
            inputValue.style.backgroundColor = 'red';
        }
    });

//PARA MAG CHANGE SA TEXT NG CHECKBOX TO PAY AND WITHOUT PAY (END)
    
</script> 
<!-- //script in leave request nilipat ko kasi d gumagana pag nasa folder(leaveReq.js) ng js siya nilagay END -->


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

<script>
function strvalidate() {
  var startDate = new Date(document.getElementById("id_inpt_strdate").value);
  var endDate = new Date(document.getElementById("id_inpt_enddate").value);
  var today = new Date(); // Get current date
  today.setHours(0, 0, 0, 0); // Set the time to 00:00:00 to ignore the time component

  // Calculate the date 14 working days ago
  var workingDaysAgo = getPastWorkingDays(today, 14);

  // Disable the button by default
  document.getElementById("id_btnsubmit").disabled = true;

  if (isWorkingDay(startDate) && startDate >= workingDaysAgo) {
    // If the selected date is within the past 14 working days and falls within the valid working days, enable the button
    document.getElementById("id_btnsubmit").disabled = false;
    document.getElementById("error-msg").innerHTML = "";
    document.getElementById("id_inpt_enddate").disabled = false;
  } else {
    // If the selected date is beyond the past 14 working days or not a valid working day, display an error message
    document.getElementById("error-msg").innerHTML = "Invalid date: beyond 14 workdays or non-working.";
    document.getElementById("id_btnsubmit").disabled = true; // Disable the button
    document.getElementById("id_inpt_enddate").value = '';
    document.getElementById("id_inpt_enddate").disabled = true;
  }
}

function isWorkingDay(date) {
  var dayOfWeek = date.getDay(); // Get the day of the week (0 - Sunday, 1 - Monday, ..., 6 - Saturday)
  // Check if the day falls within Monday to Friday (1 - 5) and exclude weekends
  return dayOfWeek >= 1 && dayOfWeek <= 5;
}

function getPastWorkingDays(date, n) {
  var workingDaysCount = 0;
  var currentDate = new Date(date);

  while (workingDaysCount < n) {
    currentDate.setDate(currentDate.getDate() - 1); // Decrement the date by 1 day

    // Check if the current date is a working day (Monday to Friday, excluding weekends)
    if (isWorkingDay(currentDate)) {
      workingDaysCount++;
    }
  }

  return currentDate;
}
</script>

<script src="js/leaveReq.js"></script>

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