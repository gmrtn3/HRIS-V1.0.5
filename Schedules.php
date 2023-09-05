
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/schedule.css">
    <title>HRIS | Schedule</title>
</head>
<body>
    <header>
        <?php include("header.php")?>
    </header>

    <style>
         table {
                display: block;
                overflow-x: hidden;
                white-space: nowrap;
                max-height: 450px;
                height: 450px;
                
                
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

    <div class="modal fade" id="schedUpdate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="title">Update Schedule</h1>
                </div>
                    <form action="Data Controller/Schedules/schedUpdate.php" method="POST">
                        <div class="modal-body">
                            
                            <div class="mb-3" >
                                <?php  
                                    $conn =mysqli_connect("localhost", "root", "" , "hris_db");
                                    $stmt = "SELECT * FROM employee_tb
                                            AS emp
                                            INNER JOIN empschedule_tb
                                            AS esched
                                            ON(emp.empid = esched.empid)  LIMIT 1";
                                    $result = $conn->query($stmt);
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                                echo "<input type='text' id='empName' style='border:none; font-size: 20px; font-weight: 500; margin: auto;' >";
                                            }
                                        }
                                ?>

                                <input type="hidden" class="form-control" name="empid" id="empid" readonly>
                            </div>
                            <div class="mb-3 mt-4 form-group">
                                <?php
                                    include 'config.php';
                                    $sql = "SELECT schedule_name FROM schedule_tb";
                                    $result = mysqli_query($conn, $sql);

                                    $options = "";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $options .= "<option style='color:black;' value='".$row['schedule_name']."'>" .$row['schedule_name']."</option>";
                                        }
                                ?>
                                <label for="schedule_name">Schedule Type</label><br>
                                <select name="schedule_name" class="form-control" id="schedule_name" style="color: black">                               
                                    <?php echo $options; ?>
                                </select>
                            </div>
                            <div class="mb-3" class="form-group">
                                
                                <label for="sched_from">From</label>
                                <input type="date" name="sched_from" id="sched_from" class="form-control" onchange="datevalidate()" min="<?php echo date('Y-m-d'); ?>">
                                <div id="sched_from_error" class="text-danger"></div>

                                <label for="sched_from" class="mt-3">To</label>
                                <input type="date" name="sched_to" id="sched_to" class="form-control"  onchange="datevalidate()">
                                <div id="sched_to_error" class="text-danger"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border: none; background-color: inherit;">Close</button>
                                <button type="submit" class="btn btn-primary" id="submit-btn" style="background-color: black; border: none;"> Update </button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>

    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4" style="background-color: #f4f4f4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">

                        <div class="row">
                                <div class="col-6">
                                    <p style="font-size: 25px; padding: 10px">Schedules</p>
                                </div>
                                
                                <div class="col-6 text-end">
                                <a href="scheduleForm" class="sched-list" ><button class="sched-button">Schedule List</button></a>
                            </div>
                        </div>

                        <!----------------------------------------select button and text input--------------------------------------->
                            <div class="schedule_container">
                                        <div class="Schedule_content">
                                        <p class="schedule_dept_text">Select Department</p>
                                        <?php
                                            include('config.php');

                                            $sql = "SELECT col_ID, col_deptname FROM dept_tb";
                                            $result = mysqli_query($conn, $sql);
                                            
                                            $Department = isset($_GET['department_name']) ? ($_GET['department_name']) : '';

                                            $options = "";
                                            $options .= "<option value='All Department'" .($Department == 'All Department' ? ' selected' : '').">All Department</option>";
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $selected = ($Department == $row['col_ID']) ? 'selected' : '';
                                                $options .= "<option value='" . $row['col_ID'] . "' " . $selected . ">" . $row['col_deptname'] . "</option>";
                                            }
                                        ?>
                                            <select class="schedule_dropdown form-select-m" aria-label=".form-select-sm example" name="department" id="select_department">
                                                <option value="" disabled selected>Select Department</option>
                                                <?php echo $options; ?>
                                            </select>
                                        </div>
                                            
                                        <div class="Schedule_content">
                                            <p class="schedule_dept_text">Select Employee</p>
                                            <label for="employee"></label>
                                                <select  class="schedule_dropdown form-select-m" aria-label=".form-select-sm example" name="employee" id="select_employee" disabled>
                                                    <option value="" disabled selected>Select Employee</option>
                                                </select>
                                        </div>

                                            
                                    <button class="schedule_btn"  onclick="filterSched()"> &rarr; Apply Filter</button>
                            </div> <!--Container Select-->
                            <!----------------------------------------select button and text input--------------------------------------->
                            
                                <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                                    <table id="order-listing" class="table" style="width: 100%">
                                        <thead>
                                            <th>Employee ID</th>
                                            <th>Employee Fullname</th>
                                            <th>Time Entry</th>
                                            <th>Time Out</th>
                                            <th>Rest Day(s)</th>
                                            <th style="display: none;">View Restday</th>
                                            <th>Work Setup</th>
                                            <th>From(Date)</th>
                                            <th>To(Date)</th>
                                            <th>Action</th>
                                            <th style="display: none;">Action</th>
                                        </thead>
                                        <?php
                                                include 'config.php';
                                                $department = $_GET['department_name'] ?? '';
                                                $employee = $_GET['empid'] ?? '';
                                                
                                                $query = "SELECT * FROM employee_tb 
                                                          INNER JOIN empschedule_tb ON employee_tb.empid = empschedule_tb.empid
                                                          INNER JOIN schedule_tb ON empschedule_tb.schedule_name = schedule_tb.schedule_name
                                                          INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID";
                                                
                                                if (!empty($department) && $department != 'All Department') {
                                                    $query .= " WHERE employee_tb.department_name = '$department'";
                                                }
                                                
                                                if (!empty($employee) && $employee != 'All Employee') {
                                                    $query .= " AND employee_tb.empid = '$employee'";
                                                }
                                                
                                                $result = $conn->query($query);
                                                
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Get the current day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
                                                        $currentDayOfWeek = date('w');
                                                
                                                        // Construct the column names for the current day
                                                        $dayNames = array('sun', 'mon', 'tues', 'wed', 'thurs', 'fri', 'sat');
                                                        $timeInColumn = strtolower($dayNames[$currentDayOfWeek]) . '_timein';
                                                        $timeOutColumn = strtolower($dayNames[$currentDayOfWeek]) . '_timeout';
                                                
                                                        // Get the time entry and time out for the current day from schedule_tb
                                                        $timeEntry = $row[$timeInColumn];
                                                        $timeOut = $row[$timeOutColumn];
                                                
                                                        // Check if time entry and time out are empty, then display "No Schedule"
                                                        if (empty($timeEntry) || empty($timeOut)) {
                                                            $timeEntry = "No Schedule";
                                                            $timeOut = "No Schedule";
                                                        }
                                                
                                                        echo "
                                                        <tr class='lh-1'>
                                                            <td style='font-weight: 400;'>" . $row["empid"] . "</td>
                                                            <td style='font-weight: 400;'>" . $row["fname"] . " " . $row["lname"] . "</td>
                                                            <td style='font-weight: 400;'>{$timeEntry}</td>
                                                            <td style='font-weight: 400;'>{$timeOut}</td>
                                                            <td style='font-weight: 400; display: none;'>" . $row["restday"] . "</td>
                                                            <td><i class='fa-solid fa-eye viewbtn' data-bs-toggle='modal' data-bs-target='#view_rest_modal' style='cursor: pointer;'></i></td>
                                                            <td style='font-weight: 400;'>" . $row["schedule_name"] . "</td>
                                                            <td style='font-weight: 400;'>" . $row["sched_from"] . "</td>
                                                            <td style='font-weight: 400;'>" . $row["sched_to"] . "</td>
                                                            <td>
                                                                <button type='button' data-bs-toggle='modal' data-bs-target='#schedUpdate' id='sched-update' class='sched-update' style='border:none; background-color:inherit; color:cornflowerblue; outline:none;'>Update</button>
                                                            </td>
                                                            <td style='display: none;'>" . $row['empid'] . "</td>
                                                        </tr>";
                                                    }
                                                } else {
                                                    // If no data found, display a message row
                                                    // echo "
                                                    // <tr>
                                                    //     <td colspan='10' style='text-align: center;'>No data available.</td>
                                                    // </tr>";
                                                }
                                            ?>
                                    </table>
                            </div>


                    <!-----------------------Modal that can view restday----------------------------->
                    <div class="modal fade" id="view_rest_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">

                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Restday</h1>
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
                    <!-----------------------Modal that can view restday----------------------------->

                    <!-- <form action="">
                    <div class="schedules-modal-update" id="schedules-modal-update">
                        <div class="sched-container">
                            <div class="sched-content">
                                <div class="schedmodal-title">
                                <h1>Update Schedule</h1>
                                <div></div>
                                </div>
                                <div class="schedmodal-emp">
                                    
                                <?php  
                                        include 'config.php';
                                        $stmt = "SELECT * FROM employee_tb
                                                AS emp
                                                INNER JOIN empschedule_tb
                                                AS esched
                                                ON(emp.empid = esched.empid)  LIMIT 1";
                                                $result = $conn->query($stmt);
                                                if($result->num_rows > 0){
                                                    while($row = $result->fetch_assoc()){
                                                        echo "<h1>".$row["fname"].""." ". "" .$row["lname"]."</h1>";
                                                    }
                                                }
                                        ?>
                                
                                </div>
                                <div class="schedule-type-update">
                                <?php
                                    include 'config.php';
                                    $sql = "SELECT schedule_name FROM schedule_tb";
                                    $result = mysqli_query($conn, $sql);

                                    $options = "";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $options .= "<option value=' ". $row['schedule_name'] . "'>" .$row['schedule_name']."</option>";
                                        }
                                        ?>

                                    <label for="schedule_name">Schedule Type</label><br>
                                    <select name="schedule_name" id="">
                                        <option value disabled selected>Select Schedule Type</option>
                                        <?php echo $options; ?>
                                    </select>
                                </div>
                                <div class="sched-update-date">
                                <label for="sched_from">From</label>
                                <input type="date" name="" id="">

                                <label for="sched_from">To</label>
                                <input type="date" name="" id="">
                                <div>
                                
                                <div class="sched-update-btn">
                                <button value="Cancel" id="sched-update-close" class="sched-update-close">Close</button>
                                <button value="" type="submit">Submit</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    </form> -->

            </div>
          </div>
        </div>
     </div>
    

    <!----------------------Script sa dropdown chain--------------------------->        
<script>
// Kapag nagbago ang pagpili sa select department dropdown
document.getElementById("select_department").addEventListener("change", function() {
    var departmentID = this.value; // Kunin ang value ng selected department

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var employees = JSON.parse(this.responseText);
            var employeeDropdown = document.getElementById("select_employee");
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

function filterSched() {
        var department = document.getElementById('select_department').value;
        var employee = document.getElementById('select_employee').value;

        var url = 'Schedules.php?department_name=' + department + '&empid=' + employee;
        window.location.href = url;
    }
</script>
<!----------------------Script sa dropdown chain--------------------------->      

    <!------------------------------------Script para sa pag pop-up ng view modal------------------------------------------------->
<script>
     $(document).ready(function(){
               $('.viewbtn').on('click', function(){
                 $('#view_rest_modal').modal('show');
                      $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function () {
                    return $(this).text();
                    }).get();
                   console.log(data);
                   $('#view_reason1').val(data[4]);
               });
             });
</script>
<!---------------------------------End ng Script para sa pag pop-up ng view modal------------------------------------------>
    
    <script>
function populateDateFields(row) {
    var startDate = row.getElementsByTagName('td')[6].innerHTML;
    var endDate = row.getElementsByTagName('td')[7].innerHTML;

    document.getElementById('sched_from').value = startDate;
    document.getElementById('sched_to').value = endDate;
}

var updateButtons = document.getElementsByClassName('sched-update');
for (var i = 0; i < updateButtons.length; i++) {
    updateButtons[i].addEventListener('click', function() {
        var row = this.closest('tr');
        populateDateFields(row);
    });
}

function datevalidate() {
    var startDateInput = document.getElementById('sched_from');
    var endDateInput = document.getElementById('sched_to');
    var startDate = new Date(startDateInput.value);
    var endDate = new Date(endDateInput.value);
    var today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time to midnight for comparison

    var startError = document.getElementById('sched_from_error');
    var endError = document.getElementById('sched_to_error');
    var submitBtn = document.getElementById('submit-btn');

    if (startDate < today) {
        startError.innerHTML = "Start Date must be today or a future date.";
    } else {
        startError.innerHTML = "";
    }

    if (endDate < startDate) {
        endError.innerHTML = "End Date must be equal to or greater than Start Date.";
    } else {
        endError.innerHTML = "";
    }

    if (startError.innerHTML !== "" || endError.innerHTML !== "") {
        submitBtn.disabled = true;
    } else {
        submitBtn.disabled = false;
    }
}
</script>

  

<script>
// sched form modal

let Modal = document.getElementById('schedules-modal-update');

//get open modal
let modalBtn = document.getElementById('sched-update');

//get close button modal
let closeModal = document.getElementsByClassName('sched-update-close')[0];

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
                                    $('#empid').val(data[10]);
                                    $('#sched_from').val(data[7]);
                                    $('#sched_to').val(data[8]);
                                    $('#empName').val(data[1]);
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