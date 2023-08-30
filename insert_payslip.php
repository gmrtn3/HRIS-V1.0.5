<?php
// insert_data.php
include 'config.php';
$inputData = json_decode(file_get_contents("php://input"), true);

foreach ($inputData as $Employeeslip) {
    $Employee = $Employeeslip['employeeId'];
    $numcutoff = $Employeeslip['numbercutoff'];
    $workingdays = $Employeeslip['worknumdays'];
    $cutoffmonth = $Employeeslip['monthcutoff'];
    $cutoffStart = $Employeeslip['cutoffstart'];
    $cutoffEnd = $Employeeslip['cutoffend'];
    $basicpayhours = $Employeeslip['basichours'];
    $basicpayamount = $Employeeslip['basicpay'];
    $OTtime = $Employeeslip['othours'];
    $OTpayamount = $Employeeslip['otpay'];
    $allowance = $Employeeslip['empAllowance'];
    $payleave = $Employeeslip['leavepay'];
    $paidholiday = $Employeeslip['holidayPay'];
    $ssscut = $Employeeslip['sssdeduct'];
    $philhealthcut = $Employeeslip['phildeduct'];
    $tincut = $Employeeslip['tindeduct'];
    $pagibigcut = $Employeeslip['pagibigdeduct'];
    $othercut = $Employeeslip['otherdeduct'];
    $latecut = $Employeeslip['latededuct'];
    $undertimecut = $Employeeslip['underdeduct'];
    $lwopcut = $Employeeslip['lwopdeduct'];
    $employeeNetpay = $Employeeslip['empnetpay'];
    $empTotalEarn = $Employeeslip['totalEarn'];
    $empTotaldeduct = $Employeeslip['totalDeduct'];

    $checkSlip = "SELECT * FROM payslip_report_tb WHERE `empid` = '$Employee' AND `cutoff_startdate` = '$cutoffStart' AND `cutoff_enddate` = '$cutoffEnd'";
    $slipRun = mysqli_query($conn, $checkSlip);

    if(mysqli_num_rows($slipRun) > 0){
        $response[] = array("status" => "error", "message" => "There's already existing data");
    }else{
        $insertQuery = "INSERT INTO payslip_report_tb(`empid`, `cutoff_month`, `cutoff_startdate`, `cutoff_enddate`, `cutoff_num`, `working_days`, `basic_hours`, `basic_amount_pay`, `overtime_hours`, `overtime_amount`, `allowances`, `paid_leaves`, `holiday_pay`, `total_earnings`, `sss_contri`, `philhealth_contri`, `tin_contri`, `pagibig_contri`, `other_contri`, `tardiness_deduct`, `undertime_deduct`, `lwop_deduct`, `total_deduction`, `net_pay`) VALUES ('$Employee', '$cutoffmonth', '$cutoffStart', '$cutoffEnd', '$numcutoff', '$workingdays', '$basicpayhours', '$basicpayamount', '$OTtime', '$OTpayamount', '$allowance', '$payleave', '$paidholiday', '$empTotalEarn', '$ssscut', '$philhealthcut', '$tincut', '$pagibigcut', '$othercut', '$latecut', '$undertimecut', '$lwopcut', '$empTotaldeduct', '$employeeNetpay')";

        $result = mysqli_query($conn, $insertQuery);
        
        if ($result) {
            // Success
            $response[] = array("status" => "success", "message" => "Data inserted for Employee ID: $Employee");
        } else {
            // Error
            $response[] = array("status" => "error", "message" => "Error inserting data for Employee ID: $Employee");
        }
    }
}

echo json_encode($response);

?>
