<?php 
 
// Load the database configuration file 
$server = "localhost";
$user = "root";
$pass ="";
$database = "hris_db";

$db = mysqli_connect($server, $user, $pass, $database);

if(!$db){
    echo '<script type="text/javascript">';
    echo 'alert("Connection Failed.");';
    echo '</script>';
    die;
}
 

// Fetch records from database 
$query = $db->query("SELECT * FROM attendances ORDER BY id ASC"); 
 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "attendance-data_" . date('Y-m-d') . ".csv"; 
     
    // Create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    // Set column headers 
    $fields = array('STATUS', 'EMPLOYEE ID', 'DATE', 'TIME IN', 'TIME OUT', 'LATE', 'EARLY OUT', 'OVERTIME', 'TOTAL WORK', 'TOTAL REST'); 
    fputcsv($f, $fields, $delimiter); 
     
    // Output each row of the data, format line as csv and write to file pointer 
    while($row = $query->fetch_assoc()){ 
        $lineData = array($row['status'], $row['empid'], $row['date'], $row['time_in'], $row['time_out'], $row['late'], $row['early_out'], $row['overtime'], $row['total_work'], $row['total_rest']); 
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
