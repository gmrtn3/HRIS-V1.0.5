
<?php
session_start();
    include '../../config.php';
    $employeeID = $_SESSION['empid'];

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../../phpmailer/src/Exception.php';
    require '../../../phpmailer/src/PHPMailer.php';
    require '../../../phpmailer/src/SMTP.php';
     #--------------------------------START IF SA HALFDAY FIRST OR SECOND HALF--------------------------------
     if (isset($_POST['firstHalf'])) {

        
                                $empname = $_POST["name_emp"];
                                $leave_type =  $_POST['name_LeaveT'];
                                $leave_period = $_POST['firstHalf'];
                                $str_date = $_POST['name_STRdate'];
                                $end_date = $_POST['name_ENDdate'];
                                $reason_txt = $_POST['name_txtRSN'];

                                    //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave
                                        $result_empsched = mysqli_query($conn, "SELECT
                                            *  
                                        FROM
                                            empschedule_tb
                                        WHERE empid = $empname");
                                        if(mysqli_num_rows($result_empsched) > 0) {                                           
                                            $row__schedEMP= mysqli_fetch_assoc($result_empsched);
                                           
                                            //to validate the dates if not in set date schedule there will be a validation
                                            $row_sched_from_timestamp = strtotime($row__schedEMP['sched_from']);
                                            $row_sched_to_timestamp = strtotime($row__schedEMP['sched_to']);
                                            $str_date_timestamp = strtotime($str_date);
                                            $end_date_timestamp = strtotime($end_date);

                                            // Check if the given range falls outside the schedule range
                                            if ($str_date_timestamp < $row_sched_from_timestamp || $end_date_timestamp > $row_sched_to_timestamp) {
                                                header("Location: ../../leavereq.php?error=You cannot Request a Leave that is out from your schedule.");
                                            } else {
                                                $sched_name = $row__schedEMP['schedule_name'];
                                                        // --------------break------------//
                                                    $result_sched = mysqli_query($conn, "SELECT
                                                        *  
                                                    FROM
                                                        schedule_tb
                                                    WHERE `schedule_name` = '$sched_name'");
                                                    if(mysqli_num_rows($result_sched) > 0) {
                                                        $row_sched= mysqli_fetch_assoc($result_sched);



                                                                    //Para sa pag select ng mga data galing sa LEAVE INFO TABLE
                                                                $result_leaveINFO = mysqli_query($conn, "SELECT
                                                                *  
                                                                FROM
                                                                leaveinfo_tb
                                                                WHERE col_empID = $empname");
                                                                if(mysqli_num_rows($result_leaveINFO) > 0) {
                                                                $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                                                                //echo $row__leaveINFO['col_vctionCrdt'];
                                                                } else {
                                                                echo "No results found.";
                                                                }
                                                                //Para sa pag select ng mga data galing sa LEAVE INFO TABLE (END)


                                                                //------------------------------------------------START VALIDATION ONLY ONE REQUEST-----------------------------------------------------

                                                                //Para sa pag select ng mga data galing sa apply leave TABLE  (PARA MAG CHECK IF EXIST) DIto CHESTAH
                                                                $result_leaveINFO = mysqli_query($conn, " SELECT
                                                                *  
                                                                FROM
                                                                applyleave_tb
                                                                WHERE `col_req_emp` = $empname
                                                                AND `col_LeavePeriod` = '$leave_period'
                                                                AND (`col_status` = 'Pending' OR `col_status` = 'Approved')
                                                                AND ('$str_date' BETWEEN `col_strDate` AND `col_endDate` 
                                                                OR '$end_date' BETWEEN `col_strDate` AND `col_endDate`)");
                                                                if(mysqli_num_rows($result_leaveINFO) > 0) {
                                                                $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                                                                
                                                                    header("Location: ../../leavereq.php?error= Cannot Apply due to the selected dates is already taken by your past requests.");
                                                                } else {       
                                                                if (isset($_POST['name_wthPay'])) {
                                                                    // CAN LEAVE WITH PAY
                                                                    $minusVacationCredits = $row__leaveINFO['col_vctionCrdt'] - 0.5;
                                                                    $minusSickCredits = $row__leaveINFO['col_sickCrdt'] - 0.5;
                                                                    $minusBvrvmntCredits = $row__leaveINFO['col_brvmntCrdt'] - 0.5;
                        

                                                                    if($leave_type == 'Vacation Leave'){
                                                                        if($minusVacationCredits < 0 ){
                                                                            header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Vacation Leave");
                                                                        }
                                                                        else{
                                                                             #sql query to insert into database
                                                                             $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                                                             VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending', '0.5')";

                                                                             if(mysqli_query($conn,$sql)){
                                                                             header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                                    //Query sa pagemail ng request
                                                                                    $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                                                                    $approverRun = mysqli_query($conn, $approverQuery);
                                                                                    
                                                                                    $ApproverArray = array();
                                                                                    while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                                                        $approverEmpid = $ApproverRow['approver_empid'];

                                                                                        $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                                                                    }

                                                                                    foreach ($ApproverArray as $ApproverEmail) {
                                                                                    $approverMail = $ApproverEmail['approverEmpid'];

                                                                                    $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                                                                    $employeeRun = mysqli_query($conn, $employeeQuery);

                                                                                    $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                                                                    $to = $EmployeeEmail['email'];
                                                                                    $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                                                                    // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                                                                    // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                                                                    $message = "
                                                                                    <html>
                                                                                    <head>
                                                                                    <title>{$subject}</title>
                                                                                    </head>
                                                                                    <body>
                                                                                    <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                                                                    <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                                                             else{
                                                                             echo "Error";
                                                                             }
                                                                            }
                                                                    } //end if statement in Vacation
                                                                    elseif($leave_type == 'Bereavement Leave'){
                                                                        if($minusBvrvmntCredits < 0 ){
                                                                            header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Bereavement Leave");
                                                                        }
                                                                        else{
                                                                                #sql query to insert into database
                                                                                $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status` , `col_credit`) 
                                                                                VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending' ,'0.5' )";

                                                                                if(mysqli_query($conn,$sql)){
                                                                                header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                                    //Query sa pagemail ng request
                                                                                    $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                                                                    $approverRun = mysqli_query($conn, $approverQuery);
                                                                                    
                                                                                    $ApproverArray = array();
                                                                                    while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                                                        $approverEmpid = $ApproverRow['approver_empid'];

                                                                                        $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                                                                    }

                                                                                    foreach ($ApproverArray as $ApproverEmail) {
                                                                                    $approverMail = $ApproverEmail['approverEmpid'];

                                                                                    $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                                                                    $employeeRun = mysqli_query($conn, $employeeQuery);

                                                                                    $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                                                                    $to = $EmployeeEmail['email'];
                                                                                    $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                                                                    // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                                                                    // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                                                                    $message = "
                                                                                    <html>
                                                                                    <head>
                                                                                    <title>{$subject}</title>
                                                                                    </head>
                                                                                    <body>
                                                                                    <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                                                                    <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                                                                else{
                                                                                echo "Error";
                                                                                }
                                                                            }
                                                                    } //end if statement in Bereavement Leave
                                                                    elseif($leave_type == 'Sick Leave'){
                                                                        if($minusSickCredits < 0 ){
                                                                            header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Sick Leave");
                                                                        }
                                                                        else{
                                                                                #sql query to insert into database
                                                                                $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                                                                VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending', '0.5')";

                                                                                if(mysqli_query($conn,$sql)){
                                                                                header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                                    //Query sa pagemail ng request
                                                                                    $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                                                                    $approverRun = mysqli_query($conn, $approverQuery);
                                                                                    
                                                                                    $ApproverArray = array();
                                                                                    while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                                                        $approverEmpid = $ApproverRow['approver_empid'];

                                                                                        $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                                                                    }

                                                                                    foreach ($ApproverArray as $ApproverEmail) {
                                                                                    $approverMail = $ApproverEmail['approverEmpid'];

                                                                                    $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                                                                    $employeeRun = mysqli_query($conn, $employeeQuery);

                                                                                    $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                                                                    $to = $EmployeeEmail['email'];
                                                                                    $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                                                                    // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                                                                    // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                                                                    $message = "
                                                                                    <html>
                                                                                    <head>
                                                                                    <title>{$subject}</title>
                                                                                    </head>
                                                                                    <body>
                                                                                    <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                                                                    <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                                                                else{
                                                                                echo "Error";
                                                                                }
                                                                            }
                                                                    } //end if statement in Sick Leave
                                                                }else{
                                                                    //else CAN LEAVE BUT NO PAY
                                                                    
                                                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status` , `col_credit`) 
                                                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','Without Pay', 'Pending', '0')";

                                                                        if(mysqli_query($conn,$sql)){
                                                                            header("Location: ../../leavereq.php?msg=Successfully Added");
                                                                                    //Query sa pagemail ng request
                                                                                    $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                                                                    $approverRun = mysqli_query($conn, $approverQuery);
                                                                                    
                                                                                    $ApproverArray = array();
                                                                                    while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                                                        $approverEmpid = $ApproverRow['approver_empid'];

                                                                                        $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                                                                    }

                                                                                    foreach ($ApproverArray as $ApproverEmail) {
                                                                                    $approverMail = $ApproverEmail['approverEmpid'];

                                                                                    $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                                                                    $employeeRun = mysqli_query($conn, $employeeQuery);

                                                                                    $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                                                                    $to = $EmployeeEmail['email'];
                                                                                    $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                                                                    // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                                                                    // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                                                                    $message = "
                                                                                    <html>
                                                                                    <head>
                                                                                    <title>{$subject}</title>
                                                                                    </head>
                                                                                    <body>
                                                                                    <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                                                                    <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                                                        else{
                                                                            echo "Error";
                                                                        }
                                                                }//END ISSET WITHPAY                                             
                                                                                

                                                                }

                                                                //Para sa pag select ng mga data galing sa apply leave TABLE  (PARA MAG CHECK IF EXIST)
                                                       
                                                    
                                                    }// end schedule tb
                                                    else {
                                                        header("Location: ../../leavereq.php?error=You cannot Request a Leave without an assigned schedule.");
                                                    }
                                            }// end para validate if out of range sa schedule ay d maka request
                                                                                        
                                                
                                                    
                                        } //end empschedule tb
                                        else {
                                            header("Location: ../../leavereq.php?error=You cannot Request a Leave without an assigned schedule.");
                                        } //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave (END)
                                
                            //------------------------------------------------START VALIDATION ONLY ONE REQUEST END-----------------------------------------------------
                            

        }
    else if (isset($_POST['secondHalf'])) {
       
                                $empname = $_POST["name_emp"];
                                $leave_type =  $_POST['name_LeaveT'];
                                $leave_period = $_POST['secondHalf'];
                                $str_date = $_POST['name_STRdate'];
                                $end_date = $_POST['name_ENDdate'];
                                $reason_txt = $_POST['name_txtRSN'];
                                 #file name with a random number so that similar dont get replaced
                                    //     $reason_file = rand(1000,10000)."-" . $_FILES["name_file"]["name"];

                                    //     #temporary file name to store file
                                    //     $tname = $_FILES["name_file"]["tmp_name"];
                                
                                    // #upload directory path
                                    // $uploads_dir = 'file_reason';
                                    // #TO move the uploaded file to specific location
                                    // move_uploaded_file($tname, $uploads_dir.'/'.$reason_file);

                                    if(isset($_FILES['name_file']) && $_FILES['name_file']['error'] == 0) {
                                        $contents = file_get_contents($_FILES['name_file']['tmp_name']);
                                        $escaped_contents = mysqli_real_escape_string($conn, $contents);
                                    } else {
                                        $escaped_contents = "";
                                    }



    //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave
            $result_empsched = mysqli_query($conn, "SELECT
            *  
            FROM
            empschedule_tb
            WHERE empid = $empname");
            if(mysqli_num_rows($result_empsched) > 0) {
            $row__schedEMP= mysqli_fetch_assoc($result_empsched);

            //to validate the dates if not in set date schedule there will be a validation
            $row_sched_from_timestamp = strtotime($row__schedEMP['sched_from']);
            $row_sched_to_timestamp = strtotime($row__schedEMP['sched_to']);
            $str_date_timestamp = strtotime($str_date);
            $end_date_timestamp = strtotime($end_date);

            // Check if the given range falls outside the schedule range
            if ($str_date_timestamp < $row_sched_from_timestamp || $end_date_timestamp > $row_sched_to_timestamp) {
                header("Location: ../../leavereq.php?error=You cannot Request a Leave that is out from your schedule.");
            } else {
                    //Para sa pag select ng mga data galing sa LEAVE INFO TABLE
                    $result_leaveINFO = mysqli_query($conn, "SELECT
                    *  
                    FROM
                    leaveinfo_tb
                    WHERE col_empID = $empname");
                    if(mysqli_num_rows($result_leaveINFO) > 0) {
                    $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                    //echo $row__leaveINFO['col_vctionCrdt'];
                    } else {
                    echo "No results found.";
                    }
                    //Para sa pag select ng mga data galing sa LEAVE INFO TABLE (END)


                    //------------------------------------------------START VALIDATION ONLY ONE REQUEST-----------------------------------------------------

                    //Para sa pag select ng mga data galing sa apply leave TABLE  (PARA MAG CHECK IF EXIST) DIto CHESTAH
                    $result_leaveINFO = mysqli_query($conn, " SELECT
                    *  
                    FROM
                    applyleave_tb
                    WHERE `col_req_emp` = $empname
                    AND `col_LeavePeriod` = '$leave_period'
                    AND (`col_status` = 'Pending' OR `col_status` = 'Approved')
                    AND ('$str_date' BETWEEN `col_strDate` AND `col_endDate` 
                    OR '$end_date' BETWEEN `col_strDate` AND `col_endDate`)");
                    if(mysqli_num_rows($result_leaveINFO) > 0) {
                    $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                    //echo  "d maka insert";
                    header("Location: ../../leavereq.php?error= Cannot Apply due to the selected dates is already taken by your past requests.");
                    } else {       
                    if (isset($_POST['name_wthPay'])) {
                        // CAN LEAVE WITH PAY
                        $minusVacationCredits = $row__leaveINFO['col_vctionCrdt'] - 0.5;
                        $minusSickCredits = $row__leaveINFO['col_sickCrdt'] - 0.5;
                        $minusBvrvmntCredits = $row__leaveINFO['col_brvmntCrdt'] - 0.5; 

                        if($leave_type == 'Vacation Leave'){
                            if($minusVacationCredits < 0 ){
                                header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Vacation Leave");
                            }
                            else{
                                
                                    #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending', '0.5')";

                                    if(mysqli_query($conn,$sql)){
                                    header("Location: ../../leavereq.php?msg=Successfully Added");
                                            //Query sa pagemail ng request
                                            $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                            $approverRun = mysqli_query($conn, $approverQuery);
                                            
                                            $ApproverArray = array();
                                            while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                $approverEmpid = $ApproverRow['approver_empid'];

                                                $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                            }

                                            foreach ($ApproverArray as $ApproverEmail) {
                                            $approverMail = $ApproverEmail['approverEmpid'];

                                            $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                            $employeeRun = mysqli_query($conn, $employeeQuery);

                                            $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                            $to = $EmployeeEmail['email'];
                                            $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                            // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                            // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                            $message = "
                                            <html>
                                            <head>
                                            <title>{$subject}</title>
                                            </head>
                                            <body>
                                            <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                            <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                    else{
                                    echo "Error";
                                    }
                                }
                        } //end if statement in Vacation
                        elseif($leave_type == 'Bereavement Leave'){
                            if($minusBvrvmntCredits < 0 ){
                                header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Bereavement Leave");
                            }
                            else{
                                    #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending', '0.5')";

                                    if(mysqli_query($conn,$sql)){
                                    header("Location: ../../leavereq.php?msg=Successfully Added");
                                    //Query sa pagemail ng request
                                            $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                            $approverRun = mysqli_query($conn, $approverQuery);
                                            
                                            $ApproverArray = array();
                                            while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                $approverEmpid = $ApproverRow['approver_empid'];

                                                $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                            }

                                            foreach ($ApproverArray as $ApproverEmail) {
                                            $approverMail = $ApproverEmail['approverEmpid'];

                                            $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                            $employeeRun = mysqli_query($conn, $employeeQuery);

                                            $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                            $to = $EmployeeEmail['email'];
                                            $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                            // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                            // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                            $message = "
                                            <html>
                                            <head>
                                            <title>{$subject}</title>
                                            </head>
                                            <body>
                                            <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                            <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                    else{
                                    echo "Error";
                                    }
                                }
                        } //end if statement in Bereavement Leave
                        elseif($leave_type == 'Sick Leave'){
                            if($minusSickCredits < 0 ){
                                header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Sick Leave");
                            }
                            else{
                                    #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending', '0.5')";

                                    if(mysqli_query($conn,$sql)){
                                    header("Location: ../../leavereq.php?msg=Successfully Added");
                                    //Query sa pagemail ng request
                                            $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                            $approverRun = mysqli_query($conn, $approverQuery);
                                            
                                            $ApproverArray = array();
                                            while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                $approverEmpid = $ApproverRow['approver_empid'];

                                                $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                            }

                                            foreach ($ApproverArray as $ApproverEmail) {
                                            $approverMail = $ApproverEmail['approverEmpid'];

                                            $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                            $employeeRun = mysqli_query($conn, $employeeQuery);

                                            $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                            $to = $EmployeeEmail['email'];
                                            $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                            // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                            // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                            $message = "
                                            <html>
                                            <head>
                                            <title>{$subject}</title>
                                            </head>
                                            <body>
                                            <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                            <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                    else{
                                    echo "Error";
                                    }
                                }
                        } //end if statement in Sick Leave
                    }else{
                        //else CAN LEAVE BUT NO PAY
                        #sql query to insert into database
                        $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                        VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','Without Pay', 'Pending', '0')";

                            if(mysqli_query($conn,$sql)){
                            header("Location: ../../leavereq.php?msg=Successfully Added");
                                            //Query sa pagemail ng request
                                            $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                            $approverRun = mysqli_query($conn, $approverQuery);
                                            
                                            $ApproverArray = array();
                                            while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                $approverEmpid = $ApproverRow['approver_empid'];

                                                $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                            }

                                            foreach ($ApproverArray as $ApproverEmail) {
                                            $approverMail = $ApproverEmail['approverEmpid'];

                                            $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                            $employeeRun = mysqli_query($conn, $employeeQuery);

                                            $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                            $to = $EmployeeEmail['email'];
                                            $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                            // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                            // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                            $message = "
                                            <html>
                                            <head>
                                            <title>{$subject}</title>
                                            </head>
                                            <body>
                                            <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                            <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                            else{
                                echo "Error";
                            }
                    } //END ISSET WITHPAY                                        
                                

                    }
                    //Para sa pag select ng mga data galing sa apply leave TABLE (END)
            } //END para mag validate ng request if pasok paba sa senet na schedue date range if hindi, ay d  maka request

                    



    } else {
        header("Location: ../../leavereq.php?error=You cannot Request a Leave without an assigned schedule.");
    } //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave (END)



                            




    //------------------------------------------------START VALIDATION ONLY ONE REQUEST END-----------------------------------------------------

    }#--------------------------------END IF SA HALFDAY FIRST OR SECOND HALF--------------------------------
    else { //------------------------------------pARA if full day
         
        $empname = $_POST["name_emp"];
        $leave_type =  $_POST['name_LeaveT'];
        $leave_period = $_POST['name_LeaveP'];
        $str_date = $_POST['name_STRdate'];
        $end_date = $_POST['name_ENDdate'];
        $reason_txt = $_POST['name_txtRSN'];
        #file name with a random number so that similar dont get replaced
    //     $reason_file = rand(1000,10000)."-" . $_FILES["name_file"]["name"];

    //     #temporary file name to store file
    //     $tname = $_FILES["name_file"]["tmp_name"];
   
    // #upload directory path
    // $uploads_dir = 'file_reason';
    // #TO move the uploaded file to specific location
    // move_uploaded_file($tname, $uploads_dir.'/'.$reason_file);

    if(isset($_FILES['name_file']) && $_FILES['name_file']['error'] == 0) {
        $contents = file_get_contents($_FILES['name_file']['tmp_name']);
        $escaped_contents = mysqli_real_escape_string($conn, $contents);
    } else {
        $escaped_contents = "";
    }



    //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave
        $result_empsched = mysqli_query($conn, "SELECT
        *  
        FROM
        empschedule_tb
        WHERE empid = $empname");
        if(mysqli_num_rows($result_empsched) > 0) {
        $row__schedEMP = mysqli_fetch_assoc($result_empsched);
        $empSched_name = $row__schedEMP['schedule_name'];

          //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule)
          $result_Sched = mysqli_query($conn, " SELECT
            *  
            FROM
            `schedule_tb`
            WHERE `schedule_name`=  '$empSched_name'");
            $row_Sched = mysqli_fetch_assoc($result_Sched);
            //echo $row_Sched['schedule_name'];
     
  //Para sa pag select ng mga data galing sa Sched TABLE (Para kunin ang data ng mga schedule) (END)

         //to validate the dates if not in set date schedule there will be a validation
         $row_sched_from_timestamp = strtotime($row__schedEMP['sched_from']);
         $row_sched_to_timestamp = strtotime($row__schedEMP['sched_to']);
         $str_date_timestamp = strtotime($str_date);
         $end_date_timestamp = strtotime($end_date);

         // Check if the given range falls outside the schedule range
         if ($str_date_timestamp < $row_sched_from_timestamp || $end_date_timestamp > $row_sched_to_timestamp) {
             header("Location: ../../leavereq.php?error=You cannot Request a Leave that is out from your schedule.");
         } else {
                //Para sa pag select ng mga data galing sa LEAVE INFO TABLE
                $result_leaveINFO = mysqli_query($conn, "SELECT
                    *  
                FROM
                    leaveinfo_tb
                WHERE col_empID = $empname");
                if(mysqli_num_rows($result_leaveINFO) > 0) {
                    $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                    //echo $row__leaveINFO['col_vctionCrdt'];
                } else {
                    echo "No results found.";
                }
                //Para sa pag select ng mga data galing sa LEAVE INFO TABLE (END)


                //------------------------------------------------START VALIDATION ONLY ONE REQUEST-----------------------------------------------------
                //Para sa pag select ng mga data galing sa apply leave TABLE 
                $result_leaveINFO = mysqli_query($conn, " SELECT
                        *  
                FROM
                                            applyleave_tb
                                        WHERE `col_req_emp` = $empname
                                        AND `col_LeavePeriod` = '$leave_period'
                                        AND (`col_status` = 'Pending' OR `col_status` = 'Approved')
                                        AND ('$str_date' BETWEEN `col_strDate` AND `col_endDate` 
                                        OR '$end_date' BETWEEN `col_strDate` AND `col_endDate`)");
                    if(mysqli_num_rows($result_leaveINFO) > 0) {
                                            $row__leaveINFO = mysqli_fetch_assoc($result_leaveINFO);
                                            //echo  "d maka insert";
                                        header("Location: ../../leavereq.php?error= Cannot Apply due to the selected dates is already taken by your past requests.");
                } else {

                        if (isset($_POST['name_wthPay'])) {
                            //---------------BREAK START IF FULLDAY ANG REQUEST----------------
                            // CAN LEAVE WITH PAY
                            $date1 = new DateTime($str_date); // value ng start date 
                            $date2 = new DateTime($end_date); // value ng end date 
                            $interval = $date1->diff($date2);
                            $numberOfDays = $interval->days + 1;
                           


//FOR inserting credits TO COMPUTE THE TOTAL WORKING DAYS IN LEAVE
                                    
// $startDate = new DateTime($str_date);
// $endDate = new DateTime($end_date);

// Create an empty array to store the dates
$dates = array();

// Clone the start date to avoid modifying it directly
$currentDate = clone $date1;

// Loop until the current date reaches the end date
while ($currentDate <= $date2) {
    $dates[] = $currentDate->format('Y-m-d');
    $currentDate->modify('+1 day');
   
}

@$working_days = 0;
foreach ($dates as $date) {

    $day_of_week = date('l', strtotime($date));//convert the each date to day
    // echo $date . " = " . $day_of_week ."<br> <br>";

    if($day_of_week === 'Monday'){
         // -----------------------BREAK MONDAY START----------------------------//
         if($row_Sched['mon_timein'] == NULL || $row_Sched['mon_timein'] == ''){
              @$sum += 0; // used for allowance in payrolldd
        }else{
              @$sum += 1; // used for allowance in payrolldd
        }
    }
       
        // -----------------------BREAK MONDAY START----------------------------//

        // -----------------------BREAK Tuesday START----------------------------//

        if($day_of_week === 'Tuesday'){
            if($row_Sched['tues_timein'] == NULL || $row_Sched['tues_timein'] == ''){
                @$sum += 0; // used for allowance in payrolldd
            }else{
                @$sum += 1; // used for allowance in payrolldd
            }
        }

           
        // -----------------------BREAK Tuesday END----------------------------//

        // -----------------------BREAK WEDNESDAY START----------------------------//
        if($day_of_week === 'Wednesday'){
            if($row_Sched['wed_timein'] == NULL || $row_Sched['wed_timein'] == ''){
                @$sum += 0; // used for allowance in payrolldd
            }else{
                @$sum += 1; // used for allowance in payrolldd
            }
        }
              

                
        // -----------------------BREAK WEDNESDAY END----------------------------//

        // -----------------------BREAK THURSDAY START----------------------------//

        if($day_of_week === 'Thursday'){
            if($row_Sched['thurs_timein'] == NULL || $row_Sched['thurs_timein'] == ''){                                                            
                @$sum += 0; // used for allowance in payrolldd
            }else{
                @$sum += 1; // used for allowance in payrolldd
            }
        }
            
        // -----------------------BREAK THURSDAY END----------------------------//


        // -----------------------BREAK FRIDAY START----------------------------//

        if($day_of_week === 'Friday'){
            if($row_Sched['fri_timein'] == NULL || $row_Sched['fri_timein'] == ''){
                  @$sum += 0; // used for allowance in payrolldd
            }else{
                  @$sum += 1; // used for allowance in payrolldd
            }
        }
        


        // -----------------------BREAK FRIDAY END----------------------------//


        // -----------------------BREAK Saturday START----------------------------//

        if($day_of_week === 'Saturday'){
            if($row_Sched['sat_timein'] == NULL || $row_Sched['sat_timein'] == ''){
                @$sum += 0; // used for allowance in payrolldd
            }else{
                @$sum += 1; // used for allowance in payrolldd
            }
        }
       

        // -----------------------BREAK Saturday END----------------------------//

        // -----------------------BREAK SUNDAY START----------------------------//
        if($day_of_week === 'Sunday'){
            if($row_Sched['sun_timein'] == NULL || $row_Sched['sun_timein'] == ''){
                @$sum += 0; // used for allowance in payrolldd
            }else{
                @$sum += 1; // used for allowance in payrolldd
            }
        }
       

        // -----------------------BREAK SUNDAY END----------------------------//
        


}//end foreach
 
$working_days_ofLEave += $sum; //SUM OF WORKING DAYS IN SCHEDULE USED IN COMPUTING ALLOWANCE 
$minusVacationCredits = $row__leaveINFO['col_vctionCrdt'] - $working_days_ofLEave;
$minusSickCredits = $row__leaveINFO['col_sickCrdt'] - $working_days_ofLEave;
$minusBvrvmntCredits = $row__leaveINFO['col_brvmntCrdt'] - $working_days_ofLEave;
                  
                            if($leave_type == 'Vacation Leave'){
                                if($minusVacationCredits < 0 ){
                                    header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Vacation Leave");
                                }
                                else{
                                        #sql query to insert into database
                                        $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                        VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending', '$working_days_ofLEave')";
                    
                                        if(mysqli_query($conn,$sql)){
                                            header("Location: ../../leavereq.php?msg=Successfully Added");
                                                //Query sa pagemail ng request
                                                $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                                $approverRun = mysqli_query($conn, $approverQuery);
                                                
                                                $ApproverArray = array();
                                                while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                    $approverEmpid = $ApproverRow['approver_empid'];

                                                    $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                                }

                                                foreach ($ApproverArray as $ApproverEmail) {
                                                $approverMail = $ApproverEmail['approverEmpid'];

                                                $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                                $employeeRun = mysqli_query($conn, $employeeQuery);

                                                $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                                $to = $EmployeeEmail['email'];
                                                $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                                // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                                // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                                $message = "
                                                <html>
                                                <head>
                                                <title>{$subject}</title>
                                                </head>
                                                <body>
                                                <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                                <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                        else{
                                            echo "Error";
                                        }
                                    }
                            } //end if statement in Vacation
                            elseif($leave_type == 'Bereavement Leave'){
                                if($minusBvrvmntCredits < 0 ){
                                    header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Bereavement Leave");
                                }
                                else{
                                        #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','With Pay', 'Pending', '$working_days_ofLEave')";
                    
                                        if(mysqli_query($conn,$sql)){
                                            header("Location: ../../leavereq.php?msg=Successfully Added");
                                                //Query sa pagemail ng request
                                                $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                                $approverRun = mysqli_query($conn, $approverQuery);
                                                
                                                $ApproverArray = array();
                                                while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                    $approverEmpid = $ApproverRow['approver_empid'];

                                                    $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                                }

                                                foreach ($ApproverArray as $ApproverEmail) {
                                                $approverMail = $ApproverEmail['approverEmpid'];

                                                $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                                $employeeRun = mysqli_query($conn, $employeeQuery);

                                                $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                                $to = $EmployeeEmail['email'];
                                                $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                                // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                                // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                                $message = "
                                                <html>
                                                <head>
                                                <title>{$subject}</title>
                                                </head>
                                                <body>
                                                <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                                <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                        else{
                                            echo "Error";
                                        } 
                                    }
                            } //end if statement in Bereavement Leave
                            elseif($leave_type == 'Sick Leave'){
                                if($minusSickCredits < 0 ){
                                    header("Location: ../../leavereq.php?error=You cannot apply request from the range date provide. Lack of credits for Sick Leave");
                                }
                                else{
                                        #sql query to insert into database
                                    $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                                    VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents', 'With Pay', 'Pending', '$working_days_ofLEave')";
                    
                                        if(mysqli_query($conn,$sql)){
                                            header("Location: ../../leavereq.php?msg=Successfully Added");
                                                //Query sa pagemail ng request
                                                $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                                $approverRun = mysqli_query($conn, $approverQuery);
                                                
                                                $ApproverArray = array();
                                                while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                    $approverEmpid = $ApproverRow['approver_empid'];

                                                    $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                                }

                                                foreach ($ApproverArray as $ApproverEmail) {
                                                $approverMail = $ApproverEmail['approverEmpid'];

                                                $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                                $employeeRun = mysqli_query($conn, $employeeQuery);

                                                $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                                $to = $EmployeeEmail['email'];
                                                $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                                // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                                // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                                $message = "
                                                <html>
                                                <head>
                                                <title>{$subject}</title>
                                                </head>
                                                <body>
                                                <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                                <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                        else{
                                        echo "Error";
                                        }
                                    }
                            } //end if statement in Sick Leave
                                        //--------------------------BREAK END IF FULLDAY ANG REQUEST---------------------- 

                        }
                        else{ 
                            //else CAN LEAVE BUT NO PAY
                            #sql query to insert into database
                            $sql = "INSERT into applyleave_tb(`col_req_emp`, `col_LeaveType`, `col_LeavePeriod`, `col_strDate`, `col_endDate`, `col_reason`, `col_file`, `col_PAID_LEAVE`, `col_status`, `col_credit`) 
                            VALUES('$empname', '$leave_type', '$leave_period', '$str_date', '$end_date', '$reason_txt', '$escaped_contents','Without Pay', 'Pending', '0')";

                                if(mysqli_query($conn,$sql)){
                                    header("Location: ../../leavereq.php?msg=Successfully Added");
                                            //Query sa pagemail ng request
                                            $approverQuery = "SELECT * FROM approver_tb WHERE empid = '$employeeID'";
                                            $approverRun = mysqli_query($conn, $approverQuery);
                                            
                                            $ApproverArray = array();
                                            while ($ApproverRow = mysqli_fetch_assoc($approverRun)) {
                                                $approverEmpid = $ApproverRow['approver_empid'];

                                                $ApproverArray[] = array('approverEmpid' => $approverEmpid);
                                            }

                                            foreach ($ApproverArray as $ApproverEmail) {
                                            $approverMail = $ApproverEmail['approverEmpid'];

                                            $employeeQuery = "SELECT * FROM employee_tb WHERE empid = '$approverMail'";
                                            $employeeRun = mysqli_query($conn, $employeeQuery);

                                            $EmployeeEmail = mysqli_fetch_assoc($employeeRun);


                                            $to = $EmployeeEmail['email'];
                                            $subject = "EMPLOYEE $employeeID HAS LEAVE REQUEST";
                                            // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                            // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                            $message = "
                                            <html>
                                            <head>
                                            <title>{$subject}</title>
                                            </head>
                                            <body>
                                            <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                            <p>Thanks for allowing me to request a leave, Please read my application on my request</p>
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
                                else{
                                    echo "Error";
                                }

                        } //END ISSET WITHPAY

                                    
                }
                //Para sa pag select ng mga data galing sa apply leave TABLE (END)
         } //END para mag validate ng request if pasok paba sa senet na schedue date range if hindi, ay d  maka request

        } else {
            header("Location: ../../leavereq.php?error=You cannot Request a Leave without an assigned schedule.");
    } //Para sa pag select ng mga data galing sa EMPSCHEDULE to validate if may sched na siya ay pwede na mag request ng leave (END)

        
    }


      
?>