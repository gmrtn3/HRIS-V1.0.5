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
    $GovernmentTotal = $Employeeslip['GovernBenefit'];
    $latecut = $Employeeslip['latededuct'];
    $undertimecut = $Employeeslip['underdeduct'];
    $lwopcut = $Employeeslip['lwopdeduct'];
    $employeeNetpay = $Employeeslip['empnetpay'];
    $empTotalEarn = $Employeeslip['totalEarn'];
    $empTotaldeduct = $Employeeslip['totalDeduct'];
    $CutoffiD = $Employeeslip['cutoffId'];
    $Frequency = $Employeeslip['frequency'];
    $Absences = $Employeeslip['absences'];
    $Payrule = $Employeeslip['payrule'];
    $Leaves = $Employeeslip['leavecount'];
    $transportation = $Employeeslip['transpoallow'];
    $meals = $Employeeslip['mealallow'];
    $internet = $Employeeslip['netallowance'];
    $addallowance = $Employeeslip['otherallow'];
    $deductOf_absence = $Employeeslip['absdeductions'];
    $deductsOf_late = $Employeeslip['latedeductions'];
    $timeOf_UT = $Employeeslip['utHours'];
    $countOf_lwop = $Employeeslip['lwopcount'];

    $checkSlip = "SELECT * FROM payslip_report_tb WHERE `empid` = '$Employee' AND `cutoff_startdate` = '$cutoffStart' AND `cutoff_enddate` = '$cutoffEnd'";
    $slipRun = mysqli_query($conn, $checkSlip);

    if(mysqli_num_rows($slipRun) > 0){
        $response[] = array("status" => "error", "message" => "There's already existing data");
    }else{
        $insertQuery = "INSERT INTO payslip_report_tb(`cutoff_ID`, `pay_rule`, `empid`, `col_frequency`, `cutoff_month`, `cutoff_startdate`, `cutoff_enddate`, `cutoff_num`, `working_days`, `basic_hours`, `basic_amount_pay`, `overtime_hours`, `overtime_amount`, `transpo_allow`, `meal_allow`, `net_allowance`, `add_allow`, `allowances`, `number_leave`, `paid_leaves`, `holiday_pay`, `total_earnings`, `absence`, `absence_deduction`, `sss_contri`, `philhealth_contri`, `tin_contri`, `pagibig_contri`, `other_contri`, `totalGovern_tb`, `total_late`, `tardiness_deduct`, `ut_time`, `undertime_deduct`, `number_lwop`, `lwop_deduct`, `total_deduction`, `net_pay`) VALUES ('$CutoffiD', '$Payrule', '$Employee', '$Frequency', '$cutoffmonth', '$cutoffStart', '$cutoffEnd', '$numcutoff', '$workingdays', '$basicpayhours', '$basicpayamount', '$OTtime', '$OTpayamount', '$transportation', '$meals', '$internet', '$addallowance', '$allowance', '$Leaves', '$payleave', '$paidholiday', '$empTotalEarn', '$Absences', '$deductOf_absence', '$ssscut', '$philhealthcut', '$tincut', '$pagibigcut', '$othercut', '$GovernmentTotal', '$deductsOf_late', '$latecut', '$timeOf_UT', '$undertimecut', '$countOf_lwop', '$lwopcut', '$empTotaldeduct', '$employeeNetpay')";

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
