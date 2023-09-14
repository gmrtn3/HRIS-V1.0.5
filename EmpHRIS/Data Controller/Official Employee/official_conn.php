<?php
    session_start();
include '../../config.php';
    $employeeID = $_SESSION['empid'];

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../../../phpmailer/src/Exception.php';
    require '../../../phpmailer/src/PHPMailer.php';
    require '../../../phpmailer/src/SMTP.php';

    if(isset($_POST['savedata']))
    {
        $employee_id = $_POST ['name_emp'];
        $name_company = $_POST ['company_name'];
        $start_date = $_POST['str_date'];
        $end_date = $_POST['end_date'];
        $start_time = $_POST['str_time'];
        $end_time = $_POST['end_time'];
        $location = $_POST['locate'];
        $reason = $_POST['text_reason'];

        if(isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
            $contents = file_get_contents($_FILES['file_upload']['tmp_name']);
            $escaped_contents = mysqli_real_escape_string($conn, $contents);
        } else {
            $escaped_contents = "";
        }

        $sql = "SELECT * FROM emp_official_tb WHERE `employee_id` = '$employee_id' AND `str_date` = '$start_date' AND `end_date` = '$end_date'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0) {
            header("Location: ../../official_emp.php?error=Same Start date and End date will not allow");
        } else {
            $query = "INSERT INTO emp_official_tb (`employee_id`, `company_name`, `str_date`, `end_date`, `start_time`, `end_time`, `location`, `file_upl`, `reason`, `status`)
                    VALUES ('$employee_id', '$name_company', '$start_date', '$end_date', '$start_time', '$end_time', '$location', '$escaped_contents', '$reason', 'Pending')";
            $query_run = mysqli_query($conn, $query);
            if($query_run) {
                header("Location: ../../official_emp.php?msg=Successfully Added");
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
                 $subject = "EMPLOYEE $employeeID HAS OFFICIAL BUSINESS REQUEST";
                 // $base_url = "http://localhost/HRISv1/Supervisor%20HRIS/overtime_req.php";
                 // <p><a href='{$base_url}'>Link of OT Request</a></p>

                 $message = "
                 <html>
                 <head>
                 <title>{$subject}</title>
                 </head>
                 <body>
                 <p><strong>Dear Ma'am/Sir $to,</strong></p>
                 <p>Thanks for allowing me to request an official business, Please read my application on my request</p>
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
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        }


    }
?>