<?php
session_start();
 include '../../config.php';


  if(isset($_POST['cancel_data'])){
   $DTR_id = $_POST["dtr_ID"];

   $reason = $_POST["name_cancel_reason"];
   $employee_ID = $_POST["dtr_empid"];

   $result = mysqli_query($conn, "SELECT * FROM emp_dtr_tb WHERE `id` = '$DTR_id' AND `empid` = '$employee_ID'");
   $row = mysqli_fetch_assoc($result);

    if($row['status'] === 'Approved'){
        header("Location: ../../dtr_emp.php?error=You cannot CANCEL a request that is already APPROVED");
    }
    else if($row['status'] === 'Rejected'){
        header("Location: ../../dtr_emp.php?error=You cannot CANCEL a request that is already REJECTED");
    }
    else if($row['status'] === 'Cancelled'){
        header("Location: ../../dtr_emp.php?error=You cannot CANCEL a request that is already CANCELLED");
    }
    else
    {
            $sql = "UPDATE emp_dtr_tb SET  `status` = 'Cancelled' WHERE `id` = '$DTR_id' AND `empid` = '$employee_ID'";
            $query_run = mysqli_query($conn, $sql);
            if($query_run){
                header("Location: ../../dtr_emp.php?msg=Cancelled Successfully");
            }
            else{
                echo '<script> alert("Data Not Updated"); </script>';
            } 
    }

  }

?>