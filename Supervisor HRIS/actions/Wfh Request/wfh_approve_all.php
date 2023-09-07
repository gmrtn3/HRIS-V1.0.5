<?php
session_start();
include '../../config.php';
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';

$query = "SELECT * FROM wfh_tb WHERE `status` = 'Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../wfh_request.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All
if (isset($_POST['approve_all_btn'])){
  $msg = "";
  $error = false;

  $query = "SELECT * FROM wfh_tb WHERE `status` = 'Pending'";
  $result_pending = mysqli_query($conn, $query);

  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Manila'));
  $currentDateTime = $now->format('Y-m-d H:i:s');

  $wfh_approver_marks = $_POST['wfh_approve_marks'];

  if(mysqli_num_rows($result_pending) > 0){
    while($wfh_row = mysqli_fetch_assoc($result_pending)){
      $wfh_id = $wfh_row['id'];
      $employeeid = $wfh_row['empid'];
      $date_wfh = $wfh_row['date'];
      $starttime = $wfh_row['start_time'];
      $endtime = $wfh_row['end_time'];
      $status_wfh = $wfh_row['status'];

      if($status_wfh === 'Pending'){
                $day_of_week = date('l', strtotime($date_wfh)); // get the day of the week using the "l" format specifier
                
                        if ($day_of_week === 'Monday') {
                            $UpdateWFH = "UPDATE wfh_tb SET `status` = 'Approved', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$wfh_id' AND `status` = 'Pending'";
                            $RunWFH = mysqli_query($conn, $UpdateWFH);
                            if ($RunWFH){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_wfh<br>";
                            }else{
                              $error = true;
                              $msg .= "Failed to update approve the employee ID: $employeeid on $date_wfh<br>";
                            }
                        } //Monday Close Bracket

                        else if ($day_of_week === 'Tuesday') {
                            $UpdateWFH = "UPDATE wfh_tb SET `status` = 'Approved', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$wfh_id' AND `status` = 'Pending'";
                            $RunWFH = mysqli_query($conn, $UpdateWFH);
                            if ($RunWFH){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_wfh<br>";
                            }else{
                              $error = true;
                              $msg .= "Failed to update approve the employee ID: $employeeid on $date_wfh<br>";
                            }
                        } //Tuesday Close Bracket 

                        else if ($day_of_week === 'Wednesday') {
                            $UpdateWFH = "UPDATE wfh_tb SET `status` = 'Approved', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$wfh_id' AND `status` = 'Pending'";
                            $RunWFH = mysqli_query($conn, $UpdateWFH);
                            if ($RunWFH){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_wfh<br>";
                            }else{
                              $error = true;
                              $msg .= "Failed to update approve the employee ID: $employeeid on $date_wfh<br>";
                            }
                        } //Wednesday Close Bracket 

                        else if ($day_of_week === 'Thursday') {
                            $UpdateWFH = "UPDATE wfh_tb SET `status` = 'Approved', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$wfh_id' AND `status` = 'Pending'";
                            $RunWFH = mysqli_query($conn, $UpdateWFH);
                            if ($RunWFH){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_wfh<br>";
                            }else{
                              $error = true;
                              $msg .= "Failed to update approve the employee ID: $employeeid on $date_wfh<br>";
                            }
                        } //Thursday Close Bracket 

                        else if ($day_of_week === 'Friday') {
                            $UpdateWFH = "UPDATE wfh_tb SET `status` = 'Approved', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$wfh_id' AND `status` = 'Pending'";
                            $RunWFH = mysqli_query($conn, $UpdateWFH);
                            if ($RunWFH){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_wfh<br>";
                            }else{
                              $error = true;
                              $msg .= "Failed to update approve the employee ID: $employeeid on $date_wfh<br>";
                            }
                        } //Friday Close Bracket 

                        else if ($day_of_week === 'Saturday') {
                            $UpdateWFH = "UPDATE wfh_tb SET `status` = 'Approved', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$wfh_id' AND `status` = 'Pending'";
                            $RunWFH = mysqli_query($conn, $UpdateWFH);
                            if ($RunWFH){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_wfh<br>";
                            }else{
                              $error = true;
                              $msg .= "Failed to update approve the employee ID: $employeeid on $date_wfh<br>";
                            }
                        } //Saturday Close Bracket 

                        else if ($day_of_week === 'Sunday') {
                            $UpdateWFH = "UPDATE wfh_tb SET `status` = 'Approved', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_approver_marks' WHERE `empid` = '$employeeid' AND `id` = '$wfh_id' AND `status` = 'Pending'";
                            $RunWFH = mysqli_query($conn, $UpdateWFH);
                            if ($RunWFH){
                              $msg .= "You Approved the Request Successfully for employee ID: $employeeid on $date_wfh<br>";
                            }else{
                              $error = true;
                              $msg .= "Failed to update approve the employee ID: $employeeid on $date_wfh<br>";
                            }
                        } //Sunday Close Bracket 

                        else {
                          $error = true;
                          $msg .= "Invalid day of the week for employee ID: $employeeid<br>";
                        }

                        if ($error) {
                          header("Location: ../../wfh_request.php?error=$msg");
                        } else {
                          header("Location: ../../wfh_request.php?msg=$msg");
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

                          $selectOT = "SELECT * FROM wfh_tb WHERE id = '$wfh_id' AND empid = '$EmpMail'";  
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
                              $subject = "EMPLOYEE '$empid - $fullname' WFH REQUEST";

                              $message = "
                              <html>
                              <head>
                              <title>{$subject}</title>
                              </head>
                              <body>
                              <p><strong>Dear $to,</strong></p>
                              <p>Your wfh request on $date_wfh is approved</p>
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
              header("Location: ../../wfh_request.php?error=$msg");
            } else {
              header("Location: ../../wfh_request.php?msg=$msg");
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

                $selectOT = "SELECT * FROM wfh_tb WHERE id = '$wfh_id' AND empid = '$EmpMail'";  
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
                    $subject = "EMPLOYEE '$empid - $fullname' WFH REQUEST";

                    $message = "
                    <html>
                    <head>
                    <title>{$subject}</title>
                    </head>
                    <body>
                    <p><strong>Dear $to,</strong></p>
                    <p>Your wfh request on $date_wfh is approved</p>
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
          }//Pending

    }
  }
}//Approve All button
?>