
<?php
session_start();
     include '../../config.php';
// Connect to the MySQL database

$employeeID = $_POST['name_emp'];
$result_leaveINFO = mysqli_query($conn, " SELECT
            *  
        FROM
            leaveinfo_tb
        WHERE col_empID = $employeeID");
        if(mysqli_num_rows($result_leaveINFO) > 0) {
            $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                header("Location: ../../leaveInfo.php?error=You cannot add credits that already had!!");
          } else {
            
                // Prepare the SQL statement
                $sql = "INSERT INTO leaveinfo_tb (`col_empID`,`col_vctionCrdt`, `col_sickCrdt`, `col_brvmntCrdt`)
                VALUES (?, ?, ?, ?)";
 
                // Sanitize the data

                $emp = mysqli_real_escape_string($conn, $_POST['name_emp']);
                $vacation_leave = mysqli_real_escape_string($conn, $_POST['name_vctn_lve']);
                $vacation_leave1 = mysqli_real_escape_string($conn, $_POST['name_vctn_lve1']);

                $vacation_leave_final = $vacation_leave . $vacation_leave1;


                $sick_leave = mysqli_real_escape_string($conn, $_POST['name_sick_lve']);
                $sick_leave1 = mysqli_real_escape_string($conn, $_POST['name_sick_lve1']);

                $sick_leave_final = $sick_leave . $sick_leave1;


                $bereavement_leave = mysqli_real_escape_string($conn, $_POST['name_brvmnt_lve']);
                $bereavement_leave1 = mysqli_real_escape_string($conn, $_POST['name_brvmnt_lve1']);

                $bereavement_leave_final = $bereavement_leave . $bereavement_leave1;

                // Bind the values to the prepared statement
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'sddd',$emp , $vacation_leave_final, $sick_leave_final, $bereavement_leave_final);

                // Execute the statement and check for errors
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../../leaveInfo.php?msg=Successfully Added");
                } else {
                    echo "Error inserting data: " . mysqli_error($conn);
                }

                // Close the statement and the connection
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
          }



?>