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
    <link rel="stylesheet" href="css/undertime.css"/>
    <link rel="stylesheet" href="css/styles.css">
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
        margin-right: 18px !important;
        margin-bottom: -17px !important;

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
                            <input type="date" name="undertime_date_view" class="form-control" id="undertime_date_view_id" readonly>
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                       <div class="col-6">
                            <label for="start" class="form-label">Start Time</label>
                            <input type="time" name="view_undertime_start" class="form-control" id="view_undertime_start_id" readonly>
                        </div>
                        <div class="col-6">
                           <label for="end" class="form-label">End Time</label>
                           <input type="time" name="view_undertime_end" class="form-control" id="view_undertime_end_id" readonly>
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

<!----------------Modal kapag clinick ang approve button----------------------->
<div class="modal fade" id="Modal_UT_Approved" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Undertime Request/approve_ut.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to approve this request?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <input type="hidden" id="approve_ut_id" name="approve_name_ut">
                          <textarea class="form-control" name="ut_approve_marks" placeholder="Approval message..." id="floatingTextarea"></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="name_approved_ut" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!----------------Modal kapag clinick ang approve button----------------------->

<!----------------Modal kapag clinick ang reject button----------------------->
<div class="modal fade" id="Modal_UT_reject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Undertime Request/reject_ut.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to reject this request?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <input type="hidden" id="reject_ut_id" name="reject_name_ut">
                          <textarea class="form-control" name="ut_reject_remarks" placeholder="Reject message..." id="floatingTextarea" required></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="name_rejected_ut" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!----------------Modal kapag clinick ang reject button----------------------->

<!-----------------Modal kapag naclick ang Approve all button-------------------------->
<div class="modal fade" id="ut_approve_all_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Undertime Request/ut_approve_all.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to approve all the requests?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <textarea class="form-control" name="ut_all_approve_marks" placeholder="Approve message..." id="floatingTextarea" required></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="approve_all_ut" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!-----------------Modal kapag naclick ang Approve all button-------------------------->


<!-----------------Modal kapag naclick ang Approve all button-------------------------->
<div class="modal fade" id="ut_reject_all_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Undertime Request/ut_reject_all.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to approve all the requests?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <textarea class="form-control" name="ut_all_reject_marks" placeholder="Approve message..." id="floatingTextarea" required></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="reject_all_ut" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!-----------------Modal kapag naclick ang Approve all button-------------------------->



<!------------------------------------Main Panel of data table------------------------------------------------->
    <div class="main-panel mt-5">
        <div class=" mt-5">
          <div class="card">
            <div class="card-body">  
<!------------------------------------Main Panel of data table------------------------------------------------->


<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <h2>Undertime Request List</h2>
                                </div>
                            </div> <!--ROW END-->
<!----------------------------------End Class ng header including the button for modal-------------------------------------------->

<!------------------------------------Message alert------------------------------------------------->
<?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
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
<div class="undertime_panel">
            <div class="dropdown_panel">
                <p class="drop_text">Employee</p>
                <?php
                include 'config.php';

                $sql = "SELECT `empid`, CONCAT(`fname`, ' ',`lname`) AS `full_name` FROM employee_tb";
                $result = mysqli_query($conn, $sql);

                $employeeID = isset($_GET['empid']) ? ($_GET['empid']) : '';
                echo "<select class='select_custom form-select-m' aria-label='.form-select-sm example' name='name_emp' id='sel_employee'>";
                echo "<option value='' default>Select Employee</option>";
                echo "<option value='All Employee'" . ($employeeID == 'All Employee' ? ' selected' : '') . ">All Employee</option>";
                while ($row = mysqli_fetch_array($result)) {
                    $emp_id = $row['empid'];
                    $emp_name = $row['full_name'];
                    echo "<option value='$emp_id'" . ($employeeID == $emp_id ? ' selected' : '') . ">$emp_id - $emp_name</option>";
                }
                echo "</select>";
                ?>
            </div>

            <div class="dropdown_panel">
                    <?php
                        $status = isset($_GET['status']) ? $_GET['status'] : '';
                    ?>
              <p class="drop_text">Status</p>
              <select class="select_custom form-select-m" aria-label=".form-select-sm example" name="status_emp" id="id_status">
                  <option value="" default>Select Status</option>
                  <option value="All Status"<?php if ($status == 'All Status') echo ' selected'; ?>>All Status</option>
                  <option value="Pending" <?php if ($status == 'Pending') echo 'selected'; ?>>Pending</option>
                  <option value="Approved" <?php if ($status == 'Approved') echo 'selected'; ?>>Approved</option>
                  <option value="Rejected" <?php if ($status == 'Rejected') echo 'selected'; ?>>Rejected</option>
                  <option value="Cancelled" <?php if ($status == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
              </select>
          </div>
          
          <button class="btn_go" id="id_btngo" onclick="filterUTRequest()">Apply Filter</button>
          
          </div>
<!------------------------------End Syntax for Dropdown button------------------------------------------------->

<!----------------------------Script sa pagfilter ng data table------------------------->
<script>
    function filterUTRequest() {
        var employee = document.getElementById('sel_employee').value;
        var status = document.getElementById('id_status').value;

        var url = 'undertime_req.php?empid=' + employee + '&status=' + status;
        window.location.href = url;
    }
</script>
<!----------------------------Script sa pagfilter ng data table------------------------->

<!----------------------------------Button for Approve and Reject All------------------------------------------>
        <div class="btn-section">
                <button type="submit" name="approve_all" data-bs-toggle="modal" data-bs-target="#ut_approve_all_modal" class="approve-btn">Approve All</button>
                <button type="submit" name="reject_all"  data-bs-toggle="modal" data-bs-target="#ut_reject_all_modal" class="reject-btn">Reject All</button>
        </div>
<!--------------------------------End Button for Approve and Reject All---------------------------------------->  
               

<!------------------------------------------Syntax ng Table-------------------------------------------------->
        <div class="row" >
            <div class="col-12 mt-2">
                <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                    <table id="order-listing" class="table" style="width: 100%">
                      <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                            <tr>
                                <th style="display: none;">ID</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Undertime Date</th>
                                <th>Undertime</th>
                                <th>Time Out</th>
                                <th>Undertime Hours</th>
                                <th style="display: none;">Reason</th>
                                <th>File Attachment</th>
                                <th>Status</th>
                                <th>Date Filed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                         <?php
                         include 'config.php';
                         $aprrover_ID = $_SESSION['empid'];

                         $empid = $_GET['empid'] ?? '';
                         $status = $_GET['status'] ?? '';

                         if (isset($_GET['id'])) {
                          $employee_id = $_GET['id'];
                      
                          $query = "SELECT
                          undertime_tb.id,
                          employee_tb.empid,
                          CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                          undertime_tb.date,
                          undertime_tb.start_time,
                          undertime_tb.end_time,
                          undertime_tb.total_undertime,
                          undertime_tb.file_attachment,
                          undertime_tb.reason,
                          undertime_tb.status,
                          undertime_tb.date_file
                          FROM
                           undertime_tb
                          INNER JOIN employee_tb ON undertime_tb.empid = employee_tb.empid 
                          INNER JOIN approver_tb ON approver_tb.empid = undertime_tb.empid
                           WHERE approver_tb.approver_empid = '$aprrover_ID' AND undertime_tb.id = '$employee_id'";
 
                              if (!empty($empid) && $empid != 'All Employee') {
                                $query .= " AND employee_tb.empid = '$empid'";
                              }

                              if (!empty($status) && $status != 'All Status') {
                                $query .= " AND undertime_tb.status = '$status'";
                              }
                          } else {
                            $query = "SELECT
                            undertime_tb.id,
                            employee_tb.empid,
                            CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                            undertime_tb.date,
                            undertime_tb.start_time,
                            undertime_tb.end_time,
                            undertime_tb.total_undertime,
                            undertime_tb.file_attachment,
                            undertime_tb.reason,
                            undertime_tb.status,
                            undertime_tb.date_file
                            FROM
                             undertime_tb
                            INNER JOIN employee_tb ON undertime_tb.empid = employee_tb.empid 
                            INNER JOIN approver_tb ON approver_tb.empid = undertime_tb.empid
                             WHERE approver_tb.approver_empid = $aprrover_ID";
   
                              if (!empty($empid) && $empid != 'All Employee') {
                                $query .= " AND employee_tb.empid = '$empid'";
                              }

                              if (!empty($status) && $status != 'All Status') {
                                $query .= " AND undertime_tb.status = '$status'";
                              }
                          }
                         
                        

                         $result = mysqli_query($conn, $query);
                         while ($row = mysqli_fetch_assoc($result)){  
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
          
                         ?>
                            <tr>
                                <td style="display: none;"><?php echo $row['id']?></td>
                                <td><?php $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                $empid = $row['empid'];
                                if (!empty($cmpny_code)) {
                                    echo $cmpny_code . " - " . $empid;
                                } else {
                                    echo $empid;
                                }?></td>
                                <td><?php echo $row['full_name']?></td>
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
                                <td>None</td> <!-- Show an empty cell if there is no file attachment -->
                                <?php endif; ?>
                                <td <?php if ($row['status'] == 'Approved') {echo 'style="color:green;"';} elseif ($row['status'] == 'Rejected') {echo 'style="color:red;"';} elseif ($row['status'] == 'Cancelled') {echo 'style="color:gray;"';}?>><?php echo $row['status']; ?></td>
                                <td><?php echo $row['date_file']?></td>
                                <td>      
                                 <?php if ($row['status'] === 'Approved' || $row['status'] === 'Rejected' || $row['status'] === 'Cancelled'): ?>
                                 <button type="submit" class="btn btn-outline-success approveUTbtn"  data-bs-toggle="modal" data-bs-target="#Modal_UT_Approved" name="approve_btn" style="display: none;" disabled>
                                   Approve
                                 </button>
                                 <button type="submit" class="btn btn-outline-danger rejectUTbtn" data-bs-toggle="modal" data-bs-target="#Modal_UT_reject" name="reject_btn" style="display: none;" disabled>
                                   Reject
                                 </button>
                                 <?php else: ?>
                                 <button type="submit" class="btn btn-outline-success approveUTbtn" data-bs-toggle="modal" data-bs-target="#Modal_UT_Approved" name="approve_btn">
                                    Approve
                                 </button>
                                 <button type="submit" class="btn btn-outline-danger rejectUTbtn" data-bs-toggle="modal" data-bs-target="#Modal_UT_reject" name="reject_btn">
                                    Reject
                                  </button>
                                  <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                             } 
                            ?>
                      </table>
<!------------------------------------End Syntax ng Table------------------------------------------------->                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



 
<!-------------------------------Script para matest kung naseselect ba ang I.D sa approve---------------------------------------->        
<script> 
            $(document).ready(function(){
               $('.approveUTbtn').on('click', function(){
                 $().modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#approve_ut_id').val(data[0]);
               });
             });
</script>
<!-----------------------------End Script para matest kung naseselect ba ang I.D approve------------------------------------->

<!-------------------------------Script para matest kung naseselect ba ang I.D sa reject---------------------------------------->        
<script> 
            $(document).ready(function(){
               $('.rejectUTbtn').on('click', function(){
                 $().modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#reject_ut_id').val(data[0]);
               });
             });
</script>
<!-----------------------------End Script para matest kung naseselect ba ang I.D reject------------------------------------->


<!-------------------------------Script para matest kung naseselect ba ang I.D---------------------------------------->        
<script> 
            $(document).ready(function(){
               $('.checkunder').on('click', function(){
                 $().modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#check_id').val(data[0]);
               });
             });
</script>
<!-----------------------------End Script para matest kung naseselect ba ang I.D------------------------------------->

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
                   $('#undertime_date_view_id').val(data[2]);
                   $('#view_undertime_start_id').val(data[3]);
                   $('#view_undertime_end_id').val(data[4]);
                   $('#view_total_undertime_id').val(data[5]);
                   $('#view_undertime_reason_id').val(data[6]);
                   $('#view_undertime_upload_id').val(data[7]);
                   var status = $tr.find('td:eq(8)').text();
                   $('#view_undertime_status_id').val(status);
                   $('#view_datefiled_id').val(data[9]);
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


<script src="js/undertime.js"></script>


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


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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