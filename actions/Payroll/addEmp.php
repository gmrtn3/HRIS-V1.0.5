<?php
    if(isset($_POST['btn_addEmp_modal'])){
        $cutOff_emp = $_POST['cuttOff_emp']; // Array of selected employee IDs
        $cutOffID = $_POST['name_AddEMp_CutoffID'];

        include '../../config.php';

        // Iterate over each selected employee ID
        foreach($cutOff_emp as $empID) {
            $result_dept = mysqli_query($conn, "SELECT * FROM `empschedule_tb` WHERE `empid` = $empID");

            if(mysqli_num_rows($result_dept) <= 0) {
                header("Location: ../../cutoff.php?error=You cannot add an employee that has no schedule");
                exit(); // Terminate further execution if an employee has no schedule
            }
        }

        // Construct the SQL query using prepared statements
        $sql = "INSERT INTO empcutoff_tb (cutOff_ID, emp_ID) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters and execute the statement for each selected employee ID
        foreach($cutOff_emp as $empID) {
            mysqli_stmt_bind_param($stmt, "ss", $cutOffID, $empID);
            mysqli_stmt_execute($stmt);
        }

        // Check if the insertion was successful
        if(mysqli_stmt_affected_rows($stmt) > 0){
            header("Location: ../../cutoff.php?msg=Successfully Added");
            exit();
        } else {
            echo "Error";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
?>
