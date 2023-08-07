<?php
// Load the database configuration file
include_once 'db_Config.php';

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
    'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $employee_id = $line[0];
                $name  = $line[1];
                $department  = $line[2];
                $schedule_type = $line[3];
                $time_entry = $line[4];
                $time_out = $line[5];
                $total_hours = '';
                $tardiness = '';
                $undertime = '';
                $overtime = '';

                // Check if the employee is late
                if($time_entry > '09:00:00'){
                    // Calculate the amount of late
                    $time_entry_datetime = new DateTime($time_entry);
                    $scheduled_time = new DateTime('09:00:00');
                    $interval = $time_entry_datetime->diff($scheduled_time);
                    $tardiness = $interval->format('%h:%i:%s');
                }
                
                // Calculate the total work hours
                $total_hours = strtotime($time_out) - strtotime($time_entry) - 7200;
                $total_hours = date('H:i:s', $total_hours);

                if ($time_out > '18:00:00') {
                    // Calculate overtime
                    $total_hours_time = new DateTime($total_hours);
                    $scheduled_times = new DateTime('08:00:00');
                    $intervals = $total_hours_time->diff($scheduled_times);
                    $overtime = $intervals->format('%h:%i:%s');

                } else {
                    $overtime = '00:00:00';
                }

                if($time_out < '17:59:00'){
                    $time_out_datetime = new DateTime($time_out);
                    $scheduled_out = new DateTime($total_hours);
                    $early_interval = $time_entry_datetime->diff($scheduled_out);
                    $undertime = $early_interval->format('%h:%i:%s');
                } else { 
                    $undertime = '00:00:00';
                }
                
                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT id FROM daily_time_records_tb WHERE employee_id = '".$line[1]."'";
                $prevResult = $conn->query($prevQuery);
                
                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $conn->query("UPDATE daily_time_records_tb SET employee_id = '".$employee_id."', name = '".$name."', department = '".$department."', schedule_type = '".$schedule_type."', 
                    time_entry = '".$time_entry."', time_out = '".$time_out."', total_hours = '".$total_hours."', tardiness = '".$tardiness."',
                    undertime = '".$undertime."', overtime = '".$overtime."', modified = NOW() WHERE employee_id = '".$employee_id."'");
                }else{
                    // Insert member data in the database
                    $conn->query("INSERT INTO daily_time_records_tb (employee_id, name, department, schedule_type, time_entry, time_out, total_hours, tardiness, undertime, overtime) 
                    VALUES ('".$employee_id."', '".$name."', '".$department."', '".$schedule_type."', '".$time_entry."', '".$time_out."', '".$total_hours."',
                    '".$tardiness."', '".$undertime."', '".$overtime."')");
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: daily.php".$qstring);
