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
<!-- <script type="text/javascript" src="js/multi-select-dd.js"></script> -->
<link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">

<link rel="stylesheet" href="css/try.css">
<link rel="stylesheet" href="css/styles.css">
    <title>HRIS | Employee List Form</title>
</head>
<body>
    <header>
        <?php include("header.php")?>
    </header>

    <style>
    body{
        overflow: hidden;
        background-color: #f4f4f4
    }
    .modal-content{
        width: 700px !important;
        height: 600px !important;
        position: absolute !important;
        top: 100px !important;
        right: -230px !important;
       
    }
    .error-message {
    display: <?php echo (isset($_GET['showError']) && $_GET['showError'] === 'true' && isset($_GET['errorMsg'])) ? 'flex' : 'none'; ?>;
    background-color: <?php echo (isset($_GET['showError']) && $_GET['showError'] === 'true' && isset($_GET['errorMsg'])) ? 'firebrick' : 'none'; ?>;
    color: white;
    width: 500px;
    height: 45px;
    margin-left: 50px;
    margin-top: 27px !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-radius: 10px;

  }
  
  .error-message .close-btn {
    background-color: firebrick;
    color: white;
    border: none;
    font-size: 20px;
    cursor: pointer;
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
        width: 98% !important;
    }


        #multi_option{
	        max-width: 100%;
	        width: 100%;
        }
    
</style>





    <button id="" class="schedFormBtn" type="button" data-bs-toggle="modal" data-bs-target="#schedModal"> Assign to Employee</button>

    
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
    </form>

    
       <div class="scheduleform-container" id="scheduleform-container" style="background-color: #fff;">
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
                                    echo "<button style='border:none; background-color: inherit; display: flex; margin-left: 20px; font-size: 26px; margin-top: 10px; font-weight: 500;'><a href='editScheduleForm.php?id=" . $rows['id'] . "&schedule=" . $rows['schedule_name'] . "'>" . $rows['schedule_name'] . "</a></button>";
                                }
                            }
                        ?>


                    <!-- <a href="scheduleForm.php"><h1>Office Based</h1></a>
                    <a href="http://"><h1>Flexible</h1></a>
                    <a href="http://"><h1>Work From Home</h1></a> -->
                </div>
            </div>
        <form action="Data Controller/Schedules/scheduleFormController.php" method="POST" onsubmit="return validateSchedule();">
       <div class="schedule-form-show">

           
            <div class="scheduletable-container">
                    <div class="scheduletable-buttons">
                        <div class="scheduleBtn-crud">
                             <input type="submit" value="Submit" name="submit" class="btn btn-success" id="submit-btn"  style="color: #fff">
                            <!-- <input type="submit" value="Update" name="" class="btn btn-success"  > -->
                            <!-- <button style="color:white; margin-left:20px"><a href="Button Controller/delete.pshp?id=$row[id]" style="color:white;">Delete</a></button> -->
                        </div>
                    </div>
                    
                    <div class="schedule-name-container" style="height: 80px; display: flex; flex-direction: row; margin-bottom: 20px;">
                        <div>
                            <label for="schedule_name">Schedule Name</label><br>
                            <input class="schedule-input" type="text" name="schedule_name" id="" required style="border: black 1px solid;" >
                           
                        </div>
                        <div>
                        <div class="error-message d-flex align-items-center justify-content-between" id="errorMsg" style="width: 500px; margin-left: 50px; margin-top: 40px;">
                        <span id="errorMessage" style="color: red; font-size: 16px;"></span>
                                <?php
                                if (isset($_GET['showError']) && $_GET['showError'] === 'true') {
                                    if (isset($_GET['errorMsg'])) {
                                    echo $_GET['errorMsg'];
                                    echo '<button class="close-btn" id="error-text" style="border: none; background-color: inherit; font-size: 20px;" onclick="removeErrorMessage()"><span class="fa-regular fa-circle-xmark"> </span</button>';
                                    }
                                }
                                ?>

                                </div>
                            </div>
                            
                            </div>  

                    
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
                                <input type="hidden" name="restday" id="restdayInput" value="<?php echo @$row['restday'] ?>" readonly>

                                <td><input type="checkbox" class="checkbox" name="monday" value="Monday" id="checkbox1"  onchange="updateRestday()" onclick="toggleInputs(this)"> Monday</td>
                                <td><input name="mon_timein" type="time" class="time-input" id="time1" oninput="validateTime()" disabled></td>            
                                <td><input name="mon_timeout" type="time" class="time-input" id="time2" oninput="validateTime()" disabled></td>
                                <td><input name ="mon_wfh" type="checkbox" class="checkbox-lg" value="WFH" ></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="tuesday" value="Tuesday" id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)"> Tuesday</td>
                                <td><input name="tues_timein" type="time" class="time-input" id="time3" oninput="validateTime()" disabled></td>            
                                <td><input name="tues_timeout" type="time" class="time-input" id="time4" oninput="validateTime()" disabled></td>
                                <td><input name ="tues_wfh" type="checkbox" class="checkbox-lg" value="WFH"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="wednesday" value="Wednesday" id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)"> Wednesday</td>
                                <td><input name="wed_timein" type="time" class="time-input" id="time5" oninput="validateTime()" disabled></td>
                                <td><input name="wed_timeout" type="time" class="time-input" id="time6" oninput="validateTime()" disabled></td>
                                <td><input name ="wed_wfh" type="checkbox" class="checkbox-lg" value="WFH"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="thursday" value="Thursday" id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)"> Thursday </td>
                                <td><input name="thurs_timein" type="time" class="time-input" id="time7" oninput="validateTime()" disabled></td>
                                <td><input name="thurs_timeout" type="time" class="time-input" id="time8" oninput="validateTime()" disabled></td>
                                <td><input name ="thurs_wfh" type="checkbox" class="checkbox-lg" value="WFH"></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" class="checkbox" name="friday" value="Friday" id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)"> Friday</td>
                                <td><input name="fri_timein" type="time" class="time-input" id="time9" oninput="validateTime()" disabled></td>
                                <td><input name="fri_timeout" type="time" class="time-input" id="time10" oninput="validateTime()" disabled></td>
                                <td><input name ="fri_wfh" type="checkbox" class="checkbox-lg" value="WFH"></td>
                            </tr>
                            <tr>
                            <td><input type="checkbox" class="checkbox" name="saturday" value="Saturday" id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)"> Saturday</td>
                            <td><input name="sat_timein" type="time" class="time-input" id="time11" oninput="validateTime()" disabled></td>
                                <td><input name="sat_timeout" type="time" class="time-input" id="time12" oninput="validateTime()" disabled></td>
                                <td><input name ="sat_wfh" type="checkbox" class="checkbox-lg" value="WFH"></td>
                            </tr>
                            <tr>
                            <td><input type="checkbox" class="checkbox" name="sunday" value="Sunday" id="checkbox1" onchange="updateRestday()" onclick="toggleInputs(this)"> Sunday</td>
                            <td><input name="sun_timein" type="time" class="time-input" id="time13" oninput="validateTime()" disabled></td>
                                <td><input name="sun_timeout" type="time" class="time-input" id="time14" oninput="validateTime()" disabled></td>
                                <td><input name ="sun_wfh" type="checkbox" class="checkbox-lg" value="WFH"></td>
                            </tr>
                            <tr>
                                <td ><input type="checkbox" name="flexible" id="" class="checkbox-lg" value="Flexible"> Flexible</td>
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
                                    <input type="checkbox" id="enable-number-input" name="enable_grace_period" class="checkbox-lg" value="true" >
                                    <label for="grace_period">Grace Period</label>
                                </div>
                                <div>
                                    <input class="numbox" id="my-number-input" type="number" name="grace_period" placeholder="00:00" disabled>
                                    <label for="graceperiod_minutes">Minutes</label>
                                </div>
                                
                            </div>
                            <div class="schedule-ot">
                                <div>
                                    <input type="checkbox" id="enable-number-input2" name="enable_sched_ot" class="checkbox-lg" value="true">
                                    <label for="ob_ot">Enable OT</label>
                                </div>
                                <div>
                                    <input class="numbox"  id="my-number-input2" type="number" name="sched_ot" placeholder="00:00" disabled>
                                    <label for="ob_minutes">Minutes</label> 
                                </div>
                            </div>
                            <div class="schedule-holiday">
                                <input type="checkbox" name="sched_holiday" id="" class="checkbox-lg" value="Holday Work">
                                <label for="ob_holiday">Holiday Work</label>
                            </div>
                        </div> 

                    </div>                   
                </div>
                </div> 
            </div>
       </div>
       </form>
       
       
       <!-- <form action="" method="">                   
       <div class="modal fade" id="empModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">User Info</h4>
                          <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>
                        <div class="modal-body">
                            
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
        </div> -->

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
        
<!---------Script sa pagdisable ng button kapag nagcheck ng checkbox at hindi naglagay sa time input-------->

<script>
  // Kunin ang mga checkbox at mga oras gamit ang pangalan ng klase
  var checkboxes = document.getElementsByClassName('checkbox');
  var timeInputs = document.getElementsByClassName('time-input');
  var submitBtn = document.getElementById('submit-btn');

  // Magdagdag ng event listener sa mga checkbox kapag nagbago ang pag-check
  Array.from(checkboxes).forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      var checked = Array.from(checkboxes).some(function (checkbox) {
        return checkbox.checked;
      });

      var emptyInputs = Array.from(timeInputs).some(function (timeInput) {
        return timeInput.value === '' && timeInput.disabled === false;
      });

      submitBtn.disabled = checked && emptyInputs;
    });
  });

  // Magdagdag ng event listener sa mga oras kapag nagbago ang pag-input
  Array.from(timeInputs).forEach(function (timeInput) {
    timeInput.addEventListener('input', function () {
      var checked = Array.from(checkboxes).some(function (checkbox) {
        return checkbox.checked;
      });

      var emptyInputs = Array.from(timeInputs).some(function (timeInput) {
        return timeInput.value === '' && timeInput.disabled === false;
      });

      submitBtn.disabled = checked && emptyInputs;
    });
  });
</script>
<!---------Script sa pagdisable ng button kapag nagcheck ng checkbox at hindi naglagay sa time input-------->

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

<!------------------------------Script sa chain dropdown---------------------------------------->
<script>
// Kapag nagbago ang pagpili sa select department dropdown
document.getElementById("select_department").addEventListener("change", function() {
    var departmentID = this.value; // Kunin ang value ng selected department

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var employees = JSON.parse(this.responseText);
            var employeeDropdown = document.getElementById("employee-dd");
            employeeDropdown.innerHTML = ""; // I-clear ang current options

            // I-update ang employee dropdown base sa mga nakuha na empleyado
            if (departmentID == "All Department") {
                // Kapag "All Department" ang napili, ipakita ang "All Employee" kasama ang detalye ng bawat empleyado
                var allEmployeeOption = document.createElement("option");
                allEmployeeOption.value = "All Employee";
                allEmployeeOption.text = "All Employee";
                employeeDropdown.appendChild(allEmployeeOption);

                employees.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    employeeDropdown.appendChild(option);
                });
            } else {
                // Kapag ibang department ang napili, ipakita ang mga empleyado base sa department
                employees.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    employeeDropdown.appendChild(option);
                });
            }

            // I-enable ang employee dropdown
            employeeDropdown.disabled = false;
        }
    };
    xhttp.open("GET", "get_employees.php?departmentID=" + departmentID, true);
    xhttp.send();
});
</script>
<!------------------------------Script sa chain dropdown---------------------------------------->


<!------------------------Validation if lumagpas ng 12hrs ang value ng time------------------------------->        
<script>
function validateTime() {
    var timePairs = [
        [document.getElementById("time1").value, document.getElementById("time2").value],
        [document.getElementById("time3").value, document.getElementById("time4").value],
        [document.getElementById("time5").value, document.getElementById("time6").value],
        [document.getElementById("time7").value, document.getElementById("time8").value],
        [document.getElementById("time9").value, document.getElementById("time10").value],
        [document.getElementById("time11").value, document.getElementById("time12").value],
        [document.getElementById("time13").value, document.getElementById("time14").value],
       
    ];

    var errorMessage = document.getElementById('errorMessage');
    var submitBtn = document.getElementById('submit-btn');

    var hasInvalidSchedule = false;

    for (var i = 0; i < timePairs.length; i++) {
        var timeIn = timePairs[i][0];
        var timeOut = timePairs[i][1];

        if (timeIn !== "" && timeOut !== "") {
            var timeInDate = new Date("2000-01-01 " + timeIn);
            var timeOutDate = new Date("2000-01-01 " + timeOut);
            var diffInMinutes = (timeOutDate - timeInDate) / 60000;

            if (diffInMinutes > 720 || diffInMinutes <= 0) {
                hasInvalidSchedule = true;
                break;
            }
        }
    }

    if (hasInvalidSchedule) {
        errorMessage.textContent = "Invalid work schedule. The duration should be within 12 hours.";
        submitBtn.disabled = true;
    } else {
        errorMessage.textContent = "";
        submitBtn.disabled = false;
    }
}
</script>
<!------------------------End Validation if lumagpas ng 12hrs ang value ng time------------------------------->    


<!------------------------End Validation if Null ang value time------------------------------->
        
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
  function removeErrorMessage() {
    var errorMsg = document.getElementById("errorMsg");
    var parentElement = errorMsg.parentElement;
    parentElement.style.display = "none";

    var url = new URL(window.location.href);
    var params = new URLSearchParams(url.search);

    params.delete("errorMsg");
    params.delete("showError");

    // Update the URL without query parameters
    window.history.replaceState({}, document.title, url.pathname + params.toString());
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


// filter

// function filter(item){
// $.ajax({
// type: "POST",
// url: "Data Controller/empListFormController.php",
// data: { value: item},
// success:function(data){
//   $("#results").html(data);
// }
// });
// }


// function getEmployee(val){
//     $.ajax({
//         type: "POST",
//         url: "getEmployee.php",
//         data: 'empid='+val,
//         success:function(data){
//              $("employee-dd").html(data);
//              getEmployee();
//          }
//     });
// }
// </script>

<!-- <script type='text/javascript'>
            $(document).ready(function(){
                $('.schedule-info').click(function(){
                    var id = $(this).data('id');
                    $.ajax({
                        url: 'ajaxfile.php',
                        type: 'post',
                        data: {id: id},
                        success: function(response){ 
                            $('.modal-body').html(response); 
                            $('.schedModal').modal('show'); 
                        }
                    });
                });
            });
</script> -->

<script>
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
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