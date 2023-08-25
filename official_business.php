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

    <link rel="stylesheet" href="skydash/style.css">

    <script src="https://kit.fontawesome.com/803701e46b.js" crossorigin="anonymous"></script>
   

    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/official_business.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/official_businessResponsives.css">
    <title>Official Business - Admin</title>
</head>
<body>
    <header>
    <?php
        include 'header.php';
    ?>
    </header>

<!---------------------------------------View Modal Start Here -------------------------------------->
<div class="modal fade" id="viewmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Reason</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
            <label for="text_area" class="form-label"></label>
            <textarea class="form-control" name="text_reason" id="view_reason1" readonly></textarea>
         </div>
      </div><!--Modal Body Close Tag-->

    </div>
  </div>
</div>
<!---------------------------------------View Modal End Here --------------------------------------->

<!---------------------------------------Download Modal Start Here -------------------------------------->
<div class="modal fade" id="download" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Download PDF File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/Official Business/download.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table">
        <input type="hidden" name="table_name" id="name_table">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_download" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------Download Modal End Here --------------------------------------->

<!----------------Modal kapag clinick ang approve button----------------------->
<div class="modal fade" id="Modal_Approved" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Official Business/approve.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to approve this request?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <input type="hidden" id="check_id" name="id_check">
                          <textarea class="form-control" name="name_approvedremarks" placeholder="Approval message..." id="floatingTextarea"></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="name_approved" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!----------------Modal kapag clinick ang approve button----------------------->

<!----------------Modal kapag clinick ang reject button----------------------->
<div class="modal fade" id="Modal_reject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Official Business/reject.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to reject this request?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <input type="hidden" id="reject_id" name="id_reject">
                          <textarea class="form-control" name="name_rejectedRemarks" placeholder="Reject message..." id="floatingTextarea" required></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="name_rejected" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!----------------Modal kapag clinick ang reject button----------------------->

<!-----------------Modal kapag naclick ang Approve all button-------------------------->
<div class="modal fade" id="approve_all_OB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Official Business/ob_approve_all.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to approve all the requests?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <textarea class="form-control" name="ob_approve_marks" placeholder="Approve message..." id="floatingTextarea" required></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="OB_approve_all" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!-----------------Modal kapag naclick ang Approve all button-------------------------->


<!-----------------Modal kapag naclick ang reject all button-------------------------->
<div class="modal fade" id="reject_all_OB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
          <form action="actions/Official Business/ob_reject_all.php" method="POST">
              <div class="modal-content">
                  <div class="modal-header">
                      <h1 class="modal-title fs-5" id="staticBackdropLabel">You want to approve all the requests?</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="form-floating">
                          <textarea class="form-control" name="ob_reject_marks" placeholder="Approve message..." id="floatingTextarea" required></textarea>
                          <label for="floatingTextarea">Remarks:</label>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit"  name="ob_reject_all" class="btn btn-primary">Submit</button>
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </form>
      </div>
  </div>
<!-----------------Modal kapag naclick ang reject all button-------------------------->


<!---------------------------------------Main Panel Start Here --------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17); overflow-y: hidden;">
            <div class="card-body">
<!---------------------------------------Main Panel End Here --------------------------------------->
                        
<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <p class="header_prgph_DTR" style="font-size: 25px; padding: 10px">Official Business</p>
                                </div>
                            </div> <!--ROW END-->
<!----------------------------------End Class ng header including the button for modal-------------------------------------------->

<!-----------------------------------------Syntax for the alert Message----------------------------------------------------------->
<?php
        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }

?>
<!--------------------------------------End ng Syntax for the alert Message------------------------------------------------------->


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
<div class="official_panel">
            <div class="child_panel">
              <p class="empo_date_text">Employee</p>
               <?php
                    include 'config.php';

                    // Fetch all values of empid and date from the database
                    $sql = "SELECT `empid`, CONCAT(`fname`, ' ',`lname`) AS `full_name` FROM employee_tb";
                    $result = mysqli_query($conn, $sql);

                    $employeeID = isset($_GET['empid']) ? ($_GET['empid']): '';
                    echo "<select class='select_custom form-select-m' aria-label='.form-select-sm example' name='name_emp' id='select_emp'>";
                    echo "<option value=''>Select Employee</option>";
                    echo "<option value='All Employee'" .($employeeID == 'All Employee' ? ' selected' : '').">All Employee</option>"; // Add a default option
                    while ($row = mysqli_fetch_array($result)) {
                        $emp_id = $row['empid'];
                        $emp_name = $row['full_name'];
                        echo "<option value='$emp_id - $emp_name'" . ($employeeID == $emp_id . ' - ' . $emp_name ? ' selected' : '') . ">$emp_id - $emp_name</option>";
                    }
                    echo "</select>";
                ?>
            </div>

                  <div class="child_panel">
                    <?php
                      $dateFrom = isset($_GET['str_date']) ? ($_GET['str_date']): '';
                      $dateTo = isset($_GET['end_date']) ? ($_GET['end_date']): '';
                    ?>
                    <p class="empo_date_text">Date From</p>
                    <input class="select_custom" type="date" name="startdate" id="datestart" value="<?php echo $dateFrom;?>" required>
                  </div>
                  <div class="child_panel">
                    <div class="notif">
                    <p class="empo_date_text">Date To</p>
                  </div>
                    <input class="select_custom" type="date" name="enddate" id="enddate" value="<?php echo $dateTo;?>" onchange="datefunct()" required>
                  </div>
                  <button class="btn_go" id="id_btngo" onclick="filterOB()">&rarr; Apply Filter</button>
                </div>
<!------------------------------End Syntax for Dropdown button------------------------------------------------->



<!----------------------------------Button for Approve and Reject All------------------------------------------>
                <div class="btn-section">
                <button type="submit" name="approve_all" class="approve-btn" data-bs-toggle="modal" data-bs-target="#approve_all_OB">Approve All</button>
                <button type="submit" name="reject_all" class="reject-btn" data-bs-toggle="modal" data-bs-target="#reject_all_OB">Reject All</button>
                </div>
<!--------------------------------End Button for Approve and Reject All---------------------------------------->   

<!--------------------------------------------Syntax and Bootstrap class for table------------------------------------------------>
            
                          <div class="table-responsive">
                             <table id="order-listing" class="table mt-2">
                                    <thead>
                                            <tr>
                                                <th style="display: none;">ID</th>
                                                <th>Employee ID</th>
                                                <th>Name</th>
                                                <th>Company Name</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th style="display: none;">Location</th>
                                                <th>Status</th>
                                                <th>File Attachment</th>
                                                <th>Reason</th>
                                                <th style="display: none;">View Button</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                            include 'config.php';
                                            $employee = $_GET['empid'] ?? '';
                                            $dateFrom = $_GET['str_date'] ?? '';
                                            $dateTo = $_GET['end_date'] ?? '';

                                            if (isset($_GET['id'])) {
                                              $employee_id = $_GET['id'];

                                              $query = "SELECT
                                              emp_official_tb.id,
                                              employee_tb.empid,
                                              CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                              emp_official_tb.company_name,
                                              emp_official_tb.str_date,
                                              emp_official_tb.end_date,
                                              emp_official_tb.start_time,
                                              emp_official_tb.end_time,
                                              emp_official_tb.location,
                                              emp_official_tb.file_upl,
                                              emp_official_tb.reason,
                                              emp_official_tb.status
                                                FROM
                                                    employee_tb
                                                INNER JOIN emp_official_tb ON employee_tb.empid = emp_official_tb.employee_id
                                                WHERE emp_official_tb.id = '$employee_id'";

                                              if (!empty($dateFrom) && !empty($dateTo)) {
                                                if ($employee != 'All Employee') {
                                                    $query .= " AND";
                                                } else {
                                                    $query .= " WHERE";
                                                }
                                                $query .= " (emp_official_tb.str_date BETWEEN '$dateFrom' AND '$dateTo' OR emp_official_tb.end_date BETWEEN '$dateFrom' AND '$dateTo')";
                                              } elseif (!empty($dateFrom)) {
                                                if ($employee != 'All Employee') {
                                                    $query .= " AND";
                                                } else {
                                                    $query .= " WHERE";
                                                }
                                                $query .= " emp_official_tb.str_date = '$dateFrom'";
                                              } elseif (!empty($dateTo)) {
                                                if ($employee != 'All Employee') {
                                                    $query .= " AND";
                                                } else {
                                                    $query .= " WHERE";
                                                }
                                                $query .= " emp_official_tb.end_date = '$dateTo'";
                                              }
                                            } else {
                                              $query = "SELECT
                                              emp_official_tb.id,
                                              employee_tb.empid,
                                              CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                              emp_official_tb.company_name,
                                              emp_official_tb.str_date,
                                              emp_official_tb.end_date,
                                              emp_official_tb.start_time,
                                              emp_official_tb.end_time,
                                              emp_official_tb.location,
                                              emp_official_tb.file_upl,
                                              emp_official_tb.reason,
                                              emp_official_tb.status
                                                FROM
                                                    employee_tb
                                                INNER JOIN emp_official_tb ON employee_tb.empid = emp_official_tb.employee_id";

                                              if (!empty($dateFrom) && !empty($dateTo)) {
                                                if ($employee != 'All Employee') {
                                                    $query .= " AND";
                                                } else {
                                                    $query .= " WHERE";
                                                }
                                                $query .= " (emp_official_tb.str_date BETWEEN '$dateFrom' AND '$dateTo' OR emp_official_tb.end_date BETWEEN '$dateFrom' AND '$dateTo')";
                                              } elseif (!empty($dateFrom)) {
                                                if ($employee != 'All Employee') {
                                                    $query .= " AND";
                                                } else {
                                                    $query .= " WHERE";
                                                }
                                                $query .= " emp_official_tb.str_date = '$dateFrom'";
                                              } elseif (!empty($dateTo)) {
                                                if ($employee != 'All Employee') {
                                                    $query .= " AND";
                                                } else {
                                                    $query .= " WHERE";
                                                }
                                                $query .= " emp_official_tb.end_date = '$dateTo'";
                                              }
                                            }


                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {

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
                                                <td style="display: none;"><?php echo $row['id'];?></td>
                                                <td><?php $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                                $empid = $row['empid'];
                                                if (!empty($cmpny_code)) {
                                                    echo $cmpny_code . " - " . $empid;
                                                } else {
                                                    echo $empid;
                                                }?></td>
                                                <td><?php echo $row['full_name'];?></td>
                                                <td><?php echo $row['company_name'];?></td>
                                                <td><?php echo $row['str_date'];?></td>
                                                <td><?php echo $row['end_date'];?></td>
                                                <td><?php echo $row['start_time'];?></td>
                                                <td><?php echo $row['end_time'];?></td>
                                                <td style="display: none;"><?php echo $row['location'];?></td>
                                                <td <?php if ($row['status'] == 'Approved') {echo 'style="color:blue;"';} elseif ($row['status'] == 'Rejected') {echo 'style="color:red;"';} elseif ($row['status'] == 'Pending') {echo 'style="color:orange;"';} elseif ($row['status'] == 'Cancelled') {echo 'style="color:red;"';} ?>><?php echo $row['status']; ?></td>
                                                <?php if(!empty($row['file_upl'])): ?>
                                                <td>
                                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download">Download</button>
                                                </td>
                                                <?php else: ?>
                                                <td>None</td> <!-- Show an empty cell if there is no file attachment -->
                                                <?php endif; ?>
                                                <td style="display: none;"><?php echo $row['reason'];?></td>
                                                <td><i class="fa-solid fa-eye fs-5 me-3 showbtn" data-bs-toggle="modal" data-bs-target="#viewmodal" style="cursor: pointer;"></i></td>
                                                <td>
                                                <?php if ($row['status'] === 'Approved' || $row['status'] === 'Rejected' || $row['status'] === 'Cancelled'): ?>
                                                  <button type="button" class="btn btn-outline-success approvebtn" data-bs-toggle="modal" data-bs-target="#Modal_Approved" name="btn_approve" style="display: none;" disabled>
                                                    Approve
                                                  </button>
                                                  <button type="button" class="btn btn-outline-danger rejectbtn" data-bs-toggle="modal" data-bs-target="#Modal_reject" name="btn_reject" style="display: none;" disabled>
                                                    Reject
                                                  </button>
                                                <?php else: ?>
                                                  <button type="button" class="btn btn-outline-success approvebtn" data-bs-toggle="modal" data-bs-target="#Modal_Approved" name="btn_approve">
                                                    Approve
                                                  </button>
                                                  <button type="button" class="btn btn-outline-danger rejectbtn" data-bs-toggle="modal" data-bs-target="#Modal_reject" name="btn_reject">
                                                    Reject
                                                  </button>
                                                <?php endif; ?>
                                                </td>
                                            </tr>
                                                 <?php
                                                    } 
                                                  ?>
                                    </table>
                                </div>
<!------------------------------------------End Syntax and Bootstrap class for table---------------------------------------------->

                    </div><!------Main Panel Close Tag-------->
                </div>
            </div>
        </div>



<!---------------------Script sa pagfilter ng data----------------------------------->
<script>
    function filterOB() {
        var employee = document.getElementById('select_emp').value;
        var dateFrom = document.getElementById('datestart').value;
        var dateTo = document.getElementById('enddate').value;

        var url = 'Official_business.php?empid=' + employee + '&str_date=' + dateFrom + '&end_date=' + dateTo;
        window.location.href = url;
    }
</script>
<!---------------------Script sa pagfilter ng data----------------------------------->

<!-------------------------------Script para matest kung naseselect ba ang I.D sa pag-approve---------------------------------------->        
<script> 
            $(document).ready(function(){
               $('.approvebtn').on('click', function(){
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
<!-----------------------------End Script para matest kung naseselect ba ang I.D sa pag-approve------------------------------------->

<!-------------------------------Script para matest kung naseselect ba ang I.D sa pag-reject---------------------------------------->        
<script> 
            $(document).ready(function(){
               $('.rejectbtn').on('click', function(){
                 $().modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#reject_id').val(data[0]);
               });
             });
</script>
<!-----------------------------End Script para matest kung naseselect ba ang I.D sa pag-reject------------------------------------->


<!------------------------------------Script para lumabas ang modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.showbtn').on('click', function(){
                 $('#viewmodal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_reason1').val(data[11]);
               });
             });
</script>
<!---------------------------------End ng Script para lumabas ang modal------------------------------------------>


<!------------------------------------Script para sa download modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.downloadbtn').on('click', function(){
                 $('#download').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#id_table').val(data[0]);
                   $('#name_table').val(data[2]);
               });
             });
</script>
<!---------------------------------End ng Script para download modal------------------------------------------>


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



<!-----------------------Script para sa automatic na pagdisapper ng alert message------------------------------->
<!-- <script>
    setTimeout(function() {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 2000);
</script> -->
<!---------------------End Script para sa automatic na pagdisapper ng alert message------------------------------>

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
<script src="js/official_emp.js"></script>
</body>
</html>