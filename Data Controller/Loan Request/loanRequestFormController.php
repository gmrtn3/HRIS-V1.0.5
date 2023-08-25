<?php

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


    

    $conn = new mysqli('localhost', 'root', '', 'hris_db');
        if($conn->connect_error){
    die('Connection Failed: ' .$conn->connect_error);
   }

   $stmt = $conn->prepare("INSERT INTO payroll_loan_tb(`empid`, `loan_type`, `year`, `month`, `cutoff_no`,`remarks`, `loan_date`,`payable_amount`,`amortization`,`applied_cutoff`, `loan_status`, `col_BAL_amount`)
                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
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
                            col_BAL_amount = VALUES(col_BAL_amount)");

    $stmt->bind_param("ssssssssssss", $empid, $loan_type, $year, $month, $cutoff_no, $remarks, $loan_date, $payable_amount,$amortization,$applied_cutoff,$loan_status,$payable_amount);
    $stmt->execute();
    header("Location: ../../loanRequest.php");
    $stmt->close();
    $conn->close();