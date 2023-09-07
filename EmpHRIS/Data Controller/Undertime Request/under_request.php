<?php
session_start();
include '../../config.php';
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';

if(isset($_POST['add_undertime'])){

$employee_id = $_POST['name_emp'];
$date_undertime = $_POST['date_undertime'];
$start_undertime = $_POST['under_time_from'];
$end_undertime = $_POST['under_time_to'];
$total_undertime = $_POST['total_undertime'];
$reason_undertime = $_POST['undertime_reason'];

include '../../config.php';

$sql = "SELECT * FROM overtime_tb WHERE `empid` = '$employee_id' AND `work_schedule` = '$date_undertime' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$empid =  $row['empid'];
$date = $row['work_schedule'];

echo $empid, "<br>"; 
echo $employee_id, "<br>"; 
echo $date, "<br>";
echo $date_undertime, "<br>";


if($empid == $employee_id && $date == $date_undertime){
    // echo "hehe";
    header("Location: ../../undertime_req?error=You can't file an undertime when you have overtime with same date");
    exit;
}else{


if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
    $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
    $escaped_contents = mysqli_real_escape_string($conn, $contents);
} else {
    $escaped_contents = "";
}

        $sql = "SELECT * FROM undertime_tb WHERE `empid` = '$employee_id' AND `date` = '$date_undertime'";
        $result = mysqli_query($conn, $sql);


        if(empty(mysqli_fetch_assoc($result))){
            $query = "INSERT INTO undertime_tb (`empid`,`date`,`start_time`,`end_time`,`total_undertime`,`file_attachment`,`reason`,`status`)
            VALUES ('$employee_id', '$date_undertime', '$start_undertime', '$end_undertime', '$total_undertime', '$escaped_contents', '$reason_undertime', 'Pending')";
            $query_run = mysqli_query($conn, $query);

            if($query_run)
            {
                header("Location: ../../undertime_req.php?msg=Successfully Added");
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
                $subject = "EMPLOYEE $employeeID HAS UNDERTIME REQUEST";
                // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                // <p><a href='{$base_url}'>Link of OT Request</a></p>

                $message = "
                <html>
                <head>
                <title>{$subject}</title>
                </head>
                <body>
                <p><strong>Dear Ma'am/Sir $to,</strong></p>
                <p>Thanks for allowing me to request an undertime, Please read my application on my request</p>
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
                echo "Failed: " . mysqli_error($conn);
            }
        }else{
            header("Location: ../../undertime_req.php?error=You've already undertime request for that Date");
        }    

}
}
?>