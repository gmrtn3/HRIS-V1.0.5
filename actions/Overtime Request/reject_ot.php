<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hris_db";


$conn = mysqli_connect($servername, $username,  $password, $dbname);
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/src/Exception.php';
require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/SMTP.php';

if(isset($_POST['name_rejected_ot']))
{

    $reject_column_id = $_POST['reject_id_reject'];

    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Asia/Manila'));
    $currentDateTime = $now->format('Y-m-d H:i:s');

    $reject_marks = $_POST['ot_reject_remarks'];



    $result_ot = mysqli_query($conn, " SELECT * FROM overtime_tb WHERE id = '$reject_column_id'");
    if(mysqli_num_rows($result_ot) > 0) {
        $row_ot = mysqli_fetch_assoc($result_ot);
}
    $employeeid = $row_ot['empid'];
    $date_ot = $row_ot['work_schedule'];
    $starttime = $row_ot['time_in'];
    $endtime = $row_ot['time_out'];
    $overtimereq = $row_ot['ot_hours'];
    $total_overtime = $row_ot['total_ot'];
    $status_ot = $row_ot['status'];
    
    if($status_ot === 'Approved'){
        header("Location: ../../overtime_req.php?error=You cannot REJECT a request that is already APPROVED");
    }
    else if($status_ot === 'Rejected'){
        header("Location: ../../overtime_req.php?error=You cannot REJECT a request that is already REJECTED");
    }else{
        $query = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$reject_marks' WHERE `id`='$reject_column_id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../overtime_req.php?msg=You Rejected this Request");
               //Query sa pagemail ng request
               $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
               $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
               
               $EmpApproverArray = array();
               while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                   $EmployeeApprover = $EmployeeRow['empid'];

                   $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
               }

               foreach ($EmpApproverArray as $EmailOfEmployee) {
               $EmpMail = $EmailOfEmployee['EmployeeApprover'];

               $selectOT = "SELECT * FROM overtime_tb WHERE id = '$reject_column_id' AND empid = '$EmpMail'";  
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
                   $subject = "EMPLOYEE '$empid - $fullname' OVERTIME REQUEST";

                   $message = "
                   <html>
                   <head>
                   <title>{$subject}</title>
                   </head>
                   <body>
                   <p><strong>Dear $to,</strong></p>
                   <p>Your overtime request on $date_ot is rejected</p>
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
         }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    
    }
   
}

?>