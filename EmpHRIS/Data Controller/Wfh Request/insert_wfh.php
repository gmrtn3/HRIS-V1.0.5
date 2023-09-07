<?php
session_start();
include '../../config.php';
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';

if(isset($_POST['add_wfh']))
{
    $employee_id = $_POST['name_emp'];
    $wfh_date = $_POST['wfh_date'];
    $schedType = $_POST['choose_scheduletype'];
    $start_time = $_POST['time_from'];
    $end_time = $_POST['time_to'];
    $description = $_POST['request_description'];

    if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
        $escaped_contents = mysqli_real_escape_string($conn, $contents);
    } else {
        $escaped_contents = "";
    }

    $sql = "SELECT * FROM wfh_tb WHERE `empid` = '$employee_id' AND `date` = '$wfh_date'";
    $result = mysqli_query($conn, $sql);

    if(empty(mysqli_fetch_assoc($result))){
        $query = "INSERT INTO wfh_tb (`empid`, `date`, `schedule_type`, `start_time`, `end_time`, `reason`, `file_attachment`, `status`)
        VALUES ('$employee_id', '$wfh_date', '$schedType', '$start_time', '$end_time', '$description', '$escaped_contents', 'Pending')";
        $query_run = mysqli_query($conn, $query);

        if($query_run)
        {
            header("Location: ../../wfh_request.php?msg=Successfully Added");
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
             $subject = "EMPLOYEE $employeeID HAS WORK FROM HOME REQUEST";
             // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
             // <p><a href='{$base_url}'>Link of OT Request</a></p>

             $message = "
             <html>
             <head>
             <title>{$subject}</title>
             </head>
             <body>
             <p><strong>Dear Ma'am/Sir $to,</strong></p>
             <p>Thanks for allowing me to request an work from home, Please read my application on my request</p>
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
        }else{
            echo "Failed: ". mysqli_error($conn);
        }
    }else{
        header("Location: ../../wfh_request.php?error=You've already wfh request for that Date");
    }

}


?>