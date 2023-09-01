<?php
include 'config.php';

$inputData = json_decode(file_get_contents("php://input"), true);

$table_frequency = $inputData['table_frequency'];
$table_cutoffnum = $inputData['table_cutoffnum'];
$employeeID = $inputData['table_employeeId'];
$monthcutoff = $inputData['table_cutmonth'];
$cutoffStart = $inputData['table_cutoffstart'];
$cutoffEnd = $inputData['table_cutoffend'];
$empworkdays = $inputData['table_id_workdays'];
$basictotalwork = $inputData['table_basictotalwork'];
$basicpay = $inputData['table_basicempAmount'];
$othours = $inputData['table_othours'];
$otamount = $inputData['table_otamount'];
$allowance = $inputData['table_allowanceAmount'];
$paidleave = $inputData['table_leaveAmount'];
$paidHoliday = $inputData['table_holidayAmount'];
$emptotalEarn = $inputData['table_totalEarn'];
$ssscut = $inputData['table_deductSSS'];
$philcut = $inputData['table_deductphil'];
$tincut = $inputData['table_deductTIN'];
$pagibigcut = $inputData['table_deductPagibig'];
$othercut = $inputData['table_deductOther'];
$latecut = $inputData['table_deductLate'];
$Utcut = $inputData['table_deductUT'];
$lwopcut = $inputData['table_deductLWOP'];
$totaldeduct = $inputData['table_totalDeduction'];
$Netpayslip = $inputData['table_netpayslip'];
$cutoffId = $inputData['table_cutoff_id'];

$checkSlip = "SELECT * FROM payslip_report_tb WHERE `empid` = '$employeeID' AND `cutoff_startdate` = '$cutoffStart' AND `cutoff_enddate` = '$cutoffEnd'";
$slipRun = mysqli_query($conn, $checkSlip);

if(mysqli_num_rows($slipRun) > 0){
    $response[] = array("status" => "error", "message" => "There's already existing data");
}else{
    $insertQuery = "INSERT INTO payslip_report_tb(`cutoff_ID`, `empid`, `cutoff_month`, `cutoff_startdate`, `cutoff_enddate`, `cutoff_num`, `working_days`, `basic_hours`, `basic_amount_pay`, `overtime_hours`, `overtime_amount`, `allowances`, `paid_leaves`, `holiday_pay`, `total_earnings`, `sss_contri`, `philhealth_contri`, `tin_contri`, `pagibig_contri`, `other_contri`, `tardiness_deduct`, `undertime_deduct`, `lwop_deduct`, `total_deduction`, `net_pay`) VALUES ('$cutoffId', '$employeeID', '$monthcutoff', '$cutoffStart', '$cutoffEnd', '$table_cutoffnum', '$empworkdays', '$basictotalwork', '$basicpay', '$othours', '$otamount', '$allowance', '$paidleave', '$paidHoliday', '$emptotalEarn', '$ssscut', '$philcut', '$tincut', '$pagibigcut', '$othercut', '$latecut', '$Utcut', '$lwopcut', '$totaldeduct', '$Netpayslip')";

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