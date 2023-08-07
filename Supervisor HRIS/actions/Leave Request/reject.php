<?php
session_start();
include '../../config.php';
  //----------------------------------------------BREAK(FOR REJECTING)-----------------------------------------------------
  if(isset($_POST['name_rejected'])){
    $IDLEAVE_TABLE = $_SESSION["ID_applyleave"];
//Para sa pag select ng mga data galing sa APPLYLEAVE TABLE
    $result = mysqli_query($conn, "SELECT * FROM applyleave_tb WHERE col_ID=  $IDLEAVE_TABLE");
    $row = mysqli_fetch_assoc($result);

    $str_date = $row['col_strDate'];
    $end_date = $row['col_endDate'];
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
    $employee_ID = $_SESSION["ID_empId"];
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
            $sql ="UPDATE applyleave_tb SET  col_status= 'Rejected', col_dt_action= '$currentDateTime1', col_approver = '$approver' WHERE col_ID = $Applyleave_ID";
            $query_run = mysqli_query($conn, $sql);
            if($query_run){
                header("Location: ../../leavereq.php?msg=Rejected Successfully");
                    //Syntax sa email notification
                    $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                    $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                    
                    $EmpApproverArray = array();
                    while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                        $EmployeeApprover = $EmployeeRow['empid'];

                        $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                    }

                    foreach ($EmpApproverArray as $EmailOfEmployee) {
                    $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                    $selectOT = "SELECT * FROM applyleave_tb WHERE `col_ID` = '$IDLEAVE_TABLE' AND empid = '$EmpMail'";  
                    $approvedOTRun = mysqli_query($conn, $selectOT);

                    $ApprovedArray = array();
                    while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                        $employeeApproved = $ApprovedRow['empid'];

                        $ApprovedArray[] = array('employeeApproved' => $employeeApproved);
                    }

                    foreach ($ApprovedArray as $ApprovedEmail) {
                        $EmpApprovedEmail = $ApprovedEmail['employeeApproved'];

                        $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$EmpApprovedEmail'";
                        $employeeRun = mysqli_query($conn, $employeeQuery);

                        $EmployeeEmail = mysqli_fetch_assoc($employeeRun);

                        $empid = $EmployeeEmail['empid'];
                        $fullname = $EmployeeEmail['fname'] . ' ' . $EmployeeEmail['lname'];


                        $to = $EmployeeEmail['email'];
                        $subject = "EMPLOYEE '$empid - $fullname' LEAVE REQUEST";

                        $message = "
                        <html>
                        <head>
                        <title>{$subject}</title>
                        </head>
                        <body>
                        <p><strong>Dear $to,</strong></p>
                        <p>Your leave request on $str_date to $end_date is rejected</p>
                        </body>
                        </html>
                        ";
                        $mail = new PHPMailer(true);
            
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'hris.payroll.mailer@gmail.com'; //gmail name
                        $mail->Password = 'ndehozbugmfnhmes'; // app password
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;
                    
                        $mail->setFrom('hris.payroll.mailer@gmail.com'); //gmail name
                    
                        $mail->addAddress($to);
                    
                        $mail->isHTML(true);
                    
                        $imgData = file_get_contents('../../img/Slash Tech Solutions.png');
                        $imgData64 = base64_encode($imgData);
                        $cid = md5(uniqid(time()));
                        $imgSrc = 'data:image/png;base64,' . $imgData64;
                        $mail->addEmbeddedImage('../../img/Slash Tech Solutions.png', $cid, 'Slash Tech Solutions.png');
                    
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = $subject;
                        $mail->Body    = $message;
                    
                        $mail->send();
                    }
                }                  
            }else{
                echo '<script> alert("Data Not Updated"); </script>';
            } 
        }else{
                echo '<script> alert("Data Not Updated"); </script>';
            }
     
      }
}
?>