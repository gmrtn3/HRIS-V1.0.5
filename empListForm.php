
<?php
  error_reporting();
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

    if(isset($_GET['empidError'])){
        $empidError = "Employee aD does exist.";
        echo "<script> alert('$empidError')</script>";
    }

    if(isset($_GET['passError'])){
        $passError = "Password does not match!";
        echo "<script> alert('$passError')</script>";
    }

    include 'config.php';
    $result = mysqli_query($conn, "SELECT * FROM settings_company_tb");
    $row_setting = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    

    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  
   
    <link rel="stylesheet" href="css/virtual-select.min.css">
    <link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css">
      
    <script type="text/javascript" src="js/multi-select-dd.js"></script>
    <title>HRIS | Employee List Form</title>
</head>
<body>

<style>

    .emp-Access-password #eye{
       font-size:  1.3em !important;
       position: absolute !important;
       bottom: 0.1em !important;
        right: 5% !important;
        cursor: pointer !important;
        transform: translateY(-50%);
    }

    .emp-Access-cpassword #confirm-eye{
       font-size: 1.3em !important;
       position: absolute !important;
       bottom: 0.1em !important;
        right: 5% !important;
        cursor: pointer !important;
        transform: translateY(-50%);
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
        height: 45px !important;
    }



</style>



<!-- 
<div class="empListForm-container">
                <form action="" method="POST">
                    <div class="employeeList-modal" id="Modal">
                        <div class="employeeList-info-container">
                            <div class="emp-title" style="display:flex; flex-direction:space-row; align-items: center; justify-content:space-between; width: 1440px;">
                                <h1>Personal Information</h1>
                                <span class="fa-solid fa-pen-to-square" style="color: #000000; cursor: pointer; margin-right: 20px; font-size: 20px;"></span>  
                            </div>
                            <div class="emp-info-first-container">
                                
                            </div>
                        </div>         
                    </div>
                </form>
            </div> -->
    <!-- <script>
        $(document).ready(function(){
            $("form").submit(function(event){
                event.preventDefault();
                var fname = $("#form-fname").val();
                var lname = $("#form-lname").val();
                var empid = $("#form-empid").val();
                var contact = $("#form-contact").val();
                var email = $("#form-email").val();
                var submit = $("#form-submit").val();

                $(".erorr-message").load("Data Controller/Employee List/empListFormController.php", {
                    fname: fname,
                    lname: lname,
                    empid: empid,
                    contact: contact,
                    email: email,
                    submit: submit 
                });
                
            });
        }); -->
    <style>
        input{
            border: #333 1px solid !important;
        }
    </style>
        
    <header>
        <?php include("header.php")?>
    </header>

    <div class="empListForm-container" style="background-color: #fff;">
        <!-------------------------------------------------------ERROR MESSAGE ALERT------------------------------------------------------------------->
<?php
    if (isset($_GET['error'])) {
        $err = $_GET['error'];
        echo '<div id="alert-message" class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        '.$err.'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
    }
?>
<!------------------------------------------------------- END NG ERROR MESSAGE ALERT------------------------------------------------------------>
 <!-- <input type="checkbox" name="pakyawan" id="toggleCheckbox">
                                    <label for="" style="font-weight: 300; font-size: 1em; font-style: italic">Manny Pakyawan</label> -->

        <form id="myForm" action="Data Controller/Employee List/empListFormController.php" method="POST" enctype="multipart/form-data">
            <div class="employeeList-modal" id="Modal">
                    <div class="employeeList-modal-content">
                        <div class="employeeList-info-container">
                            <div class="emp-title d-flex flex-row justify-content-between" style="">
                                <h1>Personal Information</h1>
                                <div class="mr-2">
                                   
                                </div>
                            </div>

                            <!-- for employee id Auto Increment -->
                           
                            <div class="emp-info-first-input">
                                <div class="emp-info-fname">
                                        <label for="fname">First Name</label><br>
                                        <input class="" id="form-fname" type="text" name="fname" placeholder="First Name" id="fname" onkeyup='saveValue(this);' value="<?php echo isset($_GET['fname']) ? $_GET['fname'] : ''; ?>" required>
                                        
                                </div>
                                <div class="emp-info-mname">
                                        <label for="lname">Middle Name</label><br>
                                        <input type="text" name="mname" id="form-lname" placeholder="Middle Name" id="lname" onkeyup='saveValue(this);' value="<?php echo isset($_GET['mname']) ? $_GET['mname'] : ''; ?>" required>
                                </div>
                                <div class="emp-info-lname">
                                        <label for="lname">Last Name</label><br>
                                        <input type="text" name="lname" id="form-lname" placeholder="Last Name" id="mname" onkeyup='saveValue(this);' value="<?php echo isset($_GET['lname']) ? $_GET['lname'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="emp-info-second-input">
                                <div class="emp-info-address">
                                        <label for="address">Complete Address</label><br>
                                        <input type="text" name="address" id="" placeholder="Complete Address" value="<?php echo isset($_GET['address']) ? $_GET['address'] : ''; ?>" required>

                                </div>
                                <div class="emp-info-contact">
                                        <label for="contact">Contact Number</label><br>
                                        <input type="text" id="numberInput" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" name="contact" placeholder="Contact Number" value="<?php echo isset($_GET['contact']) ? $_GET['contact'] : ''; ?>" required>
                                        
                                </div>
                            </div>
                            <div class="emp-info-third-input">
                                <div class="emp-info-cstatus">
                                        <label for="cstatus">Civil Status</label><br>
                                        <select name="cstatus" id="" placeholdber="Select Status" required>
                                            <option value selected disabled>Select Status</option>
                                            <option value="Single" <?php echo isset($_GET['cstatus']) && $_GET['cstatus'] === 'Single' ? 'selected' : ''; ?>>Single</option>
                                            <option value="Married" <?php echo isset($_GET['cstatus']) && $_GET['cstatus'] === 'Married' ? 'selected' : ''; ?>>Married</option>
                                        </select>
                                </div>
                                <div class="emp-info-gender">
                                        <label for="gender">Gender</label><br>
                                        <select name="gender" id=""  placeholdber="Select Gender" required>
                                            <option value="" selected="selected" class="selectTag" style="color: gray;">Select Gender</option>
                                            <option value="Male" <?php echo isset($_GET['gender']) && $_GET['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                            <option value="Female" <?php echo isset($_GET['gender']) && $_GET['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                          
                                        </select>
                                </div>
                                <div class="emp-info-dob">
                                    <label for="empdob" required>Date of Birth</label><br>
                                    <input type="date" name="empdob" id="empdob" placeholder="Select Date of Birth" value="<?php echo isset($_GET['empdob']) ? $_GET['empdob'] : ''; ?>" required>         
                                </div>
                            </div>
                            <div class="emp-info-fourth-input w-100 d-flex flex-start ml-3 mt-1">
                              <div class="emp-info-empID" style=" width: 25.6%; margin-left: 1.7%;">
                                <label for="empid" >Employee ID</label><br>                                     
                                 <?php 
                                     $server = "localhost";
                                     $user = "root";
                                     $pass ="";
                                     $database = "hris_db";

                                     $conn = mysqli_connect($server, $user, $pass, $database);
                                     $sql = "SELECT * FROM company_code_tb";
                                     $result = mysqli_query($conn, $sql);
                                   

                                    $options = "";
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $options .= "<option value='". $row['id'] . "'>" .$row['company_code'].  "</option>"; 
                                    }
                                    ?>
                                    <div style="display:flex; flex-direction: row">
                                    <select name="company_code" id=""  style="display: flex; align-items: center; justify-content: center;width: 25%; padding: 0.2em; margin-right: 2%; height: 40px">
                                        <?php echo $options; ?>
                                    </select>

                                    <?php 
                                            include 'config.php';
                                                $sql = "SELECT empid FROM employee_tb ORDER BY empid DESC LIMIT 1";
                                                $result = mysqli_query($conn, $sql);
                                                $row = mysqli_fetch_assoc($result);

                                                $empid = '';

                                                if ($row) {
                                                    $lastEmpID = $row['empid'];
                                                    $nextEmpID = (int)$lastEmpID + 1;
                                                    
                                                    if ($nextEmpID < 10) {
                                                        $nextEmpIDFormatted = sprintf("%03d", $nextEmpID); // Format for 001-009
                                                    } elseif ($nextEmpID < 100) {
                                                        $nextEmpIDFormatted = sprintf("%03d", $nextEmpID); // Format for 010-099
                                                    } else {
                                                        $nextEmpIDFormatted = $nextEmpID; // No leading zeros for 100 and beyond
                                                    }
                                                } else {
                                                    // No existing employee IDs, start from '001'
                                                    $nextEmpIDFormatted = '001';
                                                }
                                        ?>
                                    <input type="text" name="empid" id="form-empid" class="p-1 form-control" placeholder="Employee ID" maxlength="6" style="width: 73%; height: 2.9em" value="<?php echo $nextEmpIDFormatted; ?>" readonly>  
                                    </div>
                                    <span id="empid-error" style="color: red;"></span>
                                </div>
                        </div> 
                          
                        <div class="employeeList-empDetail-container">
                            <div class="emp-title">
                                <h1>Employment Details</h1>
                            </div>
                            <div class="emp-empDetail-first-input">
                            <div class="empInfo-classification">
                                     
                                     <?php
                                        include 'config.php';

                                        $sql = "SELECT * FROM classification_tb";
                                        $results = mysqli_query($conn, $sql);
                                        $options = "";
                                        while ($rows = mysqli_fetch_assoc($results)) {
                                           $options .="<option value='".$rows['id']."'>".$rows['classification']."</option>";
                                        }
                                        ?>
                                        
                                        <label for="classification">Employment Classification</label><br>
                                        <select  name="classification" id="classification" placeholder="" required>
                                        <option value disabled selected>Select Classification</option> 
                                            <?php echo $options; ?>
                                        </select>
                                </div>

                                <div class="emp-empDetail-dateHired">
                                    <label for="empdate_hired">Date Hired</label><br>
                                    <input type="date" name="empdate_hired" id="empdate_hired" placeholder="Date Hired" value="<?php echo isset($_GET['empdate_hired']) ? $_GET['empdate_hired'] : ''; ?>" required >
                                </div> 
                              
                                <div class="emp-empDetail-depts" >
                                      <?php
                                        include 'config.php';
                                        $sql = "SELECT * FROM branch_tb";
                                        $result = mysqli_query($conn, $sql);

                                        $options = "";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $options .= "<option value='". $row['id'] . "'>" .$row['branch_name'].  "</option>"; //I-integer yung data column ng department name sa employee_tb
                                        }
                                        ?>

                                    <label for="empbranch">Select Branch</label><br>
                                        <select name="empbranch"   id="branch" required >
                                        <option value disabled selected>Select Branch</option>
                                          <?php echo $options; ?>
                                        </select>
                                </div>
                            </div>
                            <div class="emp-empDetail-second-input" style=" ">
                                <script>
                                    function calculateDailyRate() {
                                        const basicSalary = document.getElementById('empbsalary').value;
                                        const dailyRateInput = document.getElementById('drate');
                                        if (basicSalary.trim() === '') {
                                            dailyRateInput.setAttribute('placeholder', 'Daily Rate');
                                            dailyRateInput.value = '';
                                        } else {
                                            const dailyRate = parseFloat(basicSalary) / 22;
                                            dailyRateInput.removeAttribute('placeholder');
                                            dailyRateInput.value = dailyRate.toFixed(2);
                                        }
                                    }
                                </script>
                                <div class="emp-empDetail-approver">
                                <div>
                                    <?php
                                        include 'config.php';
                                        $sql = "SELECT * FROM employee_tb WHERE `role` = 'Admin' OR `role` = 'Supervisor'";
                                        $result = mysqli_query($conn, $sql);

                                        $options = "";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            
                                            $options .= "<option value='" . $row['empid'] . "' style='display:flex; font-size: 16px; font-style:normal;'>".$row['fname']. " ". " " ." ".$row['lname']." </option>";
                                        }
                                        ?>

                                        
                                        <label for="approver">Immediate Superior/Approver</label><br>
                                        <select class="approver-dd" name="approver[]" id="approver" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2" style="display:flex; width: 380px;" readonly>
                                            <?php echo $options ?>
                                           
                                        </select>
                                        
                                    
                                    </div>
                                </div>
                                <div class="emp-empDetail-piece_rate" style="display:none;">
                                <div>
                                    <?php
                                       include 'config.php';
                                        $sql = "SELECT * FROM piece_rate_tb";
                                        $result = mysqli_query($conn, $sql);

                                        $options = "";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            
                                            $options .= "<option value='" . $row['id'] . "' style='display:flex; font-size: 16px; font-style:normal;'>".$row['unit_type']."</option>";
                                        }
                                        ?>

                                        
                                        <label for="pakyawan">Pakyawan Work Type</label><br>
                                        <select class="pakyawan-dd" name="piece_rate_id[]" id="piece_rate_id" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2" style="display:flex; width: 380px;" disabled>
                                            <?php echo $options ?>
                                           
                                        </select>
                                        <input type="hidden" name="piece_rate_id_hidden" id="piece_rate_id_hidden" value="<?php echo @$row['id']?>">

                                        
                                        <script>
                                            $(document).ready(function() {
                                            $('.pakyawan-dd').change(function() {
                                                var selectedValues = $(this).val();
                                                $('#piece_rate_id_hidden').val(JSON.stringify(selectedValues));
                                            });
                                            });
                                        </script>
                                    
                                    </div>
                                </div>
                                <div class="emp-empDetail-work_frequency" style="display:none;">
                                    <div>
                                        <label for="">Work Frequency</label><br>
                                        <select name="work_frequency" id="">
                                            <option value selected disabled>Select Frequency</option>
                                            <option value="Daily">Daily</option>
                                            <option value="Weekly">Weekly</option>
                                        </select>
                                    
                                    </div>
                                </div>
                               
                                <div class="emp-empDetail-bsalary">
                                    <label for="empbsalary">Basic Salary</label><br>
                                    <input type="number" id="empbsalary" name="empbsalary" oninput="calculateDailyRate()" required placeholder="Basic Salary" value="<?php echo isset($_GET['empbsalary']) ? $_GET['empbsalary'] : ''; ?>"  step="0.01" />

                                </div>
                                <div class="emp-empDetail-drate">
                                    <label for="drate">Daily Rate</label><br>
                                    <input type="text" name="drate" id="drate" placeholder="Daily Rate" required readonly class="form-control" style="height: 40px;" value="<?php echo isset($_GET['drate']) ? $_GET['drate'] : ''; ?>" readonly>
                                </div>
                            </div>
                            <div class="emp-empDetail-third-input" style="width: 57%; display: flex; flex-direction: row; justify-content: space-between ">
                                      
                                <div class="emp-empDetail-dept">
                                      <?php
                                        $server = "localhost";
                                        $user = "root";
                                        $pass ="";
                                        $database = "hris_db";

                                        $conn = mysqli_connect($server, $user, $pass, $database);
                                        $sql = "SELECT * FROM dept_tb";
                                        $result = mysqli_query($conn, $sql);

                                        $options = "";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $options .= "<option value='".$row['col_ID'] . "'>" .$row['col_deptname'].  "</option>"; //I-integer yung data column ng department name sa employee_tb
                                        }
                                        ?>

                                    <label for="depatment" >Select Department</label><br>
                                        <select name="col_deptname" style="width: 380px; height: 45px;" id="deparment" required>
                                        <option value disabled selected>Select Department</option>
                                          <?php echo $options; ?>
                                        </select>
                                </div>         

                                <div class="emp-empDetail-jposition">
                                    <?php
                                                    include 'config.php';

                                                     $sql = "SELECT * FROM positionn_tb";
                                                     $results = mysqli_query($conn, $sql);
             
                                                     $options = "";
                                                     while ($rows = mysqli_fetch_assoc($results)) {
                                                         $options .= "<option value='".$rows['id']."'>" .$rows['position'].  "</option>";
                                                     }
                                                     ?>
             
                                                 <label for="empposition">Select Positon</label><br>
                                                     <select required name="empposition" style="width: 380px; height: 45px;" id="position" value="<?php echo $row['position'];?>">
                                                     <option value disabled selected>Select Position</option> 
                                                       <?php echo $options; ?>
                                                     </select>
                                </div>

                            </div>
                        </div>

                        <div class="employeeList-govern-container hide-element">
                            <div class="emp-title">
                                <h1>Government Information</h1>
                            </div>
                            <div class="emp-govern-first-input">
                                <div class="emp-govern-sss">
                                    <label for="empsss">SSS #</label><br>
                                    <input type="text" name="empsss"  placeholder="Input SSS#" value="<?php echo isset($_GET['empsss']) ? $_GET['empsss'] : ''; ?>" id="sss" >
                                </div>
                                <div class="emp-govern-TIN">
                                    <label for="emptin">TIN</label><br>
                                    <input type="text" name="emptin" id="tin" placeholder="Input TIN" value="<?php echo isset($_GET['emptin']) ? $_GET['emptin'] : ''; ?>" >
                                </div>
                            </div>
                            <div class="emp-govern-second-input">
                                <div class="emp-govern-pagibig">
                                    <label for="emppagibig">Pagibig #</label><br>
                                    <input type="text" name="emppagibig" id="pagibig" placeholder="Input Pagibig #" value="<?php echo isset($_GET['emppagibig']) ? $_GET['emppagibig'] : ''; ?>" >
                                </div>
                                <div class="emp-govern-TIN">
                                    <label for="empphilhealth">Philhealth #</label><br>
                                    <input type="text" name="empphilhealth" id="philhealth" placeholder="Input Philhealth #" value="<?php echo isset($_GET['empphilhealth']) ? $_GET['empphilhealth'] : ''; ?>" >
                                </div>
                            </div>
                        </div>
                        
                        <div class="employeeList-allowance-container hide-element">
                            <div class="emp-title">
                                <h1>Employee Monthly Allowance</h1>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#allowanceModal" id="modal-update" style="margin-left: 70%; background-color: inherit;" class='link-dark editbtn border-0'><i class='fa-solid fa-pen-to-square fs-5 me-3' title='Edit'></i>Edit</button>
                            </div>

                            <div class="emp-allowance-first-input">
                                <div class="emp-allowance-transpo">
                                    <label for="emptranspo"><?php echo $newTranspoLabel; ?></label><br>
                                    <input type="text" id="transpo" name="emptranspo" placeholder="Allowance Amount" id="numberInput" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 8) this.value = this.value.slice(0, 8);" value="<?php echo isset($_GET['emptranspo']) ? $_GET['emptranspo'] : ''; ?>" >
                                </div>
                                <div class="emp-allowance-meal">
                                    <label for="empmeal"><?php echo $newMealLabel; ?></label><br>
                                    <input type="text" id="meal" name="empmeal" placeholder="Allowance Amount" id="numberInput" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 8) this.value = this.value.slice(0, 8);" value="<?php echo isset($_GET['empmeal']) ? $_GET['empmeal'] : ''; ?>" > 
                                </div>
                                <div class="emp-allowance-internet">
                                    <label for="empinternet"><?php echo $newInternetLabel; ?></label><br>
                                    <input type="text" id="internet" name="empinternet" placeholder="Allowance Amount" id="numberInput" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 8) this.value = this.value.slice(0, 8);" value="<?php echo isset($_GET['empinternet']) ? $_GET['empinternet'] : ''; ?>" > 
                                </div>
                            </div>
                        </div>
                                <!-- Modal -->
                                <div class="modal fade" id="allowanceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Labels</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="newTranspoLabel">New Allowance Label</label>
                                                    <input type="text" class="form-control" id="newTranspoLabel" placeholder="Enter new label">
                                                </div>

                                                <div class="form-group">
                                                    <label for="newMealLabel">New Allowance Label</label>
                                                    <input type="text" class="form-control" id="newMealLabel" placeholder="Enter new label">
                                                </div>

                                                <div class="form-group">
                                                    <label for="newInternetLabel">New Allowance Label</label>
                                                    <input type="text" class="form-control" id="newInternetLabel" placeholder="Enter new label">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" onclick="updateLabels()">Save</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             <!---Modal-->
                        
                        <div class="employeeList-schedule-input hide-element">
                            <div class="emp-title">
                                <h1>Schedule</h1>
                            </div>

                            <div class="emp-schedule-first-input">
                                <div class="emp-schedule-accessID">
                                <?php
                                        $server = "localhost";
                                        $user = "root";
                                        $pass ="";
                                        $database = "hris_db";

                                        $conn = mysqli_connect($server, $user, $pass, $database);
                                        $sql = "SELECT * FROM schedule_tb";
                                        $result = mysqli_query($conn, $sql);

                                        $options = "";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $options .= "<option value='".$row['schedule_name']."'>".$row['schedule_name']."</option>"; //I-integer yung data column ng department name sa employee_tb
                                        }
                                        ?>

                                    <label for="schedule_name">Select Schedule Type</label><br>
                                        <select name="schedule_name" id="schedule" >
                                        <option value disabled selected>Select Schedule Type</option>
                                          <?php echo $options; ?>
                                        </select>                            
                                </div>
                                <div class="emp-schedule-startDate">
                                    <label for="sched_from">Start Date</label><br>
                                    <input type="date" name="sched_from" id="sched_from" placeholder="Start Date" >  
                                </div>
                                <div class="emp-schedule-endDate">
                                    <label for="sched_to">End Date</label><br>
                                    <input type="date" name="sched_to" id="sched_to" placeholder="End Date" >  
                                </div>
                            </div>
                        </div>

                        <div class="employeeList-empAccess-container hide-element">
                            <div class="emp-title">
                                <h1>Employee Access</h1>
                            </div>
                            <div class="emp-Access-first-input">
                                <div class="emp-Access-access_id">
                                        <label for="empaccess_id">Access ID</label><br>
                                        <input type="text" name="empaccess_id" id="access_id" placeholder="Access ID"  required>
                                </div>
                                <div class="emp-empAccess-username">
                                    <label for="username">Username</label><br>
                                    <input type="text" name="username" id="username" placeholder="Username"  required>
                                </div>
                                <div class="emp-empAccess-role">
                                    <label for="role">Role</label><br>
                                    <select name="role" id="role"  required>
                                            <option value="" selected="selected" class="selectTag" style="color: gray;" >Select Role</option>
                                            <option value="Employee">Employee</option>
                                            <option value="admin">Admin</option>
                                            <option value="Supervisor">Supervisor</option>
                                            
                                    </select>  
                                </div>
                            </div>
                            <div class="emp-Access-second-input">
                                <div class="emp-Access-email">
                                        <label for="email">Personal Email</label><br>
                                        <input pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9.-]+\.[a-z]{2,}"  type="email" name="email" id="form-email" placeholder="Email Address" title="Must be a valid email."  required>
                                </div>
                                <div class="emp-Access-email">
                                        <label for="email">Company Email</label><br>
                                        <input pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9.-]+\.[a-z]{2,}"  type="text" name="comp_email" id="form-emails" placeholder="Email Address" title="Must be a valid email."  value="<?php echo $row_setting['email_domain']?>"required>
                                </div>
                            </div>
                            <div class="emp-Access-password">
                                    <label for="password">Password</label><br>
                                    <input type="password"  pattern="[a-zA-Z0-9]{5,}" title="Must be at least 5 characters." oninput="Pass()" oninput="showPasswordIcon(this, 'eye')" name="password" id="pass" placeholder="Password" required>
                                    <i class="fas fa-eye show-pass" aria-hidden="true" id="eye" style="display: none;" onclick="togglePassword()"></i>
                                </div>

                                <div class="emp-Access-cpassword">
                                    <label for="cpassword">Confirm Password</label><br>
                                    <input type="password"  pattern="[a-zA-Z0-9]{5,}" title="Must be at least 5 characters." disabled oninput="matchPass()" oninput="showPasswordIcon(this, 'confirm-eye')" name="cpassword" id="cpass" placeholder="Confirm Password" required>
                                    <i class="fas fa-eye show-pass" aria-hidden="true" id="confirm-eye" style="display: none;" onclick="toggleConfirmPassword()"></i>
                                </div>  
                                <p  id="id_pValidate" style="margin-top: 5px; margin-right: 825px; color: red; display: none; text-align: right;">Passwords don't match!</p>
                        </div>

                        <!-- <div class="password_sec">

                        </div> -->
                        
                    <div class="empList-save-btn">
                        <div>
                            <a style="margin-right: 10px; font-size: 20px; text-decoration: none" href="EmployeeList.php">Cancel</a>
                            <span class="modalSave" style="border: none"> <input class="submit" id="btn_save" type="submit" value="Save" style="border: none"></span>
                        </div>
                    </div>
                </div>
            </form>
             

            </div>
    
        </div>

<!--------------------Script sa pagchange ng Label sa allowance---------------------->       
<script>
    // Retrieve cookies when the page loads
    window.onload = function() {
        var newTranspoLabel = getCookie("newTranspoLabel");
        var newMealLabel = getCookie("newMealLabel");
        var newInternetLabel = getCookie("newInternetLabel");

        // Set the labels on the page with retrieved values
        document.querySelector(".emp-allowance-transpo label").textContent = newTranspoLabel;
        document.querySelector(".emp-allowance-meal label").textContent = newMealLabel;
        document.querySelector(".emp-allowance-internet label").textContent = newInternetLabel;
    };

    function updateLabels() {
        // Get the new label values from input fields
        var newTranspoLabel = document.getElementById("newTranspoLabel").value;
        var newMealLabel = document.getElementById("newMealLabel").value;
        var newInternetLabel = document.getElementById("newInternetLabel").value;

        // Update the labels on the page
        document.querySelector(".emp-allowance-transpo label").textContent = newTranspoLabel;
        document.querySelector(".emp-allowance-meal label").textContent = newMealLabel;
        document.querySelector(".emp-allowance-internet label").textContent = newInternetLabel;

        // Close the modal
        $('#allowanceModal').modal('hide');

        // Set cookies
        document.cookie = "newTranspoLabel=" + newTranspoLabel;
        document.cookie = "newMealLabel=" + newMealLabel;
        document.cookie = "newInternetLabel=" + newInternetLabel;

        // Create an object with the new labels
        var newLabels = {
            newTranspoLabel: newTranspoLabel,
            newMealLabel: newMealLabel,
            newInternetLabel: newInternetLabel
        };

        fetch('editempListForm.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(newLabels)
        })
        .then(response => response.text())
        .then(data => {
            // Handle the response from editempListForm.php if needed
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });

        // Send the new labels to PayReport.php using fetch
        fetch('PayReport.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(newLabels)
        })
        .then(response => response.text())
        .then(data => {
            // Handle the response from PayReport.php if needed
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });


        // Send the new labels to PayReport.php using fetch
        fetch('gnrate_payroll_prac.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(newLabels)
        })
        .then(response => response.text())
        .then(data => {
            // Handle the response from PayReport.php if needed
            console.log(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    

    // Retrieve cookies function
    function getCookie(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) return parts.pop().split(";").shift();
    }
</script>
<!--------------------Script sa pagchange ng Label sa allowance---------------------->      




        <!-- <script>
   const checkbox = document.getElementById('toggleCheckbox');

   checkbox.addEventListener('click', function() {
      const form = document.getElementById('myForm');
      const elementsToHide = document.querySelectorAll('.hide-element');
      const additionalElement = document.getElementById('pakyawan-additional');

      if (checkbox.checked) {
         elementsToHide.forEach(function(element) {
            element.style.maxHeight = '0';
            element.style.overflow = 'hidden';
            element.style.transition = 'max-height 0.3s ease';
            removeRequiredAttribute(element);
         });
         additionalElement.style.display = 'block';
         additionalElement.style.display = additionalElement.style.display === 'block' ? 'flex' : 'block';
         form.action = 'Data Controller/Employee List/pakyawanController.php';
         // Store checkbox state in localStorage
         localStorage.setItem('checkboxState', 'checked');
      } else {
         elementsToHide.forEach(function(element) {
            element.style.maxHeight = '';
            element.style.overflow = '';
            element.style.transition = '';
            restoreRequiredAttribute(element);
         });
         additionalElement.style.display = 'none';
         form.action = 'Data Controller/Employee List/empListFormController.php';
         // Remove checkbox state from localStorage
         localStorage.removeItem('checkboxState');
      }
   });

   // Helper function to remove the 'required' attribute from input and select elements
   function removeRequiredAttribute(element) {
      const requiredInputs = element.querySelectorAll('input[required], select[required]');
      requiredInputs.forEach(function(input) {
         input.removeAttribute('required');
      });
   }

   // Helper function to restore the 'required' attribute on input and select elements
   function restoreRequiredAttribute(element) {
      const requiredInputs = element.querySelectorAll('input[required], select[required]');
      requiredInputs.forEach(function(input) {
         input.setAttribute('required', 'required');
      });
   }
</script>



<style>
   .hide-element {
      max-height: none;
      overflow: visible;
   }
</style> -->
  

<script>
  function togglePassword() {
    var passwordInput = document.getElementById("pass");
    var eyeIcon = document.getElementById("eye");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    } else {
      passwordInput.type = "password";
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    }
  }

  function toggleConfirmPassword() {
    var confirmPasswordInput = document.getElementById("cpass");
    var confirmEyeIcon = document.getElementById("confirm-eye");

    if (confirmPasswordInput.type === "password") {
      confirmPasswordInput.type = "text";
      confirmEyeIcon.classList.remove("fa-eye");
      confirmEyeIcon.classList.add("fa-eye-slash");
    } else {
      confirmPasswordInput.type = "password";
      confirmEyeIcon.classList.remove("fa-eye-slash");
      confirmEyeIcon.classList.add("fa-eye");
    }
  }

  function showPasswordIcon(input, iconId) {
    var eyeIcon = document.getElementById(iconId);
    if (input.value !== "") {
      eyeIcon.style.display = "inline-block";
    } else {
      eyeIcon.style.display = "none";
    }
  }
</script>

<script text="text/javascript" src="js/virtual-select.min.js"> </script>

<script type="text/javascript">
   VirtualSelect.init({ 
  ele: '#multipleSelect' 
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

 // Get references to the date hired, start date, and end date input fields
 const dateHiredInput = document.querySelector('[name="empdate_hired"]');
    const startDateInput = document.querySelector('[name="sched_from"]');
    const endDateInput = document.querySelector('[name="sched_to"]');

    // Function to enable/disable the start date and end date fields
    function toggleDateFields() {
        if (dateHiredInput.value !== '') {
            const selectedDate = dateHiredInput.value;
            startDateInput.min = selectedDate;
            endDateInput.min = selectedDate;
            startDateInput.disabled = false;
            endDateInput.disabled = false;
        } else {
            startDateInput.disabled = true;
            endDateInput.disabled = true;
        }
    }

    // Disable the start date and end date fields initially
    toggleDateFields();

    // Add an event listener to the date hired field
    dateHiredInput.addEventListener('change', toggleDateFields);

function Pass(){
    let pass = document.getElementById('pass').value;
    let cpass = document.getElementById('cpass').value;
   
    if(pass === ""){
        document.getElementById('cpass').disabled = true;
    }
    else{
        document.getElementById('cpass').disabled = false;

        
    if(cpass != pass){
        
        document.getElementById('id_pValidate').style.display = "";
        document.getElementById('btn_save').style.cursor = "no-drop";
        document.getElementById('btn_save').disabled = true;
    }
    else{
        document.getElementById('id_pValidate').style.display = "none";
        document.getElementById('btn_save').style.cursor = "pointer";
        document.getElementById('btn_save').disabled = false;
    }
    }
}
function matchPass(){
    let pass = document.getElementById('pass').value;
    let cpass = document.getElementById('cpass').value;

    if(pass != cpass){
        
        document.getElementById('id_pValidate').style.display = "";
        document.getElementById('btn_save').style.cursor = "no-drop";
        document.getElementById('btn_save').disabled = true;
    }
    else{
        document.getElementById('id_pValidate').style.display = "none";
        document.getElementById('btn_save').style.cursor = "pointer";
        document.getElementById('btn_save').disabled = false;
    }
}


</script>

<script>
   
  document.addEventListener("DOMContentLoaded", function() {

   var btn = document.getElementById("btn_save");
    var classificationSelect = document.getElementById("classification");
    var department = document.getElementById("deparment");
    var position = document.getElementById("position");
    var empbsalary = document.getElementById("empbsalary");
    var drate = document.getElementById("drate");
    var approver = document.getElementById("approver");
    var empdate_hired = document.getElementById("empdate_hired");
    var branch = document.getElementById("branch");
    var sss = document.getElementById("sss");
    var tin = document.getElementById("tin");
    var pagibig = document.getElementById("pagibig");
    var philhealth = document.getElementById("philhealth");
    var transpo = document.getElementById("transpo");
    var meal = document.getElementById("meal");
    var internet = document.getElementById("internet");
    var schedule = document.getElementById("schedule");
    var sched_from = document.getElementById("sched_from");
    var sched_to = document.getElementById("sched_to");
    var access_id = document.getElementById("access_id");
    var username = document.getElementById("username");
    var role = document.getElementById("role");
    var formEmail = document.getElementById("form-email");
    var compEmail = document.getElementById("form-emails");
    var pass = document.getElementById("pass");
    var cpass = document.getElementById("cpass");
    var form = document.getElementById("myForm");
    var governContainer = document.querySelector(".employeeList-govern-container");
    var allowanceContainer = document.querySelector(".employeeList-allowance-container");
    var scheduleInput = document.querySelector(".employeeList-schedule-input");
    var empAccessContainer = document.querySelector(".employeeList-empAccess-container");
    var empDrate = document.querySelector(".emp-empDetail-drate");
    var empSalary = document.querySelector(".emp-empDetail-bsalary");
    var empDept = document.querySelector(".emp-empDetail-dept");
    var empPosition = document.querySelector(".emp-empDetail-jposition");
    var pakyawan = document.querySelector(".emp-empDetail-piece_rate");
    var freq = document.querySelector(".emp-empDetail-work_frequency");
    //   var empDetailSecondInput = document.querySelector(".emp-empDetail-second-input");

    classificationSelect.addEventListener("change", function() {
      if (classificationSelect.value === "3") {
        function removeRequiredAttribute(element) {
      const requiredInputs = element.querySelectorAll('input[required], select[required]');
      requiredInputs.forEach(function(input) {
         input.removeAttribute('required');
      });
   }
        form.action = "Data Controller/Employee List/pakyawanController.php";
        governContainer.style.display = "none";
        allowanceContainer.style.display = "none";
        scheduleInput.style.display = "none";
        empAccessContainer.style.display = "none";
        empDrate.style.display = "none";
        empSalary.style.display = "none";
        empDept.style.display = "none";
        empPosition.style.display = "none";
        pakyawan.style.display = "block";
        freq.style.display = "block";
        pass.removeAttribute("disabled");
        cpass.removeAttribute("disabled");
        empbsalary.removeAttribute("required");
        department.removeAttribute("required");
        access_id.removeAttribute("required");
        username.removeAttribute("required");
        role.removeAttribute("required");
        formEmail.removeAttribute("required");
        pass.removeAttribute("required");
        cpass.removeAttribute("required");
        

        btn.addEventListener("click", function() {
            console.log("hehe");
        });
      } else {
        form.action = "Data Controller/Employee List/empListFormController.php";
        governContainer.style.display = "block";
        allowanceContainer.style.display = "block";
        scheduleInput.style.display = "block";
        empAccessContainer.style.display = "block";
        empDrate.style.display = "block";
        empSalary.style.display = "block";
        empDept.style.display = "block";
        empPosition.style.display = "block";
        pakyawan.style.display = "none";
        freq.style.display = "none";
        empbsalary.setAttribute("required");
        
      }

      if (classificationSelect.value !== "") {
        transpo.removeAttribute("disabled");
        meal.removeAttribute("disabled");
        internet.removeAttribute("disabled");
        department.removeAttribute("disabled");
        position.removeAttribute("disabled");
        empbsalary.removeAttribute("disabled");
        drate.removeAttribute("disabled");
        approver.removeAttribute("disabled");
        empdate_hired.removeAttribute("disabled");
        branch.removeAttribute("disabled");
        sss.removeAttribute("disabled");
        tin.removeAttribute("disabled");
        pagibig.removeAttribute("disabled");
        philhealth.removeAttribute("disabled");
        schedule.removeAttribute("disabled");
        sched_from.removeAttribute("disabled");
        sched_to.removeAttribute("disabled");
        access_id.removeAttribute("disabled");
        username.removeAttribute("disabled");
        role.removeAttribute("disabled");
        formEmail.removeAttribute("disabled");
        pass.removeAttribute("disabled");
        cpass.removeAttribute("disabled");
      } else {
        transpo.setAttribute("disabled", "disabled");
        meal.setAttribute("disabled", "disabled");
        internet.setAttribute("disabled", "disabled");
        department.setAttribute("disabled", "disabled");
        position.setAttribute("disabled", "disabled");
        empbsalary.setAttribute("disabled", "disabled");
        drate.setAttribute("disabled", "disabled");
        approver.setAttribute("disabled", "disabled");
        empdate_hired.setAttribute("disabled", "disabled");
        branch.setAttribute("disabled", "disabled");
        sss.setAttribute("disabled", "disabled");
        tin.setAttribute("disabled", "disabled");
        pagibig.setAttribute("disabled", "disabled");
        philhealth.setAttribute("disabled", "disabled");
        schedule.setAttribute("disabled", "disabled");
        sched_from.setAttribute("disabled", "disabled");
        sched_to.setAttribute("disabled", "disabled");
        access_id.setAttribute("disabled", "disabled");
        username.setAttribute("disabled", "disabled");
        role.setAttribute("disabled", "disabled");
        formEmail.setAttribute("disabled", "disabled");
        pass.setAttribute("disabled", "disabled");
        cpass.setAttribute("disabled", "disabled");
      }
    });
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

    

  
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
</body>
</html>