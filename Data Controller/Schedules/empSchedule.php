<?php 

if(isset($_POST['submit'])){
    $empidArray = $_POST['empid'][0];

    $empids = explode(",", $empidArray);

    $schedule_name = $_POST['schedule_name'];
    $sched_from = $_POST['sched_from'];
    $sched_to = $_POST['sched_to'];
    
    include '../../config.php';

    $sql = "INSERT INTO empschedule_tb (empid, schedule_name, sched_from, sched_to)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
    schedule_name = VALUES(schedule_name),
    sched_from = VALUES(sched_from),
    sched_to = VALUES(sched_to)";

    $stmt = mysqli_prepare($conn, $sql);

    foreach($empids as $empID ){

        $empID = trim($empID);

        echo $empID;
        // Check if the employee ID is not empty
        if (!empty($empID)) {
            // Convert the employee ID to a string to preserve leading zeroes
            $empID = strval($empID);

            // Bind parameters and execute the statement for the current employee ID
            mysqli_stmt_bind_param($stmt, "ssss", $empID, $schedule_name, $sched_from, $sched_to); // Assuming cutOff_ID is an integer
            mysqli_stmt_execute($stmt);
        }
    }
    mysqli_stmt_close($stmt);

    // Check if there were any successful insertions
    // Assuming success if there were no errors and no exit() calls
    header("Location: ../../Schedules");
    exit();

    mysqli_close($conn);
     
}