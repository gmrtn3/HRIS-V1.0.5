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
/************************* For Approve Button ***************************/
if(isset($_POST['name_approved']))
{
    $column_id = $_POST['id_check'];

    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Asia/Manila'));
    $currentDateTime = $now->format('Y-m-d H:i:s');

    $approver_remarks = $_POST['name_approvedremarks'];

    $result_official = mysqli_query($conn, "SELECT * FROM emp_official_tb WHERE id = '$column_id'");
    if(mysqli_num_rows($result_official) > 0) {
        $row_official = mysqli_fetch_assoc($result_official);
    }
    $employeeid = $row_official['employee_id'];
    $date_official_start = $row_official['str_date'];
    $date_official_end = $row_official ['end_date'];
    $starttime_official = $row_official['start_time'];
    $endtime_official = $row_official['end_time'];
    $status_official = $row_official['status'];

    if($status_official === 'Approved'){
        header("Location: ../../official_business.php?error=You cannot APPROVE a request that is already APPROVED");
    }
    else if($status_official === 'Rejected'){
        header("Location: ../../official_business.php?error=You cannot APPROVE a request that is already REJECTED");
    } else {

                    $start_date = new DateTime($date_official_start);
                    $end_date = new DateTime($date_official_end);
                    $interval = new DateInterval('P1D'); // 1 day interval
                    $daterange = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // Include end date
                    
                    foreach ($daterange as $date) {
                        $date_range = $date->format('l');
                        $date_str = $date->format('Y-m-d');
                    
                    if($date_range === 'Monday'){
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


                        $FirstSelect = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                        $FirstRun = mysqli_query($conn , $FirstSelect);

                        if(mysqli_num_rows($FirstRun) > 0){                         
                                $UpdateAbsent = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str'";
                                $runUpdate = mysqli_query($conn, $UpdateAbsent);
                                if($runUpdate){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                        } else {
                            $InsertEmpty = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                            $EmptyRun = mysqli_query($conn, $InsertEmpty);
                            
                            if($EmptyRun){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                      }
                    } //Close bracket Monday

                    else if($date_range === 'Tuesday'){
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

                        $FirstSelect = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                        $FirstRun = mysqli_query($conn , $FirstSelect);

                        if(mysqli_num_rows($FirstRun) > 0){                         
                                $UpdateAbsent = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str'";
                                $runUpdate = mysqli_query($conn, $UpdateAbsent);
                                if($runUpdate){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                        } else {
                            $InsertEmpty = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                            $EmptyRun = mysqli_query($conn, $InsertEmpty);
                            
                            if($EmptyRun){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                      }
                    } //Close bracket Tuesday

                    else if($date_range === 'Wednesday'){
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

                        $FirstSelect = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                        $FirstRun = mysqli_query($conn , $FirstSelect);

                        if(mysqli_num_rows($FirstRun) > 0){                         
                                $UpdateAbsent = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str'";
                                $runUpdate = mysqli_query($conn, $UpdateAbsent);
                                if($runUpdate){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                        } else {
                            $InsertEmpty = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                            $EmptyRun = mysqli_query($conn, $InsertEmpty);
                            
                            if($EmptyRun){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                      }
                    } //Close Bracket Wednesday

                    else if($date_range === 'Thursday'){
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

                        $FirstSelect = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                        $FirstRun = mysqli_query($conn , $FirstSelect);

                        if(mysqli_num_rows($FirstRun) > 0){                         
                                $UpdateAbsent = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str'";
                                $runUpdate = mysqli_query($conn, $UpdateAbsent);
                                if($runUpdate){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                        } else {
                            $InsertEmpty = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                            $EmptyRun = mysqli_query($conn, $InsertEmpty);
                            
                            if($EmptyRun){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                      }
                    } //Close Bracket Thursday

                    else if($date_range === 'Friday'){
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

                        $FirstSelect = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                        $FirstRun = mysqli_query($conn , $FirstSelect);

                        if(mysqli_num_rows($FirstRun) > 0){                         
                                $UpdateAbsent = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str'";
                                $runUpdate = mysqli_query($conn, $UpdateAbsent);
                                if($runUpdate){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                        } else {
                            $InsertEmpty = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                            $EmptyRun = mysqli_query($conn, $InsertEmpty);
                            
                            if($EmptyRun){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                      }
                    } //Close Bracket Friday

                    else if($date_range === 'Saturday'){
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

                        $FirstSelect = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                        $FirstRun = mysqli_query($conn , $FirstSelect);

                        if(mysqli_num_rows($FirstRun) > 0){                         
                                $UpdateAbsent = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str'";
                                $runUpdate = mysqli_query($conn, $UpdateAbsent);
                                if($runUpdate){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                        } else {
                            $InsertEmpty = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                            $EmptyRun = mysqli_query($conn, $InsertEmpty);
                            
                            if($EmptyRun){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                      }
                    } //Close Bracket Saturday


                    else if($date_range === 'Sunday'){
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
                        
                        $FirstSelect = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_str' AND `status` = 'Absent'";
                        $FirstRun = mysqli_query($conn , $FirstSelect);

                        if(mysqli_num_rows($FirstRun) > 0){                         
                                $UpdateAbsent = "UPDATE attendances SET `status` = 'Present', `time_in` = '$starttime_official', `time_out` = '$endtime_official', `total_work` = '$total_hours' WHERE `empid` = '$employeeid' AND `date` = '$date_str'";
                                $runUpdate = mysqli_query($conn, $UpdateAbsent);
                                if($runUpdate){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                        } else {
                            $InsertEmpty = "INSERT INTO attendances (`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_Out`, `overtime`, `total_work`) 
                            VALUES ('Present', '$employeeid', '$date_str', '$starttime_official', '$endtime_official', '00:00:00', '00:00:00', '00:00:00', '$total_hours')";
                            $EmptyRun = mysqli_query($conn, $InsertEmpty);
                            
                            if($EmptyRun){
                                    $updateOB = "UPDATE emp_official_tb SET `status` = 'Approved', `action_taken` = '$currentDateTime', `remarks` = '$approver_remarks' WHERE id = '$column_id'";
                                    $OBrun = mysqli_query($conn, $updateOB);

                                    if($OBrun){
                                        header("Location: ../../official_business.php?msg=You approved this request successfully");
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

                                        $selectOT = "SELECT * FROM emp_official_tb WHERE id = '$column_id' AND employee_id = '$EmpMail'";  
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
                                    }else{
                                        echo "Failed: " . mysqli_error($conn);
                                    }
                                }else{
                                    header("Location: ../../official_business.php?error=this status is not Pending");
                           }
                      }
                    } //Close Bracket Sunday


            } //Close bracket foreach
       }
  } //Button Approve

/************************* End of Approve Button ***************************/



?>