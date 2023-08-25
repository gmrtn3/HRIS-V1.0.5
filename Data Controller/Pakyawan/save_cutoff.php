<?php
include '../../config.php';

if(isset($_POST['btn_save'])){
    // Retrieve the selected empId values
    $empIDs = $_POST['pakyawan_empid'];

    // Convert the array into a comma-separated string
    $empIDsString = implode(',', $empIDs);

    // Rest of your code...
    $work_type = $_POST['work_type'];
    $work_frequency = $_POST['work_frequency'];
    $work_month = $_POST['work_month'];
    $year = $_POST['year'];
    
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

   
    $result_cutoff = mysqli_query($conn, 
    "SELECT *  FROM pakyawan_cutoff_tb WHERE work_type = '$work_type' AND work_month = '$work_month' AND `year` = $year AND ('$start_date' BETWEEN `start_date` AND `end_date` OR '$end_date' BETWEEN `start_date` AND `end_date`)") ;
        if(mysqli_num_rows($result_cutoff) > 0) 
            {
                $row_cutoff = mysqli_fetch_assoc($result_cutoff);
                header("Location: ../../pakyawan_payroll?error= You cannot add a cutoff that is already exist");
            }
        else
            {
                
                // Prepare the SQL statement
                $sql = "INSERT INTO pakyawan_cutoff_tb (`work_type`, work_frequency, `work_month`, `year` ,`start_date`, `end_date`)
                VALUES (?, ?, ?, ?, ?, ?)";

            // Sanitize the data
            $int_workType = mysqli_real_escape_string($conn, $work_type);
            $int_workFrequency = mysqli_real_escape_string($conn, $work_frequency);
            $int_workMonth = mysqli_real_escape_string($conn, $work_month);
            $int_year = mysqli_real_escape_string($conn, $year);
            
            $int_startDate = mysqli_real_escape_string($conn, $start_date);
            $end_endDate = mysqli_real_escape_string($conn, $end_date);

            // Bind the values to the prepared statement
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssssss', $int_workType, $int_workFrequency, $int_workMonth, $int_year, $int_startDate, $end_endDate);

            // Execute the statement and check for errors
            if (mysqli_stmt_execute($stmt)) {
            // Retrieve the last inserted ID
            $lastInsertID = mysqli_insert_id($conn);

            // Prepare the SQL statement for inserting into empcutoff_tb
            $sql = "INSERT INTO pakyawan_payroll_tb (`cutoff_id`, `pakyawan_empid`) VALUES (?, ?)";

            // Bind the values to the prepared statement
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $lastInsertID, $EmpID);

            // Insert each selected empId into empcutoff_tb
            foreach ($empIDs as $EmpID) {
                // Sanitize the data
                $EmpID = mysqli_real_escape_string($conn, $EmpID);

                // Execute the statement and check for errors
                if (!mysqli_stmt_execute($stmt)) {
                    echo "Error inserting data: " . mysqli_error($conn);
                }
            }

            // Close the statement and the connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: ../../pakyawan_payroll?msg=Successfully Added");
            exit();
            } else {
            echo "Error inserting data: " . mysqli_error($conn);
            }
            }

}


?>
