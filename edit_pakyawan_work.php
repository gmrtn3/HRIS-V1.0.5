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
    

    if(count($_POST) > 0){

      mysqli_query($conn, "UPDATE employee_tb SET fname='".$_POST['fname']."', mname='".$_POST['mname']."', lname='".$_POST['lname']."', address='".$_POST['address']."', contact='".$_POST['contact']."', cstatus='".$_POST['cstatus']."', gender='".$_POST['gender']."', empdob='".$_POST['empdob']."', work_frequency='".$_POST['work_frequency']."', empbranch='".$_POST['empbranch']."' WHERE id='".$_POST['id']."'");

          // Insert into approver_tb table
      $approverEmpIds = $_POST['approver'];
      $empid_update= $_GET['empid'];

      // echo $approverEmpIds;

      $query = "DELETE FROM approver_tb WHERE empid= $empid_update";
      $query_run = mysqli_query($conn, $query);

      if($query_run)
      {
          foreach ($approverEmpIds as $approverEmpId) {
            // echo $approverEmpId;
              $stmt2 = $conn->prepare("INSERT INTO approver_tb (`empid`, `approver_empid`)
                                      VALUES (?, ?)");
          
              if (!$stmt2) {
                  die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
              }
          
              $stmt2->bind_param("ss", $empid_update, $approverEmpId);
          
              $stmt2->execute();
          
              if ($stmt2->errno) {
                  echo "<script>alert('Error: " . $stmt2->error . "');</script>";
                  echo "<script>window.location.href = '../../empListForm.php';</script>";
                  exit;
              }
          
              $stmt2->close();
          }
      }
      else
      {
          echo "Failed: " . mysqli_error($conn);
      }

      $empid = $_POST['empid'];

      // Get the piece_rate_id values from the form
      $pieceRateIds = $_POST['piece_rate_id'];

      // Delete the existing records for the given empid to handle the update case
      $deleteQuery = "DELETE FROM employee_pakyawan_work_tb WHERE empid = ?";
      $stmt = $conn->prepare($deleteQuery);
      $stmt->bind_param("s", $empid);
      $stmt->execute();
      $stmt->close();
  
      // Insert the new records with the given empid and piece_rate_id values
      $insertQuery = "INSERT INTO employee_pakyawan_work_tb (empid, piece_rate_id) VALUES (?, ?)";
      $stmt = $conn->prepare($insertQuery);
  
      foreach ($pieceRateIds as $pieceRateId) {
          $stmt->bind_param("ss", $empid, $pieceRateId);
          $stmt->execute();
      }
  
      $stmt->close();
    
      echo "<script> alert('Data Inserted Successfully')</script>";
      echo "<script>window.location.href = 'EmployeeList';</script>";
      exit;
    }
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

    .form-select,.form-control{
      border: gray 1px solid;
    }
    .multiselect-dropdown span.optext {
      background-color: inherit;  

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
    echo $empid;
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
        <a href="EmployeeList" style="text-decoration: none; color: black" class="mr-1">Close</a>
        <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php 
  $empid = $_GET['empid'];
  echo $empid;

  include 'config.php';

  $sql = "SELECT * FROM employee_tb WHERE empid = $empid";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="pakyawan-container shadow d-flex flex-column justify-content-between" style="width: 80%; position: absolute; left: 18%; top: 14%; height: 82%; background-color: #fff; border-radius: 0.625em; ">
            <div>
              <div class="title-header mt-3" style="width: 97%; margin:auto; background-color: #ccc; height: 3.125em; border-radius: 0.625em; display:flex; align-items: center;">
                <p class="ml-3" style="font-size: 1.5em; font-weight: 500">Pakyawan Employment</p>
            
              </div>
              <div class="pakyawan-information mt-3" style=" width: 96%; margin:auto;  ">
                  <div class="row">
                      <div class="form-group col">
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" id="">
                        <label for="">First Name</label><br>
                        <input class="form-control" type="text" placeholder="First Name" name="fname" value="<?php echo $row['fname']?>" id="">
                      </div>
                      <div class="form-group col">
                        <label for="">Middle Name</label><br>
                        <input class="form-control" type="text" placeholder="Middle Name" name="mname" value="<?php echo $row['mname']?>" id="">
                      </div>
                      <div class="form-group col" >
                        <label for="">Last Name</label><br>
                        <input class="form-control" type="text" placeholder="Last Name" name="lname" value="<?php echo $row['lname']?>" id="">
                      </div>
                  </div>

                  <div class="row">
                      <div class="form-group col-8">
                        <label for="">Address</label><br>
                        <input class="form-control" type="text" placeholder="Address" name="address" value="<?php echo $row['address']?>" id="">
                      </div>
                      <div class="form-group col">
                        <label for="">Contact Number</label><br>
                        <input class="form-control" placeholder="Contact Number" type="text" name="contact" value="<?php echo $row['contact']?>"  id="" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                      </div>
                  </div>
                  
                  <div class="row">
                      <div class="form-group col">
                        <label for="">Civil Status </label><br>
                        <select name="cstatus" id="" placeholdber="Select Status" value="<?php echo $row['cstatus'];?>" class="form-select">
                          <option value="<?php echo $row['cstatus']?>" selected="selected" class="selectTag" style="color: gray;"><?php echo $row['cstatus']?></option>
                              <option value="Single" >Single</option>
                              <option value="Married">Married</option>
                          </select>
                      </div>
                      <div class="form-group col">
                        <label for="">Gender</label>
                          <select name="gender" id="" placeholdber="Select Gender" value="<?php echo $row['gender'];?>" class="form-select">
                            <option value="<?php echo $row['gender']?>" selected="selected" class="selectTag" style="color: gray;"><?php echo $row['gender']?></option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                          </select>
                      </div>
                      <div class="form-group col" >
                        <label for="">Date of Birth</label><br>
                        <input type="date" name="empdob" id="empdob" placeholder="Select Date of Birth" value="<?php echo $row['empdob'] ?>" class="form-control">
                      </div>
                  </div>

                  <?php 
                    include 'config.php';

                    $sql = "SELECT employee_tb.company_code, 
                    employee_tb.empid, 
                    assigned_company_code_tb.company_code_id, 
                    assigned_company_code_tb.empid, 
                    company_code_tb.id, 
                    company_code_tb.company_code AS company_code_name 
                    FROM assigned_company_code_tb 
                    INNER JOIN company_code_tb ON assigned_company_code_tb.company_code_id = company_code_tb.id 
                    INNER JOIN employee_tb ON assigned_company_code_tb.empid = employee_tb.empid 
                    WHERE assigned_company_code_tb.empid = $empid ";

                    $results = mysqli_query($conn, $sql);

                    $rows = mysqli_fetch_assoc($results);
                  ?>

                  <div class="row">
                      <div class="form-group col">
                        <label for="">Employee ID </label><br>
                        <div class="d-flex flex-row w-100" style="">
                          <input type="text" name="" id="" class="form-control" value="<?php echo $rows['company_code_name']; ?>" readonly style="width: 20%">
                          <input class="form-control" type="text" name="empid" id="" value="<?php echo $row['empid'] ?>" readonly style="width: 80%">
                        </div>
                      </div>
                    <?php 
                    include 'config.php';
                    $sql=  "SELECT employee_tb.*, classification_tb.classification FROM employee_tb
                            INNER JOIN classification_tb ON employee_tb.classification = classification_tb.id
                            WHERE employee_tb.empid = $empid";
                    $resulta = mysqli_query($conn, $sql);
                    $cRow = mysqli_fetch_assoc($resulta);
                    ?>

                      <div class="form-group col">
                        <label for="">Employement Classification</label><br>
                        <input class="form-control" type="text" name="classification" id="" value="<?php echo $cRow['classification']?>" readonly>
                      </div>
                      <div class="form-group col" >
                        <?php
                          include 'config.php';

                          $sql = "SELECT * FROM branch_tb";
                          $results = mysqli_query($conn, $sql);
                          $options = "";
                          while ($rows = mysqli_fetch_assoc($results)) {
                                  $selected = ($rows['id'] == $row['empbranch']) ? 'selected' : '';
                                  $options .= "<option value='".$rows['id']."' ".$selected.">" .$rows['branch_name'].  "</option>";
                              }
                        ?>
                                          
                        <label for="empbranch">Branch</label><br>
                        <select class="form-select" name="empbranch" id="" placeholder="Branch" value="<?php echo $row['branch_name'];?>">
                            <?php echo $options; ?>
                        </select>
                      </div>
                  </div>

                  <div class="row">
                      <div class="form-group col">
                      <?php
                          include 'config.php';
                          $sql = "SELECT * FROM employee_tb WHERE `role` = 'admin' OR `role` = 'Supervisor'";
                          $result = mysqli_query($conn, $sql);
                          $options = "";
                          while ($rows = mysqli_fetch_assoc($result)) {
                                  $options .= "<option  style='display:flex; font-size: 10px; font-style:normal;' value='".$rows['empid']."'>".$rows['fname']. " " .$rows['lname']." </option>";
                                }
                                    
                              $employee_ID = $_GET['empid'];

                                $query = "SELECT * FROM approver_tb WHERE empid = $employee_ID";
                                $result = $conn->query($query);
                                          
                                  // Check if any rows are fetched
                                  if ($result->num_rows > 0) {
                                      $array_approver = array(); // Array to store the approvers
                                          
                                    // Loop through each row
                                    while ($row = $result->fetch_assoc()) {
                                        $approver_empid = $row["approver_empid"];
                                        $array_approver[] = array('approver_empid' => $approver_empid);
                                          }
                                  }
                                          
                          ?>
                                        
                        <label for="approver">Immediate Superior/Approver</label><br>
                        <select class="form-select" class="approver-dd" name="approver[]" id="approver_ID" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2">
                            <?php
                            foreach ($array_approver as $approvers) {
                                $approver_ID = $approvers['approver_empid'];
                        
                                $query = "SELECT * FROM employee_tb WHERE empid = $approver_ID";
                                $result = $conn->query($query);
                        
                                // Check if any rows are fetched
                                if ($result->num_rows > 0) {
                                  $row_emp_approver = mysqli_fetch_assoc($result);
                                  
                                  echo '<option value="' . $approver_ID . '" selected>' . $row_emp_approver['fname'] . ' ' . $row_emp_approver['lname'] . '</option>';
                                                    
                                }
                            }
                            echo $options;
                            ?>
                        </select>
                      </div>
                      <div class="form-group col">
                        <?php
                          include 'config.php';

                          $sql = "SELECT * FROM piece_rate_tb";
                          $result = mysqli_query($conn, $sql);
                          
                          $options = "";
                          while ($row = mysqli_fetch_assoc($result)) {
                            
                              $work_basedSQL = "SELECT * FROM employee_pakyawan_work_tb 
                                                WHERE employee_pakyawan_work_tb.empid = $empid AND piece_rate_id = " . $row['id'];
                              $work_basedResult = mysqli_query($conn, $work_basedSQL);
                          
                              // Check if the piece rate item is not assigned to the employee
                              if (mysqli_num_rows($work_basedResult) == 0) {
                                  $options .= "<option value='" . $row['id'] . "' style='display:flex; font-size: 16px; font-style:normal;'>".$row['unit_type']."</option>";
                              }
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
                          <select name="piece_rate_id[]" class="form-select" id="pakyawan_work_edit" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2">
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
                      <div class="form-group col">
                      <?php 
                          include 'config.php';

                          $sql = "SELECT * FROM employee_tb WHERE empid = $empid";

                          $freqResult = mysqli_query($conn, $sql);

                          $freqRow = mysqli_fetch_assoc($freqResult);
                        
                        ?>
                      <label for="">Frequency</label><br>
                        <select name="work_frequency" id="" placeholder="Frequency" value="<?php echo $freqRow['work_frequency'];?>" class="form-select">
                          <option value="<?php echo @$freqRow['work_frequency']?>" selected="selected" class="selectTag" style="color: gray;"><?php echo @$freqRow['work_frequency']?></option>
                              <option value="Daily" >Daily</option>
                              <option value="Weekly">Weekly</option>
                              
                          </select>
                      </div>
                  </div>        
              </div>
            </div>  
            <div class="action-button mb-5 p-4 " style="">
              <a href="EmployeeList.php" class="mr-3" style="text-decoration: none; font-size: 1.3em">Cancel</a>
              <button type="submit" class="btn btn-primary" style="font-size: 1.1em">Update</button>
            </div>
        </div>
    </form>        
  
      



           
 
<!-- <script type="text/javascript">
      document.getElementById("customFile").onchange = function(){
          document.getElementById("form").submit();
      };
</script> -->

<script>
    // sched form modal

let allowanceModal = document.getElementById('allowance-modal');

//get open modal
let allowanceBtn = document.getElementById('allowance-update');

//get close button modal
let allowanceClose = document.getElementsByClassName('allowance-modal-close')[0];

//event listener
allowanceBtn.addEventListener('click', openAllowance);
allowanceClose.addEventListener('click', exitAllowance);
window.addEventListener('click', clickOutsides);

//functions
function openAllowance(){
    allowanceModal.style.display ='block';
}

function exitAllowance(){
    allowanceModal.style.display ='none';
}

function clickOutsides(e){
    if(e.target == allowanceModal){
        allowanceModal.style.display ='none';    
    }
}

</script>

<script>
    var inputBox = document.getElementById("inputBox");

var invalidChars = [
  "-",
  "+",
  "e",
];

inputBox.addEventListener("input", function() {
  this.value = this.value.replace(/[e\+\-]/gi, "");
});

inputBox.addEventListener("keydown", function(e) {
  if (invalidChars.includes(e.key)) {
    e.preventDefault();
  }
});
</script>
    
    
<script>
 // Calculate the date 18 years ago
 var today = new Date();
var maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());


var minDate = new Date(today.getFullYear() - 70, today.getMonth(), today.getDate());

// Format the maxDate and minDate as YYYY-MM-DD
var maxDateFormatted = maxDate.toISOString().split("T")[0];
var minDateFormatted = minDate.toISOString().split("T")[0];

// Set the max and min attributes of the input element
document.getElementById("empdob").setAttribute("max", maxDateFormatted);
document.getElementById("empdob").setAttribute("min", minDateFormatted);


// sched form modal

let Modal = document.getElementById('emp-modal');

//get open modal
let modalBtn = document.getElementById('modal-update');

//get close button modal
let closeModal = document.getElementsByClassName('emp-modal-close')[0];

//event listener
modalBtn.addEventListener('click', openModal);
closeModal.addEventListener('click', exitModal);
window.addEventListener('click', clickOutside);

//functions
function openModal(){
    Modal.style.display ='block';
}

function exitModal(){
    Modal.style.display ='none';
}

function clickOutside(e){
    if(e.target == Modal){
        Modal.style.display ='none';    
    }
}

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
        var select = document.getElementById("approver_ID");
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    

  
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
</html>