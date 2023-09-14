<?php

include 'config.php';

// Check if there is a file uploaded
if(isset($_FILES['emp_img']) && $_FILES['emp_img']['size'] > 0) {
    $file_name = $_FILES['emp_img']['name'];
    $file_tmp = $_FILES['emp_img']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid() . "." . $file_ext;
    move_uploaded_file($file_tmp, "uploads/" . $new_file_name);
    $_POST['emp_img_url'] = $new_file_name;
}

if(isset($row['emp_img_url'])) {
    $image_url = $row['emp_img_url'];
} else {
    $image_url = "default_image.png";
}

// Get file extension from image URL
$file_ext = pathinfo($image_url, PATHINFO_EXTENSION);

// Remove any additional extensions from the image URL
$image_url = str_replace("." . $file_ext, "", $image_url);

if(count($_POST) > 0){
    $emp_img_url = "";
    if (isset($_POST['emp_img_url'])) {
        $emp_img_url = ", emp_img_url='".$_POST['emp_img_url']."'";
    }

    $dailyRate_update = intval($_POST['empbsalary']) / 22;
    $dailyRate_update = number_format($dailyRate_update, 2);
    $dailyRate_update = str_replace(',', '', $dailyRate_update); // Remove comma

    mysqli_query($conn, "UPDATE employee_tb SET fname='".$_POST['fname']."',mname='".$_POST['mname']."', lname='".$_POST['lname']."',contact='".$_POST['contact']."',cstatus='".$_POST['cstatus']."',gender='".$_POST['gender']."',empdob='".$_POST['empdob']."',empsss='".$_POST['empsss']."',emptin='".$_POST['emptin']."',emppagibig='".$_POST['emppagibig']."',empphilhealth='".$_POST['empphilhealth']."',empbranch='".$_POST['empbranch']."',department_name='".$_POST['department_name']."',empbsalary='".$_POST['empbsalary']."', drate='". $dailyRate_update ."', otrate='".$_POST['otrate']."', empdate_hired='".$_POST['empdate_hired']."',emptranspo='".$_POST['emptranspo']."',empmeal='".$_POST['empmeal']."',empinternet='".$_POST['empinternet']."',empposition='".$_POST['empposition']."', role='".$_POST['role']."',email='".$_POST['email']."', company_email='".$_POST['comp_email']."',sss_amount='".$_POST['sss_amount']."', tin_amount='".$_POST['tin_amount']."', pagibig_amount='".$_POST['pagibig_amount']."', philhealth_amount='".$_POST['philhealth_amount']."', classification='".$_POST['classification']."', bank_name='".$_POST['bank_name']."', bank_number='".$_POST['bank_number']."'".$emp_img_url.", company_code='".$_POST['company_code']."'
    WHERE id ='".$_POST['id']."'");

    mysqli_query($conn, "UPDATE assigned_company_code_tb SET company_code_id='".$_POST['company_code']."' WHERE empid = '".$_POST['empid']."' ");
    header ("Location: EmployeeList.php");

    // echo $_POST['company_code'];


    // Insert into approver_tb table
$approverEmpIds = $_POST['approver'];
$empid_update= $_GET['empid'];

$query = "DELETE FROM approver_tb WHERE empid= $empid_update";
$query_run = mysqli_query($conn, $query);

if($query_run)
{
    foreach ($approverEmpIds as $approverEmpId) {
        
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


// ----------------END Insert into approver_tb table -------------------------
}



    $result = mysqli_query($conn, "SELECT * FROM employee_tb WHERE empid ='". $_GET['empid']. "'");
    $row = mysqli_fetch_assoc($result);  

    $empid = $row['empid'];

    $restdayResult = mysqli_query($conn, "SELECT restday FROM employee_tb AS emp
                                        INNER JOIN empschedule_tb AS esched ON esched.empid = emp.empid
                                        INNER JOIN schedule_tb AS sched ON sched.schedule_name = esched.schedule_name
                                        WHERE emp.empid = '".$_GET['empid']."'");
    $restdayRow = mysqli_fetch_assoc($restdayResult);



  
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
        $userId = $_SESSION['id'];
       
        $iconResult = mysqli_query($conn, "SELECT id, emp_img_url FROM employee_tb WHERE id = '$userId'");
        $iconRow = mysqli_fetch_assoc($iconResult);

        if ($iconRow) {
            $image_url = $iconRow['emp_img_url'];
        } else {
            // Handle the case when the user ID is not found in the database
            $image_url = '../img/user.jpg'; // Set a default image or handle the situation accordingly
        }
        

    //    echo $image_url;
    }

    // echo $userId;
    // echo $_SESSION['empid'];

    
}

// NiRetrieve ko ang decode JSON data galing empListForm.php para mapalitan din ang label ng allowance
$data = json_decode(file_get_contents('php://input'), true);

// Update session variables with the new labels (if they are set)
if (isset($data['newTranspoLabel'])) {
    $_SESSION['newTranspoLabel'] = $data['newTranspoLabel'];
}
if (isset($data['newMealLabel'])) {
    $_SESSION['newMealLabel'] = $data['newMealLabel'];
}
if (isset($data['newInternetLabel'])) {
    $_SESSION['newInternetLabel'] = $data['newInternetLabel'];
}

// Define default labels or use session data
$newTranspoLabel = isset($_SESSION['newTranspoLabel']) ? $_SESSION['newTranspoLabel'] : '';
$newMealLabel = isset($_SESSION['newMealLabel']) ? $_SESSION['newMealLabel'] : '';
$newInternetLabel = isset($_SESSION['newInternetLabel']) ? $_SESSION['newInternetLabel'] : '';
//End ng ajax para label
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    
     

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

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="js/multi-select-dd.js"></script>

<link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css"> 
    <title>HRIS | Employee List Form</title>
</head> 
<body>
    <header>
        <?php include("header.php");?>
        
    </header>

    <?php
    
    //    var_dump($userId);

    //    $resultIcon = mysqli_query($conn, $sql);
    //    $row = mysqli_fetch_assoc($resultIcon);

    //    if($row){

    //    }
    ?>
    <style>
      .deduction-move{

}
.multiselect-dropdown{
    width: 420px !important;
    height: 50px !important;
    font-size: 18px  !important;
}
.multiselect-dropdown-list{
     display: flex !important;
     flex-direction: column !important;
}
.multiselect-dropdown-list input{
 height: 20px !important;
 width: 20px !important;
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
 height: 50px !important;

}

.emp-head img{
    height: 250px;
    width: 250px;
}


    </style>

        <?php 
        
        // $stmt = "SELECT * FROM employee_tb WHERE empid=$empid";
        // $result = mysqli_query($conn, $stmt);
        // $row = mysqli_fetch_assoc($result);

        ?>
            <form action="" method="POST" enctype="multipart/form-data" id="form">
                <div class="empListForm-container" style="background-color: #fff">            
                    <div class="employeeList-modal" id="Modal">
                        <div class="employeeList-info-container">
                            <div class="emp-title" style="display:flex; flex-direction:space-row; align-items: center; justify-content:space-between; width: 1440px;">
                            <h1>Employment Information</h1>
                                <span class="fa-solid fa-pen-to-square" style="color: #000000; cursor: pointer; margin-right: 20px; font-size: 20px;"></span>  
                            </div>
                            <div class="emp-list-main">
                                <div class="emp-info-first-container" style="height: 400px">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    
                                    <div class="emp-fname">
                                        <label for="fname">First Name</label><br>
                                        <input type="text"  name="fname" id="" placeholder="First Name" value="<?php echo $row['fname']; ?>" style="border: black 1px solid;"> 
                                    </div>
                                    
                                    <div class="emp-lname">
                                        <label for="lname">Last Name</label><br>
                                        <input type="text" name="lname" id="" placeholder="Middle Name" value="<?php echo $row['lname']; ?>" style="border: black 1px solid;">
                                    </div>
                                    <div class="emp-fname mt-3">
                                        <label for="fname">Middle Name</label><br>
                                        <input type="text" class="" name="mname" id="" placeholder="First Name" value="<?php echo $row['mname']; ?>" style="border: black 1px solid; margin-top: 0.2em"> 
                                    </div>
                                    <div class="emp-dob">
                                        <label for="empdob">Date of Birth</label><br>
                                        <input type="date" name="empdob" id="empdob" placeholder="Select Date of Birth" value="<?php echo $row['empdob'] ?>" style="border: black 1px solid;">
                                    </div>
                                    <div class="emp-contact">
                                        <label for="contact">Contact Number</label><br>
                                        <input type="text"  name="contact" value="<?php echo $row['contact']?>" id="numberInput" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" style="border: black 1px solid;">
                                    </div>
                                    <div class="emp-cstatus">
                                        <label for="cstatus">Marital Status</label><br>
                                            <select name="cstatus" id="" placeholdber="Select Status" value="<?php echo $row['cstatus'];?>"  style="border: black 1px solid;">
                                            <option value="<?php echo $row['cstatus']?>" selected="selected" class="selectTag" style="color: gray;"><?php echo $row['cstatus']?></option>
                                                <option value="Single" >Single</option>
                                                <option value="Married">Married</option>
                                            </select>
                                    </div>
                                    <div class="emp-email">
                                        <label for="email">Email</label><br>
                                        <input type="email" name="email" id="" placeholder="Email Address" value="<?php echo $row['email'] ?>" pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9.-]+\.[a-z]{2,}" title="Must be a valid email." style="border: black 1px solid;">
                                    </div>

                                    <div class="emp-email">
                                        <label for="email">Company Email</label><br>
                                        <input type="text" name="comp_email" id="" placeholder="Email Address" value="<?php echo $row['company_email'] ?>" pattern="[A-Za-z0-9._+-]+@[A-Za-z0-9.-]+\.[a-z]{2,}" title="Must be a valid email." style="border: black 1px solid;">
                                    </div>

                                    <div class="emp-datehired">
                                        <label for="empdate_hired">Date Joined</label><br>
                                            <input type="date" name="empdate_hired" id="" placeholder="Date Hired" value="<?php echo $row['empdate_hired'] ?>" style="border: black 1px solid;">
                                    </div>
                                    
                                    <div class="emp-gender">
                                        <label for="gender">Gender</label><br>
                                        <select name="gender" id="" placeholdber="Select Gender" value="<?php echo $row['gender'];?>" style="border: black 1px solid;">
                                        <option value="<?php echo $row['gender']?>" selected="selected" class="selectTag" style="color: gray;"><?php echo $row['gender']?></option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="emp-list-info-second-container"> 
                                    <div class="emp-head mt-1">
                                        <?php
                                        if(!empty($row['emp_img_url'])) {
                                            $image_url = $row['emp_img_url'];
                                        } else {
                                           

                                            $Supervisor_Profile = "SELECT * FROM employee_tb WHERE `empid` = '$empid'";
                                            $profileRun = mysqli_query($conn, $Supervisor_Profile);

                                            $SuperProfile = mysqli_fetch_assoc($profileRun);
                                            $visor_Profile = $SuperProfile['user_profile'];

                                            $image_data = "";
                                                            
                                            if (!empty($visor_Profile)) {
                                                $image_data = base64_encode($visor_Profile); // Convert blob to base64
                                            } else {
                                                // Set default image path when user_profile is empty
                                                $image_data = base64_encode(file_get_contents("img/user.jpg"));
                                            }
                                            
                                            $image_type = 'image/jpeg'; // Default image type
                                            
                                            // Determine the image type based on the blob data
                                            if (substr($image_data, 0, 4) === "\x89PNG") {
                                                $image_type = 'image/png';
                                            } elseif (substr($image_data, 0, 2) === "\xFF\xD8") {
                                                $image_type = 'image/jpeg';
                                            } elseif (substr($image_data, 0, 4) === "RIFF" && substr($image_data, 8, 4) === "WEBP") {
                                                $image_type = 'image/webp';
                                            }
                    
                                        }
                                        // Get file extension from image URL
                                        $file_ext = pathinfo($image_url, PATHINFO_EXTENSION);
                                        ?>
                                        <!-- src="uploads/<?php echo $image_url; ?>" -->
                                        <img <?php if(!empty($image_url)){ echo "src='uploads/".$image_url."' "; } else{ echo "src='data:".$image_type.";base64,".$image_data."'";} ?> alt="" srcset="" accept=".jpg, .jpeg, .png" title="<?php echo $image_url; ?>" >
                                        <!-- Set hidden input value to image URL with file extension -->
                                        <input type="hidden" name="emp_img_url" value="<?php echo $image_url; ?>">
                                    </div>
                                    <div class="emp-info" style="margin-top: 10px;">
                                        <h1><?php echo $row['fname']; ?> <?php echo $row['lname'];?></h1>
                                        
                                        <?php 
                                            $sqle = "SELECT * FROM positionn_tb
                                                    INNER JOIN employee_tb on positionn_tb.id = employee_tb.empposition
                                                    WHERE employee_tb.empid = $empid";
                                            $resulte = mysqli_query($conn, $sqle);
                                            $rowe = mysqli_fetch_assoc($resulte);

                                            $position = $rowe['position'];
 

                                        ?> 
                                        <h2 style="font-size: 1.3em; color: gray; font-style:italic"><?php echo $position ?></h2>
                                        <p class="" style="margin-top: -3px; color: black; font-weight: 500">Status: <span style="<?php if($row['status'] == 'Active'){echo "color: green"; }else{echo "color:red"; } ?>"><?php echo $row['status'] ?></span></p>
                                        
                                        <div class="emp-stats" style="">
                                        
                                        <!-- <h4 style="margin-top: 9px; margin-left: 50px;">Status: </h4>
                                        <input type="text" name="status" id="status" value="<?php if(isset($row['status']) && !empty($row['status'])) { echo $row['status']; } else { echo 'Inactive'; }?>" style="width: 65px; border: none; margin-top: 1px; margin-left: 4px; font-weight: 500; outline: none; color: <?php echo ($row['status'] === 'Active') ? 'green' : 'red'; ?>;" readonly>
                                        <span onclick="changeWord()" class="fa-solid fa-rotate" style="cursor: pointer; margin-top: 9px;"></span> -->

                                        <!-- <script>
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
                                        </script> -->
                                        </div>
                                    </div>
                                    <div class="custom-file" style="width:300px; margin-top:10px;">
                                        <!-- <input type="file" class="custom-file-input" id="customFile" name="emp_img" > -->
                                        <!-- <label class="custom-file-label" for="customFile">Choose file</label> -->
                                    </div>

                                    <script>
                                        // Add the following code if you want the name of the file appear on select
                                        $(".custom-file-input").on("change", function() {
                                        var fileName = $(this).val().split("\\").pop();
                                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                                        });
                                    </script>
                                </div>

                            </div>
                        </div> 

                        <div class="employeeList-government-container">
                            <div class="emp-title" style="display:flex; flex-direction:space-row; align-items: center; justify-content:space-between; width: 1440px;">
                                <h1>Government Information</h1>
                                <button type="button"  data-bs-toggle="modal" data-bs-target="#governModal" id="modal-update" id="modal-update" class="fa-light fa-plus" style="color: #000000; cursor: pointer; margin-right: 20px; font-size: 20px; border:none; background-color:inherit; outline:none; font-size: 30px;"> </button>
                            </div> 
                            <div class="emp-govern-first-container">
                                <div class="gov-sss" style="display:flex">
                                    <div>
                                    <label for="empsss">SSS #</label><br>
                                        <input type="text" name="empsss" id="" placeholder="Input SSS#" value="<?php echo $row['empsss'] ?>" style="border: black 1px solid;" ><br> 
                                        
                                    </div>
                                    <div>
                                    <label for="sssamount">Amount</label><br>
                                    <input type="text" name="sss_amount" id="numberInput" placeholder="Input Deduction" value="<?php if(isset($row['sss_amount'])&& !empty($row['sss_amount'])) { echo $row['sss_amount']; }?>" style="color:black; font-size: 15px; border: black 1px solid" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                                    
                                    </div>
                                </div>

                                <div class="gov-tin" style="display:flex">
                                    <div>
                                        <label for="emptin">TIN #</label><br>
                                            <input type="text" name="emptin" id="" placeholder="Input TIN" value="<?php echo $row['emptin'] ?>" style="border: black 1px solid;">
                                    </div>
                                    <div>
                                    <label for="tinamount">Amount</label><br>
                                    <input type="text" name="tin_amount" id="numberInput" placeholder="Input Deduction" value="<?php if(isset($row['tin_amount'])&& !empty($row['tin_amount'])) { echo $row['tin_amount']; }?>" style="color:black; font-size: 15px; border: black 1px solid" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                                    
                                    </div>
                                </div>

                                <div class="gov-pagibig" style="display:flex">
                                    <div>
                                        <label for="emppagibig">Pagibig #</label><br>
                                            <input type="text" name="emppagibig" id="" placeholder="Input Pagibig #" value="<?php echo $row['emppagibig'] ?>" style="border: black 1px solid;" >
                                    </div>
                                    <div>
                                    <label for="pagibigamount">Amount</label><br>
                                    <input type="text" name="pagibig_amount" id="numberInput" placeholder="Input Deduction" value="<?php if(isset($row['pagibig_amount'])&& !empty($row['pagibig_amount'])) { echo $row['pagibig_amount']; }?>" style="color:black; font-size: 15px; border: black 1px solid" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                                    
                                    </div>
                                </div>

                                <div class="gov-philhealth" style="display:flex">
                                    <div>
                                        <label for="empphilhealth">Philhealth #</label><br>
                                            <input type="text" name="empphilhealth" id="" placeholder="Input Philhealth #" value="<?php echo $row['empphilhealth'] ?>" style="border: black 1px solid;">
                                    </div>
                                    <div>
                                    <label for="philhealth_amount">Amount</label><br>
                                    <input type="text" name="philhealth_amount" id="numberInput" placeholder="Input Deduction" value="<?php if(isset($row['philhealth_amount'])&& !empty($row['philhealth_amount'])) { echo $row['philhealth_amount']; }?>" style="color:black; font-size: 15px; border: black 1px solid" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                                   
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="emp-allowance-container">
                            <div class="emp-title" style="display:flex; flex-direction:space-row; align-items: center; justify-content:space-between; width: 1440px;">
                                <h1 id="countfield">Employee Monthly Allowance</h1>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#allowanceModal" id="modal-update" class="fa-light fa-plus" style="color: #000000; cursor: pointer; margin-right: 20px; font-size: 20px; border:none; background-color:inherit; outline:none; font-size: 30px;"> </button>
                            </div>
                            <div class="emp-allowance-first-container">
                                <div class="allowance-transpo">
                                    <label for="emptranspo"><?php echo $newTranspoLabel; ?></label><br>
                                        <input type="text" name="emptranspo" placeholder="0.00" value="<?php echo $row['emptranspo']; ?>" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" style="border: black 1px solid;">
                                </div>

                                <div class="allowance-meal">
                                    <label for="empmeal"><?php echo $newMealLabel; ?></label><br>
                                        <input type="text" name="empmeal" placeholder="0.00" value="<?php echo $row['empmeal'] ?>" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" style="border: black 1px solid;"> 
                                </div>

                                <div class="allowance-internet">
                                    <label for="empinternet"><?php echo $newInternetLabel; ?></label><br>
                                        <input type="text" name="empinternet" placeholder="0.00" value="<?php echo $row['empinternet'] ?>" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" style="border: black 1px solid;">  
                                </div>
                            </div>

                            <?php
                                $newAllowance = mysqli_query($conn, "SELECT * FROM allowancededuct_tb WHERE `id_emp` = '$empid' LIMIT 3");
                                if ($newAllowance && mysqli_num_rows($newAllowance) > 0) {
                                    echo '<div class="emp-allowance-first-container">';
                                    while ($allowrow = mysqli_fetch_assoc($newAllowance)) {

                                        echo '<div class="allowance-internet">';
                                        echo '<label for="emptranspo">' . $allowrow['other_allowance'] . '</label><br>';
                                        echo '<input type="text" name="newallowance" placeholder="0.00" value="' . $allowrow['allowance_amount'] . '" oninput="this.value = this.value.replace(/[^0-9]/g, \'\'); if(this.value.length > 11) this.value = this.value.slice(0, 11);" style="border: black 1px solid;">';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                } else {
                                    // Walang resulta sa query, kaya maaaring i-remove o hindi ipakita ang div na emp-allowance-first-container
                                    // Halimbawa:
                                    // echo '<div class="emp-allowance-first-container" style="display: none;"></div>';
                                }
                                ?>

                        </div><!--emp allowance container-->


                        <?php
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

                        <div class="emp-empInfo-container">
                            <div class="emp-title" style="display:flex; flex-direction:space-row; align-items: center; justify-content:space-between; width: 1440px;">
                            <h1>Employment Credentials</h1>
                            </div>
                            <div class="emp-empInfo-first-container">
                                <div class="empInfo-empid" style="">
                                    <label for="empid">Employee ID</label><br>
                                    <div class="" style="display:flex; flex-direction: row">
                                    <?php
                                        include 'config.php';

                                        $sql = "SELECT * FROM company_code_tb";
                                        $results = mysqli_query($conn, $sql);
                                        $options = "";
                                        while ($rows = mysqli_fetch_assoc($results)) {
                                            $selected = ($rows['id'] == $row['company_code']) ? 'selected' : '';
                                            $options .= "<option value='".$rows['id']."' ".$selected." >" .$rows['company_code'].  "</option>";
                                        }
                                        ?>
                                        <select name="company_code" id="" value="<?php echo $cmpny_row['company_code_name']?>" style="width: 15%">
                                            <?php echo $options ?>
                                        </select>
                                        <input type="text" name="empid" id="" placeholder="Employee ID" value="<?php echo $row['empid'] ?>" readonly class="form-control" style="height:50px; width: 75%">
                                    </div>
                                </div>
                                <div class="empInfo-position">
                                <?php
                                        include 'config.php';

                                        $sql = "SELECT * FROM positionn_tb";
                                        $results = mysqli_query($conn, $sql);
                                        $options = "";
                                        while ($rows = mysqli_fetch_assoc($results)) {
                                            $selected = ($rows['id'] == $row['empposition']) ? 'selected' : '';
                                            $options .= "<option value='".$rows['id']."' ".$selected.">" .$rows['position'].  "</option>";
                                        }
                                        ?>
                                        
                                        <label for="empposition">Position</label><br>
                                        <select name="empposition" id="" placeholder="" value="<?php echo $row['empposition'];?>">
                                            
                                            <?php echo $options; ?>
                                        </select>
                                </div>
                                <div class="empInfo-role">
                                    <label for="role">Role</label><br>
                                    <select name="role" value="<?php echo $row['role'] ?>">
                                            <option selected="selected" class="selectTag" style="color: gray;" value="<?php echo $row['role'] ?>"><?php echo $row['role'];?></option>
                                            <option value="Employee">Employee</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Superadmin">Superadmin</option>  
                                        </select> 
                                </div>
                                <div class="empInfo-branch">
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
                                        <select name="empbranch" id="" placeholder="Select Branch" value="<?php echo $row['branch_name'];?>">
                                            <?php echo $options; ?>
                                        </select>
                                </div>

                                <div class="empInfo-department">
                                    <?php
                                        include 'config.php';

                                        $sql = "SELECT * FROM dept_tb";
                                        $results = mysqli_query($conn, $sql);
                                        $options = "";
                                        while ($rows = mysqli_fetch_assoc($results)) {
                                            $selected = ($rows['col_ID'] == $row['department_name']) ? 'selected' : '';
                                            $options .= "<option value='".$rows['col_ID']."' ".$selected.">" .$rows['col_deptname'].  "</option>";
                                        }
                                        ?>
                                        
                                        <label for="department_name">Department</label><br>
                                        <select name="department_name" id="" placeholder="" value="<?php echo $row['col_deptname'];?>">
                                            
                                            <?php echo $options; ?>
                                        </select>
                                </div>
                                <div class="empInfo-classification">
                                     
                                     <?php
                                        include 'config.php';

                                        $sql = "SELECT * FROM classification_tb";
                                        $results = mysqli_query($conn, $sql);
                                        $options = "";
                                        while ($rows = mysqli_fetch_assoc($results)) {
                                            $selected = ($rows['id'] == $row['classification']) ? 'selected' : '';
                                            $options .= "<option value='".$rows['id']."' ".$selected.">" .$rows['classification'].  "</option>";
                                        }
                                        ?>
                                        
                                        <label for="classification">Employment Classification</label><br>
                                        <select name="classification" id="" placeholder="" value="<?php echo $row['classification'];?>">
                                            
                                            <?php echo $options; ?>
                                        </select>
                                </div>
                                <div class="empInfo-salary">
                                    <label for="empbsalary">Basic Salary</label><br>
                                        
                                        <input type="text"  name="empbsalary"  value="<?php if(isset($row['empbsalary'])){ echo $row['empbsalary'];} else{ echo 'No Data.'; } ?>" id="numberInput" style="border: black 1px solid;">
                                </div>
                                <div class="empInfo-otrate">
                                    <label for="otrate">OT Rate</label><br>
                                        
                                        <input type="text"  name="otrate" placeholder="OT Rate" value="<?php echo $row['otrate']?>" id="numberInput" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 8);" style="border: black 1px solid;">
                                </div>
                                <div class="empInfo-approver">
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
                                        <select class="approver-dd" name="approver[]" id="approver_ID" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2" style="display:flex; width: 380px;">
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
                        </div>
                        <div class="emp-worksched-container">
                            <div class="emp-title" style="display:flex; flex-direction:space-row; align-items: center; justify-content:space-between; width: 1440px;">
                                <h1>Employment Work Schedule</h1>
                            </div>
                            <div class="emp-worksched-first-container">

                                <div class="worksched-restday">
                                    <label for="restday">Rest Day</label><br>
                                    <input type="text"  id="" placeholder="Rest Day" value="<?php echo !empty($restdayRow['restday']) ? $restdayRow['restday'] : 'No rest day'; ?>" style="border: black 1px solid;" readonly>

                                </div>
                                <div class="worksched-scedule">
                                    <label for="schedule_name">Schedule Setup</label><br>
                                        <?php
                                        include 'config.php';
                                   
                                            $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid ='". $_GET['empid']. "'");
                                            if(mysqli_num_rows($result_emp_sched) > 0) {
                                            $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                                            $schedID = $row_emp_sched['schedule_name'];

                                           
                                        }
                                        
                                            ?>
                                        <input type="text" name="schedule_name" value="<?php error_reporting(E_ERROR | E_PARSE);
                                                if($schedID == NULL){
                                                    echo 'No Schedule';
                                                }else{
                                                    echo $schedID;
                                                }
                                            ?>" id="" readonly style="border: black 1px solid;">                                       
                                </div>
                            </div>
                        </div>
                        <div class="emp-payroll-container">
                            <div class="emp-title" style="display:flex; flex-direction:space-row; align-items: center; justify-content:space-between; width: 1440px;">
                                <h1>Employment Payroll Details</h1>
                            </div>
                            <div class="emp-payroll-first-container">
                                <div class="payroll-bank-name">
                                    <label for="bank_name">Bank Name</label><br>
                                    <input type="text" name="bank_name" id="" placeholder="N/A" value="<?php echo $row['bank_name']?>" style="border: black 1px solid;">
                                </div>
                                <div class="payroll-bank_no">
                                    <label for="bank_number">Bank Account Number</label><br>
                                    <input type="text" name="bank_number" id="" placeholder="N/A"  value="<?php echo $row['bank_number']?>" style="border: black 1px solid;">
                                </div>
                            </div>
                        </div>
                        <div class="export">

                        </div>
                            <div class="empList-save-btn">
                                <div>
                                    <span class="closeModal" id="closeModal"><a href="EmployeeList.php">Cancel<a></span>
                                    <span class="modalSave"> <input class="submit" type="submit" name="update" value="Update"></span>
                                </div>
                         </div>
                    </div>
                </div>
            </form>


                    <?php 
                        $server = "localhost";
                        $user = "root";
                        $pass ="";
                        $database = "hris_db";
     
                        $conn = mysqli_connect($server, $user, $pass, $database);
                        $sql = "SELECT empid FROM employee_tb";

                        $results = mysqli_query($conn, "SELECT * FROM employee_tb WHERE empid ='". $_GET['empid']. "'");
                        $rows = mysqli_fetch_assoc($results);
     
                                
                    ?>

                <form action="Data Controller/Employee List/otherGovernController.php" method="POST">
                    <div class="modal fade" id="governModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog" style="position: absolute; top: 50px; left: 35%; " >
                        <div class="modal-content" id="" style="width: 800px;">
                        <script>
                            $(document).ready(function(){

                                var html = '<tr><td><input required type="text" name="other_govern[]" id=""  class="emp-desc form-control" placeholder="Description"style="margin-top: 10px; border: black 1px solid;"></td><td><input required type="text" name="govern_amount[]" id="inputBox" class="emp-amount form-control" placeholder="Amount" oninput="validateInput(this)" style="margin-top: 10px; border: black 1px solid;"></td><td><input type="button" value="Remove" name="id_emp" id="empRemove" class="btn" style="margin-top: 10px;"></td><td> <input type="hidden" name="id_emp[]" value="<?php echo $rows['empid']?>" id="" style="width:30px"></td></tr>';

                                var max = 5;
                                var x = 1;
                                $("#empAdd").click(function(){
                                    if(x <= max ){
                                        $("#table-field").append(html);
                                        x++;
                                    }
                                });

                                $("#table-field").on('click','#empRemove',function(){
                                    $(this).closest('tr').remove();
                                    x--;
                                });

                            });

                            function validateInput(input) {
                                input.value = input.value.replace(/\D/g, '');
                                }   
                        </script>
                        <input type="hidden" name="id" value="<?php echo $rows['id']; ?>">
                            <div class="modal-header">
                                <h1 class="modal-title" style="font-size: 25px;">Add new deduction</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                
                                <table class="" id="table-field" style=" width: 600px; margin-left: 100px;" >
                                    <tr>
                                        <th>Description</th>
                                        <th style="margin-right: 100px;">Amount</th>
                                        <th >Actions</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td><input required type="text" name="other_govern[]" id=""  class="emp-desc form-control" placeholder="Description" style="border: black 1px solid;" ></td>
                                        <td><input required type="text" name="govern_amount[]" id=""  class="emp-amount form-control" placeholder="Amount" style="border: black 1px solid;" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);"></td>
                                        <td><input type="button"  value="Add" name="id_emp[]" id="empAdd" class="btn btn-success" style="width: 73px; margin-left: 20px;" ></td>
                                        <td>
                                        <input type="hidden" name="id_emp[]" value="<?php echo $rows['empid']?>" id="" style="width:30px">

                                        </td>
                                    </tr>
                                </table>
                              

                                <div class="other-govern-title" style="margin-top: 30px">
                                    <h1 style="font-size: 23px; margin-left: 20px; margin-bottom:-20px;">New Deductions</h1>
                                
                                
                                <table style=" width: 300px; margin-left: 100px; margin-top: 30px;">
                                    <tr>
                                        <th>Description</th>
                                        <th class="deduction-move">Amount</th>
                                        <th>Actions</th>         
                                    </tr>
                                    <?php
                                    $conn = mysqli_connect("localhost", "root", "", "hris_db");
                                    $sql = "SELECT govern.id, govern.other_govern, govern.govern_amount, emp.empid FROM governdeduct_tb AS govern
                                            INNER JOIN employee_tb AS emp ON emp.empid = govern.id_emp
                                            WHERE govern.id_emp = '".$_GET['empid']."'";
                                    $result = mysqli_query($conn, $sql);
                                    $totalAmount = 0;
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $totalAmount += $row['govern_amount'];
                                            echo "<tr>";
                                            echo "<td><input type='text' readonly class='emp-desc form-control' style='margin-top:10px;width:250px;' name='other_govern[]' value='" . $row['other_govern'] . "'></td>";
                                            echo "<td><input type='text'  style='margin-top:10px; width:250px;'  class='emp-amount form-control' readonly name='govern_amount[]' value='" . $row['govern_amount'] . "'></td>";
                                            echo "<td><button type='button' name='delete_data' class='btn btn-danger'><a href='actions/Employee List/govern_delete.php?id=".$row['id']."&empid=".$row['empid']."' style='color:white;'>Delete</a></button></td>";
                                            echo "<input type='hidden'readonly name='empid[]' value='" . $row['empid'] . "'>";
                                            echo "</tr>";
                                        }
                                    }
                                    echo "<tr>";
                                    echo "<td>Total Amount:</td>";
                                    echo "<td><input type='text' readonly style='margin-top:10px;'  class='emp-amount form-control' name='total_amount' value='" . $totalAmount . "'></td>";
                                    echo "</tr>";
                                    mysqli_close($conn);
                                    ?>
                 <input type='hidden' name="empid" value="<?php echo $rows['empid'];?>">
                                
                                </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border: none; font-size: 20px; background-color: inherit;">Close</button>
                                <input type="submit" value="Submit" name="submit" id="submit" style=" font-size: 23px; margin-top: -1px; margin-right: 10px; color: #fff; height: 50px; padding: 10px; background-color: black; border: none; border-radius: 10px;"  >
                            </div>        
                        </div>
                        </div>
                    </div>
                    </form> 
                    

                    <?php 
                        $server = "localhost";
                        $user = "root";
                        $pass ="";
                        $database = "hris_db";
     
                        $conn = mysqli_connect($server, $user, $pass, $database);
                        $sql = "SELECT empid FROM employee_tb";

                        $resultss = mysqli_query($conn, "SELECT * FROM employee_tb WHERE empid ='". $_GET['empid']. "'");
                        $rowss = mysqli_fetch_assoc($resultss);
     
                                
                    ?>

                   
                <form action="Data Controller/Employee List/otherAllowanceController.php" method="POST">
                <?php 
                        $server = "localhost";
                        $user = "root";
                        $pass ="";
                        $database = "hris_db";
     
                        $conn = mysqli_connect($server, $user, $pass, $database);
                        $sql = "SELECT empid FROM employee_tb";

                        $results = mysqli_query($conn, "SELECT * FROM employee_tb WHERE empid ='". $_GET['empid']. "'");
                        $rows = mysqli_fetch_assoc($results);                        
                    ?>
                    <div class="modal fade" id="allowanceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="allowanceLabel" aria-hidden="true">
                        <div class="modal-dialog"  style="position: absolute; top: 50px; left: 35%; ">
                        <div class="modal-content" style="width: 800px;">
                            <script>
                                $(document).ready(function(){
                                    var html = '<tr><td><input required type="text" name="other_allowance[]" id=""  class="allowance-desc form-control" placeholder="Description"style="margin-top: 10px; border: black 1px solid; width: 100%;"></td><td><input required type="text" name="govern_amount[]" id="inputBox" class="emp-amount form-control" placeholder="Amount" oninput="validateInput(this)" style="margin-top: 10px; border: black 1px solid;"></td><td><input type="button" value="Remove" name="id_emp" id="allowanceRemove" class="btn" style="margin-top: 10px;"></td><td> <input type="hidden" name="id_emp[]" value="<?php echo $rows['empid']?>" id="" style="width:30px"></td></tr>';
                                var max = 5;
                                var x = 1;
                                $("#allowanceAdd").click(function(){
                                    if(x <= max ){
                                        $("#table-fields").append(html);
                                        x++;
                                    }
                                });

                                $("#table-fields").on('click','#allowanceRemove',function(){
                                    $(this).closest('tr').remove();
                                    x--;
                                });

                            });
                            function validateInput(input) {
                                input.value = input.value.replace(/\D/g, '');
                                }  
                            </script>
                            <input type="hidden" name="id" value="<?php echo $rows['id']; ?>">
                            <div class="modal-header">
                                <h1 class="modal-title" style="font-size: 25px;">Add new allowance</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">  
                                <table class="" id="table-fields" style=" width: 300px; margin-left: 100px;" >
                                    <tr>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="other_allowance[]" id="modal-description"  class="allowance-desc form-control" placeholder="Description" style="width: 250px; border: black 1px solid;" required></td>
                                        <td><input type="text" name="allowance_amount[]" id="modal-amount"  class="allowance-amount form-control" placeholder="Amount" style="width: 250px; border: black 1px solid;" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);" required></td>
                                        <td><input type="button" value="Add" name="id_emp[]" id="allowanceAdd" class="btn btn-success" style="width: 73px;" ></td>
                                        <td>
                                        <input type="hidden" name="id_emp[]" value="<?php echo $rows['empid']?>" id="" style="width:30px">
                                        </td>
                                    </tr>
                                </table>
                           

                                <div class="other-allowance-title" style="margin-top: 30px">
                                    <h1 style="font-size: 23px; margin-left: 20px; margin-bottom:-20px;">New Allowance</h1>
                                
                                
                                <table style="width: 300px; margin-left: 100px; margin-top: 30px;">
                                    <tr>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Actions</th>         
                                    </tr>
                                    <?php
                                        $conn = mysqli_connect("localhost", "root", "", "hris_db");
                                        $sql = "SELECT allow.id, allow.other_allowance, allow.allowance_amount, emp.empid 
                                                FROM allowancededuct_tb AS allow
                                                INNER JOIN employee_tb AS emp ON emp.empid = allow.id_emp
                                                WHERE allow.id_emp = '".$_GET['empid']."'";
                                        $resultd = mysqli_query($conn, $sql);
                                        $totalAmountd = 0;
                                        if (mysqli_num_rows($resultd) > 0) {
                                            while ($rowd = mysqli_fetch_assoc($resultd)) {
                                                $totalAmountd += $rowd['allowance_amount'];
                                                echo "<tr>";
                                                echo "<td><input type='text' readonly class='form-control allowance-desc' style='margin-top:10px; width: 250px;' name='other_allowance[]' value='" . $rowd['other_allowance'] . "'></td>";
                                                echo "<td><input type='text' style='margin-top:10px; width:250px;' class='form-control allowance-amount' readonly name='allowance_amount[]' value='" . $rowd['allowance_amount'] . "' ></td>";
                                                echo "<td><button type='button' name='delete_data' class='btn btn-danger'><a href='actions/Employee List/allowance_delete.php?id=".$rowd['id']."&empid=".$rowd['empid']."' style='color:white;'>Delete</a></button></td>";
                                                echo "<input type='hidden' readonly name='empid[]' value='" . $rowd['empid'] . "'>";
                                                echo "</tr>";
                                            }
                                        }
                                        echo "<tr>";
                                        echo "<td>Total Amount:</td>";
                                        echo "<td><input type='text' disabled style='margin-top:10px;' class='form-control allowance-amount' name='total_amount' value='" . $totalAmountd . "'></td>";
                                        echo "</tr>";
                                        mysqli_close($conn);
                                        ?>

                                  <input type='hidden' name="empid" value="<?php echo $rowss['empid'];?>">
                                
                                </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border: none; font-size: 20px; background-color: inherit;">Close</button>
                                <input type="submit" value="Submit" name="submit" id="submit" style=" font-size: 23px; margin-top: -1px; margin-right: 10px; color: #fff; height: 50px; padding: 10px; background-color: black; border: none; border-radius: 10px;"  >
                            </div>  
                            </form>
                        </div>
                        </div>
                    </div>
                
                
           
<!----------Script para macount ang text input field sa allowance na magdidisplay sa gilid ng h1----------->
<script>
document.addEventListener("DOMContentLoaded", function () {
    var allowanceContainer = document.querySelector(".emp-allowance-container");
var inputFields = allowanceContainer.querySelectorAll(".emp-allowance-first-container input[type='text']");
var inputFieldCount = inputFields.length;

var h1Tag = document.getElementById('countfield');
h1Tag.textContent = "Employee Monthly Allowance (" + inputFieldCount + "/6)";

});
</script>
<!----------Script para macount ang text input field sa allowance na magdidisplay sa gilid ng h1----------->       

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