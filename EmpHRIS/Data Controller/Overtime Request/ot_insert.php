<?php
session_start();
include '../../config.php';
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';
    
if (isset($_POST['add_overtime'])) {
    $employee_id = $_POST['name_emp'];
    $work_schedule = $_POST['schedule'];
    $time_entry = $_POST['time_start'];
    $time_out = $_POST['time_end'];
    $ot_set = $_POST['time_to'];
    $total_time = $_POST['total_overtime'];
    $reason = $_POST['file_reason'];

    include '../../config.php';

    $sql = "SELECT * FROM undertime_tb WHERE `empid` = '$employee_id' AND `date` = '$work_schedule' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $empid =  $row['empid'];
    $date = $row['date'];

    echo $empid, "<br>"; 
    echo $employee_id, "<br>"; 
    echo $date, "<br>";
    echo $work_schedule, "<br>";
    
    

    if($empid == $employee_id && $date == $work_schedule){
        // echo "hehe";
        header("Location: ../../overtime_req?error=You can't file an overtime when you have undertime with same date");
        exit;
    }else{

    
    

    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
        $escaped_contents = mysqli_real_escape_string($conn, $contents);
    } else {
        $escaped_contents = "";
    }

    $result_emp_sched = mysqli_query($conn, "SELECT schedule_name FROM empschedule_tb WHERE empid = '$employeeID'");
    if (mysqli_num_rows($result_emp_sched) > 0) {
        $row_emp_sched = mysqli_fetch_assoc($result_emp_sched);
        $schedID = $row_emp_sched['schedule_name'];

        $result_sched_tb = mysqli_query($conn, "SELECT * FROM `schedule_tb` WHERE `schedule_name` = '$schedID'");
        if (mysqli_num_rows($result_sched_tb) > 0) {
            $row_sched_tb = mysqli_fetch_assoc($result_sched_tb);
            $Able_OT = $row_sched_tb['enable_sched_ot'];
            $OT_time = $row_sched_tb['sched_ot'];

            // Days when overtime is enabled
            $enabledDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            $day_of_week = date('l', strtotime($work_schedule));

            if (!in_array($day_of_week, $enabledDays) || empty($Able_OT)) {
                header("Location: ../../overtime_req.php?error=You cannot file an overtime request because the 'Enable OT' option is not set. Please contact your supervisor for further assistance.");
                exit();
            } else {
                if (!empty($OT_time)) {
                    $time_out_converted = new DateTime($time_out);
                    $ot_period_minutes = isset($row_sched_tb['sched_ot']) ? $row_sched_tb['sched_ot'] : 0; // Retrieve enable OT from $time array or set to 0 if not available
                    
                    if ($ot_period_minutes > 0) {
                        $ot_period_interval = new DateInterval('PT' . $ot_period_minutes . 'M');
                        $AddTimeOut_OTperiod = $time_out_converted->add($ot_period_interval)->format('H:i:s');  
                        
                    }
            
                    if ($ot_set > $AddTimeOut_OTperiod) {
                        $sql = "SELECT * FROM overtime_tb WHERE `empid` = '$employee_id' AND `work_schedule` = '$work_schedule'";
                        $result = mysqli_query($conn, $sql);
            
                        if (mysqli_num_rows($result) === 0) {
                            $query = "INSERT INTO overtime_tb (`empid`,`work_schedule`,`time_in`,`time_out`,`ot_hours`,`total_ot`,`reason`,`file_attachment`,`status`)
                            VALUES ('$employee_id', '$work_schedule', '$time_entry', '$time_out', '$ot_set', '$total_time', '$reason', '$escaped_contents', 'Pending')";
                            $query_run = mysqli_query($conn, $query);
            
                            if ($query_run) {
                                header("Location: ../../overtime_req.php?msg=Successfully Added");
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

                                echo $EmployeeEmail['email'];


                                $to = $EmployeeEmail['email'];
                                $subject = "EMPLOYEE $employeeID HAS OVERTIME REQUEST";
                                // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                                // <p><a href='{$base_url}'>Link of OT Request</a></p>

                                $message = "
                                <html>
                                <head>
                                <title>{$subject}</title>
                                </head>
                                <body>
                                <p><strong>Dear Ma'am/Sir $to,</strong></p>
                                <p>Thanks for allowing me to request an overtime, Please read my application on my request</p>
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
                                exit();
                            } else {
                                echo "Failed: " . mysqli_error($conn);
                                exit();
                            }
                        } else {
                            header("Location: ../../overtime_req.php?error=You've already requested overtime for that Date");
                            exit();
                        }
                    } else {
                        header("Location: ../../overtime_req.php?error=The requested overtime duration must be greater than the allowed overtime for this day.");
                        exit();
                    }
                } else {
                    header("Location: ../../overtime_req.php?error=The 'OT Time' is not set. Please contact your supervisor for further assistance.");
                    exit();
                }
            }
         }
    }
}
}
?>
