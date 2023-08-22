<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hris_db");
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/src/Exception.php';
require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/SMTP.php';

$query = "SELECT * FROM emp_official_tb WHERE `status`='Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../official_business.php?error=No Pending Requests");
  exit();
}

// Check if the user clicked Approve All or Reject All Button
if (isset($_POST['OB_approve_all'])) {
  $msg = '';
  $error = false;

  $query = "SELECT * FROM emp_official_tb WHERE `status` = 'Pending'";
  $result_pending = mysqli_query($conn, $query);

  $now = new DateTime();
  $now->setTimezone(new DateTimeZone('Asia/Manila'));
  $currentDateTime = $now->format('Y-m-d H:i:s');

  $OB_approver_marks = $_POST['ob_approve_marks'];

  if (mysqli_num_rows($result_pending) > 0) {
    while ($row_official = mysqli_fetch_assoc($result)) {
      $official_ID = $row_official['id'];
      $employeeid = $row_official['employee_id'];
      $date_official_start = $row_official['str_date'];
      $date_official_end = $row_official ['end_date'];
      $starttime_official = $row_official['start_time'];
      $endtime_official = $row_official['end_time'];
      $status_official = $row_official['status'];

    if ($status_official == 'Pending') {
        
                        $start_date = new DateTime($date_official_start);
                        $end_date = new DateTime($date_official_end);
                        $interval = new DateInterval('P1D'); // 1 day interval
                        $daterange = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // Include end date

                        foreach ($daterange as $date) {
                          $date_range = $date->format('l');
                          $date_str = $date->format('Y-m-d');

                          if($date_range === 'Monday'){
                            $selectPresent = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                            $PresentRun = mysqli_query($conn, $selectPresent);
                            
                            if (mysqli_num_rows($PresentRun) <= 0) {
                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  
                                
                                $start_time = new DateTime($starttime_official);
                                $end_time = new DateTime($endtime_official);
                                
                                if ($start_time < $lunchbreak_start && $end_time > $lunchbreak_start) {
                                    $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($calculated_hours);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_hours = $total_work_datetime->format('H:i:s');
                                } else {
                                    $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                    // Remove Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($calculated_hours);
                                    $total_hours = $total_work_datetime->format('H:i:s');
                                } 
                            
                                $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                                VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                                $result_attendance = mysqli_query($conn, $sql);
                            
                                if (!$result_attendance) {
                                    $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                    $error = true;
                                } else {
                                    $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                    $RunOB = mysqli_query($conn, $UpdateOB);
                                    $msg = "You Approved all requests successfully.";
                                }
                            } else {
                                $UpdateAtt = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                $PendingRun = mysqli_query($conn, $UpdateAtt);
                                if (!$PendingRun) {
                                    $msg = "Failed to update the attendances table: " . mysqli_error($conn);
                                    $error = true;
                                } else {
                                    $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                    $RunOB = mysqli_query($conn, $UpdateOB);
                                    $msg = "You Approved all requests successfully.";
                                }
                            }  
                            header("Location: ../../official_business.php?msg=$msg");
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

                            $selectOT = "SELECT * FROM emp_official_tb WHERE `id` = '$official_ID' AND `employee_id` = '$EmpMail'";  
                            $approvedOTRun = mysqli_query($conn, $selectOT);

                            $ApprovedArray = array();
                            while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                                $employeeApproved = $ApprovedRow['employee_id'];

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
                                $subject = "EMPLOYEE '$empid - $fullname' OFFICIAL BUSINESS REQUEST";
        
                                $message = "
                                <html>
                                <head>
                                <title>{$subject}</title>
                                </head>
                                <body>
                                <p><strong>Dear $to,</strong></p>
                                <p>Your official business request on $date_official_start to $date_official_end is approved</p>
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
                        } //Monday

                             else if($date_range === 'Tuesday'){
                                $selectPresent = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                $PresentRun = mysqli_query($conn, $selectPresent);

                                if (mysqli_num_rows($PresentRun) <= 0) {
                                    $lunchbreak_start = new DateTime('12:00:00');
                                    $lunchbreak_end = new DateTime('13:00:00');  
                                    
                                    $start_time = new DateTime($starttime_official);
                                    $end_time = new DateTime($endtime_official);
                                    
                                    if ($start_time < $lunchbreak_start && $end_time > $lunchbreak_start) {
                                        $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                        // Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($calculated_hours);
                                        $total_work_datetime->sub(new DateInterval('PT1H'));
                                        $total_hours = $total_work_datetime->format('H:i:s');
                                    } else {
                                        $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                        // Remove Subtract 1 hour from total work
                                        $total_work_datetime = new DateTime($calculated_hours);
                                        $total_hours = $total_work_datetime->format('H:i:s');
                                    } 

                                    $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                                    VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                                    $result_attendance = mysqli_query($conn, $sql);

                                    if (!$result_attendance) {
                                        $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                        $error = true;
                                    } else {
                                        $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                        $RunOB = mysqli_query($conn, $UpdateOB);
                                        $msg = "You Approved all requests successfully.";
                                    }
                                } else {
                                    $UpdateAtt = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                    $PendingRun = mysqli_query($conn, $UpdateAtt);
                                    if (!$PendingRun) {
                                        $msg = "Failed to update the attendances table: " . mysqli_error($conn);
                                        $error = true;
                                    } else {
                                        $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                        $RunOB = mysqli_query($conn, $UpdateOB);
                                        $msg = "You Approved all requests successfully.";
                                    }
                                }
                                header("Location: ../../official_business.php?msg=$msg");     
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

                                $selectOT = "SELECT * FROM emp_official_tb WHERE `id` = '$official_ID' AND `employee_id` = '$EmpMail'";  
                                $approvedOTRun = mysqli_query($conn, $selectOT);

                                $ApprovedArray = array();
                                while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                                    $employeeApproved = $ApprovedRow['employee_id'];

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
                                    $subject = "EMPLOYEE '$empid - $fullname' OFFICIAL BUSINESS REQUEST";
            
                                    $message = "
                                    <html>
                                    <head>
                                    <title>{$subject}</title>
                                    </head>
                                    <body>
                                    <p><strong>Dear $to,</strong></p>
                                    <p>Your official business request on $date_official_start to $date_official_end is approved</p>
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
                           }//Close bracket Tuesday

                        else if($date_range === 'Wednesday'){
                            $selectPresent = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                            $PresentRun = mysqli_query($conn, $selectPresent);

                            if (mysqli_num_rows($PresentRun) <= 0) {
                                $lunchbreak_start = new DateTime('12:00:00');
                                $lunchbreak_end = new DateTime('13:00:00');  
                                
                                $start_time = new DateTime($starttime_official);
                                $end_time = new DateTime($endtime_official);
                                
                                if ($start_time < $lunchbreak_start && $end_time > $lunchbreak_start) {
                                    $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                    // Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($calculated_hours);
                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                    $total_hours = $total_work_datetime->format('H:i:s');
                                } else {
                                    $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                    // Remove Subtract 1 hour from total work
                                    $total_work_datetime = new DateTime($calculated_hours);
                                    $total_hours = $total_work_datetime->format('H:i:s');
                                } 

                                $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                                VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                                $result_attendance = mysqli_query($conn, $sql);

                                if (!$result_attendance) {
                                    $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                    $error = true;
                                } else {
                                    $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                    $RunOB = mysqli_query($conn, $UpdateOB);
                                    $msg = "You Approved all requests successfully.";
                                }
                            } else {
                                $UpdateAtt = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                $PendingRun = mysqli_query($conn, $UpdateAtt);
                                if (!$PendingRun) {
                                    $msg = "Failed to update the attendances table: " . mysqli_error($conn);
                                    $error = true;
                                } else {
                                    $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                    $RunOB = mysqli_query($conn, $UpdateOB);
                                    $msg = "You Approved all requests successfully.";
                                }
                            }
                            header("Location: ../../official_business.php?msg=$msg");      
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

                                $selectOT = "SELECT * FROM emp_official_tb WHERE `id` = '$official_ID' AND `employee_id` = '$EmpMail'";  
                                $approvedOTRun = mysqli_query($conn, $selectOT);

                                $ApprovedArray = array();
                                while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                                    $employeeApproved = $ApprovedRow['employee_id'];

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
                                    $subject = "EMPLOYEE '$empid - $fullname' OFFICIAL BUSINESS REQUEST";
            
                                    $message = "
                                    <html>
                                    <head>
                                    <title>{$subject}</title>
                                    </head>
                                    <body>
                                    <p><strong>Dear $to,</strong></p>
                                    <p>Your official business request on $date_official_start to $date_official_end is approved</p>
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
                           }//Close bracket Wednesday

                                else if($date_range === 'Thursday'){
                                    $selectPresent = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                    $PresentRun = mysqli_query($conn, $selectPresent);

                                    if (mysqli_num_rows($PresentRun) <= 0) {
                                        $lunchbreak_start = new DateTime('12:00:00');
                                        $lunchbreak_end = new DateTime('13:00:00');  
                                        
                                        $start_time = new DateTime($starttime_official);
                                        $end_time = new DateTime($endtime_official);
                                        
                                        if ($start_time < $lunchbreak_start && $end_time > $lunchbreak_start) {
                                            $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                            // Subtract 1 hour from total work
                                            $total_work_datetime = new DateTime($calculated_hours);
                                            $total_work_datetime->sub(new DateInterval('PT1H'));
                                            $total_hours = $total_work_datetime->format('H:i:s');
                                        } else {
                                            $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                            // Remove Subtract 1 hour from total work
                                            $total_work_datetime = new DateTime($calculated_hours);
                                            $total_hours = $total_work_datetime->format('H:i:s');
                                        } 

                                        $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                                        VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                                        $result_attendance = mysqli_query($conn, $sql);

                                        if (!$result_attendance) {
                                            $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                            $error = true;
                                        } else {
                                            $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                            $RunOB = mysqli_query($conn, $UpdateOB);
                                            $msg = "You Approved all requests successfully.";
                                        }
                                    } else {
                                        $UpdateAtt = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                        $PendingRun = mysqli_query($conn, $UpdateAtt);
                                        if (!$PendingRun) {
                                            $msg = "Failed to update the attendances table: " . mysqli_error($conn);
                                            $error = true;
                                        } else {
                                            $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                            $RunOB = mysqli_query($conn, $UpdateOB);
                                            $msg = "You Approved all requests successfully.";
                                        }
                                    }
                                    header("Location: ../../official_business.php?msg=$msg"); 
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

                                    $selectOT = "SELECT * FROM emp_official_tb WHERE `id` = '$official_ID' AND `employee_id` = '$EmpMail'";  
                                    $approvedOTRun = mysqli_query($conn, $selectOT);

                                    $ApprovedArray = array();
                                    while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                                        $employeeApproved = $ApprovedRow['employee_id'];

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
                                        $subject = "EMPLOYEE '$empid - $fullname' OFFICIAL BUSINESS REQUEST";
                
                                        $message = "
                                        <html>
                                        <head>
                                        <title>{$subject}</title>
                                        </head>
                                        <body>
                                        <p><strong>Dear $to,</strong></p>
                                        <p>Your official business request on $date_official_start to $date_official_end is approved</p>
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
                               }//Close bracket Thursday


                                        else if($date_range === 'Friday'){
                                            $selectPresent = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                            $PresentRun = mysqli_query($conn, $selectPresent);

                                            if (mysqli_num_rows($PresentRun) <= 0) {
                                                $lunchbreak_start = new DateTime('12:00:00');
                                                $lunchbreak_end = new DateTime('13:00:00');  
                                                
                                                $start_time = new DateTime($starttime_official);
                                                $end_time = new DateTime($endtime_official);
                                                
                                                if ($start_time < $lunchbreak_start && $end_time > $lunchbreak_start) {
                                                    $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                                    // Subtract 1 hour from total work
                                                    $total_work_datetime = new DateTime($calculated_hours);
                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                    $total_hours = $total_work_datetime->format('H:i:s');
                                                } else {
                                                    $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                                    // Remove Subtract 1 hour from total work
                                                    $total_work_datetime = new DateTime($calculated_hours);
                                                    $total_hours = $total_work_datetime->format('H:i:s');
                                                } 

                                                $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                                                VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                                                $result_attendance = mysqli_query($conn, $sql);

                                                if (!$result_attendance) {
                                                    $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                                    $error = true;
                                                } else {
                                                    $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                                    $RunOB = mysqli_query($conn, $UpdateOB);
                                                    $msg = "You Approved all requests successfully.";
                                                }
                                            } else {
                                                $UpdateAtt = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                                $PendingRun = mysqli_query($conn, $UpdateAtt);
                                                if (!$PendingRun) {
                                                    $msg = "Failed to update the attendances table: " . mysqli_error($conn);
                                                    $error = true;
                                                } else {
                                                    $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                                    $RunOB = mysqli_query($conn, $UpdateOB);
                                                    $msg = "You Approved all requests successfully.";
                                                }
                                            }
                                            header("Location: ../../official_business.php?msg=$msg"); 
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

                                            $selectOT = "SELECT * FROM emp_official_tb WHERE `id` = '$official_ID' AND `employee_id` = '$EmpMail'";  
                                            $approvedOTRun = mysqli_query($conn, $selectOT);

                                            $ApprovedArray = array();
                                            while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                                                $employeeApproved = $ApprovedRow['employee_id'];

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
                                                $subject = "EMPLOYEE '$empid - $fullname' OFFICIAL BUSINESS REQUEST";
                        
                                                $message = "
                                                <html>
                                                <head>
                                                <title>{$subject}</title>
                                                </head>
                                                <body>
                                                <p><strong>Dear $to,</strong></p>
                                                <p>Your official business request on $date_official_start to $date_official_end is approved</p>
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
                                    }//Close bracket Friday

                                    else if($date_range === 'Saturday'){
                                        $selectPresent = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                        $PresentRun = mysqli_query($conn, $selectPresent);

                                        if (mysqli_num_rows($PresentRun) <= 0) {
                                            $lunchbreak_start = new DateTime('12:00:00');
                                            $lunchbreak_end = new DateTime('13:00:00');  
                                            
                                            $start_time = new DateTime($starttime_official);
                                            $end_time = new DateTime($endtime_official);
                                            
                                            if ($start_time < $lunchbreak_start && $end_time > $lunchbreak_start) {
                                                $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_work_datetime = new DateTime($calculated_hours);
                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                $total_hours = $total_work_datetime->format('H:i:s');
                                            } else {
                                                $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                                // Remove Subtract 1 hour from total work
                                                $total_work_datetime = new DateTime($calculated_hours);
                                                $total_hours = $total_work_datetime->format('H:i:s');
                                            } 

                                            $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                                            $result_attendance = mysqli_query($conn, $sql);

                                            if (!$result_attendance) {
                                                $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                                $error = true;
                                            } else {
                                                $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                                $RunOB = mysqli_query($conn, $UpdateOB);
                                                $msg = "You Approved all requests successfully.";
                                            }
                                        } else {
                                            $UpdateAtt = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                            $PendingRun = mysqli_query($conn, $UpdateAtt);
                                            if (!$PendingRun) {
                                                $msg = "Failed to update the attendances table: " . mysqli_error($conn);
                                                $error = true;
                                            } else {
                                                $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                                $RunOB = mysqli_query($conn, $UpdateOB);
                                                $msg = "You Approved all requests successfully.";
                                            }
                                        }
                                        header("Location: ../../official_business.php?msg=$msg");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE `id` = '$official_ID' AND `employee_id` = '$EmpMail'";  
                                        $approvedOTRun = mysqli_query($conn, $selectOT);

                                        $ApprovedArray = array();
                                        while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                                            $employeeApproved = $ApprovedRow['employee_id'];

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
                                            $subject = "EMPLOYEE '$empid - $fullname' OFFICIAL BUSINESS REQUEST";
                    
                                            $message = "
                                            <html>
                                            <head>
                                            <title>{$subject}</title>
                                            </head>
                                            <body>
                                            <p><strong>Dear $to,</strong></p>
                                            <p>Your official business request on $date_official_start to $date_official_end is approved</p>
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
                                   }//Close bracket Saturday

                                else if($date_range === 'Sunday'){
                                    $selectPresent = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                    $PresentRun = mysqli_query($conn, $selectPresent);

                                    if (mysqli_num_rows($PresentRun) <= 0) {
                                        $lunchbreak_start = new DateTime('12:00:00');
                                        $lunchbreak_end = new DateTime('13:00:00');  
                                        
                                        $start_time = new DateTime($starttime_official);
                                        $end_time = new DateTime($endtime_official);
                                        
                                        if ($start_time < $lunchbreak_start && $end_time > $lunchbreak_start) {
                                            $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                            // Subtract 1 hour from total work
                                            $total_work_datetime = new DateTime($calculated_hours);
                                            $total_work_datetime->sub(new DateInterval('PT1H'));
                                            $total_hours = $total_work_datetime->format('H:i:s');
                                        } else {
                                            $calculated_hours = $end_time->diff($start_time)->format('%H:%I:%S');
                                            // Remove Subtract 1 hour from total work
                                            $total_work_datetime = new DateTime($calculated_hours);
                                            $total_hours = $total_work_datetime->format('H:i:s');
                                        } 

                                        $sql = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                                        VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                                        $result_attendance = mysqli_query($conn, $sql);

                                        if (!$result_attendance) {
                                            $msg = "Failed to insert into the attendances table: " . mysqli_error($conn);
                                            $error = true;
                                        } else {
                                            $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                            $RunOB = mysqli_query($conn, $UpdateOB);
                                            $msg = "You Approved all requests successfully.";
                                        }
                                    } else {
                                        $UpdateAtt = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                                        $PendingRun = mysqli_query($conn, $UpdateAtt);
                                        if (!$PendingRun) {
                                            $msg = "Failed to update the attendances table: " . mysqli_error($conn);
                                            $error = true;
                                        } else {
                                            $UpdateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$OB_approver_marks' WHERE `status` = 'Pending' AND `employee_id` = '$employeeid' AND `id` = '$official_ID'";
                                            $RunOB = mysqli_query($conn, $UpdateOB);
                                            $msg = "You Approved all requests successfully.";
                                        }
                                    }
                                    header("Location: ../../official_business.php?msg=$msg");   
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

                                    $selectOT = "SELECT * FROM emp_official_tb WHERE `id` = '$official_ID' AND `employee_id` = '$EmpMail'";  
                                    $approvedOTRun = mysqli_query($conn, $selectOT);

                                    $ApprovedArray = array();
                                    while ($ApprovedRow = mysqli_fetch_assoc($approvedOTRun)) {
                                        $employeeApproved = $ApprovedRow['employee_id'];

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
                                        $subject = "EMPLOYEE '$empid - $fullname' OFFICIAL BUSINESS REQUEST";
                
                                        $message = "
                                        <html>
                                        <head>
                                        <title>{$subject}</title>
                                        </head>
                                        <body>
                                        <p><strong>Dear $to,</strong></p>
                                        <p>Your official business request on $date_official_start to $date_official_end is approved</p>
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
                            }//Close bracket Sunday
                             
                     
                                    
                  } //Foreach Close bracket
           
        } //Pending
    }//While close bracket
  }
}

?>