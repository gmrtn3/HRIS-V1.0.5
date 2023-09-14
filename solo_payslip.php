<?php
include 'config.php';

$inputData = json_decode(file_get_contents("php://input"), true);
$cutoffId = $inputData['table_cutoff_id'];
$Payrule = $inputData['table_pay_rule'];
$employeeID = $inputData['table_employeeId'];
$table_frequency = $inputData['table_frequency'];
$monthcutoff = $inputData['table_cutmonth'];
$cutoffStart = $inputData['table_cutoffstart'];
$cutoffEnd = $inputData['table_cutoffend'];
$table_cutoffnum = $inputData['table_cutoffnum'];
$empworkdays = $inputData['table_id_workdays'];
$basictotalwork = $inputData['table_basictotalwork'];
$basicpay = $inputData['table_basicempAmount'];
$othours = $inputData['table_othours'];
$otamount = $inputData['table_otamount'];
$transportation = $inputData['table_transport'];
$mealsallow = $inputData['table_meals'];
$Internetallow = $inputData['table_internett'];
$addallowance = $inputData['table_otherAllowance'];
$allowance = $inputData['table_allowanceAmount'];
$leaveNumber = $inputData['table_leave_number'];
$paidleave = $inputData['table_leaveAmount'];
$paidHoliday = $inputData['table_holidayAmount'];
$emptotalEarn = $inputData['table_totalEarn'];
$totalAbsent = $inputData['table_absentnumber'];
$absenceDeduction = $inputData['table_absentdeducts'];
$ssscut = $inputData['table_deductSSS'];
$philcut = $inputData['table_deductphil'];
$tincut = $inputData['table_deductTIN'];
$pagibigcut = $inputData['table_deductPagibig'];
$othercut = $inputData['table_deductOther'];
$totalGovernment = $inputData['table_governmenttotal'];
$numberLate = $inputData['table_countLate'];
$latecut = $inputData['table_deductLate'];
$undertimeHours = $inputData['table_countUT'];
$Utcut = $inputData['table_deductUT'];
$lwopNumber = $inputData['table_numberLWOP'];
$lwopcut = $inputData['table_deductLWOP'];
$totaldeduct = $inputData['table_totalDeduction'];
$Netpayslip = $inputData['table_netpayslip'];


$checkSlip = "SELECT * FROM payslip_report_tb WHERE `empid` = '$employeeID' AND `cutoff_startdate` = '$cutoffStart' AND `cutoff_enddate` = '$cutoffEnd'";
$slipRun = mysqli_query($conn, $checkSlip);

if(mysqli_num_rows($slipRun) > 0){
    $response[] = array("status" => "error", "message" => "There's already existing data");
}else{
    $insertQuery ="INSERT INTO payslip_report_tb(`cutoff_ID`, `pay_rule`, `empid`, `col_frequency`, `cutoff_month`, `cutoff_startdate`, `cutoff_enddate`, `cutoff_num`, `working_days`, `basic_hours`, `basic_amount_pay`, `overtime_hours`, `overtime_amount`, `transpo_allow`, `meal_allow`, `net_allowance`, `add_allow`, `allowances`, `number_leave`, `paid_leaves`, `holiday_pay`, `total_earnings`, `absence`, `absence_deduction`, `sss_contri`, `philhealth_contri`, `tin_contri`, `pagibig_contri`, `other_contri`, `totalGovern_tb`, `total_late`, `tardiness_deduct`, `ut_time`, `undertime_deduct`, `number_lwop`, `lwop_deduct`, `total_deduction`, `net_pay`) VALUES ('$cutoffId', '$Payrule', '$employeeID', '$table_frequency', '$monthcutoff', '$cutoffStart', '$cutoffEnd', '$table_cutoffnum', '$empworkdays', '$basictotalwork', '$basicpay', '$othours', '$otamount', '$transportation', '$mealsallow', '$Internetallow', '$addallowance', '$allowance', '$leaveNumber', '$paidleave', '$paidHoliday', '$emptotalEarn', '$totalAbsent', '$absenceDeduction', '$ssscut', '$philcut', '$tincut', '$pagibigcut', '$othercut', '$totalGovernment', '$numberLate', '$latecut', '$undertimeHours', '$Utcut', '$lwopNumber', '$lwopcut', '$totaldeduct', '$Netpayslip')";

    $result = mysqli_query($conn, $insertQuery);
    
    if ($result) {
        // Success
        $response[] = array("status" => "success", "message" => "Data inserted for Employee ID: $Employee");
    } else {
        // Error
        $response[] = array("status" => "error", "message" => "Error inserting data for Employee ID: $Employee");
    }
}

echo json_encode($response);

?>