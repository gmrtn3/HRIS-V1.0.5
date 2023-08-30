<?php
include '../../config.php';

if (isset($_POST['btn_save'])) {
    // Retrieve the selected empId values


        $empids = $_POST['name_empId'][0];

        $empIDs = explode(",", $empids);

        $type = $_POST['name_type'];
        $frequency = $_POST['name_frequency'];
        $month = $_POST['name_Month'];
        $year = $_POST['name_year'];
        $strDate = $_POST['name_strDate'];
        $endDate = $_POST['name_endDate'];
        $Cut_num = $_POST['name_cutoffNum'];

        

        $selectedEmpIDs = array(); // Array to store selected employee IDs

        foreach ($empIDs as $empID) {
            // Check if employee has attendances within the date range
            $checkAttendances = "SELECT * FROM attendances WHERE `empid` = '$empID' AND `date` BETWEEN '$strDate' AND '$endDate'";
            $attendancesRun = mysqli_query($conn, $checkAttendances);
        
            if (mysqli_num_rows($attendancesRun) > 0) {
                $hasPresentStatus = false;
        
                while ($attendanceRow = mysqli_fetch_assoc($attendancesRun)) {
                    if ($attendanceRow['status'] === 'Present') {
                        $hasPresentStatus = true;
                        break;
                    }
                }
        
                if ($hasPresentStatus) {
                    $selectedEmpIDs[] = $empID; // Store the selected employee ID
                } else {
                    header("Location: ../../cutoff.php?error=No 'Present' attendance found for employee on $strDate to $endDate");
                    exit();
                }

                if (!empty($selectedEmpIDs)) {
                // Check if cutoff already exists (moved this outside the loop)
                        $CheckcutOff = "SELECT * FROM cutoff_tb WHERE col_type = '$type' AND col_month = '$month' AND `col_year` = '$year' AND `col_cutOffNum` = '$Cut_num' AND ('$strDate' BETWEEN `col_startDate` AND `col_endDate` OR '$endDate' BETWEEN `col_startDate` AND `col_endDate`)";
                        $cutoffRun = mysqli_query($conn, $CheckcutOff);

                        if (mysqli_num_rows($cutoffRun) == 0) {
                            $sql = "INSERT INTO cutoff_tb (`col_type`, `col_frequency`, `col_month`, `col_year`, `col_startDate`, `col_endDate`, `col_cutOffNum`)
                                    VALUES ('$type', '$frequency', '$month', '$year', '$strDate', '$endDate', '$Cut_num')";
                            $sqlrun = mysqli_query($conn, $sql);

                            if ($sqlrun) {
                                $cutoff = "SELECT max(col_ID) AS cutoffID FROM cutoff_tb";
                                $cutoffRun = mysqli_query($conn, $cutoff);

                                if (mysqli_num_rows($cutoffRun) > 0) {
                                    $row = mysqli_fetch_assoc($cutoffRun);
                                    $cutID = $row['cutoffID'];

                                    foreach ($selectedEmpIDs as $empID) {
                                        $query = "INSERT INTO empcutoff_tb (`cutOff_ID`, `emp_ID`) VALUES ('$cutID', '$empID')";
                                        $queryrun = mysqli_query($conn, $query);
                                    }

                                    header("Location: ../../cutoff.php?msg=Cutoff for $strDate to $endDate is successfully");
                                    exit();
                                }
                            } else {
                                echo "Failed: " . mysqli_error($conn);
                                exit();
                            }
                    } else {
                        header("Location: ../../cutoff.php?msg=You cannot create a cutoff for $strDate to $endDate that already exists");
                        exit();
                    }
                } else {
                    header("Location: ../../cutoff.php?error=No attendance data found for selected employees");
                    exit();
                }
                
            } else {
                header("Location: ../../cutoff.php?error=No attendance found for employee on $strDate to $endDate");
                exit();
            }
        }
    



    
    // if (!empty($selectedEmpIDs)) {
    //     // Check if cutoff already exists (moved this outside the loop)
    //     $CheckcutOff = "SELECT * FROM cutoff_tb WHERE col_type = '$type' AND col_month = '$month' AND `col_year` = '$year' AND `col_cutOffNum` = '$Cut_num' AND ('$strDate' BETWEEN `col_startDate` AND `col_endDate` OR '$endDate' BETWEEN `col_startDate` AND `col_endDate`)";
    //     $cutoffRun = mysqli_query($conn, $CheckcutOff);

    //     if (mysqli_num_rows($cutoffRun) == 0) {
    //         $sql = "INSERT INTO cutoff_tb (`col_type`, `col_frequency`, `col_month`, `col_year`, `col_startDate`, `col_endDate`, `col_cutOffNum`)
    //                 VALUES ('$type', '$frequency', '$month', '$year', '$strDate', '$endDate', '$Cut_num')";
    //         $sqlrun = mysqli_query($conn, $sql);

    //         if ($sqlrun) {
    //             $cutoff = "SELECT max(col_ID) AS cutoffID FROM cutoff_tb";
    //             $cutoffRun = mysqli_query($conn, $cutoff);

    //             if (mysqli_num_rows($cutoffRun) > 0) {
    //                 $row = mysqli_fetch_assoc($cutoffRun);
    //                 $cutID = $row['cutoffID'];

    //                 foreach ($selectedEmpIDs as $empID) {
    //                     $query = "INSERT INTO empcutoff_tb (`cutOff_ID`, `emp_ID`) VALUES ('$cutID', '$empID')";
    //                     $queryrun = mysqli_query($conn, $query);
    //                 }

    //                 header("Location: ../../cutoff.php?msg=Cutoff for $strDate to $endDate is successfully");
    //                 exit();
    //             }
    //         } else {
    //             echo "Failed: " . mysqli_error($conn);
    //             exit();
    //         }
    //     } else {
    //         header("Location: ../../cutoff.php?msg=You cannot create a cutoff for $strDate to $endDate that already exists");
    //         exit();
    //     }
    // } else {
    //     header("Location: ../../cutoff.php?error=No attendance data found for selected employees");
    //     exit();
    // }
}

?>
