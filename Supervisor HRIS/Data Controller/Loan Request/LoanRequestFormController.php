<?php
include '../../config.php';
    $empid = $_POST['empid'];
    $loan_type = $_POST['loan_type'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $cutoff_no = $_POST['cutoff_no'];
    $remarks = $_POST['remarks'];
    $loan_date = $_POST['loan_date'];
    $payable_amount = $_POST['payable_amount'];
    $amortization = $_POST['amortization'];
    $applied_cutoff = $_POST['applied_cutoff'];
    $loan_status = $_POST['loan_status'];
    $status = $_POST['status'];

    



   $stmt = $conn->prepare("INSERT INTO payroll_loan_tb(`empid`, `loan_type`, `year`, `month`, `cutoff_no`,`remarks`, `loan_date`,`payable_amount`,`amortization`,`applied_cutoff`, `loan_status`, `col_BAL_amount`, `status`)
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
                            ON DUPLICATE KEY UPDATE
                            loan_type = VALUES(loan_type),
                            year = VALUES(year),
                            month = VALUES(month),
                            remarks = VALUES(remarks),
                            loan_date = VALUES(loan_date),
                            payable_amount = VALUES(payable_amount),
                            amortization = VALUES(amortization),
                            applied_cutoff = VALUES(applied_cutoff),
                            loan_status = VALUES(loan_status),
                            col_BAL_amount = VALUES(col_BAL_amount),
                            status = VALUES(status)
                            ");

    $stmt->bind_param("sssssssssssss", $empid, $loan_type, $year, $month, $cutoff_no, $remarks, $loan_date, $payable_amount,$amortization,$applied_cutoff,$loan_status,$payable_amount, $status);
    $stmt->execute();
    header("Location: ../../loanRequest.php");
    $stmt->close();
    $conn->close();