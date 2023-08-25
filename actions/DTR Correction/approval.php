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
if(isset($_POST['approve_btn']))
{

    $tableid = $_POST['input'];

    $result_dtr = mysqli_query($conn, "SELECT * FROM emp_dtr_tb WHERE id = '$tableid'");
    if(mysqli_num_rows($result_dtr) > 0) {
        $row_dtr = mysqli_fetch_assoc($result_dtr);
    }
    $employeeid = $row_dtr['empid'];
    $date_dtr = $row_dtr['date'];
    $time_dtr = $row_dtr['time'];
    $type_dtr = $row_dtr['type'];
    $status_dtr = $row_dtr['status'];

    if($status_dtr === 'Approved'){
        header("Location: ../../dtr_admin.php?error=You cannot APPROVE a request that is already APPROVED");
    }
    else if($status_dtr === 'Rejected'){
        header("Location: ../../dtr_admin.php?error=You cannot APPROVE a request that is already REJECTED");
    } else {
        if($type_dtr === 'IN'){
                        $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeid'");
                         if(mysqli_num_rows($result_emp_sched) > 0) {
                         $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
                         $schedID = $row_emp_sched['schedule_name'];
                    

                        $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
                                if(mysqli_num_rows($result_sched_tb) > 0) {
                                    $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
                                    $sched_name =  $row_sched_tb['schedule_name'];
                                    $col_monday_timein =  $row_sched_tb['mon_timein'];
                                    $col_tuesday_timein =  $row_sched_tb['tues_timein'];
                                    $col_wednesday_timein =  $row_sched_tb['wed_timein'];
                                    $col_thursday_timein =  $row_sched_tb['thurs_timein'];
                                    $col_friday_timein =  $row_sched_tb['fri_timein'];
                                    $col_saturday_timein =  $row_sched_tb['sat_timein'];
                                    $col_sunday_timein =  $row_sched_tb['sun_timein'];
                                    $col_grace_period = $row_sched_tb['grace_period'];

                                    $day_of_week = date('l', strtotime($date_dtr)); // get the day of the week using the "l" format specifier  
  
                                    if($day_of_week === 'Monday'){
                                        $late = '';
                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $fetch_timeout = $row['time_out'];
                                            // echo $fetch_timeout;
                                            if ($fetch_timeout != '00:00:00') {
                                                $time_out_datetime = new DateTime($fetch_timeout);
                                                $time_in_datetime = new DateTime($time_dtr);

                                                $lunchbreak_start = new DateTime('12:00:00');
                                                $lunchbreak_end = new DateTime('13:00:00');

                                                $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                                                $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);

                                                if ($time_in_datetime < $lunchbreak_start) {
                                                    $grace_period_total = new DateTime($col_monday_timein);
                                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                                    if ($grace_period_minutes > 0) {
                                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                        $grace_period_total->add($grace_period_interval);
                                                    }
                                                    if ($grace_period_total < $time_in_datetime) {
                                                        $monday_timeIn = new DateTime($col_monday_timein);
                                                        if (empty($monday_timeIn)) {
                                                            $late = "00:00:00";
                                                        } else {
                                                            
                                                            $late = $time_in_datetime->diff($monday_timeIn)->format('%H:%I:%S');
                                                            $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_work);
                                                            $total_work_datetime->sub(new DateInterval('PT1H'));
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                    } else {
                                                        $total_work = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                        // Subtract 1 hour from total work
                                                        $total_work_schedtime = new DateTime($total_work);
                                                        $total_work_schedtime->sub(new DateInterval('PT1H'));
                                                        $total_work = $total_work_schedtime->format('H:i:s');
                                                    }
                                                } else {
                                                    if($time_in_datetime > $col_monday_timein){
                                                        $scheduled_time = new DateTime($col_monday_timein);
                                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                                        $latetotal = new DateTime($interval);
                                                        $latetotal->sub(new DateInterval('PT1H'));
                                                        $late = $latetotal->format('H:i:s');
                                                    }
                                                    $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                }
                                            } else {
                                                $total_work = "00:00:00";
                                            }
                                        }

                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                        $query_run = mysqli_query($conn, $query);
                        
                                        if(mysqli_num_rows($query_run) > 0) {
                                            $sql = "UPDATE attendances SET `time_in` = '$time_dtr' , `late` = '$late' , `total_work` = ' $total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                            $result = mysqli_query($conn, $sql);
                                        if($result){
                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                    }else{
                                                        echo "Failed: " . mysqli_error($conn);
                                                    } 
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                            }      
                                        } else {
                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                        }
                                    } //Close Bracket day of week with Monday

                                    else if ($day_of_week === 'Tuesday'){
                                        $late = '';
                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $fetch_timeout = $row['time_out'];
                                            // echo $fetch_timeout;
                                            if ($fetch_timeout != '00:00:00') {
                                                $time_out_datetime = new DateTime($fetch_timeout);
                                                $time_in_datetime = new DateTime($time_dtr);

                                                $lunchbreak_start = new DateTime('12:00:00');
                                                $lunchbreak_end = new DateTime('13:00:00');

                                                $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                                $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);

                                                if ($time_in_datetime < $lunchbreak_start) {
                                                    $grace_period_total = new DateTime($col_tuesday_timein);
                                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                                    if ($grace_period_minutes > 0) {
                                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                        $grace_period_total->add($grace_period_interval);
                                                    }
                                                    if ($grace_period_total < $time_in_datetime) {
                                                        $tuesday_timeIn = new DateTime($col_tuesday_timein);
                                                        if (empty($tuesday_timeIn)) {
                                                            $late = "00:00:00";
                                                        } else {
                                                            
                                                            $late = $time_in_datetime->diff($tuesday_timeIn)->format('%H:%I:%S');
                                                            $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_work);
                                                            $total_work_datetime->sub(new DateInterval('PT1H'));
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                    } else {
                                                        $total_work = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                        // Subtract 1 hour from total work
                                                        $total_work_schedtime = new DateTime($total_work);
                                                        $total_work_schedtime->sub(new DateInterval('PT1H'));
                                                        $total_work = $total_work_schedtime->format('H:i:s');
                                                    }
                                                } else {
                                                    if($time_in_datetime > $col_tuesday_timein){
                                                        $scheduled_time = new DateTime($col_tuesday_timein);
                                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                                        $latetotal = new DateTime($interval);
                                                        $latetotal->sub(new DateInterval('PT1H'));
                                                        $late = $latetotal->format('H:i:s');
                                                    }
                                                    $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                }
                                            } else {
                                                $total_work = "00:00:00";
                                            }
                                        }

                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                        $query_run = mysqli_query($conn, $query);
                        
                                        if(mysqli_num_rows($query_run) > 0) {
                                            $sql = "UPDATE attendances SET `time_in` = '$time_dtr' , `late` = '$late' , `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                            $result = mysqli_query($conn, $sql);
                                        if($result){
                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                            //Syntax sa pag-email notification
                                                            $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                            $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                            
                                                            $EmpApproverArray = array();
                                                            while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                $EmployeeApprover = $EmployeeRow['empid'];

                                                                $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                            }

                                                            foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                            $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                            $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                            $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                            $ApprovedArray = array();
                                                            while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                        
                                                                $message = "
                                                                <html>
                                                                <head>
                                                                <title>{$subject}</title>
                                                                </head>
                                                                <body>
                                                                <p><strong>Dear $to,</strong></p>
                                                                <p>Your DTR Correction request for time-in on $date_dtr is approved</p>
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
                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                        }
                                    } //Close Bracket day of week with Tuesday

                                    else if ($day_of_week === 'Wednesday'){
                                        $late = '';
                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $fetch_timeout = $row['time_out'];
                                            // echo $fetch_timeout;
                                            if ($fetch_timeout != '00:00:00') {
                                                $time_out_datetime = new DateTime($fetch_timeout);
                                                $time_in_datetime = new DateTime($time_dtr);

                                                $lunchbreak_start = new DateTime('12:00:00');
                                                $lunchbreak_end = new DateTime('13:00:00');

                                                $SchedTimeIn = new DateTime($row_sched_tb['wed_timein']);
                                                $SchedTimeOut = new DateTime($row_sched_tb['wed_timeout']);

                                                if ($time_in_datetime < $lunchbreak_start) {
                                                    $grace_period_total = new DateTime($col_wednesday_timein);
                                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                                    if ($grace_period_minutes > 0) {
                                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                        $grace_period_total->add($grace_period_interval);
                                                    }
                                                    if ($grace_period_total < $time_in_datetime) {
                                                        $wednesday_timeIn = new DateTime($col_wednesday_timein);
                                                        if (empty($wednesday_timeIn)) {
                                                            $late = "00:00:00";
                                                        } else {
                                                            
                                                            $late = $time_in_datetime->diff($wednesday_timeIn)->format('%H:%I:%S');
                                                            $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_work);
                                                            $total_work_datetime->sub(new DateInterval('PT1H'));
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                    } else {
                                                        $total_work = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                        // Subtract 1 hour from total work
                                                        $total_work_schedtime = new DateTime($total_work);
                                                        $total_work_schedtime->sub(new DateInterval('PT1H'));
                                                        $total_work = $total_work_schedtime->format('H:i:s');
                                                    }
                                                } else {
                                                    if($time_in_datetime > $col_wednesday_timein){
                                                        $scheduled_time = new DateTime($col_wednesday_timein);
                                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                                        $latetotal = new DateTime($interval);
                                                        $latetotal->sub(new DateInterval('PT1H'));
                                                        $late = $latetotal->format('H:i:s');
                                                    }
                                                    $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                }
                                            } else {
                                                $total_work = "00:00:00";
                                            }
                                        }

                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                        $query_run = mysqli_query($conn, $query);
                        
                                        if(mysqli_num_rows($query_run) > 0) {
                                            $sql = "UPDATE attendances SET `time_in` = '$time_dtr' , `late` = '$late' , `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                            $result = mysqli_query($conn, $sql);
                                        if($result){
                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                    }else{
                                                        echo "Failed: " . mysqli_error($conn);
                                                    } 
                                            } else {
                                                echo "Failed: " . mysqli_error($conn);
                                            }      
                                        } else {
                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                        }
                                    } //Close Bracket day of week with Wednesday

                                    else if($day_of_week === 'Thursday'){
                                        $late = '';
                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                        if (mysqli_num_rows($result) > 0) {
                                            $row = mysqli_fetch_assoc($result);
                                            $fetch_timeout = $row['time_out'];
                                            // echo $fetch_timeout;
                                            if ($fetch_timeout != '00:00:00') {
                                                $time_out_datetime = new DateTime($fetch_timeout);
                                                $time_in_datetime = new DateTime($time_dtr);

                                                $lunchbreak_start = new DateTime('12:00:00');
                                                $lunchbreak_end = new DateTime('13:00:00');

                                                $SchedTimeIn = new DateTime($row_sched_tb['thurs_timein']);
                                                $SchedTimeOut = new DateTime($row_sched_tb['thurs_timeout']);

                                                if ($time_in_datetime < $lunchbreak_start) {
                                                    $grace_period_total = new DateTime($col_thursday_timein);
                                                    $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available

                                                    if ($grace_period_minutes > 0) {
                                                        $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                        $grace_period_total->add($grace_period_interval);
                                                    }
                                                    if ($grace_period_total < $time_in_datetime) {
                                                        $thursday_timeIn = new DateTime($col_thursday_timein);
                                                        if (empty($thursday_timeIn)) {
                                                            $late = "00:00:00";
                                                        } else {
                                                            
                                                            $late = $time_in_datetime->diff($thursday_timeIn)->format('%H:%I:%S');
                                                            $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_work);
                                                            $total_work_datetime->sub(new DateInterval('PT1H'));
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                    } else {
                                                        $total_work = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                        // Subtract 1 hour from total work
                                                        $total_work_schedtime = new DateTime($total_work);
                                                        $total_work_schedtime->sub(new DateInterval('PT1H'));
                                                        $total_work = $total_work_schedtime->format('H:i:s');
                                                    }
                                                } else {
                                                    if($time_in_datetime > $col_thursday_timein){
                                                        $scheduled_time = new DateTime($col_thursday_timein);
                                                        $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                                        $latetotal = new DateTime($interval);
                                                        $latetotal->sub(new DateInterval('PT1H'));
                                                        $late = $latetotal->format('H:i:s');
                                                    }
                                                    $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                }
                                            } else {
                                                $total_work = "00:00:00";
                                            }
                                        }

                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                        $query_run = mysqli_query($conn, $query);
                        
                                        if(mysqli_num_rows($query_run) > 0) {
                                            $sql = "UPDATE attendances SET `time_in` = '$time_dtr' , `late` = '$late' , `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                            $result = mysqli_query($conn, $sql);
                                        if($result){
                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                $query_run = mysqli_query($conn, $sql);
                                                    if($query_run){
                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                             //Syntax sa pag-email notification
                                                            $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                            $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                            
                                                            $EmpApproverArray = array();
                                                            while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                $EmployeeApprover = $EmployeeRow['empid'];

                                                                $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                            }

                                                            foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                            $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                            $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                            $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                            $ApprovedArray = array();
                                                            while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                        
                                                                $message = "
                                                                <html>
                                                                <head>
                                                                <title>{$subject}</title>
                                                                </head>
                                                                <body>
                                                                <p><strong>Dear $to,</strong></p>
                                                                <p>Your DTR Correction request for time-in on $date_dtr is approved</p>
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
                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                        }
                                    } //Close Bracket day of week with Thursday

                                        else if($day_of_week === 'Friday'){
                                            $late = '';
                                            $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $fetch_timeout = $row['time_out'];
                                                // echo $fetch_timeout;
                                                if ($fetch_timeout != '00:00:00') {
                                                    $time_out_datetime = new DateTime($fetch_timeout);
                                                    $time_in_datetime = new DateTime($time_dtr);
    
                                                    $lunchbreak_start = new DateTime('12:00:00');
                                                    $lunchbreak_end = new DateTime('13:00:00');
    
                                                    $SchedTimeIn = new DateTime($row_sched_tb['fri_timein']);
                                                    $SchedTimeOut = new DateTime($row_sched_tb['fri_timeout']);
    
                                                    if ($time_in_datetime < $lunchbreak_start) {
                                                        $grace_period_total = new DateTime($col_friday_timein);
                                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                                        if ($grace_period_minutes > 0) {
                                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                            $grace_period_total->add($grace_period_interval);
                                                        }
                                                        if ($grace_period_total < $time_in_datetime) {
                                                            $friday_timeIn = new DateTime($col_friday_timein);
                                                            if (empty($friday_timeIn)) {
                                                                $late = "00:00:00";
                                                            } else {
                                                                
                                                                $late = $time_in_datetime->diff($friday_timeIn)->format('%H:%I:%S');
                                                                $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_work);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                        } else {
                                                            $total_work = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                            // Subtract 1 hour from total work
                                                            $total_work_schedtime = new DateTime($total_work);
                                                            $total_work_schedtime->sub(new DateInterval('PT1H'));
                                                            $total_work = $total_work_schedtime->format('H:i:s');
                                                        }
                                                    } else {
                                                        if($time_in_datetime > $col_friday_timein){
                                                            $scheduled_time = new DateTime($col_friday_timein);
                                                            $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                                            $latetotal = new DateTime($interval);
                                                            $latetotal->sub(new DateInterval('PT1H'));
                                                            $late = $latetotal->format('H:i:s');
                                                        }
                                                        $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                    }
                                                } else {
                                                    $total_work = "00:00:00";
                                                }
                                            }
    
                                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                            $query_run = mysqli_query($conn, $query);
                            
                                            if(mysqli_num_rows($query_run) > 0) {
                                                $sql = "UPDATE attendances SET `time_in` = '$time_dtr' , `late` = '$late' , `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                $result = mysqli_query($conn, $sql);
                                            if($result){
                                                    $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                    $query_run = mysqli_query($conn, $sql);
                                                        if($query_run){
                                                            header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                            //Syntax sa pag-email notification
                                                            $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                            $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                            
                                                            $EmpApproverArray = array();
                                                            while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                $EmployeeApprover = $EmployeeRow['empid'];

                                                                $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                            }

                                                            foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                            $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                            $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                            $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                            $ApprovedArray = array();
                                                            while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                        
                                                                $message = "
                                                                <html>
                                                                <head>
                                                                <title>{$subject}</title>
                                                                </head>
                                                                <body>
                                                                <p><strong>Dear $to,</strong></p>
                                                                <p>Your DTR Correction request for time-in on $date_dtr is approved</p>
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
                                                header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                            }
                                        } //Close Bracket day of week with Friday

                                        else if($day_of_week === 'Saturday'){
                                            $late = '';
                                            $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $fetch_timeout = $row['time_out'];
                                                // echo $fetch_timeout;
                                                if ($fetch_timeout != '00:00:00') {
                                                    $time_out_datetime = new DateTime($fetch_timeout);
                                                    $time_in_datetime = new DateTime($time_dtr);
    
                                                    $lunchbreak_start = new DateTime('12:00:00');
                                                    $lunchbreak_end = new DateTime('13:00:00');
    
                                                    $SchedTimeIn = new DateTime($row_sched_tb['sat_timein']);
                                                    $SchedTimeOut = new DateTime($row_sched_tb['sat_timeout']);
    
                                                    if ($time_in_datetime < $lunchbreak_start) {
                                                        $grace_period_total = new DateTime($col_saturday_timein);
                                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                                        if ($grace_period_minutes > 0) {
                                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                            $grace_period_total->add($grace_period_interval);
                                                        }
                                                        if ($grace_period_total < $time_in_datetime) {
                                                            $saturday_timeIn = new DateTime($col_saturday_timein);
                                                            if (empty($saturday_timeIn)) {
                                                                $late = "00:00:00";
                                                            } else {
                                                                
                                                                $late = $time_in_datetime->diff($saturday_timeIn)->format('%H:%I:%S');
                                                                $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_work);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                        } else {
                                                            $total_work = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                            // Subtract 1 hour from total work
                                                            $total_work_schedtime = new DateTime($total_work);
                                                            $total_work_schedtime->sub(new DateInterval('PT1H'));
                                                            $total_work = $total_work_schedtime->format('H:i:s');
                                                        }
                                                    } else {
                                                        if($time_in_datetime > $col_saturday_timein){
                                                            $scheduled_time = new DateTime($col_saturday_timein);
                                                            $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                                            $latetotal = new DateTime($interval);
                                                            $latetotal->sub(new DateInterval('PT1H'));
                                                            $late = $latetotal->format('H:i:s');
                                                        }
                                                        $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                    }
                                                } else {
                                                    $total_work = "00:00:00";
                                                }
                                            }
    
                                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                            $query_run = mysqli_query($conn, $query);
                            
                                            if(mysqli_num_rows($query_run) > 0) {
                                                $sql = "UPDATE attendances SET `time_in` = '$time_dtr' , `late` = '$late' , `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                $result = mysqli_query($conn, $sql);
                                            if($result){
                                                    $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                    $query_run = mysqli_query($conn, $sql);
                                                        if($query_run){
                                                            header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                            //Syntax sa pag-email notification
                                                            $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                            $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                            
                                                            $EmpApproverArray = array();
                                                            while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                $EmployeeApprover = $EmployeeRow['empid'];

                                                                $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                            }

                                                            foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                            $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                            $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                            $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                            $ApprovedArray = array();
                                                            while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                        
                                                                $message = "
                                                                <html>
                                                                <head>
                                                                <title>{$subject}</title>
                                                                </head>
                                                                <body>
                                                                <p><strong>Dear $to,</strong></p>
                                                                <p>Your DTR Correction request for time-in on $date_dtr is approved</p>
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
                                                header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                            }
                                        } //Close Bracket day of week with Saturday

                                        else if($day_of_week === 'Sunday'){
                                            $late = '';
                                            $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                            if (mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $fetch_timeout = $row['time_out'];
                                                // echo $fetch_timeout;
                                                if ($fetch_timeout != '00:00:00') {
                                                    $time_out_datetime = new DateTime($fetch_timeout);
                                                    $time_in_datetime = new DateTime($time_dtr);
    
                                                    $lunchbreak_start = new DateTime('12:00:00');
                                                    $lunchbreak_end = new DateTime('13:00:00');
    
                                                    $SchedTimeIn = new DateTime($row_sched_tb['sun_timein']);
                                                    $SchedTimeOut = new DateTime($row_sched_tb['sun_timeout']);
    
                                                    if ($time_in_datetime < $lunchbreak_start) {
                                                        $grace_period_total = new DateTime($col_sunday_timein);
                                                        $grace_period_minutes = isset($col_grace_period) ? $col_grace_period : 0; // Retrieve grace period from $time array or set to 0 if not available
    
                                                        if ($grace_period_minutes > 0) {
                                                            $grace_period_interval = new DateInterval('PT' . $grace_period_minutes . 'M');
                                                            $grace_period_total->add($grace_period_interval);
                                                        }
                                                        if ($grace_period_total < $time_in_datetime) {
                                                            $sunday_timeIn = new DateTime($col_sunday_timein);
                                                            if (empty($sunday_timeIn)) {
                                                                $late = "00:00:00";
                                                            } else {
                                                                
                                                                $late = $time_in_datetime->diff($sunday_timeIn)->format('%H:%I:%S');
                                                                $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_work);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                        } else {
                                                            $total_work = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                            // Subtract 1 hour from total work
                                                            $total_work_schedtime = new DateTime($total_work);
                                                            $total_work_schedtime->sub(new DateInterval('PT1H'));
                                                            $total_work = $total_work_schedtime->format('H:i:s');
                                                        }
                                                    } else {
                                                        if($time_in_datetime > $col_sunday_timein){
                                                            $scheduled_time = new DateTime($col_sunday_timein);
                                                            $interval = $time_in_datetime->diff($scheduled_time)->format('%H:%I:%S');
                                                            $latetotal = new DateTime($interval);
                                                            $latetotal->sub(new DateInterval('PT1H'));
                                                            $late = $latetotal->format('H:i:s');
                                                        }
                                                        $total_work = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                    }
                                                } else {
                                                    $total_work = "00:00:00";
                                                }
                                            }
    
                                            $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                            $query_run = mysqli_query($conn, $query);
                            
                                            if(mysqli_num_rows($query_run) > 0) {
                                                $sql = "UPDATE attendances SET `time_in` = '$time_dtr' , `late` = '$late' , `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                $result = mysqli_query($conn, $sql);
                                            if($result){
                                                    $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                    $query_run = mysqli_query($conn, $sql);
                                                        if($query_run){
                                                            header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                            //Syntax sa pag-email notification
                                                            $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                            $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                            
                                                            $EmpApproverArray = array();
                                                            while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                $EmployeeApprover = $EmployeeRow['empid'];

                                                                $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                            }

                                                            foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                            $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                            $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                            $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                            $ApprovedArray = array();
                                                            while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                        
                                                                $message = "
                                                                <html>
                                                                <head>
                                                                <title>{$subject}</title>
                                                                </head>
                                                                <body>
                                                                <p><strong>Dear $to,</strong></p>
                                                                <p>Your DTR Correction request for time-in on $date_dtr is approved</p>
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
                                                header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                }
                                            } //Close bracket sa Sunday
                                         } //Close bracket sa result_sched_tb
                                     }
                                 }
                                                else if ($type_dtr === 'OUT') {
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
                                                        $col_grace_period = $row_sched_tb['grace_period'];


                                                $day_of_week = date('l', strtotime($date_dtr)); // get the day of the week using the "l" format specifier


                                                if ($day_of_week === 'Monday') {
                                                    $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                                    if (mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $fetch_timein = $row['time_in'];
                                                    $fetch_late = $row['late'];

                                                    if ($fetch_timein != '00:00:00') {
                                                        $time_in_datetime = new DateTime($fetch_timein);
                                                        $time_out_datetime = new DateTime($time_dtr);
                                                        $late_datetime = new DateTime($fetch_late);

                                                        $lunchbreak_start = new DateTime('12:00:00');
                                                        $lunchbreak_end = new DateTime('13:00:00');  

                                                        $SchedTimeIn = new DateTime($row_sched_tb['mon_timein']);
                                                        $SchedTimeOut = new DateTime($row_sched_tb['mon_timeout']);

                                                        //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                                        if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                            //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                             if($fetch_late != '00:00:00'){
                                                                $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }else{
                                                                //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                // Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_works);
                                                                $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                             }
                                                        }else{
                                                            $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                            // Remove Subtract 1 hour from total work
                                                            $total_work_datetime = new DateTime($total_workss);
                                                            $total_work = $total_work_datetime->format('H:i:s');
                                                        }
                                                        if ($time_out_datetime < $SchedTimeOut) {
                                                            $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                            $total_earlyOut = new DateTime($interval);
                                                            // $total_earlyOut->sub(new DateInterval('PT1H'));
                                                            $early_out = $total_earlyOut->format('H:i:s');
                                                        } else {
                                                            $early_out = "00:00:00";
                                                        }
                                                     }else{
                                                        $total_work = "00:00:00";
                                                     }
                                                    //  echo $total_work;
                                                    //  echo $early_out;
                                                  }

                                                    $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                                    $query_run = mysqli_query($conn, $query);
                                    
                                                    if(mysqli_num_rows($query_run) > 0) {
                                                        $sql = "UPDATE attendances SET `time_out` = '$time_dtr' , `early_out` = '$early_out',
                                                        `overtime` = '$overtime' , `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                        $result = mysqli_query($conn, $sql);
                                                    if($result){
                                                            $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                            $query_run = mysqli_query($conn, $sql);
                                                                if($query_run){
                                                                    header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully"); 
                                                                    //Syntax sa pag-email notification
                                                                    $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                                    $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                                    
                                                                    $EmpApproverArray = array();
                                                                    while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                        $EmployeeApprover = $EmployeeRow['empid'];

                                                                        $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                                    }

                                                                    foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                                    $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                                    $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                                    $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                                    $ApprovedArray = array();
                                                                    while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                        $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                                
                                                                        $message = "
                                                                        <html>
                                                                        <head>
                                                                        <title>{$subject}</title>
                                                                        </head>
                                                                        <body>
                                                                        <p><strong>Dear $to,</strong></p>
                                                                        <p>Your DTR Correction request for time-out on $date_dtr is approved</p>
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
                                                        header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                    } 
                                                } //day of week with Monday Close bracket

                                                    else if($day_of_week === 'Tuesday'){
                                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                                        if (mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $fetch_timein = $row['time_in'];
                                                        $fetch_late = $row['late'];

                                                        if ($fetch_timein != '00:00:00') {
                                                            $time_in_datetime = new DateTime($fetch_timein);
                                                            $time_out_datetime = new DateTime($time_dtr);
                                                            $late_datetime = new DateTime($fetch_late);

                                                            $lunchbreak_start = new DateTime('12:00:00');
                                                            $lunchbreak_end = new DateTime('13:00:00');  

                                                            $SchedTimeIn = new DateTime($row_sched_tb['tues_timein']);
                                                            $SchedTimeOut = new DateTime($row_sched_tb['tues_timeout']);

                                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                                //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                                 if($fetch_late != '00:00:00'){
                                                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }else{
                                                                    //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                    $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }
                                                            }else{
                                                                $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Remove Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_workss);
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                            if ($time_out_datetime < $SchedTimeOut) {
                                                                $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                                $total_earlyOut = new DateTime($interval);
                                                                // $total_earlyOut->sub(new DateInterval('PT1H'));
                                                                $early_out = $total_earlyOut->format('H:i:s');
                                                            } else {
                                                                $early_out = "00:00:00";
                                                            }
                                                         }else{
                                                            $total_work = "00:00:00";
                                                         }
                                                        //  echo $total_work;
                                                        //  echo $early_out;
                                                      }

                                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                                        $query_run = mysqli_query($conn, $query);
                                        
                                                        if(mysqli_num_rows($query_run) > 0) {
                                                            $sql = "UPDATE attendances SET `time_out`='$time_dtr', `early_out`='$early_out',
                                                            `overtime`='$overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                            $result = mysqli_query($conn, $sql);
                                                        if($result){
                                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                                $query_run = mysqli_query($conn, $sql);
                                                                    if($query_run){
                                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                                        //Syntax sa pag-email notification
                                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                                        
                                                                        $EmpApproverArray = array();
                                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                                        }

                                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                                        $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                                        $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                                        $ApprovedArray = array();
                                                                        while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                            $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                                    
                                                                            $message = "
                                                                            <html>
                                                                            <head>
                                                                            <title>{$subject}</title>
                                                                            </head>
                                                                            <body>
                                                                            <p><strong>Dear $to,</strong></p>
                                                                            <p>Your DTR Correction request for time-out on $date_dtr is approved</p>
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
                                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                    } 
                                                } //day of week with Tuesday Close bracket

                                                    else if($day_of_week === 'Wednesday'){
                                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                                        if (mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $fetch_timein = $row['time_in'];
                                                        $fetch_late = $row['late'];

                                                        if ($fetch_timein != '00:00:00') {
                                                            $time_in_datetime = new DateTime($fetch_timein);
                                                            $time_out_datetime = new DateTime($time_dtr);
                                                            $late_datetime = new DateTime($fetch_late);

                                                            $lunchbreak_start = new DateTime('12:00:00');
                                                            $lunchbreak_end = new DateTime('13:00:00');  

                                                            $SchedTimeIn = new DateTime($row_sched_tb['wed_timein']);
                                                            $SchedTimeOut = new DateTime($row_sched_tb['wed_timeout']);

                                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                                //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                                 if($fetch_late != '00:00:00'){
                                                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }else{
                                                                    //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                    $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }
                                                            }else{
                                                                $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Remove Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_workss);
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                            if ($time_out_datetime < $SchedTimeOut) {
                                                                $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                                $total_earlyOut = new DateTime($interval);
                                                                // $total_earlyOut->sub(new DateInterval('PT1H'));
                                                                $early_out = $total_earlyOut->format('H:i:s');
                                                            } else {
                                                                $early_out = "00:00:00";
                                                            }
                                                         }else{
                                                            $total_work = "00:00:00";
                                                         }
                                                        //  echo $total_work;
                                                        //  echo $early_out;
                                                      }
                                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                                        $query_run = mysqli_query($conn, $query);
                                        
                                                        if(mysqli_num_rows($query_run) > 0) {
                                                            $sql = "UPDATE attendances SET `time_out` = '$time_dtr', `early_out` = '$early_out',
                                                            `overtime` = '$overtime', `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                            $result = mysqli_query($conn, $sql);
                                                        if($result){
                                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                                $query_run = mysqli_query($conn, $sql);
                                                                    if($query_run){
                                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                                        //Syntax sa pag-email notification
                                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                                        
                                                                        $EmpApproverArray = array();
                                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                                        }

                                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                                        $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                                        $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                                        $ApprovedArray = array();
                                                                        while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                            $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                                    
                                                                            $message = "
                                                                            <html>
                                                                            <head>
                                                                            <title>{$subject}</title>
                                                                            </head>
                                                                            <body>
                                                                            <p><strong>Dear $to,</strong></p>
                                                                            <p>Your DTR Correction request for time-out on $date_dtr is approved</p>
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
                                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                        }  
                                                    } //day of week with Wednesday Close bracket

                                                    else if($day_of_week === 'Thursday'){
                                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                                        if (mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $fetch_timein = $row['time_in'];
                                                        $fetch_late = $row['late'];

                                                        if ($fetch_timein != '00:00:00') {
                                                            $time_in_datetime = new DateTime($fetch_timein);
                                                            $time_out_datetime = new DateTime($time_dtr);
                                                            $late_datetime = new DateTime($fetch_late);

                                                            $lunchbreak_start = new DateTime('12:00:00');
                                                            $lunchbreak_end = new DateTime('13:00:00');  

                                                            $SchedTimeIn = new DateTime($row_sched_tb['thurs_timein']);
                                                            $SchedTimeOut = new DateTime($row_sched_tb['thurs_timeout']);

                                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                                //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                                 if($fetch_late != '00:00:00'){
                                                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }else{
                                                                    //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                    $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }
                                                            }else{
                                                                $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Remove Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_workss);
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                            if ($time_out_datetime < $SchedTimeOut) {
                                                                $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                                $total_earlyOut = new DateTime($interval);
                                                                // $total_earlyOut->sub(new DateInterval('PT1H'));
                                                                $early_out = $total_earlyOut->format('H:i:s');
                                                            } else {
                                                                $early_out = "00:00:00";
                                                            }
                                                         }else{
                                                            $total_work = "00:00:00";
                                                         }
                                                        //  echo $total_work;
                                                        //  echo $early_out;
                                                      }

                                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                                        $query_run = mysqli_query($conn, $query);
                                        
                                                        if(mysqli_num_rows($query_run) > 0) {
                                                            $sql = "UPDATE attendances SET `time_out`='$time_dtr', `early_out`='$early_out',
                                                            `overtime`='$overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                            $result = mysqli_query($conn, $sql);
                                                        if($result){
                                                                $sql = "UPDATE emp_dtr_tb SET `status` = 'Approved' WHERE `id` = '$tableid'";
                                                                $query_run = mysqli_query($conn, $sql);
                                                                    if($query_run){
                                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");  
                                                                         //Syntax sa pag-email notification
                                                                         $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                                         $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                                         
                                                                         $EmpApproverArray = array();
                                                                         while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                             $EmployeeApprover = $EmployeeRow['empid'];
 
                                                                             $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                                         }
 
                                                                         foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                                         $EmpMail = $EmailOfEmployee['EmployeeApprover'];
 
                                                                         $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                                         $approvedDTRRun = mysqli_query($conn, $selectDTR);
 
                                                                         $ApprovedArray = array();
                                                                         while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                             $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                                     
                                                                             $message = "
                                                                             <html>
                                                                             <head>
                                                                             <title>{$subject}</title>
                                                                             </head>
                                                                             <body>
                                                                             <p><strong>Dear $to,</strong></p>
                                                                             <p>Your DTR Correction request for time-out on $date_dtr is approved</p>
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
                                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                        } 
                                                    } //day of week with Thursday Close bracket

                                                    else if($day_of_week === 'Friday'){
                                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                                        if (mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $fetch_timein = $row['time_in'];
                                                        $fetch_late = $row['late'];

                                                        if ($fetch_timein != '00:00:00') {
                                                            $time_in_datetime = new DateTime($fetch_timein);
                                                            $time_out_datetime = new DateTime($time_dtr);
                                                            $late_datetime = new DateTime($fetch_late);

                                                            $lunchbreak_start = new DateTime('12:00:00');
                                                            $lunchbreak_end = new DateTime('13:00:00');  

                                                            $SchedTimeIn = new DateTime($row_sched_tb['fri_timein']);
                                                            $SchedTimeOut = new DateTime($row_sched_tb['fri_timeout']);

                                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                                //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                                 if($fetch_late != '00:00:00'){
                                                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }else{
                                                                    //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                    $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }
                                                            }else{
                                                                $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Remove Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_workss);
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                            if ($time_out_datetime < $SchedTimeOut) {
                                                                $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                                $total_earlyOut = new DateTime($interval);
                                                                // $total_earlyOut->sub(new DateInterval('PT1H'));
                                                                $early_out = $total_earlyOut->format('H:i:s');
                                                            } else {
                                                                $early_out = "00:00:00";
                                                            }
                                                         }else{
                                                            $total_work = "00:00:00";
                                                         }
                                                        //  echo $total_work;
                                                        //  echo $early_out;
                                                      }

                                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                                        $query_run = mysqli_query($conn, $query);
                                        
                                                        if(mysqli_num_rows($query_run) > 0) {
                                                            $sql = "UPDATE attendances SET `time_out`='$time_dtr', `early_out`='$early_out',
                                                            `overtime`='$overtime', `total_work`='$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                            $result = mysqli_query($conn, $sql);
                                                        if($result){
                                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                                $query_run = mysqli_query($conn, $sql);
                                                                    if($query_run){
                                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");  
                                                                        //Syntax sa pag-email notification
                                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);

                                                                        $EmpApproverArray = array();
                                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                                        }

                                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                                        $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                                        $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                                        $ApprovedArray = array();
                                                                        while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                            $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";

                                                                            $message = "
                                                                            <html>
                                                                            <head>
                                                                            <title>{$subject}</title>
                                                                            </head>
                                                                            <body>
                                                                            <p><strong>Dear $to,</strong></p>
                                                                            <p>Your DTR Correction request for time-out on $date_dtr is approved</p>
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
                                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                        } 
                                                } //day of week with Friday Close bracket

                                                    else if($day_of_week === 'Saturday'){
                                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                                        if (mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $fetch_timein = $row['time_in'];
                                                        $fetch_late = $row['late'];

                                                        if ($fetch_timein != '00:00:00') {
                                                            $time_in_datetime = new DateTime($fetch_timein);
                                                            $time_out_datetime = new DateTime($time_dtr);
                                                            $late_datetime = new DateTime($fetch_late);

                                                            $lunchbreak_start = new DateTime('12:00:00');
                                                            $lunchbreak_end = new DateTime('13:00:00');  

                                                            $SchedTimeIn = new DateTime($row_sched_tb['sat_timein']);
                                                            $SchedTimeOut = new DateTime($row_sched_tb['sat_timeout']);

                                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                                //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                                 if($fetch_late != '00:00:00'){
                                                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }else{
                                                                    //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                    $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }
                                                            }else{
                                                                $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Remove Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_workss);
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                            if ($time_out_datetime < $SchedTimeOut) {
                                                                $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                                $total_earlyOut = new DateTime($interval);
                                                                // $total_earlyOut->sub(new DateInterval('PT1H'));
                                                                $early_out = $total_earlyOut->format('H:i:s');
                                                            } else {
                                                                $early_out = "00:00:00";
                                                            }
                                                         }else{
                                                            $total_work = "00:00:00";
                                                         }
                                                        //  echo $total_work;
                                                        //  echo $early_out;
                                                      }

                                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                                        $query_run = mysqli_query($conn, $query);
                                        
                                                        if(mysqli_num_rows($query_run) > 0) {
                                                            $sql = "UPDATE attendances SET `time_out` = '$time_dtr', `early_out` = '$early_out',
                                                            `overtime` = '$overtime', `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                            $result = mysqli_query($conn, $sql);
                                                        if($result){
                                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                                $query_run = mysqli_query($conn, $sql);
                                                                    if($query_run){
                                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                                        //Syntax sa pag-email notification
                                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);

                                                                        $EmpApproverArray = array();
                                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                                        }

                                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                                        $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                                        $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                                        $ApprovedArray = array();
                                                                        while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                            $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";

                                                                            $message = "
                                                                            <html>
                                                                            <head>
                                                                            <title>{$subject}</title>
                                                                            </head>
                                                                            <body>
                                                                            <p><strong>Dear $to,</strong></p>
                                                                            <p>Your DTR Correction request for time-out on $date_dtr is approved</p>
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
                                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                        } 
                                                    } //day of week with Saturday Close bracket
                                                    
                                                    else if($day_of_week === 'Sunday'){
                                                        $result = mysqli_query($conn, "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'");
                                                        if (mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $fetch_timein = $row['time_in'];
                                                        $fetch_late = $row['late'];
    
                                                        if ($fetch_timein != '00:00:00') {
                                                            $time_in_datetime = new DateTime($fetch_timein);
                                                            $time_out_datetime = new DateTime($time_dtr);
                                                            $late_datetime = new DateTime($fetch_late);
    
                                                            $lunchbreak_start = new DateTime('12:00:00');
                                                            $lunchbreak_end = new DateTime('13:00:00');  
    
                                                            $SchedTimeIn = new DateTime($row_sched_tb['sun_timein']);
                                                            $SchedTimeOut = new DateTime($row_sched_tb['sun_timeout']);
    
                                                            //Check kung ang existing time in ay before lunchbreak at ang time out ay greater than sa lunchbreak
                                                            if($time_in_datetime < $lunchbreak_start && $time_out_datetime > $lunchbreak_start) {
                                                                //kung ang existing time in ay before lunch ichicheck kung ano ang value ng late
                                                                 if($fetch_late != '00:00:00'){
                                                                    $total_works = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }else{
                                                                    //Kung walang late, ang nakaset na time in at time out ang i-cacalculate ang difference
                                                                    $total_works = $SchedTimeOut->diff($SchedTimeIn)->format('%H:%I:%S');
                                                                    // Subtract 1 hour from total work
                                                                    $total_work_datetime = new DateTime($total_works);
                                                                    $total_work_datetime->sub(new DateInterval('PT1H'));
                                                                    $total_work = $total_work_datetime->format('H:i:s');
                                                                 }
                                                            }else{
                                                                $total_workss = $time_out_datetime->diff($time_in_datetime)->format('%H:%I:%S');
                                                                // Remove Subtract 1 hour from total work
                                                                $total_work_datetime = new DateTime($total_workss);
                                                                $total_work = $total_work_datetime->format('H:i:s');
                                                            }
                                                            if ($time_out_datetime < $SchedTimeOut) {
                                                                $interval = $time_out_datetime->diff($SchedTimeOut)->format('%H:%I:%S');
                                                                $total_earlyOut = new DateTime($interval);
                                                                // $total_earlyOut->sub(new DateInterval('PT1H'));
                                                                $early_out = $total_earlyOut->format('H:i:s');
                                                            } else {
                                                                $early_out = "00:00:00";
                                                            }
                                                         }else{
                                                            $total_work = "00:00:00";
                                                         }
                                                        //  echo $total_work;
                                                        //  echo $early_out;
                                                      }

                                                        $query = "SELECT * FROM attendances WHERE `empid` = '$employeeid' AND `date` = '$date_dtr' AND `status` = 'Present'";
                                                        $query_run = mysqli_query($conn, $query);
                                        
                                                        if(mysqli_num_rows($query_run) > 0) {
                                                            $sql = "UPDATE attendances SET `time_out` = '$time_dtr', `early_out` = '$early_out',
                                                            `overtime` = '$overtime', `total_work` = '$total_work' WHERE `empid` = '$employeeid' AND `date` = '$date_dtr'";
                                                            $result = mysqli_query($conn, $sql);
                                                        if($result){
                                                                $sql = "UPDATE emp_dtr_tb SET `status` ='Approved' WHERE `id`='$tableid'";
                                                                $query_run = mysqli_query($conn, $sql);
                                                                    if($query_run){
                                                                        header("Location: ../../dtr_admin.php?msg=You Approved this Request Successfully");
                                                                        //Syntax sa pag-email notification
                                                                        $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                                                                        $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                                                                        
                                                                        $EmpApproverArray = array();
                                                                        while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                                                                            $EmployeeApprover = $EmployeeRow['empid'];

                                                                            $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                                                                        }

                                                                        foreach ($EmpApproverArray as $EmailOfEmployee) {
                                                                        $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                                                                        $selectDTR = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
                                                                        $approvedDTRRun = mysqli_query($conn, $selectDTR);

                                                                        $ApprovedArray = array();
                                                                        while ($ApprovedRow = mysqli_fetch_assoc($approvedDTRRun)) {
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
                                                                            $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";
                                                    
                                                                            $message = "
                                                                            <html>
                                                                            <head>
                                                                            <title>{$subject}</title>
                                                                            </head>
                                                                            <body>
                                                                            <p><strong>Dear $to,</strong></p>
                                                                            <p>Your DTR Correction request for time-out on $date_dtr is approved</p>
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
                                                            header("Location: ../../dtr_admin.php?error=There's no attendance of this employee for the $date_dtr");
                                                        }  
                                                    } //day of week with Sunday Close bracket

                                            } // Close bracket sa result_sched_tb
                                         }                            
                                      } //type_dtr out close bracket
                                 }
                            } //Close bracket sa approve_btn
/************************* End of Approve Button ***************************/


/************************* For Reject Button ***************************/
if(isset($_POST['reject_btn']))
{

    $tableid = $_POST['input'];

    $result_dtr = mysqli_query($conn, " SELECT * FROM emp_dtr_tb WHERE id = '$tableid'");
    if(mysqli_num_rows($result_dtr) > 0) {
        $row_dtr = mysqli_fetch_assoc($result_dtr);
}
    $status_dtr = $row_dtr['status'];
    
    if($status_dtr === 'Approved'){
        header("Location: ../../dtr_admin.php?error=You cannot REJECT a request that is already APPROVED");
    }
    else if($status_dtr === 'Rejected'){
        header("Location: ../../dtr_admin.php?error=You cannot REJECT a request that is already REJECTED");
    }else{
        $query = "UPDATE emp_dtr_tb SET `status` ='Rejected' WHERE `id`='$tableid'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../dtr_admin.php?msg=You Rejected this Request");
                //Syntax sa pag-email notification
                $GetapproverQuery = "SELECT * FROM approver_tb WHERE approver_empid = '$employeeID'";
                $GetApproverRun = mysqli_query($conn, $GetapproverQuery);
                
                $EmpApproverArray = array();
                while ($EmployeeRow = mysqli_fetch_assoc($GetApproverRun)) {
                    $EmployeeApprover = $EmployeeRow['empid'];

                    $EmpApproverArray[] = array('EmployeeApprover' => $EmployeeApprover);
                }

                foreach ($EmpApproverArray as $EmailOfEmployee) {
                $EmpMail = $EmailOfEmployee['EmployeeApprover'];

                $selectWFH = "SELECT * FROM emp_dtr_tb WHERE id = '$tableid' AND empid = '$EmpMail'";  
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
                    $subject = "EMPLOYEE '$empid - $fullname' DTR CORRECTION REQUEST";

                    $message = "
                    <html>
                    <head>
                    <title>{$subject}</title>
                    </head>
                    <body>
                    <p><strong>Dear $to,</strong></p>
                    <p>Your DTR Correction request on $date_dtr is rejected</p>
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
/************************* End of Reject Button ***************************/
?>