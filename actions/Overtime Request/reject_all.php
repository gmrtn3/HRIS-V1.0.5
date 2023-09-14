<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hris_db");
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/src/Exception.php';
require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/SMTP.php';

$query = "SELECT * FROM overtime_tb WHERE `status`= 'Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../overtime_req.php?error=No Pending Requests");
  exit();
}

if (isset($_POST['reject_all'])){
    $msg = "";
    $error = false;
  
    $query = "SELECT * FROM overtime_tb WHERE `status` = 'Pending'";
    $result_pending = mysqli_query($conn, $query);
  
    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Asia/Manila'));
    $currentDateTime = $now->format('Y-m-d H:i:s');

    $ot_reject_marks = $_POST['ot_reject_marks'];


    if(mysqli_num_rows($result_pending) > 0){
        while($ot_row = mysqli_fetch_assoc($result_pending)){
          $overtime_id = $ot_row['id'];
          $employeeid = $ot_row['empid'];
          $date_ot = $ot_row['work_schedule'];
          $starttime = $ot_row['time_in'];
          $endtime = $ot_row['time_out'];
          $overtimereq = $ot_row['ot_hours'];
          $total_overtime = $ot_row['total_ot'];
          $status_ot = $ot_row['status'];
    
          if($status_ot === 'Pending'){    
                $day_of_week = date('l', strtotime($date_ot)); // get the day of the week using the "l" format specifier
                
                        if ($day_of_week === 'Monday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Monday Close Bracket

                        else if ($day_of_week === 'Tuesday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Tuesday Close Bracket 

                        else if ($day_of_week === 'Wednesday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Wednesday Close Bracket 

                        else if ($day_of_week === 'Thursday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Thursday Close Bracket 

                        else if ($day_of_week === 'Friday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Friday Close Bracket 

                        else if ($day_of_week === 'Saturday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Saturday Close Bracket 

                        else if ($day_of_week === 'Sunday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Sunday Close Bracket 

                        else {
                          $error = true;
                          $msg .= "Invalid day of the week for employee ID: $employeeid<br>";
                        }

                        if ($error) {
                          header("Location: ../../overtime_req.php?error=$msg");
                        } else {
                          header("Location: ../../overtime_req.php?msg=$msg");
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

                          $selectOT = "SELECT * FROM overtime_tb WHERE id = '$overtime_id' AND empid = '$EmpMail'";  
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

            if ($error) {
              header("Location: ../../overtime_req.php?error=$msg");
            } else {
              header("Location: ../../overtime_req.php?msg=$msg");
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

                          $selectOT = "SELECT * FROM overtime_tb WHERE id = '$overtime_id' AND empid = '$EmpMail'";  
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

          }
        }
    }

}



?>