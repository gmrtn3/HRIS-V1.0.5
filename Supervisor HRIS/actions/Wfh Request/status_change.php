<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hris_db");
$employeeID = $_SESSION['empid'];
$Username = $_SESSION['username'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';

// Check database connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM wfh_tb WHERE `status`='Pending'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../wfh_request.php?error=No Pending Requests");
  exit();
}

if (isset($_POST['approve_all'])) {
    $checkPending = "SELECT * FROM wfh_tb WHERE `status`='Pending'";
    $checkPendingResult = mysqli_query($conn, $checkPending);

    $approvedCount = 0;
    $alreadyApprovedRejected = false;

    while ($row = mysqli_fetch_assoc($checkPendingResult)) {
        $id = $row['id'];
        $status = $row['status'];
        $choose_date = $row['date'];

        if ($status == 'Pending') {
            $queryUpdate = "UPDATE wfh_tb SET `status`='Approved' WHERE `id`='$id' AND `status`='Pending'";
            $updateResult = mysqli_query($conn, $queryUpdate);

            if ($updateResult) {
                $approvedCount++;
            } else {
                echo "Failed: " . mysqli_error($conn);
                exit();
            }
        } elseif ($status == 'Approved' || $status == 'Rejected') {
            $alreadyApprovedRejected = true;
        }
    }

    if ($approvedCount > 0) {
        header("Location: ../../wfh_request.php?msg=You approved all requests successfully");
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

            $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$id' AND empid = '$EmpMail'";
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
            <p>Your work from home request on $choose_date is already approved</p>
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
        exit();
    } elseif ($alreadyApprovedRejected) {
        header("Location: ../../wfh_request.php?error=Sorry, you cannot approve requests that are already approved or rejected");
        exit();
    }
}



if (isset($_POST['reject_all'])) {
    $checkPending = "SELECT * FROM wfh_tb WHERE `status`='Pending'";
    $checkPendingResult = mysqli_query($conn, $checkPending);

    $rejectedCount = 0;
    $alreadyApprovedRejected = false;

    while ($row = mysqli_fetch_assoc($checkPendingResult)) {
        $id = $row['id'];
        $status = $row['status'];

        if ($status == 'Pending') {
            $queryUpdate = "UPDATE wfh_tb SET `status`='Rejected' WHERE `id`='$id' AND `status`='Pending'";
            $updateResult = mysqli_query($conn, $queryUpdate);

            if ($updateResult) {
                $rejectedCount++;
            } else {
                echo "Failed: " . mysqli_error($conn);
                exit();
            }
        } elseif ($status == 'Approved' || $status == 'Rejected') {
            $alreadyApprovedRejected = true;
        }
    }

    if ($rejectedCount > 0) {
        header("Location: ../../wfh_request.php?msg=You rejected all requests successfully");
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

            $selectWFH = "SELECT * FROM wfh_tb WHERE id= '$id' AND empid = '$EmpMail'";
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
        exit();
    } elseif ($alreadyApprovedRejected) {
        header("Location: ../../wfh_request.php?error=Sorry, you cannot reject requests that are already approved or rejected");
        exit();
    }
}
mysqli_close($conn);
?>

