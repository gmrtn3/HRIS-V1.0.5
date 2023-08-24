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

    

        $server = "localhost";
        $user = "root";
        $pass ="";
        $database = "hris_db";
    
        $conn = mysqli_connect($server, $user, $pass, $database);
    
        if(count($_POST) > 0){
            mysqli_query($conn, "UPDATE schedule_tb
                                 SET schedule_name='".$_POST['schedule_name']."', monday='".$_POST['monday']."', mon_timein='".$_POST['mon_timein']."', mon_timeout='".$_POST['mon_timeout']."', mon_wfh='".$_POST['mon_wfh']."', tuesday='".$_POST['tuesday']."', tues_timein='".$_POST['tues_timein']."', tues_timeout='".$_POST['tues_timeout']."', tues_wfh='".$_POST['tues_wfh']."', wednesday='".$_POST['wednesday']."', wed_timein='".$_POST['wed_timein']."', wed_timeout='".$_POST['wed_timeout']."', wed_wfh='".$_POST['wed_wfh']."', thursday='".$_POST['thursday']."', thurs_timein='".$_POST['thurs_timein']."', thurs_timeout='".$_POST['thurs_timeout']."', thurs_wfh='".$_POST['thurs_wfh']."', friday='".$_POST['friday']."', fri_timein='".$_POST['fri_timein']."', fri_timeout='".$_POST['fri_timeout']."', fri_wfh='".$_POST['fri_wfh']."', saturday='".$_POST['saturday']."', sat_timein='".$_POST['sat_timein']."', sat_timeout='".$_POST['sat_timeout']."', sat_wfh='".$_POST['sat_wfh']."', sunday='".$_POST['sunday']."', sun_timein='".$_POST['sun_timein']."', sun_timeout='".$_POST['sun_timeout']."', sun_wfh='".$_POST['sun_wfh']."', flexible='".$_POST['flexible']."', enable_grace_period='".$_POST['enable_grace_period']."' , grace_period='".$_POST['grace_period']."', enable_sched_ot='".$_POST['enable_sched_ot']."'  , sched_ot='".$_POST['sched_ot']."', sched_holiday='".$_POST['sched_holiday']."', restday='".$_POST['restday']."'
                                 WHERE id='".$_GET['id']."'");
                     
                                    $schedules_names = $_GET['schedule'];   
                                     
                                    $query = " SELECT
                                                    *  
                                                FROM
                                                `empschedule_tb`
                                                WHERE `schedule_name` = '$schedules_names'";
                                    $result = $conn->query($query);

                                    // Check if any rows are fetched 
                                    if ($result->num_rows > 0) 
                                    {
                                        $empidArray = array(); // Array to store the dates
                                                                   
                                        // Loop through each row
                                        while($row = $result->fetch_assoc()) 
                                        {                                            
                                            $empid = $row["empid"];
                                           
                                            $empidArray[] = array('empid' => $empid); // Append the fetched date and late 
                                        } //end while

                                        foreach ($empidArray as $empidArrayss) 
                                            {
                                                $empids = $empidArrayss['empid'];
                                                $schedname = $_POST['schedule_name'];
                                                mysqli_query($conn, "UPDATE empschedule_tb SET schedule_name = '$schedname' WHERE empid = $empids ");
                                            }

                                        header('Location: scheduleForm.php');
                                    }
                                    else{
                                        header('Location: scheduleForm.php?msg="Something went wrong"');
                                    }
                                  //para sa pag select sa schedule base sa schedule na fetch (END)
           
        }
            $resulta = mysqli_query($conn, "SELECT * FROM schedule_tb WHERE id ='". $_GET['id']. "'");
            $schedrow = mysqli_fetch_assoc($resulta);
        
        

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
<script type="text/javascript" src="js/multi-select-dd.js"></script>


<link rel="stylesheet" href="css/try.css">
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">
    <title>HRIS | Employee List Form</title>
</head>
<body>
    <header>
        <?php include("header.php")?>
    </header>

    <style>
        body a{
            text-decoration: none;

        }

        .modal-content{
        width: 700px !important;
        height: 600px !important;
        position: absolute !important;
        top: 100px !important;
        right: -230px !important;
       
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
        width: 97.5% !important;
    }
    #multi_option{
	        max-width: 100%;
	        width: 100%;
        }
    </style>



    <button id="schedFormBtn" class="schedFormBtn"  type="button" data-bs-toggle="modal" data-bs-target="#schedModal" > Assign to Employee</button>
    
   
    
        <div class="modal fade" id="schedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="title" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="title">Change Schedule</h1>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label for="department">Select Department</label><br>
                        <?php
                            include 'config.php';

                            $sqls = "SELECT * FROM dept_tb";

                            $results = mysqli_query($conn, $sqls);

                            $option = "";
                            while ($rows = mysqli_fetch_assoc($results)) {
                                $option .= "<option value='" . $rows['col_ID'] . "'>" . $rows['col_deptname'] . "</option> ";
                            }
                        ?>
                        <select name="department" id="departmentDropdown" class="form-select">
                            <option value selected>Select Department</option>
                            <option value='All'>All</option>
                            <?php echo $option ?>
                        </select>
                    </div>
                     <!-- <p>Selected Department ID: <span id="selectedDepartment"><?php echo @$selectedDepartment ?></span></p> -->
                     <form action="Data Controller/Schedules/empSchedule.php" method="POST">
                    <div class="mb-3">
                        <label for="emp">Select Employee</label><br>
                          <div id="employeeDropdown">
                            <select class="approver-dd dd-hide" name="empid[]" id="multi_option" multiple placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;">
                            </select>
                        </div>
                    </div>

                        
                        <div class="mb-3">
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
                                        $options .= "<option value='".$row['schedule_name']."'>".$row['schedule_name']."</option>";
                                    }
                                    ?>

                                <label for="schedule_name">Schedule Type</label><br>
                                <select name="schedule_name" id="" class="form-select">

                                
                                    <?php echo $options; ?>
                                </select>
                        </div>
                        <div class=" d-flex flex-row  w-100">
                            <div class="w-50">
                                <label for="from">From</label><br>
                                <input type="date" name="sched_from" class="form-control " id="sched_from_id"   onchange="datevalidate()" min="<?php echo date('Y-m-d'); ?>" required>
                                <div id="sched_from_error" class="text-danger" style="font-size: small;"></div>


                            </div>
                            <div class="w-50">
                                <label for="from">To</label><br>
                                <input type="date" name="sched_to" id="sched_to_id" class="form-control" onchange="datevalidate()" required>   
                                <div id="sched_to_error" class="text-danger" style="font-size: small;"></div>
 
                            </div>
                        </div>
                        
                        <div class="sched-modal-btn mt-5">
                            <div>

                            </div>
                            <div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border:none; background-color: inherit; font-size: 23px;">Close</button>
                            <button class="btn btn-primary" name="submit" type="submit">Submit</button>
                            </div>
                            </div>
                        </div>
                            
                    </div>
                </div>
            </div>
        </div>
    </form>

    
    <form action="" method="POST">
       <div class="scheduleform-container" style="background-color: #fff">
            <div class="schedulelist-container">
                <div class="schedulelist-title">
                    <h1>Schedule List</h1>
                </div>
                <div class="schedulelist">

                     <?php
                            $server = "localhost";
                            $user = "root";
                            $pass ="";
                            $database = "hris_db";

                            $conn = mysqli_connect($server, $user, $pass, $database);
                            $sql = "SELECT * FROM schedule_tb";
                            $results = mysqli_query($conn, $sql);

                           
                            if($results->num_rows > 0){
                                while($rows = $results->fetch_assoc()){
                                    echo "<button style='border:none; background-color: inherit; display: flex; margin-left: 20px; font-size: 26px; margin-top: 10px; font-weight: 500;'><a href='editScheduleForm.php?id=$rows[id]'>".$rows['schedule_name']."</a></button>";
                                }
                            }
                        ?>

                   
                    <!-- <a href="scheduleForm.php"><h1>Office Based</h1></a>
                    <a href="http://"><h1>Flexible</h1></a>
                    <a href="http://"><h1>Work From Home</h1></a> -->
                </div>
            </div>
            <div class="scheduletable-container">
                    <div class="scheduletable-buttons">
                        <div class="scheduleBtn-crud">
                            <!-- <button style="color:white;" type="submit"><a href="" style="color:white;">Add</a></button> -->
                            <input type="submit" value="Update" name="update" class="btn btn-success" style="color: white;" >
                            <!-- <button class="btn btn-danger" style="background-color: black; border: black 1px solid;"><a href="actions/Schedules/delete.php?id=<?php //echo $schedrow['id'] ?>" style="color:white; text-decoration: none;">Delete</a></button>
                            <button class="btn btn-primary"><a href="scheduleForm.php" style="color:white; text-decoration: none;">Create New</a></button> -->
                            <a class="btn btn-primary" href="actions/Schedules/delete.php?id=<?php echo $schedrow['id'] ?>" style="color:white; text-decoration: none;">Delete</a>
                            <a href="scheduleForm.php" class="btn btn-dark" style="color:white; text-decoration: none;">Create New</a>
                        </div>
                    </div>

                    <label for="schedule_name">Schedule Name</label><br>
                    <input class="schedule-input" type="text" name="schedule_name" id="" value="<?php echo $schedrow['schedule_name'];?>" required>

                <div class="scheduletable-table">

            <div class="schedule-table-container">
                <table class="table-hover" id="scheduleForm-table">
                        <thead>
                            <th> </th>
                            <th>Time Entry </th>
                            <th>Time Out </th>
                            <th>Work From Home </th>
                        </thead>
                        <tbody>
                            <tr>
                           
                                <input type="hidden" name="id" value="<?php echo $schedrow['id']; ?>">
                                <input type="hidden" name="restday" id="restdayInput" value="<?php echo $schedrow['restday'] ?>" readonly>
                                <td>
                                <input type="checkbox" class="checkbox" name="monday" id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)" value="Monday" <?php if ($schedrow['monday']){ echo "checked"; } ?>> Monday</td>
                                <td><input name="mon_timein" type="time" class="time-input" id="time1"  value="<?php if(isset($schedrow['mon_timein'])&& !empty($schedrow['mon_timein'])) { echo $schedrow['mon_timein']; } else {echo 'No data'; }?>"></td>
                                <td><input name="mon_timeout" type="time" class="time-input" id="time2"  value="<?php if(isset($schedrow['mon_timeout'])&& !empty($schedrow['mon_timeout'])) { echo $schedrow['mon_timeout']; } else { echo 'No data'; }?>"></td>
                                <td><input name ="mon_wfh" type="checkbox" class="checkbox-lg" value="WFH" <?php if ($schedrow['mon_wfh']){ echo "checked"; } ?>></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="tuesday"  id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)" value="Tuesday" <?php if ($schedrow['tuesday']){ echo "checked"; } ?>> Tuesday</td>
                                <td><input name="tues_timein" type="time" class="time-input" id="time3"  value="<?php if(isset($schedrow['tues_timein'])&& !empty($schedrow['tues_timein'])) { echo $schedrow['tues_timein']; } else {echo 'No data'; }?>"></td>
                                <td><input name="tues_timeout" type="time" class="time-input" id="time4"  value="<?php if(isset($schedrow['tues_timeout'])&& !empty($schedrow['tues_timeout'])) { echo $schedrow['tues_timeout']; } else {echo 'No data'; }?>"></td>
                                <td><input name ="tues_wfh" type="checkbox" class="checkbox-lg" value="WFH" <?php if ($schedrow['tues_wfh']){ echo "checked"; } ?>></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="wednesday"  id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)" value="Wednesday" <?php if ($schedrow['wednesday']){ echo "checked"; } ?>> Wednesday</td>
                                <td><input name="wed_timein" type="time" class="time-input" id="time5"  value="<?php if(isset($schedrow['wed_timein'])&& !empty($schedrow['wed_timein'])) { echo $schedrow['wed_timein']; } else {echo 'No data'; }?>"></td>
                                <td><input name="wed_timeout" type="time" class="time-input" id="time6"  value="<?php if(isset($schedrow['wed_timeout'])&& !empty($schedrow['wed_timeout'])) { echo $schedrow['wed_timeout']; } else {echo 'No data'; }?>"></td>
                                <td><input name ="wed_wfh" type="checkbox" class="checkbox-lg" value="WFH" <?php if ($schedrow['wed_wfh']){ echo "checked"; } ?>></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="thursday" value="Thursday" onchange="updateRestday()" <?php if ($schedrow['thursday']){ echo "checked"; } ?> id="checkbox1" onclick="toggleInputs(this)">  Thursday </td>
                                <td><input name="thurs_timein" type="time" class="time-input" id="time7"  value="<?php if(isset($schedrow['thurs_timein'])&& !empty($schedrow['thurs_timein'])) { echo $schedrow['thurs_timein']; } else {echo 'No data'; }?>"></td>
                                <td><input name="thurs_timeout" type="time" class="time-input" id="time8" value="<?php if(isset($schedrow['thurs_timeout'])&& !empty($schedrow['thurs_timeout'])) { echo $schedrow['thurs_timeout']; } else {echo 'No data'; }?>"></td>
                                <td><input name ="thurs_wfh" type="checkbox" class="checkbox-lg" value="WFH" <?php if ($schedrow['thurs_wfh']){ echo "checked"; } ?>></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="friday" value="Friday" onchange="updateRestday()" <?php if ($schedrow['friday']){ echo "checked"; } ?> id="checkbox1" onclick="toggleInputs(this)"> Friday</td>

                                <td><input name="fri_timein" type="time" class="time-input" id="time9"  value="<?php if(isset($schedrow['fri_timein'])&& !empty($schedrow['fri_timein'])) { echo $schedrow['fri_timein']; } else {echo 'No data'; }?>"></td>
                                <td><input name="fri_timeout" type="time" class="time-input" id="time10"  value="<?php if(isset($schedrow['fri_timeout'])&& !empty($schedrow['fri_timeout'])) { echo $schedrow['fri_timeout']; } else {echo 'No data'; }?>"></td>
                                <td><input name ="fri_wfh" type="checkbox" class="checkbox-lg" value="WFH" <?php if ($schedrow['fri_wfh']){ echo "checked"; } ?>></td>
                            </tr>
                            <tr>
                            <td><input type="checkbox" class="checkbox" name="saturday" value="Saturday" onchange="updateRestday()" <?php if ($schedrow['saturday']){ echo "checked"; } ?> id="checkbox1" onclick="toggleInputs(this)"> Saturday</td>


                            <td><input name="sat_timein" type="time" class="time-input" id="time11"  value="<?php if(isset($schedrow['sat_timein'])&& !empty($schedrow['sat_timein'])) { echo $schedrow['sat_timein']; } else {echo 'No data'; }?>"></td>
                                <td><input name="sat_timeout" type="time" class="time-input" id="time12"  value="<?php if(isset($schedrow['sat_timeout'])&& !empty($schedrow['sat_timeout'])) { echo $schedrow['sat_timeout']; } else {echo 'No data'; }?>"></td>
                                <td><input name ="sat_wfh" type="checkbox" class="checkbox-lg" value="WFH" <?php if ($schedrow['sat_wfh']){ echo "checked"; } ?>></td>
                            </tr>
                            <tr>
                            <td><input type="checkbox" class="checkbox" name="sunday" value="Sunday" onchange="updateRestday()" <?php if ($schedrow['sunday']){ echo "checked"; } ?> id="checkbox1" onclick="toggleInputs(this)" > Sunday</td>

                            <td><input name="sun_timein" type="time" class="time-input" id="time13"  value="<?php  if(isset($schedrow['sun_timein'])&& !empty($schedrow['sun_timein'])) { echo $schedrow['sun_timein']; } else echo 'No data';?>"></td>
                                <td><input name="sun_timeout" type="time" class="time-input" id="time14"  value="<?php if(isset($schedrow['sun_timeout'])&& !empty($schedrow['sun_timeout'])) { echo $schedrow['sun_timeout']; } else {echo 'No data'; }?>"></td>
                                <td><input name ="sun_wfh" type="checkbox" class="checkbox-lg" value="WFH" <?php if ($schedrow['sun_wfh']){ echo "checked"; } ?>></td>
                            </tr>
                            <tr>
                                <td ><input type="checkbox" name="flexible" id="" class="checkbox-lg" value="Flexible" <?php if ($schedrow['flexible']){ echo "checked"; } ?>> Flexible</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>

                 <div class="schedule-extra">
                    <div>
                        <div class="schedule-gracePeriod">
                                <div>
                                    <input type="checkbox" id="enable-number-input" class="checkbox-lg" name="enable_grace_period" value="true" <?php if ($schedrow['enable_grace_period']){ echo "checked"; } ?> id="checkbox1" onclick="toggleInputs(this)" >
                                    <label for="grace_period">Grace Period</label>
                                </div>
                                <div>
                                    <input class="numbox" id="my-number-input" type="number" name="grace_period" placeholder="00:00" value="<?php if(isset($schedrow['grace_period'])&& !empty($schedrow['grace_period'])) { echo $schedrow['grace_period']; } else {echo 'No data'; }?>">
                                    <label for="graceperiod_minutes">Minutes</label>
                                </div> 
                                
                            </div>
                            <div class="schedule-ot">
                                <div>
                                    <input type="checkbox" id="enable-number-input2" class="checkbox-lg" name="enable_sched_ot" value="true"<?php if ($schedrow['enable_sched_ot']){ echo "checked"; } ?> id="checkbox1" onclick="toggleInputs(this)" >
                                    <label for="ob_ot">Enable OT</label>
                                </div>
                                <div>
                                    <input class="numbox"  id="my-number-input2" type="number" name="sched_ot" placeholder="00:00"  value="<?php if(isset($schedrow['sched_ot'])&& !empty($schedrow['sched_ot'])) { echo $schedrow['sched_ot']; } else {echo 'No data'; }?>">
                                    <label for="ob_minutes">Minutes</label> 
                                </div>
                            </div>
                            <div class="schedule-holiday">
                                <input type="checkbox" name="sched_holiday" id="" class="checkbox-lg" value="Holiday Work" <?php if ($schedrow['sched_holiday']){ echo "checked"; } ?>>
                                <label for="ob_holiday">Holiday Work</label>
                            </div>
                        </div> 

                    </div>                   
                </div>

            </div>
       </div>
       </form>   

       
       <script>
      $(document).ready(function() {
    $('#departmentDropdown').change(function() {
        var selectedValue = $(this).val();
        
        // Send selectedValue to a PHP script via AJAX
        $.ajax({
            type: 'POST',
            url: 'update_selected_department.php', // Create this PHP file to handle the AJAX request
            data: { department: selectedValue },
            success: function(response) {
                $('#selectedDepartment').text(response); // Update the value in the <p> tag

                // Fetch employee options based on the selected department
                $.ajax({
                    type: 'POST',
                    url: 'sched_employee_options.php', // Create this PHP file to generate employee options
                    data: { department: response },
                    success: function(employeeOptions) {
                        // Update the employee dropdown with new options
                        $('#employeeDropdown').html(employeeOptions);
                        console.log('Employee options updated successfully.');

                        // Collect selected employee IDs
                        var selectedEmployeeIDs = $('#multi_option').val();
                        console.log('Selected Employee IDs:', selectedEmployeeIDs);

                        // Now submit the form with the selected employee IDs
                      
                    }
                });
            }
        });
    });
});
    </script>


        <script type="text/javascript" src="js/virtual-select.min.js"></script>
<script type="text/javascript">
	VirtualSelect.init({ 
	  ele: '#multi_option' 
	});
</script>

       <script>
function datevalidate() {
    var startDateInput = document.getElementById('sched_from_id');
    var endDateInput = document.getElementById('sched_to_id');
    var startDate = new Date(startDateInput.value);
    var endDate = new Date(endDateInput.value);
    var today = new Date();
    today.setHours(0, 0, 0, 0); // Ibahin ang oras sa midnight para sa paghahambing

    var startError = document.getElementById('sched_from_error');
    var endError = document.getElementById('sched_to_error');
    var submitBtn = document.getElementById('submit-button');

    submitBtn.disabled = false; // I-reset ang estado ng submit button sa default

    if (startDate < today) {
        startError.innerHTML = "Start Date must be today or a future date.";
        submitBtn.disabled = true; // I-disable ang submit button kung may error
    } else {
        startError.innerHTML = "";
    }

    if (endDate < startDate) {
        endError.innerHTML = "End Date must be equal to or greater than Start Date.";
        submitBtn.disabled = true; // I-disable ang submit button kung may error
    } else {
        endError.innerHTML = "";
    }
}
</script>

    
       <script>
    function updateRestday() {
  var checkboxes = document.getElementsByClassName('checkbox');
  var restdayInput = document.getElementById('restdayInput');
  var restdays = [];

  for (var i = 0; i < checkboxes.length; i++) {
    if (!checkboxes[i].checked) {
      restdays.push(checkboxes[i].value);
    }
  }

  restdayInput.value = restdays.join(', ');
}
</script>


    
<script>
function toggleInputs(checkbox) {
  var row = checkbox.parentNode.parentNode;
  var inputs = row.getElementsByTagName("input");

  if (checkbox.checked) {
    for (var i = 0; i < inputs.length; i++) {
      inputs[i].disabled = false;
    }
  } else {
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i] !== checkbox) {
        inputs[i].disabled = true;
      }
    }
  }
}


const checkbox = document.getElementById('enable-number-input');
const checkbox2 = document.getElementById('enable-number-input2');
const numberInput = document.getElementById('my-number-input');
const numberInput2 = document.getElementById('my-number-input2');


checkbox.addEventListener('change', () => {
  numberInput.disabled = !checkbox.checked;
});

checkbox2.addEventListener('change', () => {
  numberInput2.disabled = !checkbox2.checked;
});


// sched form modal

let Modal = document.getElementById('schedFormModal');

//get open modal
let modalBtn = document.getElementById('schedFormBtn');

//get close button modal
let closeModal = document.getElementsByClassName('schedFormClose')[0];

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

//filter

function filter(item){
$.ajax({
type: "POST",
url: "Data Controller/empListFormController.php",
data: { value: item},
success:function(data){
  $("#results").html(data);
}
});
}


function getEmployee(val){
    $.ajax({
        type: "POST",
        url: "getEmployee.php",
        data: 'empid='+val,
        success:function(data){
             $("employee-dd").html(data);
             getEmployee();
         }
    });
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
      $('#scheduleform-container').addClass('move-content');
    } else {
      $('#scheduleform-container').removeClass('move-content');

      // Add class for transition
      $('#scheduleform-container').addClass('move-content-transition');
      // Wait for transition to complete before removing the class
      setTimeout(function() {
        $('#scheduleform-container').removeClass('move-content-transition');
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

</body>
</html>