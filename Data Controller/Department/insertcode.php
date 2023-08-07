
<?php
     include '../../config.php';
// Connect to the MySQL database

$dept_name = $_POST['name_dept'];


$result_dept = mysqli_query($conn, " SELECT
            *  
        FROM
            dept_tb
        WHERE col_deptname = '$dept_name'");

        if(mysqli_num_rows($result_dept) > 0) {
            $row__dept = mysqli_fetch_assoc($result_dept);
            header("Location: ../../Department.php?error=You cannot add a department name that is already exist");
          } 
          else{

                // Prepare the SQL statement
                $sql = "INSERT INTO dept_tb (`col_deptname`)
                        VALUES (?)";

                // Sanitize the data

                $emp = mysqli_real_escape_string($conn, $_POST['name_dept']);

                // Bind the values to the prepared statement
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 's',$emp);

                // Execute the statement and check for errors
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../../Department.php?msg=Successfully Added");
                
                } else {
                    echo "Error inserting data: " . mysqli_error($conn);
                }
 
                // Close the statement and the connection
                mysqli_stmt_close($stmt);
                mysqli_close($conn);

          }




?>