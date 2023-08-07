<?php
include '../../config.php';

$conn = mysqli_connect($server, $user, $pass, $database);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $work_frequency = $_POST['work_frequency'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $employee = $_POST["employee"];
    $unit_type = $_POST["unit_type"];
    $unit_work = $_POST["unit_work"];

    // Validate if there are conflicting records with the same work_frequency, employee, unit_type, and unit_work
    $existingSql = "SELECT * FROM pakyawan_based_work_tb WHERE 
                    work_frequency = '$work_frequency' AND 
                    employee = '$employee' AND 
                    unit_type = '$unit_type' AND 
                    unit_work = '$unit_work' AND 
                    ((start_date >= '$start_date' AND start_date <= '$end_date') OR 
                    (end_date >= '$start_date' AND end_date <= '$end_date') OR 
                    (start_date <= '$start_date' AND end_date >= '$end_date'))";
    $existingQuery = mysqli_query($conn, $existingSql);

    if (mysqli_num_rows($existingQuery) > 0) {
        header("Location: ../../pakyawan_work?validationFailed=1");
        exit;
    }

    // Validate if the start_date is within the range of any existing records
    $startInRangeSql = "SELECT * FROM pakyawan_based_work_tb WHERE 
                        employee = '$employee' AND 
                        unit_type = '$unit_type' AND 
                        unit_work = '$unit_work' AND 
                        start_date <= '$start_date' AND 
                        end_date >= '$start_date'";
    $startInRangeQuery = mysqli_query($conn, $startInRangeSql);

    if (mysqli_num_rows($startInRangeQuery) > 0) {
        header("Location: ../../pakyawan_work?validationFailed=1");
        exit;
    }

    // Insert the data into the pakyawan_based_work_tb table
    $sql = "INSERT INTO pakyawan_based_work_tb (work_frequency, start_date, end_date, employee, unit_type, unit_work ) VALUES ('$work_frequency','$start_date','$end_date','$employee', '$unit_type' ,'$unit_work')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../pakyawan_work");
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
