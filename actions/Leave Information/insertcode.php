<?php 

if (isset($_POST['save_changes'])) {
    $empidArray = $_POST['empid'][0];
    $empids = explode(",", $empidArray);
    $vacationLeave = $_POST['name_vctn_lve'];
    $sickLeave = $_POST['name_sick_lve'];
    $bereavementLeave = $_POST['name_brvmnt_lve'];
    
    include '../../config.php';

    $sql = "INSERT INTO leaveinfo_tb (col_empID, col_vctionCrdt, col_sickCrdt, col_brvmntCrdt)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
    col_vctionCrdt = VALUES(col_vctionCrdt),
    col_sickCrdt = VALUES(col_sickCrdt),
    col_brvmntCrdt = VALUES(col_brvmntCrdt)";

    $stmt = mysqli_prepare($conn, $sql);

    foreach ($empids as $empID) {
        $empID = trim($empID);
        if (!empty($empID)) {
            $empID = strval($empID);
            
            // Check if data already exists for this employee
            $check_sql = "SELECT col_empID FROM leaveinfo_tb WHERE col_empID = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $empID);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) == 0) {
                // No existing data, insert new data
                mysqli_stmt_close($check_stmt);
                mysqli_stmt_bind_param($stmt, "ssss", $empID, $vacationLeave, $sickLeave, $bereavementLeave);
                mysqli_stmt_execute($stmt);
            } else {
                // Data already exists, skip insertion
                mysqli_stmt_close($check_stmt);
            }
        }
    }
    
    mysqli_stmt_close($stmt);
    header("Location: ../../leaveInfo");
    exit();
    
    mysqli_close($conn);
}



// // Connect to the MySQL database

// $employeeID = $_POST['name_emp'];
// $result_leaveINFO = mysqli_query($conn, "SELECT * FROM leaveinfo_tb WHERE col_empID = $employeeID");
//         if(mysqli_num_rows($result_leaveINFO) > 0) {
//             $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
//                 header("Location: ../../leaveInfo.php?error=You cannot add credits that already had!!");
//           } else {
            
//                 // Prepare the SQL statement
//                 $sql = "INSERT INTO leaveinfo_tb (`col_empID`,`col_vctionCrdt`, `col_sickCrdt`, `col_brvmntCrdt`)
//                 VALUES (?, ?, ?, ?)";
 
//                 // Sanitize the data

//                 $emp = mysqli_real_escape_string($conn, $_POST['name_emp']);
//                 $vacation_leave = mysqli_real_escape_string($conn, $_POST['name_vctn_lve']);
//                 $vacation_leave1 = mysqli_real_escape_string($conn, $_POST['name_vctn_lve1']);

//                 $vacation_leave_final = $vacation_leave . $vacation_leave1;


//                 $sick_leave = mysqli_real_escape_string($conn, $_POST['name_sick_lve']);
//                 $sick_leave1 = mysqli_real_escape_string($conn, $_POST['name_sick_lve1']);

//                 $sick_leave_final = $sick_leave . $sick_leave1;


//                 $bereavement_leave = mysqli_real_escape_string($conn, $_POST['name_brvmnt_lve']);
//                 $bereavement_leave1 = mysqli_real_escape_string($conn, $_POST['name_brvmnt_lve1']);

//                 $bereavement_leave_final = $bereavement_leave . $bereavement_leave1;

//                 // Bind the values to the prepared statement
//                 $stmt = mysqli_prepare($conn, $sql);
//                 mysqli_stmt_bind_param($stmt, 'sddd',$emp , $vacation_leave_final, $sick_leave_final, $bereavement_leave_final);

//                 // Execute the statement and check for errors
//                 if (mysqli_stmt_execute($stmt)) {
//                     header("Location: ../../leaveInfo.php?msg=Successfully Added");
//                 } else {
//                     echo "Error inserting data: " . mysqli_error($conn);
//                 }

//                 // Close the statement and the connection
//                 mysqli_stmt_close($stmt);
//                 mysqli_close($conn);
//           }



?>