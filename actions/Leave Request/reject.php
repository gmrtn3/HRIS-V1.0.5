<?php
session_start();
include '../../config.php';
  //----------------------------------------------BREAK(FOR REJECTING)-----------------------------------------------------
  if(isset($_POST['name_rejected'])){
    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
//Para sa pag select ng mga data galing sa APPLYLEAVE TABLE
    $result = mysqli_query($conn, " SELECT
                                        *  
                                    FROM
                                        applyleave_tb
                                    WHERE col_ID=  $IDLEAVE_TABLE");
    $row = mysqli_fetch_assoc($result);
    //echo $row['IDLEAVE_TABLE'];
//Para sa pag select ng mga data galing sa APPLYLEAVE TABLE (END)

if($row['col_status'] === 'Approved' ){
header("Location: ../../leavereq.php?error=You cannot REJECT a request that is already APPROVED");
}
else if($row['col_status'] === 'Rejected'){
header("Location: ../../leavereq.php?error=You cannot REJECT a request that is already REJECTED");
}
else if($row['col_status'] === 'Cancelled'){
    header("Location: ../../leavereq.php?error=You cannot REJECT a request that is already CANCELLED");
    }
else{
    $reason = $_POST["name_rjectResn"];
    //$employee_ID = $_SESSION["ID_empId"];
    $employee_ID = $row['col_req_emp'];
    $approver = $_SESSION["empid"];

    //para sa pag update from pending to approved and action time
      // Get the current date and time
      $now = new DateTime();
      $now->setTimezone(new DateTimeZone('Asia/Manila'));
      $currentDateTime1 = $now->format('Y-m-d H:i:s');

      //get the session for ID in applyleave selected employee
      $Status = $_SESSION["col_status"];
      $Applyleave_ID = $_SESSION["ID_applyleave"];


      $sql1 = "INSERT into actiontaken_tb(`col_applyID`, `col_remarks`,`col_status`) 
            VALUES('$Applyleave_ID','$reason', 'Rejected')";
        if(mysqli_query($conn,$sql1))
        {
            $sql ="UPDATE applyleave_tb SET col_status= 'Rejected', col_dt_action= '$currentDateTime1', col_approver = '$approver' WHERE col_ID = $Applyleave_ID";
            $query_run = mysqli_query($conn, $sql);
            if($query_run){
                header("Location: ../../leavereq.php?msg=Rejected Successfully");
            }
            else{
                echo '<script> alert("Data Not Updated"); </script>';
            } 
        } 
        else
            {
                echo '<script> alert("Data Not Updated"); </script>';
            }
     
}


}
?>