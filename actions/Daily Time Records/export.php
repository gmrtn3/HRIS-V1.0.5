<?php 
 
// Load the database configuration file 
$conn = mysqli_connect("localhost","root", "","hris_db");

if(!$conn){
    echo '<script type="text/javascript">';
    echo 'alert("Connection Failed.");';
    echo '</script>';
    die;
}
 

// Fetch records from database 
$query = $conn->query("SELECT * FROM daily_time_records_tb ORDER BY id ASC"); 
 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "DTR-Report-" . date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array('EMPLOYEE_ID', 'NAME', 'DEPARMENT', 'SCHEDULE TYPE', 'TIME ENTRY', 'TIME OUT', 'TOTAL HOURS', 'TARDINESS', 'UNDERTIME', 'OVERTIME',); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 
        $lineData = array($row['employee_id'], $row['name'], $row['department'], $row['schedule_type'], $row['time_entry'], $row['time_out'], $row['total_hours'], $row['tardiness'], $row['undertime'], $row['overtime']); 
        fputcsv($f, $lineData, $delimiter); 
    } 
     
    // Move back to beginning of file 
    fseek($f, 0); 
     
    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
} 
exit; 