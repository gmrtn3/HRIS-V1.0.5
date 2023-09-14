<?php
include '../../config.php';
// Include the config.php file here

if (isset($_POST['cutoffID']) && isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $cutoffID = $_POST['cutoffID'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $Getprb = "SELECT payslip_report_tb.id,
    payslip_report_tb.cutoff_ID,
    payslip_report_tb.pay_rule,
    payslip_report_tb.empid,
    payslip_report_tb.col_frequency,
    payslip_report_tb.cutoff_startdate, 
    payslip_report_tb.cutoff_enddate, 
    payslip_report_tb.working_days, 
    payslip_report_tb.basic_hours, 
    payslip_report_tb.basic_amount_pay, 
    payslip_report_tb.overtime_hours, 
    payslip_report_tb.overtime_amount, 
    payslip_report_tb.transpo_allow, 
    payslip_report_tb.meal_allow, 
    payslip_report_tb.net_allowance, 
    payslip_report_tb.add_allow, 
    payslip_report_tb.allowances, 
    payslip_report_tb.number_leave, 
    payslip_report_tb.paid_leaves, 
    payslip_report_tb.holiday_pay, 
    payslip_report_tb.total_earnings, 
    payslip_report_tb.absence, 
    payslip_report_tb.absence_deduction, 
    payslip_report_tb.sss_contri, 
    payslip_report_tb.philhealth_contri, 
    payslip_report_tb.tin_contri, 
    payslip_report_tb.pagibig_contri, 
    payslip_report_tb.other_contri, 
    payslip_report_tb.total_late, 
    payslip_report_tb.tardiness_deduct, 
    payslip_report_tb.ut_time, 
    payslip_report_tb.undertime_deduct, 
    payslip_report_tb.number_lwop, 
    payslip_report_tb.lwop_deduct, 
    payslip_report_tb.total_deduction, 
    payslip_report_tb.net_pay,
    payslip_report_tb.date_time,
    employee_tb.empid,
    CONCAT(employee_tb.fname, ' ', employee_tb.lname) AS full_name FROM payslip_report_tb INNER JOIN
    employee_tb ON employee_tb.empid = payslip_report_tb.empid WHERE cutoff_ID = '$cutoffID' AND cutoff_startdate = '$startDate' AND cutoff_enddate = '$endDate'";
    $query_run = mysqli_query($conn, $Getprb);

    // Create a file handle for writing to a CSV file
    $csvFileName = "pay_report.csv";
    $csvFile = fopen($csvFileName, 'w');

    // Write the CSV header row
    $csvHeader = array(
        "Employee ID",
        "Name",
        "Total Earnings",
        "Total Deduction",
        "Salary Final",
        "Total Days",
        "Total Hours",
        "Overtime Hours",
        "Overtime Pay",
        "Transport",
        "Meal",
        "Internet",
        "Other",
        "Leave Pay",
        "Holiday Pay",
        "Absent",
        "Absent Deduction",
        "Late",
        "Late Deduction",
        "Undertime",
        "Undertime Deduction",
        "LWOP Deduction",
        "SSS",
        "Philhealth",
        "TIN",
        "Pag-ibig",
        "Other Government"
    );
    fputcsv($csvFile, $csvHeader);

    // Loop through the database results and write each row to the CSV file
    while ($row = mysqli_fetch_assoc($query_run)) {
        $csvRow = array(
            $row['empid'],
            $row['full_name'],
            number_format($row['total_earnings'], 2),
            number_format($row['total_deduction'], 2),
            $row['net_pay'],
            $row['working_days'],
            $row['basic_hours'],
            $row['overtime_hours'],
            $row['overtime_amount'],
            $row['transpo_allow'],
            $row['meal_allow'],
            $row['net_allowance'],
            $row['add_allow'],
            $row['paid_leaves'],
            $row['holiday_pay'],
            $row['absence'],
            $row['absence_deduction'],
            $row['total_late'],
            $row['tardiness_deduct'],
            $row['ut_time'],
            $row['undertime_deduct'],
            $row['lwop_deduct'],
            $row['sss_contri'],
            $row['philhealth_contri'],
            $row['tin_contri'],
            $row['pagibig_contri'],
            $row['other_contri']
        );
        fputcsv($csvFile, $csvRow);
    }

    // Close the CSV file
    fclose($csvFile);

    // Force the download of the CSV file
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename=' . $csvFileName);
    readfile($csvFileName);
    exit;
} else {
    // Handle the case where data is not provided
    echo "Data is missing.";
}
?>

