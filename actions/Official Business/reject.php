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

if(isset($_POST['name_rejected']))
{

    $column_id = $_POST['id_reject'];

    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Asia/Manila'));
    $currentDateTime = $now->format('Y-m-d H:i:s');

    $rejected_remarks = $_POST['name_rejectedRemarks'];

    $result_official = mysqli_query($conn, " SELECT * FROM emp_official_tb WHERE id = '$column_id'");
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
        header("Location: ../../official_business.php?error=You cannot REJECT a request that is already APPROVED");
    }
    else if($status_official === 'Rejected'){
        header("Location: ../../official_business.php?error=You cannot REJECT a request that is already REJECTED");
    }else{
        $query = "UPDATE emp_official_tb SET `status` ='Rejected', `action_taken` = '$currentDateTime', `remarks` = '$rejected_remarks' WHERE `id`='$column_id'";
        $query_run = mysqli_query($conn, $query);
    
        if($query_run)
        {
            header("Location: ../../official_business.php?msg=You Rejected this Request");
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
                <p>Your official business request on $date_official_start to $date_official_end is rejected</p>
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