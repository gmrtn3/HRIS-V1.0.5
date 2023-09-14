<?php
    session_start();
 include '../../config.php';
    $employeeID = $_SESSION['empid'];
    $Username = $_SESSION['username'];

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../../phpmailer/src/Exception.php';
    require '../../../phpmailer/src/PHPMailer.php';
    require '../../../phpmailer/src/SMTP.php';

if(isset($_POST['name_rejected_wfh']))
{

    $reject_id = $_POST['reject_id_wfh'];

    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Asia/Manila'));
    $currentDateTime = $now->format('Y-m-d H:i:s');

    $wfh_reject_marks = $_POST['wfh_reject_remarks'];

    $result_wfh = mysqli_query($conn, " SELECT * FROM wfh_tb WHERE id = '$reject_id'");
    if(mysqli_num_rows($result_wfh) > 0) {
        $row_wfh = mysqli_fetch_assoc($result_wfh);
}
    $status_wfh = $row_wfh['status'];
    $employeeid = $row_wfh['empid'];
    $choose_date = $row_wfh['date'];
    $starttime = $row_wfh['start_time'];
    $endtime = $row_wfh['end_time'];
    $status_ot = $row_wfh['status'];

            $day_of_week = date('l', strtotime($choose_date)); // get the day of the week using the "l" format specifier

            if($day_of_week === 'Monday'){
                $checkWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND date = '$choose_date' AND status = 'Rejected'";
                $wfhRun = mysqli_query($conn, $checkWFH);
                
                if (mysqli_num_rows($wfhRun) === 0) {
                    $sql = "UPDATE wfh_tb SET `status` = 'Rejected', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_reject_marks' WHERE `id` = '$reject_id'";
                    $query_run = mysqli_query($conn, $sql);
    
                    if ($query_run) {
                        header("Location: ../../wfh_request.php?msg=You Rejected this Request Successfully");
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

                        $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$reject_id' AND empid = '$EmpMail'";
                        $approvedWFHRun = mysqli_query($conn, $selectWFH);

                        $ApprovedArray = array();
                        while ($ApprovedRow = mysqli_fetch_assoc($approvedWFHRun)) {
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
                        $subject = "EMPLOYEE '$empid - $fullname' WORK FROM HOME REQUEST";

                        $message = "
                        <html>
                        <head>
                        <title>{$subject}</title>
                        </head>
                        <body>
                        <p><strong>Dear $to,</strong></p>
                        <p>Your work from home request on $choose_date is rejected</p>
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
                    } else {
                        header("Location: ../../wfh_request.php?error=This Request is already Rejected");
                    }
                }
            } //Monday Close Tag

            else if($day_of_week === 'Tuesday'){
                $checkWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND date = '$choose_date' AND status = 'Rejected'";
                $wfhRun = mysqli_query($conn, $checkWFH);
                
                if (mysqli_num_rows($wfhRun) === 0) {
                    $sql = "UPDATE wfh_tb SET `status` = 'Rejected', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_reject_marks' WHERE `id` = '$reject_id'";
                    $query_run = mysqli_query($conn, $sql);
    
                    if ($query_run) {
                        header("Location: ../../wfh_request.php?msg=You Rejected this Request Successfully");
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

                        $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$reject_id' AND empid = '$EmpMail'";
                        $approvedWFHRun = mysqli_query($conn, $selectWFH);

                        $ApprovedArray = array();
                        while ($ApprovedRow = mysqli_fetch_assoc($approvedWFHRun)) {
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
                        $subject = "EMPLOYEE '$empid - $fullname' WORK FROM HOME REQUEST";

                        $message = "
                        <html>
                        <head>
                        <title>{$subject}</title>
                        </head>
                        <body>
                        <p><strong>Dear $to,</strong></p>
                        <p>Your work from home request on $choose_date is rejected</p>
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
                    } else {
                        header("Location: ../../wfh_request.php?error=This Request is already Rejected");
                    }
                }
            } //Tuesday Close Tag

            else if($day_of_week === 'Wednesday'){
                $checkWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND date = '$choose_date' AND status = 'Rejected'";
                $wfhRun = mysqli_query($conn, $checkWFH);
                
                if (mysqli_num_rows($wfhRun) === 0) {
                    $sql = "UPDATE wfh_tb SET `status` = 'Rejected', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_reject_marks' WHERE `id` = '$reject_id'";
                    $query_run = mysqli_query($conn, $sql);
    
                    if ($query_run) {
                        header("Location: ../../wfh_request.php?msg=You Rejected this Request Successfully");
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
 
                         $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$reject_id' AND empid = '$EmpMail'";
                         $approvedWFHRun = mysqli_query($conn, $selectWFH);
 
                         $ApprovedArray = array();
                         while ($ApprovedRow = mysqli_fetch_assoc($approvedWFHRun)) {
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
                         $subject = "EMPLOYEE '$empid - $fullname' WORK FROM HOME REQUEST";
 
                         $message = "
                         <html>
                         <head>
                         <title>{$subject}</title>
                         </head>
                         <body>
                         <p><strong>Dear $to,</strong></p>
                         <p>Your work from home request on $choose_date is rejected</p>
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
                    } else {
                        header("Location: ../../wfh_request.php?error=This Request is already Rejected");
                    }
                }
            } //Wednesday Close Tag

            else if($day_of_week === 'Thursday'){
                $checkWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND date = '$choose_date' AND status = 'Rejected'";
                $wfhRun = mysqli_query($conn, $checkWFH);
                
                if (mysqli_num_rows($wfhRun) === 0) {
                    $sql = "UPDATE wfh_tb SET `status` = 'Rejected', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_reject_marks' WHERE `id` = '$reject_id'";
                    $query_run = mysqli_query($conn, $sql);
    
                    if ($query_run) {
                        header("Location: ../../wfh_request.php?msg=You Rejected this Request Successfully");
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
 
                         $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$reject_id' AND empid = '$EmpMail'";
                         $approvedWFHRun = mysqli_query($conn, $selectWFH);
 
                         $ApprovedArray = array();
                         while ($ApprovedRow = mysqli_fetch_assoc($approvedWFHRun)) {
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
                         $subject = "EMPLOYEE '$empid - $fullname' WORK FROM HOME REQUEST";
 
                         $message = "
                         <html>
                         <head>
                         <title>{$subject}</title>
                         </head>
                         <body>
                         <p><strong>Dear $to,</strong></p>
                         <p>Your work from home request on $choose_date is rejected</p>
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
                    } else {
                        header("Location: ../../wfh_request.php?error=This Request is already Rejected");
                    }
                }
            } //Thursday Close Tag

            else if($day_of_week === 'Friday'){
                $checkWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND date = '$choose_date' AND status = 'Rejected'";
                $wfhRun = mysqli_query($conn, $checkWFH);
                
                if (mysqli_num_rows($wfhRun) === 0) {
                    $sql = "UPDATE wfh_tb SET `status` = 'Rejected', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_reject_marks' WHERE `id` = '$reject_id'";
                    $query_run = mysqli_query($conn, $sql);
    
                    if ($query_run) {
                        header("Location: ../../wfh_request.php?msg=You Rejected this Request Successfully");
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
 
                         $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$reject_id' AND empid = '$EmpMail'";
                         $approvedWFHRun = mysqli_query($conn, $selectWFH);
 
                         $ApprovedArray = array();
                         while ($ApprovedRow = mysqli_fetch_assoc($approvedWFHRun)) {
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
                         $subject = "EMPLOYEE '$empid - $fullname' WORK FROM HOME REQUEST";
 
                         $message = "
                         <html>
                         <head>
                         <title>{$subject}</title>
                         </head>
                         <body>
                         <p><strong>Dear $to,</strong></p>
                         <p>Your work from home request on $choose_date is rejected</p>
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
                    } else {
                        header("Location: ../../wfh_request.php?error=This Request is already Rejected");
                    }
                }
            } //Friday Close Tag

            else if($day_of_week === 'Saturday'){
                $checkWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND date = '$choose_date' AND status = 'Rejected'";
                $wfhRun = mysqli_query($conn, $checkWFH);
                
                if (mysqli_num_rows($wfhRun) === 0) {
                    $sql = "UPDATE wfh_tb SET `status` = 'Rejected', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_reject_marks' WHERE `id` = '$reject_id'";
                    $query_run = mysqli_query($conn, $sql);
    
                    if ($query_run) {
                        header("Location: ../../wfh_request.php?msg=You Rejected this Request Successfully");
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

                        $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$reject_id' AND empid = '$EmpMail'";
                        $approvedWFHRun = mysqli_query($conn, $selectWFH);

                        $ApprovedArray = array();
                        while ($ApprovedRow = mysqli_fetch_assoc($approvedWFHRun)) {
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
                        $subject = "EMPLOYEE '$empid - $fullname' WORK FROM HOME REQUEST";

                        $message = "
                        <html>
                        <head>
                        <title>{$subject}</title>
                        </head>
                        <body>
                        <p><strong>Dear $to,</strong></p>
                        <p>Your work from home request on $choose_date is rejected</p>
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
                    } else {
                        header("Location: ../../wfh_request.php?error=This Request is already Rejected");
                    }
                }
            } //Saturday Close Tag

            else if($day_of_week === 'Sunday'){
                $checkWFH = "SELECT * FROM wfh_tb WHERE empid = '$employeeid' AND date = '$choose_date' AND status = 'Rejected'";
                $wfhRun = mysqli_query($conn, $checkWFH);
                
                if (mysqli_num_rows($wfhRun) === 0) {
                    $sql = "UPDATE wfh_tb SET `status` = 'Rejected', `wfh_action_taken` = '$currentDateTime', `wfh_remarks` = '$wfh_reject_marks' WHERE `id` = '$reject_id'";
                    $query_run = mysqli_query($conn, $sql);
    
                    if ($query_run) {
                        header("Location: ../../wfh_request.php?msg=You Rejected this Request Successfully");
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

                        $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$reject_id' AND empid = '$EmpMail'";
                        $approvedWFHRun = mysqli_query($conn, $selectWFH);

                        $ApprovedArray = array();
                        while ($ApprovedRow = mysqli_fetch_assoc($approvedWFHRun)) {
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
                        $subject = "EMPLOYEE '$empid - $fullname' WORK FROM HOME REQUEST";

                        $message = "
                        <html>
                        <head>
                        <title>{$subject}</title>
                        </head>
                        <body>
                        <p><strong>Dear $to,</strong></p>
                        <p>Your work from home request on $choose_date is rejected</p>
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
                    } else {
                        header("Location: ../../wfh_request.php?error=This Request is already Rejected");
                    }
                }
            } //Sunday Close Tag
   
}

?>