<?php

include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $company_code = $_POST['company_code'];
    $empid = $_POST['empid'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $cstatus = $_POST['cstatus'];
    $gender = $_POST['gender'];
    $empdob = $_POST['empdob'];
    $empdate_hired = $_POST['empdate_hired'];
    $empbranch = filter_input(INPUT_POST, 'empbranch', FILTER_SANITIZE_STRING);
    $classification = $_POST['classification'];
    $work_frequency = $_POST['work_frequency'];
    $empType = 'Piece Rate';
    $email = "N/A";

    // Check if empid already exists in employee_tb
    $checkStmt = $conn->prepare("SELECT empid FROM employee_tb WHERE empid = ?");
    $checkStmt->bind_param("s", $empid);
    $checkStmt->execute();
    $checkStmt->store_result();

    
    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Duplicate employee ID. Please enter a unique employee ID.');</script>";
        echo "<script>window.location.href = '../../empListForm';</script>";
        exit;
    }

    $checkStmt->close();
    $status = 'Active';
    // Insert into employee_tb table
    $stmt = $conn->prepare("INSERT INTO employee_tb (`fname`,`mname`, `lname`, `company_code`, `empid`, `address`, `contact`, `cstatus`, `gender`, `empdob`, `classification`, `empdate_hired`, `empbranch`, `status`, `work_frequency` , `role`, `email`)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssssss", $fname, $mname, $lname, $company_code, $empid, $address, $contact, $cstatus, $gender, $empdob, $classification, $empdate_hired, $empbranch, $status, $work_frequency , $empType, $email);

    $stmt->execute();

    if ($stmt->errno) {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
        echo "<script>window.location.href = '../../EmployeeList';</script>";
        exit;
    }

    $stmt->close();

        $cmpny_stmt = $conn->prepare("INSERT INTO assigned_company_code_tb(`empid`, `company_code_id`)
        VALUES (?,?)");

        if (!$cmpny_stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $cmpny_stmt->bind_param("ss", $empid, $company_code);

        $cmpny_stmt->execute();

        if ($cmpny_stmt->errno) {
        echo "<script>alert('Error: " . $cmpny_stmt->error . "');</script>";
        echo "<script>window.location.href = '../../empListForm';</script>";
        exit;
        }

        $cmpny_stmt->close();

    // Insert into approver_tb table
    $approverEmpIds = $_POST['approver'];

    foreach ($approverEmpIds as $approverEmpId) {
        $stmt2 = $conn->prepare("INSERT INTO approver_tb (`empid`, `approver_empid`)
                                VALUES (?, ?)");

        if (!$stmt2) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $stmt2->bind_param("ss", $empid, $approverEmpId);

        $stmt2->execute();

        if ($stmt2->errno) {
            echo "<script>alert('Error: " . $stmt2->error . "');</script>";
            echo "<script>window.location.href = '../../EmployeeList';</script>";
            exit;
        }

        $stmt2->close();
    }

    // Insert into employee_pakyawan_work_tb table
    $pieceRateIds = json_decode($_POST['piece_rate_id_hidden']);

    foreach ($pieceRateIds as $pieceRateId) {
        $stmt3 = $conn->prepare("INSERT INTO employee_pakyawan_work_tb (`empid`, `piece_rate_id`)
                                VALUES (?, ?)");

        if (!$stmt3) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $stmt3->bind_param("ss", $empid, $pieceRateId);

        $stmt3->execute();

        if ($stmt3->errno) {
            echo "<script>alert('Error: " . $stmt3->error . "');</script>";
            echo "<script>window.location.href = '../../EmployeeList';</script>";
            exit;
        }

        $stmt3->close();
    }

    echo "<script>alert('Data inserted successfully.');</script>";
    echo "<script>window.location.href = '../../EmployeeList';</script>";
}
