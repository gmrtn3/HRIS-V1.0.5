<?php
    // header('Content-Type: text/csv');
    // header('Content-Disposition: attachment; filename="employee-data.csv"');

    // $data = $_GET;

    // $output = fopen('php://output', 'w');

    // fputcsv($output, array_keys($data));

    // fputcsv($output, $data);

    // fclose($output);
    include '../../config.php';

    if (isset($_GET['employeeId']) && isset($_GET['minDate']) && isset($_GET['maxDate'])) {
        $employeeId = $_GET['employeeId'];
        $minDate = $_GET['minDate'];
        $maxDate = $_GET['maxDate'];
    
        $query = "SELECT `status`, `date`, `time_in`, `time_out`, `late`, `early_out`, `overtime`, `total_work` FROM attendances WHERE `empid` = '$employeeId' AND `date` BETWEEN '$minDate' AND '$maxDate'";
    
        $result = mysqli_query($conn, $query);
    
        // Create a temporary CSV file
        $filename = tempnam(sys_get_temp_dir(), 'attendance_');
        $output = fopen($filename, 'w');
    
        // Write CSV header
        fputcsv($output, array('Status', 'Date', 'Time In', 'Time Out', 'Late', 'Undertime', 'Overtime', 'Total Working Hours'));
    
        // Write data rows
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }
    
        fclose($output);
    
        // Set the appropriate headers for file download
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="employee-data.csv"');
    
        // Output the file content
        readfile($filename);
    
        // Delete the temporary file
        unlink($filename);
    
        exit;
    } else {
        // Handle the case when parameters are missing
        echo "Invalid request. Please provide employeeId, minDate, and maxDate.";
    }
    
    

?>