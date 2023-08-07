<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "hris_db");
$employeeID = $_SESSION['empid'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../phpmailer/src/Exception.php';
require '../../../phpmailer/src/PHPMailer.php';
require '../../../phpmailer/src/SMTP.php';

$query = "SELECT * FROM overtime_tb WHERE `status`= 'Pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
  header("Location: ../../overtime_req.php?error=No Pending Requests");
  exit();
}

if (isset($_POST['reject_all'])){
    $msg = "";
    $error = false;
  
    $query = "SELECT * FROM overtime_tb WHERE `status` = 'Pending'";
    $result_pending = mysqli_query($conn, $query);
  
    $now = new DateTime();
    $now->setTimezone(new DateTimeZone('Asia/Manila'));
    $currentDateTime = $now->format('Y-m-d H:i:s');

    $ot_reject_marks = $_POST['ot_reject_marks'];


    if(mysqli_num_rows($result_pending) > 0){
        while($ot_row = mysqli_fetch_assoc($result_pending)){
          $overtime_id = $ot_row['id'];
          $employeeid = $ot_row['empid'];
          $date_ot = $ot_row['work_schedule'];
          $starttime = $ot_row['time_in'];
          $endtime = $ot_row['time_out'];
          $overtimereq = $ot_row['ot_hours'];
          $total_overtime = $ot_row['total_ot'];
          $status_ot = $ot_row['status'];
    
          if($status_ot === 'Pending'){    
                $day_of_week = date('l', strtotime($date_ot)); // get the day of the week using the "l" format specifier
                
                        if ($day_of_week === 'Monday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Monday Close Bracket

                        else if ($day_of_week === 'Tuesday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Tuesday Close Bracket 

                        else if ($day_of_week === 'Wednesday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Wednesday Close Bracket 

                        else if ($day_of_week === 'Thursday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Thursday Close Bracket 

                        else if ($day_of_week === 'Friday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Friday Close Bracket 

                        else if ($day_of_week === 'Saturday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Saturday Close Bracket 

                        else if ($day_of_week === 'Sunday') {
                            $UpdateOT = "UPDATE overtime_tb SET `status` = 'Rejected', `ot_action_taken` = '$currentDateTime', `ot_remarks` = '$ot_reject_marks' WHERE `empid` = '$employeeid' AND `id` = '$overtime_id' AND `status` = 'Pending'";
                            $RunOT = mysqli_query($conn, $UpdateOT);
                            if ($RunOT){
                              $msg .= "You Rejected the Request Successfully for employee ID: $employeeid on $date_ot<br>";
                            }else{
                            $error = true;
                            $msg .= "Failed to reject the request: $employeeid on $date_ot<br>";
                            }
                        } //Sunday Close Bracket 

                        else {
                          $error = true;
                          $msg .= "Invalid day of the week for employee ID: $employeeid<br>";
                        }

                        if ($error) {
                          header("Location: ../../overtime_req.php?error=$msg");
                        } else {
                          header("Location: ../../overtime_req.php?msg=$msg");
                        }

            if ($error) {
              header("Location: ../../overtime_req.php?error=$msg");
            } else {
              header("Location: ../../overtime_req.php?msg=$msg");

            }

          }
        }
    }

}



?>