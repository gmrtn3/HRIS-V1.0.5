<?php 
include 'config.php';

$pdfData = $_POST['pdfData'];
$employeeId = $_POST['employeeId'];
$Cutoff_Frequency = $_POST['Cutoff_Frequency'];
$Cutoff_Numbers = $_POST['Cutoff_Numbers'];
$employee_workdays = $_POST['employee_workdays'];
$cutoffId = $_POST['cutoffId'];

$decodedPdfData = base64_decode($pdfData);

date_default_timezone_set('Asia/Manila');
$currentDateTime = date('His');

$result_emp = mysqli_query($conn, "SELECT
                                        CONCAT(
                                            employee_tb.`fname`,
                                                ' ',
                                            employee_tb.`lname`
                                            ) AS `full_name`
                                            FROM 
                                            `employee_tb`
                                            WHERE `empid`=  '$employeeId'");
 $row_emp= mysqli_fetch_assoc($result_emp);

 $pdfFilePath = 'Payslip PDF/' . $row_emp['full_name'] . $currentDateTime . "_" . $Cutoff_Numbers . '.pdf';
 $file = fopen($pdfFilePath, 'wb'); // Open the file in write mode
 if ($file) {
  fwrite($file, $decodedPdfData);
  fclose($file);
  echo "Done"; // Send a success response
  } else {
    echo "Error writing the PDF file.";
  }

if ($Cutoff_Frequency === 'Monthly'){

}else if($Cutoff_Frequency === 'Semi-Month'){
  $first_cutOFf = '1';
  $last_cutoff ='2';
}
else if($Cutoff_Frequency === 'Weekly'){
  $first_cutOFf = '1';
  $last_cutoff ='4';
}

    if ($Cutoff_Frequency === 'Monthly')
    {
        //for every cutoff loan deductions
        $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$employeeId' AND `loan_status` != 'PAID' AND `status` = 'Approved'";
        $result = $conn->query($query);

        // Check if any rows are fetched
        if ($result->num_rows > 0) 
        {
        $loanArray = array(); // Array to store the dates

        // Loop through each row
        while($row = $result->fetch_assoc()) 
        {
        $loan_ID = $row["id"];
        $loan_payable = $row["payable_amount"];
        $loan_amortization = $row["amortization"];
        $loan_BAL = $row["col_BAL_amount"] ;

        $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
            
        } //end while

                    // Bind parameters and execute the statement for each loan
                    foreach ($loanArray as $loan_data) {
                        // Prepare the statement
                          $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                          $stmt = $conn->prepare($sql);
          
                          $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                          $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                          $stmt->execute();
          
                          if($diff_loan <= 0){
                            $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                            $stmt = $conn->prepare($sql);
          
                            $loan_stats = 'PAID';
                            $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                            $stmt->execute();
                          }  
                      }
                      $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                      $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
                      $stmt->execute();
          
                      echo 'Done';
    }else{
      $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
      $stmt->execute();
        echo 'Done';
    }
} else {
    if($Cutoff_Numbers === $first_cutOFf){
        $query = "SELECT * FROM payroll_loan_tb WHERE `empid` = '$employeeId' AND `loan_status` != 'PAID' AND `status` = 'Approved' AND (`applied_cutoff` = 'First Cutoff' OR `applied_cutoff` = 'Every Cutoff')";
        $result = $conn->query($query);

                  // Check if any rows are fetched
                  if ($result->num_rows > 0) {
                     $loanArray = array(); // Array to store the dates
            
                  // Loop through each row
                  while($row = $result->fetch_assoc()) 
                  {
                    $loan_ID = $row["id"];
                    $loan_payable = $row["payable_amount"];
                    $loan_amortization = $row["amortization"];
                    $loan_BAL = $row["col_BAL_amount"] ; 
                  
                    $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
                      
                  } //end while

                      foreach ($loanArray as $loan_data) {
                        // Prepare the statement
                          $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                          $stmt = $conn->prepare($sql);
        
                          $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                          $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                          $stmt->execute();
        
                          if($diff_loan <= 0){
                            $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                            $stmt = $conn->prepare($sql);
            
                            $loan_stats = 'PAID';
                            $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                            $stmt->execute();
                          }  
                      }

                        // Check if there's existing data in payslip_tb table
                        $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
                        $existingDataStmt = $conn->prepare($existingDataQuery);
                        $existingDataStmt->bind_param("sii", $employeeId, $Cutoff_Numbers, $cutoffId);
                        $existingDataStmt->execute();
                        $existingDataResult = $existingDataStmt->get_result();

                        if ($existingDataResult->num_rows > 0) {
                            echo 'Data already exists.';

                        } else {
                            // Insert data into payslip_tb table
                            $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
                            $stmt->execute();

                            echo 'Done';
                        }
                  } else {
                    // Check if there's existing data in payslip_tb table
                    $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
                    $existingDataStmt = $conn->prepare($existingDataQuery);
                    $existingDataStmt->bind_param("sii", $employeeId, $Cutoff_Numbers, $cutoffId);
                    $existingDataStmt->execute();
                    $existingDataResult = $existingDataStmt->get_result();

                    if ($existingDataResult->num_rows > 0) {
                        echo 'Data already exists.';

                    } else {
                        // Insert data into payslip_tb table
                        $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
                        $stmt->execute();

                        echo 'Done';
                    }
                  }
    } else if($Cutoff_Numbers === $first_cutOFf){
        $query = "SELECT * FROM payroll_loan_tb WHERE empid = $employeeId AND loan_status != 'PAID' AND `status` = 'Approved' AND (`applied_cutoff` = 'Last Cutoff' OR `applied_cutoff` = 'Every Cutoff')";
        $result = $conn->query($query);
    
        // Check if any rows are fetched
        if ($result->num_rows > 0) 
        {
          $loanArray = array(); // Array to store the dates
  
        // Loop through each row
        while($row = $result->fetch_assoc()) 
        {
          $loan_ID = $row["id"];
          $loan_payable = $row["payable_amount"];
          $loan_amortization = $row["amortization"];
          $loan_BAL = $row["col_BAL_amount"] ; 
        
          $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
            
        } //end while

            foreach ($loanArray as $loan_data) {
              // Prepare the statement
                $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);

                $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                $stmt->execute();

                if($diff_loan <= 0){
                  $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                  $stmt = $conn->prepare($sql);
  
                  $loan_stats = 'PAID';
                  $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                  $stmt->execute();
                }  
            }
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $employeeId, $Cutoff_Numbers, $cutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
                $stmt->execute();

                echo 'Done';
            }
        }else{
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $employeeId, $Cutoff_Numbers, $cutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
                $stmt->execute();

                echo 'Done';
            }
        }
    }else if($Cutoff_Numbers === '2' || $Cutoff_Numbers === '3'){
        $query = "SELECT * FROM payroll_loan_tb WHERE empid = $employeeId AND loan_status != 'PAID' AND `status` = 'Approved' AND `applied_cutoff` = 'Every Cutoff'";
        $result = $conn->query($query);
    
        // Check if any rows are fetched
        if ($result->num_rows > 0) 
        {
        
              $loanArray = array(); // Array to store the dates
        
            // Loop through each row
            while($row = $result->fetch_assoc()) 
            {
    
              $loan_ID = $row["id"];
              $loan_payable = $row["payable_amount"];
              $loan_amortization = $row["amortization"];
              $loan_BAL = $row["col_BAL_amount"] ; 
            
              $loanArray[] = array('ammortization' => $loan_amortization, 'loanID_tb' => $loan_ID, 'loan_balance' => $loan_BAL); 
                
            }
                foreach ($loanArray as $loan_data) {
                  // Prepare the statement
                    $sql = "UPDATE payroll_loan_tb SET col_BAL_amount = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
    
                    $diff_loan = ((int) $loan_data['loan_balance'] - (int) $loan_data['ammortization']);
                    $stmt->bind_param("ii", $diff_loan, $loan_data['loanID_tb']);
                    $stmt->execute();
    
                    if($diff_loan <= 0){
                      $sql = "UPDATE payroll_loan_tb SET loan_status = ? WHERE id = ?";
                      $stmt = $conn->prepare($sql);
      
                      $loan_stats = 'PAID';
                      $stmt->bind_param("si", $loan_stats, $loan_data['loanID_tb']);
                      $stmt->execute();
                    }  
    
    
                }
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $employeeId, $Cutoff_Numbers, $cutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
                $stmt->execute();

                echo 'Done';
            }
        }else{
            // Check if there's existing data in payslip_tb table
            $existingDataQuery = "SELECT * FROM payslip_tb WHERE col_empid = ? AND col_numDaysWork = ? AND col_cutoffID = ?";
            $existingDataStmt = $conn->prepare($existingDataQuery);
            $existingDataStmt->bind_param("sii", $employeeId, $Cutoff_Numbers, $cutoffId);
            $existingDataStmt->execute();
            $existingDataResult = $existingDataStmt->get_result();

            if ($existingDataResult->num_rows > 0) {
                echo 'Data already exists.';

            } else {
                // Insert data into payslip_tb table
                $stmt = $conn->prepare("INSERT INTO payslip_tb (col_Payslip_pdf, col_empid, col_cutoffID, col_numDaysWork) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssii", $pdfFilePath, $employeeId, $cutoffId, $employee_workdays);
                $stmt->execute();

                echo 'Done';
            }
        }
    }
}

?>