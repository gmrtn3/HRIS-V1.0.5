<?php
    if(isset($_POST['btn_addEmp_modal'])){
        $cutEmp = $_POST['cuttOff_emp'][0]; // Array of selected employee IDs

        $cutOff_emp = explode(",", $cutEmp);

        $cutOffID = $_POST['name_AddEMp_CutoffID'];
    
        include '../../config.php';
    
        $ExistingCutoff = "SELECT * FROM cutoff_tb WHERE `col_ID` = '$cutOffID'";
        $CutoffRun = mysqli_query($conn, $ExistingCutoff);
        $row = mysqli_fetch_assoc($CutoffRun);
        $startDate = $row['col_startDate'];
        $endDate = $row['col_endDate'];
    
        $employeesToAdd = []; // Array to store employee IDs with attendance data
    
        // Iterate over each selected employee ID
        foreach($cutOff_emp as $empID) {
            // // Check if the employee has a schedule
            // $result_dept = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid` = $empID");
            // if(mysqli_num_rows($result_dept) <= 0) {
            //     header("Location: ../../cutoff.php?error=You cannot add an employee that has no schedule");
                
            //     exit();
            // }
    
            // Check if the employee is already in the empcutoff_tb
            $CheckExisting = "SELECT * FROM empcutoff_tb WHERE `cutOff_ID` = '$cutOffID' AND `emp_ID` = '$empID'";
            $RunExist = mysqli_query($conn, $CheckExisting);
    
            if(mysqli_num_rows($RunExist) > 0){
                header("Location: ../../cutoff.php?error=This employee has already been added to the cutoff");
                echo "wala nga";
                exit();
            }
    
            // Check if the employee has attendance data in the specified range
            $AttendanceExist = "SELECT * FROM attendances WHERE `empid` = '$empID' AND `date` BETWEEN '$startDate' AND '$endDate'";
            $Runattendance = mysqli_query($conn, $AttendanceExist);
    
            if(mysqli_num_rows($Runattendance) > 0){
                $hasPresentStatus = false;
                

                while ($attendanceRow = mysqli_fetch_assoc($Runattendance)) {
                    if ($attendanceRow['status'] === 'Present') {
                        $hasPresentStatus = true;
                        break;
                    }
                }

                if ($hasPresentStatus) {
                    $employeesToAdd[] = $empID; // Store the selected employee ID
                } else {
                    header("Location: ../../cutoff.php?error=No 'Present' attendance found for employee on $strDate to $endDate");
                    exit();
                }

            }
        }
    
        // Insert employees with attendance data into empcutoff_tb
        if (!empty($employeesToAdd)) {
            $values = [];
            foreach ($employeesToAdd as $empID) {
                $values[] = "('$cutOffID', '$empID')";
            }
            
            $valuesStr = implode(",", $values);
            $query = "INSERT INTO empcutoff_tb (`cutOff_ID`, `emp_ID`) VALUES $valuesStr";
            $query_run = mysqli_query($conn, $query);
    
            if ($query_run) {
                header("Location: ../../cutoff.php?msg=Employees added for cutoff $startDate to $endDate successfully");
                exit();
            } else {
                header("Location: ../../cutoff.php?error=Error adding employees to empcutoff_tb");
                exit();
            }
        } else {
            header("Location: ../../cutoff.php?error=No employees with attendance data found for the specified period");
            exit();
        }
    }
    
    
?>
