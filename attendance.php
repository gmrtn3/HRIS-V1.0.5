
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
 
    // $server = "localhost";
    // $user = "root";
    // $pass ="";
    // $database = "hris_db";

    // $db = mysqli_connect($server, $user, $pass, $database);
    include 'config.php';


    if(!empty($_GET['status'])){
        switch($_GET['status']){
            case 'succ':
                $statusType = 'alert-success';
                $statusMsg = 'Members data has been imported successfully.';
                break;
            case 'err':
                $statusType = 'alert-danger';
                $statusMsg = 'Some problem occurred, please try again.';
                break;
            case 'invalid_file':
                $statusType = 'alert-danger';
                $statusMsg = 'Please upload a valid CSV file.';
                break;
            default:
                $statusType = '';
                $statusMsg = '';
        }
    }

// FOR ATTENDANCE AUTO REFRESHER ABSENT
$_query_attendance = "SELECT * FROM attendances";
$result_attendance = mysqli_query($conn, $_query_attendance);
if(mysqli_num_rows($result_attendance) > 0){
    include ('Data Controller/Attendance/absent_refreshed.php'); // para mag generate ng automatic absent feature
   
    
}
// FOR ATTENDANCE AUTO REFRESHER ABSENT END




?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    

    


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
    <link rel="stylesheet" href="css/attendance.css">
    <!-- <link rel="stylesheet" href="css/attendanceResponsive.css"> -->

    <title>HRIS | Employee List</title>
    <script>
        function closeErrorMessage() {
            document.getElementById('error-message').style.display = 'none';
            window.history.replaceState({}, document.title, 'attendance.php');
        }
       

// Call the checkReload function when the page loads
window.onload = checkReload;
    </script>
</head>
<script>
      // Function to display the current date in the specified format
      function displayCurrentDate() {
        // Get the current date
        const today = new Date();

        // Define the date format as "MM/DD/YYYY"
        const dateFormat = `${today.getMonth() + 1}/${today.getDate()}/${today.getFullYear()}`;

        // Update the content of the h1 element with the current date
        document.getElementById("current-date").innerHTML = `Date today is <strong style=" color: #C37700">${dateFormat}</strong>`;
      }
    </script>


<body onload="displayCurrentDate()">

    <header>
        <?php include("header.php")?>
    </header>

    <style>
        html{
            overflow-y: hidden !important;
        }
        table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                max-height: 330px;
                height: 330px;
                
                
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
            .pagination{
        margin-right: 64px !important;
        
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
        border-color: #000;
    }

    
    
    #order-listing_next{
        margin-right: 28px !important;
        margin-bottom: -16px !important;

    }
    
    th.sorting_asc::after{
        opacity: 1 !important;
    }

    th.sorting_asc::before{
        opacity: 1 !important;
    }
    .empid-width{
        width: 4.6% !important;
    }
    .email-col {
    width: 13% !important; 
}
    #table-reposiveness{
        width: 95%; height: 100%; margin:auto; margin-top: 30px;
    }
    
    .attendance-input button:hover{
        background-color: #b882ee !important;
        transition: ease-in-out 0.3s !important;
    }
@media(max-width: 1350px){
    html{
        background-color: #fff !important;
        
    }
    
    .header-user{
       
        margin-right: 10px !important;
        width: 400px !important;
    }

    .header-notif{
        margin-right: 30px !important;
    }
    .header-head{
        margin-right: 25px !important;
    }


   .attendace-container{
    background-color: #fff !important;
    width: 72% !important;
    margin-right: -25px !important;
    transition: ease-in-out 1s !important;
    overflow: hidden !important;
   }

   .attendance-input{
    
    width: 95%;
    margin: auto !important;
   }

   .attendance-input select{
    width: 200px !important;
   }

   .attendance-input input{
    width: 200px !important;
   }

   .att-emp{
    
    width: 350px;
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between;
   }

   .att-emp label{
    font-size: 14px;
   }

   .att-emp select{
    margin-right: 30px;
    background-color: #fff !important;
   }

   .att-stat{
    
    width: 350px;
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between;
   }

   .att-stat label{
    font-size: 14px;
   }

   .att-stat select{
    margin-right: 30px;
    background-color: #fff !important;
   }

   .att-range{
   
    width: 350px;
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between;
   }

   .att-rang label{
    font-size: 14px;
   }

   .att-range input{
    margin-right: 30px;
    background-color: #fff !important;
   }

   .att-end{
   
    width: 350px;
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between;
   }

   .att-end label{
    font-size: 14px;
   }

   .att-end input{
    margin-right: 30px;
    background-color: #fff !important;
   }

   .att-excel-input{
    margin-top: 0px !important;
    
    width: 300px !important;
    margin-right: -83px !important;
   }

   .att-excel-input input{
    width: 300px !important;
   }

   .att-excel-input input:nth-child(2){
    margin-top: 20px !important;
    width: 100px !important;
    margin-left: 50px !important;
   }

   /* #table-responsiveness{
    width: 95% !important;
   } */

   thead th:nth-child(1){
    width: 70px !important;
   }

   tr td:nth-child(1){
    width: 70px !important;
   }

   thead th:nth-child(2){
    width: 85px !important;
   }

   tr td:nth-child(2){
    width: 85px !important;
   }

   thead th:nth-child(3){
    width: 120px !important;
   }

   tr td:nth-child(3){
    width: 120px !important;
   }

   thead th:nth-child(4){
    width: 70px !important;
   }

   tr td:nth-child(4){
    width: 85px !important;
   }

   thead th:nth-child(5){
    width: 85px !important;
   }

   tr td:nth-child(5){
    width: 85px !important;
   }

   thead th:nth-child(6){
    width: 85px !important;
   }

   tr td:nth-child(6){
    width: 85px !important;
   }

   thead th:nth-child(7){
    width: 85px !important;
   }

   tr td:nth-child(7){
    width: 100px !important;
   }

   thead th:nth-child(8){
    width: 85px !important;
   }

   tr td:nth-child(8){
    width: 85px !important;
   }

   thead th:nth-child(9){
    width: 85px !important;
   }

   tr td:nth-child(9){
    width: 95px !important;
   }

   thead th:nth-child(10){
    width: 85px !important;
   }

   tr td:nth-child(10){
    width: 85px !important;
   }

   thead th:nth-child(11){
    width: 85px !important;
   }

   tr td:nth-child(11){
    width: 85px !important;
   }
   thead th:nth-child(12){
    width: 85px !important;
   }

   tr td:nth-child(12){
    width: 120px !important;
   }
   .pagination{
    margin-right: 65px !important;
   }

   #table-responsiveness{
    overflow: hidden !important;
   }
   
}


    </style>

<!-- 
    <style>
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
                max-height: 320px;
                
                
                
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
            th, td {
                text-align: left !important;
                width: 14.28% !important;
            }

            .empid-width{
                width: 20% !important;
            }
        </style> -->


    <div class="attendace-container" id="attendace-container">
        <div class="attendance-title d-flex flex-row justify-content-between">
            <h1>Attendance</h1>
        </div>

        <div class="attendance-input" style="width: 100%; height: 20%;">
            <div class="att-emp-stat-container" style="height: 100%; ">
             <div class="att-emp">
                <?php
                    include('config.php');

                    $sql = "SELECT col_ID, col_deptname FROM dept_tb";
                    $result = mysqli_query($conn, $sql);
                    
                    $Department = isset($_GET['department_name']) ? ($_GET['department_name']) : '';

                    $options = "";
                    $options .= "<option class='select-btn form-select-m' aria-label='.form-select-sm example' value='All Department'" .($Department == 'All Department' ? ' selected' : '').">All Department</option>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($Department == $row['col_ID']) ? 'selected' : '';
                        $options .= "<option value='" . $row['col_ID'] . "' " . $selected . ">" . $row['col_deptname'] . "</option>";
                    }
                    ?>
                        <label for="emp">Select Department
                        <select  class="stat" aria-label=".form-select-sm example" name="department" id="select_department">
                            <option value="" disabled selected>Select Department</option>
                            <?php echo $options; ?>
                        </select>
                        </label>
                    </div>

                    <div class="att-emp" >
                        <label for="emp" style="width: 100%">Select Employee
                        <select  class="stat" aria-label=".form-select-sm example" name="employee" id="select_employee" disabled>
                            <option value="" disabled selected>Select Employee</option>
                        </select>
                        </label>
                    </div>
              
                    <div class="att-stat" style="margin-top: 10px;">
                        <?php
                            $status = isset($_GET['status']) ? $_GET['status'] : '';
                        ?>
                        <label for="Employee" >Status
                        <select name="select_status" id="sel_stats" class="">
                                <option value="">All Status</option>
                                <option value="Present" <?php if ($status == 'Present') echo 'selected'; ?>>Present</option>
                                <option value="Absent" <?php if ($status == 'Absent') echo 'selected'; ?>>Absent</option>
                                <option value="On-Leave" <?php if ($status == 'On-Leave') echo 'selected'; ?>>On-Leave</option>
                                <option value="LWOP" <?php if ($status == 'LWOP') echo 'selected'; ?>>LWOP</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="att-range-container">
                <?php
                        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
                        
                    ?>
                    <div class="att-range">                   
                            <label for="Employee"><span>Date Range</span> 
                            <input type="date" name="date_from" id="startdate" placeholder="Start Date" style="padding:10px; " value="<?php echo $dateFrom; ?>">
                            </label>
                    </div>
                    <input class="att-end" type="date" name="date_to" id="enddate" placeholder="End Date" style="padding:10px; " value="<?php echo $dateTo; ?>">
                </div>
                <button class="btn_go" id="id_btngo" style="height: 50px; width: 80px; margin-top: 20px; margin-left: 10px; background-color: black;" onclick="filterData()">Go</button>
            <!-- <div class="att-excel-input">   
                    <form action="Data Controller/Attendance/attendanceController.php"  enctype="multipart/form-data" method="POST">
                            <input type="file" name="file" />
                            <input type="submit" value="Submit" name="importSubmit" class="btn btn-primary" style="background-color: black;">
                    </form>
                </div> -->
            

        </div>

        <div id="att-listing" class="att-date d-flex flex-row justify-content-between">
            <h1 id="current-date"></h1>
        </div>

                 <?php
                if(isset($_GET['noSchedule'])){
                    echo ' <div class="error-handler w-100 mb-3" style="height: 3em" id="error-message">';
                        echo '<div class="w-100 d-flex flex-row align-items-center justify-content-between pl-4 pr-4" style="height: 100%; background-color: brown;">';
                            echo '<p class="error-message" style="font-size: 0.9em; color: #fff;">No Schedule! </p>';
                            echo '<button style="background-color: inherit; border: none; color: #fff; font-size: 1.1em"onclick="closeErrorMessage()"> <i class="fa-solid fa-x"></i> </button>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>

                <?php
                if(isset($_GET['noEmpid'])){
                    echo ' <div class="error-handler w-100 mb-3" style="height: 3em" id="error-message">';
                        echo '<div class="w-100 d-flex flex-row align-items-center justify-content-between pl-4 pr-4" style="height: 100%; background-color: brown;">';
                            echo '<p class="error-message" style="font-size: 0.9em; color: #fff;">You insert non-existing employee ID. </p>';
                            echo '<button style="background-color: inherit; border: none; color: #fff; font-size: 1.1em"onclick="closeErrorMessage()"> <i class="fa-solid fa-x"></i> </button>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>

                <?php
                if(isset($_GET['wrongDate'])){
                    echo ' <div class="error-handler w-100 mb-3" style="height: 3em; margin:auto;" id="error-message">';
                        echo '<div class="w-100 d-flex flex-row align-items-center justify-content-between pl-4 pr-4" style="height: 100%;  border-radius: 0.5em; background-color: brown;">';
                            echo '<p class="error-message" style="font-size: 0.9em; color: #fff;">You cannot input past or future date in the attendance! </p>';
                            echo '<button style="background-color: inherit; border: none; color: #fff; font-size: 1.1em"onclick="closeErrorMessage()"> <i class="fa-solid fa-x"></i> </button>';
                        echo '</div>';
                    echo '</div>';
                }
                ?>
        
        <div class="table-responsive p-2" id="table-responsiveness">
        <div style="overflow-x: hidden; overflow-y: hidden; width: 98%; margin:auto">
            <table id="order-listing" class="table table-responsive" style="width: 100%;">
                <thead>
                        <th>Status</th>
                        <th class="empid-width">Employee ID</th>
                        <th class="email-col">Name</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Time in</th>
                        <th>Time out</th>
                        <th>Late</th>
                        <th>Undertime</th>
                        <th>Overtime</th>
                        <th>Total Work</th>
                        <th>Total Rest</th>
                        <th>Remarks</th>                  
                </thead>
             <tbody>
             <?php
                date_default_timezone_set('Asia/Manila');
                $currentMonth = date('m');

                $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
                $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
                $department = $_GET['department_name'] ?? '';
                $employee = $_GET['empid'] ?? '';
                $status = isset($_GET['status']) ? $_GET['status'] : '';

                $sql = "SELECT attendances.status, 
                        attendances.empid,
                        attendances.date,
                        attendances.time_in,
                        attendances.time_out,
                        attendances.late,
                        attendances.early_out,
                        attendances.overtime,
                        attendances.total_work,
                        attendances.total_rest, 
                        dept_tb.col_ID,
                        dept_tb.col_deptname,
                        employee_tb.department_name,
                        CONCAT(employee_tb.`fname`, ' ', employee_tb.`lname`) AS `full_name`
                        FROM attendances
                        INNER JOIN employee_tb ON employee_tb.empid = attendances.empid
                        INNER JOIN dept_tb ON employee_tb.department_name = dept_tb.col_ID";

                    // Add filters based on the user inputs
                    if (!empty($department) && $department != 'All Department') {
                        $sql .= " AND dept_tb.col_deptname = '$department'";
                    }
                    
                    if (!empty($employee) && $employee != 'All Employee') {
                        $sql .= " AND employee_tb.empid = '$employee'";
                    }

                    if (!empty($status) && $status != 'All Status') {
                        $sql .= " AND attendances.status = '$status'";
                    }

                    if (!empty($dateFrom) && !empty($dateTo)) {
                        $dateFrom = date('Y-m-d', strtotime($dateFrom));
                        $dateTo = date('Y-m-d', strtotime($dateTo));
                        $sql .= " AND attendances.date BETWEEN '$dateFrom' AND '$dateTo'";
                    } else {
                        $sql .= " AND DATE_FORMAT(attendances.date, '%m') = '$currentMonth'";
                    }

                    $sql .= "ORDER BY CASE WHEN `attendances`.`status` = 'Present' THEN 1
                    WHEN `attendances`.`status` = 'Absent' THEN 2
                    WHEN `attendances`.`status` = 'LWOP' THEN 3
                    WHEN `attendances`.`status` = 'On-Leave' THEN 4
                    WHEN `attendances`.`status` = 'Restday' THEN 5
                    END, `attendances`.`date` DESC";

                    // For debugging, echo the SQL query

                    $result = $conn->query($sql);



                if($result->num_rows > 0){
                
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

                        ?>
                <tr>
                    <td style="font-weight: 400;"><?php echo $row['status']; ?></td>
                    <td class="empid-width" style="font-weight: 400;">
                        <?php
                        $cmpny_code = $cmpny_row['company_code_name'] ?? '';
                        echo $cmpny_code !== '' ? $cmpny_code . ' - ' . $row['empid'] : $row['empid'];
                        ?>
                    </td>
                    <td class="email-col" style="font-weight: 400;"><?php echo $row['full_name']; ?></td>
                    <td style="font-weight: 400;"><?php echo $row['col_deptname']; ?></td>
                    <td style="font-weight: 400;"><?php echo $row['date']; ?></td>
                            <!-------- td  for time out ----------->
                    <td 
                        <?php 
                            if ($row['status'] === 'LWOP'){
                                echo 'style="font-weight: 400; text-align: center;"';
                            }
                            else{
                                if($row['time_in'] === '00:00:00')
                                {
                                    echo 'style="color: #FF5D5E;" ';
                                }
                                else
                                {
                                    echo 'style="font-weight: 400;"';
                                }
                                
                            }
                        ?>
                    > <!--close td -->
                        <?php 
                             echo substr($row['time_in'], 0, 5); // Display only hour:minute
                        ?>
                    </td>
                            <!-------- td  for time out ----------->
                    <td  
                        <?php 

                            if ($row['status'] === 'LWOP'){
                                echo 'style="font-weight: 400; text-align: center;"';
                            }
                            else{
                                if($row['time_out'] === '00:00:00')
                                {
                                    echo 'style="color: #FF5D5E;" ';
                                }
                                else
                                {
                                    echo 'style="font-weight: 400;"';
                                }
                                
                            }
                           
                        ?>
                    > <!--close td -->
                        <?php 
                           echo substr($row['time_out'], 0, 5); // Display only hour:minute
                        ?>
                    </td>
                    
                    <td style="font-weight: 400; color:red;"><?php echo substr($row['late'], 0, 5); ?></td>
                    <td style="font-weight: 400; color: blue"><?php echo substr($row['early_out'], 0, 5); ?></td>
                    <td style="font-weight: 400; color: orange;"><?php echo substr($row['overtime'], 0, 5); ?></td>
                    <td style="font-weight: 400; color:green;"><?php echo substr($row['total_work'], 0, 5); ?></td>
                    <td style="font-weight: 400; color:gray;"><?php echo substr($row['total_rest'], 0, 5); ?></td>

                    <td 
                        <?php 
                        if ($row['status'] === 'LWOP'){
                            echo 'style="font-weight: 400; text-align: center;"';
                        }
                        else{
                            if($row['time_in'] === '00:00:00' || $row['time_out'] === '00:00:00')
                            {
                                echo 'style="color: #FF5D5E;  text-align: center;"';
                            } 
                            else{
                                echo 'style="font-weight: 400; text-align: center;"';
                            }
                            
                        }
                            
                            
                        ?> 
                    > <!--close td -->
                        <?php
                            if($row['status'] === 'LWOP'){
                                echo 'N/A';
                            }else{
                                if($row['time_in'] === '00:00:00')
                                {
                                    echo 'NO TIME IN';
                                }
                            else if($row['time_out'] === '00:00:00')
                                {
                                    echo 'NO TIME OUT';
                                }
                            else
                                {
                                    echo 'N/A';
                                }
                            }
                            
                         ?>
                    </td>
                </tr> 
                <?php        
            }
        } else{
            ?>
            

        <?php
        }
        ?>
    </tbody>
</table>
    </div>
    </div>

    <!-- <table id="order-listing">
        <thead>
            <th>hehe</th>
        </thead>
        <tbody>
            <tr>
                <td>
                    haha
                </td>
            </tr>
        </tbody>
    </table> -->

    
    
        <div class="att-export-btn">
        <form action="att-pdf.php" method="POST" target="_blank">
         <p>Export options: <a href="excel-att.php" class="" style="color:green"></i>Excel</a><span> |</span> <input type="submit" name="pdf_creater" value="PDF" style="border:none; color:red; background-color: inherit;"> </p>
         </form>
        </div>
   
    </div>


    
<!----------------------------Script sa pagfilter ng data table------------------------->
<script>
document.getElementById("select_department").addEventListener("change", function() {
    var departmentID = this.value; 

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var employees = JSON.parse(this.responseText);
            var employeeDropdown = document.getElementById("select_employee");
            employeeDropdown.innerHTML = "";

            if (departmentID == "All Department") {
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
                employees.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.empid;
                    option.text = employee.empid + " - " + employee.fname + " " + employee.lname;
                    employeeDropdown.appendChild(option);
                });
            }
            employeeDropdown.disabled = false;
        }
    };
    xhttp.open("GET", "get_employees.php?departmentID=" + departmentID, true);
    xhttp.send();
});

    function filterData() {
        var department = document.getElementById('select_department').value;
        var employee = document.getElementById('select_employee').value;
        var status = document.getElementById('sel_stats').value;
        var dateFrom = document.getElementById('startdate').value;
        var dateTo = document.getElementById('enddate').value;

        var url = 'attendance.php?col_deptname=' + department + '&empid=' + employee + '&status=' + status + '&date_from=' + dateFrom + '&date_to=' + dateTo;
        window.location.href = url;
    }
</script>
<!----------------------------Script sa pagfilter ng data table------------------------->

<!-- 
    <script>
$(document).ready(function () {
    $("#btnExport").click(function () {
        $.ajax({
            url: "att-pdf.php",
            method: "POST",
            data: {format: 'pdf'},
            success: function(response){
                // Create a blob object from the PDF data returned by the server
                var blob = new Blob([response], {type: 'application/pdf'});
                // Generate a URL for the PDF blob object
                var url = URL.createObjectURL(blob);
                // Create a link element to download the PDF
                var link = document.createElement('a');
                link.href = url;
                link.download = "attendances.pdf";
                // Trigger the click event of the link element to start the download
                link.click();
            },
            error: function(){
                alert('Error generating PDF!');
            }
        });
    });
});
</script> -->


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
 

    <!-- PDF -->
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/pdfmake.min.js"></script>



    
</body>
</html>

