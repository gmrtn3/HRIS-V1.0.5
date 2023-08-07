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

    require '../../../phpmailer/src/Exception.php';
    require '../../../phpmailer/src/PHPMailer.php';
    require '../../../phpmailer/src/SMTP.php';

    if(isset($_POST['approve_btn']))
{

    $column_id = $_POST['id_check'];

    $result_under = mysqli_query($conn, "SELECT * FROM undertime_tb WHERE id = '$column_id'");
    if(mysqli_num_rows($result_under) > 0) {
        $row_under = mysqli_fetch_assoc($result_under);
    }
    $employeeid = $row_under['empid'];
    $date_under = $row_under['date'];
    $starttime = $row_under['start_time'];
    $endtime = $row_under['end_time'];
    $total_undertime = $row_under['total_undertime'];
    $status_under = $row_under['status'];

                            $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'");
                            if(mysqli_num_rows($result_emp_sched) > 0) {
                            $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                            $schedID = $row_emp_sched['schedule_name'];

                            $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
                            if(mysqli_num_rows($result_sched_tb) > 0) {
                                $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                                $sched_name =  $row_sched_tb['schedule_name'];
                                $col_monday_timeout =  $row_sched_tb['mon_timeout'];
                                $col_tuesday_timeout =  $row_sched_tb['tues_timeout'];
                                $col_wednesday_timeout =  $row_sched_tb['wed_timeout'];
                                $col_thursday_timeout =  $row_sched_tb['thurs_timeout'];
                                $col_friday_timeout =  $row_sched_tb['fri_timeout'];
                                $col_saturday_timeout =  $row_sched_tb['sat_timeout'];
                                $col_sunday_timeout =  $row_sched_tb['sun_timeout'];

                                $day_of_week = date('l', strtotime($date_under)); // get the day of the week using the "l" format specifier

                                if($day_of_week === 'Monday'){
                                    $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                                    $RunAtt = mysqli_query($conn, $CheckAtt);
                                    if (mysqli_num_rows($RunAtt) > 0) {
                                        $rowAtt = mysqli_fetch_assoc($RunAtt);
                                        $fetch_timein = $rowAtt['time_in'];
                                        $fetch_late = $rowAtt['late'];                                    
                                    

                                    if($fetch_timein != '00:00:00'){
                                       $existing_timein = new DateTime($fetch_timein);
                                       $file_out = new DateTime($endtime);
                                       $late_datetime = new DateTime($fetch_late);

                                       $lunchbreak_start = new DateTime('12:00:00');
                                       $lunchbreak_end = new DateTime('13:00:00');  

                                       $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                                       $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);
                                    
                                        //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                        if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start) {
                                                $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // $total_under = (new DateTime($undertime_total))->diff($late_datetime)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }else{
                                               $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                // $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }
                                    }else{
                                        $total_work = "00:00:00";
                                    }  
                                        $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                                        `early_out` = '$total_undertime', 
                                        `total_work`='$total_work' 
                                         WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                        $result = mysqli_query($conn, $sql);
                                        if($result){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully"); 
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

                                                    $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                        <p>Your undertime request on $date_under is approved</p>
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
                                        } else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }    
                                } else {
                                        $underQuery = "INSERT INTO `attendances`(`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) 
                                        VALUES ('', '$employeeid', '$date_under', '00:00:00', '$endtime', '00:00:00', '$total_undertime', '00:00:00', '00:00:00', '00:00:00')";
                                        $underRun = mysqli_query($conn, $underQuery);

                                        if($underRun){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
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

                                                    $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                        <p>Your undertime request on $date_under is approved</p>
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
                                           }
                                         }                                    
                                    } //Monday Close Tag

                                else if ($day_of_week === 'Tuesday') {
                                    $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                                    $RunAtt = mysqli_query($conn, $CheckAtt);
                                    if (mysqli_num_rows($RunAtt) > 0) {
                                        $rowAtt = mysqli_fetch_assoc($RunAtt);
                                        $fetch_timein = $rowAtt['time_in'];
                                        $fetch_late = $rowAtt['late'];                                    
                                    

                                    if($fetch_timein != '00:00:00'){
                                       $existing_timein = new DateTime($fetch_timein);
                                       $file_out = new DateTime($endtime);
                                       $late_datetime = new DateTime($fetch_late);

                                       $lunchbreak_start = new DateTime('12:00:00');
                                       $lunchbreak_end = new DateTime('13:00:00');  

                                       $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                       $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);
                                    
                                        //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                        if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start) {
                                                $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // $total_under = (new DateTime($undertime_total))->diff($late_datetime)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }else{
                                               $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                // $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }
                                    }else{
                                        $total_work = "00:00:00";
                                    }  
                                        $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                                        `early_out` = '$total_undertime', 
                                        `total_work`='$total_work' 
                                         WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                        $result = mysqli_query($conn, $sql);
                                        if($result){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                                                    //     //Syntax sa email notification
                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                        
                                                        $EmpApproverArray = array();
                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                        }

                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                        $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                            <p>Your undertime request on $date_under is approved</p>
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
                                        } else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }    
                                } else {
                                        $underQuery = "INSERT INTO `attendances`(`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) 
                                        VALUES ('', '$employeeid', '$date_under', '00:00:00', '$endtime', '00:00:00', '$total_undertime', '00:00:00', '00:00:00', '00:00:00')";
                                        $underRun = mysqli_query($conn, $underQuery);

                                        if($underRun){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
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

                                                    $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                        <p>Your undertime request on $date_under is approved</p>
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
                                           }
                                         }                                    
                                    }//Tuesday Close Tag
                

                            else if($day_of_week === 'Wednesday'){
                                $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                                    $RunAtt = mysqli_query($conn, $CheckAtt);
                                    if (mysqli_num_rows($RunAtt) > 0) {
                                        $rowAtt = mysqli_fetch_assoc($RunAtt);
                                        $fetch_timein = $rowAtt['time_in'];
                                        $fetch_late = $rowAtt['late'];                                    
                                    

                                    if($fetch_timein != '00:00:00'){
                                       $existing_timein = new DateTime($fetch_timein);
                                       $file_out = new DateTime($endtime);
                                       $late_datetime = new DateTime($fetch_late);

                                       $lunchbreak_start = new DateTime('12:00:00');
                                       $lunchbreak_end = new DateTime('13:00:00');  

                                       $SchedTimeIn = new DateTime($row_sched_tb['wed_timein']);
                                       $SchedTimeOut = new DateTime($row_sched_tb['wed_timeout']);
                                    
                                        //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                        if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start) {
                                                $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // $total_under = (new DateTime($undertime_total))->diff($late_datetime)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }else{
                                               $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                // $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }
                                    }else{
                                        $total_work = "00:00:00";
                                    }  
                                        $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                                        `early_out` = '$total_undertime', 
                                        `total_work`='$total_work' 
                                         WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                        $result = mysqli_query($conn, $sql);
                                        if($result){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                                                    //     //Syntax sa email notification
                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                        
                                                        $EmpApproverArray = array();
                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                        }

                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                        $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                            <p>Your undertime request on $date_under is approved</p>
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
                                        } else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }    
                                } else {
                                        $underQuery = "INSERT INTO `attendances`(`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) 
                                        VALUES ('', '$employeeid', '$date_under', '00:00:00', '$endtime', '00:00:00', '$total_undertime', '00:00:00', '00:00:00', '00:00:00')";
                                        $underRun = mysqli_query($conn, $underQuery);

                                        if($underRun){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
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

                                                    $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                        <p>Your undertime request on $date_under is approved</p>
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
                                           }
                                         }   
                                     } //Wednesday Close Tag

                            else if($day_of_week === 'Thursday'){
                                $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                                    $RunAtt = mysqli_query($conn, $CheckAtt);
                                    if (mysqli_num_rows($RunAtt) > 0) {
                                        $rowAtt = mysqli_fetch_assoc($RunAtt);
                                        $fetch_timein = $rowAtt['time_in'];
                                        $fetch_late = $rowAtt['late'];                                    
                                    

                                    if($fetch_timein != '00:00:00'){
                                       $existing_timein = new DateTime($fetch_timein);
                                       $file_out = new DateTime($endtime);
                                       $late_datetime = new DateTime($fetch_late);

                                       $lunchbreak_start = new DateTime('12:00:00');
                                       $lunchbreak_end = new DateTime('13:00:00');  

                                       $SchedTimeIn = new DateTime($row_sched_tb['thurs_timein']);
                                       $SchedTimeOut = new DateTime($row_sched_tb['thurs_timeout']);
                                    
                                        //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                        if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start) {
                                                $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // $total_under = (new DateTime($undertime_total))->diff($late_datetime)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }else{
                                               $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                // $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }
                                    }else{
                                        $total_work = "00:00:00";
                                    }  
                                        $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                                        `early_out` = '$total_undertime', 
                                        `total_work`='$total_work' 
                                         WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                        $result = mysqli_query($conn, $sql);
                                        if($result){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                                                    //     //Syntax sa email notification
                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                        
                                                        $EmpApproverArray = array();
                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                        }

                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                        $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                            <p>Your undertime request on $date_under is approved</p>
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
                                        } else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }    
                                } else {
                                        $underQuery = "INSERT INTO `attendances`(`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) 
                                        VALUES ('', '$employeeid', '$date_under', '00:00:00', '$endtime', '00:00:00', '$total_undertime', '00:00:00', '00:00:00', '00:00:00')";
                                        $underRun = mysqli_query($conn, $underQuery);

                                        if($underRun){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
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

                                                    $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                        <p>Your undertime request on $date_under is approved</p>
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
                                           }
                                         }
                                   } //Thursday Close Tag

                                    else if($day_of_week === 'Friday'){   
                                    $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                                    $RunAtt = mysqli_query($conn, $CheckAtt);
                                    if (mysqli_num_rows($RunAtt) > 0) {
                                        $rowAtt = mysqli_fetch_assoc($RunAtt);
                                        $fetch_timein = $rowAtt['time_in'];
                                        $fetch_late = $rowAtt['late'];                                    
                                    

                                    if($fetch_timein != '00:00:00'){
                                       $existing_timein = new DateTime($fetch_timein);
                                       $file_out = new DateTime($endtime);
                                       $late_datetime = new DateTime($fetch_late);

                                       $lunchbreak_start = new DateTime('12:00:00');
                                       $lunchbreak_end = new DateTime('13:00:00');  

                                       $SchedTimeIn = new DateTime($row_sched_tb['fri_timein']);
                                       $SchedTimeOut = new DateTime($row_sched_tb['fri_timeout']);
                                    
                                        //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                        if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start) {
                                                $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // $total_under = (new DateTime($undertime_total))->diff($late_datetime)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }else{
                                               $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                // Subtract 1 hour from total work
                                                $total_under_datetime = new DateTime($undertime_total);
                                                // $total_under_datetime->sub(new DateInterval('PT1H'));
                                                $total_work = $total_under_datetime->format('H:i:s');
                                        }
                                    }else{
                                        $total_work = "00:00:00";
                                    }  
                                        $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                                        `early_out` = '$total_undertime', 
                                        `total_work`='$total_work' 
                                         WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                        $result = mysqli_query($conn, $sql);
                                        if($result){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                                                    //     //Syntax sa email notification
                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                        
                                                        $EmpApproverArray = array();
                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                        }

                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                        $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                            <p>Your undertime request on $date_under is approved</p>
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
                                        } else {
                                            echo "Failed: " . mysqli_error($conn);
                                        }    
                                } else {
                                        $underQuery = "INSERT INTO `attendances`(`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) 
                                        VALUES ('', '$employeeid', '$date_under', '00:00:00', '$endtime', '00:00:00', '$total_undertime', '00:00:00', '00:00:00', '00:00:00')";
                                        $underRun = mysqli_query($conn, $underQuery);

                                        if($underRun){
                                            $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                            $query_run = mysqli_query($conn, $sql);
                                                if($query_run){
                                                    header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
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

                                                    $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                        <p>Your undertime request on $date_under is approved</p>
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
                                           }
                                         }
                                       } //Friday Close Tag


                                        else if($day_of_week === 'Saturday'){   
                                        $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                                        $RunAtt = mysqli_query($conn, $CheckAtt);
                                        if (mysqli_num_rows($RunAtt) > 0) {
                                            $rowAtt = mysqli_fetch_assoc($RunAtt);
                                            $fetch_timein = $rowAtt['time_in'];
                                            $fetch_late = $rowAtt['late'];                                     

                                        if($fetch_timein != '00:00:00'){
                                        $existing_timein = new DateTime($fetch_timein);
                                        $file_out = new DateTime($endtime);
                                        $late_datetime = new DateTime($fetch_late);

                                        $lunchbreak_start = new DateTime('12:00:00');
                                        $lunchbreak_end = new DateTime('13:00:00');  

                                        $SchedTimeIn = new DateTime($row_sched_tb['sat_timein']);
                                        $SchedTimeOut = new DateTime($row_sched_tb['sat_timeout']);
                                        
                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                            if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start) {
                                                    $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                    // $total_under = (new DateTime($undertime_total))->diff($late_datetime)->format('%H:%I:%S');
                                                    // Subtract 1 hour from total work
                                                    $total_under_datetime = new DateTime($undertime_total);
                                                    $total_under_datetime->sub(new DateInterval('PT1H'));
                                                    $total_work = $total_under_datetime->format('H:i:s');
                                            }else{
                                                $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                    // Subtract 1 hour from total work
                                                    $total_under_datetime = new DateTime($undertime_total);
                                                    // $total_under_datetime->sub(new DateInterval('PT1H'));
                                                    $total_work = $total_under_datetime->format('H:i:s');
                                            }
                                        }else{
                                            $total_work = "00:00:00";
                                        }  
                                            $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                                            `early_out` = '$total_undertime', 
                                            `total_work`='$total_work' 
                                            WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                            $result = mysqli_query($conn, $sql);
                                            if($result){
                                                $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                                                        //     //Syntax sa email notification
                                                            $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                            $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                            
                                                            $EmpApproverArray = array();
                                                            while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                $EmployeeApprover = $EmployeeRow['empid'];

                                                                $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                            }

                                                            foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                            $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                            $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                                <p>Your undertime request on $date_under is approved</p>
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
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                            }    
                                    } else {
                                            $underQuery = "INSERT INTO `attendances`(`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) 
                                            VALUES ('', '$employeeid', '$date_under', '00:00:00', '$endtime', '00:00:00', '$total_undertime', '00:00:00', '00:00:00', '00:00:00')";
                                            $underRun = mysqli_query($conn, $underQuery);

                                            if($underRun){
                                                $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
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

                                                        $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                            <p>Your undertime request on $date_under is approved</p>
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
                                              }
                                           }
                                        } //Saturday Close Tag


                                    else if($day_of_week === 'Sunday'){
                                        $CheckAtt = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_under' AND `status` = 'Present'";
                                        $RunAtt = mysqli_query($conn, $CheckAtt);
                                        if (mysqli_num_rows($RunAtt) > 0) {
                                            $rowAtt = mysqli_fetch_assoc($RunAtt);
                                            $fetch_timein = $rowAtt['time_in'];
                                            $fetch_late = $rowAtt['late'];                                     

                                        if($fetch_timein != '00:00:00'){
                                        $existing_timein = new DateTime($fetch_timein);
                                        $file_out = new DateTime($endtime);
                                        $late_datetime = new DateTime($fetch_late);

                                        $lunchbreak_start = new DateTime('12:00:00');
                                        $lunchbreak_end = new DateTime('13:00:00');  

                                        $SchedTimeIn = new DateTime($row_sched_tb['sun_timein']);
                                        $SchedTimeOut = new DateTime($row_sched_tb['sun_timeout']);
                                        
                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                            if($existing_timein < $lunchbreak_start && $file_out > $lunchbreak_start) {
                                                    $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                    // $total_under = (new DateTime($undertime_total))->diff($late_datetime)->format('%H:%I:%S');
                                                    // Subtract 1 hour from total work
                                                    $total_under_datetime = new DateTime($undertime_total);
                                                    $total_under_datetime->sub(new DateInterval('PT1H'));
                                                    $total_work = $total_under_datetime->format('H:i:s');
                                            }else{
                                                    $undertime_total = $file_out->diff($SchedTimeIn)->format('%H:%I:%S');
                                                    // Subtract 1 hour from total work
                                                    $total_under_datetime = new DateTime($undertime_total);
                                                    // $total_under_datetime->sub(new DateInterval('PT1H'));
                                                    $total_work = $total_under_datetime->format('H:i:s');
                                            }
                                        }else{
                                            $total_work = "00:00:00";
                                        }  
                                            $sql = "UPDATE attendances SET `time_out` = '$endtime', 
                                            `early_out` = '$total_undertime', 
                                            `total_work`='$total_work' 
                                            WHERE `empid` = '$employeeid' AND `date` = '$date_under'";
                                            $result = mysqli_query($conn, $sql);
                                            if($result){
                                                $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
                                                        //     //Syntax sa email notification
                                                            $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                            $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                            
                                                            $EmpApproverArray = array();
                                                            while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                $EmployeeApprover = $EmployeeRow['empid'];

                                                                $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                            }

                                                            foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                            $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                            $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                                <p>Your undertime request on $date_under is approved</p>
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
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                            }    
                                    } else {
                                            $underQuery = "INSERT INTO `attendances`(`status`, `empid`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work`, `total_rest`) 
                                            VALUES ('', '$employeeid', '$date_under', '00:00:00', '$endtime', '00:00:00', '$total_undertime', '00:00:00', '00:00:00', '00:00:00')";
                                            $underRun = mysqli_query($conn, $underQuery);

                                            if($underRun){
                                                $sql = "UPDATE undertime_tb SET `status` ='Approved' WHERE `id`='$column_id'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../undertime_req.php?msg=You Approved this Request Successfully");
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

                                                        $selectOT = "SELECT * FROM undertime_tb WHERE id = '$column_id' AND empid = '$EmpMail'";  
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
                                                            <p>Your undertime request on $date_under is approved</p>
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
                                              }
                                           }
                                    } //Sunday Close Tag

        }
    }  
 } //Approve button Close Tag

 /************************* For Reject Button ***************************/
if(isset($_POST['reject_btn']))
{

    $column_id = $_POST['id_check'];

    $result_under = mysqli_query($conn, " SELECT * FROM undertime_tb WHERE id = '$column_id'");
    if(mysqli_num_rows($result_under) > 0) {
        $row_under = mysqli_fetch_assoc($result_under);
    }
    $employeeid = $row_under['empid'];
    $date_under = $row_under['date'];
    $starttime = $row_under['start_time'];
    $endtime = $row_under['end_time'];
    $total_undertime = $row_under['total_undertime'];
    $status_under = $row_under['status'];
    
    if($status_under === 'Approved'){
        header("Location: ../../undertime_req.php?error=You cannot REJECT a request that is already APPROVED");
    }
    else if($status_under === 'Rejected'){
        header("Location: ../../undertime_req.php?error=You cannot REJECT a request that is already REJECTED");
    }else{
        $query = "UPDATE undertime_tb SET `status` ='Rejected' WHERE `id`='$column_id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run){
            header("Location: ../../undertime_req.php?msg=You Rejected this Request");
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

            $selectUT = "SELECT * FROM undertime_tb WHERE id= '$column_id' AND empid = '$EmpMail'";
            $approvedUTRun = mysqli_query($conn, $selectUT);

            $ApprovedArray = array();
            while ($ApprovedRow = mysqli_fetch_assoc($approvedUTRun)) {
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
        }else{
            echo "Failed: " . mysqli_error($conn);
        }
    
    }
   
}
/************************* End of Reject Button ***************************/

?>    