<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hris_db");
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/src/Exception.php';
require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/SMTP.php';

$query = "SELECT * FROM undertime_tb WHERE `status`= 'Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../undertime_req.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All
if (isset($_POST['reject_all_ut'])){
  $msg = "";
  $error = false;

  $query = "SELECT * FROM undertime_tb WHERE `status` = 'Pending'";
  $result_pending = mysqli_query($conn, $query);

  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Manila'));
  $currentDateTime = $now->format('Y-m-d H:i:s');

  $ut_reject_marks = $_POST['ut_all_reject_marks']; //para sa remarks to

  if(mysqli_num_rows($result_pending) > 0){
    while ($row_under = mysqli_fetch_assoc($result_pending)) {
        $underID = $row_under['id'];
        $employeeid = $row_under['empid'];
        $date_under = $row_under['date'];
        $starttime = $row_under['start_time'];
        $endtime = $row_under['end_time'];
        $total_undertime = $row_under['total_undertime'];
        $status_under = $row_under['status'];

      if($status_under === 'Pending'){

                $day_of_week = date('l', strtotime($date_under)); // get the day of the week using the "l" format specifier
                
                        if ($day_of_week === 'Monday') {
                            $UpdateUT = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunUT = mysqli_query($conn, $UpdateUT);
                            if ($RunUT){
                              $msg .= "You Reject the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            } else {
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_under<br>";
                          }
                        } //Monday Close Bracket

                        else if ($day_of_week === 'Tuesday') {
                            $UpdateUT = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunUT = mysqli_query($conn, $UpdateUT);
                            if ($RunUT){
                              $msg .= "You Reject the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            } else {
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_under<br>";
                          }
                        } //Tuesday Close Bracket 

                        else if ($day_of_week === 'Wednesday') {
                            $UpdateUT = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunUT = mysqli_query($conn, $UpdateUT);
                            if ($RunUT){
                              $msg .= "You Reject the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            } else {
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_under<br>";
                          }
                      } //Wednesday Close Bracket 

                        else if ($day_of_week === 'Thursday') {
                            $UpdateUT = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunUT = mysqli_query($conn, $UpdateUT);
                            if ($RunUT){
                              $msg .= "You Reject the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            } else {
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_under<br>";
                          }
                      } //Thursday Close Bracket 

                        else if ($day_of_week === 'Friday') {
                            $UpdateUT = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunUT = mysqli_query($conn, $UpdateUT);
                            if ($RunUT){
                              $msg .= "You Reject the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            } else {
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_under<br>";
                          }
                      } //Friday Close Bracket 

                        else if ($day_of_week === 'Saturday') {
                            $UpdateUT = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunUT = mysqli_query($conn, $UpdateUT);
                            if ($RunUT){
                              $msg .= "You Reject the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            } else {
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_under<br>";
                          }
                       } //Saturday Close Bracket 

                        else if ($day_of_week === 'Sunday') {
                            $UpdateUT = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$ut_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$underID' AND `status` = 'Pending' AND `date` = '$date_under'";
                            $RunUT = mysqli_query($conn, $UpdateUT);
                            if ($RunUT){
                              $msg .= "You Reject the Request Successfully for employee ID: $employeeid on $date_under<br>";
                            } else {
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_under<br>";
                          }
                      } //Sunday Close Bracket 

                        else {
                          $error = true;
                          $msg .= "Invalid day of the week for employee ID: $employeeid<br>";
                        }

                        if ($error) {
                          header("Location: ../../undertime_req.php?error=$msg");
                        } else {
                          header("Location: ../../undertime_req.php?msg=$msg");
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

                          $selectOT = "SELECT * FROM undertime_tb WHERE id = '$underID' AND empid = '$EmpMail'";  
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
                              $subject = "EMPLOYEE '$empid - $fullname' UNDERTIME REQUEST";

                              $message = "
                              <html>
                              <head>
                              <title>{$subject}</title>
                              </head>
                              <body>
                              <p><strong>Dear $to,</strong></p>
                              <p>Your undertime request on $date_under is rejected</p>
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
              header("Location: ../../undertime_req.php?error=$msg");
            } else {
              header("Location: ../../undertime_req.php?msg=$msg");
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

                $selectOT = "SELECT * FROM undertime_tb WHERE id = '$underID' AND empid = '$EmpMail'";  
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
                    $subject = "EMPLOYEE '$empid - $fullname' UNDERTIME REQUEST";

                    $message = "
                    <html>
                    <head>
                    <title>{$subject}</title>
                    </head>
                    <body>
                    <p><strong>Dear $to,</strong></p>
                    <p>Your undertime request on $date_under is rejected</p>
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