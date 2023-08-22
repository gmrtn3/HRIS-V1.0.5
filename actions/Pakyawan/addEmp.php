<?php
    if(isset($_POST['btn_addEmp_modal'])){
        $pakyawan_empid = $_POST['pakyawan_empid']; // Array of selected employee IDs
        $add_pakyawan_empid = $_POST['add_pakyawan_empid'];

        include '../../config.php';
      
        // Construct the SQL query using prepared statements
        $sql = "INSERT INTO pakyawan_payroll_tb (cutoff_id, pakyawan_empid) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Bind parameters and execute the statement for each selected employee ID
        foreach($pakyawan_empid as $empID) {
            mysqli_stmt_bind_param($stmt, "ss", $add_pakyawan_empid, $empID);
            mysqli_stmt_execute($stmt);
        }

        // Check if the insertion was successful
        if(mysqli_stmt_affected_rows($stmt) > 0){
            header("Location: ../../pakyawan_payroll?msg=Successfully Added");
            exit();
        } else {
            echo "Error";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
?>
