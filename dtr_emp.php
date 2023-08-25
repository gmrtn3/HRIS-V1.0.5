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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!--Font Awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <!-- End plugin css for this page -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <!-- inject:css -->
    <link rel="stylesheet" href="bootstrap/vertical-layout-light/style.css">
    <link rel="stylesheet" href="css/dtr_emp.css"/>
    <link rel="stylesheet" href="css/styles.css">
    <title>DTR CORRECTION - Employee</title>
</head>
<body>
    <header>
        <?php
            include 'header.php'
        ?>
</header>

<style>
.sidebars ul li{
        list-style: none;
        text-decoration:none;
        width: 287px;
        margin-left:-16px;
        line-height:30px;
       
    }

    .sidebars ul li .hoverable{
        height:55px;
    }

    .sidebars ul{
        height:100%;
    }

    .sidebars .first-ul{
        line-height:60px;
        height:100px;
    }

    .sidebars ul li ul li{
        width: 100%;
    }
    
    .card-body{
                    width: 98%;
                    box-shadow: 10px 10px 10px 8px #888888;
                }

                .table{
                    width: 99.7%;
                }

                .content-wrapper{
                    width: 85%
                }
</style>
<!----------------------------------------------Modal Start Here-------------------------------------------------------------->

<div class="modal fade" id="file_dtr_btn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">DTR Correction Application</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="Data Controller/DTR Employee/dtr_conn.php" method="POST" enctype="multipart/form-data">
      <div class="modal-body">
      
        <div class="mb-3">
               <label for="Select_emp" class="form-label">Select Employee:</label>
             <?php
                include 'config.php';

               // Fetch all values of fname and lname from the database
                  $sql = "SELECT fname, lname, empid FROM employee_tb";
                  $result = mysqli_query($conn, $sql);

              // Generate the dropdown list
                  echo "<select class='form-select form-select-m' aria-label='.form-select-sm example' name='name_emp'>";
                  while ($row = mysqli_fetch_array($result)) {
                  $emp_id = $row['empid'];
                  $name = $row['empid'] . ' - ' . $row['fname'] . ' ' . $row['lname'];
                  echo "<option value='$emp_id'>$name</option>";
              }
                  echo "</select>";
            ?>
     </div>  <!--mb-3 end--->

        <div class="mb-3">
            <label for="exampleInputDate" class="form-label">Date</label>
            <input name="date_dtr" type="date" class="form-control" id="date_input" required>
        </div>

        <div class="mb-3">
            <label for="exampleInputTime" class="form-label">Time</label>
            <input name="time_dtr" type="time" class="form-control" id="time_input" required>
        </div>

        <div class="mb-3">
            <label for="disabledSelect" class="form-label">Type</label>
            <select name="select_type" id="disabledSelect" class="form-select" required>
                <option value="" disabled="" selected="">Type</option>
                <option value="IN">IN</option>
                <option value="OUT">OUT</option>
            </select>
         </div>

         <div class="mb-3">
             <label for="floatingTextarea2" class="form-label">Reason</label>
             <textarea name="text_reason" class="form-control" placeholder="Leave a reason here" id="floatingTextarea2" style="height: 100px" required></textarea>
         </div>
        
         <div class="input-group mb-3">
                 <input type="file" name="file_upload" class="form-control" id="inputGroupFile02">
          </div>
      </div> <!--Modal body div close tag-->
      <div class="modal-footer">
        <button type="submit" name="add_data" class="btn btn-primary">Add</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
      </form>


    </div>
  </div>
</div>
<!-------------------------------------------------End ng modal----------------------------------------------------------------->

<!------------------------------------------------DELETE MODAL------------------------------------------------------------------>
<div class="modal fade" id="deletemodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Your Request</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="actions/DTR Employee/dtr_delete.php" method="post">
      <div class="modal-body">
        <input type="hidden" name="delete_id" id="delete_id">
        <h4>Are you sure you want to delete your request?</h4>
      </div>
      <div class="modal-footer">
        <button type="submit" name="delete_data" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------------------END OF DELETE MODAL--------------------------------------------------------->

<!---------------------------------------View Modal Start Here -------------------------------------->
<div class="modal fade" id="view_dtr_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

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
<div class="modal fade" id="download_dtr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Download PDF File</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="actions/DTR Correction/download_dtr.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="table_id" id="id_table">
        <input type="hidden" name="table_name" id="name_table">
        <h3>Are you sure you want download the PDF File?</h3>
      </div>
      <div class="modal-footer">
        <button type="submit" name="yes_dl" class="btn btn-primary">Yes</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
      </div>
      </form>

    </div>
  </div>
</div>
<!---------------------------------------Download Modal End Here --------------------------------------->


<!----------------------------------------------Class in overall design--------------------------------------------------------->
    <div class="main-panel mt-5" style="margin-left: 15%;">
        <div class="content-wrapper mt-5">
            <div class="card">
                <div class="card-body ">
                    
<!----------------------------------------------End Class in overall design---------------------------------------------------->


<!----------------------------------Class ng header including the button for modal---------------------------------------------->                    
                            <div class="row">
                                <div class="col-6">
                                    <h2>DTR Correction Application</h2>
                                </div>
                                <div class="col-6 mt-1 text-end">
                                <!-- Button trigger modal -->
                                <button type="button" class="add_dtr_btn" data-bs-toggle="modal" data-bs-target="#file_dtr_btn">
                                    File DTR Correction
                                    </button>
                                </div>
                            </div> <!--ROW END-->
<!----------------------------------End Class ng header including the button for modal-------------------------------------------->

<!-----------------------------------------Syntax for the alert Message----------------------------------------------------------->
<?php

        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            echo '<div id="alert-message" class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            '.$msg.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }

?>
<!--------------------------------------End ng Syntax for the alert Message------------------------------------------------------->


<!-----------------------------------------Syntax for the alert Message----------------------------------------------------------->
<?php

        if (isset($_GET['error'])) {
            $err = $_GET['error'];
            echo '<div id="alert-message" class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            '.$err.'
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }

?>
<!--------------------------------------End ng Syntax for the alert Message------------------------------------------------------->


<!-------------------------------------------Style sa card at table--------------------------------------------------------------->
<style>

</style>
<!----------------------------------------End Style sa card at table-------------------------------------------------------------->

                        <div class="row">
                            <div class="col-12 mt-3">
                                <div class="table-responsive mt-5" style = "overflow: hidden;">
                                    <table id="order-listing" class="table">
                                        <thead>
                                            <tr>
                                                <th style="display: none;">ID</th>
                                                <th>Employee ID</th>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Type</th>
                                                <th>Reason</th>
                                                <th>File Attachment</th>
                                                <th style="display: none;">View Button</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                            <tbody>
                                                <?php 
                                                    $conn = mysqli_connect("localhost","root","","hris_db");

                                                    $query = "SELECT
                                                    emp_dtr_tb.id,
                                                    employee_tb.empid,
                                                    CONCAT(
                                                        employee_tb.`fname`,
                                                        ' ',
                                                        employee_tb.`lname`
                                                    ) AS `full_name`,
                                                    emp_dtr_tb.date,
                                                    emp_dtr_tb.time,
                                                    emp_dtr_tb.type,
                                                    emp_dtr_tb.reason,
                                                    emp_dtr_tb.file_attach,
                                                    emp_dtr_tb.status
                                                FROM
                                                    employee_tb
                                                INNER JOIN emp_dtr_tb ON employee_tb.empid = emp_dtr_tb.empid;";
                                                    $result = mysqli_query($conn, $query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                                <tr>
                                                                <td style="display: none;"><?php echo $row['id']?></td>
                                                                <td><?php echo $row['empid']?></td>
                                                                <td><?php echo $row['full_name']?></td>
                                                                <td><?php echo $row['date']?></td>
                                                                <td><?php echo $row['time']?></td>
                                                                <td><?php echo $row['type']?></td>
                                                                <td  style="display: none;"><?php echo $row['reason'];?></td>
                                                                <td><a href="" class="btn btn-primary viewbtn" data-bs-toggle="modal" data-bs-target="#view_dtr_modal">View</a></td>
                                                                <?php if(!empty($row['file_attach'])): ?>
                                                                <td>
                                                                <button type="button" class="btn btn-outline-success downloadbtn" data-bs-toggle="modal" data-bs-target="#download_dtr">Download</button>
                                                                </td>
                                                                <?php else: ?>
                                                                <td>None</td> <!-- Show an empty cell if there is no file attachment -->
                                                                <?php endif; ?>
                                                                <td> 
                                                                    <p><?php echo $row['status']?></p>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-outline-danger delete_btn">Delete</button>
                                                                </td>
                                                                </tr>
                                                <?php
                                                    } 
                                                ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!----Close tag of row in table----->

                </div><!----Close tag of Main Panel----->
            </div>
        </div>
    </div>

<!------------------------------------Script para lumabas ang modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.viewbtn').on('click', function(){
                 $('#view_dtr_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_reason1').val(data[6]);
               });
             });
</script>
<!---------------------------------End ng Script para lumabas ang modal------------------------------------------>

<!---------------------------------------Script sa pagpop-up ng modal para madelete--------------------------------------------->          
<script>
            $(document).ready(function (){
                $('.delete_btn').on('click' , function(){
                    $('#deletemodal').modal('show');


                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();

                    console.log(data);

                    $('#delete_id').val(data[0]);                  
                });
            });
        </script>
<!---------------------------------------End Script sa pagpop-up ng modal para madelete--------------------------------------------->

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

<!-----------------------Script para sa automatic na pagdisapper ng alert message------------------------------->
<!-- <script>
    // Set a timer to remove the alert message after 2 seconds
    setTimeout(function(){
        document.getElementById("alert-message").remove();
    }, 2000);
</script> -->
<!---------------------End Script para sa automatic na pagdisapper ng alert message------------------------------>

<!-- plugins:js -->
<script src="vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="vendors/datatables.net/jquery.dataTables.js"></script>
<script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
<script src="bootstrap js/template.js"></script>
<!-- Custom js for this page-->
<script src="bootstrap js/data-table.js"></script>
<!-- End custom js for this page-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>


</body>
</html>