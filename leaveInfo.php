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
        

    //    echo $image_url;
    }

    // echo $userId;
    // echo $_SESSION['empid'];

    
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
    <link rel="stylesheet" href="css/leaveInfo.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/leaveInfoResponsive.css">
    <link rel="stylesheet" type="text/css" href="css/virtual-select.min.css">
</head>
<body>


<!--MODAL BOOTSTRAP-->
<header>
    <?php
    include 'header.php';
    ?>
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


<!-- Modal -->
<div class="modal fade" id="add_leaveMDL" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5 " id="exampleModalLabel" >Add Leave Credits</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="actions/Leave Information/insertcode.php" method="POST">
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

        <div class="mb-3">
            <label for="emp">Select Employee</label><br>
                <div id="employeeDropdown">
                <select class="approver-dd dd-hide" name="empid[]" id="multi_option" multiple placeholder="Select Employee" data-silent-initial-value-set="false" style="display:flex; width: 380px;">
                </select>
            </div>
        </div>

            <div class="mb-3">
                <label for="vctn_lve" class="form-label">Vacation Leave</label>
                <div class="input-group mb-3">
                <input type="text" name="name_vctn_lve" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value > 20) this.value = 20; if(this.value < 0) this.value = 1; if(this.value.length > 2) this.value = this.value.slice(0, 2);" class="form-control" aria-label="Amount (to the nearest dollar)" id="vleave" required>
                    <span class="input-group-text" > <input type="text" onclick="changeVal1()" id="id_addV" style="background-color: inherit; border: none; font-size: 15px; cursor: pointer;" title="CLick Me to change the decimal" name="name_vctn_lve1" readonly value=".0"></span>
                </div>

            </div>
            <div class="mb-3">
                <label for="sick_lve" class="form-label">Sick Leave</label>
                    <div class="input-group mb-3">
                    <input type="text" name="name_sick_lve" oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value > 20) this.value = 20; if(this.value < 0) this.value = 1; if(this.value.length > 2) this.value = this.value.slice(0, 2);" class="form-control" aria-label="Amount (to the nearest dollar)" id="vleave" required>
                        <span class="input-group-text"><input type="text" onclick="changeVal2()" id="id_addS" style="background-color: inherit; border: none; font-size: 15px; cursor: pointer;" title="CLick Me to change the decimal" name="name_sick_lve1" readonly value=".0"></span>
                    </div>
                    <p></p>
            </div>
            <div class="mb-3">
                <label for="brvmnt_lve" class="form-label">Bereavement Leave</label>
                    <div class="input-group mb-3">
                    <input type="text" name="name_brvmnt_lve"  oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value > 20) this.value = 20; if(this.value < 0) this.value = 1; if(this.value.length > 2) this.value = this.value.slice(0, 2);" class="form-control" aria-label="Amount (to the nearest dollar)" id="vleave" required>
                        <span class="input-group-text"><input type="text" onclick="changeVal3()" id="id_addB" style="background-color: inherit; border: none; font-size: 15px; cursor: pointer; " title="CLick Me to change the decimal" name="name_brvmnt_lve1"  readonly value=".0"></span>
                    </div>
            </div>

        </div>
      <div class="modal-footer">
        <button type="submit" name="save_changes" class="btn btn-primary">Add Credits</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
    
  </div>
</div> <!--MODAL BOOTSTRAP END-->


    <div class="main-panel mt-5" style="margin-left: 15%; position: absolute; top:0;">
        <div class="content-wrapper mt-4">
          <div class="card mt-3" style=" width: 1550px; height:790px; box-shadow: 0 5px 8px 0 rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.17);">
            <div class="card-body">

                        <div class="row">
                                <div class="col-6">
                                    <p style="font-size: 25px; padding: 10px">Leave Credits</p>
                                </div>
                                
                                <div class="col-6 text-end btn-hover">
                                <!-- Button trigger modal -->
                                <button class="btn_addLeave" data-bs-toggle="modal" data-bs-target="#add_leaveMDL" style="background-color: black; color: white"> 
                                    Add Leave
                                </button>
                            </div>
                        </div>


                    <div class="leave_select_dept">
                                <div class="container_leave">
                                <!-- <p class="demm-text">Select Department</p> -->
                                <?php
                                        include 'config.php';

                                        // Fetch all values of col_deptname from the database
                                        $sql = "SELECT col_deptname FROM dept_tb";
                                        $result = mysqli_query($conn, $sql);

                                        // Store all values in an array
                                        $dept_options = array();
                                        while ($row = mysqli_fetch_array($result)) {
                                            $dept_options[] = $row['col_deptname'];
                                        }

                                        $Department = isset($_GET['col_deptname']) ? ($_GET['col_deptname']) : '';
                                        // Generate the dropdown list
                                        echo "<select class='dropdown_dept' aria-label='.form-' name='depart_name' id='select_depart' style='padding: 10px;'>";
                                        echo "<option value='All Department'" . ($Department == 'All Department' ? ' selected' : '') . ">All Department</option>";
                                        foreach ($dept_options as $dept_option) {
                                            echo "<option value='$dept_option'" . ($Department == $dept_option ? ' selected' : '') . ">$dept_option</option>";
                                        }
                                        echo "</select>";
                                        ?>
                                </div>
                                    
                                    <button type="button" class="apply_btn" name="buttonGO" id="id_btngo" onclick="filterLeaveInfo()"> &rarr; Apply Filter</button>
                    </div> <!--Container Select-->
                        <!---------------------para sa message na sucessful START -------------------->
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

                        <!---------------------para sa message na error START -------------------->
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

                        <div class="table-responsive" id="table-responsiveness" style="width: 98%; margin:auto; margin-top: 30px;">
                                    <table id="order-listing" class="table mt-2" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style= 'display: none;' id="header">ID</th>
                                        <th id="header"> Employee ID  </th>
                                        <th>Employee Name</th>
                                        <th>Employee Department</th>
                                        <th class= 'text-center'>Vacation Leave</th>
                                        <th class= 'text-center'>Sick Leave</th>
                                        <th class= 'text-center'>Bereavement Leave</th>
                                        <th>Action</th>                            
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                        include 'config.php';
                                        //select data db
                                        $Depart = $_GET['col_deptname'] ?? '';

                                        $sql = "SELECT
                                        leaveinfo_tb.`col_ID`,
                                        employee_tb.`empid`,
                                        CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`,
                                        dept_tb.col_deptname,
                                        leaveinfo_tb.`col_vctionCrdt`,
                                        leaveinfo_tb.`col_sickCrdt`,
                                        leaveinfo_tb.`col_brvmntCrdt`
                                    FROM
                                        employee_tb
                                    INNER JOIN leaveinfo_tb ON employee_tb.empid = leaveinfo_tb.`col_empID`
                                    INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.`col_ID`";
                            
                                        if (!empty($Depart) && $Depart != 'All Department') {
                                            $sql .= " WHERE dept_tb.col_deptname = '$Depart'";
                                        }
                                                
  
                                        $result = $conn->query($sql);

                                        //read data
                                        while($row = $result->fetch_assoc()){
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
                            

                                            $_SESSION["id"] =  $row['col_ID'];
                                            echo "<tr>
                                                <td style= 'display: none;'>" . $row['col_ID']. "</td>
                                                <td style='font-weight: 400'>";
                                                $cmpny_code = $cmpny_row['company_code_name'] ?? null;
                                                $empid = $row['empid'];
                                                if (!empty($cmpny_code)) {
                                                    echo $cmpny_code . " - " . $empid;
                                                } else {
                                                    echo $empid;
                                                }
                                                echo "</td>
                                                <td style='font-weight: 400'>" . $row['full_name'] . "</td>
                                                <td style='font-weight: 400'>" . $row['col_deptname'] . "</td>
                                                <td style='font-weight: 400' class= 'text-center'>" . $row['col_vctionCrdt'] . "</td>
                                                <td style='font-weight: 400' class= 'text-center'>" . $row['col_sickCrdt'] . "</td>
                                                <td style='font-weight: 400' class= 'text-center'>" . $row['col_brvmntCrdt'] . "</td>
                                                <td style='font-weight: 400'>
                                                <button style='background-color: inherit; border:none;' type='button' class= 'border-light editbtn' title = 'Edit' data-bs-toggle='modal' data-bs-target='#id_editmodal'>
                                                <i class='fa-solid fa-pen-to-square fs-5 me-3' title='edit'></i>
                                                </button>
                                                <button style='background-color: inherit; border:none;' type='button' class= 'border-light' title = 'Delete'>
                                                    <a href='actions/Leave Information/delete.php?col_ID=" . $row['col_ID'] . "' class='link-dark'> <i class='fa-solid fa-trash fs-5 me-3 title='delete'></i> </a>
                                                </button>
                                                    
                                                </td>
                                            </tr>"; 
                                        }
                                    ?>  
                                </tbody>   
                            </table>
                         </div>
                      </div>


 <!-- Modal EDIT -->
 <div class="modal fade" id="id_editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                <form action="actions/Leave Information/update.php" method="POST">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Leave Credits</h1>
                                        <input type="text" id="id_colId" name="name_id" style= "display: none;">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    
                                    <div class="modal-body"> 
                                        
                                        <div class="mb-2">
                                            <label for="name_employee_fname" class="form-label">Employee Name :</label>
                                                <div class="input-group">
                                                    <input type="text" id= "id_fname" name="name_employee_fname" class="form-control bg-light" aria-label="Amount (to the nearest dollar)" disabled>
                                                </div>
                                        </div>
                                        <!--              line break                     --> 
                                        <div class="mb-2">
                                            <label for="name_employee_Dept" class="form-label">Employee Department :</label>
                                                <div class="input-group ">
                                                    <input type="text" id="id_dept" name="name_employee_Dept" class="form-control bg-light" aria-label="Amount (to the nearest dollar)" disabled>
                                                </div>
                                        </div>
                                        <!--              line break                     --> 

                                        <div class="mb-2">
                                            <label for="name_employee_Dept" class="form-label">Vacation Leave :</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name= "name_set_Vcrdt" style="width: 10px;"  id="id_v_crdt" class="form-control bg-light" aria-label="" readonly>
                                                <input type="text"  oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 2) this.value = this.value.slice(0, 2);" name= "name_updt_Vcrdt" id="id_Tv_crdt" class="form-control" placeholder="00.0" required aria-label="" >

                                                <span class="input-group-text"><input type="text" onclick="changeVal4()" id="id_updtV" style="background-color: inherit; border: none; font-size: 15px; cursor: pointer; width: 20px;" title="CLick Me to change the decimal" name="name_updt_Vcrdt1" readonly value=".0"></span>
                                            </div>
                                        </div>
                                        <!--              line break                     --> 

                                        <div class="mb-2">
                                            <label for="name_employee_Dept" class="form-label">Sick Leave :</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name= "name_set_Scrdt" style="width: 10px;" id="id_s_crdt" class="form-control bg-light" aria-label="" readonly>
                                                <input type="text"  oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 2) this.value = this.value.slice(0, 2);" name= "name_updt_Scrdt" id="id_Ts_crdt" class="form-control" placeholder="00.0" required aria-label="">


                                                <span class="input-group-text"><input type="text" onclick="changeVal5()" id="id_updtS" style="background-color: inherit; border: none; font-size: 15px; cursor: pointer; width: 20px;" title="CLick Me to change the decimal" name="name_updt_Scrdt1" readonly value=".0"></span>
                                            </div>
                                        </div>
                                        <!--              line break                     --> 

                                        <div class="mb-2">
                                            <label for="name_employee_Dept" class="form-label">Bereavement Leave :</label>
                                            <div class="input-group mb-3">
                                                <input type="text" name= "name_set_Bcrdt" style="width: 10px;" id="id_B_crdt" class="form-control bg-light" aria-label="" readonly>

                                                <input type="text"  oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 2) this.value = this.value.slice(0, 2);" name= "name_updt_Bcrdt" id="id_TB_crdt" class="form-control" placeholder="00.0" required aria-label="">
                                                
                                                <span class="input-group-text"><input type="text" onclick="changeVal6()" id="id_updtB" style="background-color: inherit; border: none; font-size: 15px; cursor: pointer; width: 20px;" title="CLick Me to change the decimal" name="name_updt_Bcrdt1" readonly value=".0"></span>
                                            </div>
                                        </div>

                                        <!--              line break                     --> 
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id= "id_btnUpdate" name="updatedata" class="btn btn-primary">Save changes</button>
                                    </div>
                                    </form>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                function changeVal1() {
                    let add_input = document.getElementById("id_addV").value;
                    
                    if (add_input === ".0") {
                        document.getElementById("id_addV").value = ".5";
                    } else {
                        document.getElementById("id_addV").value = ".0";
                    }
                }

                function changeVal2() {
                    let add_input = document.getElementById("id_addS").value;
                    
                    if (add_input === ".0") {
                        document.getElementById("id_addS").value = ".5";
                    } else {
                        document.getElementById("id_addS").value = ".0";
                    }
                }

                function changeVal3() {
                    let add_input = document.getElementById("id_addB").value;
                    
                    if (add_input === ".0") {
                        document.getElementById("id_addB").value = ".5";
                    } else {
                        document.getElementById("id_addB").value = ".0";
                    }
                }

                function changeVal4() {
                    let add_input = document.getElementById("id_updtV").value;
                    
                    if (add_input === ".0") {
                        document.getElementById("id_updtV").value = ".5";
                    } else {
                        document.getElementById("id_updtV").value = ".0";
                    }
                }

                function changeVal5() {
                    let add_input = document.getElementById("id_updtS").value;
                    
                    if (add_input === ".0") {
                        document.getElementById("id_updtS").value = ".5";
                    } else {
                        document.getElementById("id_updtS").value = ".0";
                    }
                }

                function changeVal6() {
                    let add_input = document.getElementById("id_updtB").value;
                    
                    if (add_input === ".0") {
                        document.getElementById("id_updtB").value = ".5";
                    } else {
                        document.getElementById("id_updtB").value = ".0";
                    }
                }
            </script>


<script>
    function filterLeaveInfo() {
        var depart = document.getElementById('select_depart').value;

        var url = 'leaveInfo.php?&col_deptname=' + depart;
        window.location.href = url;
    }
</script>



<script>
     $(document).ready(function(){
                            $('.editbtn').on('click', function(){
                                $('#id_editmodal').modal('show');
                                $tr = $(this).closest('tr');

                                var data = $tr.children("td").map(function () {
                                    return $(this).text();
                                }).get();

                                console.log(data);
                                //id_colId
                                $('#id_colId').val(data[0]);
                                $('#id_fname').val(data[2]);
                                $('#id_dept').val(data[3]);
                                $('#id_v_crdt').val(data[4]);
                                $('#id_s_crdt').val(data[5]);
                                $('#id_B_crdt').val(data[6]);
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
    <script src="js/leaveInfo.js"></script>
</body>
</html>