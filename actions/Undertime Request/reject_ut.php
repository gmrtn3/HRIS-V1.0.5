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

    if(isset($_POST['name_rejected_ut']))
    {

    $UT_reject_id = $_POST['reject_name_ut'];

    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Asia/Manila'));
    $currentDateTime = $now->format('Y-m-d H:i:s');

    $UT_reject_marks = $_POST['ut_reject_remarks'];

    $result_under = mysqli_query($conn, " SELECT * FROM undertime_tb WHERE id = '$UT_reject_id'");
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
        $query = "UPDATE undertime_tb SET `status` = 'Rejected', `ut_action_taken` = '$currentDateTime', `ut_remarks` = '$UT_reject_marks' WHERE `id`= '$UT_reject_id'";
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

            $selectUT = "SELECT * FROM undertime_tb WHERE id = '$UT_reject_id' AND empid = '$EmpMail'";
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

?>